<?php
require_once(__DIR__ . '/session_config.php');
include('dbconnect.php');

/**
 * Blood bank stages 3-5: screening, component separation, stock and issue.
 *
 * Stage 1 (donor lifecycle) lives in bloodDonorClass.php and stage 2
 * (collection) in controller/processBloodCollection.php. Collection leaves a
 * unit at 'Collected_Unscreened' with is_usable = 0; everything that moves a
 * unit past that point is here.
 *
 * Unit lifecycle:
 *   Collected_Unscreened -> Under_Testing -> Testing_Passed
 *                                        -> Released_To_Inventory   (usable)
 *                                        -> Reserved -> Issued -> Transfused
 *                        -> Testing_Failed / Quarantined / Discarded / Expired
 *   A whole-blood unit that is split becomes 'Separated' and its children enter
 *   inventory in its place.
 *
 * Tables: sql/blood_bank_flow.sql
 */
class addBlood
{
    private function db()
    {
        $newlogin = new userlogin;
        return $newlogin->dbconnect();
    }

    /** Current user's display name, for the *_by audit columns. */
    private function actor()
    {
        return isset($_SESSION['name']) ? $_SESSION['name'] : 'SYSTEM';
    }

    // =====================================================================
    // Collections
    // =====================================================================

    /**
     * Collection events with their unit and donor, newest first.
     * $status filters on the unit's lifecycle state.
     */
    public function getCollections($from = '', $to = '', $status = '')
    {
        $db = $this->db();
        $cond = '';
        $types = '';
        $args = array();

        if ($from !== '' && $to !== '') {
            $cond .= " AND c.collection_date BETWEEN ? AND ?";
            $types .= 'ss';
            $args[] = $from;
            $args[] = $to;
        }
        if ($status !== '') {
            $cond .= " AND u.unit_status = ?";
            $types .= 's';
            $args[] = $status;
        }

        $sql = "SELECT c.collection_id, c.collection_number, c.collection_date, c.collection_time,
                       c.collection_location, c.camp_location, c.volume_collected_ml,
                       c.collected_by_name, c.collection_status, c.adverse_reaction_flag,
                       u.unit_id, u.unit_barcode, u.bag_number, u.blood_group, u.component_type,
                       u.unit_status, u.is_usable, u.is_available_for_issue, u.expiry_date,
                       d.donor_unique_id,
                       CONCAT(d.first_name, ' ', COALESCE(d.last_name, '')) AS donor_name,
                       d.mobile_number,
                       t.overall_result AS testing_result
                  FROM blood_collections c
                  LEFT JOIN blood_units u        ON u.collection_id = c.collection_id
                                                AND u.parent_unit_id IS NULL
                  LEFT JOIN blood_donor_master d ON d.donor_id = c.donor_id
                  LEFT JOIN blood_unit_testing t ON t.unit_id = u.unit_id
                 WHERE c.is_active = 1 $cond
                 ORDER BY c.collection_date DESC, c.collection_id DESC";

        $st = $db->prepare($sql);
        if ($args) {
            $st->bind_param($types, ...$args);
        }
        $st->execute();
        return $st->get_result();
    }

    // =====================================================================
    // Stage 3 — TTI screening
    // =====================================================================

    /** Units still awaiting screening, oldest collection first (FIFO). */
    public function getUnitsPendingTesting()
    {
        $db = $this->db();
        $sql = "SELECT u.*, d.donor_unique_id,
                       CONCAT(d.first_name, ' ', COALESCE(d.last_name, '')) AS donor_name,
                       c.collection_number
                  FROM blood_units u
                  LEFT JOIN blood_donor_master d ON d.donor_id = u.donor_id
                  LEFT JOIN blood_collections c  ON c.collection_id = u.collection_id
                 WHERE u.unit_status IN ('Collected_Unscreened','Under_Testing')
                 ORDER BY u.collection_date ASC, u.unit_id ASC";
        return mysqli_query($db, $sql);
    }

    /** One unit with its screening record, if any. */
    public function getUnit($unit_id)
    {
        $db = $this->db();
        // The testing columns are listed one by one on purpose. `t.*` would also
        // bring across t.unit_id and t.unit_barcode, and in an associative fetch
        // the later column wins — so a component unit (which has no screening row
        // of its own) came back with unit_barcode = NULL and every downstream
        // insert that used it failed.
        $sql = "SELECT u.*, d.donor_unique_id,
                       CONCAT(d.first_name, ' ', COALESCE(d.last_name, '')) AS donor_name,
                       d.mobile_number, c.collection_number, c.collection_date AS coll_date,
                       t.hiv_result, t.hbsag_result, t.hcv_result, t.syphilis_result,
                       t.malaria_result, t.test_method, t.kit_lot_number,
                       t.abo_group_confirmed, t.rh_factor_confirmed, t.grouping_matches,
                       t.overall_result, t.failure_reason, t.tested_by, t.tested_date,
                       t.remarks
                  FROM blood_units u
                  LEFT JOIN blood_donor_master d ON d.donor_id = u.donor_id
                  LEFT JOIN blood_collections c  ON c.collection_id = u.collection_id
                  LEFT JOIN blood_unit_testing t ON t.unit_id = u.unit_id
                 WHERE u.unit_id = ? LIMIT 1";
        $st = $db->prepare($sql);
        $st->bind_param('i', $unit_id);
        $st->execute();
        return $st->get_result()->fetch_assoc();
    }

    /**
     * Record TTI screening for a unit and move it on.
     *
     * A unit passes only when all five markers are Non_Reactive AND the
     * re-grouped ABO/Rh matches what collection recorded. Anything else fails
     * the unit — a reactive marker or a grouping mismatch both mean the bag
     * must never reach a patient.
     *
     * $r keys: hiv, hbsag, hcv, syphilis, malaria, method, kit_lot,
     *          abo_confirmed, rh_confirmed, remarks
     */
    public function saveTesting($unit_id, array $r)
    {
        $db = $this->db();
        $unit_id = (int) $unit_id;

        $unit = $this->getUnit($unit_id);
        if (!$unit) {
            return array('ok' => false, 'error' => 'Unit not found.');
        }
        if (in_array($unit['unit_status'], array('Issued', 'Transfused', 'Discarded', 'Separated'), true)) {
            return array('ok' => false, 'error' => 'This unit has already left the screening stage.');
        }

        $markers = array('hiv', 'hbsag', 'hcv', 'syphilis', 'malaria');
        $vals = array();
        foreach ($markers as $m) {
            $v = isset($r[$m]) ? $r[$m] : 'Pending';
            if (!in_array($v, array('Pending', 'Non_Reactive', 'Reactive'), true)) {
                $v = 'Pending';
            }
            $vals[$m] = $v;
        }

        $reactive = array();
        $pending  = false;
        foreach ($markers as $m) {
            if ($vals[$m] === 'Reactive') { $reactive[] = strtoupper($m); }
            if ($vals[$m] === 'Pending')  { $pending = true; }
        }

        $abo = isset($r['abo_confirmed']) ? trim((string) $r['abo_confirmed']) : '';
        $rh  = isset($r['rh_confirmed'])  ? trim((string) $r['rh_confirmed'])  : '';
        // Only judge the grouping once it has actually been entered.
        $groupingMatches = 1;
        if ($abo !== '' && $abo !== $unit['blood_group']) { $groupingMatches = 0; }
        if ($rh  !== '' && $rh  !== $unit['rh_factor'])   { $groupingMatches = 0; }

        if ($reactive) {
            $overall = 'Failed';
            $reason  = 'Reactive: ' . implode(', ', $reactive);
        } elseif (!$groupingMatches) {
            $overall = 'Failed';
            $reason  = 'ABO/Rh mismatch against the collection record';
        } elseif ($pending || $abo === '' || $rh === '') {
            $overall = 'Pending';
            $reason  = null;
        } else {
            $overall = 'Passed';
            $reason  = null;
        }

        $method  = isset($r['method']) && $r['method'] !== '' ? $r['method'] : 'ELISA';
        $kit     = isset($r['kit_lot']) ? $r['kit_lot'] : '';
        $remarks = isset($r['remarks']) ? $r['remarks'] : '';
        $actor   = $this->actor();

        mysqli_begin_transaction($db);
        try {
            // One screening record per unit; a re-test overwrites it.
            $sql = "INSERT INTO blood_unit_testing
                      (unit_id, unit_barcode, hiv_result, hbsag_result, hcv_result,
                       syphilis_result, malaria_result, test_method, kit_lot_number,
                       abo_group_confirmed, rh_factor_confirmed, grouping_matches,
                       overall_result, failure_reason, tested_by, remarks)
                    VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)
                    ON DUPLICATE KEY UPDATE
                       hiv_result=VALUES(hiv_result), hbsag_result=VALUES(hbsag_result),
                       hcv_result=VALUES(hcv_result), syphilis_result=VALUES(syphilis_result),
                       malaria_result=VALUES(malaria_result), test_method=VALUES(test_method),
                       kit_lot_number=VALUES(kit_lot_number),
                       abo_group_confirmed=VALUES(abo_group_confirmed),
                       rh_factor_confirmed=VALUES(rh_factor_confirmed),
                       grouping_matches=VALUES(grouping_matches),
                       overall_result=VALUES(overall_result),
                       failure_reason=VALUES(failure_reason),
                       tested_by=VALUES(tested_by), tested_date=NOW(), remarks=VALUES(remarks)";
            $st = $db->prepare($sql);
            $rhVal = ($rh === '') ? null : $rh;
            $aboVal = ($abo === '') ? null : $abo;
            $st->bind_param(
                'issssssssssisiss',
                $unit_id, $unit['unit_barcode'],
                $vals['hiv'], $vals['hbsag'], $vals['hcv'], $vals['syphilis'], $vals['malaria'],
                $method, $kit, $aboVal, $rhVal, $groupingMatches,
                $overall, $reason, $actor, $remarks
            );
            $st->execute();
            $testing_id = $st->insert_id ?: (int) $unit['testing_id'];
            $st->close();

            // Move the unit to match the verdict.
            if ($overall === 'Passed') {
                $status = 'Released_To_Inventory';
                $usable = 1; $available = 1; $screening = 'Completed';
            } elseif ($overall === 'Failed') {
                $status = 'Testing_Failed';
                $usable = 0; $available = 0; $screening = 'Failed';
            } else {
                $status = 'Under_Testing';
                $usable = 0; $available = 0; $screening = 'In_Progress';
            }

            $up = "UPDATE blood_units
                      SET unit_status = ?, is_usable = ?, is_available_for_issue = ?,
                          screening_status = ?, testing_id = ?, modified_date = NOW()
                    WHERE unit_id = ?";
            $st = $db->prepare($up);
            $st->bind_param('siisii', $status, $usable, $available, $screening, $testing_id, $unit_id);
            $st->execute();
            $st->close();

            // A failed unit is discarded rather than left in limbo.
            if ($overall === 'Failed') {
                $this->discardUnit($unit_id, $reason, $db);
            }

            mysqli_commit($db);
            return array('ok' => true, 'result' => $overall, 'status' => $status, 'reason' => $reason);
        } catch (Exception $e) {
            mysqli_rollback($db);
            return array('ok' => false, 'error' => $e->getMessage());
        }
    }

    /** Take a unit out of circulation. Reuses an open transaction when given one. */
    public function discardUnit($unit_id, $reason, $db = null)
    {
        $own = ($db === null);
        if ($own) { $db = $this->db(); }
        $unit_id = (int) $unit_id;
        $actor = $this->actor();

        $sql = "UPDATE blood_units
                   SET unit_status = 'Discarded', is_usable = 0, is_available_for_issue = 0,
                       discard_reason = ?, discarded_date = CURDATE(), modified_date = NOW()
                 WHERE unit_id = ?";
        $st = $db->prepare($sql);
        $st->bind_param('si', $reason, $unit_id);
        $ok = $st->execute();
        $st->close();
        return $ok;
    }

    // =====================================================================
    // Stage 4 — Component separation
    // =====================================================================

    /** Whole-blood units that passed screening and can still be split. */
    public function getUnitsForSeparation()
    {
        $db = $this->db();
        $sql = "SELECT u.*, d.donor_unique_id,
                       CONCAT(d.first_name, ' ', COALESCE(d.last_name, '')) AS donor_name,
                       c.collection_number
                  FROM blood_units u
                  LEFT JOIN blood_donor_master d ON d.donor_id = u.donor_id
                  LEFT JOIN blood_collections c  ON c.collection_id = u.collection_id
                 WHERE u.unit_status = 'Released_To_Inventory'
                   AND u.component_type = 'Whole_Blood'
                   AND u.parent_unit_id IS NULL
                   AND u.expiry_date >= CURDATE()
                 ORDER BY u.collection_date ASC";
        return mysqli_query($db, $sql);
    }

    /** Shelf life / storage reference, keyed by component type. */
    public function getComponentTypes()
    {
        $db = $this->db();
        $res = mysqli_query($db, "SELECT * FROM blood_component_shelf_life_ref WHERE is_active = 1 ORDER BY component_id");
        $out = array();
        while ($row = mysqli_fetch_assoc($res)) {
            $out[$row['component_type']] = $row;
        }
        return $out;
    }

    /**
     * Split a screened whole-blood unit into components.
     *
     * Children inherit the parent's group and donor, and carry parent_unit_id so
     * a transfusion reaction can always be traced back to the donation. Each
     * child's expiry runs from the ORIGINAL collection date, not today — the
     * blood is as old as its donation regardless of when it was separated.
     *
     * $components: list of component_type strings.
     */
    public function separateComponents($parent_unit_id, array $components, $method = 'Centrifugation', $remarks = '')
    {
        $db = $this->db();
        $parent_unit_id = (int) $parent_unit_id;

        $parent = $this->getUnit($parent_unit_id);
        if (!$parent) {
            return array('ok' => false, 'error' => 'Parent unit not found.');
        }
        if ($parent['unit_status'] !== 'Released_To_Inventory') {
            return array('ok' => false, 'error' => 'Only a screened unit in inventory can be separated.');
        }
        if ($parent['component_type'] !== 'Whole_Blood') {
            return array('ok' => false, 'error' => 'This unit is already a component.');
        }
        $components = array_values(array_unique(array_filter($components)));
        if (!$components) {
            return array('ok' => false, 'error' => 'Select at least one component.');
        }

        $ref   = $this->getComponentTypes();
        $actor = $this->actor();

        mysqli_begin_transaction($db);
        try {
            $made = array();
            foreach ($components as $type) {
                if (!isset($ref[$type])) {
                    throw new Exception("Unknown component type: $type");
                }
                $shelf  = (int) $ref[$type]['shelf_life_days'];
                $volume = (int) $ref[$type]['typical_volume_ml'];
                $expiry = date('Y-m-d', strtotime($parent['collection_date'] . " + $shelf days"));

                $barcode = $parent['unit_barcode'] . '-' . $this->componentSuffix($type);
                $bag     = $parent['bag_number'] . '-' . $this->componentSuffix($type);

                $sql = "INSERT INTO blood_units
                          (unit_barcode, bag_number, collection_id, donor_id, blood_group, rh_factor,
                           component_type, volume_ml, collection_date, expiry_date, unit_status,
                           is_usable, is_available_for_issue, screening_status, testing_id,
                           parent_unit_id, separated_date, storage_location, created_by, created_date)
                        VALUES (?,?,?,?,?,?,?,?,?,?, 'Released_To_Inventory', 1, 1, 'Completed', ?, ?, NOW(), ?, ?, NOW())";
                $st = $db->prepare($sql);
                $storage = $ref[$type]['storage_temp'];
                $createdBy = isset($_SESSION['loginid']) ? (int) $_SESSION['loginid'] : 0;
                $testingId = $parent['testing_id'] !== null ? (int) $parent['testing_id'] : null;
                // 14 placeholders: barcode, bag, collection, donor, group, rh,
                // component, volume, collection_date, expiry_date, testing,
                // parent, storage, created_by. expiry_date is a DATE string —
                // typing it 'i' coerces it to 0 and the insert is rejected.
                $st->bind_param(
                    'ssiisssissiisi',
                    $barcode, $bag, $parent['collection_id'], $parent['donor_id'],
                    $parent['blood_group'], $parent['rh_factor'], $type, $volume,
                    $parent['collection_date'], $expiry, $testingId, $parent_unit_id,
                    $storage, $createdBy
                );
                if (!$st->execute()) {
                    throw new Exception("Could not create the $type unit: " . $st->error);
                }
                $st->close();
                $made[] = $type;
            }

            // The parent no longer exists as a transfusable unit.
            $st = $db->prepare("UPDATE blood_units
                                   SET unit_status = 'Separated', is_usable = 0,
                                       is_available_for_issue = 0, separated_date = NOW(),
                                       modified_date = NOW()
                                 WHERE unit_id = ?");
            $st->bind_param('i', $parent_unit_id);
            $st->execute();
            $st->close();

            $list  = implode(', ', $made);
            $count = count($made);
            $st = $db->prepare("INSERT INTO blood_component_separation
                                  (parent_unit_id, parent_barcode, components_made, component_count,
                                   separation_method, separated_by, remarks)
                                VALUES (?,?,?,?,?,?,?)");
            $st->bind_param('ississs', $parent_unit_id, $parent['unit_barcode'], $list,
                            $count, $method, $actor, $remarks);
            $st->execute();
            $st->close();

            mysqli_commit($db);
            return array('ok' => true, 'components' => $made);
        } catch (Exception $e) {
            mysqli_rollback($db);
            return array('ok' => false, 'error' => $e->getMessage());
        }
    }

    /** Short suffix appended to a child unit's barcode. */
    private function componentSuffix($type)
    {
        $map = array(
            'Packed_RBC'      => 'RBC',
            'Plasma_FFP'      => 'FFP',
            'Platelets'       => 'PLT',
            'Cryoprecipitate' => 'CRYO',
            'Whole_Blood'     => 'WB',
        );
        return isset($map[$type]) ? $map[$type] : strtoupper(substr($type, 0, 4));
    }

    /** Separation history, newest first. */
    public function getSeparationHistory($limit = 100)
    {
        $db = $this->db();
        $limit = (int) $limit;
        $sql = "SELECT s.*, u.blood_group
                  FROM blood_component_separation s
                  LEFT JOIN blood_units u ON u.unit_id = s.parent_unit_id
                 ORDER BY s.separated_date DESC
                 LIMIT $limit";
        return mysqli_query($db, $sql);
    }

    // =====================================================================
    // Stock
    // =====================================================================

    /** Expire anything past its date. Cheap enough to call on a stock view. */
    public function expireStaleUnits()
    {
        $db = $this->db();
        $sql = "UPDATE blood_units
                   SET unit_status = 'Expired', is_usable = 0, is_available_for_issue = 0,
                       modified_date = NOW()
                 WHERE expiry_date < CURDATE()
                   AND unit_status IN ('Released_To_Inventory','Reserved','Collected_Unscreened','Under_Testing','Testing_Passed')";
        mysqli_query($db, $sql);
        return mysqli_affected_rows($db);
    }

    /** Available counts per blood group x component. */
    public function getStockMatrix()
    {
        $db = $this->db();
        $sql = "SELECT blood_group, component_type, COUNT(*) AS units,
                       SUM(CASE WHEN expiry_date <= DATE_ADD(CURDATE(), INTERVAL 7 DAY) THEN 1 ELSE 0 END) AS expiring_soon
                  FROM blood_units
                 WHERE is_available_for_issue = 1
                   AND unit_status = 'Released_To_Inventory'
                   AND expiry_date >= CURDATE()
                 GROUP BY blood_group, component_type
                 ORDER BY blood_group, component_type";
        return mysqli_query($db, $sql);
    }

    /** Headline stock figures for the dashboard cards. */
    public function getStockSummary()
    {
        $db = $this->db();
        $sql = "SELECT
                  SUM(CASE WHEN is_available_for_issue = 1 AND unit_status = 'Released_To_Inventory'
                            AND expiry_date >= CURDATE() THEN 1 ELSE 0 END) AS available,
                  SUM(CASE WHEN unit_status IN ('Collected_Unscreened','Under_Testing') THEN 1 ELSE 0 END) AS pending_testing,
                  SUM(CASE WHEN unit_status = 'Reserved'  THEN 1 ELSE 0 END) AS reserved,
                  SUM(CASE WHEN unit_status = 'Issued'    THEN 1 ELSE 0 END) AS issued,
                  SUM(CASE WHEN unit_status = 'Expired'   THEN 1 ELSE 0 END) AS expired,
                  SUM(CASE WHEN unit_status = 'Discarded' THEN 1 ELSE 0 END) AS discarded,
                  SUM(CASE WHEN is_available_for_issue = 1 AND unit_status = 'Released_To_Inventory'
                            AND expiry_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 7 DAY)
                           THEN 1 ELSE 0 END) AS expiring_soon
                FROM blood_units";
        $row = mysqli_fetch_assoc(mysqli_query($db, $sql));
        foreach ($row as $k => $v) { $row[$k] = (int) $v; }
        return $row;
    }

    /** Unit-level stock listing. $status '' means every state. */
    public function getStockUnits($group = '', $component = '', $status = '')
    {
        $db = $this->db();
        $cond = '';
        $types = '';
        $args = array();

        if ($group !== '')     { $cond .= " AND u.blood_group = ?";   $types .= 's'; $args[] = $group; }
        if ($component !== '') { $cond .= " AND u.component_type = ?"; $types .= 's'; $args[] = $component; }
        if ($status !== '')    { $cond .= " AND u.unit_status = ?";    $types .= 's'; $args[] = $status; }

        $sql = "SELECT u.*, d.donor_unique_id,
                       CONCAT(d.first_name, ' ', COALESCE(d.last_name, '')) AS donor_name,
                       DATEDIFF(u.expiry_date, CURDATE()) AS days_to_expiry
                  FROM blood_units u
                  LEFT JOIN blood_donor_master d ON d.donor_id = u.donor_id
                 WHERE 1 = 1 $cond
                 ORDER BY u.expiry_date ASC, u.unit_id DESC";
        $st = $db->prepare($sql);
        if ($args) { $st->bind_param($types, ...$args); }
        $st->execute();
        return $st->get_result();
    }

    // =====================================================================
    // Stage 5 — Requests, crossmatch, issue
    // =====================================================================

    /** Next request number, BR-YYYYMMDD-XXXX. */
    private function nextNumber($db, $table, $column, $prefix)
    {
        $day = date('Ymd');
        $like = "$prefix-$day-%";
        $st = $db->prepare("SELECT `$column` FROM `$table` WHERE `$column` LIKE ? ORDER BY `$column` DESC LIMIT 1");
        $st->bind_param('s', $like);
        $st->execute();
        $row = $st->get_result()->fetch_assoc();
        $st->close();
        $seq = 1;
        if ($row) {
            $parts = explode('-', $row[$column]);
            $seq = (int) end($parts) + 1;
        }
        return sprintf('%s-%s-%04d', $prefix, $day, $seq);
    }

    public function createRequest(array $r)
    {
        $db = $this->db();
        $number = $this->nextNumber($db, 'blood_requests', 'request_number', 'BR');
        $actor  = $this->actor();

        $sql = "INSERT INTO blood_requests
                  (request_number, patient_type, patient_no, patient_name, patient_age,
                   patient_gender, ward, blood_group, component_type, units_required,
                   urgency, indication, requested_by, created_by)
                VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
        $st = $db->prepare($sql);
        $units = (int) $r['units_required'];
        $st->bind_param(
            'sssssssssissss',
            $number, $r['patient_type'], $r['patient_no'], $r['patient_name'], $r['patient_age'],
            $r['patient_gender'], $r['ward'], $r['blood_group'], $r['component_type'], $units,
            $r['urgency'], $r['indication'], $r['requested_by'], $actor
        );
        $ok = $st->execute();
        $id = $st->insert_id;
        $st->close();
        return $ok ? array('ok' => true, 'request_id' => $id, 'request_number' => $number)
                   : array('ok' => false, 'error' => 'Could not save the request.');
    }

    public function getRequests($status = '')
    {
        $db = $this->db();
        $cond = '';
        if ($status !== '') {
            $cond = " WHERE r.request_status = '" . mysqli_real_escape_string($db, $status) . "'";
        }
        $sql = "SELECT r.*,
                       (SELECT COUNT(*) FROM blood_crossmatch x
                         WHERE x.request_id = r.request_id AND x.result = 'Compatible') AS compatible_units
                  FROM blood_requests r
                  $cond
                 ORDER BY FIELD(r.urgency,'Emergency','Urgent','Routine'), r.created_date DESC";
        return mysqli_query($db, $sql);
    }

    public function getRequest($request_id)
    {
        $db = $this->db();
        // bind_param takes its arguments by reference, so the cast has to land
        // in a variable first — passing (int)$x directly is a fatal error.
        $request_id = (int) $request_id;
        $st = $db->prepare("SELECT * FROM blood_requests WHERE request_id = ? LIMIT 1");
        $st->bind_param('i', $request_id);
        $st->execute();
        return $st->get_result()->fetch_assoc();
    }

    /**
     * Units that could serve a request: right component, in inventory, unexpired,
     * and ABO/Rh compatible with the patient.
     */
    public function getCompatibleUnits($blood_group, $component_type)
    {
        $db = $this->db();
        $groups = $this->compatibleDonorGroups($blood_group);
        if (!$groups) { return array(); }

        $in = "'" . implode("','", array_map(function ($g) use ($db) {
            return mysqli_real_escape_string($db, $g);
        }, $groups)) . "'";

        $sql = "SELECT u.*, DATEDIFF(u.expiry_date, CURDATE()) AS days_to_expiry
                  FROM blood_units u
                 WHERE u.is_available_for_issue = 1
                   AND u.unit_status = 'Released_To_Inventory'
                   AND u.expiry_date >= CURDATE()
                   AND u.component_type = ?
                   AND u.blood_group IN ($in)
                 ORDER BY u.expiry_date ASC";
        $st = $db->prepare($sql);
        $st->bind_param('s', $component_type);
        $st->execute();
        $res = $st->get_result();
        $out = array();
        while ($row = $res->fetch_assoc()) { $out[] = $row; }
        return $out;
    }

    /**
     * ABO/Rh donor groups a recipient can safely receive red cells from.
     * Rh-negative recipients must not receive Rh-positive cells; Rh-positive
     * recipients may receive either.
     */
    public function compatibleDonorGroups($recipient)
    {
        $map = array(
            'O-'  => array('O-'),
            'O+'  => array('O-', 'O+'),
            'A-'  => array('O-', 'A-'),
            'A+'  => array('O-', 'O+', 'A-', 'A+'),
            'B-'  => array('O-', 'B-'),
            'B+'  => array('O-', 'O+', 'B-', 'B+'),
            'AB-' => array('O-', 'A-', 'B-', 'AB-'),
            'AB+' => array('O-', 'O+', 'A-', 'A+', 'B-', 'B+', 'AB-', 'AB+'),
        );
        return isset($map[$recipient]) ? $map[$recipient] : array();
    }

    /** Record a crossmatch and reserve the unit when it is compatible. */
    public function saveCrossmatch($request_id, $unit_id, $method, $result, $note = '')
    {
        $db = $this->db();
        $request_id = (int) $request_id;
        $unit_id = (int) $unit_id;
        $actor = $this->actor();

        $unit = $this->getUnit($unit_id);
        if (!$unit) { return array('ok' => false, 'error' => 'Unit not found.'); }
        if ($result === 'Compatible' && $unit['unit_status'] !== 'Released_To_Inventory') {
            return array('ok' => false, 'error' => 'That unit is no longer available in inventory.');
        }

        mysqli_begin_transaction($db);
        try {
            $st = $db->prepare("INSERT INTO blood_crossmatch
                                  (request_id, unit_id, unit_barcode, method, result,
                                   incompatibility_note, performed_by)
                                VALUES (?,?,?,?,?,?,?)");
            $st->bind_param('iisssss', $request_id, $unit_id, $unit['unit_barcode'],
                            $method, $result, $note, $actor);
            if (!$st->execute()) { throw new Exception('Could not save the crossmatch: ' . $st->error); }
            $st->close();

            if ($result === 'Compatible') {
                // Reserve so a second request cannot claim the same bag.
                $st = $db->prepare("UPDATE blood_units
                                       SET unit_status = 'Reserved', is_available_for_issue = 0,
                                           modified_date = NOW()
                                     WHERE unit_id = ?");
                $st->bind_param('i', $unit_id);
                $st->execute();
                $st->close();

                $st = $db->prepare("UPDATE blood_requests SET request_status = 'Ready'
                                     WHERE request_id = ? AND request_status IN ('Pending','Crossmatching')");
                $st->bind_param('i', $request_id);
                $st->execute();
                $st->close();
            } else {
                $st = $db->prepare("UPDATE blood_requests SET request_status = 'Crossmatching'
                                     WHERE request_id = ? AND request_status = 'Pending'");
                $st->bind_param('i', $request_id);
                $st->execute();
                $st->close();
            }

            mysqli_commit($db);
            return array('ok' => true);
        } catch (Exception $e) {
            mysqli_rollback($db);
            return array('ok' => false, 'error' => $e->getMessage());
        }
    }

    /** Crossmatch records for one request. */
    public function getCrossmatches($request_id)
    {
        $db = $this->db();
        $request_id = (int) $request_id;
        $st = $db->prepare("SELECT x.*, u.blood_group, u.component_type, u.expiry_date, u.unit_status
                              FROM blood_crossmatch x
                              LEFT JOIN blood_units u ON u.unit_id = x.unit_id
                             WHERE x.request_id = ?
                             ORDER BY x.performed_date DESC");
        $st->bind_param('i', $request_id);
        $st->execute();
        return $st->get_result();
    }

    /** Issue a crossmatched unit against a request. */
    public function issueUnit($request_id, $unit_id, $collected_by = '')
    {
        $db = $this->db();
        $request_id = (int) $request_id;
        $unit_id = (int) $unit_id;

        $req  = $this->getRequest($request_id);
        $unit = $this->getUnit($unit_id);
        if (!$req || !$unit) { return array('ok' => false, 'error' => 'Request or unit not found.'); }
        if ($unit['unit_status'] !== 'Reserved') {
            return array('ok' => false, 'error' => 'Only a crossmatched, reserved unit can be issued.');
        }

        // Refuse to issue against a crossmatch that is not on file as compatible.
        $st = $db->prepare("SELECT crossmatch_id FROM blood_crossmatch
                             WHERE request_id = ? AND unit_id = ? AND result = 'Compatible'
                             ORDER BY crossmatch_id DESC LIMIT 1");
        $st->bind_param('ii', $request_id, $unit_id);
        $st->execute();
        $xm = $st->get_result()->fetch_assoc();
        $st->close();
        if (!$xm) {
            return array('ok' => false, 'error' => 'No compatible crossmatch on record for this unit.');
        }

        $number = $this->nextNumber($db, 'blood_issues', 'issue_number', 'BI');
        $actor  = $this->actor();

        mysqli_begin_transaction($db);
        try {
            $st = $db->prepare("INSERT INTO blood_issues
                                  (issue_number, request_id, unit_id, unit_barcode, crossmatch_id,
                                   patient_no, patient_name, blood_group, component_type,
                                   issued_by, collected_by)
                                VALUES (?,?,?,?,?,?,?,?,?,?,?)");
            $st->bind_param('siisisssiss',
                $number, $request_id, $unit_id, $unit['unit_barcode'], $xm['crossmatch_id'],
                $req['patient_no'], $req['patient_name'], $unit['blood_group'],
                $unit['component_type'], $actor, $collected_by);
            if (!$st->execute()) { throw new Exception('Could not save the issue record: ' . $st->error); }
            $issue_id = $st->insert_id;
            $st->close();

            $st = $db->prepare("UPDATE blood_units
                                   SET unit_status = 'Issued', is_available_for_issue = 0,
                                       issued_to_patient = ?, issue_date = NOW(), modified_date = NOW()
                                 WHERE unit_id = ?");
            $st->bind_param('si', $req['patient_name'], $unit_id);
            $st->execute();
            $st->close();

            // Fully or partially issued, depending on how many units are still owed.
            $st = $db->prepare("UPDATE blood_requests
                                   SET units_issued = units_issued + 1,
                                       request_status = IF(units_issued + 1 >= units_required,
                                                           'Issued', 'Partially_Issued')
                                 WHERE request_id = ?");
            $st->bind_param('i', $request_id);
            $st->execute();
            $st->close();

            mysqli_commit($db);
            return array('ok' => true, 'issue_id' => $issue_id, 'issue_number' => $number);
        } catch (Exception $e) {
            mysqli_rollback($db);
            return array('ok' => false, 'error' => $e->getMessage());
        }
    }

    /**
     * Close out an issued unit.
     * $status: Transfused | Returned | Discarded.
     * A return puts the unit back in inventory only when the cold chain held —
     * the caller says so via $back_to_stock.
     */
    public function recordOutcome($issue_id, $status, array $r = array(), $back_to_stock = false)
    {
        $db = $this->db();
        $issue_id = (int) $issue_id;

        $st = $db->prepare("SELECT * FROM blood_issues WHERE issue_id = ? LIMIT 1");
        $st->bind_param('i', $issue_id);
        $st->execute();
        $issue = $st->get_result()->fetch_assoc();
        $st->close();
        if (!$issue) { return array('ok' => false, 'error' => 'Issue record not found.'); }

        $reaction = !empty($r['reaction_flag']) ? 1 : 0;
        $rtype    = isset($r['reaction_type'])  ? $r['reaction_type']  : '';
        $rnotes   = isset($r['reaction_notes']) ? $r['reaction_notes'] : '';
        $reason   = isset($r['return_reason'])  ? $r['return_reason']  : '';

        mysqli_begin_transaction($db);
        try {
            $sql = "UPDATE blood_issues
                       SET transfusion_status = ?, reaction_flag = ?, reaction_type = ?,
                           reaction_notes = ?, return_reason = ?,
                           transfusion_date = IF(? = 'Transfused', NOW(), transfusion_date),
                           returned_date    = IF(? IN ('Returned','Discarded'), NOW(), returned_date)
                     WHERE issue_id = ?";
            $st = $db->prepare($sql);
            // 8 placeholders: status, reaction, type, notes, reason, status,
            // status, issue_id. The type string must be exactly 8 long.
            $st->bind_param('sisssssi', $status, $reaction, $rtype, $rnotes, $reason,
                            $status, $status, $issue_id);
            if (!$st->execute()) { throw new Exception('Could not update the issue: ' . $st->error); }
            $st->close();

            if ($status === 'Transfused') {
                $unitStatus = 'Transfused'; $avail = 0;
            } elseif ($status === 'Returned' && $back_to_stock) {
                $unitStatus = 'Released_To_Inventory'; $avail = 1;
            } else {
                $unitStatus = 'Discarded'; $avail = 0;
            }

            $st = $db->prepare("UPDATE blood_units
                                   SET unit_status = ?, is_available_for_issue = ?,
                                       discard_reason = IF(? = 'Discarded', ?, discard_reason),
                                       discarded_date = IF(? = 'Discarded', CURDATE(), discarded_date),
                                       modified_date = NOW()
                                 WHERE unit_id = ?");
            // 6 placeholders: status, available, status, reason, status, unit_id.
            $st->bind_param('sisssi', $unitStatus, $avail, $unitStatus, $reason,
                            $unitStatus, $issue['unit_id']);
            if (!$st->execute()) { throw new Exception('Could not update the unit: ' . $st->error); }
            $st->close();

            // A returned unit frees up its slot on the request again.
            if ($status !== 'Transfused') {
                $st = $db->prepare("UPDATE blood_requests
                                       SET units_issued = GREATEST(units_issued - 1, 0),
                                           request_status = IF(units_issued - 1 <= 0, 'Ready', 'Partially_Issued')
                                     WHERE request_id = ?");
                $st->bind_param('i', $issue['request_id']);
                $st->execute();
                $st->close();
            }

            mysqli_commit($db);
            return array('ok' => true);
        } catch (Exception $e) {
            mysqli_rollback($db);
            return array('ok' => false, 'error' => $e->getMessage());
        }
    }

    /** Issue register. */
    public function getIssues($from = '', $to = '', $status = '')
    {
        $db = $this->db();
        $cond = '';
        $types = '';
        $args = array();

        if ($from !== '' && $to !== '') {
            $cond .= " AND DATE(i.issued_date) BETWEEN ? AND ?";
            $types .= 'ss'; $args[] = $from; $args[] = $to;
        }
        if ($status !== '') {
            $cond .= " AND i.transfusion_status = ?";
            $types .= 's'; $args[] = $status;
        }

        $sql = "SELECT i.*, r.request_number, r.ward, r.urgency
                  FROM blood_issues i
                  LEFT JOIN blood_requests r ON r.request_id = i.request_id
                 WHERE 1 = 1 $cond
                 ORDER BY i.issued_date DESC";
        $st = $db->prepare($sql);
        if ($args) { $st->bind_param($types, ...$args); }
        $st->execute();
        return $st->get_result();
    }

    /** Cancel an outstanding request and release anything reserved for it. */
    public function cancelRequest($request_id, $reason)
    {
        $db = $this->db();
        $request_id = (int) $request_id;

        mysqli_begin_transaction($db);
        try {
            $st = $db->prepare("UPDATE blood_units u
                                  JOIN blood_crossmatch x ON x.unit_id = u.unit_id
                                   SET u.unit_status = 'Released_To_Inventory',
                                       u.is_available_for_issue = 1, u.modified_date = NOW()
                                 WHERE x.request_id = ? AND u.unit_status = 'Reserved'");
            $st->bind_param('i', $request_id);
            $st->execute();
            $st->close();

            $st = $db->prepare("UPDATE blood_requests
                                   SET request_status = 'Cancelled', cancel_reason = ?
                                 WHERE request_id = ?");
            $st->bind_param('si', $reason, $request_id);
            $st->execute();
            $st->close();

            mysqli_commit($db);
            return array('ok' => true);
        } catch (Exception $e) {
            mysqli_rollback($db);
            return array('ok' => false, 'error' => $e->getMessage());
        }
    }
}

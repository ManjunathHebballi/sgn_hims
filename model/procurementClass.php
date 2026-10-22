<?php
require_once(__DIR__ . '/session_config.php');
include_once('dbconnect.php');

/**
 * Tender & Procurement.
 *
 *   requisition -> approval -> tender -> bids -> comparison -> award
 *                                                    -> approval -> PO
 *
 * The store module owns everything from the purchase order onward. This class
 * only READS store_items / store_suppliers / sub_store, and at the very end
 * writes one ordinary row into store_purchase_orders (+ its items) so GRN,
 * stock and payment carry on unchanged. No store table or store file is
 * modified by this module.
 *
 * Tables: sql/procurement_tender.sql
 */
class procurement
{
    private function db()
    {
        $newlogin = new userlogin;
        return $newlogin->dbconnect();
    }

    private function actor()
    {
        return isset($_SESSION['name']) ? $_SESSION['name'] : 'SYSTEM';
    }

    private function actorRole()
    {
        return isset($_SESSION['role']) ? $_SESSION['role'] : '';
    }

    /** Next document number, e.g. PR-20260828-0001. */
    private function nextNumber($db, $table, $column, $prefix)
    {
        $day  = date('Ymd');
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

    // =====================================================================
    // Approval engine
    // =====================================================================

    /**
     * Levels that apply to an entity at a given value, in order.
     * A level whose amount band excludes this value is skipped entirely — that
     * is how a small requisition clears on one signature and a large one needs
     * two.
     */
    public function applicableLevels($entity_type, $amount)
    {
        $db = $this->db();
        $amount = (float) $amount;
        $sql = "SELECT * FROM procurement_approval_matrix
                 WHERE entity_type = ? AND is_active = 1
                   AND min_amount <= ?
                   AND (max_amount IS NULL OR max_amount >= ?)
                 ORDER BY level ASC";
        $st = $db->prepare($sql);
        $st->bind_param('sdd', $entity_type, $amount, $amount);
        $st->execute();
        $res = $st->get_result();
        $out = array();
        while ($r = $res->fetch_assoc()) { $out[] = $r; }
        $st->close();
        return $out;
    }

    /** Decisions already recorded against an entity, oldest first. */
    public function approvalHistory($entity_type, $entity_id)
    {
        $db = $this->db();
        $entity_id = (int) $entity_id;
        $st = $db->prepare("SELECT * FROM procurement_approvals
                             WHERE entity_type = ? AND entity_id = ?
                             ORDER BY level ASC, id ASC");
        $st->bind_param('si', $entity_type, $entity_id);
        $st->execute();
        $res = $st->get_result();
        $out = array();
        while ($r = $res->fetch_assoc()) { $out[] = $r; }
        $st->close();
        return $out;
    }

    /**
     * The level this entity is waiting on, or null when fully approved.
     * Returns the matrix row so the caller can see which role is required.
     */
    public function pendingLevel($entity_type, $entity_id, $amount)
    {
        $levels = $this->applicableLevels($entity_type, $amount);
        $done   = array();
        foreach ($this->approvalHistory($entity_type, $entity_id) as $a) {
            if ($a['action'] === 'APPROVED') { $done[(int) $a['level']] = true; }
        }
        foreach ($levels as $lv) {
            if (!isset($done[(int) $lv['level']])) { return $lv; }
        }
        return null;
    }

    /** Can the current user sign the level this entity is waiting on? */
    public function canApprove($entity_type, $entity_id, $amount)
    {
        $lv = $this->pendingLevel($entity_type, $entity_id, $amount);
        if ($lv === null) { return false; }
        $role = $this->actorRole();
        // Admin can always sign — otherwise a hospital with nobody in the named
        // role would have documents stuck forever with no way to clear them.
        return ($role === $lv['approver_role'] || $role === 'Admin');
    }

    /**
     * Record one approval decision and move the entity on.
     * Returns array(ok, error, fully_approved).
     */
    public function recordApproval($entity_type, $entity_id, $action, $remarks = '')
    {
        $db = $this->db();
        $entity_id = (int) $entity_id;

        $entity = $this->loadEntity($entity_type, $entity_id);
        if (!$entity) { return array('ok' => false, 'error' => 'Record not found.'); }
        if (!in_array($action, array('APPROVED', 'REJECTED'), true)) {
            return array('ok' => false, 'error' => 'Unknown action.');
        }
        if (!in_array($entity['status'], $this->approvableStatuses($entity_type), true)) {
            return array('ok' => false, 'error' => 'This record is not awaiting approval.');
        }

        $amount = (float) $entity['_amount'];
        $lv = $this->pendingLevel($entity_type, $entity_id, $amount);
        if ($lv === null) { return array('ok' => false, 'error' => 'Already fully approved.'); }
        if (!$this->canApprove($entity_type, $entity_id, $amount)) {
            return array('ok' => false,
                         'error' => 'This level needs the ' . $lv['approver_role'] . ' role.');
        }

        $level = (int) $lv['level'];
        $role  = $lv['approver_role'];
        $actor = $this->actor();
        $arole = $this->actorRole();

        mysqli_begin_transaction($db);
        try {
            $st = $db->prepare("INSERT INTO procurement_approvals
                                  (entity_type, entity_id, level, approver_role, action,
                                   acted_by, acted_role, remarks)
                                VALUES (?,?,?,?,?,?,?,?)");
            $st->bind_param('sisssss' . 's', $entity_type, $entity_id, $level, $role,
                            $action, $actor, $arole, $remarks);
            if (!$st->execute()) { throw new Exception('Could not record the decision: ' . $st->error); }
            $st->close();

            if ($action === 'REJECTED') {
                $this->setStatus($db, $entity_type, $entity_id, 'REJECTED', $remarks, $level);
                mysqli_commit($db);
                return array('ok' => true, 'fully_approved' => false, 'rejected' => true);
            }

            // Approved at this level — is anything left?
            $next = $this->pendingLevelWithin($db, $entity_type, $entity_id, $amount);
            if ($next === null) {
                $this->setStatus($db, $entity_type, $entity_id, 'APPROVED', null, $level);
                mysqli_commit($db);
                return array('ok' => true, 'fully_approved' => true);
            }

            $this->bumpLevel($db, $entity_type, $entity_id, $level);
            mysqli_commit($db);
            return array('ok' => true, 'fully_approved' => false,
                         'next_role' => $next['approver_role'], 'next_level' => $next['level']);
        } catch (Exception $e) {
            mysqli_rollback($db);
            return array('ok' => false, 'error' => $e->getMessage());
        }
    }

    /** pendingLevel() re-evaluated inside an open transaction. */
    private function pendingLevelWithin($db, $entity_type, $entity_id, $amount)
    {
        $levels = $this->applicableLevels($entity_type, $amount);
        $done = array();
        $st = $db->prepare("SELECT level FROM procurement_approvals
                             WHERE entity_type = ? AND entity_id = ? AND action = 'APPROVED'");
        $st->bind_param('si', $entity_type, $entity_id);
        $st->execute();
        $res = $st->get_result();
        while ($r = $res->fetch_assoc()) { $done[(int) $r['level']] = true; }
        $st->close();
        foreach ($levels as $lv) {
            if (!isset($done[(int) $lv['level']])) { return $lv; }
        }
        return null;
    }

    /** Statuses from which an entity can still be approved. */
    private function approvableStatuses($entity_type)
    {
        if ($entity_type === 'REQUISITION') { return array('SUBMITTED'); }
        if ($entity_type === 'TENDER')      { return array('PENDING_APPROVAL'); }
        return array('PENDING_APPROVAL');   // AWARD
    }

    private function entityTable($entity_type)
    {
        $map = array(
            'REQUISITION' => 'procurement_requisitions',
            'TENDER'      => 'tenders',
            'AWARD'       => 'tender_awards',
        );
        return isset($map[$entity_type]) ? $map[$entity_type] : null;
    }

    /** Load an entity with a normalised `_amount` for the approval bands. */
    public function loadEntity($entity_type, $entity_id)
    {
        $db = $this->db();
        $tbl = $this->entityTable($entity_type);
        if (!$tbl) { return null; }
        $entity_id = (int) $entity_id;
        $res = mysqli_query($db, "SELECT * FROM `$tbl` WHERE id = $entity_id LIMIT 1");
        $row = $res ? mysqli_fetch_assoc($res) : null;
        if (!$row) { return null; }

        if ($entity_type === 'REQUISITION')  { $row['_amount'] = $row['estimated_amount']; }
        elseif ($entity_type === 'TENDER')   { $row['_amount'] = $row['estimated_value']; }
        else                                 { $row['_amount'] = $row['award_amount']; }
        return $row;
    }

    private function setStatus($db, $entity_type, $entity_id, $status, $reason, $level)
    {
        $tbl = $this->entityTable($entity_type);
        $entity_id = (int) $entity_id;
        $level = (int) $level;
        $col = ($entity_type === 'TENDER') ? 'cancel_reason' : 'reject_reason';
        if ($status === 'REJECTED') {
            $st = $db->prepare("UPDATE `$tbl` SET status = 'REJECTED', `$col` = ?, current_level = ? WHERE id = ?");
            $st->bind_param('sii', $reason, $level, $entity_id);
        } else {
            // A tender that clears approval is published, not merely "approved".
            $newStatus = ($entity_type === 'TENDER') ? 'PUBLISHED' : 'APPROVED';
            $st = $db->prepare("UPDATE `$tbl` SET status = ?, current_level = ? WHERE id = ?");
            $st->bind_param('sii', $newStatus, $level, $entity_id);
        }
        $st->execute();
        $st->close();
    }

    private function bumpLevel($db, $entity_type, $entity_id, $level)
    {
        $tbl = $this->entityTable($entity_type);
        $entity_id = (int) $entity_id;
        $level = (int) $level;
        mysqli_query($db, "UPDATE `$tbl` SET current_level = $level WHERE id = $entity_id");
    }

    /** Everything currently waiting on the signed-in user's role. */
    public function myPendingApprovals()
    {
        $db = $this->db();
        $out = array();
        $sets = array(
            array('REQUISITION', 'procurement_requisitions', 'requisition_no', 'estimated_amount', "status = 'SUBMITTED'"),
            array('TENDER',      'tenders',                  'tender_no',      'estimated_value',  "status = 'PENDING_APPROVAL'"),
            array('AWARD',       'tender_awards',            'award_no',       'award_amount',     "status = 'PENDING_APPROVAL'"),
        );
        foreach ($sets as $s) {
            list($type, $tbl, $noCol, $amtCol, $where) = $s;
            $res = mysqli_query($db, "SELECT id, `$noCol` AS doc_no, `$amtCol` AS amount, created_by, created_at
                                        FROM `$tbl` WHERE $where ORDER BY created_at ASC");
            if (!$res) { continue; }
            while ($r = mysqli_fetch_assoc($res)) {
                if (!$this->canApprove($type, $r['id'], $r['amount'])) { continue; }
                $lv = $this->pendingLevel($type, $r['id'], $r['amount']);
                $r['entity_type']   = $type;
                $r['pending_level'] = $lv ? $lv['level'] : '';
                $r['pending_role']  = $lv ? $lv['approver_role'] : '';
                $out[] = $r;
            }
        }
        return $out;
    }

    // =====================================================================
    // Requisitions
    // =====================================================================

    public function createRequisition(array $r, array $items)
    {
        $db = $this->db();
        $number = $this->nextNumber($db, 'procurement_requisitions', 'requisition_no', 'PR');
        $actor  = $this->actor();

        $clean = $this->cleanItems($items);
        if (!$clean) { return array('ok' => false, 'error' => 'Add at least one item with a quantity.'); }
        $estimated = 0.0;
        foreach ($clean as $i) { $estimated += $i['value']; }

        mysqli_begin_transaction($db);
        try {
            $st = $db->prepare("INSERT INTO procurement_requisitions
                                  (requisition_no, store_id, department, required_by, priority,
                                   justification, estimated_amount, status, created_by)
                                VALUES (?,?,?,?,?,?,?,'DRAFT',?)");
            $store_id = (int) $r['store_id'];
            $required = ($r['required_by'] === '') ? null : $r['required_by'];
            $st->bind_param('sissssds', $number, $store_id, $r['department'], $required,
                            $r['priority'], $r['justification'], $estimated, $actor);
            if (!$st->execute()) { throw new Exception('Could not save the requisition: ' . $st->error); }
            $id = $st->insert_id;
            $st->close();

            $this->saveRequisitionItems($db, $id, $clean);
            mysqli_commit($db);
            return array('ok' => true, 'id' => $id, 'requisition_no' => $number);
        } catch (Exception $e) {
            mysqli_rollback($db);
            return array('ok' => false, 'error' => $e->getMessage());
        }
    }

    /** Normalise posted item rows: drop blanks, compute line values. */
    private function cleanItems(array $items)
    {
        $out = array();
        foreach ($items as $i) {
            $item_id = isset($i['item_id']) ? (int) $i['item_id'] : 0;
            $qty     = isset($i['quantity']) ? (float) $i['quantity'] : 0;
            if ($item_id <= 0 || $qty <= 0) { continue; }
            $rate = isset($i['rate']) ? (float) $i['rate'] : 0;
            $out[] = array(
                'item_id'       => $item_id,
                'quantity'      => $qty,
                'rate'          => $rate,
                'value'         => $qty * $rate,
                'specification' => isset($i['specification']) ? $i['specification'] : '',
                'remarks'       => isset($i['remarks']) ? $i['remarks'] : '',
            );
        }
        return $out;
    }

    private function saveRequisitionItems($db, $requisition_id, array $items)
    {
        $st = $db->prepare("INSERT INTO procurement_requisition_items
                              (requisition_id, item_id, quantity, estimated_rate,
                               estimated_value, specification, remarks)
                            VALUES (?,?,?,?,?,?,?)");
        foreach ($items as $i) {
            $st->bind_param('iidddss', $requisition_id, $i['item_id'], $i['quantity'],
                            $i['rate'], $i['value'], $i['specification'], $i['remarks']);
            if (!$st->execute()) { throw new Exception('Could not save a requisition line: ' . $st->error); }
        }
        $st->close();
    }

    /** Move a draft requisition into the approval queue. */
    public function submitRequisition($id)
    {
        $db = $this->db();
        $id = (int) $id;
        $req = $this->loadEntity('REQUISITION', $id);
        if (!$req) { return array('ok' => false, 'error' => 'Requisition not found.'); }
        if ($req['status'] !== 'DRAFT' && $req['status'] !== 'REJECTED') {
            return array('ok' => false, 'error' => 'Only a draft or rejected requisition can be submitted.');
        }
        // A resubmission starts the approval trail over.
        mysqli_query($db, "DELETE FROM procurement_approvals
                            WHERE entity_type='REQUISITION' AND entity_id=$id");
        mysqli_query($db, "UPDATE procurement_requisitions
                              SET status='SUBMITTED', current_level=0, reject_reason=NULL
                            WHERE id=$id");
        return array('ok' => true);
    }

    public function getRequisitions($status = '')
    {
        $db = $this->db();
        $cond = '';
        if ($status !== '') {
            $cond = " WHERE r.status = '" . mysqli_real_escape_string($db, $status) . "'";
        }
        $sql = "SELECT r.*, ss.store_name,
                       (SELECT COUNT(*) FROM procurement_requisition_items i
                         WHERE i.requisition_id = r.id) AS item_count
                  FROM procurement_requisitions r
                  LEFT JOIN sub_store ss ON ss.id = r.store_id
                  $cond
                 ORDER BY FIELD(r.priority,'Emergency','Urgent','Normal'), r.created_at DESC";
        return mysqli_query($db, $sql);
    }

    public function getRequisitionItems($requisition_id)
    {
        $db = $this->db();
        $requisition_id = (int) $requisition_id;
        $sql = "SELECT i.*, si.item_name, si.item_code, su.unit_name
                  FROM procurement_requisition_items i
                  LEFT JOIN store_items si ON si.id = i.item_id
                  LEFT JOIN store_units su ON su.id = si.unit_id
                 WHERE i.requisition_id = $requisition_id
                 ORDER BY i.id";
        return mysqli_query($db, $sql);
    }

    // =====================================================================
    // Tenders
    // =====================================================================

    /** Create a tender, optionally seeded from an approved requisition. */
    public function createTender(array $t, array $items, $requisition_id = 0)
    {
        $db = $this->db();
        $requisition_id = (int) $requisition_id;

        if ($requisition_id > 0) {
            $req = $this->loadEntity('REQUISITION', $requisition_id);
            if (!$req) { return array('ok' => false, 'error' => 'Requisition not found.'); }
            if ($req['status'] !== 'APPROVED') {
                return array('ok' => false, 'error' => 'Only an approved requisition can go to tender.');
            }
        }

        $clean = $this->cleanItems($items);
        if (!$clean) { return array('ok' => false, 'error' => 'Add at least one item with a quantity.'); }
        $estimated = 0.0;
        foreach ($clean as $i) { $estimated += $i['value']; }

        $number = $this->nextNumber($db, 'tenders', 'tender_no', 'TND');
        $actor  = $this->actor();

        mysqli_begin_transaction($db);
        try {
            $st = $db->prepare("INSERT INTO tenders
                                  (tender_no, requisition_id, title, description, tender_type,
                                   published_date, closing_date, opening_date, emd_amount,
                                   estimated_value, terms_conditions, status, created_by)
                                VALUES (?,?,?,?,?,?,?,?,?,?,?,'DRAFT',?)");
            $pub  = ($t['published_date'] === '') ? null : $t['published_date'];
            $clos = ($t['closing_date']   === '') ? null : $t['closing_date'];
            $open = ($t['opening_date']   === '') ? null : $t['opening_date'];
            $emd  = (float) $t['emd_amount'];
            $st->bind_param('sissssssddss', $number, $requisition_id, $t['title'], $t['description'],
                            $t['tender_type'], $pub, $clos, $open, $emd, $estimated,
                            $t['terms_conditions'], $actor);
            if (!$st->execute()) { throw new Exception('Could not save the tender: ' . $st->error); }
            $id = $st->insert_id;
            $st->close();

            $st = $db->prepare("INSERT INTO tender_items (tender_id, item_id, quantity, estimated_rate, specification)
                                VALUES (?,?,?,?,?)");
            foreach ($clean as $i) {
                $st->bind_param('iidds', $id, $i['item_id'], $i['quantity'], $i['rate'], $i['specification']);
                if (!$st->execute()) { throw new Exception('Could not save a tender line: ' . $st->error); }
            }
            $st->close();

            if ($requisition_id > 0) {
                mysqli_query($db, "UPDATE procurement_requisitions SET status='TENDERED' WHERE id=$requisition_id");
            }

            mysqli_commit($db);
            return array('ok' => true, 'id' => $id, 'tender_no' => $number);
        } catch (Exception $e) {
            mysqli_rollback($db);
            return array('ok' => false, 'error' => $e->getMessage());
        }
    }

    /** Send a draft tender for approval; publishing happens on final sign-off. */
    public function submitTender($id)
    {
        $db = $this->db();
        $id = (int) $id;
        $t = $this->loadEntity('TENDER', $id);
        if (!$t) { return array('ok' => false, 'error' => 'Tender not found.'); }
        if ($t['status'] !== 'DRAFT' && $t['status'] !== 'REJECTED') {
            return array('ok' => false, 'error' => 'Only a draft tender can be submitted.');
        }
        mysqli_query($db, "DELETE FROM procurement_approvals WHERE entity_type='TENDER' AND entity_id=$id");
        mysqli_query($db, "UPDATE tenders SET status='PENDING_APPROVAL', current_level=0 WHERE id=$id");
        return array('ok' => true);
    }

    /** Stop accepting bids so evaluation can begin. */
    public function closeTender($id)
    {
        $db = $this->db();
        $id = (int) $id;
        $t = $this->loadEntity('TENDER', $id);
        if (!$t) { return array('ok' => false, 'error' => 'Tender not found.'); }
        if ($t['status'] !== 'PUBLISHED') {
            return array('ok' => false, 'error' => 'Only a published tender can be closed.');
        }
        mysqli_query($db, "UPDATE tenders SET status='UNDER_EVALUATION' WHERE id=$id");
        return array('ok' => true);
    }

    public function cancelTender($id, $reason)
    {
        $db = $this->db();
        $id = (int) $id;
        $st = $db->prepare("UPDATE tenders SET status='CANCELLED', cancel_reason=?
                             WHERE id=? AND status NOT IN ('AWARDED','CANCELLED')");
        $st->bind_param('si', $reason, $id);
        $st->execute();
        $ok = mysqli_affected_rows($db) > 0;
        $st->close();
        return $ok ? array('ok' => true)
                   : array('ok' => false, 'error' => 'Tender cannot be cancelled in its current state.');
    }

    public function getTenders($status = '')
    {
        $db = $this->db();
        $cond = '';
        if ($status !== '') {
            $cond = " WHERE t.status = '" . mysqli_real_escape_string($db, $status) . "'";
        }
        $sql = "SELECT t.*, r.requisition_no,
                       (SELECT COUNT(*) FROM tender_items ti WHERE ti.tender_id = t.id) AS item_count,
                       (SELECT COUNT(*) FROM tender_bids b WHERE b.tender_id = t.id AND b.status='SUBMITTED') AS bid_count
                  FROM tenders t
                  LEFT JOIN procurement_requisitions r ON r.id = t.requisition_id
                  $cond
                 ORDER BY t.created_at DESC";
        return mysqli_query($db, $sql);
    }

    public function getTenderItems($tender_id)
    {
        $db = $this->db();
        $tender_id = (int) $tender_id;
        $sql = "SELECT ti.*, si.item_name, si.item_code, su.unit_name
                  FROM tender_items ti
                  LEFT JOIN store_items si ON si.id = ti.item_id
                  LEFT JOIN store_units su ON su.id = si.unit_id
                 WHERE ti.tender_id = $tender_id
                 ORDER BY ti.id";
        return mysqli_query($db, $sql);
    }

    // =====================================================================
    // Bids
    // =====================================================================

    /**
     * Record a supplier's bid. Re-submitting for the same supplier replaces the
     * previous one, so a corrected quotation does not create a second bid.
     */
    public function saveBid($tender_id, array $b, array $rates)
    {
        $db = $this->db();
        $tender_id = (int) $tender_id;

        $t = $this->loadEntity('TENDER', $tender_id);
        if (!$t) { return array('ok' => false, 'error' => 'Tender not found.'); }
        if (!in_array($t['status'], array('PUBLISHED', 'UNDER_EVALUATION'), true)) {
            return array('ok' => false, 'error' => 'Bids can only be recorded against a published tender.');
        }

        $supplier_id = (int) $b['supplier_id'];
        if ($supplier_id <= 0) { return array('ok' => false, 'error' => 'Choose a supplier.'); }

        // line values, from the tender's quantities
        $qty = array();
        $res = mysqli_query($db, "SELECT id, item_id, quantity FROM tender_items WHERE tender_id = $tender_id");
        while ($r = mysqli_fetch_assoc($res)) { $qty[(int) $r['id']] = $r; }

        $lines = array();
        $total = 0.0;
        foreach ($rates as $tender_item_id => $line) {
            $tid = (int) $tender_item_id;
            if (!isset($qty[$tid])) { continue; }
            $rate = isset($line['rate']) ? (float) $line['rate'] : 0;
            if ($rate <= 0) { continue; }
            $gst  = isset($line['gst_percent']) ? (float) $line['gst_percent'] : 0;
            $net  = $rate + ($rate * $gst / 100);
            $amt  = $net * (float) $qty[$tid]['quantity'];
            $total += $amt;
            $lines[] = array(
                'tender_item_id' => $tid,
                'item_id'        => (int) $qty[$tid]['item_id'],
                'rate'           => $rate,
                'gst_percent'    => $gst,
                'net_rate'       => $net,
                'amount'         => $amt,
                'brand'          => isset($line['brand']) ? $line['brand'] : '',
                'remarks'        => isset($line['remarks']) ? $line['remarks'] : '',
            );
        }
        if (!$lines) { return array('ok' => false, 'error' => 'Enter a rate for at least one item.'); }

        $actor = $this->actor();
        mysqli_begin_transaction($db);
        try {
            // Replace any previous bid from this supplier — but never one an
            // award already points at. Deleting it would leave tender_awards
            // referencing a bid id that no longer exists, and the award is the
            // document the purchase order is built from.
            $old = mysqli_query($db, "SELECT id FROM tender_bids
                                       WHERE tender_id=$tender_id AND supplier_id=$supplier_id LIMIT 1");
            if ($old && $row = mysqli_fetch_assoc($old)) {
                $oldId = (int) $row['id'];
                $used = mysqli_query($db, "SELECT id FROM tender_awards
                                            WHERE bid_id=$oldId AND status <> 'REJECTED' LIMIT 1");
                if ($used && mysqli_num_rows($used) > 0) {
                    throw new Exception('This bid has already been awarded and cannot be replaced.');
                }
                mysqli_query($db, "DELETE FROM tender_bid_items WHERE bid_id=$oldId");
                mysqli_query($db, "DELETE FROM tender_bids WHERE id=$oldId");
            }

            $st = $db->prepare("INSERT INTO tender_bids
                                  (tender_id, supplier_id, bid_reference, bid_date, total_amount,
                                   delivery_days, payment_terms, warranty_terms, emd_received,
                                   technical_status, status, remarks, created_by)
                                VALUES (?,?,?,?,?,?,?,?,?,?,'SUBMITTED',?,?)");
            $bidDate = ($b['bid_date'] === '') ? null : $b['bid_date'];
            $days    = (int) $b['delivery_days'];
            $emd     = !empty($b['emd_received']) ? 1 : 0;
            $tech    = isset($b['technical_status']) ? $b['technical_status'] : 'Pending';
            $st->bind_param('iissdississs', $tender_id, $supplier_id, $b['bid_reference'], $bidDate,
                            $total, $days, $b['payment_terms'], $b['warranty_terms'], $emd,
                            $tech, $b['remarks'], $actor);
            if (!$st->execute()) { throw new Exception('Could not save the bid: ' . $st->error); }
            $bid_id = $st->insert_id;
            $st->close();

            $st = $db->prepare("INSERT INTO tender_bid_items
                                  (bid_id, tender_item_id, item_id, rate, gst_percent, net_rate, amount, brand, remarks)
                                VALUES (?,?,?,?,?,?,?,?,?)");
            foreach ($lines as $l) {
                $st->bind_param('iiiddddss', $bid_id, $l['tender_item_id'], $l['item_id'], $l['rate'],
                                $l['gst_percent'], $l['net_rate'], $l['amount'], $l['brand'], $l['remarks']);
                if (!$st->execute()) { throw new Exception('Could not save a bid line: ' . $st->error); }
            }
            $st->close();

            mysqli_commit($db);
            return array('ok' => true, 'bid_id' => $bid_id, 'total' => $total);
        } catch (Exception $e) {
            mysqli_rollback($db);
            return array('ok' => false, 'error' => $e->getMessage());
        }
    }

    public function getBids($tender_id)
    {
        $db = $this->db();
        $tender_id = (int) $tender_id;
        $sql = "SELECT b.*, s.supplier_name, s.contact_person, s.phone
                  FROM tender_bids b
                  LEFT JOIN store_suppliers s ON s.id = b.supplier_id
                 WHERE b.tender_id = $tender_id AND b.status = 'SUBMITTED'
                 ORDER BY b.total_amount ASC";
        return mysqli_query($db, $sql);
    }

    public function setBidTechnicalStatus($bid_id, $status, $reason = '')
    {
        $db = $this->db();
        $bid_id = (int) $bid_id;
        if (!in_array($status, array('Pending', 'Qualified', 'Disqualified'), true)) {
            return array('ok' => false, 'error' => 'Unknown technical status.');
        }
        $st = $db->prepare("UPDATE tender_bids SET technical_status=?, disqualify_reason=? WHERE id=?");
        $st->bind_param('ssi', $status, $reason, $bid_id);
        $st->execute();
        $st->close();
        return array('ok' => true);
    }

    /**
     * Comparative statement: every tender line against every qualified bid,
     * with the lowest net rate marked. Disqualified bids are shown but never
     * rank — a technically rejected supplier must not win on price.
     */
    public function comparison($tender_id)
    {
        $db = $this->db();
        $tender_id = (int) $tender_id;

        $items = array();
        $res = mysqli_query($db, "SELECT ti.id, ti.item_id, ti.quantity, si.item_name, si.item_code, su.unit_name
                                    FROM tender_items ti
                                    LEFT JOIN store_items si ON si.id = ti.item_id
                                    LEFT JOIN store_units su ON su.id = si.unit_id
                                   WHERE ti.tender_id = $tender_id ORDER BY ti.id");
        while ($r = mysqli_fetch_assoc($res)) { $items[(int) $r['id']] = $r; $items[(int) $r['id']]['bids'] = array(); }

        $bids = array();
        $res = mysqli_query($db, "SELECT b.id, b.supplier_id, b.total_amount, b.technical_status,
                                         b.delivery_days, s.supplier_name
                                    FROM tender_bids b
                                    LEFT JOIN store_suppliers s ON s.id = b.supplier_id
                                   WHERE b.tender_id = $tender_id AND b.status='SUBMITTED'
                                   ORDER BY b.total_amount ASC");
        while ($r = mysqli_fetch_assoc($res)) { $bids[(int) $r['id']] = $r; }

        if ($bids) {
            $in = implode(',', array_keys($bids));
            $res = mysqli_query($db, "SELECT * FROM tender_bid_items WHERE bid_id IN ($in)");
            while ($r = mysqli_fetch_assoc($res)) {
                $tid = (int) $r['tender_item_id'];
                if (isset($items[$tid])) { $items[$tid]['bids'][(int) $r['bid_id']] = $r; }
            }
        }

        // mark the lowest qualified net rate on each line
        foreach ($items as $tid => $row) {
            $best = null; $bestBid = null;
            foreach ($row['bids'] as $bid_id => $line) {
                if (!isset($bids[$bid_id]) || $bids[$bid_id]['technical_status'] === 'Disqualified') { continue; }
                $net = (float) $line['net_rate'];
                if ($best === null || $net < $best) { $best = $net; $bestBid = $bid_id; }
            }
            $items[$tid]['lowest_bid_id'] = $bestBid;
            $items[$tid]['lowest_rate']   = $best;
        }

        // overall L1: cheapest qualified bid by total
        $l1 = null;
        foreach ($bids as $id => $b) {
            if ($b['technical_status'] === 'Disqualified') { continue; }
            if ($l1 === null || (float) $b['total_amount'] < (float) $bids[$l1]['total_amount']) { $l1 = $id; }
        }
        return array('items' => $items, 'bids' => $bids, 'l1_bid_id' => $l1);
    }

    // =====================================================================
    // Award
    // =====================================================================

    /**
     * Propose an award. Awarding anything but the lowest qualified bid demands
     * a written reason — that is what a purchase committee is later asked for.
     */
    public function createAward($tender_id, $bid_id, $deviation_reason = '')
    {
        $db = $this->db();
        $tender_id = (int) $tender_id;
        $bid_id = (int) $bid_id;

        $t = $this->loadEntity('TENDER', $tender_id);
        if (!$t) { return array('ok' => false, 'error' => 'Tender not found.'); }
        if ($t['status'] !== 'UNDER_EVALUATION') {
            return array('ok' => false, 'error' => 'Close the tender for bidding before awarding it.');
        }

        $res = mysqli_query($db, "SELECT * FROM tender_bids WHERE id=$bid_id AND tender_id=$tender_id LIMIT 1");
        $bid = $res ? mysqli_fetch_assoc($res) : null;
        if (!$bid) { return array('ok' => false, 'error' => 'Bid not found for this tender.'); }
        if ($bid['technical_status'] === 'Disqualified') {
            return array('ok' => false, 'error' => 'A technically disqualified bid cannot be awarded.');
        }

        $cmp = $this->comparison($tender_id);
        $isLowest = ($cmp['l1_bid_id'] !== null && (int) $cmp['l1_bid_id'] === $bid_id) ? 1 : 0;
        if (!$isLowest && trim($deviation_reason) === '') {
            return array('ok' => false,
                         'error' => 'This is not the lowest qualified bid — record why it is being awarded.');
        }

        $existing = mysqli_query($db, "SELECT id FROM tender_awards
                                        WHERE tender_id=$tender_id AND status <> 'REJECTED' LIMIT 1");
        if ($existing && mysqli_num_rows($existing) > 0) {
            return array('ok' => false, 'error' => 'This tender already has an award in progress.');
        }

        $number = $this->nextNumber($db, 'tender_awards', 'award_no', 'AWD');
        $actor  = $this->actor();
        $amount = (float) $bid['total_amount'];
        $supplier_id = (int) $bid['supplier_id'];

        $st = $db->prepare("INSERT INTO tender_awards
                              (award_no, tender_id, bid_id, supplier_id, award_amount,
                               is_lowest, deviation_reason, status, created_by)
                            VALUES (?,?,?,?,?,?,?,'PENDING_APPROVAL',?)");
        $st->bind_param('siiidiss', $number, $tender_id, $bid_id, $supplier_id, $amount,
                        $isLowest, $deviation_reason, $actor);
        if (!$st->execute()) {
            $err = $st->error; $st->close();
            return array('ok' => false, 'error' => 'Could not save the award: ' . $err);
        }
        $id = $st->insert_id;
        $st->close();

        return array('ok' => true, 'id' => $id, 'award_no' => $number, 'is_lowest' => (bool) $isLowest);
    }

    public function getAwards($tender_id = 0)
    {
        $db = $this->db();
        $cond = '';
        if ($tender_id > 0) { $cond = ' WHERE a.tender_id = ' . (int) $tender_id; }
        $sql = "SELECT a.*, t.tender_no, t.title, s.supplier_name
                  FROM tender_awards a
                  LEFT JOIN tenders t ON t.id = a.tender_id
                  LEFT JOIN store_suppliers s ON s.id = a.supplier_id
                  $cond
                 ORDER BY a.created_at DESC";
        return mysqli_query($db, $sql);
    }

    /**
     * Turn an approved award into a purchase order.
     *
     * This is the only place the module writes outside its own tables, and it
     * writes exactly the shape store::addPurchaseOrder() does, so GRN, stock
     * and payment treat it as any other PO. Store code is not involved.
     */
    public function generatePurchaseOrder($award_id)
    {
        $db = $this->db();
        $award_id = (int) $award_id;

        $award = $this->loadEntity('AWARD', $award_id);
        if (!$award) { return array('ok' => false, 'error' => 'Award not found.'); }
        if ($award['status'] !== 'APPROVED') {
            return array('ok' => false, 'error' => 'The award must be fully approved first.');
        }
        if (!empty($award['po_id'])) {
            return array('ok' => false, 'error' => 'A purchase order already exists for this award.');
        }

        $bid_id = (int) $award['bid_id'];
        $res = mysqli_query($db, "SELECT b.*, t.id AS tid, t.requisition_id
                                    FROM tender_bids b
                                    LEFT JOIN tenders t ON t.id = b.tender_id
                                   WHERE b.id=$bid_id LIMIT 1");
        $bid = $res ? mysqli_fetch_assoc($res) : null;
        if (!$bid) { return array('ok' => false, 'error' => 'Winning bid not found.'); }

        // the store the requisition came from, so the PO lands in the right place
        $store_id = 0;
        if (!empty($bid['requisition_id'])) {
            $rq = mysqli_query($db, "SELECT store_id FROM procurement_requisitions WHERE id=" . (int) $bid['requisition_id']);
            if ($rq && $r = mysqli_fetch_assoc($rq)) { $store_id = (int) $r['store_id']; }
        }

        $po_number = $this->nextNumber($db, 'store_purchase_orders', 'po_number', 'PO');
        $actor     = $this->actor();
        $supplier  = (int) $award['supplier_id'];
        $total     = (float) $award['award_amount'];
        $terms     = (string) $bid['payment_terms'];
        $expected  = date('Y-m-d', strtotime('+' . max(1, (int) $bid['delivery_days']) . ' days'));
        $remarks   = 'Generated from tender award ' . $award['award_no'];

        mysqli_begin_transaction($db);
        try {
            $st = $db->prepare("INSERT INTO store_purchase_orders
                                  (po_number, supplier_id, po_date, expected_date, payment_terms,
                                   total_amount, remarks, status, created_by, store_id)
                                VALUES (?,?,CURDATE(),?,?,?,?,'PENDING',?,?)");
            $st->bind_param('sissdssi', $po_number, $supplier, $expected, $terms,
                            $total, $remarks, $actor, $store_id);
            if (!$st->execute()) { throw new Exception('Could not create the purchase order: ' . $st->error); }
            $po_id = $st->insert_id;
            $st->close();

            $items = mysqli_query($db, "SELECT bi.*, ti.quantity
                                          FROM tender_bid_items bi
                                          LEFT JOIN tender_items ti ON ti.id = bi.tender_item_id
                                         WHERE bi.bid_id = $bid_id");
            $st = $db->prepare("INSERT INTO store_purchase_order_items
                                  (po_id, item_id, quantity, rate, gst_percent, gst_amount, net_amount, amount)
                                VALUES (?,?,?,?,?,?,?,?)");
            $lineCount = 0;
            while ($i = mysqli_fetch_assoc($items)) {
                $q         = (float) $i['quantity'];
                $rate      = (float) $i['rate'];
                $gstPct    = (float) $i['gst_percent'];
                $base      = $rate * $q;
                $gstAmount = $base * $gstPct / 100;
                $net       = $base + $gstAmount;
                $item_id   = (int) $i['item_id'];
                $st->bind_param('iiddddd' . 'd', $po_id, $item_id, $q, $rate, $gstPct,
                                $gstAmount, $net, $net);
                if (!$st->execute()) { throw new Exception('Could not add a purchase order line: ' . $st->error); }
                $lineCount++;
            }
            $st->close();
            if ($lineCount === 0) { throw new Exception('The winning bid has no priced lines.'); }

            $st = $db->prepare("UPDATE tender_awards
                                   SET status='PO_GENERATED', po_id=?, po_number=?, po_generated_at=NOW()
                                 WHERE id=?");
            $st->bind_param('isi', $po_id, $po_number, $award_id);
            $st->execute();
            $st->close();

            $tid = (int) $award['tender_id'];
            mysqli_query($db, "UPDATE tenders SET status='AWARDED' WHERE id=$tid");
            if (!empty($bid['requisition_id'])) {
                $rid = (int) $bid['requisition_id'];
                mysqli_query($db, "UPDATE procurement_requisitions SET status='CLOSED' WHERE id=$rid");
            }

            mysqli_commit($db);
            return array('ok' => true, 'po_id' => $po_id, 'po_number' => $po_number, 'lines' => $lineCount);
        } catch (Exception $e) {
            mysqli_rollback($db);
            return array('ok' => false, 'error' => $e->getMessage());
        }
    }

    // =====================================================================
    // Master data (read-only from the store module)
    // =====================================================================

    public function storeItems()
    {
        $db = $this->db();
        return mysqli_query($db, "SELECT si.id, si.item_code, si.item_name, su.unit_name
                                    FROM store_items si
                                    LEFT JOIN store_units su ON su.id = si.unit_id
                                   ORDER BY si.item_name");
    }

    public function storeSuppliers()
    {
        $db = $this->db();
        return mysqli_query($db, "SELECT id, supplier_name FROM store_suppliers ORDER BY supplier_name");
    }

    public function subStores()
    {
        $db = $this->db();
        return mysqli_query($db, "SELECT id, store_name FROM sub_store WHERE status='active' ORDER BY store_name");
    }

    /** Headline counts for the dashboard. */
    public function dashboardCounts()
    {
        $db = $this->db();
        $q = function ($sql) use ($db) {
            $r = mysqli_query($db, $sql);
            return $r ? (int) mysqli_fetch_row($r)[0] : 0;
        };
        return array(
            'req_pending'   => $q("SELECT COUNT(*) FROM procurement_requisitions WHERE status='SUBMITTED'"),
            'req_approved'  => $q("SELECT COUNT(*) FROM procurement_requisitions WHERE status='APPROVED'"),
            'tender_open'   => $q("SELECT COUNT(*) FROM tenders WHERE status='PUBLISHED'"),
            'tender_eval'   => $q("SELECT COUNT(*) FROM tenders WHERE status='UNDER_EVALUATION'"),
            'award_pending' => $q("SELECT COUNT(*) FROM tender_awards WHERE status='PENDING_APPROVAL'"),
            'po_generated'  => $q("SELECT COUNT(*) FROM tender_awards WHERE status='PO_GENERATED'"),
        );
    }
}

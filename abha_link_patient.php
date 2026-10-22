<?php
/**
 * abha_link_patient.php
 * ---------------------------------------------------------------------------
 * M1 TC-011 / TC-017 / TC-023 / TC-025 — link a verified/fetched ABHA profile
 * to a HIMS patient (central_opd) and enforce a UNIQUE ABHA <-> patient mapping.
 *
 * Actions (JSON body, field `page`):
 *   - check_duplicate : is this ABHA already mapped to an OPD/IPD patient?
 *   - search_patients : find existing patients to link against
 *   - link_patient    : write ABHA number/address onto a chosen central_opd row
 *
 * "Register in HIMS" (creating a brand-new patient) is handled by redirecting
 * to opregistration.php with the ABHA profile prefilled — not here.
 *
 * All queries use prepared statements (project rule: bind_param, never PDO).
 */

// Forces the project session save path and starts the session (store_consent
// stamps $_SESSION['name'] onto the audit row).
require_once __DIR__ . '/model/session_config.php';

header('Content-Type: application/json');

include_once __DIR__ . '/model/dbconnect.php';

$raw   = file_get_contents('php://input');
$input = json_decode($raw, true);
if (!is_array($input)) {
    $input = $_POST;
}

$page = isset($input['page']) ? preg_replace('/[^a-zA-Z0-9_]/', '', $input['page']) : '';

/** Normalise an ABHA number to the 14-digit form (strip spaces/hyphens). */
function normAbha($v) {
    return preg_replace('/[^0-9]/', '', (string) $v);
}

try {
    $db  = new userlogin();
    $con = $db->dbconnect();
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => 'Database connection failed']);
    exit;
}

/**
 * Look for an existing patient (OPD first, then IPD) already carrying this ABHA
 * number. Matches on either the raw 14-digit number or the hyphenated form.
 * Returns [source, ref, name] or null.
 */
function findExistingByAbha(mysqli $con, string $abhaNorm) {
    if ($abhaNorm === '') {
        return null;
    }

    // OPD
    $sql = "SELECT opd_no, name FROM central_opd
            WHERE REPLACE(REPLACE(abha_number,'-',''),' ','') = ? AND abha_number <> ''
            ORDER BY id DESC LIMIT 1";
    $st = $con->prepare($sql);
    $st->bind_param('s', $abhaNorm);
    $st->execute();
    $r = $st->get_result()->fetch_assoc();
    $st->close();
    if ($r) {
        return ['source' => 'opd', 'ref' => $r['opd_no'], 'name' => $r['name']];
    }

    // IPD
    $sql = "SELECT ipd_no, name FROM central_ipd
            WHERE REPLACE(REPLACE(abha_number,'-',''),' ','') = ? AND abha_number <> ''
            ORDER BY id DESC LIMIT 1";
    $st = $con->prepare($sql);
    $st->bind_param('s', $abhaNorm);
    $st->execute();
    $r = $st->get_result()->fetch_assoc();
    $st->close();
    if ($r) {
        return ['source' => 'ipd', 'ref' => $r['ipd_no'], 'name' => $r['name']];
    }

    return null;
}

// ── check_duplicate ─────────────────────────────────────────────────────────
if ($page === 'check_duplicate') {
    $abha = normAbha($input['abha_number'] ?? '');
    if ($abha === '') {
        echo json_encode(['success' => false, 'error' => 'Missing abha_number']);
        exit;
    }
    $hit = findExistingByAbha($con, $abha);
    echo json_encode([
        'success' => true,
        'exists'  => $hit ? true : false,
        'patient' => $hit,
    ]);
    exit;

// ── check_abha_address ──────────────────────────────────────────────────────
// Does this EXACT ABHA address already sit on a central_opd row? Used right after
// an ABHA-address verification to decide whether to offer new-patient registration.
// Matches on abha_address only (one ABHA number can have several addresses).
} elseif ($page === 'check_abha_address') {
    $addr = strtolower(trim($input['abha_address'] ?? ''));
    if ($addr === '') {
        echo json_encode(['success' => false, 'error' => 'Missing abha_address']);
        exit;
    }
    $st = $con->prepare(
        "SELECT opd_no, name FROM central_opd
         WHERE abha_address = ? AND abha_address <> '' ORDER BY id DESC LIMIT 1"
    );
    $st->bind_param('s', $addr);
    $st->execute();
    $row = $st->get_result()->fetch_assoc();
    $st->close();
    echo json_encode([
        'success' => true,
        'exists'  => $row ? true : false,
        'patient' => $row ? ['source' => 'opd', 'ref' => $row['opd_no'], 'name' => $row['name']] : null,
    ]);
    exit;

// ── list_abha_addresses ─────────────────────────────────────────────────────
// All verified ABHA addresses on record for a given ABHA number (one number can
// own several: name@sbx, name@abdm, …). Used by the follow-up page to offer a
// dropdown when more than one exists.
} elseif ($page === 'list_abha_addresses') {
    $abha = normAbha($input['abha_number'] ?? '');
    if ($abha === '') {
        echo json_encode(['success' => false, 'error' => 'Missing abha_number']);
        exit;
    }
    $rows = [];
    $has  = $con->query("SHOW TABLES LIKE 'abha_addresses'");
    if ($has && $has->num_rows > 0) {
        $st = $con->prepare(
            "SELECT LOWER(abha_address) AS abha_address, is_preferred FROM abha_addresses
             WHERE abha_number = ? AND abha_address <> ''
             ORDER BY is_preferred DESC, id ASC"
        );
        $st->bind_param('s', $abha);
        $st->execute();
        $r = $st->get_result();
        while ($row = $r->fetch_assoc()) {
            $rows[] = $row;
        }
        $st->close();
    }
    echo json_encode(['success' => true, 'addresses' => $rows]);
    exit;

// ── search_patients ─────────────────────────────────────────────────────────
} elseif ($page === 'search_patients') {
    $q = trim($input['q'] ?? '');
    if (strlen($q) < 2) {
        echo json_encode(['success' => false, 'error' => 'Enter at least 2 characters']);
        exit;
    }
    $like = '%' . $q . '%';
    $pre  = $q . '%';
    // central_opd here stores age only — no dob column. Kept in the response as
    // an empty string so the compare table on the client keeps its shape.
    $sql  = "SELECT opd_no, uhid, name, '' AS dob, age, gender, mobile, address, abha_number
             FROM central_opd
             WHERE name LIKE ? OR mobile LIKE ? OR opd_no LIKE ? OR uhid LIKE ?
             ORDER BY id DESC LIMIT 20";
    $st = $con->prepare($sql);
    $st->bind_param('ssss', $like, $pre, $pre, $pre);
    $st->execute();
    $res  = $st->get_result();
    $rows = [];
    while ($row = $res->fetch_assoc()) {
        $rows[] = $row;
    }
    $st->close();
    echo json_encode(['success' => true, 'patients' => $rows]);
    exit;

// ── link_patient ────────────────────────────────────────────────────────────
} elseif ($page === 'link_patient') {
    $opdNo    = trim($input['opd_no'] ?? '');
    $abha     = normAbha($input['abha_number'] ?? '');
    $abhaAddr = strtolower(trim($input['abha_address'] ?? ''));

    if ($opdNo === '' || $abha === '') {
        echo json_encode(['success' => false, 'error' => 'Missing opd_no or abha_number']);
        exit;
    }

    // TC-025: this ABHA must not already belong to a DIFFERENT patient.
    $existing = findExistingByAbha($con, $abha);
    if ($existing && !($existing['source'] === 'opd' && $existing['ref'] === $opdNo)) {
        echo json_encode([
            'success'  => false,
            'error'    => 'This ABHA is already linked to another patient (' .
                          strtoupper($existing['source']) . ' ' . $existing['ref'] . ' — ' . $existing['name'] . ').',
            'existing' => $existing,
        ]);
        exit;
    }

    // Fetch the target patient and make sure it is not already linked to a
    // DIFFERENT ABHA (one patient <-> one ABHA, the reverse of TC-025).
    $st = $con->prepare("SELECT name, abha_number FROM central_opd WHERE opd_no = ? LIMIT 1");
    $st->bind_param('s', $opdNo);
    $st->execute();
    $target = $st->get_result()->fetch_assoc();
    $st->close();

    if (!$target) {
        echo json_encode(['success' => false, 'error' => 'Patient ' . $opdNo . ' not found']);
        exit;
    }
    $targetAbha = normAbha($target['abha_number']);
    if ($targetAbha !== '' && $targetAbha !== $abha) {
        echo json_encode([
            'success' => false,
            'error'   => 'Patient ' . $opdNo . ' is already linked to a different ABHA number.',
        ]);
        exit;
    }

    // Store the 14-digit form; central_opd already carries these two columns.
    $st = $con->prepare("UPDATE central_opd SET abha_number = ?, abha_address = ? WHERE opd_no = ?");
    $st->bind_param('sss', $abha, $abhaAddr, $opdNo);
    $ok = $st->execute();
    $st->close();

    if ($ok) {
        echo json_encode([
            'success' => true,
            'message' => 'ABHA linked to patient ' . $opdNo,
            'patient' => ['opd_no' => $opdNo, 'name' => $target['name']],
        ]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Failed to update patient record']);
    }
    exit;

// ── store_abha_address ──────────────────────────────────────────────────────
// A single ABHA number can own several ABHA addresses (e.g. name@sbx, name@abdm).
// central_opd.abha_address holds only one, so overwriting it would drop the rest.
// Here we ACCUMULATE every verified address in its own table — idempotent, so it
// is safe to call after each verification without creating duplicates.
} elseif ($page === 'store_abha_address') {
    $abha     = normAbha($input['abha_number'] ?? '');
    $fullName = trim($input['full_name'] ?? '');
    $preferred = strtolower(trim($input['preferred_abha_address'] ?? ''));

    // Accept a single address or an array of them.
    $addrs = $input['abha_addresses'] ?? ($input['abha_address'] ?? []);
    if (!is_array($addrs)) {
        $addrs = [$addrs];
    }

    // Trim, drop empties, de-dupe.
    $clean = [];
    foreach ($addrs as $a) {
        $a = strtolower(trim((string) $a));
        if ($a !== '' && !in_array($a, $clean, true)) {
            $clean[] = $a;
        }
    }

    if ($abha === '' || count($clean) === 0) {
        echo json_encode(['success' => false, 'error' => 'Missing abha_number or abha_address']);
        exit;
    }

    // Idempotent create so no separate migration step is needed on deploy.
    $con->query(
        "CREATE TABLE IF NOT EXISTS `abha_addresses` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `abha_number` varchar(50) NOT NULL,
            `abha_address` varchar(120) NOT NULL,
            `full_name` varchar(150) DEFAULT NULL,
            `is_preferred` tinyint(1) NOT NULL DEFAULT 0,
            `created_at` datetime NOT NULL DEFAULT current_timestamp(),
            `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
            PRIMARY KEY (`id`),
            UNIQUE KEY `uniq_number_address` (`abha_number`, `abha_address`),
            KEY `idx_abha_number` (`abha_number`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci"
    );

    $st = $con->prepare(
        "INSERT INTO abha_addresses (abha_number, abha_address, full_name, is_preferred)
         VALUES (?, ?, ?, ?)
         ON DUPLICATE KEY UPDATE
            full_name    = VALUES(full_name),
            is_preferred = VALUES(is_preferred),
            updated_at   = current_timestamp()"
    );
    if (!$st) {
        echo json_encode(['success' => false, 'error' => 'Address store unavailable']);
        exit;
    }

    $stored = 0;
    foreach ($clean as $a) {
        $isPref = ($preferred !== '' && $a === $preferred) ? 1 : 0;
        $st->bind_param('sssi', $abha, $a, $fullName, $isPref);
        if ($st->execute()) {
            $stored++;
        }
    }
    $st->close();

    // Return the full set now on record for this number.
    $rows = [];
    $sel  = $con->prepare(
        "SELECT abha_address, is_preferred FROM abha_addresses
         WHERE abha_number = ? ORDER BY is_preferred DESC, id ASC"
    );
    $sel->bind_param('s', $abha);
    $sel->execute();
    $r = $sel->get_result();
    while ($row = $r->fetch_assoc()) {
        $rows[] = $row;
    }
    $sel->close();

    echo json_encode(['success' => true, 'stored' => $stored, 'addresses' => $rows]);
    exit;

// ── store_consent (TC-002) ──────────────────────────────────────────────────
} elseif ($page === 'store_consent') {
    $purpose = preg_replace('/[^a-zA-Z]/', '', $input['purpose'] ?? '');
    $abha    = normAbha($input['abha_number'] ?? '');
    $version = substr(trim($input['consent_version'] ?? ''), 0, 20);
    $lang    = preg_replace('/[^a-zA-Z-]/', '', substr((string)($input['language'] ?? ''), 0, 10));
    $text    = trim($input['consent_text'] ?? '');
    if (strlen($text) > 5000) {
        $text = substr($text, 0, 5000);
    }
    $user = $_SESSION['name'] ?? 'Unknown';
    $ip   = $_SERVER['REMOTE_ADDR'] ?? '';

    // Idempotent create so no separate migration step is needed on deploy.
    $con->query(
        "CREATE TABLE IF NOT EXISTS `abha_consent_log` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `purpose` varchar(20) NOT NULL,
            `abha_number` varchar(50) DEFAULT NULL,
            `consent_version` varchar(20) DEFAULT NULL,
            `language` varchar(10) DEFAULT NULL,
            `consent_text` text DEFAULT NULL,
            `user_name` varchar(150) DEFAULT NULL,
            `ip_address` varchar(64) DEFAULT NULL,
            `created_at` datetime NOT NULL DEFAULT current_timestamp(),
            PRIMARY KEY (`id`),
            KEY `idx_abha_number` (`abha_number`),
            KEY `idx_created_at` (`created_at`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci"
    );

    $st = $con->prepare(
        "INSERT INTO abha_consent_log
            (purpose, abha_number, consent_version, language, consent_text, user_name, ip_address)
         VALUES (?, ?, ?, ?, ?, ?, ?)"
    );
    if (!$st) {
        echo json_encode(['success' => false, 'error' => 'Consent log unavailable']);
        exit;
    }
    $st->bind_param('sssssss', $purpose, $abha, $version, $lang, $text, $user, $ip);
    $ok = $st->execute();
    $id = $st->insert_id;
    $st->close();

    echo json_encode(['success' => (bool) $ok, 'id' => $id]);
    exit;

// ── unknown ─────────────────────────────────────────────────────────────────
} else {
    echo json_encode(['success' => false, 'error' => 'Unknown action']);
    exit;
}

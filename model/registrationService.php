<?php
/**
 * OPD registration and IPD admission without the web form, used by
 * api/opd_registration.php, api/ipd_registration.php and bulk_registration.php.
 *
 * Mirrors addOP::insertOP() and addIP::insertIP() (numbering, columns, side
 * effects) but uses prepared statements and returns a result instead of
 * echoing a redirect:
 *   ['ok' => bool, 'code' => HTTP-style status, 'message' => string, 'data' => array|null]
 *
 * Department, doctor and bed may be given as ids or by name, so a CSV can be
 * filled in by hand.
 */
require_once(__DIR__ . '/env.php');
date_default_timezone_set("Asia/Kolkata");

function reg_result($ok, $code, $message, $data = null)
{
    return ['ok' => $ok, 'code' => $code, 'message' => $message, 'data' => $data];
}

/** Trimmed string field, '' when missing. Arrays are joined with ','. */
function reg_field($in, $key, $default = '')
{
    if (!isset($in[$key])) return $default;
    $v = is_array($in[$key]) ? implode(',', $in[$key]) : trim((string) $in[$key]);
    return $v === '' ? $default : $v;
}

/**
 * First row of a lookup: by integer id when $value is all digits, else by name.
 * Kept apart so MySQL never casts a name like "3 WARD" to the id 3.
 */
function reg_lookup($con, $byIdSql, $byNameSql, $value)
{
    $stmt = $con->prepare(ctype_digit($value) ? $byIdSql : $byNameSql);
    $stmt->bind_param('s', $value);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    return $row ?: null;
}

/** department id from an id or a department name. */
function reg_resolve_department($con, $value)
{
    if ($value === '') return null;
    $row = reg_lookup($con,
        "SELECT id FROM department WHERE id = ? LIMIT 1",
        "SELECT id FROM department WHERE UPPER(TRIM(dept_name)) = UPPER(TRIM(?)) LIMIT 1",
        $value);
    return $row ? (string) $row['id'] : null;
}

/** doctor row (id, full_name) from an id or "First Last" name. */
function reg_resolve_doctor($con, $value)
{
    if ($value === '') return null;
    $cols = "id, TRIM(CONCAT(name,' ',last_name)) AS full_name";
    return reg_lookup($con,
        "SELECT $cols FROM doctor WHERE id = ? LIMIT 1",
        "SELECT $cols FROM doctor WHERE UPPER(TRIM(CONCAT(name,' ',last_name))) = UPPER(TRIM(?)) LIMIT 1",
        $value);
}

/**
 * bed row from bed.id, or else from a bed number. A bed number is matched within
 * the ward when one is given, since numbers can repeat across wards.
 */
function reg_resolve_bed($con, $bedId, $bedNumber, $ward = '')
{
    $cols = "id, bed_number, ward, status";
    if ($bedId !== '') {
        $stmt = $con->prepare("SELECT $cols FROM bed WHERE id = ? LIMIT 1");
        $id = (int) $bedId;
        $stmt->bind_param('i', $id);
    } elseif ($bedNumber !== '') {
        $stmt = $con->prepare("SELECT $cols FROM bed WHERE UPPER(TRIM(bed_number)) = UPPER(TRIM(?))
                                 AND (? = '' OR UPPER(TRIM(ward)) = UPPER(TRIM(?)))
                               ORDER BY status = 'occupied', id LIMIT 1");
        $stmt->bind_param('sss', $bedNumber, $ward, $ward);
    } else {
        return null;
    }
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    return $row ?: null;
}

function reg_valid_date($v)
{
    return (bool) preg_match('/^\d{4}-\d{2}-\d{2}$/', $v) && strtotime($v) !== false;
}

/** Next OPD no / UHID sequence for a date, as addOP::getNextCentralOpdSequence(). */
function reg_next_opd_seq($con, $dateKey, $column)
{
    $start = $column === 'uhid' ? 4 : 3;
    $row = mysqli_fetch_assoc(mysqli_query($con,
        "SELECT SUBSTR(MAX(`$column`),$start,12) AS seq FROM central_opd WHERE `$column` LIKE '%$dateKey%'"));
    return (isset($row['seq']) && trim((string) $row['seq']) !== '') ? (string) ($row['seq'] + 1) : $dateKey . '0001';
}

/** Same audit trail the web forms write to. */
function reg_log_query($con, $sql, $createdBy)
{
    $now = date('Y-m-d H:i:s');
    $stmt = $con->prepare("INSERT INTO `query`(`sql_query`, `created_by`, `created_at`) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $sql, $createdBy, $now);
    $stmt->execute();
    $stmt->close();
}

/**
 * Register a new OPD patient.
 * Required: name, gender (Male/Female/Other), age, mobile (10 digits), department, doctor.
 */
function reg_register_opd($con, array $in, $createdBy)
{
    $missing = [];
    foreach (['name', 'gender', 'age', 'mobile', 'department', 'doctor'] as $k) {
        if (reg_field($in, $k) === '') $missing[] = $k;
    }
    if ($missing) return reg_result(false, 422, 'Missing required fields: ' . implode(', ', $missing));

    $gender = ucfirst(strtolower(reg_field($in, 'gender')));
    if ($gender === 'M') $gender = 'Male';
    if ($gender === 'F') $gender = 'Female';
    if (!in_array($gender, ['Male', 'Female', 'Other'], true)) {
        return reg_result(false, 422, 'gender must be Male, Female or Other');
    }
    $mobile = preg_replace('/\D+/', '', reg_field($in, 'mobile'));
    if (!preg_match('/^[0-9]{10}$/', $mobile)) return reg_result(false, 422, 'mobile must be 10 digits');

    $date = reg_field($in, 'date', date('Y-m-d'));
    if (!reg_valid_date($date)) return reg_result(false, 422, 'date must be YYYY-MM-DD');
    $dob = reg_field($in, 'dob');
    if ($dob !== '' && !reg_valid_date($dob)) return reg_result(false, 422, 'dob must be YYYY-MM-DD');

    $deptId = reg_resolve_department($con, reg_field($in, 'department'));
    if ($deptId === null) return reg_result(false, 422, 'Unknown department: ' . reg_field($in, 'department'));
    $doc = reg_resolve_doctor($con, reg_field($in, 'doctor'));
    if ($doc === null) return reg_result(false, 422, 'Unknown doctor: ' . reg_field($in, 'doctor'));
    $doctor = (string) $doc['id']; // central_opd.doctor holds the doctor id

    $name = strtoupper(reg_field($in, 'name'));
    $economic = reg_field($in, 'economic');
    $bplNo = $economic === 'BPL' ? reg_field($in, 'bpl_no') : '';

    // OPD no / UHID: same scheme as addOP::insertOP().
    $dateKey = date('Ymd', strtotime($date));
    $opd_no = $uhid = null;
    $res = mysqli_query($con, "SELECT id, table_id FROM data_table WHERE id IN (1,4)");
    while ($row = mysqli_fetch_assoc($res)) {
        if ((int) $row['id'] === 1) $opd_no = $row['table_id'] + 1;
        if ((int) $row['id'] === 4) $uhid = $row['table_id'] + 1;
    }
    if ($dateKey !== substr((string) $opd_no, 0, 8)) $opd_no = reg_next_opd_seq($con, $dateKey, 'opd_no');
    if ($dateKey !== substr((string) $uhid, 0, 8))   $uhid = reg_next_opd_seq($con, $dateKey, 'uhid');

    // Token is per doctor per day.
    $stmt = $con->prepare("SELECT IFNULL(MAX(token),0)+1 AS tok FROM central_opd WHERE DATE(date)=? AND doctor=? AND is_generated=0");
    $stmt->bind_param('ss', $date, $doctor);
    $stmt->execute();
    $token = (int) $stmt->get_result()->fetch_assoc()['tok'];
    $stmt->close();

    // Pharmacy (RetailXpress) customer link, as the web form does. Skipped if no key configured.
    $rxId = '';
    $rxKey = (string) env('RETAILXPRESS_API_KEY', '');
    if ($rxKey !== '') {
        $rxId = reg_rx_find_or_create_customer($rxKey, $name, $mobile, reg_field($in, 'address'), strtoupper($gender), 'SGN' . $uhid);
    }

    $cols = ['uhid', 'opd_no', 'date', 'name', 'dob', 'age', 'gender', 'address', 'diagnosis', 'dept_id', 'sub_deptid',
        'religion', 'caste', 'parent_name', 'education', 'marital_status', 'occupation', 'economic_state', 'bpl_no',
        'annual_income', 'category', 'mobile', 'doctor', 'created_by', 'pan', 'aadhar', 'policy', 'patient_type',
        'token', 'city', 'mlc', 'rx_id', 'abha_number', 'abha_address', 'state', 'district', 'pincode'];
    $sql = "INSERT INTO central_opd (`" . implode('`,`', $cols) . "`,`created_at`) VALUES ("
        . implode(',', array_fill(0, count($cols), '?')) . ",NOW())";

    $id = 0;
    $vals = [];
    for ($attempt = 0; $attempt < 5; $attempt++) {
        $vals = ['SGN' . $uhid, 'OP' . $opd_no, $date, $name, $dob === '' ? null : $dob, reg_field($in, 'age'), $gender,
            reg_field($in, 'address'), reg_field($in, 'diagnosis'), $deptId, reg_field($in, 'sub_department'),
            reg_field($in, 'religion'), reg_field($in, 'caste'), reg_field($in, 'parent_name'), reg_field($in, 'education'),
            reg_field($in, 'marital_status'), reg_field($in, 'occupation'), $economic, $bplNo,
            reg_field($in, 'annual_income'), reg_field($in, 'category'), $mobile, $doctor, $createdBy,
            reg_field($in, 'pan'), reg_field($in, 'aadhar'), reg_field($in, 'policy'), reg_field($in, 'patient_type'),
            $token, reg_field($in, 'city'), reg_field($in, 'mlc', '0'), $rxId, reg_field($in, 'abha_number'),
            reg_field($in, 'abha_address'), reg_field($in, 'state'), reg_field($in, 'district'), reg_field($in, 'pincode')];
        $stmt = $con->prepare($sql);
        $stmt->bind_param(str_repeat('s', count($vals)), ...$vals);
        if ($stmt->execute()) {
            $id = $stmt->insert_id;
            $stmt->close();
            break;
        }
        $errno = $stmt->errno;
        $error = $stmt->error;
        $stmt->close();
        if ($errno !== 1062) return reg_result(false, 500, 'Registration failed: ' . $error);
        // Duplicate OPD no / UHID from a concurrent registration: take the next ones.
        $opd_no = reg_next_opd_seq($con, $dateKey, 'opd_no');
        $uhid = reg_next_opd_seq($con, $dateKey, 'uhid');
    }
    if ($id <= 0) return reg_result(false, 409, 'Could not allocate a unique OPD number, please retry');

    reg_log_query($con, "opd_registration id=$id " . json_encode(array_combine($cols, $vals)), $createdBy);
    mysqli_query($con, "UPDATE data_table SET table_id=" . (int) $opd_no . " WHERE id=1");
    mysqli_query($con, "UPDATE data_table SET table_id=" . (int) $uhid . " WHERE id=4");

    return reg_result(true, 201, 'OPD registration successful', [
        'id' => $id,
        'uhid' => 'SGN' . $uhid,
        'opd_no' => 'OP' . $opd_no,
        'token' => $token,
        'date' => $date,
        'name' => $name,
        'doctor' => $doctor,
        'rx_id' => $rxId,
    ]);
}

/**
 * Admit an existing OPD patient.
 * Required: opd_no, department, doctor, and bed_id or bed_number. ward defaults to the bed's ward.
 * Patient details default from the latest OPD visit for opd_no; any field given overrides them.
 */
function reg_register_ipd($con, array $in, $createdBy)
{
    $bedId = reg_field($in, 'bed_id');
    $bedNumber = reg_field($in, 'bed_number');
    $missing = [];
    foreach (['opd_no', 'department', 'doctor'] as $k) {
        if (reg_field($in, $k) === '') $missing[] = $k;
    }
    if ($bedId === '' && $bedNumber === '') $missing[] = 'bed_id or bed_number';
    if ($missing) return reg_result(false, 422, 'Missing required fields: ' . implode(', ', $missing));

    $admission = reg_field($in, 'admission_date', date('Y-m-d H:i:s'));
    if (strtotime($admission) === false) {
        return reg_result(false, 422, 'admission_date must be YYYY-MM-DD or YYYY-MM-DD HH:MM:SS');
    }
    $admission = date('Y-m-d H:i:s', strtotime($admission));

    $opdNo = reg_field($in, 'opd_no');
    $stmt = $con->prepare("SELECT * FROM central_opd WHERE opd_no=? ORDER BY id DESC LIMIT 1");
    $stmt->bind_param('s', $opdNo);
    $stmt->execute();
    $op = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    if (!$op) return reg_result(false, 404, 'No OPD registration found for opd_no ' . $opdNo);

    $deptId = reg_resolve_department($con, reg_field($in, 'department'));
    if ($deptId === null) return reg_result(false, 422, 'Unknown department: ' . reg_field($in, 'department'));
    $doc = reg_resolve_doctor($con, reg_field($in, 'doctor'));
    if ($doc === null) return reg_result(false, 422, 'Unknown doctor: ' . reg_field($in, 'doctor'));
    $doctor = $doc['full_name']; // central_ipd.doctor holds the doctor's name

    if ($bedId !== '' && !ctype_digit($bedId)) return reg_result(false, 422, 'bed_id must be a number');
    $bed = reg_resolve_bed($con, $bedId, $bedNumber, reg_field($in, 'ward'));
    if ($bed === null) return reg_result(false, 422, 'Unknown bed: ' . ($bedId !== '' ? $bedId : $bedNumber));
    $ward = reg_field($in, 'ward', (string) $bed['ward']);

    // Field from the request, else from the OPD visit.
    $f = function ($key, $opKey = null) use ($in, $op) {
        $v = reg_field($in, $key);
        return ($v === '' && $opKey !== null) ? (string) ($op[$opKey] ?? '') : $v;
    };

    // Same remark format as addIP::buildCashlessRemark().
    $ptype = $f('patient_type', 'patient_type');
    $policy = $f('policy', 'policy');
    $remark = $f('remark');
    if (strpos(strtoupper($ptype), 'CASHLESS') !== false) {
        $details = array_filter([$f('coverage_type'), $f('insurance_name')], 'strlen');
        if ($f('policy') !== '') $details[] = 'Policy/Claim: ' . $f('policy');
        if ($details) {
            $cashless = 'Cashless Details - ' . implode(' | ', $details);
            $remark = $remark !== '' ? $remark . ' | ' . $cashless : $cashless;
        }
    }

    $con->begin_transaction();

    // Claim the bed atomically so two admissions cannot take the same bed.
    $bedId = (int) $bed['id'];
    $stmt = $con->prepare("UPDATE bed SET status='occupied' WHERE id=? AND status<>'occupied'");
    $stmt->bind_param('i', $bedId);
    $stmt->execute();
    $claimed = $stmt->affected_rows;
    $stmt->close();
    if ($claimed !== 1) {
        $con->rollback();
        return reg_result(false, 409, 'Bed ' . $bed['bed_number'] . ' (' . $bed['ward'] . ') is already occupied');
    }

    // IPD no: IP + YYYYMMDD + 3-digit daily sequence, same as addIP::insertIP().
    $dateKey = date('Ymd', strtotime($admission));
    $row = mysqli_fetch_assoc(mysqli_query($con,
        "SELECT SUBSTR(MAX(ipd_no),3,12) AS ipd FROM central_ipd WHERE ipd_no LIKE '%$dateKey%' FOR UPDATE"));
    $ipd_no = (isset($row['ipd']) && $row['ipd'] !== '') ? (string) ($row['ipd'] + 1) : $dateKey . '001';

    $cols = ['ipd_no', 'opd_no', 'rx_id', 'admission_date', 'name', 'age', 'gender', 'mobile', 'category', 'address',
        'dept_id', 'religion', 'caste', 'parent_name', 'occupation', 'diagnosis', 'annual_income', 'doctor', 'mlc',
        'bed_id', 'ward', 'cmo', 'created_by', 'refer_by', 'remark', 'patient_type', 'policy', 'insurance_name', 'is_generated'];
    $vals = ['IP' . $ipd_no, $op['opd_no'], $f('ipcard_no', 'rx_id'), $admission, strtoupper($f('name', 'name')),
        $f('age', 'age'), $f('gender', 'gender'), $f('mobile', 'mobile'), $f('category', 'category'),
        $f('address', 'address'), $deptId, $f('religion', 'religion'), $f('caste', 'caste'),
        $f('parent_name', 'parent_name'), $f('occupation', 'occupation'), $f('diagnosis', 'diagnosis'),
        $f('annual_income', 'annual_income'), $doctor, $f('mlc') ?: '0', (string) $bedId, $ward, $f('cmo'),
        $createdBy, $f('refer_by'), $remark, $ptype, $policy, $f('insurance_name'), $f('is_generated') ?: '0'];

    $stmt = $con->prepare("INSERT INTO central_ipd (`" . implode('`,`', $cols) . "`) VALUES ("
        . implode(',', array_fill(0, count($cols), '?')) . ")");
    $stmt->bind_param(str_repeat('s', count($vals)), ...$vals);
    if (!$stmt->execute()) {
        $error = $stmt->error;
        $stmt->close();
        $con->rollback();
        return reg_result(false, 500, 'Admission failed: ' . $error);
    }
    $id = $stmt->insert_id;
    $stmt->close();

    mysqli_query($con, "UPDATE data_table SET table_id=" . (int) $ipd_no . " WHERE id=2");

    // Flag the OPD visit as admitted, as the web form does.
    $stmt = $con->prepare("UPDATE central_opd SET is_ipd=1, patient_type=?, policy=? WHERE opd_no=? AND date=DATE(?)");
    $stmt->bind_param('ssss', $ptype, $policy, $op['opd_no'], $admission);
    $stmt->execute();
    $stmt->close();

    $con->commit();
    reg_log_query($con, "ipd_registration id=$id " . json_encode(array_combine($cols, $vals)), $createdBy);

    return reg_result(true, 201, 'IPD admission successful', [
        'id' => $id,
        'ipd_no' => 'IP' . $ipd_no,
        'opd_no' => $op['opd_no'],
        'uhid' => $op['uhid'],
        'name' => $vals[4],
        'admission_date' => $admission,
        'ward' => $ward,
        'bed_id' => $bedId,
        'bed_number' => $bed['bed_number'],
        'doctor' => $doctor,
    ]);
}

function reg_rx_find_or_create_customer($apiKey, $name, $mobile, $address, $gender, $uniqueCode)
{
    $base = 'https://api.retailxpress.ai/v1/external/customer';
    $call = function ($method, $url, $body = null) use ($apiKey) {
        $ch = curl_init($url);
        $headers = ['x-api-key: ' . $apiKey];
        if ($body !== null) {
            $headers[] = 'Content-Type: application/json';
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));
        }
        curl_setopt_array($ch, [CURLOPT_RETURNTRANSFER => true, CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_HTTPHEADER => $headers, CURLOPT_TIMEOUT => 15]);
        $resp = curl_exec($ch);
        curl_close($ch);
        return json_decode((string) $resp, true);
    };

    $found = $call('GET', $base . '?page=1&limit=20&sort_by=createdAt&sort_order=desc&search=' . urlencode($mobile));
    $items = $found['data']['items'] ?? (isset($found['data'][0]) ? $found['data'] : []);
    if (!empty($items[0]['id']) && strcasecmp(trim($items[0]['full_name'] ?? ''), $name) === 0) {
        return (string) $items[0]['id'];
    }
    $created = $call('POST', $base, ['full_name' => $name, 'phone_number' => $mobile, 'address' => $address,
        'gender' => $gender, 'unique_code' => $uniqueCode]);
    return (string) ($created['data']['id'] ?? '');
}

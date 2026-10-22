<?php
header("Content-Type: application/json");

// ===== CONFIG =====
$SECRET_TOKEN = "71hdu0RWz4BdGb41SYPP40eDBM5Y5uOLgBdT6KFHEDOfrOh6eFvYQzQVoLAJfemK";

// ===== AUTH CHECK =====
$headers = getallheaders();
$auth = $headers['Authorization'] ?? '';

if ($auth !== "Bearer " . $SECRET_TOKEN) {
    http_response_code(401);
    echo json_encode(["status" => "error", "message" => "Unauthorized"]);
    exit;
}

// ===== GET INPUT =====
$input = file_get_contents("php://input");
$data = json_decode($input, true);

if (!$data) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Invalid JSON"]);
    exit;
}

// ===== REQUIRED FIELDS =====
$visit_no = $data['visit_no'] ?? '';
$testid   = $data['testid'] ?? '';
$parameters = $data['parameters'] ?? [];

if (!$visit_no || !$testid || empty($parameters)) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Missing data"]);
    exit;
}
require_once(__DIR__ . '/../model/dbconnect.php');
    $con = $login->dbconnect1();

if ($con->connect_error) {
    file_put_contents("api_error.log", "DB Connection Failed\n", FILE_APPEND);
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "DB Error"]);
    exit;
}

// ===== PROCESS PARAMETERS =====
foreach ($parameters as $param) {

    $machine_code = $param['code'] ?? '';
    $value = $param['value'] ?? '';
    $unit = $param['unit'] ?? '';
    $reference = $param['reference'] ?? '';
    $flag = $param['flag'] ?? '';

    if (!$machine_code) continue;

    // 🔹 Find parameter_id
    $stmt = $con->prepare("
        SELECT id 
        FROM lab_test_parameter
        WHERE test_id = ?
        AND machine_code = ?
        LIMIT 1
    ");
    $stmt->bind_param("is", $testid, $machine_code);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows == 0) {
        file_put_contents("api_error.log", "Mapping missing: $machine_code\n", FILE_APPEND);
        continue;
    }

    $row = $res->fetch_assoc();
    $parameter_id = $row['id'];

    // 🔹 Insert / Update result
    $insert = $con->prepare("
        INSERT INTO lab_report (
            parameter_id,
            date,
            test_result,
            patient_id,
            patient_type,
            testid,
            print_flag,
            parameter_type,
            visit_no,
            bill_no,
            lab_tech,
            unit,
            reference_range,
            abnormal_flag,
            source
        )
        VALUES (?, CURDATE(), ?, '', 'OPD', ?, 0, 'Numeric', ?, '', 'Machine', ?, ?, ?, 'machine')
        ON DUPLICATE KEY UPDATE
            test_result = VALUES(test_result),
            unit = VALUES(unit),
            reference_range = VALUES(reference_range),
            abnormal_flag = VALUES(abnormal_flag)
    ");

    $insert->bind_param(
        "ississss",
        $parameter_id,
        $value,
        $testid,
        $visit_no,
        $unit,
        $reference,
        $flag
    );

    if (!$insert->execute()) {
        file_put_contents("api_error.log", "Insert failed: " . $insert->error . "\n", FILE_APPEND);
    }
}

// ===== UPDATE SAMPLE STATUS =====
$barcode = $visit_no . "/" . $testid;

$update = $con->prepare("
    UPDATE sample_status
    SET is_result_entered = 1,
        result_entered_by = 'Machine',
        result_entered_at = NOW()
    WHERE barcode_no = ?
");

$update->bind_param("s", $barcode);
$update->execute();

// ===== RESPONSE =====
echo json_encode([
    "status" => "success",
    "message" => "Results processed"
]);
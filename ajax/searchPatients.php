<?php 
require_once(__DIR__ . '/../model/session_config.php');
include('../model/dbconnect.php');

// Search for patients (Admitted in pre-op, post-op, or CathLab wards)
$search = isset($_POST['search']) ? trim($_POST['search']) : '';
$status = isset($_POST['status']) ? $_POST['status'] : 'admitted';

if(strlen($search) < 2) {
    echo json_encode([]);
    exit();
}

$search_term = '%' . mysqli_real_escape_string($db, $search) . '%';

$query = "SELECT DISTINCT 
            pa.id,
            pa.patient_id,
            p.name,
            p.mobile,
            pa.ip_no,
            pa.admission_date,
            wd.ward_name,
            p.age,
            p.gender
          FROM patient_admission pa
          JOIN patient p ON pa.patient_id = p.id
          LEFT JOIN wards wd ON pa.ward_id = wd.id
          WHERE pa.status = 'admitted'
          AND (p.name LIKE ? OR pa.ip_no LIKE ? OR p.mobile LIKE ?)
          AND (wd.ward_type IN ('pre-operative', 'post-operative', 'cathlab') OR wd.id IN (
              SELECT id FROM wards WHERE ward_name LIKE '%cathlab%' OR ward_name LIKE '%cath lab%'
          ))
          ORDER BY pa.admission_date DESC
          LIMIT 10";

$stmt = mysqli_prepare($db, $query);
mysqli_stmt_bind_param($stmt, "sss", $search_term, $search_term, $search_term);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$patients = [];
while($row = mysqli_fetch_assoc($result)) {
    $patients[] = [
        'id' => $row['id'],
        'patient_id' => $row['patient_id'],
        'ip_no' => $row['ip_no'],
        'name' => $row['name'],
        'age' => $row['age'] . '/' . $row['gender'],
        'admission_date' => date('d-M-Y', strtotime($row['admission_date'])),
        'ward' => $row['ward_name']
    ];
}

echo json_encode($patients);
?>

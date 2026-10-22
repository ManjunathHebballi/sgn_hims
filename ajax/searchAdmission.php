<?php 
require_once(__DIR__ . '/../model/session_config.php');
include('../model/dbconnect.php');
 $newlogin = new userlogin;
$db=$newlogin->dbconnect();
$search = isset($_POST['search']) ? trim($_POST['search']) : '';

if(strlen($search) < 2) {
    echo json_encode([]);
    exit();
}

$search_term = '%' . mysqli_real_escape_string($db, $search) . '%';

$query = "SELECT id, ipd_no, name, age, gender, ward, admission_date
          FROM central_ipd 
          WHERE discharge=0 and (name LIKE ? OR ipd_no LIKE ? OR mobile LIKE ?)
          ORDER BY admission_date DESC
          LIMIT 15";

$stmt = mysqli_prepare($db, $query);
mysqli_stmt_bind_param($stmt, "sss", $search_term, $search_term, $search_term);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$admissions = [];
while($row = mysqli_fetch_assoc($result)) {
    $admissions[] = [
        'id' => $row['id'],
        'ip_no' => $row['ipd_no'],
        'name' => $row['name'],
        'age' => $row['age'],
        'gender' => $row['gender'],
        'ward' => $row['ward'],
        'admission_date' => $row['admission_date']
    ];
}

echo json_encode($admissions);
?>

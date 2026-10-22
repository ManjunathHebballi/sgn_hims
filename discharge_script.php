<?php

require_once(__DIR__ . '/model/session_config.php');
date_default_timezone_set("Asia/Kolkata");
include('model/dbconnect.php');

$newlogin = new userlogin;
$con = $newlogin->dbconnect();

$sql2 = "SELECT ipd_no, admission_date, name, age, gender, address, dept_id, bed_id, diagnosis,dis_date
        FROM central_ipd  where 
          dis_date not like '00%' and discharge=0 and dis_date <= CURDATE()";
// echo $sql2;exit;
$res2 = mysqli_query($con, $sql2);

if ($res2) {
//     // Prepare insert statement for discharge
    $stmt_insert = $con->prepare("INSERT INTO discharge 
        (patient_id, patient_name, dept_id, age, sex, admission_date, discharge_date, diagnosis, created_by) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");

//     // Prepare update statements
    $stmt_bed = $con->prepare("UPDATE bed SET status = 'active' WHERE id = ?");
    $stmt_ipd = $con->prepare("UPDATE central_ipd SET discharge = 1 WHERE ipd_no = ?");

    while ($row2 = mysqli_fetch_assoc($res2)) {
        $admission_date = $row2['admission_date'];
        $discharge_date = $row2['dis_date'];

        // Bind and execute insert
        $stmt_insert->bind_param(
            "ssissssss", 
            $row2['ipd_no'], 
            $row2['name'], 
            $row2['dept_id'], 
            $row2['age'], 
            $row2['gender'], 
            $admission_date, 
            $discharge_date, 
            $row2['diagnosis'],
            $_SESSION['name']
        );
        $stmt_insert->execute();

//         // Update bed status
        $stmt_bed->bind_param("i", $row2['bed_id']);
        $stmt_bed->execute();

//         // Update IPD discharge flag
        $stmt_ipd->bind_param("s", $row2['ipd_no']);
        $stmt_ipd->execute();
    }

//     // Close statements
    $stmt_insert->close();
    $stmt_bed->close();
    $stmt_ipd->close();
} else {
    echo "Error fetching IPD data: " . mysqli_error($con);
}

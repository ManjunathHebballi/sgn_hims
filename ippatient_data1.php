<?php
require_once(__DIR__ . '/model/session_config.php');
date_default_timezone_set("Asia/Kolkata");
include('model/dbconnect.php');

$adm = array('83','100'); 
$disc = array(0);

exit;
$month = 8;
$year = 2025;

// Generate date list
$list = [];
for ($d = 30; $d <= 31; $d++) {
    $time = mktime(12, 0, 0, $month, $d, $year);
    if (date('m', $time) == $month)
        $list[] = date('Y-m-d', $time);
}



$newlogin = new userlogin;
$con = $newlogin->dbconnect();

// Department mapping: dept_id => [percentage, label]
$departments = [
    2  => 11,    // Pediatrics
    3  => 2,    // Psychiatry
    4  => 3,    // Skin
    7  => 17,   // Ortho
    11 => 5,    // Ophthalmology
    12 => 16,   // OBG
    6  => 20,   // General Surgery
    18 => 4,    // ENT
    1  => 22,   // General Medicine
    17 => 0    // Emergency
];
// Sum: 9+2+3+15+5+14+16+4+18+14 = 100

function getNextIpdNo($con, $date)
{
    $query1 = "SELECT * FROM data_table WHERE id=2";
    $central_ipd = mysqli_fetch_assoc(mysqli_query($con, $query1));
    $ipd_no = $central_ipd['table_id'] + 1;
    $y = date("Ymd", strtotime($date));
    if ($y != substr($ipd_no, 0, 8)) {
        $data = "SELECT SUBSTR(MAX(ipd_no),3,11) as ipd FROM central_ipd where ipd_no like 'IP$y%'";
        $ip_no = mysqli_fetch_assoc(mysqli_query($con, $data));
        if (isset($ip_no['ipd'])) {
            $ipd_no = $ip_no['ipd'] + 1;
        } else {
            $ipd_no = $y . '001';
        }
    }
    return $ipd_no;
}

function admitPatient($con, $row, $date, $ipd_no)
{
    $query = "INSERT INTO central_ipd(opd_no,ipd_no,admission_date,name,age,gender,address,dept_id,bed_id,ward,diagnosis,doctor,is_generated,`created_by`,`dis_date`) 
        VALUES (
            '{$row['opd_no']}',
            'IP$ipd_no',
            '$date',
            '{$row['name']}',
            '{$row['age']}',
            '{$row['gender']}',
            '{$row['address']}',
            '{$row['dept_id']}',
            '{$row['bed']}',
            '{$row['ward']}',
            '{$row['diagnosis']}',
            '{$row['doctor']}',
            '1',
            '{$_SESSION['name']}',
            '{$row['dis_date']}'
        )";
    mysqli_query($con, $query);
    mysqli_query($con, "UPDATE bed1 SET status='occupied' WHERE id='{$row['bed']}'");
    mysqli_query($con, "UPDATE data_table SET table_id='$ipd_no' WHERE id=2");
    mysqli_query($con, "UPDATE central_opd SET is_ipd=1 WHERE id='{$row['id']}'");
}

foreach ($list as $count => $date) {
    $array = [];
    $discharge = $disc[$count] ?? 0;
    $ipdpatient = $adm[$count] ?? 0;
    $discdate = date("Y-m-d", strtotime($date));

   // Discharge patients: random + force discharge if admitted > 8 days
    // 1. Select patients admitted more than 8 days ago and not yet discharged
//     if($discharge>0) {
//      $sql2 = "SELECT ipd_no,admission_date,name,age,gender,address,dept_id,bed_id,diagnosis 
//              FROM central_ipd 
//              WHERE discharge=0 
//               AND DATE(admission_date) < '$discdate' 
//               AND (DATEDIFF('$discdate', admission_date) > 8)
//              ORDER BY
//               RAND()";  
//      $res2 = mysqli_query($con, $sql2);
//     $forcedDischarges = [];
//   $sn=0;
//     while ($row2 = mysqli_fetch_assoc($res2)) {
//         $forcedDischarges[] = $row2;
//          mysqli_query($con, "UPDATE bed1 SET status='active' WHERE id='{$row2['bed_id']}'");
//         mysqli_query($con, "UPDATE central_ipd SET discharge=1 WHERE ipd_no='{$row2['ipd_no']}'");
//         $sn++;
//     }
    
//     $discharge=$discharge-$sn;
//     if($discharge>0) {
//         $sql2 = "SELECT ipd_no,admission_date,name,age,gender,address,dept_id,bed_id,diagnosis 
//                  FROM central_ipd 
//                  WHERE discharge=0 
//                   AND DATE(admission_date) < '$discdate' 
//                  ORDER BY
//                   RAND() 
//                  LIMIT $discharge";
    
//         $res2 = mysqli_query($con, $sql2);
         
//         $forcedDischarges = [];
//         while ($row2 = mysqli_fetch_assoc($res2)) {
//             $forcedDischarges[] = $row2;
//             mysqli_query($con, "UPDATE bed1 SET status='active' WHERE id='{$row2['bed_id']}'");
//         mysqli_query($con, "UPDATE central_ipd SET discharge=1 WHERE ipd_no='{$row2['ipd_no']}'");
//         }
        
//     }
// }

$sql2 = "SELECT ipd_no, admission_date, name, age, gender, address, dept_id, bed_id, diagnosis,dis_date
        FROM central_ipd 
        WHERE discharge = 0 
        and dis_date<='$discdate' and admission_date<='$discdate'
        
        ORDER BY RAND()";
// echo $sql2;exit;
$res2 = mysqli_query($con, $sql2);

if ($res2) {
//     // Prepare insert statement for discharge
    $stmt_insert = $con->prepare("INSERT INTO discharge 
        (patient_id, patient_name, dept_id, age, sex, admission_date, discharge_date, diagnosis) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

//     // Prepare update statements
    $stmt_bed = $con->prepare("UPDATE bed1 SET status = 'active' WHERE id = ?");
    $stmt_ipd = $con->prepare("UPDATE central_ipd SET discharge = 1 WHERE ipd_no = ?");

    while ($row2 = mysqli_fetch_assoc($res2)) {
        $admission_date = $row2['admission_date'];
        $discharge_date = $row2['dis_date'];

        // Bind and execute insert
        $stmt_insert->bind_param(
            "ssisssss", 
            $row2['ipd_no'], 
            $row2['name'], 
            $row2['dept_id'], 
            $row2['age'], 
            $row2['gender'], 
            $admission_date, 
            $discharge_date, 
            $row2['diagnosis']
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


    // Calculate department-wise admissions
    $dept_counts = [];
    foreach ($departments as $dept_id => $percent) {
        $dept_counts[$dept_id] = round(($ipdpatient * $percent) / 100);
    }
    // print_r($dept_counts);exit;
    $dept_pool = [];
    foreach ($departments as $dept_id => $percent) {
        for ($i = 0; $i < $percent; $i++) {
            $dept_pool[] = $dept_id;
        }
    }

    // Admit patients department-wise
    for ($i = 0; $i < $ipdpatient; $i++) {
        // Only assign if department has count > 0
        // Shuffle department pool for random assignment
        shuffle($dept_pool);

        foreach ($dept_pool as $dept_id) {
            if (!isset($dept_counts[$dept_id]) || $dept_counts[$dept_id] <= 0) {
                continue;
            }
            // Build query for each department
            $sql = "SELECT c.id,c.opd_no,c.name,c.age,c.gender,c.mobile,c.category,c.address,c.dept_id,c.religion,c.caste,c.parent_name,c.occupation,c.annual_income,IFNULL(t.name,c.diagnosis) as diagnosis,concat(d.name,' ',d.last_name) as doctor,b.id as bed,b.ward,DATE_ADD(date, INTERVAL IFNULL(t.days,3) DAY) as dis_date 
            FROM central_opd c
            JOIN doctor d ON d.id = c.doctor
            LEFT JOIN temp_diagnosis t ON t.dept=c.dept_id and c.age between t.min_age and t.max_age and (c.gender=t.sex or t.sex='both') 
            JOIN bed1 b ON c.dept_id = b.department AND (case when b.department!=2 then b.ward LIKE CONCAT(c.gender,'%') else 1=1 end) OR b.ward=t.icu AND b.status='active'
            WHERE date = '$date' AND is_ipd=0 AND c.is_generated=1 AND c.dept_id=$dept_id
            ORDER BY RAND() LIMIT 1";
            // if($dept_counts[4])
            // echo $sql;exit;
            $res = mysqli_query($con, $sql);
            if ($row = mysqli_fetch_assoc($res)) {
                if (empty($row['bed'])) {  
                    continue;
                }
                $ipd_no = getNextIpdNo($con, $date);
                admitPatient($con, $row, $date, $ipd_no);
                $array[] = $row['diagnosis'];
                $dept_counts[$dept_id]--; // Decrement count after successful insertion
                break; // Move to next patient after successful admission
            }
        }
    }
}
?>
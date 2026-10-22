<?php
require_once(__DIR__ . '/model/session_config.php');
date_default_timezone_set("Asia/Kolkata");
include('model/dbconnect.php');

$newlogin = new userlogin;
$con = $newlogin->dbconnect();

$sql = "select * from delivery_cases where id>156";
$res = mysqli_query($con, $sql);
while($row = mysqli_fetch_assoc($res))
{
    $sql1="SELECT * FROM `central_ipd` where opd_no in (select opd_no from central_opd where date='$row[admission_date]' and old_patient=0 and dept_id=12 and diagnosis not like 'O80%' and date(admission_date)=date) order by rand() limit 1;";
    $row1 = mysqli_fetch_assoc(mysqli_query($con, $sql1));
    
    $sql2="update central_opd set name='$row[name]',age='',gender='Female',address='$row[address]',doctor='$row[doctor_id]',diagnosis='$row[diagnosis]' where opd_no='$row1[opd_no]';";
    // echo $sql2."<br>";
    mysqli_query($con, $sql2);
    
    
    $sql3="update central_ipd set name='$row[name]',age='',gender='Female',address='$row[address]',doctor='$row[doctor]',diagnosis='$row[diagnosis]',ipcard_no='$row[ip_card]' where opd_no='$row1[opd_no]';";
    // echo $sql3."<br>";
    mysqli_query($con, $sql3);
    
    
    $sql4="update discharge set patient_name='$row[name]',age='',sex='Female',discharge_date='$row[discharge_date]',diagnosis='$row[diagnosis]' where patient_id='$row1[ipd_no]';";
    // echo $sql4."<br>";exit;
    mysqli_query($con, $sql4);
    
    
    
  echo "OPD No. ".$row1['opd_no']." --- IPD No. ".$row1['ipd_no']."<br>";
    
}
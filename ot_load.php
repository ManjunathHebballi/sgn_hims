<?php 
require_once(__DIR__ . '/model/session_config.php');
date_default_timezone_set("Asia/Kolkata");  
include('model/dbconnect.php');
 $newlogin = new userlogin;                  
  $con=$newlogin->dbconnect();
$ot=array('6');
$month = 4;
$year = 2024;

for($d=8; $d<=8; $d++)
{
    $time=mktime(12, 0, 0, $month, $d, $year);          
    if (date('m', $time)==$month)       
        $list[]=date('Y-m-d', $time);
}


for($count=0;$count<count($list);$count++)
{
     $date=$list[$count];
     $data=$ot[$count];
    $sql="INSERT INTO `operation_theater` (`patient_id`,`date`,`doctor`,`ot_type`,`ot_procedure`) SELECT i.`ipd_no`,DATE_ADD(i.`admission_date`, INTERVAL FLOOR(RAND() * DATEDIFF(DATE_SUB(d.discharge_date, INTERVAL 1 DAY),i.`admission_date`)) DAY) as date,i.`doctor`,'2','2' from central_ipd i,discharge d where i.ipd_no=d.patient_id and i.dept_id in (6,1) having date='$date' order by rand() limit $data";
        echo $sql."<br>";
        $res=mysqli_query($con,$sql);
}
?>
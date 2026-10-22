<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('memory_limit', '512M');
include('model/dbconnect.php');
$newlogin = new userlogin;                  
$con=$newlogin->dbconnect();
$pre='';
$sql="SELECT * from central_opd where date like '2024-05-%' order by date";
// echo $sql;exit;
$res=mysqli_query($con,$sql);
while($row=mysqli_fetch_assoc($res))
{
    $y=date("Ymd",strtotime($row['date']));
    if($y!=$pre)
    {
        $opd=$y.'0001';
    }
    else{
        $opd++;
    }
    $sql1="UPDATE central_opd set uhid='UHID$opd' where id='$row[id]'";
    mysqli_query($con,$sql1);
    $pre=$y;
}
?>
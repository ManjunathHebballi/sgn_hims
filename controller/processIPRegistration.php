<?php
require_once(__DIR__ . '/../model/session_config.php');
include('../model/ipClass.php');
// print_r($_REQUEST['diagnosis']);exit;
$newIP = new addIP;
$newIP->setadmdate($_REQUEST['admdate']);
$newIP->setopno($_REQUEST['opno']);
$newIP->setfname($_REQUEST['name']);
$newIP->setage($_REQUEST['age']);
$newIP->setsex($_REQUEST['sex']);
$newIP->setaddress($_REQUEST['address']);
$newIP->setmobile($_REQUEST['mobile']);
$newIP->setreligion($_REQUEST['religion']);
$newIP->setcategory($_REQUEST['category']);
$newIP->setannual($_REQUEST['annual_inc']);
$newIP->setcaste($_REQUEST['caste']);
$newIP->setpname($_REQUEST['fname']);
$newIP->setoccupation($_REQUEST['occupation']);
$newIP->setdepartment($_REQUEST['dept']);
$newIP->setdiagnosis($_REQUEST['diagnosis']);
$newIP->setipcardno($_REQUEST['ipcardno']);
$newIP->setdrname($_REQUEST['drname']);
$newIP->setmlc($_REQUEST['mlc']);
$newIP->setward($_REQUEST['ward']);
$newIP->setbed($_REQUEST['bed']);
$newIP->setcmo($_REQUEST['cmo']);
$newIP->setptype($_REQUEST['ptype']);
$newIP->setpolicy($_REQUEST['policy'] ?? '');
$newIP->setcoverage_type($_REQUEST['coverage_type'] ?? '');
$newIP->setinsurance_name($_REQUEST['insurance_name'] ?? '');
$newIP->setreferby($_REQUEST['referby']);
$newIP->setremark($_REQUEST['remark']);
$newIP->setis_generated($_REQUEST['is_generated']);
// exit;
if(isset($_REQUEST['editid']))
{
    // print_r($_REQUEST['diagnosis']);exit;
    $newIP->updateIP($_REQUEST['editid']);
    echo "<script>window.location='../ippatient.php?op_from=$_REQUEST[from]&op_to=$_REQUEST[to]&search=$_REQUEST[search]'</script>";
}
else{
$newIP->insertIP();
}
?>
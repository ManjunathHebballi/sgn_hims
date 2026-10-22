<?php
require_once(__DIR__ . '/../../model/session_config.php');
include('../model/billingClass.php');

$newOP = new addBilling;
if($_REQUEST['submit']=='opreg')
{
$newOP->setadmdate($_REQUEST['admdate']);

$newOP->setfname($_REQUEST['fname']);
$newOP->setage($_REQUEST['age']);
$newOP->setsex($_REQUEST['sex']);
$newOP->setaddress($_REQUEST['address']);
$newOP->setmobile($_REQUEST['mobile']);
$newOP->setreligion($_REQUEST['religion']);
$newOP->setpan($_REQUEST['pan']);
$newOP->setaadhar($_REQUEST['aadhar']);
$newOP->setpolicy($_REQUEST['policy']);
$newOP->setpname($_REQUEST['pname']);
// print_r($_REQUEST);

$newOP->setmarital($_REQUEST['marital']);
$newOP->setdepartment($_REQUEST['department']);
$newOP->setdiagnosis($_REQUEST['diagnosis']);
$newOP->setconsultancy($_REQUEST['consultancy']);
$newOP->setdoctor($_REQUEST['doctor']);
$newOP->setptype($_REQUEST['ptype']);
$newOP->setmlc($_REQUEST['mlc']);
// print_r($_REQUEST);exit;
$newOP->setcity($_REQUEST['city']);
$newOP->insertOP($_REQUEST['patient_type']);
// header("Location:../opregistration.php");
}


?>
<?php
require_once(__DIR__ . '/../model/session_config.php');
include('../model/userClass.php');

$newOP = new addClass;
if($_REQUEST['submit']=='submit')
{
// print_r($_SESSION);exit;
$newOP->setdob($_REQUEST['dob']);
$newOP->setfname($_REQUEST['fname']);
$newOP->setlname($_REQUEST['lname']);
$newOP->setdesignation($_REQUEST['designation']);
$newOP->setcontact($_REQUEST['contact']);

$newOP->setsex($_REQUEST['sex']);
$newOP->setqualification($_REQUEST['qualification']);
$newOP->setdept($_REQUEST['dept']);
$newOP->setrole($_REQUEST['role']);
$newOP->setemail($_REQUEST['email']);
$newOP->setpass($_REQUEST['pass']);

$newOP->setlab_department(isset($_REQUEST['lab_department']) ? $_REQUEST['lab_department'] : NULL);
$newOP->setsub_store(isset($_REQUEST['store']) ? $_REQUEST['store'] : NULL);
// echo 'here111111';exit;
$newOP->insertUser();
// exit;
}
if($_REQUEST['submit']=='Edit')
{

$newOP->setdob($_REQUEST['dob']);
$newOP->setfname($_REQUEST['fname']);
$newOP->setlname($_REQUEST['lname']);
$newOP->setdesignation($_REQUEST['designation']);
$newOP->setcontact($_REQUEST['contact']);
$newOP->setsex($_REQUEST['sex']);
$newOP->setqualification($_REQUEST['qualification']);
$newOP->setdept($_REQUEST['dept']);
$newOP->setrole($_REQUEST['role']);
$newOP->setemail($_REQUEST['email']);
$newOP->setpass($_REQUEST['pass']);
$newOP->setlab_department(isset($_REQUEST['lab_department']) ? $_REQUEST['lab_department'] : '');
$newOP->setsub_store(isset($_REQUEST['store']) ? $_REQUEST['store'] : '');

$newOP->editUser($_REQUEST['id']);
echo "<script>window.location='../edit_users.php?success=1&id=$_REQUEST[id]'</script>";
}

?>
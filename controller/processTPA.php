<?php
require_once(__DIR__ . '/../model/session_config.php');
include('../model/tpaClass.php');
$newTPA = new addTPA;
// print_r($_REQUEST);exit;

if($_REQUEST['submit']=='tpa')
{
    $newTPA->setname($_REQUEST['name']);
    $newTPA->setcontact_person($_REQUEST['contact_person']);
    $newTPA->setmobile($_REQUEST['mobile']);
    $newTPA->setaddress($_REQUEST['address']);

       $newTPA->insertTPA();
       
       echo "<script>window.location='../add_tpa.php'</script>";
   
}
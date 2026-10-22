<?php
require_once(__DIR__ . '/../model/session_config.php');
include('../model/ipClass.php');
// print_r($_REQUEST['diagnosis']);exit;
$newIP = new addIP;



if($_REQUEST['submit']=='transfer_bed')
{
    $newIP->setpatinetid($_REQUEST['patinetid']);
    $newIP->setpre_ward($_REQUEST['pre_ward']);
    $newIP->setpres_bed($_REQUEST['pres_bed']);
    $newIP->setward($_REQUEST['ward']); 
    $newIP->setbed($_REQUEST['bed']); 
    $newIP->insertTransferBed();
     echo "<script>window.location='../transfer_bed.php'</script>";
}
?>
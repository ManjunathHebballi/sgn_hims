<?php
require_once(__DIR__ . '/../model/session_config.php');
include('../model/manufactureClass.php');
$newManufacture = new manufacture;
// print_r($_REQUEST);
$newManufacture->setname($_REQUEST['name']);
$newManufacture->setmobile($_REQUEST['mobile']);
$newManufacture->setemail($_REQUEST['email']);
$newManufacture->setaddress($_REQUEST['address']);
$newManufacture->setcity($_REQUEST['city']);
$newManufacture->setbalance($_REQUEST['balance']);


if($_REQUEST['submit']=='new')
{
   $newManufacture->insertManufacture(); 
}
else{
    $newManufacture->updateManufacture($_REQUEST['editid'],$_REQUEST['status']); 
}

echo "<script>window.location='../add_manufacturer.php'</script>";
?>
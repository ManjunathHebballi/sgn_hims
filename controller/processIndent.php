<?php
require_once(__DIR__ . '/../model/session_config.php');
include("../model/medicineClass.php");
$newMedicine= new medicine();
echo "<pre>";
// print_r($_REQUEST);exit;
if($_REQUEST['submit']=='add_indent')
{
    // echo 'here';exit;
    $newMedicine->setsub_store($_REQUEST['sub_store']);
    $newMedicine->setdate($_REQUEST['date']);
    $newMedicine->setmedicine($_REQUEST['madicine']);
    $newMedicine->setQty($_REQUEST['req_qty']);
    $newMedicine->setpriority($_REQUEST['priority']);
    $newMedicine->addIndent();   
    echo "<script>window.location='../view_indent.php'</script>";
}
if($_REQUEST['submit']=='issue_indent')
{
    // echo 'here';exit;
    $newMedicine->setindent_id($_REQUEST['indent_id']);
    $newMedicine->settax($_REQUEST['tax']);
    $newMedicine->setprice($_REQUEST['price']);
    $newMedicine->setQty($_REQUEST['quantity']);
    $newMedicine->setexdate($_REQUEST['expdate']);
    $newMedicine->setbatch($_REQUEST['batch']);
    $newMedicine->issueIndent();   
    echo "<script>window.location='../indent_list.php'</script>";
}
?>
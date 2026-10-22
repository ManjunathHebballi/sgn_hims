<?php
require_once(__DIR__ . '/../model/session_config.php');
include('../model/medicineClass.php');
$newMedicine = new medicine;
// print_r($_REQUEST);

if($_REQUEST['submit']=='category')
{
    // print_r($_REQUEST);
    $newMedicine->setName($_REQUEST['name']);
    $newMedicine->setStatus($_REQUEST['status']);
    if($_REQUEST['condition']=='new')
    {
        $newMedicine->insertCategory();
    }
    else{
        $newMedicine->updateCategory($_REQUEST['editid']);
    }
   echo "<script>window.location='../add_category.php'</script>";
}

if($_REQUEST['submit']=='units')
{
    // print_r($_REQUEST);
    $newMedicine->setName($_REQUEST['name']);
    $newMedicine->setStatus($_REQUEST['status']);
    if($_REQUEST['condition']=='new')
    {
        $newMedicine->insertUnit();
    }
    else{
        $newMedicine->updateUnit($_REQUEST['editid']);
    }
    echo "<script>window.location='../add_units.php'</script>";
}
if($_REQUEST['submit']=='leaf')
{
    // print_r($_REQUEST);
    $newMedicine->setName($_REQUEST['name']);
    $newMedicine->setQty($_REQUEST['qty']);
    $newMedicine->setStatus($_REQUEST['status']);
    if($_REQUEST['condition']=='new')
    {
        $newMedicine->insertLeaf();
    }
    else{
        $newMedicine->updateLeaf($_REQUEST['editid']);
    }
    echo "<script>window.location='../add_leaf.php'</script>";
}

if($_REQUEST['submit']=='types')
{
    // print_r($_REQUEST);
    $newMedicine->setName($_REQUEST['name']);
    $newMedicine->setStatus($_REQUEST['status']);
    if($_REQUEST['condition']=='new')
    {
        $newMedicine->insertType();
    }
    else{
        $newMedicine->updateType($_REQUEST['editid']);
    }
    echo "<script>window.location='../add_types.php'</script>";
}

if($_REQUEST['submit']=='medicine')
{
    // print_r($_REQUEST);exit;
    $newMedicine->setName($_REQUEST['name']);
    $newMedicine->setstrength($_REQUEST['strength']);
    $newMedicine->setgeniric($_REQUEST['geniric']);
    $newMedicine->setunit($_REQUEST['unit']);
    $newMedicine->setshelf($_REQUEST['shelf']);
    $newMedicine->setdetails($_REQUEST['details']);
    $newMedicine->setcategory($_REQUEST['category']);
    $newMedicine->settype($_REQUEST['type']);
    $newMedicine->setmanufacture($_REQUEST['manufacture']);
    $newMedicine->setprice($_REQUEST['price']);
    $newMedicine->sethsn($_REQUEST['hsn']);
    $newMedicine->setgst($_REQUEST['gst']);
    $newMedicine->setmprice($_REQUEST['mprice']);
    if($_REQUEST['condition']=='new')
    {
        $newMedicine->insertMedicine();
    }
    else{
    $newMedicine->setStatus($_REQUEST['status']);
        $newMedicine->updateMedicine($_REQUEST['editid']);
    }
    echo "<script>window.location='../view_medicines.php'</script>";
}

if($_REQUEST['submit']=='cust_return')
{
    
    // print_r($_REQUEST);
    $patient_id=$_REQUEST['patient_id'];
    $pur_id=$_REQUEST['pur_id'];
    $ret=$_REQUEST['ret'];
    $deduction=$_REQUEST['deduction'];
    $ded_amt=$_REQUEST['ded_amount'];
    $ret_amt=$_REQUEST['total'];
    $n=$_REQUEST['sn_val']-1;
    
    for($i=0;$i<$n;$i++)
    {
        $newMedicine->setpatient_id($patient_id[$i]);
        $newMedicine->setpur_id($pur_id[$i]);
        $newMedicine->setret($ret[$i]);
        $newMedicine->setdeduction($deduction[$i]);
        $newMedicine->settotal_deduction($ded_amt[$i]);
        $newMedicine->setnet_return($ret_amt[$i]);
        $newMedicine->setptype($_REQUEST['ptype']);
        $newMedicine->setinvoice_no($_REQUEST['cust_invoice']);
        $newMedicine->insertCustReturn();
       
    }
  
    echo "<script>window.location='../add_returns.php'</script>";
}
?>
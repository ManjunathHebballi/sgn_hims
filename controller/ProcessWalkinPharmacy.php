<?php 

require_once(__DIR__ . '/../model/session_config.php');
include("../model/medicineClass.php");

  if($_REQUEST['id']==1)
    {
        $id=1;

    }
    else{
        $id=$_REQUEST['id']-1;
    }
   
$newMedicine= new medicine();
//  print_r($_REQUEST);exit;
$date=$_REQUEST['date'];
    $medicine=$_REQUEST['madicine'];
  
    $batch=$_REQUEST['batch'];
    $quantity=$_REQUEST['quantity'];
    $price=$_REQUEST['price'];
    $total=$_REQUEST['total'];
    $tax=$_REQUEST['tax'];
  $newMedicine->setpname($_REQUEST['pname']);

$newMedicine->setaddress($_REQUEST['address']);
  $newMedicine->setpnum($_REQUEST['pnum']);
  $newMedicine->setdate($_REQUEST['date']);
  $newMedicine->setdoctor($_REQUEST['doctor']);
  $newMedicine->setpayment_mode($_REQUEST['payment_mode']);
    $expdate=$_REQUEST['expdate'];
    
    $bill=$newMedicine->getInvoiceNo();
//  echo $bill;exit;
for ($i=0; $i < $id; $i++) {
// echo $medicine[$i];exit;
  $newMedicine->setmedicine($medicine[$i]);
  $newMedicine->setbatch($batch[$i]);

  $newMedicine->setquantity($quantity[$i]);
  $newMedicine->setprice($price[$i]);
  $newMedicine->settotal($total[$i]);
  $newMedicine->setindiscount($_REQUEST['indiscount']);
  $newMedicine->setgrand($_REQUEST['grand']);
  // $newMedicine->setdoct($_REQUEST['doct']);
  $newMedicine->insertWalkinBill($bill);
  $_REQUEST['indiscount']=$_REQUEST['grand']=0;
}
 $inv=$newMedicine->updateInvoiceNo($bill);
  echo "<script>window.open('../print_pharma_bill3.php?id=$_REQUEST[pnum]&inv=$bill','_blank')</script>";
  echo "<script>window.location='../walkin_pharma_billing.php'</script>";



 ?>


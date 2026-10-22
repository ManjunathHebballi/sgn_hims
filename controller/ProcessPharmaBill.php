<?php
require_once(__DIR__ . '/../model/session_config.php');
include('../model/medicineClass.php');
$newOT = new medicine;

 if($_REQUEST['id']==1)
    {
        $id=1;

    }
    else{
        $id=$_REQUEST['id']-1;
    }
    
    // echo "<pre>";
    // print_r($_REQUEST);
    // if(isset($_REQUEST['price_pre']))
    // {
    //     $qty=$_REQUEST['qty'];
    //     $medicine_id=$_REQUEST['medicine_id'];
    //     $price_pre=$_REQUEST['price_pre'];
    //     $totl_pre=$_REQUEST['totl_pre'];
    //     for($j=0;$j<count($qty);$j++)
    //     {
    //         $newOT->updateTablet($qty[$j],$medicine_id[$j],$price_pre[$j],$totl_pre[$j],$_REQUEST['pid']);
    //     }
    // }
    $date=$_REQUEST['date'];
    $medicine=$_REQUEST['madicine'];
  
    $batch=$_REQUEST['batch'];
    $quantity=$_REQUEST['quantity'];
    $price=$_REQUEST['price'];
    $total=$_REQUEST['total'];
    $tax=$_REQUEST['tax'];
    
    $expdate=$_REQUEST['expdate'];
    echo 'here';
    $inv=$newOT->getInvoiceNo();
    $newOT->setinvoice($inv);
    for ($i=0; $i < $id; $i++) { 
       
    // echo $medicine[$i];
  $newOT->setpname($_REQUEST['pname1']);
  $newOT->setdate($date[$i]);
  
  $newOT->setmedicine($medicine[$i]);
  $newOT->setbatch($batch[$i]);
//   
  $newOT->settax($tax[$i]);
    $newOT->setexdate(date("Y-m-t",strtotime($expdate[$i])));
      
//   $newOT->setuunit($_REQUEST['uunit'.$i]);
  $newOT->setquantity($quantity[$i]);
  $newOT->setprice($price[$i]);

  $newOT->settotal($total[$i]);
  $newOT->setindiscount($_REQUEST['indiscount']);
  $newOT->setgrand($_REQUEST['grand']);
   $newOT->setpayment($_REQUEST['pay_mode']);
  if($medicine[$i] > 0)
  {
   
  $newOT->insertOPMedic($_REQUEST['pid']);
  $_REQUEST['indiscount']=$_REQUEST['grand']=0;
  }
  elseif(strlen($_REQUEST['indiscount']) > 0)
  {
    //   print_r($_REQUEST);
    //   exit;
    $newOT->updateDiscOP($_REQUEST['pid']);
  }
}
 $inv=$newOT->updateInvoiceNo($inv);
 echo "<script>window.open('../print_pharma_bill.php?id=$_REQUEST[pid]&inv=$inv','_blank')</script>";
  echo "<script>window.location='../pharma_billing.php?id=$_REQUEST[pid]&save=1'</script>";
//   header("Location: ../pharma_billing.php?id=$_REQUEST[pid]&save=1"); 
 ?>
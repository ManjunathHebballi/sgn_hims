<?php
require_once(__DIR__ . '/../model/session_config.php');
include('../model/ipClass.php');
$newOP = new addIP;

 if($_REQUEST['id']==1)
    {
        $id=1;

    }
    else{
        $id=$_REQUEST['id']-1;
    }
    // echo "<pre>";
    // print_r($_REQUEST);exit;
    // if(isset($_REQUEST['price_pre']))
    // {
    //     $qty=$_REQUEST['qty'];
    //     $medicine_id=$_REQUEST['medicine_id'];
    //     $price_pre=$_REQUEST['price_pre'];
    //     $totl_pre=$_REQUEST['totl_pre'];
    //     for($j=0;$j<count($qty);$j++)
    //     {
    //         $newOP->updateTabletIp($qty[$j],$medicine_id[$j],$price_pre[$j],$totl_pre[$j],$_REQUEST['pid']);
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
    
    $inv=$newOP->getInvoiceNo();
    $newOP->setinvoice($inv);
    for ($i=0; $i < $id; $i++) { 
       
       


  $newOP->setmedicine1($medicine[$i]);
  $newOP->setbatch1($batch[$i]);
  // $newOP->setuunit($_REQUEST['uunit'.$i]);
  $newOP->setquantity1($quantity[$i]);
  $newOP->setprice1($price[$i]);
  $newOP->settotal1($total[$i]);
  $newOP->setindiscount1($_REQUEST['indiscount']);
  $newOP->setgrand1($_REQUEST['grand']);
  if($medicine[$i] > 0)
  {
     
  $newOP->insertIPMedic1($_REQUEST['pid']);
  $_REQUEST['indiscount']=$_REQUEST['grand']=0;
  }
  elseif(strlen($_REQUEST['indiscount']) > 0)
  {
    $newOP->updateDiscoIP($_REQUEST['pid']);
  }
}
$newOP->updateInvoiceNo($inv);
  echo "<script>window.open('../print_pharma_bill2.php?id=$_REQUEST[pid]&inv=$inv','_blank')</script>";
  echo "<script>window.location='../collect_amount.php?id=$_REQUEST[pid]&save=1'</script>";
//   header("Location: ../collect_amount.php?id=$_REQUEST[pid]");
 ?>
<?php
require_once(__DIR__ . '/../model/session_config.php');
include('../model/radiologyClass.php');
$newRadio = new addRadio;

if($_REQUEST['submit']=='add_test')
{  
   $newRadio->settest_name($_REQUEST['test_name']); 
   $newRadio->setshort_name($_REQUEST['short_name']);
   $newRadio->settest_type($_REQUEST['test_type']);
   $newRadio->setRadiology_category_id($_REQUEST['radiology_category_id']);
   $newRadio->setsub_category($_REQUEST['sub_category']);
   $newRadio->setmethod($_REQUEST['method']);
   $newRadio->setreport_days($_REQUEST['report_days']); 
   $newRadio->setcharge_category_id($_REQUEST['charge_category_id']);
   $newRadio->setcharge_name($_REQUEST['charge_name']);
   $newRadio->settax($_REQUEST['tax']);
   $newRadio->setstandard_charge($_REQUEST['standard_charge']);
   $newRadio->setamount($_REQUEST['amount']);
   
   if($_REQUEST['id']==0)
    {
        $id=1;

    }
    else{
        $id=$_REQUEST['id'];
    }
   for ($i=0; $i < $id; $i++) {
        $newRadio->setparameter($_REQUEST['parameter'.$i]);
        $newRadio->setrange($_REQUEST['range'.$i]);
        $newRadio->setunit($_REQUEST['unit'.$i]);
        $newRadio->insertRadioTest();
   }
   echo "<script>window.location='../radiology_test.php'</script>";
}


if($_REQUEST['submit']=='test_bill')
{ 
    // print_r($_REQUEST);
    // echo $_REQUEST['id'];
    // exit;
     $newRadio->setpayment_mode($_REQUEST['payment_mode']); 
     $newRadio->setnote($_REQUEST['note']); 
     
     
      $newRadio->setgrand_total($_REQUEST['grand_total']); 
     $newRadio->settotal_tax($_REQUEST['total_tax']);
     $newRadio->setdiscount($_REQUEST['discount']); 
     $newRadio->setnet_amount($_REQUEST['net_amount']); 
     $newRadio->setpaid_amount($_REQUEST['paid_amount']); 
     $newRadio->setdue_amount($_REQUEST['due_amount']); 
     
     
     if($_REQUEST['id']==0)
    {
        $id=1;

    }
    else{
        $id=$_REQUEST['id'];
    }
     
      for ($i=0; $i < $id; $i++) 
      {
     $newRadio->settest($_REQUEST['test'.$i]); 
     $newRadio->setdays($_REQUEST['days'.$i]); 
     $newRadio->setdate($_REQUEST['date'.$i]); 
     $newRadio->settax($_REQUEST['tax'.$i]); 
     $newRadio->setamount($_REQUEST['amount'.$i]); 
     $newRadio->insertRadioBill($_REQUEST['patient_id']);
      }
    // exit;
    echo "<script>window.location='../radiology_op_billing.php'</script>";
}
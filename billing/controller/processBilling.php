<?php
require_once(__DIR__ . '/../../model/session_config.php');
include('../model/billingClass.php');
$newBill = new addBilling;

function getRequestedServiceId($index)
{
    if (isset($_REQUEST['service_id'][$index]) && trim((string)$_REQUEST['service_id'][$index]) !== '') {
        return $_REQUEST['service_id'][$index];
    }

    if (isset($_REQUEST['service'][$index]) && trim((string)$_REQUEST['service'][$index]) !== '') {
        return $_REQUEST['service'][$index];
    }

    return '';
}

if($_REQUEST['submit']=='op_patho')
{
    
    $newBill->setpatient_id($_REQUEST['patient_id']);
    $newBill->setservice($_REQUEST['service']);
    
    // print_r($_REQUEST);exit;
    $newBill->setservice_type($_REQUEST['service_type']);
    $newBill->setpatient_type($_REQUEST['patient_type']);
    $newBill->setdoc($_REQUEST['doc']);
    $newBill->settotal($_REQUEST['total']);
    $newBill->setdiscount($_REQUEST['discount']);
    $newBill->setdiscount_reason($_REQUEST['discount_reason']);
     
    $newBill->setgrand($_REQUEST['grand']);
    $newBill->setgross($_REQUEST['gross']);
    $newBill->setsrv_disc($_REQUEST['srv_disc']);
    $newBill->settot_amt($_REQUEST['tot_amt']);
    $newBill->setoutstand($_REQUEST['outstand']);
    $newBill->setdepartment($_REQUEST['dept']);
isset($_REQUEST['ref_center']) ? $newBill->setref_center($_REQUEST['ref_center']) : $newBill->setref_center(0);

if(isset($_REQUEST['billid'])) 
{

    $newBill->setbillid($_REQUEST['billid']);
    $bill_no=$_REQUEST['billno'];
    $newBill->setbillno($bill_no);
//         // 
//          echo '<pre>';
// print_r($_REQUEST);
// exit;
        $newBill->updateBill();
        $existingCount = isset($_REQUEST['editid']) ? count($_REQUEST['editid']) : 0;
        for($i=0;$i<$existingCount;$i++)
        {
            $newBill->setquantity($_REQUEST['quantity'][$i]);
            $newBill->setservice_id(getRequestedServiceId($i));
            $newBill->setprice($_REQUEST['price'][$i]);
            $newBill->setdisc_per($_REQUEST['disc_per'][$i]);
            $newBill->setdisc_amt($_REQUEST['disc_amt'][$i]);
            $newBill->setdoctor($_REQUEST['doctor_id'][$i]);

            $newBill->updateBilldetails($_REQUEST['editid'][$i]);
        }
        $totalRows = isset($_REQUEST['service_id']) ? count($_REQUEST['service_id']) : 0;
        for($i=$existingCount;$i<$totalRows;$i++)
        {
            $serviceId = getRequestedServiceId($i);
            if(trim((string)$serviceId) === '') {
                continue;
            }
            $newBill->setquantity($_REQUEST['quantity'][$i]);
            $newBill->setservice_id($serviceId);
            $newBill->setprice($_REQUEST['price'][$i]);
            $newBill->setdisc_per($_REQUEST['disc_per'][$i]);
            $newBill->setdisc_amt($_REQUEST['disc_amt'][$i]);
            $newBill->setdoctor($_REQUEST['doctor_id'][$i]);
            if(isset($_REQUEST['desc'][$i])) {
                $newBill->setdesc($_REQUEST['desc'][$i]);
            }
            $newBill->insertBillingDetails($_REQUEST['billid'],$bill_no);
        }
       if($_REQUEST['service_type']=='IP')
    {
        echo "<script>window.location='../ipbill_list.php?type=$_REQUEST[service_type]'</script>";
    }
    else{
        echo "<script>window.location='../bill_list.php?type=$_REQUEST[service_type]'</script>";
    }
    }
    else{
        $bill_no1=$newBill->insertBill();
        $data=explode("|",$bill_no1);
        $id=$data[1];
        $bill_no=$data[0];
        if($id>0){
            for($i=0;$i<count($_REQUEST['service_id']);$i++)
            {
                $newBill->setquantity($_REQUEST['quantity'][$i]);
                $newBill->setservice_id(getRequestedServiceId($i));
                $newBill->setprice($_REQUEST['price'][$i]);
                $newBill->setdisc_per($_REQUEST['disc_per'][$i]);
                $newBill->setdisc_amt($_REQUEST['disc_amt'][$i]);
                $newBill->setdoctor($_REQUEST['doctor_id'][$i]);
                if(isset($_REQUEST['desc'][$i])) {
                $newBill->setdesc($_REQUEST['desc'][$i]);
                }
                $newBill->insertBillingDetails($id,$bill_no);
            }
            if(!empty($_REQUEST['round_assessment_ids'])) {

                $newBill->markRoundAssessmentsBilled($_REQUEST['round_assessment_ids'],$_REQUEST['desc']);
            }
        
        if($_REQUEST['service_type']=='IP')
        {
            if($_REQUEST['checkboxName']==1)
            {
            $newBill->setadvance($_REQUEST['advance']);
            $newBill->setadv_id($_REQUEST['adv_id']);
            $newBill->setadj($_REQUEST['adj']);
            $newBill->setnote($_REQUEST['note']);
            $newBill->insertAdjustAmount($_REQUEST['patient_id'],$bill_no);
            }
        echo "<script>window.location='../view_ipbilling.php?id=$_REQUEST[patient_id]&bill_no=$bill_no&bill_id=$id&type=$_REQUEST[service_type]';</script>";
        
        }
    else{
        //  echo $bill_no;exit;
        if(isset($_REQUEST['opd_id']))
        {
            echo "<script>window.open('../../optoken.php?id=$_REQUEST[opd_id]')</script>";
        }
        if($_REQUEST['patient_type']=='OP')
        {
     echo "<script>window.location='../view_billing.php?id=$_REQUEST[patient_id]&bill_no=$bill_no&bill_id=$id&type=$_REQUEST[service_type]';</script>";
        }
        else{
             echo "<script>window.location='../view_ipbilling.php?id=$_REQUEST[patient_id]&bill_no=$bill_no&bill_id=$id&type=$_REQUEST[service_type]';</script>";
        }
    }
        }
          else{
           echo "<script>alert('Bill not generated. please check and re-bill it')</script>";   
           if($_REQUEST['patient_type']=='OP')
        {
           echo "<script>window.location='../bill_list.php?type=$_REQUEST[service_type]';</script>";
        }else{
             echo "<script>window.location='../ipbill_list.php?type=$_REQUEST[service_type]';</script>";
        }
          }
    }
    
        
       
    
}

if($_REQUEST['submit']=='revenue')
{
    
     $newBill->setdoctor($_REQUEST['doctor']);
     $newBill->setservice($_REQUEST['service']);
     $newBill->setprimary_doc_amt($_REQUEST['primary_doc_amt']);
     $newBill->setprimary_doc_percent($_REQUEST['primary_doc_percent']);
     $newBill->setprimary_hospital_percent($_REQUEST['primary_hospital_percent']);
     $newBill->setsecondary_doc_amt($_REQUEST['secondary_doc_amt']);
     $newBill->setsecond_doc_percent($_REQUEST['second_doc_percent']);
     $newBill->setsecond_hospital_percent($_REQUEST['second_hospital_percent']);
     $newBill->setstatus($_REQUEST['status']);
    
     $newBill->insertDocRevenue();
    
     echo "<script>alert('Added successfully');window.location='../doctor_revenue_share.php'</script>";
}

if($_REQUEST['submit']=='return')
{

$newBill->returnAmount($_REQUEST['bill_no'],$_REQUEST['remain_amount'],$_REQUEST['type']);
    if($_REQUEST['type']=='IP')
    {
        echo "<script>window.location='../ipbill_list.php?type=$_REQUEST[type]&op_from=$_REQUEST[date_from]&op_to=$_REQUEST[date_to]'</script>";
    }
    else{
        echo "<script>window.location='../bill_list.php?type=$_REQUEST[type]&op_from=$_REQUEST[date_from]&op_to=$_REQUEST[date_to]'</script>";
    }
}

if($_REQUEST['submit']=='return_advance')
{

$newBill->returnAdvAmount($_REQUEST['bill_no'],$_REQUEST['remain_amount']);
    
        echo "<script>window.location='../advance_list.php?op_from=$_REQUEST[date_from]&op_to=$_REQUEST[date_to]'</script>";
    
    
}

if($_REQUEST['submit']=='advance')
{
    // print_r($_REQUEST);exit;
   
   $newBill->setipd($_REQUEST['ipd']);
   $newBill->setadvance_amount($_REQUEST['advance_amount']);
    $newBill->setpayment_mode($_REQUEST['payment_mode']);
    $newBill->setnote($_REQUEST['ref_note']);
    if(isset($_REQUEST['editid']))
    {
        $id=$_REQUEST['editid'];
        $newBill->updateAdvance($id);
         echo "<script>window.location='../advance_list.php'</script>";
    }
    else{
       $id=$newBill->insertAdvance();
       echo "<script>window.open('../print_advance_amount.php?id=$_REQUEST[ipd]&adv_id=$id')</script>";
  echo "<script>window.location='../../ippatient.php'</script>";
    }
  
}

if($_REQUEST['submit']=='final')
{
    
    $newBill->setpatient_id($_REQUEST['patient_id']);
    $newBill->setservice($_REQUEST['service']);
    $newBill->setservice_type($_REQUEST['service_type']);
    $newBill->setpatient_type($_REQUEST['patient_type']);
    $newBill->setdoc($_REQUEST['doc']);
    $newBill->settotal($_REQUEST['total']);
    $newBill->setdiscount($_REQUEST['discount']);
    $newBill->setgrand($_REQUEST['grand']);
    $newBill->setgross($_REQUEST['gross']);
    $newBill->setsrv_disc($_REQUEST['srv_disc']);
    $newBill->settot_amt($_REQUEST['tot_amt']);
    $newBill->setoutstand($_REQUEST['outstand']);
    $newBill->setfinal_bill($_REQUEST['final_bill']);
    $newBill->setFinalBill($_REQUEST['patient_id']);  
    $newBill->setdepartment($_REQUEST['dept']);
    if(isset($_REQUEST['billid'])) 
    {
        
        $newBill->setbillid($_REQUEST['billid']);
        $bill_no=$_REQUEST['billno'];
        $newBill->setbillno($bill_no);
        // 
        $newBill->updateBill();
         $existingCount = isset($_REQUEST['editid']) ? count($_REQUEST['editid']) : 0;
         for($i=0;$i<$existingCount;$i++)
        {
            $newBill->setquantity($_REQUEST['quantity'][$i]);
            $newBill->setservice_id(getRequestedServiceId($i));
            $newBill->setprice($_REQUEST['price'][$i]);
            $newBill->setdisc_per($_REQUEST['disc_per'][$i]);
            $newBill->setdisc_amt($_REQUEST['disc_amt'][$i]);
            $newBill->setdoctor($_REQUEST['doctor_id'][$i]);
            if(isset($_REQUEST['desc'][$i])) {
                $newBill->setdesc($_REQUEST['desc'][$i]);
                }
            $newBill->updateBilldetails($_REQUEST['editid'][$i]);
        }
        $totalRows = isset($_REQUEST['service_id']) ? count($_REQUEST['service_id']) : 0;
        for($i=$existingCount;$i<$totalRows;$i++)
        {
            $serviceId = getRequestedServiceId($i);
            if(trim((string)$serviceId) === '') {
                continue;
            }
            $newBill->setquantity($_REQUEST['quantity'][$i]);
            $newBill->setservice_id($serviceId);
            $newBill->setprice($_REQUEST['price'][$i]);
            $newBill->setdisc_per($_REQUEST['disc_per'][$i]);
            $newBill->setdisc_amt($_REQUEST['disc_amt'][$i]);
            $newBill->setdoctor($_REQUEST['doctor_id'][$i]);
            if(isset($_REQUEST['desc'][$i])) {
                $newBill->setdesc($_REQUEST['desc'][$i]);
            }
            $newBill->insertBillingDetails($_REQUEST['billid'],$bill_no);
        }
    }
    else{
        $bill_no1=$newBill->insertBill();
        $data=explode("|",$bill_no1);
        $id=$data[1];
        $bill_no=$data[0];
        if($id>0)
        {
        for($i=0;$i<count($_REQUEST['service_id']);$i++)
        {
            $newBill->setquantity($_REQUEST['quantity'][$i]);
            $newBill->setservice_id(getRequestedServiceId($i));
            $newBill->setprice($_REQUEST['price'][$i]);
            $newBill->setdisc_per($_REQUEST['disc_per'][$i]);
            $newBill->setdisc_amt($_REQUEST['disc_amt'][$i]);
            $newBill->setdoctor($_REQUEST['doctor_id'][$i]);
            if(isset($_REQUEST['desc'][$i])) {
                $newBill->setdesc($_REQUEST['desc'][$i]);
                }
            $newBill->insertBillingDetails($id,$bill_no);
        }
            if(!empty($_REQUEST['round_assessment_ids'])) {
                $newBill->markRoundAssessmentsBilled($_REQUEST['round_assessment_ids'],$_REQUEST['desc']);
            }
        }
        else{
            echo "<script>alert('Bill not generated. please check and re-bill it')</script>";
            
        }
    }
    
        
 
        if($_REQUEST['checkboxName']==1)
    {
    $newBill->setadvance($_REQUEST['advance']);
    $newBill->setadv_id($_REQUEST['adv_id']);
    $newBill->setadj($_REQUEST['adj']);
    $newBill->setnote($_REQUEST['note']);
    $newBill->insertAdjustAmount($_REQUEST['patient_id'],$bill_no);
    }
     echo "<script>window.location='../view_ipbilling.php?id=$_REQUEST[patient_id]&bill_no=$bill_no&bill_id=$id&type=IP'</script>";
     
   
  
}

if($_REQUEST['submit']=='payment')
{
    // print_r($_REQUEST);exit;
    $newBill->setpaid_amt($_REQUEST['payment_made']);
    $newBill->setpayment_mode($_REQUEST['payment_method']);
    $newBill->setpayment_date($_REQUEST['payment_date']);
    $newBill->setnote($_REQUEST['reference_note']);
    $newBill->addPayment($_REQUEST['bill_id'],$_REQUEST['bill_no']);
    if($_REQUEST['service_type']=='IP')
    {
        echo "<script>window.location='../view_ipbilling.php?id=$_REQUEST[id]&bill_no=$_REQUEST[bill_no]&bill_id=$_REQUEST[bill_id]&status=PAID'</script>";
    }
    else{
        echo "<script>window.location='../view_billing.php?id=$_REQUEST[id]&bill_no=$_REQUEST[bill_no]&bill_id=$_REQUEST[bill_id]&status=PAID'</script>";
    }
    
}


if($_REQUEST['submit']=='op_reg')
{
    
    $newBill->setpatient_id($_REQUEST['patient_id']);
    $newBill->setservice($_REQUEST['service']);
    
    // print_r($_REQUEST);exit;
    $newBill->setservice_type($_REQUEST['service_type']);
    $newBill->setpatient_type($_REQUEST['patient_type']);
    $newBill->setdoc($_REQUEST['doc']);
    $newBill->settotal($_REQUEST['total']);
    $newBill->setdiscount($_REQUEST['discount']);

    $newBill->setgrand($_REQUEST['grand']);
    $newBill->setgross($_REQUEST['gross']);
    $newBill->setsrv_disc($_REQUEST['srv_disc']);
    
    $newBill->settot_amt($_REQUEST['tot_amt']);
    $newBill->setoutstand($_REQUEST['outstand']);
    $newBill->setdepartment($_REQUEST['dept']);
   $newBill->setpayment_mode($_REQUEST['payment_mode']);
   $newBill->setpayment_date(date("Y-m-d H:i:s"));
    $newBill->setpaid_amt($_REQUEST['paid_amt']);
        $bill_no1=$newBill->insertBill();
        $data=explode("|",$bill_no1);
        $id=$data[1];
        $bill_no=$data[0];
        if($id>0)
        {
            for($i=0;$i<count($_REQUEST['service_id']);$i++)
            {
                $newBill->setquantity($_REQUEST['quantity'][$i]);
                $newBill->setservice_id($_REQUEST['service_id'][$i]);
                $newBill->setprice($_REQUEST['price'][$i]);
                $newBill->setdisc_per($_REQUEST['disc_per'][$i]);
                $newBill->setdisc_amt($_REQUEST['disc_amt'][$i]);
                $newBill->setdoctor($_REQUEST['doctor_id'][$i]);
    
                $newBill->insertBillingDetails($id,$bill_no);
            }
            if($_REQUEST['paid_amt']>0)
             {
            $newBill->addOPPayment($id,$bill_no);
             }
        }
          else{
           echo "<script>alert('Bill not generated. please check and re-bill it')</script>";   
          }
  
            echo "<script>window.open('../../opcasesheet.php?id=$_REQUEST[patient_id]')</script>";
        
     echo "<script>window.location='../view_billing.php?id=$_REQUEST[patient_id]&bill_no=$bill_no&bill_id=$id&type=$_REQUEST[service_type]&flag=1';</script>";
    
    
    
        
       
    
}

if($_REQUEST['submit']=='claim_bill')
{
    
    $newBill->setpatient_id($_REQUEST['patient_id']);
    $newBill->setservice($_REQUEST['service']);
    
    // print_r($_REQUEST);exit;
    $newBill->setservice_type($_REQUEST['service_type']);
    $newBill->setpatient_type($_REQUEST['patient_type']);
    $newBill->setdoc($_REQUEST['doc']);
    $newBill->settotal($_REQUEST['total']);
    $newBill->setdiscount($_REQUEST['discount']);
     
    $newBill->setgrand($_REQUEST['grand']);
    $newBill->setgross($_REQUEST['gross']);
    $newBill->setsrv_disc($_REQUEST['srv_disc']);
    $newBill->settot_amt($_REQUEST['tot_amt']);
    $newBill->setoutstand($_REQUEST['outstand']);
    $newBill->setdepartment($_REQUEST['dept']);
    $newBill->setward($_REQUEST['ward']);
   $newBill->setdate($_REQUEST['date']);
    if(isset($_REQUEST['billid'])) 
    {
        
        $newBill->setbillid($_REQUEST['billid']);
        $bill_no=$_REQUEST['billno'];
        $newBill->setbillno($bill_no);
//         // 
//          echo '<pre>';
// print_r($_REQUEST);
// exit;
        $newBill->updateClaimBill();
        for($i=0;$i<count($_REQUEST['editid']);$i++)
        {
            $newBill->setquantity($_REQUEST['quantity'][$i]);
            $newBill->setservice_id($_REQUEST['service_id'][$i]);
            $newBill->setprice($_REQUEST['price'][$i]);
            $newBill->setdisc_per($_REQUEST['disc_per'][$i]);
            $newBill->setdisc_amt($_REQUEST['disc_amt'][$i]);
            $newBill->setdoctor($_REQUEST['doctor_id'][$i]);

            $newBill->updateClaimBilldetails($_REQUEST['editid'][$i]);
        }
  
        echo "<script>window.location='../claimbill_list.php?type=$_REQUEST[service_type]'</script>";
    
    }
    else{
        $bill_no1=$newBill->insertClaimBill($_REQUEST['bill_no']);
        $data=explode("|",$bill_no1);
        $id=$data[1];
        $bill_no=$data[0];
        for($i=0;$i<count($_REQUEST['service_id']);$i++)
        {
            $newBill->setquantity($_REQUEST['quantity'][$i]);
            $newBill->setservice_id($_REQUEST['service_id'][$i]);
            $newBill->setprice($_REQUEST['price'][$i]);
            $newBill->setdisc_per($_REQUEST['disc_per'][$i]);
            $newBill->setdisc_amt($_REQUEST['disc_amt'][$i]);
            $newBill->setdoctor($_REQUEST['doctor_id'][$i]);

            $newBill->insertClaimBillingDetails($id,$bill_no);
        }
       
        echo "<script>window.location='../print_claimbill.php?id=$_REQUEST[patient_id]&bill_no=$bill_no';</script>";
        
        
    }
    
        
       
    
}
if($_REQUEST['submit']=='esi_bill')
{
    
    $newBill->setpatient_id($_REQUEST['patient_id']);
    $newBill->setservice($_REQUEST['service']);
    
    // print_r($_REQUEST);exit;
    $newBill->setservice_type($_REQUEST['service_type']);
    $newBill->setpatient_type($_REQUEST['patient_type']);
    $newBill->setdoc($_REQUEST['doc']);
    $newBill->settotal($_REQUEST['total']);
    $newBill->setdiscount($_REQUEST['discount']);
     
    $newBill->setgrand($_REQUEST['grand']);
    $newBill->setgross($_REQUEST['gross']);
    $newBill->setsrv_disc($_REQUEST['srv_disc']);
    $newBill->settot_amt($_REQUEST['tot_amt']);
    $newBill->setoutstand($_REQUEST['outstand']);
    $newBill->setdepartment($_REQUEST['dept']);
    $newBill->setward($_REQUEST['ward']);
    $newBill->setdate($_REQUEST['date']);
    if(isset($_REQUEST['billid'])) 
    {
        
        $newBill->setbillid($_REQUEST['billid']);
        $bill_no=$_REQUEST['billno'];
        $newBill->setbillno($bill_no);
//         // 
//          echo '<pre>';
// print_r($_REQUEST);
// exit;
        $newBill->updateESIBill();
        for($i=0;$i<count($_REQUEST['editid']);$i++)
        {
            $newBill->setquantity($_REQUEST['quantity'][$i]);
            $newBill->setservice_id($_REQUEST['service_id'][$i]);
            $newBill->setprice($_REQUEST['price'][$i]);
            $newBill->setdisc_per($_REQUEST['disc_per'][$i]);
            $newBill->setdisc_amt($_REQUEST['disc_amt'][$i]);
            $newBill->setdoctor($_REQUEST['doctor_id'][$i]);

            $newBill->updateESIBilldetails($_REQUEST['editid'][$i]);
        }
  
        echo "<script>window.location='../esibill_list.php?type=$_REQUEST[service_type]'</script>";
    
    }
    else{
        $bill_no1=$newBill->insertESIBill($_REQUEST['bill_no']);
        $data=explode("|",$bill_no1);
        $id=$data[1];
        $bill_no=$data[0];
        for($i=0;$i<count($_REQUEST['service_id']);$i++)
        {
            $newBill->setquantity($_REQUEST['quantity'][$i]);
            $newBill->setservice_id($_REQUEST['service_id'][$i]);
            $newBill->setprice($_REQUEST['price'][$i]);
            $newBill->setdisc_per($_REQUEST['disc_per'][$i]);
            $newBill->setdisc_amt($_REQUEST['disc_amt'][$i]);
            $newBill->setdoctor($_REQUEST['doctor_id'][$i]);

            $newBill->insertESIBillingDetails($id,$bill_no);
        }
       
        echo "<script>window.location='../print_esibill.php?id=$_REQUEST[patient_id]&bill_no=$bill_no';</script>";
        
        
    }
    
        
       
    
}
if($_REQUEST['submit']=='discharge')
{
    if(strlen($_FILES["file"]['name'])>0)
    
    {   
    
        $file=rand(1000,100000)."-".$_FILES["file"]["name"];
        $spath = "uploads/$file";  
           $target_path = "../../uploads/";  
        $target_path = $target_path.basename( $file);   
              
        if(move_uploaded_file($_FILES['file']['tmp_name'], $target_path)) {  
            // echo "File uploaded successfully!";
            // call  function where to store in db and insert $target_path  
        } 
               
        
        if(strlen($_FILES["file"]['name'])>0)
        {   
            $newBill->setfile($spath);
        }
        // 
    }

$newBill->setpatinetid($_REQUEST['patinetid']);
$newBill->setrefno($_REQUEST['refno']);
$newBill->setfname($_REQUEST['fname']);
$newBill->setage($_REQUEST['age']);
$newBill->setsex($_REQUEST['sex']);

$newBill->setadmidate($_REQUEST['admidate']);
$newBill->setdisdate(date("Y-m-d H:i:s",strtotime($_REQUEST['disdate'])));
$newBill->setdiagnosis($_REQUEST['diagnosis']);
$newBill->setcomplaint($_REQUEST['complaint']);

$newBill->setinvestigations($_REQUEST['investigations']);
$newBill->setcourse($_REQUEST['course']);
$newBill->setexam($_REQUEST['exam']);
$newBill->setadvice($_REQUEST['advice']);
$newBill->setdeptid($_REQUEST['deptid']);

$newBill->setdis_condtion($_REQUEST['dis_condtion']);


$newBill->insertDischarge();
echo "<script>window.location='../view_ipbilling.php?id=$_REQUEST[patinetid]&bill_no=$_REQUEST[bill_no]&bill_id=$_REQUEST[bill_id]'</script>";



}
if($_REQUEST['submit']=='update_payment')
{
    $newBill->updatePayment($_REQUEST['amount'],$_REQUEST['date'],$_REQUEST['payment_mode'],$_REQUEST['payment_note'],$_REQUEST['payment_id'],$_REQUEST['bill_id'],$_REQUEST['collected_by']);
    echo "<script>window.location='../view_billing.php?id=$_REQUEST[id]&bill_no=$_REQUEST[bill_no]&bill_id=$_REQUEST[bill_id]'</script>";
}

if(isset($_REQUEST['submit']) && $_REQUEST['submit']=='bulk_payment')
{
    $billIds  = isset($_REQUEST['bulk_bill_ids'])  ? $_REQUEST['bulk_bill_ids']  : array();
    $billNos  = isset($_REQUEST['bulk_bill_nos'])  ? $_REQUEST['bulk_bill_nos']  : array();
    $amounts  = isset($_REQUEST['bulk_amounts'])   ? $_REQUEST['bulk_amounts']   : array();
    $payDate  = $_REQUEST['bulk_payment_date'];
    $payMode  = $_REQUEST['bulk_payment_method'];
    $payNote  = isset($_REQUEST['bulk_reference_note']) ? $_REQUEST['bulk_reference_note'] : '';

    for($i = 0; $i < count($billIds); $i++) {
        $amt    = floatval($amounts[$i]);
        $billId = intval($billIds[$i]);
        $billNo = $billNos[$i];
        if($amt <= 0) continue;
        $newBill->setpaid_amt($amt);
        $newBill->setpayment_mode($payMode);
        $newBill->setpayment_date($payDate);
        $newBill->setnote($payNote);
        $newBill->addPayment($billId, $billNo);
    }

    $id      = $_REQUEST['redirect_id'];
    $bill_no = $_REQUEST['redirect_bill_no'];
    $bill_id = $_REQUEST['redirect_bill_id'];
    echo "<script>window.location='../view_ipbilling.php?id=$id&bill_no=$bill_no&bill_id=$bill_id&status=PAID'</script>";
}

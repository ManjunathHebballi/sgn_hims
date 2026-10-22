<?php
require_once(__DIR__ . '/../model/session_config.php');
include('../model/masterClass.php');
$newMaster = new master;
echo "<pre>";
// print_r($_REQUEST);exit;
if($_REQUEST['submit']=='bed')
{
    $newMaster->setbed($_REQUEST['bed']);
    $newMaster->setward($_REQUEST['ward']);
    $newMaster->setdepartment($_REQUEST['department']);

    if($_REQUEST['page']=='new')
    {
       $newMaster->insertBed();
       
    }
    else{
        $newMaster->updateBed($_REQUEST['page']);
    }
   echo "<script>window.location='../master.php#pills-bed-tab'</script>";
    
}
if($_REQUEST['submit']=='Roga')
{
    // print_r($_REQUEST);exit;
    $newMaster->setroga($_REQUEST['roga']);
    if($_REQUEST['page']=='new')
    {
       $newMaster->insertRoga();
     
    }
    else{
        $newMaster->updateRoga($_REQUEST['page']);
    }
      echo "<script>window.location='../master.php#pills-roga-tab'</script>";
}
if($_REQUEST['submit']=='procedure')
{
    // print_r($_REQUEST);exit;
    $newMaster->setprocedure($_REQUEST['procedure']);
    if($_REQUEST['page']=='new')
    {
       $newMaster->insertProcedure();
      
    }
    else{
        $newMaster->updateProcedure($_REQUEST['page']);
    }
     echo "<script>window.location='../master.php#pills-procedures-tab'</script>";
}
if($_REQUEST['submit']=='subProcedure')
{
    // print_r($_REQUEST);exit;
    $newMaster->setprocedure($_REQUEST['procedure']);
    $newMaster->setsub_procedure($_REQUEST['sub_procedure']);

    if($_REQUEST['page']=='new')
    {
       $newMaster->insertSubProcedure();
       
    }
    else{
        $newMaster->updateSubProcedure($_REQUEST['page']);
    }
    echo "<script>window.location='../master.php#pills-sub-procedures-tab'</script>";
}
if($_REQUEST['submit']=='ot_type')
{
    // print_r($_REQUEST);exit;
    $newMaster->setot($_REQUEST['ot']);

    if($_REQUEST['page']=='new')
    {
       $newMaster->insertOt_type();
       
    }
    else{
        $newMaster->updateOt_type($_REQUEST['page']);
    }
    echo "<script>window.location='../master.php#pills-ot-tab'</script>";
}
if($_REQUEST['submit']=='ot_proce')
{
    // print_r($_REQUEST);exit;
    $newMaster->setot($_REQUEST['ottype']);

    $newMaster->setot_procedure($_REQUEST['ot_procedure']);

    if($_REQUEST['page']=='new')
    {
       $newMaster->insertOt_procedure();
    }
    else{
        $newMaster->updateOt_procedure($_REQUEST['page']);
    }
    echo "<script>window.location='../master.php#pills-ot-procedures-tab'</script>";

}
if($_REQUEST['submit']=='proce_medic')
{
    $newMaster->setprocetype($_REQUEST['procetype']);

    $newMaster->setprocedure_medic($_REQUEST['procedure_medic']);

    if($_REQUEST['page']=='new')
    {
       $newMaster->insertProcedure_medic();
    }
    else{
        $newMaster->editOt_procedure();
    }
    echo "<script>window.location='../master.php#pills-ot-proceduresmed-tab'</script>";

}


if($_REQUEST['submit']=='bills')
{
    // print_r($_REQUEST);exit;
    $newMaster->setbills($_REQUEST['bill']);

    $newMaster->setamount($_REQUEST['amount']);

    if($_REQUEST['page']=='new')
    {
       $newMaster->insertBills();
    }
    else{
        $newMaster->updateBills($_REQUEST['page']);
    }
    echo "<script>window.location='../master.php#pills-bills-tab'</script>";

}

if($_REQUEST['submit']=='department')
{
    // print_r($_REQUEST);exit;
    $newMaster->setdept($_REQUEST['dept']);
    $newMaster->setbed_no($_REQUEST['bed_no']);
    $newMaster->setdept_type($_REQUEST['dept_type']);
    $newMaster->setunit_no($_REQUEST['unit_no']);

    if($_REQUEST['page']=='new')
    {
       $newMaster->insertdepartment();
    }
    else{
        $newMaster->updateDepartment($_REQUEST['page']);
    }
    echo "<script>window.location='../master.php#pills-units-tab'</script>";

}
if($_REQUEST['submit']=='unit')
{
    $newMaster->setunit_type($_REQUEST['unit_type']);
    $newMaster->insertUnit();
    header("Location:../unit_type.php");
}
if($_REQUEST['submit']=='tax')
{
    $newMaster->setname($_REQUEST['name']);
    $newMaster->setpercentage($_REQUEST['percentage']);
    $newMaster->insertTax();
    header("Location:../tax_category.php");
}
if($_REQUEST['submit']=='charge_type')
{
    $newMaster->setchargename($_REQUEST['chargename']);
    $newMaster->setlab_test($_REQUEST['lab_test']);
    $newMaster->insertChargeType();
    header("Location:../charge_type.php");
}
if($_REQUEST['submit']=='charge')
{    
    $newMaster->setcharge_type($_REQUEST['charge_type']);
    $newMaster->setchargename($_REQUEST['chargename']);
    $newMaster->setdescription($_REQUEST['description']);
    $newMaster->insertCharge();
    header("Location:../charge_category_list.php");
}
if($_REQUEST['submit']=='radio_category')
{    
    $newMaster->setcategory($_REQUEST['category']);
  
    $newMaster->insertRadioCat();
    header("Location:../radiology_category.php");
}
if($_REQUEST['submit']=='radio_unit')
{    
    $newMaster->setunit_type($_REQUEST['unit_type']);
  
    $newMaster->insertRadioUnit();
    header("Location:../radiology_unit_type.php");
}
if($_REQUEST['submit']=='radio_para')
{    
    $newMaster->setpara_name($_REQUEST['para_name']);
    $newMaster->setrange($_REQUEST['range']);
    $newMaster->setunit_type($_REQUEST['unit_type']);
  
    $newMaster->insertRadioPara();
    header("Location:../radiology_parameter.php");
}
if($_REQUEST['submit']=='patho_category')
{    
    $newMaster->setpatho_category($_REQUEST['patho_category']);
  
    $newMaster->insertPathoCat();
    header("Location:../pathology_category.php");
}
if($_REQUEST['submit']=='patho_unit')
{    
    $newMaster->setunit_type($_REQUEST['unit_type']);
  
    $newMaster->insertPathoUnit();
    header("Location:../pathology_unit_type.php");
}

if($_REQUEST['submit']=='patho_para')
{    
    $newMaster->setpara_name($_REQUEST['para_name']);
    $newMaster->setrange($_REQUEST['range']);
    $newMaster->setunit_type($_REQUEST['unit_type']);
  
    $newMaster->insertPathoPara();
    header("Location:../pathology_parameter.php");
}

if($_REQUEST['submit']=='doctor')
{
    $newMaster->setdob($_REQUEST['dob']);
$newMaster->setfname($_REQUEST['fname']);
$newMaster->setlname($_REQUEST['lname']);
$newMaster->setabbname($_REQUEST['abbname']);
$newMaster->setsex($_REQUEST['sex']);
$newMaster->setdoctor_type($_REQUEST['doctor_type']);
$newMaster->setspeciality($_REQUEST['speciality']);
$newMaster->setschedule_type($_REQUEST['schedule_type']);
$newMaster->setregistration_no($_REQUEST['registration_no']);
$newMaster->setaadhar($_REQUEST['aadhar']);
$newMaster->setpancard($_REQUEST['pancard']);
$newMaster->setqualification($_REQUEST['qualification']);
$newMaster->setcontact($_REQUEST['contact']);
$newMaster->setdept($_REQUEST['dept']);
$newMaster->setbill_dept($_REQUEST['bill_dept']);
$newMaster->setemail($_REQUEST['email']);
$newMaster->setvisit($_REQUEST['visit']);
$newMaster->setresidence($_REQUEST['residence']);
$newMaster->setcountry($_REQUEST['country']);
$newMaster->setstate($_REQUEST['state']);
$newMaster->setcity($_REQUEST['city']);
$newMaster->setpin($_REQUEST['pin']);
$newMaster->setclinic($_REQUEST['clinic']);
$newMaster->setc_country($_REQUEST['c_country']);
$newMaster->setc_state($_REQUEST['c_state']);
$newMaster->setc_city($_REQUEST['c_city']);
$newMaster->setc_pin($_REQUEST['c_pin']);
$newMaster->setc_tel($_REQUEST['c_tel']);
$newMaster->setbank_type($_REQUEST['bank_type']);
$newMaster->setaccount_no($_REQUEST['account_no']);
$newMaster->setifsc($_REQUEST['ifsc']);
$newMaster->setConsultation($_REQUEST['fees']);
if($_REQUEST['editid'])
{
    $newMaster->updateDoctorDetails($_REQUEST['editid']);
}
else{
    $newMaster->insertDoctorDetails();
}
echo "<script>window.location='../master.php#pills-doctor-tab'</script>";

}


if($_REQUEST['submit']=='diagnosis_icd')
{    
    $newMaster->seticd($_REQUEST['icd']);
    $newMaster->setdetails($_REQUEST['details']);
   
  
     if($_REQUEST['page']=='new')
    {
       $newMaster->insertdiagnosis_icd();
       
    }
    else{
        $newMaster->updatediagnosis_icd($_REQUEST['page']);
    }
    echo "<script>window.location='../master.php#pills-diag_icd-tab'</script>";
}


if($_REQUEST['submit']=='diagnosis')
{    
    $newMaster->setdiag_name($_REQUEST['diag_name']);
    $newMaster->setdih($_REQUEST['dih']);
    $newMaster->setmin_age($_REQUEST['min_age']);
    $newMaster->setmax_age($_REQUEST['max_age']);
    $newMaster->setgender($_REQUEST['gender']);
    $newMaster->setdept($_REQUEST['dept']);
   
  
     if($_REQUEST['page']=='new')
    {
       $newMaster->insertdiagnosis();
       
    }
    else{
        $newMaster->updatediagnosis($_REQUEST['page']);
    }
    echo "<script>window.location='../master.php#pills-diag-tab'</script>";
}
if($_REQUEST['submit']=='patient_type')
{
    if(!empty($_REQUEST['old_patient_type']))
    {
        $err=$newMaster->updatePatientType($_REQUEST['old_patient_type'], $_REQUEST['patient_type']);
    }
    else{
        $err=$newMaster->insertPatientType($_REQUEST['patient_type']);
    }
    $qs = $err === '' ? 'pt_saved=1' : 'pt_error=' . urlencode($err);
    echo "<script>window.location='../master.php?$qs#pills-ptype-tab'</script>";
}




?>
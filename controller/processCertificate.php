<?php
require_once(__DIR__ . '/../model/session_config.php');
include('../model/certificateClass.php');
$newCerti = new addCerti;
// print_r($_REQUEST);exit;

if($_REQUEST['submit']=='birth')
{
    $newCerti->setipd_no($_REQUEST['ipd_no']);
    $newCerti->setmother_name($_REQUEST['mother_name']);
    $newCerti->setbaby_name($_REQUEST['baby_name']);
    $newCerti->setbaby_gender($_REQUEST['baby_gender']);
    $newCerti->setmobile_no($_REQUEST['mobile_no']);
    $newCerti->setpatient_id($_REQUEST['patient_id']);
    $newCerti->setdoctor_name($_REQUEST['doctor_name']);
    $newCerti->setweight($_REQUEST['weight']);
    $newCerti->setheight($_REQUEST['height']);
    $newCerti->setdob($_REQUEST['dob']);
   

       $newCerti->insertBirth();
       echo "<script>window.location='../birth_record.php'</script>";
   
}


if($_REQUEST['submit']=='edit_birth')
{
    $newCerti->setipd_no($_REQUEST['model_ipd_no']);
    $newCerti->setmother_name($_REQUEST['model_mother_name']);
    $newCerti->setbaby_name($_REQUEST['model_baby_name']);
    $newCerti->setbaby_gender($_REQUEST['model_baby_gender']);
    $newCerti->setmobile_no($_REQUEST['model_mobile_no']);
    $newCerti->setpatient_id($_REQUEST['model_patient_id']);
    $newCerti->setdoctor_name($_REQUEST['model_doctor_name']);
    $newCerti->setweight($_REQUEST['model_weight']);
    $newCerti->setheight($_REQUEST['model_height']);
    $newCerti->setdob($_REQUEST['model_dob']);
   

       $newCerti->updateBirth($_REQUEST['birth_id']);
       echo "<script>window.location='../birth_record.php'</script>";
   
}



if($_REQUEST['submit']=='death')
{
    $newCerti->setipd_no($_REQUEST['ipd_no']);
    $newCerti->setpatient_name($_REQUEST['patient_name']);
    $newCerti->setgender($_REQUEST['gender']);
    $newCerti->setmobile_no($_REQUEST['mobile_no']);
    $newCerti->setpatient_id($_REQUEST['patient_id']);
    $newCerti->setdoctor_name($_REQUEST['doctor_name']);
    $newCerti->setdod($_REQUEST['dod']);
    $newCerti->setcause($_REQUEST['cause']);
   

       $newCerti->insertDeath();
       echo "<script>window.location='../death_record.php'</script>";
   
}

if($_REQUEST['submit']=='edit_death')
{
    $newCerti->setipd_no($_REQUEST['model_ipd_no']);
    $newCerti->setpatient_name($_REQUEST['model_patient_name']);
    $newCerti->setgender($_REQUEST['model_gender']);
    $newCerti->setmobile_no($_REQUEST['model_mobile_no']);
    $newCerti->setpatient_id($_REQUEST['model_patient_id']);
    $newCerti->setdoctor_name($_REQUEST['model_doctor_name']);
    $newCerti->setdod($_REQUEST['model_dod']);
    $newCerti->setcause($_REQUEST['model_cause']);
   

       $newCerti->updateDeath($_REQUEST['death_id']);
       echo "<script>window.location='../death_record.php'</script>";
   
}

<?php
require_once(__DIR__ . '/../../model/session_config.php');
include('../model/masterClass.php');
$newmaster = new master;
// print_r($_REQUEST);
// exit;
if($_REQUEST['submit']=='group')
{
    $newmaster->setgroup_name($_REQUEST['group_name']);
    $newmaster->setbill_group($_REQUEST['bill_group']);
    
     if($_REQUEST['page']=='new')
    {
        $newmaster->insertServiceGroup();
    }
    else
    {
        $newmaster->updateServiceGroup($_REQUEST['page']);
    }
    header("Location: ../master.php#pills-home-tab");
}


if($_REQUEST['submit']=='service')
{
    $newmaster->setgroup_name($_REQUEST['group_name']);
    $newmaster->setservice_name($_REQUEST['service_name']);
    $newmaster->setesi_code($_REQUEST['esi_code']);
    $newmaster->setesi_charges($_REQUEST['esi_charges']);
    $newmaster->setopd_charge($_REQUEST['opd_charge']);
    $newmaster->setipd_charge($_REQUEST['ipd_charge']);
    $newmaster->setdoctor_share($_REQUEST['doctor_share']);
    $newmaster->settype($_REQUEST['type']);
     if($_REQUEST['page']=='new')
    {
    $newmaster->insertServiceList();
    }
    else
    {
        $newmaster->setstatus($_REQUEST['status']);
        $newmaster->updateServiceList($_REQUEST['page']);
    }
    header("Location: ../master.php#pills-ot-tab");
}
if($_REQUEST['submit']=='doctor')
{
    $newmaster->setdoctor($_REQUEST['doctor']);
    $newmaster->setservice_name($_REQUEST['service_name']);
    $newmaster->setamt_share2doctor($_REQUEST['amt_share2doctor']);
    $newmaster->setperc_share2doctor($_REQUEST['perc_share2doctor']);
    $newmaster->setperc_share2hospital($_REQUEST['perc_share2hospital']);
     if($_REQUEST['page']=='new')
    {
    $newmaster->insertDoctorShare();
    }
    else
    {
        $newmaster->updateDoctorShare($_REQUEST['page']);
    }
    echo "<script>window.location= '../master.php#pills-doct-tab'</script>";
}

?>
<?php
require_once(__DIR__ . '/../model/session_config.php');
include('../model/ambulanceClass.php');
$ambulance=new ambulance;
if($_REQUEST['submit']=='add')
{
    $ambulance->setnumber($_REQUEST['number']);
    $ambulance->setcharge($_REQUEST['charge']);
    $ambulance->setname($_REQUEST['name']);
    $ambulance->setlicense($_REQUEST['license']);
    $ambulance->setassistant($_REQUEST['assistant']);
    $ambulance->setvehicle_type($_REQUEST['vehicle_type']);
    $ambulance->setcontact($_REQUEST['contact']);
    $res=$ambulance->addAmbulance();
    if($res > 0)
    {
        echo "<script>alert('New Ambulance is added');window.location='../list_ambulance.php'</script>";
    }
    else{
        echo "<script>alert('Ambulance is Not added');window.location='../list_ambulance.php'</script>";
    }
   
}
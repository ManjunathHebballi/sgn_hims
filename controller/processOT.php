<?php
require_once(__DIR__ . '/../model/session_config.php');
include('../model/otClass.php');
$newOT = new addOT;
// print_r($_REQUEST);exit;
if($_REQUEST['submit']=='stock')
{
    $newOT->setsutra($_REQUEST['sutra']);
    $newOT->setused($_REQUEST['used']);
    $newOT->setbalance($_REQUEST['balance']);

       $newOT->insertStock();
       echo "<script>window.location='../stockragister.php'</script>";
   
}

if($_REQUEST['submit']=='pads')
{
    $newOT->setpads($_REQUEST['pads']);
    $newOT->setused($_REQUEST['used']);
    $newOT->setbalance($_REQUEST['balance']);

       $newOT->insertPads();
       echo "<script>window.location='../padsregister.php'</script>";
   
}

if($_REQUEST['submit']=='swabs')
{
    $newOT->setswabs($_REQUEST['swabs']);
    $newOT->setused($_REQUEST['used']);
    $newOT->setbalance($_REQUEST['balance']);

       $newOT->insertSwabs();
       echo "<script>window.location='../swabregister.php'</script>";
   
}
if($_REQUEST['submit']=='fumigation')
{
    $newOT->setnote($_REQUEST['note']);
 

       $newOT->insertFumigation();
       echo "<script>window.location='../fumigation.php'</script>";
   
}
if($_REQUEST['submit']=='clave')
{
    $newOT->setnote($_REQUEST['note']);
 

       $newOT->insertClave();
       echo "<script>window.location='../auto.php'</script>";
   
}
if($_REQUEST['submit']=='ot_reg')
{
    $newOT->setip_no($_REQUEST['id']);
    $newOT->setdoctor($_REQUEST['doctor']);
    $newOT->setottype($_REQUEST['ottype']);
    $newOT->setotdate($_REQUEST['otdate']);
    if($_REQUEST['major_ot'] > 0)
    {
        $newOT->setotprocedure($_REQUEST['major_ot']);

    }
    else{
        $newOT->setotprocedure($_REQUEST['minor_ot']);
    }
    $newOT->setotcomments($_REQUEST['comments']);

       $newOT->insertOTReg();
       echo "<script>window.location='../ot.php?id=$_REQUEST[id]'</script>";
   
}
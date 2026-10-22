<?php
require_once(__DIR__ . '/../model/session_config.php');
include('../model/masterClass.php');
$newMaster = new master;

// print_r($_REQUEST);
if($_REQUEST['day']=='Monday')
{
    $day='mo_';
}
if($_REQUEST['day']=='Tuesday')
{
    $day='tu_';
}
if($_REQUEST['day']=='Wednesday')
{
    $day='we_';
}
if($_REQUEST['day']=='Thursday')
{
    $day='th_';
}
if($_REQUEST['day']=='Friday')
{
    $day='fr_';
}
if($_REQUEST['day']=='Saturday')
{
    $day='sa_';
}
if($_REQUEST['day']=='Sunday')
{
    $day='su_';
}
$newMaster->setdept($_REQUEST[$day.'dept']);
$newMaster->setdoctor($_REQUEST[$day.'dctor']);
$newMaster->setfrom($_REQUEST[$day.'from']);
$newMaster->setto($_REQUEST[$day.'to']);
$newMaster->setday($_REQUEST['day']);


$newMaster->insertTimeTable();
header("Location:../create-timetable.php");
?>
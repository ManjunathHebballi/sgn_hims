<?php
require_once(__DIR__ . '/../model/session_config.php');
include('../model/optreatmentClass.php');
$newOT = new addOTTreat;
// print_r($_REQUEST);exit;
if($_REQUEST['submit']=='exam')
{
    // print_r($_REQUEST);exit;
    $newOT->setcomone($_REQUEST['one']);
    $newOT->setcomtwo($_REQUEST['two']);
    $newOT->setcomthree($_REQUEST['three']);
    $newOT->setcomfour($_REQUEST['four']);
    $newOT->setnadi($_REQUEST['nadi']);
    $newOT->setmala($_REQUEST['mala']);
    $newOT->setmutra($_REQUEST['mutra']);
    $newOT->setjiva($_REQUEST['jiva']);
    $newOT->setsparsha($_REQUEST['sparsha']);
    $newOT->setshabda($_REQUEST['shabda']);
    $newOT->setdrik($_REQUEST['drik']);
    $newOT->setakriti($_REQUEST['akriti']);
    $newOT->setrs($_REQUEST['rs']);
    $newOT->setlung($_REQUEST['lung']);
    $newOT->setothers($_REQUEST['others']);
    $newOT->setpulse($_REQUEST['pulse']);
    $newOT->setbp($_REQUEST['bp']);
    $newOT->setheart($_REQUEST['heart']);
    $newOT->setpa($_REQUEST['pa']);
    $newOT->setorganomegaly($_REQUEST['Organomegaly']);
    $newOT->setcns($_REQUEST['cns']);
    $newOT->setpower($_REQUEST['power']);
    $newOT->setreflexes($_REQUEST['reflexes']);
    $newOT->settone($_REQUEST['tone']);
    $newOT->setroga($_REQUEST['roga']);
    $newOT->setdept($_REQUEST['dept']);
    $newOT->setopd($_REQUEST['opd']);
    

    if($_REQUEST['page']=='new')
    {
       $newOT->insertOPTreat();
    }
    else{
        $newOT->updateOPTreat();
    }
    echo "<script>window.location='../optreatment.php?id=$_REQUEST[opd]#v-pills-examination-tab'</script>";
}
if($_REQUEST['submit']== 'invest')
{
// print_r($_REQUEST);exit;
       $bill_no= $newOT->insertOPInvest($_REQUEST['lab_test'],$_REQUEST['pid'],$_REQUEST['date']);
       header("Location:../lab_report.php?id=$_REQUEST[pid]&bill_no=$bill_no");
}
if($_REQUEST['submit']== 'medic')
{
// print_r($_REQUEST);exit;
$newOT->setomedicine($_REQUEST['omedicine']);
$newOT->setroga($_REQUEST['dose1']);
$newOT->setqty($_REQUEST['qty1']);
$newOT->settotal1($_REQUEST['total1']);
$newOT->setmorning($_REQUEST['morning']);
$newOT->setafternoon($_REQUEST['afternoon']);
$newOT->setevening($_REQUEST['evening']);
$newOT->setnight($_REQUEST['night']);
$newOT->setanupana($_REQUEST['anupana']);
// $newOT->setnight($_REQUEST['night']);
        $newOT->insertOPMedic($_REQUEST['pid']);
       echo "<script>window.location='../optreatment.php?id=$_REQUEST[pid]#v-pills-medication-tab'</script>";
}
if($_REQUEST['submit']== 'proced')
{

$newOT->setpdate($_REQUEST['pdate']);
$newOT->setprocedure($_REQUEST['procedure']);
$newOT->setsubprocedure($_REQUEST['subprocedure']);
$newOT->setomedicine($_REQUEST['proce_medicine']);
$newOT->setroga($_REQUEST['dose']);
$newOT->setqty($_REQUEST['qty']);
$newOT->settotal1($_REQUEST['total']);
$newOT->setmorning($_REQUEST['morning']);
$newOT->setafternoon($_REQUEST['afternoon']);
$newOT->setevening($_REQUEST['evening']);
$newOT->setnight($_REQUEST['night']);
$newOT->setanupana($_REQUEST['observation']);
        $newOT->insertProceMedic($_REQUEST['pid']);
       echo "<script>window.location='../optreatment.php?id=$_REQUEST[pid]#v-pills-treatment-tab'</script>";
}
?>
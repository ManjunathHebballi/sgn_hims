<?php
require_once(__DIR__ . '/../model/session_config.php');
include('../model/opClass.php');

$newOP = new addOP;
if($_REQUEST['submit']=='opreg')
{
$newOP->setadmdate($_REQUEST['admdate']);
$newOP->setcard($_REQUEST['card']);
$newOP->setfname($_REQUEST['fname']);
$newOP->setage($_REQUEST['age']);
$newOP->setdob(isset($_REQUEST['dob']) ? $_REQUEST['dob'] : '');
$newOP->setState(isset($_REQUEST['state']) ? $_REQUEST['state'] : '');
$newOP->setDistrict(isset($_REQUEST['district']) ? $_REQUEST['district'] : '');
$newOP->setPincode(isset($_REQUEST['pincode']) ? $_REQUEST['pincode'] : '');
// Only present when registration was opened from a verified ABHA profile.
$newOP->setabhanumber(isset($_REQUEST['abha_number']) ? $_REQUEST['abha_number'] : '');
$newOP->setabhaaddress(isset($_REQUEST['abha_address']) ? $_REQUEST['abha_address'] : '');
$newOP->setsex($_REQUEST['sex']);
$newOP->setaddress($_REQUEST['address']);
$newOP->setmobile($_REQUEST['mobile']);
$newOP->setreligion($_REQUEST['religion']);
$newOP->setpan($_REQUEST['pan']);
$newOP->setaadhar($_REQUEST['aadhar']);
$newOP->setcaste($_REQUEST['caste']);
$newOP->setpolicy($_REQUEST['policy']);
$newOP->setpname($_REQUEST['pname']);
// print_r($_REQUEST);
$newOP->setmarital($_REQUEST['marital']);
$newOP->setoccupation($_REQUEST['occupation']);
$newOP->seteconomic($_REQUEST['economic']);
if($_REQUEST['economic']!='BPL')
{
    $_REQUEST['cardn']='';
}
$newOP->setcardn($_REQUEST['cardn']);
$newOP->setdepartment($_REQUEST['department']);
$newOP->setsubdepartment($_REQUEST['subdepartment']);
$newOP->setdiagnosis($_REQUEST['diagnosis']);
$newOP->setconsultancy($_REQUEST['consultancy']);
$newOP->setdoctor($_REQUEST['doctor']);
$newOP->setptype($_REQUEST['ptype']);
$newOP->setpcomment($_REQUEST['pcomment']);
$newOP->setmlc($_REQUEST['mlc']);
$newOP->setcity($_REQUEST['city']);
$newOP->insertOP();
// header("Location:../opregistration.php");
}
if($_REQUEST['submit']=='revisit')
{
$newOP->setdepartment($_REQUEST['dept']);
$newOP->setopd_no($_REQUEST['opd_no1']);
// $newOP->setdiagnosis($_REQUEST['cmts']);
$newOP->setadmdate($_REQUEST['date']);
$newOP->setconsultancy($_REQUEST['consultancy1']);
$newOP->setdoctor($_REQUEST['doctor']);
// Carry the current ABHA + contact details onto the new follow-up visit row
// (revisitOP uses these when present, instead of copying the previous visit's).
$newOP->setabhanumber(isset($_REQUEST['abha_number']) ? $_REQUEST['abha_number'] : '');
$newOP->setabhaaddress(isset($_REQUEST['abha_address']) ? $_REQUEST['abha_address'] : '');
$newOP->setmobile(isset($_REQUEST['mobile']) ? $_REQUEST['mobile'] : '');
$newOP->setaddress(isset($_REQUEST['address']) ? $_REQUEST['address'] : '');
$newOP->setcity(isset($_REQUEST['city']) ? $_REQUEST['city'] : '');
$newOP->setState(isset($_REQUEST['state']) ? $_REQUEST['state'] : '');
$newOP->setDistrict(isset($_REQUEST['district']) ? $_REQUEST['district'] : '');
$newOP->setPincode(isset($_REQUEST['pincode']) ? $_REQUEST['pincode'] : '');
$newOP->revisitOP();
// Save any edited Mobile / Address / City / State / District / PIN onto the
// patient's existing OPD rows.
if(isset($_REQUEST['mobile']) || isset($_REQUEST['address']) || isset($_REQUEST['city'])
   || isset($_REQUEST['state']) || isset($_REQUEST['district']) || isset($_REQUEST['pincode'])) {
    $newOP->updateOPContact(
        $_REQUEST['opd_no1'],
        isset($_REQUEST['mobile'])   ? $_REQUEST['mobile']   : '',
        isset($_REQUEST['address'])  ? $_REQUEST['address']  : '',
        isset($_REQUEST['city'])     ? $_REQUEST['city']     : '',
        isset($_REQUEST['state'])    ? $_REQUEST['state']    : '',
        isset($_REQUEST['district']) ? $_REQUEST['district'] : '',
        isset($_REQUEST['pincode'])  ? $_REQUEST['pincode']  : ''
    );
}

}
if($_REQUEST['submit']=='editopreg')
{
$newOP->setadmdate($_REQUEST['admdate']);
$newOP->setcard($_REQUEST['card']);
$newOP->setfname($_REQUEST['fname']);
$newOP->setage($_REQUEST['age']);
$newOP->setdob(isset($_REQUEST['dob']) ? $_REQUEST['dob'] : '');
$newOP->setState(isset($_REQUEST['state']) ? $_REQUEST['state'] : '');
$newOP->setDistrict(isset($_REQUEST['district']) ? $_REQUEST['district'] : '');
$newOP->setPincode(isset($_REQUEST['pincode']) ? $_REQUEST['pincode'] : '');
$newOP->setsex($_REQUEST['sex']);
$newOP->setaddress($_REQUEST['address']);
$newOP->setmobile($_REQUEST['mobile']);
$newOP->setreligion($_REQUEST['religion']);
$newOP->setpan($_REQUEST['pan']);
$newOP->setaadhar($_REQUEST['aadhar']);
$newOP->setcaste($_REQUEST['caste']);
$newOP->setpcomment($_REQUEST['remark']);
$newOP->setpolicy($_REQUEST['policy']);
$newOP->setpname($_REQUEST['pname']);
// print_r($_REQUEST);
$newOP->setmarital($_REQUEST['marital']);
$newOP->setoccupation($_REQUEST['occupation']);
$newOP->seteconomic($_REQUEST['economic']);
if($_REQUEST['economic']!='BPL')
{
    $_REQUEST['cardn']='';
}
$newOP->setcardn($_REQUEST['cardn']);
$newOP->setdepartment($_REQUEST['department']);
$newOP->setdiagnosis($_REQUEST['diagnosis']);
$newOP->setdoctor($_REQUEST['doctor']);
$newOP->editOP($_REQUEST['editid']);
header("Location:../oppatient.php");
}

?>
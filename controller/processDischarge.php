<?php
require_once(__DIR__ . '/../model/session_config.php');
include('../model/dischargeClass.php');
$newDischarge = new discharge;

if(strlen($_FILES["file"]['name'])>0)

{   

    $file=rand(1000,100000)."-".$_FILES["file"]["name"];
    $spath = "uploads/$file";  
       $target_path = "../uploads/";  
    $target_path = $target_path.basename( $file);   
          
    if(move_uploaded_file($_FILES['file']['tmp_name'], $target_path)) {  
        // echo "File uploaded successfully!";
        // call  function where to store in db and insert $target_path  
    } 
           
    
    if(strlen($_FILES["file"]['name'])>0)
    {   
        $newDischarge->setfile($spath);
    }
    // 
}

$newDischarge->setpatinetid($_REQUEST['patinetid']);
$newDischarge->setrefno($_REQUEST['refno']);
$newDischarge->setfname($_REQUEST['fname']);
$newDischarge->setage($_REQUEST['age']);
$newDischarge->setsex($_REQUEST['sex']);

$newDischarge->setadmidate($_REQUEST['admidate']);
$newDischarge->setdisdate(date("Y-m-d H:i:s",strtotime($_REQUEST['disdate'])));
$newDischarge->setdiagnosis($_REQUEST['diagnosis']);
$newDischarge->setcomplaint($_REQUEST['complaint']);

$newDischarge->setinvestigations($_REQUEST['investigations']);
$newDischarge->setcourse($_REQUEST['course']);
$newDischarge->setexam($_REQUEST['exam']);
$newDischarge->setadvice($_REQUEST['advice']);
$newDischarge->setdeptid($_REQUEST['deptid']);

$newDischarge->setillness($_REQUEST['illness']);
$newDischarge->setpast($_REQUEST['past']);
$newDischarge->setexamination($_REQUEST['examination']);
$newDischarge->setreview($_REQUEST['review']);

$newDischarge->setdis_condtion($_REQUEST['dis_condtion']);

if(isset($_REQUEST['editid']))
{
    $newDischarge->updateDischarge($_REQUEST['editid']);
    echo "<script>window.location='../dischargelist.php'</script>";
}
else{
$newDischarge->insertDischarge();
echo "<script>window.location='../discharge.php'</script>";
}

?>
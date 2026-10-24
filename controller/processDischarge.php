<?php
require_once(__DIR__ . '/../model/session_config.php');
include('../model/dischargeClass.php');
include('../model/optreatmentClass1.php');
require_once(__DIR__ . '/../upload_guard.php');
$newDischarge = new discharge;

// The discharge summary file is also kept as a patient document, so it lists on
// patient_documents.php and links to the patient's ABHA as a HealthDocumentRecord.
$spath = '';
if(isset($_FILES["file"]) && strlen($_FILES["file"]['name'])>0)
{
    $upErr = null;
    $stored = uploadGuardStore($_FILES['file'], 'document', dirname(__DIR__) . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'patient_documents', $upErr);
    if ($stored === null) {
        echo "<script>alert(" . json_encode('Discharge summary file not saved: ' . $upErr) . ");history.back();</script>";
        exit;
    }
    $spath = 'uploads/patient_documents/' . $stored;
    $newDischarge->setfile($spath);
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
    $saved = $newDischarge->updateDischarge($_REQUEST['editid']);
    $redirect = '../dischargelist.php';
}
else{
$saved = $newDischarge->insertDischarge();
$redirect = '../discharge.php';
}

if($saved && $spath !== '')
{
    $ipdNo = trim((string) $_REQUEST['patinetid']);
    $newDischarge->ensurePatientDocumentsTable();
    $patientRow = $newDischarge->getIpdPatientByNo($ipdNo);
    $patientName = $patientRow ? $patientRow['name'] : (string) $_REQUEST['fname'];
    $uploadedBy = isset($_SESSION['name']) ? $_SESSION['name'] : 'System';
    $documentId = $newDischarge->insertPatientDocument($ipdNo, $patientName, 'Discharge Summary', $spath, $uploadedBy);
    // Skips silently when the patient has no ABHA or auto-link is switched off.
    if($documentId !== false && $patientRow && !empty($patientRow['opd_id']))
    {
        $abhaModel = new addOTTreat();
        $abhaModel->linkAbhaSingleContext('document', $documentId, (int) $patientRow['opd_id'], $ipdNo);
    }
}
echo "<script>window.location='$redirect'</script>";

?>
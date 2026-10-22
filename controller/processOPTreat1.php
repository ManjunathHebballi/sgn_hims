<?php
// This project has no auth_guard.php; session_config.php starts the session and
// the model's dbconnect() enforces the login check on every query.
require_once(__DIR__ . '/../model/session_config.php');
include('../model/optreatmentClass1.php');
date_default_timezone_set('asia/kolkata');
$newOT = new addOTTreat;

function reqVal(string $key, $default = '')
{
    return isset($_REQUEST[$key]) ? $_REQUEST[$key] : $default;
}

function reqArr(string $key): array
{
    if (!isset($_REQUEST[$key])) {
        return [];
    }
    return is_array($_REQUEST[$key]) ? $_REQUEST[$key] : [$_REQUEST[$key]];
}

// The patient id is always a numeric central_opd.id. Pin it once here and pass
// $pid (never the raw request value) to every model call and the redirect below.
$pid = (int) reqVal('pid', 0);
// echo "<pre>";
// print_r($_REQUEST);

// exit;
$care_context = array();
$newOT->setcomo(reqArr('como'));
$newOT->sethyper(reqArr('hyper'));
$newOT->setassociated(reqArr('associated'));
$newOT->setcomplaint(reqArr('complaint'));
$newOT->sethistory((string) reqVal('history', ''));
// Family History arrives from optreatment.php as one row per relative
// (fh_relation[] / fh_condition[] / fh_age[] / fh_deceased[] / fh_contributed[])
// and is stored as JSON in the existing family_history TEXT column. That is what
// lets the FHIR bundle fill relationship / sex / condition / age /
// contributedToDeath instead of publishing a card full of "NA".
//
// NOTE: this block must stay in step with the form. If it is ever reduced back
// to reading a plain `family_history` field, the structured rows the form posts
// are silently dropped and family history stops saving altogether.
//
// Two fallbacks, so nothing is lost:
//   - optreatment1.php still posts the old free-text `family_history` field
//   - a legacy free-text value carried on the form is preserved verbatim
$fhRelations   = reqArr('fh_relation');
$fhConditions  = reqArr('fh_condition');
$fhAges        = reqArr('fh_age');
$fhDeceased    = reqArr('fh_deceased');
$fhContributed = reqArr('fh_contributed');

$familyRows = [];
foreach ($fhRelations as $i => $rel) {
    $rel  = trim((string) $rel);
    $cond = trim((string) ($fhConditions[$i] ?? ''));
    if ($rel === '' && $cond === '') {
        continue;   // blank row the user never filled in
    }
    $age = trim((string) ($fhAges[$i] ?? ''));
    $familyRows[] = [
        'relation'    => $rel,
        'condition'   => $cond,
        'age'         => ($age !== '' && ctype_digit($age)) ? $age : '',
        'deceased'    => (($fhDeceased[$i] ?? 'No') === 'Yes') ? 'Yes' : 'No',
        'contributed' => (($fhContributed[$i] ?? 'No') === 'Yes') ? 'Yes' : 'No',
    ];
}

if ($familyRows !== []) {
    $familyHistoryValue = json_encode($familyRows, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
} else {
    // No structured rows: keep whatever free text was posted (optreatment1.php,
    // or the read-only legacy box on optreatment.php).
    $familyHistoryValue = (string) reqVal('family_history', '');
    if (trim($familyHistoryValue) === '') {
        $familyHistoryValue = (string) reqVal('family_history_legacy', '');
    }
}
$newOT->setfamily_history($familyHistoryValue);
// Wellness / lifestyle assessment (WellnessRecord FHIR observations)
$newOT->setwellness([
    'smoking'          => (string) reqVal('smoking', ''),
    'alcohol'          => (string) reqVal('alcohol', ''),
    'diet'             => (string) reqVal('diet', ''),
    'steps'            => (string) reqVal('steps', ''),
    'calories_burned'  => (string) reqVal('calories_burned', ''),
    'sleep_duration'   => (string) reqVal('sleep_duration', ''),
    'lmp'              => (string) reqVal('lmp', ''),
    'menopause'        => (string) reqVal('menopause', ''),
    'menarche'         => (string) reqVal('menarche', ''),
    'fluid_intake'     => (string) reqVal('fluid_intake', ''),
    'mental_status'    => (string) reqVal('mental_status', ''),
    'overall_wellness' => (string) reqVal('overall_wellness', ''),
]);
$newOT->setbp((string) reqVal('bp', ''));
$newOT->setrbs((string) reqVal('rbs', ''));
$newOT->setpr((string) reqVal('pr', reqVal('pulse', '')));
$newOT->setht((string) reqVal('ht', ''));
$newOT->setwaist((string) reqVal('waist', ''));
$newOT->settp((string) reqVal('tp', ''));
$newOT->setrr((string) reqVal('rr', ''));
$newOT->setspo2((string) reqVal('spo', ''));
$newOT->sethc((string) reqVal('hc', ''));
$newOT->setbmi((string) reqVal('bmi', ''));
$newOT->setweight((string) reqVal('weight', ''));
$newOT->setexamination((string) reqVal('examination', ''));

$newOT->setdiagnosis(reqArr('diagnosis'));
$newOT->setlab_obs((string) reqVal('lab_obs', ''));
$newOT->setradio_obs((string) reqVal('radio_obs', ''));

$medicalAdvice = trim((string) reqVal('medical_advice', ''));
$procedureNotes = trim((string) reqVal('procedure', ''));
if ($procedureNotes !== '') {
    $medicalAdvice .= ($medicalAdvice !== '' ? "\n" : '') . 'Procedure: ' . $procedureNotes;
}
$newOT->setmedical_advice($medicalAdvice);

$advice = reqArr('advice');
$followUpNotes = trim((string) reqVal('follow_up_notes', ''));
if ($followUpNotes !== '') {
    $advice[] = 'Follow Up: ' . $followUpNotes;
}
$newOT->setadvice($advice);
$newOT->setrefer_to((string) reqVal('refer_to', ''));

$newOT->setvacc_date((string) reqVal('vacc_date', ''));
$newOT->setimmunization(reqArr('immunization'));
$newOT->setmanufacturer(reqArr('manufacturer'));
$newOT->setlot_number(reqArr('lot_number'));
$newOT->setdose_number(reqArr('dose_number'));

$newOT->setpatient_id($pid);

$immunization = [
    'referenceNumber' => '',
    'display' => '',
    'careContexts' => []
];
if (count(reqArr('immunization')) > 0) {
    $immunization = $newOT->insertImmunization($pid);
}


if (isset($_REQUEST['nextdate'])) {
    $date = $_REQUEST['nextdate'];
} else {
    $date = date("Y-m-d");
}

$days = reqVal('day', 0) . ' ' . reqVal('indays', 'days');
$date1 = date("Y-m-d", strtotime($date . ' + ' . $days));
$newOT->setnextdate($date1);
//   echo $date1;exit;
//   $newOT->setdiet($_REQUEST['diet']);
//   $newOT->setgeneral_exam($_REQUEST['general_exam']);


$wellnessJSON = [
    'referenceNumber' => '',
    'display' => '',
    'careContexts' => []
];
$consultationJSON = [
    'referenceNumber' => '',
    'display' => '',
    'careContexts' => []
];
$healthDocJSON = [
    'referenceNumber' => '',
    'display' => '',
    'careContexts' => []
];
if (isset($_REQUEST['exam_edit']) && $_REQUEST['exam_edit'] > 0) {
    // echo 'if';exit;
    $newOT->setopd((int) $_REQUEST['exam_edit']); // op_examination.id — numeric only
    $newOT->updateOPTreat();
    // $prescriptionJSON='';
} else {
    // echo 'else';exit;
    $wellnessJSON     = $newOT->insertOPTreat();
    $consultationJSON = $newOT->buildOPConsultationContext();
    $healthDocJSON    = $newOT->buildHealthDocumentContext();
}

$labTests = reqArr('lab_test');
if (count($labTests) > 0) {
    $newOT->insertOPInvest($labTests, $pid);
}

$radioTests = reqArr('radio_lab_test');
if (count($radioTests) > 0) {
    $newOT->insertOPRadioInvest($radioTests, $pid);
}



$newOT->setomedicine(reqArr('medicine'));
$newOT->setomedicineid(reqArr('medicine_id'));
$newOT->setdose(reqArr('dose'));
$newOT->setqty(reqArr('qty'));
$newOT->setinst(reqArr('inst'));
$newOT->setwhen(reqArr('food'));
$newOT->setfrequency(reqArr('frequency'));
$newOT->setduration(reqArr('days'));

$prescriptionJSON = $newOT->insertOPMedicDoc($pid);

$patientRecords = [];

if (count($consultationJSON['careContexts']) > 0) {
    $patientRecords[] = [
        "referenceNumber" => $consultationJSON['referenceNumber'],
        "display"         => $consultationJSON['display'],
        "careContexts"    => $consultationJSON['careContexts'],
        "hiType"          => "OPConsultation",
        "count"           => count($consultationJSON['careContexts'])
    ];
}
if (count($healthDocJSON['careContexts']) > 0) {
    $patientRecords[] = [
        "referenceNumber" => $healthDocJSON['referenceNumber'],
        "display"         => $healthDocJSON['display'],
        "careContexts"    => $healthDocJSON['careContexts'],
        "hiType"          => "HealthDocumentRecord",
        "count"           => count($healthDocJSON['careContexts'])
    ];
}
if (count($wellnessJSON['careContexts']) > 0) {
    $patientRecords[] = [
        "referenceNumber" => $wellnessJSON['referenceNumber'],
        "display"         => $wellnessJSON['display'],
        "careContexts"    => $wellnessJSON['careContexts'],
        "hiType"          => "WellnessRecord",
        "count"           => count($wellnessJSON['careContexts'])
    ];
}
// Add Prescription (carries its own Binary prescription document in-bundle,
// per the NRCES PrescriptionRecord profile — no separate document care context).
if (count($prescriptionJSON['careContexts']) > 0) {
    $patientRecords[] = [
        "referenceNumber" => $prescriptionJSON['referenceNumber'],
        "display"         => $prescriptionJSON['display'],
        "careContexts"    => $prescriptionJSON['careContexts'],
        "hiType"          => "Prescription",
        "count"           => count($prescriptionJSON['careContexts'])
    ];
}
if (count($immunization['careContexts']) > 0) {
    $patientRecords[] = [
        "referenceNumber" => $immunization['referenceNumber'],
        "display"         => $immunization['display'],
        "careContexts"    => $immunization['careContexts'],
        "hiType"          => "ImmunizationRecord",
        "count"           => count($immunization['careContexts'])
    ];
}
$newOT->linkAbhaCareContexts($pid, $patientRecords);
$pidUrl = rawurlencode((string) $pid); // neutralise reflected XSS in redirect
if (reqVal('sumbit') == 'print') {
    echo "<script>window.open('../print_opcasesheet.php?id=$pidUrl', '_blank')</script>";
}

echo "<script>window.location='../optreatment.php?id=$pidUrl'</script>";

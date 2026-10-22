<?php
// dbconnect.php opens the session (applying the hardened cookie params first),
// so include it BEFORE anything else. This file used to call session_start()
// itself on the line above the include, which started the session too early for
// session_set_cookie_params() to take effect.
include('dbconnect.php');
date_default_timezone_set('asia/kolkata');


class addOTTreat{ 
    
function setcomone($one)
{
    $this->one=$one;
}
function setdose($dose)
{
    $this->dose=$dose;
}
function setfood($food)
{
    $this->food=$food;
}
function setwhen($when)
{
    $this->when=$when;
}
function setfrequency($frequency)
{
    $this->frequency=$frequency;
}
function setduration($duration)
{
    $this->duration=$duration;
}

function setcomtwo($two)
{
    $this->two=$two;
}
function setcomthree($three)
{
    $this->three=$three;
}
function setcomfour($four)
{
    $this->four=$four;
}
function setnadi($nadi)
{
    $this->nadi=$nadi;
}
function setmala($mala)
{
    $this->mala=$mala;
}
function setmutra($mutra)
{
    $this->mutra=$mutra;
}
function setjiva($jiva)
{
    $this->jiva=$jiva;
}
function setsparsha($sparsha)
{
    $this->sparsha=$sparsha;
}
function setshabda($shabda)
{
    $this->shabda=$shabda;
}
function setdrik($drik)
{
    $this->drik=$drik;
}
function setakriti($akriti)
{
    $this->akriti=$akriti;
}
function setrs($rs)
{
    $this->rs=$rs;
}
function setlung($lung)
{
    $this->lung=$lung;
}
function setothers($others)
{
    $this->others=$others;
}



function setbmax($bmax)
{
    $this->bmax=$bmax;
}
function setpulse($pulse)
{
    $this->pulse=$pulse;
}


function setdiet($diet)
{
    $this->diet=$diet;
}
function setgeneral_exam($general_exam)
{
    $this->general_exam=$general_exam;
}
function setpatient_id($patient_id)
{
    $this->patient_id=$patient_id;
}



function setheart($heart)
{
    $this->heart=$heart;
}
function setpa($pa)
{
    $this->pa=$pa;
}
function setorganomegaly($organomegaly)
{
    $this->organomegaly=$organomegaly;
}
function setcns($cns)
{
    $this->cns=$cns;
}
function setpower($power)
{
    $this->power=$power;
}
function setreflexes($reflexes)
{
    $this->reflexes=$reflexes;
}
function settone($tone)
{
    $this->tone=$tone;
}
function setroga($roga)
{
    $this->roga=$roga;
}
function setdept($dept)
{
    $this->dept=$dept;
}
function setopd($opd)
{
    $this->opd=$opd;
}
function setomedicine($omedicine)
{
    $this->omedicine=$omedicine;
}
function setomedicineid($omedicineid)
{
    $this->omedicineid=$omedicineid;
}
// function setroga($roga)
// {
//     $this->roga=$roga;
// }
function setqty($qty)
{
    $this->qty=$qty;
}
function settotal1($total1)
{
    $this->total1=$total1;
}
function setpname1($pname1) 
        {
            $this->pname1 = $pname1;
        }
        function setdate1($date1) 
        {
            $this->date1 = $date1;
        }
         function setmedicine1($medicine1) 
        {
            $this->medicine1 = $medicine1;
        }
         function setquantity1($quantity1) 
        {
            $this->quantity1 = $quantity1;
        }
         function setprice1($price1) 
        {
            $this->price1 = $price1;
        }
 function setindiscount1($indiscount1) 
        {
            $this->indiscount1 = $indiscount1;
        }
        function setgrand1($grand1) 
        {
            $this->grand1 = $grand1;
        }
         function setbatch1($batch1) 
        {
            $this->batch1 = $batch1;
        }
function setmorning($morning)
{
    $this->morning=$morning;
}
function setafternoon($afternoon)
{
    $this->afternoon=$afternoon;
}
function setevening($evening)
{
    $this->evening=$evening;
}
function setnight($night)
{
    $this->night=$night;
}
function setanupana($anupana)
{
    $this->anupana=$anupana;
}
function setpid($pid)
{
    $this->pid=$pid;
}
function setpdate($pdate)
{
    $this->pdate=$pdate;
}

function setsubprocedure($subprocedure)
{
    $this->subprocedure=$subprocedure;
}

function setprocedure($procedure)
{
    $this->procedure=$procedure;
}


function setcomo($como)
{
    $this->como=$como;
}
function sethyper($hyper)
{
    $this->hyper=$hyper;
}
function setcomplaint($complaint)
{
    $this->complaint=$complaint;
}
function setassociated($associated)
{
    $this->associated=$associated;
}


function sethistory($history)
{
    $this->history=$history;
}
function setfamily_history($family_history)
{
    $this->family_history=$family_history;
}
function setwellness($wellness)
{
    $this->wellness = is_array($wellness) ? $wellness : [];
}
// Build the wellness column=value fragment for INSERT/UPDATE (escaped). Empty
// numeric/date fields are stored as NULL so they don't emit bogus observations.
// Wellness columns as placeholders + the values to bind. The column names are a
// fixed whitelist (never user input); only the values are parameterised. Nullable
// fields bind PHP null so an empty box stores NULL rather than ''.
function wellnessSql(mysqli $con = null): array
{
    $fields = ['smoking','alcohol','diet','steps','calories_burned','sleep_duration','lmp','menopause','menarche','fluid_intake','mental_status','overall_wellness'];
    $nullable = ['steps','calories_burned','sleep_duration','lmp','menarche','fluid_intake']; // '' -> NULL
    $w = is_array($this->wellness ?? null) ? $this->wellness : [];
    $cols = [];
    $vals = [];
    $set  = [];
    $params = [];
    foreach ($fields as $f) {
        $v = trim((string)($w[$f] ?? ''));
        $cols[] = "`$f`";
        $vals[] = '?';
        $set[]  = "`$f`=?";
        $params[] = ($v === '' && in_array($f, $nullable, true)) ? null : $v;
    }
    return [
        'cols'   => implode(', ', $cols),
        'vals'   => implode(', ', $vals),
        'set'    => implode(', ', $set),
        'params' => $params,
        'types'  => str_repeat('s', count($params)),
    ];
}

// Insert a lookup value into a master table if it isn't there yet (used for the
// free-text "tags" the selects allow). Prepared — the values are user input.
function ensureLookup(mysqli $con, string $table, string $col, string $value, bool $withStatus = false): void
{
    if ($value === '') { return; }
    $sel = $con->prepare("SELECT id FROM `$table` WHERE `$col` = ? LIMIT 1");
    $sel->bind_param('s', $value);
    $sel->execute();
    $found = $sel->get_result();
    $exists = $found && $found->num_rows > 0;
    $sel->close();
    if ($exists) { return; }
    $ins = $withStatus
        ? $con->prepare("INSERT INTO `$table` (`$col`,`status`) VALUES (?, 'active')")
        : $con->prepare("INSERT INTO `$table` (`$col`) VALUES (?)");
    $ins->bind_param('s', $value);
    $ins->execute();
    $ins->close();
}

function setbp($bp)
{
    $this->bp=$bp;
}

function setrbs($rbs)
{
    $this->rbs=$rbs;
}

function setpr($pr)
{
    $this->pr=$pr;
}

function setht($ht)
{
    $this->ht=$ht;
}


function setwaist($waist)
{
    $this->waist=$waist;
}

function settp($tp)
{
    $this->tp=$tp;
}

function setrr($rr)
{
    $this->rr=$rr;
}

function setspo2($spo2)
{
    $this->spo2=$spo2;
}
function setweight($weight)
{
    $this->weight=$weight;
}




function sethc($hc)
{
    $this->hc=$hc;
}

function setbmi($bmi)
{
    $this->bmi=$bmi;
}

function setexamination($examination)
{
    $this->examination=$examination;
}



function setdiagnosis($diagnosis)
{
    $this->diagnosis=$diagnosis;
}

function setlab_obs($lab_obs)
{
    $this->lab_obs=$lab_obs;
}
function setradio_obs($radio_obs)
{
    $this->radio_obs=$radio_obs;
}
function setmedical_advice($medical_advice)
{
    $this->medical_advice=$medical_advice;
}
function setadvice($advice)
{
    $this->advice=$advice;
}



function setnextdate($nextdate)
{
    $this->nextdate=$nextdate;
}

function setrefer_to($refer_to)
{
    $this->refer_to=$refer_to;
}
function setbmin($bmin)
{
    $this->bmin=$bmin;
}

function setinst($inst)
{
    $this->inst=$inst;
}


function setvacc_date($vacc_date)
{
    $this->vacc_date=$vacc_date;
}
function setimmunization($immunization)
{
    $this->immunization=$immunization;
}
function setmanufacturer($manufacturer)
{
    $this->manufacturer=$manufacturer;
}
function setlot_number($lot_number)
{
    $this->lot_number=$lot_number;
}
function setdose_number($dose_number)
{
    $this->dose_number=$dose_number;
}


    function getIcdCodes()
    {
        $newlogin = new userlogin;                  
        $con=$newlogin->dbconnect();
        $sql="SELECT * FROM `icd_codes` limit 100 ";
        $res=mysqli_query($con,$sql);
        return $res;
    }

    function getProce()
    {
        $newlogin = new userlogin;                  
        $con=$newlogin->dbconnect();
        $sql="SELECT * FROM `procedure` WHERE `status`='active'";
        $res=mysqli_query($con,$sql);
        return $res;
    }
    function getRoga()
    {
        $newlogin = new userlogin;                  
        $con=$newlogin->dbconnect();
        $sql="SELECT * FROM `roga` WHERE `status`='active'";
        $res=mysqli_query($con,$sql);
        return $res;
    }
    
    function getComorbidities()
    {
        $newlogin = new userlogin;                  
        $con=$newlogin->dbconnect();
        $sql="SELECT * FROM `comorbidities` WHERE `status`='active'";
        $res=mysqli_query($con,$sql);
        return $res;
    }
    
    function getHypersensitivity()
    {
        $newlogin = new userlogin;                  
        $con=$newlogin->dbconnect();
        $sql="SELECT * FROM `hypersensitivity` WHERE `status`='active'";
        $res=mysqli_query($con,$sql);
        return $res;
    }
    
    function getAdvice()
    {
        $newlogin = new userlogin;                  
        $con=$newlogin->dbconnect();
        $sql="SELECT * FROM `advice` WHERE `status`='active'";
        $res=mysqli_query($con,$sql);
        return $res;
    }
    
    function getAssociated_with()
    {
        $newlogin = new userlogin;                  
        $con=$newlogin->dbconnect();
        $sql="SELECT * FROM `associated_with` WHERE `status`='active'";
        $res=mysqli_query($con,$sql);
        return $res;
    }
    
    function selectDept()
    {
    $newlogin = new userlogin;                  
    $con=$newlogin->dbconnect();
    $sql="SELECT * FROM `department` WHERE `status`='active'";
    //   echo $sql;
    $res=mysqli_query($con,$sql);
    return $res;
    }
    function insertOPTreat()
    {
        $date=date("Y-m-d");
        $newlogin = new userlogin;                  
        $con=$newlogin->dbconnect();
        $como=implode(', ',$this->como);
        $comp=implode(', ',$this->complaint);
        $hyper=implode(', ',$this->hyper);
        $associated=implode(', ',$this->associated);
        $diagnosis=implode(', ',$this->diagnosis);
        $advice=implode(', ',$this->advice);
        // An unset follow-up used to become 1970-01-01: strtotime('') returns
        // false and date() reads that as timestamp 0. The stored epoch date then
        // looked like a real appointment to anything that did not special-case
        // it — the FHIR bundle published "Follow Up: 01-01-1970". Store the zero
        // date instead, which every reader already treats as "none".
        $nextTs   = strtotime((string) $this->nextdate);
        $nextdate = ($this->nextdate !== '' && $nextTs !== false && $nextTs > 0)
            ? date("Y-m-d", $nextTs)
            : '0000-00-00';
        
        foreach ($this->complaint as $complaint) {
            $this->ensureLookup($con, 'temp_complaints', 'complaints', trim((string) $complaint));
        }
        foreach ($this->hyper as $hyper_s) {
            $this->ensureLookup($con, 'hypersensitivity', 'hypersensitivity', trim((string) $hyper_s), true);
        }
    
        foreach ($this->associated as $associated_w) {
            $this->ensureLookup($con, 'associated_with', 'associated_with', trim((string) $associated_w), true);
        }
    
    
        foreach ($this->como as $comor) {
            $this->ensureLookup($con, 'comorbidities', 'comorbidities', trim((string) $comor), true);
        }
    
    
        foreach ($this->diagnosis as $diag) {
            $this->ensureLookup($con, 'icd_codes', 'details', trim((string) $diag));
        }
    
    
        foreach ($this->advice as $adv) {
            $this->ensureLookup($con, 'advice', 'advice', trim((string) $adv), true);
        }
        
        
        // $sql="INSERT INTO `op_examination` ( `patient_id`, `date`, `bp_min`, `bp_max`, `pulse`, `weight`, `complaints`, `diagnosis`, `next_visit_date`, `diet`, `general_examinition`,`rbs`) VALUES ('$this->patient_id', '$date', '$this->bmin', '$this->bmax', '$this->pulse', '$this->weight', '$comp', '$diagnosis', '$nextdate', '$this->diet', '$this->general_exam','$this->rbs');";
       
        $familyHistory = (string)($this->family_history ?? '');
        $wl = $this->wellnessSql();
        // Every value below is clinician-entered free text — all bound, never
        // interpolated. This also lets notes containing apostrophes save cleanly.
        $sql="INSERT INTO `op_examination` (`patient_id`, `date`, `comorbidities`, `hypersensitivity`, `complaints`, `associated_with`, `history`, `family_history`, `bp`, `rbs`, `pr`, `ht`, `waist`, `tp`, `rr`, `spo2`, `weight`, `hc`, `bmi`, `examination`, `diagnosis`, `lab_observation`, `radiology_observation`, `medical_advice`, `advice`, {$wl['cols']}, `next_visit_date`, `referred_to`) VALUES
                                           (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, {$wl['vals']}, ?, ?)";
        $patient_id = (int) $this->patient_id;
        $params = array_merge(
            [$patient_id, $date, $como, $hyper, $comp, $associated, (string) $this->history, $familyHistory,
             (string) $this->bp, (string) $this->rbs, (string) $this->pr, (string) $this->ht,
             (string) $this->waist, (string) $this->tp, (string) $this->rr, (string) $this->spo2,
             (string) $this->weight, (string) $this->hc, (string) $this->bmi, (string) $this->examination,
             $diagnosis, (string) $this->lab_obs, (string) $this->radio_obs,
             (string) $this->medical_advice, $advice],
            $wl['params'],
            [$nextdate, (string) $this->refer_to]
        );
        $types = 'i' . str_repeat('s', 24) . $wl['types'] . 'ss';
        $stmt = $con->prepare($sql);
        $stmt->bind_param($types, ...$params);
        $res = $stmt->execute();
        // Capture the op_examination row id BEFORE any further insert overwrites insert_id.
        // This is the actual stored visit id used as the care context reference.
        $examId = mysqli_insert_id($con);
        $this->examId = $examId;

               $date=date("Y-m-d H:i:s");

        $sql1 = $con->prepare("INSERT INTO `query`(`sql_query`, `created_by`, `created_at`) VALUES (?, ?, ?)");
        $sql1->bind_param("sss", $sql, $_SESSION['name'], $date);
        $sql1->execute();
$sql1->close();
$con->close();
// Care context reference = op_examination id, namespaced per HI type so the
// three records from one visit (Wellness/OPConsultation/case sheet) don't
// collide on the same reference (ABDM keys care contexts by this value).
$careGUID = 'WELL-' . $examId;


$display = "Vitals Wellness Record " . date("d.m.Y");

if (!empty($this->bp)) {
    $display .= ", BP- {$this->bp} mmHg";
}

if (!empty($this->rbs)) {
    $display .= ", RBS- {$this->rbs} mg/dL";
}

if (!empty($this->pr)) {
    $display .= ", Pulse Rate- {$this->pr} bpm";
}

if (!empty($this->ht)) {
    $display .= ", Height- {$this->ht} cm";
}

if (!empty($this->waist)) {
    $display .= ", Waist- {$this->waist} cm";
}

if (!empty($this->tp)) {
    $display .= ", Temp- {$this->tp} F";
}

if (!empty($this->rr)) {
    $display .= ", RR- {$this->rr} CPM";
}

if (!empty($this->spo2)) {
    $display .= ", SpO2- {$this->spo2}";
}

if (!empty($this->weight)) {
    $display .= ", Weight- {$this->weight} KGs";
}

if (!empty($this->hc)) {
    $display .= ", HC- {$this->hc} cm";
}

if (!empty($this->bmi)) {
    $display .= ", BMI- {$this->bmi}";
}

$wellnessRecord = [
        "referenceNumber" => "WELL/" . $this->patient_id,
        "display" => "Wellness Record - Patient ID: " . $this->patient_id,
        "careContexts" => [
            [
                
                
                
                "referenceNumber" => $careGUID,
                "display" => $display
            ]
        ],
        "hiType" => "WellnessRecord",
        "count" => 1
        ];

        return $wellnessRecord;
    }

    function buildOPConsultationContext()
    {
        $comp      = implode(', ', (array) $this->complaint);
        $diagnosis = implode(', ', (array) $this->diagnosis);

        $displayParts = ['OPD Consultation ' . date('d.m.Y')];
        if ($comp !== '')      $displayParts[] = 'Complaints: ' . $comp;
        if ($diagnosis !== '') $displayParts[] = 'Diagnosis: ' . $diagnosis;

        // Same visit row as the wellness record — namespaced so it doesn't
        // collide with the Wellness/case-sheet care context reference.
        $visitRef = 'OPC-' . (isset($this->examId) ? $this->examId : $this->patient_id);

        return [
            'referenceNumber' => 'OPC/' . $this->patient_id,
            'display'         => 'OPD Consultation for Patient ID: ' . $this->patient_id,
            'careContexts'    => [[
                'referenceNumber' => $visitRef,
                'display'         => implode('; ', $displayParts)
            ]],
            'hiType' => 'OPConsultation',
            'count'  => 1
        ];
    }

    function buildHealthDocumentContext()
    {
        // Same visit row as the wellness record — namespaced so it doesn't
        // collide with the Wellness/OPConsultation care context reference.
        $visitRef = 'HDOC-' . (isset($this->examId) ? $this->examId : $this->patient_id);

        return [
            'referenceNumber' => 'HDR-OPC/' . $this->patient_id,
            'display'         => 'OPD Case Sheet for Patient ID: ' . $this->patient_id,
            'careContexts'    => [[
                'referenceNumber' => $visitRef,
                'display'         => 'OPD Case Sheet ' . date('d.m.Y')
            ]],
            'hiType' => 'HealthDocumentRecord',
            'count'  => 1
        ];
    }

    function updateOPTreat()
    {
        $date=date("Y-m-d");
        $newlogin = new userlogin;                  
        $con=$newlogin->dbconnect();
        $como=implode(', ',$this->como);
        $comp=implode(', ',$this->complaint);
        $hyper=implode(', ',$this->hyper);
        $associated=implode(', ',$this->associated);
        $diagnosis=implode(', ',$this->diagnosis);
        $advice=implode(', ',$this->advice);
        // An unset follow-up used to become 1970-01-01: strtotime('') returns
        // false and date() reads that as timestamp 0. The stored epoch date then
        // looked like a real appointment to anything that did not special-case
        // it — the FHIR bundle published "Follow Up: 01-01-1970". Store the zero
        // date instead, which every reader already treats as "none".
        $nextTs   = strtotime((string) $this->nextdate);
        $nextdate = ($this->nextdate !== '' && $nextTs !== false && $nextTs > 0)
            ? date("Y-m-d", $nextTs)
            : '0000-00-00';
        
        foreach ($this->complaint as $complaint) {
            $this->ensureLookup($con, 'temp_complaints', 'complaints', trim((string) $complaint));
        }
        foreach ($this->hyper as $hyper_s) {
            $this->ensureLookup($con, 'hypersensitivity', 'hypersensitivity', trim((string) $hyper_s), true);
        }
    
        foreach ($this->associated as $associated_w) {
            $this->ensureLookup($con, 'associated_with', 'associated_with', trim((string) $associated_w), true);
        }
    
    
        foreach ($this->como as $comor) {
            $this->ensureLookup($con, 'comorbidities', 'comorbidities', trim((string) $comor), true);
        }
    
    
        foreach ($this->diagnosis as $diag) {
            $this->ensureLookup($con, 'icd_codes', 'details', trim((string) $diag));
        }
    
    
        foreach ($this->advice as $adv) {
            $this->ensureLookup($con, 'advice', 'advice', trim((string) $adv), true);
        }
       
        // $sql="UPDATE `op_examination` SET `bp_min`='$this->bmin',`bp_max`='$this->bmax',`pulse`='$this->pulse',`weight`='$this->weight',`complaints`='$comp',`diagnosis`='$diagnosis',`next_visit_date`='$nextdate',`diet`='$this->diet',`general_examinition`='$this->general_exam',`rbs`='$this->rbs' WHERE `id`='$this->opd'";
          $familyHistory = (string)($this->family_history ?? '');
          $wl = $this->wellnessSql();
          // All values bound — none interpolated.
          $sql="UPDATE `op_examination`
SET
    {$wl['set']},
    `comorbidities` = ?,
    `hypersensitivity` = ?,
    `complaints` = ?,
    `associated_with` = ?,
    `history` = ?,
    `family_history` = ?,
    `bp` = ?,
    `rbs` = ?,
    `pr` = ?,
    `ht` = ?,
    `waist` = ?,
    `tp` = ?,
    `rr` = ?,
    `spo2` = ?,
    `weight` = ?,
    `hc` = ?,
    `bmi` = ?,
    `examination` = ?,
    `diagnosis` = ?,
    `lab_observation` = ?,
    `radiology_observation` = ?,
    `medical_advice` = ?,
    `advice` = ?,
    `next_visit_date` = ?,
    `referred_to` = ?
WHERE
   `id`=?";
        $params = array_merge(
            $wl['params'],
            [$como, $hyper, $comp, $associated, (string) $this->history, $familyHistory,
             (string) $this->bp, (string) $this->rbs, (string) $this->pr, (string) $this->ht,
             (string) $this->waist, (string) $this->tp, (string) $this->rr, (string) $this->spo2,
             (string) $this->weight, (string) $this->hc, (string) $this->bmi, (string) $this->examination,
             $diagnosis, (string) $this->lab_obs, (string) $this->radio_obs,
             (string) $this->medical_advice, $advice, $nextdate, (string) $this->refer_to],
            [(int) $this->opd]
        );
        $types = $wl['types'] . str_repeat('s', 25) . 'i';
        $stmt = $con->prepare($sql);
        $stmt->bind_param($types, ...$params);
        $res = $stmt->execute();
        
               $date=date("Y-m-d H:i:s");
        
        $sql1 = $con->prepare("INSERT INTO `query`(`sql_query`, `created_by`, `created_at`) VALUES (?, ?, ?)");
        $sql1->bind_param("sss", $sql, $_SESSION['name'], $date);
        $sql1->execute();
$sql1->close();
$con->close();

        return $res;
    }

    function getLab()
    {
        $newlogin = new userlogin;                  
        $con=$newlogin->dbconnect();
     
        $sql="SELECT ln.`id` as testid,lg.`testname`,ln.`name` as test_name FROM `lab_test` lg,`lab_test_parameter` ln WHERE lg.`testid`=ln.`test_id` and ln.`status`='active' and testname_header not in ('USG','XRAY')  order by lg.`testid`,ln.`test_id`";
        //   echo $sql;
        $res=mysqli_query($con,$sql);
        return $res;
    }
    
    function getRadiology()
    {
        $newlogin = new userlogin;                  
        $con=$newlogin->dbconnect();
     
        $sql="SELECT ln.`id` as testid,lg.`testname`,ln.`name` as test_name FROM `lab_test` lg,`lab_test_parameter` ln WHERE lg.`testid`=ln.`test_id` and ln.`status`='active' and testname_header in ('USG','XRAY')  order by lg.`testid`,ln.`test_id`";
        //   echo $sql;exit;
        $res=mysqli_query($con,$sql);
        return $res;
    }
    
    
    /**
     * Record the tests ordered on this visit.
     *
     * HIMS writes these to `lab_report1`, a table this project does not have —
     * carrying that across meant prepare() returned false and nothing was ever
     * ordered, so the Lab and Radiology lists stayed permanently empty. Here an
     * order is a row in `op_lab` pointing at a `lab` row; $isRadio decides which
     * side of `lab.is_radio` a newly created test lands on.
     *
     * A numeric value is an existing lab.id. Anything else is a free-typed test
     * name, which is created in `lab` first and then ordered.
     */
    private function insertInvestigation($lab, $pid, $isRadio)
    {
        $newlogin = new userlogin;
        $con=$newlogin->dbconnect();
        $date=date("Y-m-d H:i:s");
        $res = false;

        $insOrder = $con->prepare("INSERT INTO `op_lab`(`test_id`, `patient_id`, `date`, `created_by`) VALUES (?,?,?,?)");
        $insTest  = $con->prepare("INSERT INTO `lab`(`name`, `is_radio`, `status`) VALUES (?,?,'active')");
        $insAudit = $con->prepare("INSERT INTO `query`(`sql_query`, `created_by`, `created_at`) VALUES (?, ?, ?)");
        if (!$insOrder || !$insTest) {
            return false;
        }

        $user   = isset($_SESSION['name']) ? $_SESSION['name'] : '';
        $pidInt = (int) $pid;
        $radio  = (int) $isRadio;

        foreach ((array) $lab as $value) {
            $value = trim((string) $value);
            if ($value === '') {
                continue;
            }

            if (is_numeric($value)) {
                $testId = (int) $value;
            } else {
                $insTest->bind_param('si', $value, $radio);
                if (!$insTest->execute()) {
                    continue;
                }
                $testId = mysqli_insert_id($con);
            }

            $insOrder->bind_param('iiss', $testId, $pidInt, $date, $user);
            $res = $insOrder->execute();

            if ($insAudit) {
                $audit = "INSERT INTO `op_lab`(`test_id`, `patient_id`) VALUES ('$testId','$pidInt')"; // audit text only
                $insAudit->bind_param("sss", $audit, $user, $date);
                $insAudit->execute();
            }
        }

        $con->close();
        return $res;
    }

    function insertOPInvest($lab,$pid)
    {
        return $this->insertInvestigation($lab, $pid, 0);
    }

    function insertOPRadioInvest($lab,$pid)
    {
        return $this->insertInvestigation($lab, $pid, 1);
    }
    
    
    // NOTE: this project's lab module has a different schema from HIMS's — no
    // `lab_report1`, and ordered tests live in `lab` + `op_lab` rather than
    // `lab_test_parameter`. This is this project's own query, kept as-is; taking
    // HIMS's version would query tables that do not exist here.
    function getPatientLab($id)
    {
        $newlogin = new userlogin;
        $con=$newlogin->dbconnect();

        // ONE ROW PER ORDERED TEST — the test's own name, never the individual
        // parameter names that make it up. Callers render `test_name`
        // (optreatment.php) or `name` (print_opcasesheet.php), so both aliases
        // carry the same test name. GROUP BY stops a test ordered twice from
        // listing twice.
        $stmt=$con->prepare("SELECT l.id, l.name, l.name AS test_name, MIN(ol.id) AS op_lab_id FROM `lab` l, op_lab ol WHERE l.id=ol.test_id AND ol.patient_id=? AND l.is_radio=0 GROUP BY l.id, l.name ORDER BY l.id");
        $stmt->bind_param('i',$id);
        $stmt->execute();
        return $stmt->get_result();
    }
    
    function getPatientLabRadio($id)
    {
        $newlogin = new userlogin;                  
        $con=$newlogin->dbconnect();
     
        // Radiology is the exact mirror of getPatientLab(): the tests ORDERED for
        // this patient, from `lab` + `op_lab`, filtered on is_radio=1.
        //
        // It used to read `labtest_group` + `lab_report` instead, which is the
        // per-PARAMETER results table — so the list showed one row per parameter
        // (and rendered the result value, not a name). Ordered radiology tests
        // live in op_lab exactly like lab tests do, so this returns ONE ROW PER
        // TEST with the test's own name.
        $stmt=$con->prepare("SELECT l.id, l.name, l.name AS test_name, MIN(ol.id) AS op_lab_id FROM `lab` l, op_lab ol WHERE l.id=ol.test_id AND ol.patient_id=? AND l.is_radio=1 GROUP BY l.id, l.name ORDER BY l.id");
        $stmt->bind_param('i',$id);
        $stmt->execute();
        return $stmt->get_result();
    }
    function getMedic()
    {
        $newlogin = new userlogin;                  
        $con=$newlogin->dbconnect();
        $sql="SELECT * from `medicine` where `status`='active'";
        // echo $sql;exit;
        $res=mysqli_query($con,$sql);
        return $res;
    }
     function getdoctMedic()
    {
        $newlogin = new userlogin;                  
        $con=$newlogin->dbconnect();
        $sql="SELECT * from `medicine` where `status`='active' order by name asc";
        // echo $sql;exit;
        $res=mysqli_query($con,$sql);
        return $res;
    }
     function getPatient($id)
    {
        $newlogin = new userlogin;                  
        $con=$newlogin->dbconnect();
        $stmt=$con->prepare("SELECT c.*,d.designation,d.qualification,concat(d.name,' ',d.last_name) as doc_name,d.email as doc_mail,d.unit from `central_opd` c,doctor d where c.`id`=? and c.doctor=d.id");
        $stmt->bind_param('i',$id);
        $stmt->execute();
        return $stmt->get_result();
    }
    function getPatientByOpdNo($opd_no)
    {
        $newlogin = new userlogin;
        $con = $newlogin->dbconnect();
        $opd_no = trim((string)$opd_no);
        // Accept "123", "OP123" or the raw central_opd.id. Build the candidate list
        // in PHP, then match it with a fixed IN(?,?,?) — no value reaches the SQL.
        $candidates = [$opd_no];
        if (preg_match('/^\d+$/', $opd_no)) {
            $candidates[] = 'OP' . $opd_no;
        }
        if (preg_match('/^OP\d+$/i', $opd_no)) {
            $candidates[] = substr($opd_no, 2);
        }
        $candidates = array_values(array_unique($candidates));
        $in = implode(',', array_fill(0, count($candidates), '?'));
        $sql = "SELECT c.*,d.designation,d.qualification,concat(d.name,' ',d.last_name) as doctor,d.email as doc_mail,d.unit from `central_opd` c,doctor d where (c.opd_no IN ($in) or c.id=?) and c.doctor=d.id";
        $params = $candidates;
        $params[] = (int) $opd_no;
        $types = str_repeat('s', count($candidates)) . 'i';
        $stmt = $con->prepare($sql);
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        return $stmt->get_result();
    }
     function getipPatient($id)
    {
        $newlogin = new userlogin;                  
        $con=$newlogin->dbconnect();
        $stmt=$con->prepare("SELECT c.* from `central_ipd` c where c.`ipd_no`=?");
        $stmt->bind_param('s',$id);
        $stmt->execute();
        return $stmt->get_result();
    }
     function getPatientbyabha($id)
    {
        $newlogin = new userlogin;                  
        $con=$newlogin->dbconnect();
        $stmt=$con->prepare("SELECT c.*,d.designation,d.qualification,concat(d.name,' ',d.last_name) as doc_name,d.email as doc_mail,d.unit from `central_opd` c,doctor d where (c.`abha_number`=? or c.`abha_address`=?) and c.doctor=d.id order by c.id desc limit 1");
        $stmt->bind_param('ss',$id,$id);
        $stmt->execute();
        return $stmt->get_result();
    }
    function patientoldDetials($opd,$date)
    {
        $newlogin = new userlogin;                  
        $con=$newlogin->dbconnect();
        $stmt=$con->prepare("SELECT m.*,me.name from  op_medicine m,medicine me where me.id=m.medicine and m.patient_id=(select id from central_opd where opd_no=? and date(date)<date(?) order by id desc limit 1)");
        $stmt->bind_param('ss',$opd,$date);
        $stmt->execute();
        return $stmt->get_result();
    }
    
    function getOPExamination($id)
    {
        $newlogin = new userlogin;                  
        $con=$newlogin->dbconnect();
        $stmt=$con->prepare("SELECT * from `op_examination` where `patient_id`=?");
        $stmt->bind_param('i',$id);
        $stmt->execute();
        return $stmt->get_result();
    }
    function insertOPMedic($id)
    {
        $date=date("Y-m-d"); 
        $newlogin = new userlogin;                  
        $con=$newlogin->dbconnect();
        $omedicine=$this->omedicine;
        $omedicineid=$this->omedicineid;
        $dose=$this->dose;
        $qty=$this->qty;
        // See insertOPMedicDoc(): the controller maps the form's "food" select to
        // setwhen(), so setfood() is never called and this must not be read raw.
        $food=$this->food ?? [];
        $when=$this->when;
        $frequency=$this->frequency;
        $duration=$this->duration;
        $res = false;
        $ins=$con->prepare("INSERT INTO `op_medicine`(`patient_id`, `date`, `medicine`, `dosage`, `qty`,`food`,`when`,`frequency`,`days`) VALUES (?,?,?,?,?,?,?,?,?)");
        $pidInt=(int)$id;
        for($i=0;$i<count($omedicine);$i++)
        {
            if($omedicineid[$i] > 0)
            {
                $vMed =(string)$omedicine[$i]; $vDose=(string)$dose[$i];
                $vQty =(string)$qty[$i];       $vFood=(string)$food[$i];
                $vWhen=(string)$when[$i];      $vFreq=(string)$frequency[$i];
                $vDur =(string)$duration[$i];
                $ins->bind_param('issssssss',$pidInt,$date,$vMed,$vDose,$vQty,$vFood,$vWhen,$vFreq,$vDur);
                 $res=$ins->execute();
            }

        }

        return $res;
    }
    
    
    
    function insertOPMedicDoc($id)
    {
        $date=date("Y-m-d"); 
        $newlogin = new userlogin;                  
        $con=$newlogin->dbconnect();
        $omedicine=$this->omedicine;
         $omedicineid=$this->omedicineid;
        $dose=$this->dose;
        $qty=$this->qty;
        // setfood() exists but the controller never calls it: the form's "food"
        // select is mapped to setwhen(), so op_medicine.`when` carries the
        // Before/After Food value and `food` is left blank. Reading $this->food
        // unguarded therefore raised a notice on every prescription save. Default
        // to an empty list — same stored result, without the notice.
        $food=$this->food ?? [];
        $when=$this->when;
        $frequency=$this->frequency;
        $duration=$this->duration;
        $inst=$this->inst;
        // Initialised here, not only inside the row loop: a consultation saved
        // with no prescription never enters that loop, and the foreach below then
        // ran on an undefined variable (a PHP warning printed straight into the
        // response). An empty list is the correct answer for "no medicines".
        $prescriptionItems = [];
        // Prescription rows are all request-supplied — bound, never interpolated.
        // Statements are prepared once and re-bound per row.
        $pidInt   = (int) $id;
        $insExist = $con->prepare("INSERT INTO `op_medicine`(`patient_id`, `date`, `medicine`, `dosage`, `qty`,`food`,`when`,`frequency`,`days`,`date_time`,`instruction`) VALUES (?,?,?,?,?,?,?,?,?,NOW(),?)");
        // NOTE: HIMS spells this column `timimg` — a typo that shipped in ITS
        // schema, which its copy of these statements had to match. This project's
        // `medicine` table has the correctly spelled `timing`, so the HIMS
        // spelling must NOT be carried across: prepare() would return false and
        // the next bind_param() call fatals.
        $updMed   = $con->prepare("UPDATE medicine set `dosage`=?, `qty`=?,`timing`=?,`duration`=?,`frequency`=? where id=?");
        $insMed   = $con->prepare("INSERT INTO medicine(`name`,`qty`,`dosage`,`timing`,`frequency`,`duration`,`status`) value(?,?,?,?,?,?,'Active')");
        $insNew   = $con->prepare("INSERT INTO `op_medicine`(`patient_id`, `date`, `medicine`, `dosage`, `qty`,`food`,`when`,`frequency`,`days`,`date_time`) VALUES (?,?,?,?,?,?,?,?,?,NOW())");
        for($i=0;$i<count($omedicine);$i++)
        {
            $vMedName = (string) $omedicine[$i];
            $vMedId   = (string) $omedicineid[$i];
            $vDose    = (string) $dose[$i];
            $vQty     = (string) $qty[$i];
            $vFood    = (string) $food[$i];
            $vWhen    = (string) $when[$i];
            $vFreq    = (string) $frequency[$i];
            $vDur     = (string) $duration[$i];
            $vInst    = (string) ($inst[$i] ?? '');
            if($omedicineid[$i] > 0)
            {
                $insExist->bind_param('isssssssss', $pidInt, $date, $vMedId, $vDose, $vQty, $vFood, $vWhen, $vFreq, $vDur, $vInst);
                $res = $insExist->execute();
                $opMedId = mysqli_insert_id($con);
                $medIdInt = (int) $omedicineid[$i];
                $updMed->bind_param('sssssi', $vDose, $vQty, $vWhen, $vDur, $vFreq, $medIdInt);
                $updMed->execute();
                $prescriptionItems[] = ['id' => $opMedId, 'display' => date("d.m.y").", ".$omedicine[$i] . ", " . $dose[$i] . ", " . $frequency[$i]];
            }
               elseif(strlen($omedicine[$i])>1){
                $insMed->bind_param('ssssss', $vMedName, $vQty, $vDose, $vFood, $vFreq, $vDur);
                $insMed->execute();
                $med_id=mysqli_insert_id($con);
                $insNew->bind_param('isissssss', $pidInt, $date, $med_id, $vDose, $vQty, $vFood, $vWhen, $vFreq, $vDur);
                $res = $insNew->execute();
                 $opMedId = mysqli_insert_id($con);
                 $prescriptionItems[] = ['id' => $opMedId, 'display' => date("d.m.Y").", ".$omedicine[$i] . ", " . $dose[$i] . ", " . $frequency[$i]];
            }

        }
        $careContexts = [];
    foreach ($prescriptionItems as $item)
    {
        // referenceNumber = op_medicine row id (the stored prescription line)
        $careContexts[] = [
            "referenceNumber" => (string) $item['id'],
            "display"         => $item['display']
        ];
    }
     $jsonData = [
        "referenceNumber" => "PRES/" . $id,
        "display" => "Prescription for Patient ID: " . $id,
        "careContexts" => $careContexts,
        "hiType" => "Prescription",
        "count" => count($careContexts)
    ];
        return $jsonData;
    }
    function getPatienMedic($id)
    {
        $newlogin = new userlogin;                  
        $con=$newlogin->dbconnect();
        $stmt=$con->prepare("SELECT om.*,m.`name` from `op_medicine` om,`medicine` m where m.`id`=om.`medicine` and `patient_id`=?");
        $stmt->bind_param('i',$id);
        $stmt->execute();
        return $stmt->get_result();
    }
    
    function getPatienMedicDoc($id)
    {
        $newlogin = new userlogin;                  
        $con=$newlogin->dbconnect();
        $stmt=$con->prepare("SELECT om.*,m.`name` from `op_medicine` om,`medicine` m where m.`id`=om.`medicine` and `patient_id`=?");
        $stmt->bind_param('i',$id);
        $stmt->execute();
        return $stmt->get_result();
    }
    function getExamData($id)
    {
        $newlogin = new userlogin;                  
        $con=$newlogin->dbconnect();
        $stmt=$con->prepare("SELECT * from `op_examination` WHERE `patient_id`=?");
        $stmt->bind_param('i',$id);
        $stmt->execute();
        return $stmt->get_result();
    }
    function insertProceMedic($id)
    {
        $newlogin = new userlogin;                  
        $con=$newlogin->dbconnect();
        $sql="INSERT INTO `op_treatment`( `patient_id`, `date`, `procedure`, `sub_procedure`, `medicine`, `days`, `qty`, `total`, `morning`, `afternoon`, `evening`, `night`, `observation`)
        VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)";
        $stmt=$con->prepare($sql);
        $pIdInt=(int)$id;
        $vals=[$this->pdate,$this->procedure,$this->subprocedure,$this->omedicine,$this->roga,
               $this->qty,$this->total1,$this->morning,$this->afternoon,$this->evening,
               $this->night,$this->anupana];
        $vals=array_map(function($v){ return (string)(is_array($v)?implode(', ',$v):$v); },$vals);
        $stmt->bind_param('i'.str_repeat('s',12),$pIdInt,...$vals);
        $res=$stmt->execute();
        $date=date("Y-m-d H:i:s");
           $sql1 = $con->prepare("INSERT INTO `query`(`sql_query`, `created_by`, `created_at`) VALUES (?, ?, ?)");
        $sql1->bind_param("sss", $sql, $_SESSION['name'], $date);
        $sql1->execute();
$sql1->close();
$con->close();
        return $res;
    }
    function getProceMedic($id)
    {
        $newlogin = new userlogin;                  
        $con=$newlogin->dbconnect();
        $stmt=$con->prepare("SELECT ot.*,p.procedure_name,sp.subprocedure_name,m.medicine as proce_medic from `procedure` p,`procedure_medicine` m,`op_treatment` ot left join sub_procedure sp on ot.sub_procedure=sp.id WHERE `patient_id`=? and p.id=ot.procedure and m.id=ot.medicine");
        $stmt->bind_param('i',$id);
        $stmt->execute();
        return $stmt->get_result();
    
    }
    
      function insertOPMedic1($id)
    {
        $date=date("Y-m-d");
        $newlogin = new userlogin;                  
        $con=$newlogin->dbconnect();
        $sql="INSERT INTO `op_medicine`(`patient_id`, `date`, `medicine`, `qty`,`price`,`total`,`discount`,`grand_total`) VALUES (?,?,?,?,?,?,?,?)";

        // Read the batch's current stock, then decrement it — both bound.
        $sel=$con->prepare("select current_qty from medicine_purchase where medicine_id=? and batch_id=?");
        $sel->bind_param('ss',$this->medicine1,$this->batch1);
        $sel->execute();
        $result2=$sel->get_result();
        $row=$result2?mysqli_fetch_assoc($result2):null;
        $qty=($row['current_qty'] ?? 0) - $this->quantity1;
        $sel->close();

        $upd=$con->prepare("update medicine_purchase set current_qty=? where medicine_id=? and batch_id=?");
        $upd->bind_param('sss',$qty,$this->medicine1,$this->batch1);
        $res1=$upd->execute();
        $upd->close();

        $ins=$con->prepare($sql);
        $pIdInt=(int)$id;
        $ins->bind_param('isssssss',$pIdInt,$date,$this->medicine1,$this->quantity1,
                         $this->price1,$this->total1,$this->indiscount1,$this->grand1);
        $res=$ins->execute();
        
             $date=date("Y-m-d H:i:s");
           $sql1 = $con->prepare("INSERT INTO `query`(`sql_query`, `created_by`, `created_at`) VALUES (?, ?, ?)");
        $sql1->bind_param("sss", $sql, $_SESSION['name'], $date);
        $sql1->execute();
$sql1->close();
$con->close();
        return $res;
    }
    function updateTablet($qty,$medicine_id,$price,$totl,$pid)
     {
          $newlogin = new userlogin;
        $con=$newlogin->dbconnect();
        $sql="UPDATE `op_medicine` set `qty`=?,`price`=?,`total`=? WHERE `medicine`=? and `patient_id`=?";
         $stmt=$con->prepare($sql);
         $pidInt=(int)$pid;
         $stmt->bind_param('ssssi',$qty,$price,$totl,$medicine_id,$pidInt);
         $result=$stmt->execute();
              $date=date("Y-m-d H:i:s");
           $sql1 = $con->prepare("INSERT INTO `query`(`sql_query`, `created_by`, `created_at`) VALUES (?, ?, ?)");
        $sql1->bind_param("sss", $sql, $_SESSION['name'], $date);
        $sql1->execute();
$sql1->close();
$con->close();
        return $result;
         
     }
      function updateDiscOP($id)
    {
        $newlogin = new userlogin;
        $con=$newlogin->dbconnect();
        $sql="UPDATE `op_medicine` SET `discount`=? WHERE `patient_id`=? and discount=0 limit 1";
        $stmt=$con->prepare($sql);
        $pidInt=(int)$id;
        $stmt->bind_param('si',$this->indiscount1,$pidInt);
        $result=$stmt->execute();
             $date=date("Y-m-d H:i:s");
           $sql1 = $con->prepare("INSERT INTO `query`(`sql_query`, `created_by`, `created_at`) VALUES (?, ?, ?)");
        $sql1->bind_param("sss", $sql, $_SESSION['name'], $date);
        $sql1->execute();
$sql1->close();
$con->close();
        return $result;
    }
     function getPatientname($id)
    {
        $newlogin = new userlogin;                  
          $con=$newlogin->dbconnect();
          $stmt=$con->prepare("SELECT op.date,op.opd_no, op.name,op.opd_no,d.dept_name,op.doctor,op.id,op.address,CONCAT(u.name,' ',u.last_name) as doctor FROM central_opd op,department d,user u WHERE d.id=op.dept_id and op.doctor=u.id and op.id=?");
          $idInt=(int)$id;
          $stmt->bind_param('i',$idInt);
          $stmt->execute();
          return $stmt->get_result();
    }
     function getPharmaBill($id)
    {
        $newlogin = new userlogin;                  
      $con=$newlogin->dbconnect();
      $stmt=$con->prepare("SELECT opm.*,op.*,d.dept_name,CONCAT(u.name,' ',u.last_name) as doctor,m.name as mname,opm.price,me.exp_date as expdate,mu.unit_name FROM medicine m,central_opd op, op_medicine opm,department d,user u,medicine_purchase me,medicine_units mu WHERE opm.patient_id=op.id and d.id=op.dept_id and op.doctor=u.id and me.medicine_id=opm.medicine and  m.id=me.medicine_id and op.id=?  GROUP by opm.total,opm.date");
      $idInt=(int)$id;
      $stmt->bind_param('i',$idInt);
      $stmt->execute();
      return $stmt->get_result();
    }
    
    function getComplaints()
    {
        $newlogin = new userlogin;                  
      $con=$newlogin->dbconnect();
      $sql="select * from temp_complaints";
      $res=mysqli_query($con,$sql);
      return $res;
    }
    
    function getPatientOPMedic($id)
    {
      $newlogin = new userlogin;                  
      $con=$newlogin->dbconnect();
      $stmt=$con->prepare("select om.date,m.name,om.frequency,om.when,om.qty,om.dosage from op_medicine om,medicine m where om.patient_id=? and m.id=om.medicine");
      $idInt=(int)$id;
      $stmt->bind_param('i',$idInt);
      $stmt->execute();
      return $stmt->get_result();
    }
    function getPatientHistory($opd,$date)
    {
        $newlogin = new userlogin;                  
      $con=$newlogin->dbconnect();
      $date=date("Y-m-d",strtotime($date));
      // op_examination no longer has bp_min/bp_max/pulse — BP is one `bp` field
      // ("120/80") and pulse is `pr`. Alias them back to the names optreatment1.php
      // reads, so this query runs instead of silently returning false.
      $stmt=$con->prepare("select c.id,c.date,CONCAT(u.name,' ',u.last_name) as doct_name,SUBSTRING_INDEX(ex.`bp`,'/',1) as bp_min,SUBSTRING_INDEX(ex.`bp`,'/',-1) as bp_max,ex.`pr` as pulse,ex.`rbs`,ex.`weight`,ex.`complaints`,ex.`diagnosis`,ex.`next_visit_date` from user u,central_opd c LEFT JOIN `op_examination` ex on ex.patient_id=c.id where c.opd_no=? and u.id=c.doctor and date(c.date) < ? order by date(c.date) desc");
      $stmt->bind_param('ss',$opd,$date);
      $stmt->execute();
      return $stmt->get_result();
    }
    function getDoctor()
    {
      $newlogin = new userlogin;                  
      $con=$newlogin->dbconnect();
      $day=date("l");
      if($_SESSION['role']=='Admin')
      {
          $admin='';
      }
      else{
          $admin=" and st.`day`='$day'";
      }
      
      $sql="SELECT u.* FROM `doctor` u WHERE role='Doctor' and status='active' group by u.id";
      $res=mysqli_query($con,$sql);
      return $res;
    }
    
    /**
     * Clean one immunization free-text field.
     *
     * These are clinical identifiers, not prose: a manufacturer, a lot/batch number
     * and a dose label. Anything outside the allowed characters is punctuation
     * noise (a fuzzed "!@#$%^" or a mis-keyed field), so strip it, collapse the
     * whitespace and cap the length to the column width. A value left with no
     * letter or digit carried no information and is stored empty rather than as
     * a row of symbols.
     */
    private static function cleanImmuField(string $value, string $allowed, int $max): string
    {
        $v = preg_replace('/[^' . $allowed . ']+/u', ' ', $value);
        $v = trim(preg_replace('/\s+/u', ' ', (string) $v));
        // Separators left dangling once the noise is gone ("5432 &") carry nothing.
        // A trailing '.' is kept — it belongs to abbreviations like "Pvt. Ltd."
        $v = preg_replace('/^[^A-Za-z0-9(]+/u', '', $v);
        $v = preg_replace('/[,&\/\- ]+$/u', '', (string) $v);
        if ($v === '' || !preg_match('/[A-Za-z0-9]/', $v)) {
            return '';
        }
        return function_exists('mb_substr') ? mb_substr($v, 0, $max) : substr($v, 0, $max);
    }

    /** A next-vaccination date is only meaningful as a real, future-or-today date. */
    private static function cleanNextDate($value): ?string
    {
        $v = trim((string) $value);
        if ($v === '' || strpos($v, '0000-00-00') === 0) {
            return null;
        }
        $d = DateTime::createFromFormat('Y-m-d', substr($v, 0, 10));
        if (!$d || $d->format('Y-m-d') !== substr($v, 0, 10)) {
            return null; // not a real calendar date
        }
        return $d->format('Y-m-d');
    }

    function insertImmunization($id)
    {
        $newlogin = new userlogin;
      $con=$newlogin->dbconnect();
      $immunization=$this->immunization;
      $manufacturers = (array) ($this->manufacturer ?? []);
      $lotNumbers    = (array) ($this->lot_number ?? []);
      $doseNumbers   = (array) ($this->dose_number ?? []);
      $date=date("d.m.Y");
      $careContexts = [];
      // Every value here is request-supplied (pid, immunization[], manufacturer[],
      // lot_number[], dose_number[], vacc_date) — bind them all.
      $pidInt = (int) $id;
      // Blank / malformed dates must land as NULL, never as the 0000-00-00 that a
      // DATE column silently accepts for an empty string.
      $vaccDate = self::cleanNextDate($this->vacc_date);
      $ins = $con->prepare("INSERT INTO `immunization`( `patient_id`, `immunization_name`, `manufacturer`, `lot_number`, `dose_number`, `next_date`, `created_at`) VALUES (?,?,?,?,?,?,now())");
      for($i=0;$i<count($immunization);$i++)
      {
          $vaccine = self::cleanImmuField((string) $immunization[$i], 'A-Za-z0-9 ._()\/\-', 255);
          if ($vaccine === '') {
              continue; // no vaccine named — nothing to record
          }
          $manufacturer = self::cleanImmuField((string) ($manufacturers[$i] ?? ''), 'A-Za-z0-9 .,&()\/\-', 150);
          $lotNumber    = self::cleanImmuField((string) ($lotNumbers[$i] ?? ''), 'A-Za-z0-9 _\/\-', 100);
          $doseNumber   = self::cleanImmuField((string) ($doseNumbers[$i] ?? ''), 'A-Za-z0-9 \/\-', 50);
          $ins->bind_param('isssss', $pidInt, $vaccine, $manufacturer, $lotNumber, $doseNumber, $vaccDate);
          $ins->execute();
          $immuId = mysqli_insert_id($con);
          // referenceNumber = immunization row id (the stored vaccination record).
          // The display uses the CLEANED values — it is shown in the patient's PHR
          // app, so it must not carry anything the row itself rejected.
          $careContexts[] = [
              "referenceNumber" => (string) $immuId,
              "display"         => "$date, $vaccine" . ($doseNumber !== '' ? " (Dose $doseNumber)" : '') .
                                   ($vaccDate !== null ? ", Next date: $vaccDate" : '')
          ];
      }
      $id=$this->patient_id;
     $jsonData = [
        "referenceNumber" => "IMMUNI/" . $id,
        "display" => "Immunization for Patient ID: " . $id,
        "careContexts" => $careContexts,
        "hiType" => "ImmunizationRecord",
        "count" => count($careContexts)
    ];
        return $jsonData;
     
    }
    
    function getPatientImmunization($id)
    {
         $newlogin = new userlogin;                  
      $con=$newlogin->dbconnect();
      $stmt=$con->prepare("SELECT * FROM immunization where patient_id=?");
      $stmt->bind_param('i',$id);
      $stmt->execute();
      return $stmt->get_result();
    }
    /**
     * Push care-context groups to ABDM and record what was linked.
     *
     * $marks says which rows to flag on success, as
     *   [['table' => ..., 'col' => ..., 'val' => ...], ...]
     * Before this existed the only write-back was central_opd, so an
     * invoice, discharge summary or diagnostic report could never be
     * recorded as linked and would be re-pushed on every attempt.
     *
     * The skip check is per mark, not per patient: a patient whose OP
     * consultation was linked last year must still be able to link a bill
     * raised today. The old per-patient guard is preserved for the OPD
     * caller because its mark IS the patient row.
     */
    function linkAbhaContextGroups($opd, $patient1, array $marks = [])
    {
        // Kill switch: ABDM_AUTOLINK=off in .env. Guarded here rather than at the
        // call site so every caller is covered. Returns false like the other
        // early exits, and callers already ignore the return value.
        // The config is required explicitly — the clinical save paths do NOT load
        // abdm_config.php, so a bare function_exists() check would find nothing
        // and let auto-linking run on regardless of the flag.
        require_once __DIR__ . '/../config/abdm_config.php';
        if (!abdmAutoLinkEnabled()) {
            return false;
        }

        // Diagnostic log — every save records why the ABHA auto-link did or did
        // not fire (silent failures were leaving abha_linked=0 with no trace).
        $lg = function ($m) use ($opd) {
            @file_put_contents(__DIR__ . '/../api/abdm_link.log',
                '[' . date('Y-m-d H:i:s') . "] opd=$opd " . $m . "\n", FILE_APPEND);
        };

        if (empty($patient1) || !is_array($patient1)) {
            $lg('SKIP: no care contexts to link (empty records — typically an edit re-save that did not rebuild OPConsultation/Wellness contexts)');
            return false;
        }

        // ABDM rejects the whole request with "Invalid display" if any label is
        // empty, over-long or oddly punctuated. buildOPConsultationContext()
        // implodes complaints+diagnosis, which is the empty string when a visit
        // records neither.
        $patient1 = abdmSanitizeCareContextEntries($patient1);

        $newlogin = new userlogin;
        $con = $newlogin->dbconnect();
        // $opd is the request-supplied pid — bind it.
        $stmt = $con->prepare("SELECT * FROM central_opd WHERE id=? LIMIT 1");
        $opdInt = (int) $opd;
        $stmt->bind_param('i', $opdInt);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result ? mysqli_fetch_assoc($result) : null;

        // Skip silently if patient has no ABHA linked
        if (!$row || empty($row['abha_number']) || empty($row['abha_address'])) {
            $lg('SKIP: patient row missing ABHA number/address');
            mysqli_close($con);
            return false;
        }


        // Default the write-back target to the patient row, which is what the
        // OP-consultation caller has always used.
        if (!$marks) {
            $marks = [['table' => 'central_opd', 'col' => 'id', 'val' => $opdInt]];
        }

        // Skip only when every context in this push is already recorded as
        // linked. Checking per mark is what lets a bill raised today link for a
        // patient whose OP consultation was linked months ago.
        $pending = [];
        foreach ($marks as $mk) {
            $mTbl = preg_replace('/[^A-Za-z0-9_]/', '', (string) ($mk['table'] ?? ''));
            $mCol = preg_replace('/[^A-Za-z0-9_]/', '', (string) ($mk['col'] ?? ''));
            $mVal = (string) ($mk['val'] ?? '');
            if ($mTbl === '' || $mCol === '' || $mVal === '') {
                continue;
            }
            $mValEsc = mysqli_real_escape_string($con, $mVal);
            $q = @mysqli_query($con, "SELECT `abha_linked` FROM `$mTbl` WHERE `$mCol`='$mValEsc' LIMIT 1");
            if ($q === false) {
                // The column does not exist on this table yet. Say so rather
                // than pushing blind and losing the fact that it happened.
                $lg("SKIP: $mTbl.abha_linked unavailable (" . mysqli_error($con) . ')');
                mysqli_close($con);
                return false;
            }
            $mRow = mysqli_fetch_assoc($q);
            if (!$mRow || empty($mRow['abha_linked'])) {
                $pending[] = ['table' => $mTbl, 'col' => $mCol, 'val' => $mVal];
            }
        }

        if (!$pending) {
            $lg('SKIP: every context in this push is already abha_linked=1');
            mysqli_close($con);
            return false;
        }
        $marks = $pending;

        $lg('START: ' . count($patient1) . ' hiType group(s)');

        $clientid     = $_SESSION['clientid'];
        $clientSecret = $_SESSION['clientSecret'];
        $base_url     = $_SESSION['base_url'];
        $hipId        = $_SESSION['hipId'];
        $x_cm_id      = $_SESSION['x_cm_id'];
        $apiKey       = $_SESSION['abha_api'];

        $abha_number = str_replace("-", "", $row['abha_number']);
        $abha_address = $row['abha_address'];
        $name         = $row['name'];
        $dob          = date("Y", strtotime($row['dob']));
        $gender       = $row['gender'];
        $sexCode      = ($gender == 'Male') ? 'M' : (($gender == 'Female') ? 'F' : 'O');

        // Step 1: Get ABDM access token
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL            => "$base_url/api/milestone2/abdm-gateway/session",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_CUSTOMREQUEST  => 'POST',
            CURLOPT_POSTFIELDS     => json_encode([
                "grantType"    => "client_credentials",
                "clientId"     => $clientid,
                "clientSecret" => $clientSecret,
            ]),
            CURLOPT_HTTPHEADER => array(
                'X-API-Key: ' . $apiKey,
                'Content-Type: application/json',
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);

        $data        = json_decode($response, true);
        $accessToken = $data['data']['accessToken'] ?? null;

        if (!$accessToken) {
            $lg('FAIL: no gateway access token. httpResp=' . substr((string)$response, 0, 300));
            mysqli_close($con);
            return false;
        }

        // ABDM rejects any care-context (or group) display beyond ~100 chars with
        // "ABDM-9999: Invalid display". The Wellness record concatenates every
        // vital and easily exceeds 200 chars, so cap every display defensively —
        // cutting on the last comma boundary keeps it clean (ends on a full vital).
        $capDisplay = function ($s) {
            $s = trim(preg_replace('/\s+/', ' ', (string) $s));
            $len = function_exists('mb_strlen') ? mb_strlen($s) : strlen($s);
            if ($len <= 100) {
                return $s;
            }
            $sub   = function_exists('mb_substr') ? mb_substr($s, 0, 100) : substr($s, 0, 100);
            $comma = strrpos($sub, ',');
            if ($comma !== false && $comma > 40) {
                $sub = substr($sub, 0, $comma);
            }
            return rtrim($sub, " ,;-");
        };
        foreach ($patient1 as &$grp) {
            if (isset($grp['display'])) {
                $grp['display'] = $capDisplay($grp['display']);
            }
            if (!empty($grp['careContexts']) && is_array($grp['careContexts'])) {
                foreach ($grp['careContexts'] as &$cc) {
                    if (isset($cc['display'])) {
                        $cc['display'] = $capDisplay($cc['display']);
                    }
                }
                unset($cc);
            }
        }
        unset($grp);

        // Step 2: Initiate the link in ONE call. We send the care-contexts to the
        // Node gateway, which stores them (keyed by REQUEST-ID) and AUTO-LINKS them
        // when ABDM's link-token webhook arrives — no polling, no separate
        // care-contexts/link call from PHP.
        $requestId = $this->generateUuidV4();
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL            => "$base_url/api/milestone2/hip-link/token/generate",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => 40,
            CURLOPT_CUSTOMREQUEST  => 'POST',
            CURLOPT_POSTFIELDS     => json_encode([
                "abhaNumber"  => $abha_number,
                "abhaAddress" => $abha_address,
                "name"        => $name,
                "gender"      => $sexCode,
                "yearOfBirth" => $dob,
                "patient"     => $patient1,
            ]),
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Authorization: Bearer ' . $accessToken,
                'REQUEST-ID: ' . $requestId,
                'TIMESTAMP: '  . $this->isoTimestamp(),
                'X-HIP-ID: '   . $hipId,
                'X-CM-ID: '    . $x_cm_id,
                'X-API-Key: '  . $apiKey,
            ),
        ));
        $response = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        // 2xx = link initiated & care-contexts stored for auto-link; mark linked
        // so we don't re-initiate on every save. The actual care-contexts/link
        // runs asynchronously in Node when the webhook arrives.
        if ($httpCode >= 200 && $httpCode < 300) {
            // Flag every context this push covered, so it is not sent again.
            // Table and column are whitelisted above, and the value is bound.
            foreach ($marks as $mk) {
                $sql = "UPDATE `{$mk['table']}` SET `abha_linked`=1, `linkRequestId`=? WHERE `{$mk['col']}`=? LIMIT 1";
                $upd = $con->prepare($sql);
                if (!$upd) {
                    $lg("WARN: cannot mark {$mk['table']}.{$mk['col']}={$mk['val']} (" . $con->error . ')');
                    continue;
                }
                $mVal = (string) $mk['val'];
                $upd->bind_param('ss', $requestId, $mVal);
                $upd->execute();
                $lg("MARK: {$mk['table']}.{$mk['col']}={$mk['val']} rows=" . $upd->affected_rows);
                $upd->close();
            }
            $lg("OK: link initiated httpCode=$httpCode requestId=$requestId");
        } else {
            $lg("FAIL: token/generate httpCode=$httpCode resp=" . substr((string)$response, 0, 400));
        }

        mysqli_close($con);
        return $response;
    }

    /**
     * The OP-consultation entry point, unchanged for its caller: the patient row
     * is both the thing pushed and the thing flagged, so its behaviour is
     * identical to before this was generalised.
     */
    function linkAbhaCareContexts($opd, $patient1)
    {
        return $this->linkAbhaContextGroups($opd, $patient1, [
            ['table' => 'central_opd', 'col' => 'id', 'val' => (int) $opd],
        ]);
    }

    /**
     * Link one care context raised outside the OP-consultation flow: a bill, a
     * discharge summary, a finalised lab report.
     *
     * The reference formats below MUST match the ones api/patient_history.php
     * emits, or ABDM receives the same record under two different references and
     * stores it twice. That has happened once already: the file records that a
     * grouped INV-{date}/{patient_id} form was replaced by INV/{billId} because
     * it put the patient id where the bill id was expected.
     *
     * @param string $kind  invoice | diagnostic | discharge
     * @param string $id    bill id, sample_status id, or ipd_no
     * @param int    $opdId central_opd.id — the patient whose ABHA is used
     */
    function linkAbhaSingleContext($kind, $id, $opdId, $patientLabel = '')
    {
        $id = trim((string) $id);
        if ($id === '' || !$opdId) {
            return false;
        }
        $label = $patientLabel !== '' ? $patientLabel : $opdId;

        switch ($kind) {
            case 'invoice':
                $group = [
                    'referenceNumber' => 'INV/' . $id,
                    'display'         => 'Invoice for Patient ID: ' . $label,
                    'careContexts'    => [[
                        'referenceNumber' => $id,
                        'display'         => 'Invoice ' . $id,
                    ]],
                    'hiType' => 'Invoice',
                    'count'  => 1,
                ];
                $mark = ['table' => 'billing', 'col' => 'id', 'val' => $id];
                break;

            case 'diagnostic':
                $group = [
                    'referenceNumber' => 'DIAGRPT/' . $id,
                    'display'         => 'Diagnostic Report for Patient ID: ' . $label,
                    'careContexts'    => [[
                        'referenceNumber' => 'DGR-' . $id,
                        'display'         => 'Diagnostic Report ' . $id,
                    ]],
                    'hiType' => 'DiagnosticReport',
                    'count'  => 1,
                ];
                $mark = ['table' => 'sample_status', 'col' => 'id', 'val' => $id];
                break;

            case 'discharge':
                // NOTE: patient_history.php stamps this reference with the date
                // the list is generated, not the date of discharge. Pushed once
                // at discharge that is stable; pushed again later it produces a
                // different reference for the same summary. See the handover
                // note before running any backlog job over DischargeSummary.
                $group = [
                    'referenceNumber' => 'DIS-' . date('Ymd') . '/' . $id,
                    'display'         => 'Discharge Summary for Patient ID: ' . $label,
                    'careContexts'    => [[
                        'referenceNumber' => $id,
                        'display'         => 'Discharge Summary ' . $id,
                    ]],
                    'hiType' => 'DischargeSummary',
                    'count'  => 1,
                ];
                $mark = ['table' => 'central_ipd', 'col' => 'ipd_no', 'val' => $id];
                break;

            default:
                return false;
        }

        return $this->linkAbhaContextGroups($opdId, [$group], [$mark]);
    }
    function generateUuidV4() {
        return sprintf(
            '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xffff), mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff) | 0x4000,
            mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        );
    }
    function isoTimestamp() {
      return gmdate("Y-m-d\TH:i:s.000\Z");
    }
}

if (!function_exists('formatFamilyHistoryText')) {
    /**
     * Render op_examination.family_history for a human reader.
     *
     * optreatment.php stores family history as JSON — one object per relative —
     * so the FHIR bundle can populate relationship / sex / age / deceased /
     * contributedToDeath instead of publishing a card full of "NA". Anything
     * that PRINTS the column (the case sheet, the discharge summary, the PHR
     * document) must therefore decode it first, or the patient is shown raw
     * JSON.
     *
     * Rows saved before that change are plain sentences and are returned
     * untouched, so old records keep printing exactly as they always did.
     *
     * Lives here rather than in its own file because every caller —
     * print_opcasesheet.php, api/fhir_records.php, optreatment.php,
     * optreatment1.php — already includes this model.
     *
     * @return string one relative per line, or the original text.
     */
    function formatFamilyHistoryText($value): string
    {
        $raw = trim((string) $value);
        if ($raw === '') {
            return '';
        }

        $rows = json_decode($raw, true);
        if (!is_array($rows) || $rows === []) {
            return $raw;   // legacy free text
        }

        $lines = [];
        foreach ($rows as $row) {
            if (!is_array($row)) {
                continue;
            }
            $rel  = trim((string) ($row['relation'] ?? ''));
            $cond = trim((string) ($row['condition'] ?? ''));
            $age  = trim((string) ($row['age'] ?? ''));
            if ($rel === '' && $cond === '') {
                continue;
            }

            $line = $rel !== '' ? $rel : 'Family member';
            if ($cond !== '') {
                $line .= ' - ' . $cond;
            }
            if ($age !== '') {
                $line .= ' (age ' . $age . ')';
            }
            if (($row['deceased'] ?? 'No') === 'Yes') {
                $line .= ' - deceased';
                if (($row['contributed'] ?? 'No') === 'Yes') {
                    $line .= ', condition contributed to death';
                }
            }
            $lines[] = $line;
        }

        return $lines !== [] ? implode("\n", $lines) : $raw;
    }
}
?>
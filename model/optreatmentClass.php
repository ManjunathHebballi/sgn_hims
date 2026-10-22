<?php
require_once(__DIR__ . '/session_config.php');
date_default_timezone_set('asia/kolkata');
include('dbconnect.php');


class addOTTreat{ 
    
function setcomone($one)
{
    $this->one=$one;
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
function setpulse($pulse)
{
    $this->pulse=$pulse;
}
function setbp($bp)
{
    $this->bp=$bp;
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

    function getIcdCodes()
    {
        $newlogin = new userlogin;                  
        $con=$newlogin->dbconnect();
        $sql="SELECT * FROM `icd_codes` ";
        $res=mysqli_query($con,$sql);
        return $res;
    }
    function getDiagnosis()
    {
        $newlogin = new userlogin;                  
        $con=$newlogin->dbconnect();
        $sql="SELECT * FROM `diagnosis` ";
        $res=mysqli_query($con,$sql);
        return $res;
    }
function getPatienMedicDoc($id)
    {
        $newlogin = new userlogin;                  
        $con=$newlogin->dbconnect();
        $sql="SELECT om.*,m.`name` from `op_medicine` om,`medicine` m where m.`id`=om.`medicine` and `patient_id`='$id'";
        // echo $sql;
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
        $sql="INSERT INTO `op_examination`( `patient_id`, `date`, `complaints1`, `complaints2`, `complaints3`, `complaints4`, `nadi`, `mala`, `mutra`, `jiva`, `sparsh`, `shabda`, `drik`, `akriti`, `rs`, `lungs`, `others`, `cvs`, `bp`, `heart`, `tenderness`,`organomegaly`, `cns`, `power`, `reflexes`, `tone`, `roga`, `dept_id`) 
        VALUES ('$this->opd','$date','$this->one','$this->two','$this->three','$this->four','$this->nadi','$this->mala','$this->mutra','$this->jiva','$this->sparsha','$this->shabda','$this->drik','$this->akriti','$this->rs','$this->lung','$this->others','$this->pulse','$this->bp','$this->heart','$this->pa','$this->organomegaly','$this->cns','$this->power','$this->reflexes','$this->tone','$this->roga','$this->dept')";
        //   echo $sql;exit;
        $res=mysqli_query($con,$sql);
       
        $date=date("Y-m-d H:i:s");
      $sql1 = $con->prepare("INSERT INTO `query`(`sql_query`, `created_by`, `created_at`) VALUES (?, ?, ?)");
        $sql1->bind_param("sss", $sql, $_SESSION['name'], $date);
        $sql1->execute();
$sql1->close();
$con->close();
        return $res;
    }
    function updateOPTreat()
    {
        $date=date("Y-m-d");
        $newlogin = new userlogin;                  
        $con=$newlogin->dbconnect();
        $sql="UPDATE `op_examination` SET `complaints1`='$this->one',`complaints2`='$this->two',`complaints3`='$this->three',`complaints4`='$this->four',`nadi`='$this->nadi',`mala`='$this->mala',`mutra`='$this->mutra',`jiva`='$this->jiva',`sparsh`='$this->sparsha',`shabda`='$this->shabda',`drik`='$this->drik',`akriti`='$this->akriti',`rs`='$this->rs',`lungs`='$this->lung',`others`='$this->others',`cvs`='$this->pulse',`bp`='$this->bp',`heart`='$this->heart',`tenderness`='$this->pa',`organomegaly`='$this->organomegaly',`cns`='$this->cns',`power`='$this->power',`reflexes`='$this->reflexes',`tone`='$this->tone',`roga`='$this->roga',`dept_id`='$this->dept' WHERE `patient_id`='$this->opd'";
        //   echo $sql;exit;
        $res=mysqli_query($con,$sql);
        
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
        if($_SESSION['role']=='Radiology')
        {
          $labid=" and is_radio=1";
        }
        else{
          $labid="";
        }
        $sql="SELECT ln.`testid`,lg.`testname`,ln.`test_name` FROM `lab_test` lg,`labtest_group` ln WHERE lg.`testid`=ln.`labid` and `status`='active' $labid order by lg.`testid`,ln.`testid`";
        //   echo $sql;exit;
        $res=mysqli_query($con,$sql);
        return $res;
    }
    function insertOPInvest($lab,$pid,$date)
    {
        // $date=date("Y-m-d");
          $date=date("Y-m-d H:i:s");
        $newlogin = new userlogin;                  
        $con=$newlogin->dbconnect();
          
    
    
    $sql="select * from `central_opd` where id='$pid'";
    
$ip_res=mysqli_query($con,$sql);

 $month = date('n'); // Numeric representation of month (1-12)
    $year = date('Y');

    if ($month >= 4) {
        // April to December
        $startYear = $year;
        $endYear = $year + 1;
    } else {
        // January to March
        $startYear = $year - 1;
        $endYear = $year;
    }

    $financialYear= "$startYear-" . substr($endYear, -2);
    

if(mysqli_num_rows($ip_res)>0)
    {
        $bill_row=mysqli_fetch_assoc($ip_res);
        if($bill_row['is_generated']==1)
        {
           $query="SELECT * FROM data_table WHERE id=7";
                 $bill=mysqli_fetch_assoc(mysqli_query($con,$query));
                 $bill_no=$bill['table_id'];
               
                 $bill_no=$bill_no+1;
                 $fi_bill_no='PH/'.$financialYear.'/'.sprintf("%04d", $bill_no);
         
         $sql="INSERT INTO `billing` ( `patient_id`, `amount`, `service_level_discount`, `discount_amount`, `paid_amount`, `outstand_amount`, `bill_no`, `doctor`, `dept_id`,  `service_type`, `patient_type`, `status`, `collected_by`, `collected_at`,`is_generated`,`discount_reason`) VALUES 
                               ( '$bill_row[id]', '0', '0', '0', '0', '0', '$fi_bill_no', '$bill_row[doctor]', '$bill_row[dept_id]', 'Pathology', 'OP', 'PAID', '$_SESSION[name]', NOW(),'1','CAMP')";
                            //   echo $sql;exit;
                      $res=mysqli_query($con,$sql);
                      $id=mysqli_insert_id($con);
                      if($id>0)  {
                      $query1="UPDATE data_table set table_id='$bill_no' WHERE id=7";
                      mysqli_query($con,$query1);
                    
                     
                      }
        }
    }
    
                    $date1 = date('Ymd',strtotime($date)); // 20250625
                    $prefix = 'LAB-' . $date1 . '-';
                    $query1 = "SELECT visit_no,patient_id FROM lab_report WHERE visit_no LIKE '$prefix%' ORDER BY visit_no DESC LIMIT 1";
                    $res1 = mysqli_query($con, $query1);
                    
                    if ($row1 = mysqli_fetch_assoc($res1)) 
                    {
                       
                        $last_no = (int)substr($row1['visit_no'], -4); // Extract last 4 digits
                        $new_no = str_pad($last_no + 1, 4, '0', STR_PAD_LEFT);
                        $visit_no = $prefix . $new_no;
                    }
                     else 
                     {
                        $new_no = '0001';
                        $visit_no = $prefix . $new_no;
                      }
                    
                    // 
        // echo $query1.$visit_no;exit;
        // Tests already recorded for this bill on this save. Replaces the old
        // $test_id_old check, which only compared against the previous iteration
        // and so let an interleaved parameter order create duplicates.
        $sample_seen = array();
    foreach ($lab as $key => $value) {
 
        $query2="SELECT test_id FROM `lab_test_parameter` where id=$value";
        $res2=mysqli_query($con, $query2);
        $row2=mysqli_fetch_assoc($res2);
        $test_id=$row2['test_id'];
        // Guard on both this save and what is already on file: another path
        // (sample_collection.php, or an earlier save of this treatment) may have
        // recorded the same bill/test already.
        $dup_chk = mysqli_query($con, "SELECT id FROM sample_status
                                        WHERE bill_no='" . mysqli_real_escape_string($con, (string) $fi_bill_no) . "'
                                          AND patient_id='" . mysqli_real_escape_string($con, (string) $pid) . "'
                                          AND test_id='" . mysqli_real_escape_string($con, (string) $test_id) . "'
                                        LIMIT 1");
        $already = ($dup_chk && mysqli_num_rows($dup_chk) > 0);
        if(!isset($sample_seen[$test_id]) && !$already)
        {
            $dt_sp=date("Y-m-d H:i:s", strtotime($dt . " +10 minutes"));
            $dt_ac=date("Y-m-d H:i:s", strtotime($dt_sp . " +10 minutes"));
            $dt_fz=date("Y-m-d H:i:s", strtotime($dt_sp . " +100 minutes"));
            $sql="INSERT IGNORE INTO sample_status(`bill_no`, `patient_id`, `test_id`, `is_sample_collected`, `sample_collected_by`, `sample_collected_at`, `is_sample_acknowledge`, `sample_acknowledged_by`, `sample_acknowledged_at`,`is_finalized`,`finalized_by`,`finalized_at`)
             VALUES ('$fi_bill_no','$pid','$test_id','1','lab','$dt_sp','1','lab','$dt_ac','1','lab','$dt_fz')";
            
            mysqli_query($con,$sql);
            if($id>0)
            {
                $sql1="INSERT INTO `billing_details` (`bill_id`, `bill_no`, `service_id`, `doctor_id`, `unit`, `price`, `discount`, `status`, `created_at`) VALUES 
                                    ( '$id', '$fi_bill_no', '$test_id', '0', '1', '0', '0', 'PAID', NOW())";
                $res=mysqli_query($con,$sql1);  
            }
        }
         $query = "INSERT INTO lab_report (parameter_id,date,patient_id,patient_type,visit_no,testid,bill_no) 
                                          VALUES ('$value', '$date', '$pid', 'OP', '$visit_no', '$test_id','$fi_bill_no')";
                                mysqli_query($con, $query);
                             
        // echo $query;exit;
        $sql1 = $con->prepare("INSERT INTO `query`(`sql_query`, `created_by`, `created_at`) VALUES (?, ?, ?)");
        $sql1->bind_param("sss", $query, $_SESSION['name'], $date);
        $sql1->execute();
        $sql1->close();
        $sample_seen[$test_id] = true;
    }
// exit;

    return $fi_bill_no;
    $con->close();
    }
    function getPatientLab($id)
    {
        $newlogin = new userlogin;                  
        $con=$newlogin->dbconnect();
         if($_SESSION['role']=='Radiology')
        {
          $labid=" and is_ratio=1";
        }
        else{
          $labid="";
        }
        $sql="SELECT ln.`testid`,lg.`testname`,ln.`test_name`,lr.`test_result`,lr.`id`,lr.date FROM `lab_test` lg,`labtest_group` ln,`lab_report` lr WHERE lg.`testid`=ln.`labid` and `status`='active' $labid and lr.`patient_id`='$id' and patient_type='OP' and lr.`lab_result`=ln.`testid`  order by lg.`testid`";
        //   echo $sql;exit;
        $res=mysqli_query($con,$sql);
        return $res;
    }
     function getPatientLab1($id)
    {
        $newlogin = new userlogin;                  
        $con=$newlogin->dbconnect();

        $sql="SELECT lp.`name`,lr.`id`,lr.`date` as ldate,lp.unit,lr.visit_no FROM `lab_report` lr,`lab_test_parameter` lp,lab_test lt WHERE lt.`testid` =lp.`test_id` and lp.id=lr.parameter_id and lr.`patient_id`='$id'";
        //   echo $sql;exit;
        $res=mysqli_query($con,$sql);
        return $res;
    }
    function getLabTests()
    {
        $newlogin = new userlogin;                  
        $con=$newlogin->dbconnect();
        $sql="SELECT lp.*,lt.testname FROM `lab_test_parameter` lp,lab_test lt where lt.testid=lp.test_id and lp.name!='' and lt.status='active' and lp.status='active' group by lp.id order by testname";
        $res=mysqli_query($con,$sql);
        return $res;
        
    }
    
    function getMedic()
    {
        $newlogin = new userlogin;                  
        $con=$newlogin->dbconnect();
        $sql="SELECT * from `medicine` where `status`='active'";
        $res=mysqli_query($con,$sql);
        return $res;
    }
     function getPatient($id)
    {
        $newlogin = new userlogin;                  
        $con=$newlogin->dbconnect();
        $sql="SELECT * from `central_opd` where `id`='$id'";
        // echo $sql;exit;
        $res=mysqli_query($con,$sql);
        return $res;
    }
    function insertOPMedic($id)
    {
        $date=date("Y-m-d"); 
        $newlogin = new userlogin;                  
        $con=$newlogin->dbconnect();
        $sql="INSERT INTO `op_medicine`(`patient_id`, `date`, `medicine`, `dosage`, `qty`,) VALUES ('$id','$date','$this->omedicine','$this->dosage','$this->qty')";
        $res=mysqli_query($con,$sql);
        
         $date=date("Y-m-d H:i:s");
      $sql1 = $con->prepare("INSERT INTO `query`(`sql_query`, `created_by`, `created_at`) VALUES (?, ?, ?)");
        $sql1->bind_param("sss", $sql, $_SESSION['name'], $date);
        $sql1->execute();
$sql1->close();
$con->close();
        return $res;
    }
    function getPatienMedic($id)
    {
        $newlogin = new userlogin;                  
        $con=$newlogin->dbconnect();
        $sql="SELECT om.*,m.`name` from `op_medicine` om,`medicine` m where m.`id`=om.`medicine` and `patient_id`='$id'";
        $res=mysqli_query($con,$sql);
        return $res;
    }
    function getExamData($id)
    {
        $newlogin = new userlogin;                  
        $con=$newlogin->dbconnect();
        $sql="SELECT * from `op_examination` WHERE `patient_id`='$id'";
        // echo $sql;
        $res=mysqli_query($con,$sql);
        return $res;
    }
    function insertProceMedic($id)
    {
        $newlogin = new userlogin;                  
        $con=$newlogin->dbconnect();
        $sql="INSERT INTO `op_treatment`( `patient_id`, `date`, `procedure`, `sub_procedure`, `medicine`, `days`, `qty`, `total`, `morning`, `afternoon`, `evening`, `night`, `observation`) 
        VALUES ('$id','$this->pdate','$this->procedure','$this->subprocedure','$this->omedicine','$this->roga','$this->qty','$this->total1','$this->morning','$this->afternoon','$this->evening','$this->night','$this->anupana')";
        $res=mysqli_query($con,$sql);
       
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
        $sql="SELECT ot.*,p.procedure_name,sp.subprocedure_name,m.medicine as proce_medic from `procedure` p,`procedure_medicine` m,`op_treatment` ot left join sub_procedure sp on ot.sub_procedure=sp.id WHERE `patient_id`='$id' and p.id=ot.procedure and m.id=ot.medicine";
        // echo $sql;
        $res=mysqli_query($con,$sql);
        return $res;
    
    }
    function getOPExamination($id)
    {
        $newlogin = new userlogin;                  
        $con=$newlogin->dbconnect();
        $sql="SELECT * from `op_examination` where `patient_id`='$id'";
        // echo $sql;
        // exit;
        $res=mysqli_query($con,$sql);
        return $res;
    }
    function getComplaints()
    {
        $newlogin = new userlogin;                  
      $con=$newlogin->dbconnect();
      $sql="select * from temp_complaints";
      $res=mysqli_query($con,$sql);
      return $res;
    }
    function patientoldDetials($opd,$date)
    {
        $newlogin = new userlogin;                  
        $con=$newlogin->dbconnect();
        $sql="SELECT m.*,me.name from  op_medicine m,medicine me where me.id=m.medicine and m.patient_id=(select id from central_opd where opd_no='$opd' and date(date)<date('$date') order by id desc limit 1)";
        // echo $sql;
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
    function getPatientHistory($opd,$date)
    {
        $newlogin = new userlogin;                  
      $con=$newlogin->dbconnect();
      $date=date("Y-m-d",strtotime($date));
      $sql="select c.id,c.date,CONCAT(u.name,' ',u.last_name) as doct_name,ex.`bp_min`,ex.`bp_max`,ex.`pulse`,ex.`rbs`,ex.`weight`,ex.`complaints`,ex.`diagnosis`,ex.`next_visit_date` from doctor u,central_opd c LEFT JOIN `op_examination` ex on ex.patient_id=c.id where c.opd_no='$opd' and u.id=c.doctor and date(c.date) < '$date' order by date(c.date) desc";
    //   echo $sql;
      $res=mysqli_query($con,$sql);
      return $res;
    }
       function insertOPMedic1($id)
    {
        $date=date("Y-m-d");
        $newlogin = new userlogin;                  
        $con=$newlogin->dbconnect();
        $sql="INSERT INTO `op_medicine`(`patient_id`, `date`, `medicine`, `qty`,`price`,`total`,`discount`,`grand_total`) VALUES ('$id','$date','$this->medicine1','$this->quantity1','$this->price1','$this->total1','$this->indiscount1','$this->grand1')";
        // echo $sql;
        // exit();
        
        
         $sql2="select current_qty from medicine_purchase where medicine_id='$this->medicine1' and batch_id='$this->batch1'";
          $result2=mysqli_query($con,$sql2);
          $row=mysqli_fetch_assoc($result2);
          $qty=$row['current_qty'] - $this->quantity1;
          
          
          $sql1="update medicine_purchase set current_qty=$qty where medicine_id='$this->medicine1' and batch_id='$this->batch1'";
        //      echo $sql1;
        // exit;
            $res1=mysqli_query($con,$sql1);
       
        
        $res=mysqli_query($con,$sql);
        return $res;
    }
}
?>
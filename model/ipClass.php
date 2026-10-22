<?php 
require_once(__DIR__ . '/session_config.php');
include('dbconnect.php');


class addIP{ 
private $admdate,
        $opno,
        $ipcardno,
        $card,
        $fname,
        $age,
        $sex,
        $religion,
        $caste,
        $mobile,
        $pname,
        $occupation,
        $department,
        $diagnosis,
        $address,
        $category,
        $annual,
        $drname,
        $mlc,
        $patinetid,
        $pre_ward,
        $pres_bed,
        $ward,
        $bed,
        $cmo,
        $advance,
        $referby,
        $remark,
        $ptype,
        $policy,
        $coverage_type,
        $insurance_name,
        $is_generated;

    function setadmdate($admdate) {
        $this->admdate = $admdate;
    }   
    function setopno($opno) {
        $this->opno = $opno;
    }
    function setipcardno($ipcardno) {
        $this->ipcardno = $ipcardno;
    }   
function setcard($card) {
  $this->card = $card;
}   
function setfname($fname) {
  $this->fname = $fname;
}   
function setage($age) {
  $this->age = $age;
}   
function setsex($sex) {
  $this->sex = $sex;
}   
function setreligion($religion) {
  $this->religion = $religion;
}   
function setcaste($caste) {
  $this->caste = $caste;
}   
function setmobile($mobile) {
  $this->mobile = $mobile;
}   
function setpname($pname) {
  $this->pname = $pname;
}   
function setoccupation($occupation) {
  $this->occupation = $occupation;
}  

function setdepartment($department) {
  $this->department = $department;
}  
function setdiagnosis($diagnosis) {
  $this->diagnosis = $diagnosis;
} 

function setaddress($address) {
  $this->address = $address;
} 
function setcategory($category) {
  $this->category = $category;
} 
function setannual($annual) {
  $this->annual = $annual;
} 
function setdrname($drname) {
    $this->drname = $drname;
  } 
  function setmlc($mlc) {
    $this->mlc = $mlc;
  } 
   function setpatinetid($patinetid) {
    $this->patinetid = $patinetid;
  }
   function setpre_ward($pre_ward) {
    $this->pre_ward = $pre_ward;
  }
   function setpres_bed($pres_bed) {
    $this->pres_bed = $pres_bed;
  }
  function setward($ward) {
    $this->ward = $ward;
  } 
  function setbed($bed) {
    $this->bed = $bed;
  } 
  function setcmo($cmo) {
    $this->cmo = $cmo;
  } 
  function setadvance($advance) {
    $this->advance = $advance;
  } 
 function setreferby($referby) {
    $this->referby = $referby;
  } 
  function setremark($remark) {
    $this->remark = $remark;
  } 
 function setptype($ptype)
 {
     $this->ptype = $ptype;
 }
 
 function setpolicy($policy)
 {
     $this->policy = $policy;
 }
 
 function setcoverage_type($coverage_type)
 {
     $this->coverage_type = $coverage_type;
 }
 
 function setinsurance_name($insurance_name)
 {
     $this->insurance_name = $insurance_name;
 }
 
 function buildCashlessRemark()
 {
     $remark = trim((string)$this->remark);
     $ptype = strtoupper(trim((string)$this->ptype));
 
     if(strpos($ptype, 'CASHLESS') !== false)
     {
         $details = array();
 
         if(trim((string)$this->coverage_type) !== '')
         {
             $details[] = trim((string)$this->coverage_type);
         }
 
         if(trim((string)$this->insurance_name) !== '')
         {
             $details[] = trim((string)$this->insurance_name);
         }
 
         if(trim((string)$this->policy) !== '')
         {
             $details[] = 'Policy/Claim: '.trim((string)$this->policy);
         }
 
         if(!empty($details))
         {
             $cashlessRemark = 'Cashless Details - '.implode(' | ', $details);
             $remark = $remark !== '' ? $remark.' | '.$cashlessRemark : $cashlessRemark;
         }
     }
 
     return $remark;
 }
 
 function setis_generated($is_generated)
 {
     $this->is_generated = $is_generated;
 }
 
  function getIcdCodes()
    {
        $newlogin = new userlogin;                  
        $con=$newlogin->dbconnect();
        $sql="SELECT id,code,details,substring(details, 1, 100) as data FROM `icd_codes` GROUP by substring(details,1,20) ";
        $res=mysqli_query($con,$sql);
        return $res;
    }
 
function selectDept()
{
  $newlogin = new userlogin;                  
  $con=$newlogin->dbconnect();
  $sql="SELECT * FROM `department` WHERE `status`='active'";
  $res=mysqli_query($con,$sql);
  return $res;
}
function selectWard()
{
  $newlogin = new userlogin;                  
  $con=$newlogin->dbconnect();
  $sql="SELECT distinct(`ward`) as ward FROM `bed` WHERE `status`='active'";
  $res=mysqli_query($con,$sql);
  return $res;
}
function selectBed()
{
  $newlogin = new userlogin;                  
  $con=$newlogin->dbconnect();
  $sql="SELECT * FROM `bed` WHERE `status`='active'";
  $res=mysqli_query($con,$sql);
  return $res;
}
function selectDoctor()
{
  $newlogin = new userlogin;                  
  $con=$newlogin->dbconnect();
  $sql="SELECT * FROM `doctor` WHERE `status`='active' and `role`='Doctor' order by name";
  $res=mysqli_query($con,$sql);
  return $res;
}

function insertIP()
{
    $date=date('Y-m-d H:i:s',strtotime($this->admdate));
    $newlogin = new userlogin;                  
    $con=$newlogin->dbconnect();
    $query="SELECT * FROM `data_table` WHERE `id`=2";

  $central_ipd=mysqli_fetch_assoc(mysqli_query($con,$query));
  $ipd_no=$central_ipd['table_id']+1;
$y=date("Ymd",strtotime($date));
  if($y!=substr($ipd_no,0,2))
  {
      $data="SELECT SUBSTR(MAX(ipd_no),3,12) as ipd FROM central_ipd where ipd_no like '%$y%'";
      $ip_no=mysqli_fetch_assoc(mysqli_query($con,$data));
      if(isset($ip_no['ipd']))
                    {
                        $ipd_no=$ip_no['ipd']+1;
                       
                    }
                    else{
                       $ipd_no=$y.'001';
                    }
            
  }
  $diagnosis=is_array($this->diagnosis) ? implode(",",$this->diagnosis) : $this->diagnosis;
  $remark=mysqli_real_escape_string($con, $this->buildCashlessRemark());
  $policy=mysqli_real_escape_string($con, (string)$this->policy);
  $insurance_name=mysqli_real_escape_string($con, (string)$this->insurance_name);
  $patient_type=mysqli_real_escape_string($con, (string)$this->ptype);
    $sql="INSERT INTO `central_ipd`( `ipd_no`,`opd_no`,`rx_id`, `admission_date`, `name`, `age`, `gender`, `mobile`, `category`, `address`, `dept_id`,`religion`, `caste`, `parent_name`, `occupation`, `diagnosis`, `annual_income`, `doctor`, `mlc`, `bed_id`, `ward`,`cmo`,`created_by`,`refer_by`,`remark`,`patient_type`,`policy`,`insurance_name`,`is_generated`)
     VALUES (CONCAT('IP',$ipd_no),'$this->opno','$this->ipcardno','$date','$this->fname','$this->age','$this->sex','$this->mobile','$this->category','$this->address','$this->department','$this->religion','$this->caste','$this->pname','$this->occupation','$diagnosis','$this->annual','$this->drname','$this->mlc','$this->bed','$this->ward','$this->cmo','$_SESSION[name]','$this->referby','$remark','$patient_type','$policy','$insurance_name'
     ,'$this->is_generated')";
    // echo $sql;exit;
    
    
    
     
    $res=mysqli_query($con,$sql);
    $id=mysqli_insert_id($con);
              $date=date("Y-m-d H:i:s");
      $sql1 = $con->prepare("INSERT INTO `query`(`sql_query`, `created_by`, `created_at`) VALUES (?, ?, ?)");
        $sql1->bind_param("sss", $sql, $_SESSION['name'], $date);
        $sql1->execute();
$sql1->close();
    if ($con->connect_error) {
            die("Connection failed: " . $con->connect_error);
        }

        $query="UPDATE data_table set table_id=$ipd_no WHERE id=2";
        mysqli_query($con,$query);

        
        $bed="UPDATE `bed` SET `status`='occupied' WHERE `id`='$this->bed'";
        mysqli_query($con,$bed);
        
         $query7="UPDATE central_opd SET is_ipd=1, patient_type='$patient_type', policy='$policy' where opd_no='$this->opno' and date=DATE('$date')";
            mysqli_query($con,$query7);

   $con->close();

        echo "<script>window.open('../print_admission_slip.php?id=$id');window.location='../ippatient.php'</script>";
}





function selectIPreg()
{
  $newlogin = new userlogin;                  
  $con=$newlogin->dbconnect();
  $sql="SELECT * FROM `central_ipd`";
  $res=mysqli_query($con,$sql);
  return $res;
}


function selectIPreg2($date)
{
  $newlogin = new userlogin;                  
  $con=$newlogin->dbconnect();
  $sql="SELECT c.*,d.dept_name,b.bed_number as bed FROM `central_ipd` c,department d,bed b where b.id=c.bed_id and d.id=c.dept_id and date(admission_date)='$date' and c.is_generated=1";
  $res=mysqli_query($con,$sql);
  return $res;
}

function selectIPreg1($from,$to)
{
  $newlogin = new userlogin;                  
  $con=$newlogin->dbconnect();
  $sql="SELECT c.*,d.unit,b.bed_number as bed,de.dept_name FROM department de,`central_ipd` c LEFT JOIN `doctor` d on concat(TRIM(d.name),' ',TRIM(d.last_name))=TRIM(c.doctor) LEFT JOIN bed b on b.id=bed_id WHERE DATE(admission_date) between '$from' and '$to'and de.id=c.dept_id";
  $res=mysqli_query($con,$sql);
  return $res;
}



function selectIPreg1_search($from,$to,$search)
{
  $newlogin = new userlogin;                  
  $con=$newlogin->dbconnect();
  $data='';
   if(strlen($search)>1 )
  {
      $data .="(address like '%$search%' || ipd_no like '$search%' || c.name like '%$search%' || c.mobile like '$search%' || opd_no like '$search%')";
  }
  else if($_SESSION['role']=='Generated')
  {
      $data .=" is_generated=1 and DATE(admission_date) between '$from' and '$to'";
  }
  else 
  {
      $data .=" DATE(admission_date) between '$from' and '$to'";
  }
  
  $sql="SELECT c.*,d.unit,b.bed_number as bed,de.dept_name,lr.test_result FROM department de,`central_ipd` c LEFT JOIN `doctor` d on concat(TRIM(d.name),' ',TRIM(d.last_name))=TRIM(c.doctor) LEFT JOIN bed b on b.id=bed_id left join lab_report lr on lr.patient_id=c.ipd_no WHERE $data and de.id=c.dept_id  group by c.id order by c.id";
//   echo $sql;
  $res=mysqli_query($con,$sql);
  return $res;
}

function advanceAmount($from,$to)
{
  $newlogin = new userlogin;                  
  $con=$newlogin->dbconnect();
  $sql="SELECT *, sum(a.amount) as total  FROM `central_ipd` c,`ip_advance_amount`a WHERE DATE(admission_date) between '$from' and '$to' group by a.ip_no  ";
  $res=mysqli_query($con,$sql);
  return $res;
}


function getIPPatient($id)
{
    $newlogin = new userlogin;                  
  $con=$newlogin->dbconnect();
  $sql="SELECT * FROM `central_ipd` WHERE id='$id'";
  $res=mysqli_query($con,$sql);
  return $res;
}

function updateIP($id)
{
    $newlogin = new userlogin;                  
  $con=$newlogin->dbconnect();
  $diagnosis=is_array($this->diagnosis) ? implode(",",$this->diagnosis) : $this->diagnosis;
  $remark=mysqli_real_escape_string($con, $this->buildCashlessRemark());
  $policy=mysqli_real_escape_string($con, (string)$this->policy);
  $insurance_name=mysqli_real_escape_string($con, (string)$this->insurance_name);
  $patient_type=mysqli_real_escape_string($con, (string)$this->ptype);
  $sql="UPDATE `central_ipd` set `opd_no`='$this->opno',`name`='$this->fname', `age`='$this->age', `gender`='$this->sex', `mobile`='$this->mobile', `category`='$this->category',`religion`='$this->religion', `caste`='$this->caste', `address`='$this->address',`admission_date`='$this->admdate', `dept_id`='$this->department',`parent_name`='$this->pname',`occupation`='$this->occupation', `diagnosis`='$diagnosis', `annual_income`='$this->annual', `doctor`='$this->drname', `mlc`='$this->mlc',`refer_by`='$this->referby', `remark`='$remark', `patient_type`='$patient_type', `policy`='$policy', `insurance_name`='$insurance_name' WHERE id='$id'";
//   echo $sql;exit;
  $res=mysqli_query($con,$sql);
  
  $sql1="UPDATE `central_opd` SET  `name`='$this->fname', `age`='$this->age', `gender`='$this->sex', `mobile`='$this->mobile',  `address`='$this->address',`diagnosis`='$diagnosis', `patient_type`='$patient_type', `policy`='$policy' WHERE `opd_no`='$this->opno'";
   mysqli_query($con,$sql1);
            $date=date("Y-m-d H:i:s");
      $sql1 = $con->prepare("INSERT INTO `query`(`sql_query`, `created_by`, `created_at`) VALUES (?, ?, ?)");
        $sql1->bind_param("sss", $sql, $_SESSION['name'], $date);
        $sql1->execute();
$sql1->close();
$con->close();
  return $res;
}
function delteIP($id)
{
    $newlogin = new userlogin;                  
  $con=$newlogin->dbconnect();
  $query="select * from central_ipd where id='$id'";
  $row=mysqli_fetch_assoc(mysqli_query($con,$query));
  $ipd=substr($row['ipd_no'],2,11)-1;
  $sql="DELETE FROM `central_ipd` WHERE id='$id'";
  $res=mysqli_query($con,$sql);
  
           $date=date("Y-m-d H:i:s");
      $sql1 = $con->prepare("INSERT INTO `query`(`sql_query`, `created_by`, `created_at`) VALUES (?, ?, ?)");
        $sql1->bind_param("sss", $sql, $_SESSION['name'], $date);
        $sql1->execute();
$sql1->close();

  $query="UPDATE data_table set table_id=$ipd WHERE id=2";
        mysqli_query($con,$query);

        
        $bed="UPDATE `bed` SET `status`='active' WHERE `id`='$row[bed_id]'";
        mysqli_query($con,$bed);
        $con->close();
  return $res;
}


function insertTransferBed()
{
      $newlogin = new userlogin;                  
      $con=$newlogin->dbconnect();
      
      
      $sql="update central_ipd set ward='$this->ward',bed_id='$this->bed' where ipd_no='$this->patinetid'";
      mysqli_query($con,$sql);
      
               $date=date("Y-m-d H:i:s");
      $sql1 = $con->prepare("INSERT INTO `query`(`sql_query`, `created_by`, `created_at`) VALUES (?, ?, ?)");
        $sql1->bind_param("sss", $sql, $_SESSION['name'], $date);
        $sql1->execute();
$sql1->close();
          
      $sql1="update bed set status='active' where id='$this->pres_bed' and ward='$this->pre_ward'";
      mysqli_query($con,$sql1);
      
      
       $sql2="update bed set status='occupied' where id='$this->bed' and ward='$this->ward'";
      mysqli_query($con,$sql2);
      
      $sql3="INSERT INTO `transfer_bed` (`patient_id`, `old_ward`, `old_bed`, `new_ward`, `new_bed`, `transfered_date`, `transfered_by`) VALUES ('$this->patinetid', '$this->pre_ward', '$this->pres_bed', '$this->ward', '$this->bed',NOW(), '$_SESSION[name]')";
      $res= mysqli_query($con,$sql3);
       $date=date("Y-m-d H:i:s");
                    $date=date("Y-m-d H:i:s");
      $sql1 = $con->prepare("INSERT INTO `query`(`sql_query`, `created_by`, `created_at`) VALUES (?, ?, ?)");
        $sql1->bind_param("sss", $sql, $_SESSION['name'], $date);
        $sql1->execute();
$sql1->close();
$con->close();
    //   exit;
      return $res;
}

function getTransferedBedList($from,$to)
{
      $newlogin = new userlogin;                  
      $con=$newlogin->dbconnect();
      
      $sql="select t.*,c.*,(select b.bed_number from bed b where b.id=t.old_bed) as o_bed,(select b.bed_number from bed b where b.id=t.new_bed) as n_bed,d.dept_name from central_ipd c,department d,transfer_bed t  where c.dept_id=d.id and t.patient_id=c.ipd_no and date(transfered_date) between '$from' and '$to'";
    //   echo $sql;
      $res= mysqli_query($con,$sql);
   
      return $res;
}


function getTransferBedDetails($ipd)
{
      $newlogin = new userlogin;                  
      $con=$newlogin->dbconnect();
      
      $sql="select t.id as transfer_id,t.*,c.*,(select b.bed_number from bed b where b.id=t.old_bed) as o_bed,(select b.bed_number from bed b where b.id=t.new_bed) as n_bed,d.dept_name from central_ipd c,department d,transfer_bed t  where c.dept_id=d.id and t.patient_id=c.ipd_no and t.patient_id='$ipd'";
    //   echo $sql;
      $res= mysqli_query($con,$sql);
   
      return $res;
}
  function getTPAList()
  {
      $newlogin = new userlogin;                  
      $con=$newlogin->dbconnect();
      
      $sql="SELECT * FROM `tpa` ORDER BY `name`";
      $res= mysqli_query($con,$sql);
   
      return $res;
  }

  function getPatientType()
  {
      $newlogin = new userlogin;                  
      $con=$newlogin->dbconnect();
      
      $sql="SELECT * FROM `patient_type`";
      $res= mysqli_query($con,$sql);
   
      return $res;
  }
}
?>
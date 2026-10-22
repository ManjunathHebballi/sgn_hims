<?php 
require_once(__DIR__ . '/../../model/session_config.php');
include('dbconnect.php');


class discharge{
    private $patinetid,$refno,$fname,$age,$sex,$admidate,$disdate,$diagnosis,$complaint,$investigations,$course,$advice,$exam,$deptid,$dis_condtion,$files; 
    function setpatinetid($patinetid) {
        $this->patinetid = $patinetid;
    } 
    function setrefno($refno) {
        $this->refno = $refno;
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
    function setadmidate($admidate) {
        $this->admidate = $admidate;
    } 
    function setdisdate($disdate) {
        $this->disdate = $disdate;
    } 
    function setdiagnosis($diagnosis) {
        $this->diagnosis = $diagnosis;
    }
    function setcomplaint($complaint) {
        $this->complaint = $complaint;
    }
    function setinvestigations($investigations) {
        $this->investigations = $investigations;
    }
    function setcourse($course) {
        $this->course = $course;
    }
    function setexam($exam) {
        $this->exam = $exam;
    }
    function setadvice($advice) {
        $this->advice = $advice;
    } 

    function setdeptid($deptid) {
        $this->deptid = $deptid;
    } 
   function setdis_condtion($dis_condtion) {
        $this->dis_condtion = $dis_condtion;
    }
    function setfile($files) {
        $this->files = $files;
    } 

    function insertDischarge()
    {
        $newlogin = new userlogin;                  
          $con=$newlogin->dbconnect();
          $sql1="SELECT * FROM `discharge` WHERE `patient_id`='$this->patinetid'";
          
          $res1=mysqli_query($con,$sql1);
          if(mysqli_num_rows($res1)==0)
          {
          $sql="INSERT INTO `discharge`(`patient_id`, `ref_no`, `patient_name`,`dept_id`, `age`, `sex`, `admission_date`, `discharge_date`, `diagnosis`, `complaints`, `investigation`, `course_treatment`, `advice_discharge`,`physical_examination`,`created_by`,`discharge_condition`,`discharge_file`) 
          VALUES ('$this->patinetid','$this->refno','$this->fname','$this->deptid','$this->age','$this->sex','$this->admidate','$this->disdate','$this->diagnosis','$this->complaint','$this->investigations','$this->course','$this->advice','$this->exam','$_SESSION[name]','$this->dis_condtion','$this->files')";
        //  echo $sql;exit
          $res=mysqli_query($con,$sql);
              
               $date=date("Y-m-d H:i:s");
      $sql1 = $con->prepare("INSERT INTO `query`(`sql_query`, `created_by`, `created_at`) VALUES (?, ?, ?)");
        $sql1->bind_param("sss", $sql, $_SESSION['name'], $date);
        $sql1->execute();
$sql1->close();
            $query1="UPDATE central_ipd SET diagnosis='$this->diagnosis' WHERE ipd_no='$this->patinetid'";
            mysqli_query($con,$query1);
          $query="UPDATE `bed` SET `status`='active' WHERE id=(SELECT `bed_id` FROM `central_ipd` WHERE ipd_no='$this->patinetid') and `status`='occupied'";
          mysqli_query($con,$query);
          
          $query="UPDATE `central_ipd` SET discharge=1,final_bill=1 WHERE ipd_no='$this->patinetid'";
          mysqli_query($con,$query);
          $con->close();
          
           return $res;
          }
          else{
              echo "<script>alert('Patient Already Discharged Please check the IP Number')</script>";
            //   echo mysqli_num_rows($res1);
        //   echo $sql1;
        // exit;
              echo "<script>window.loaction='../dischargelist.php'</script>";
          }
    }

    function getDischarge($min,$max,$search)
    {
        $newlogin = new userlogin;                  
          $con=$newlogin->dbconnect();
         if(strlen($search)>2)
         {
              $data =" (patient_name like '%$search%' || patient_id like '$search%' ||  d.diagnosis like '$search%')" ;
         }
         else{
              $data ="DATE(discharge_date) between '$min' and '$max'";
         }
          $sql="SELECT d.*,c.created_by as createdby FROM `discharge` d, central_ipd c WHERE $data and c.ipd_no=d.patient_id";
        //   echo $sql; exit;
          $res=mysqli_query($con,$sql);
          return $res;
    }
    function getDischargeList($id)
    {
        $newlogin = new userlogin;                  
          $con=$newlogin->dbconnect();
          $sql="SELECT d.*,c.created_by as createdby FROM `discharge` d, central_ipd c WHERE d.id='$id' and c.ipd_no=d.patient_id";
          $res=mysqli_query($con,$sql);
          return $res;
    }
    
    function updateDischarge($id)
    {
        $newlogin = new userlogin;                  
          $con=$newlogin->dbconnect();
          $query="update central_ipd set  `diagnosis`='$this->diagnosis' where ipd_no='$this->patinetid'";
          mysqli_query($con,$query);
          
          $sql="UPDATE `discharge` SET `ref_no`='$this->refno',`discharge_date`='$this->disdate', `diagnosis`='$this->diagnosis', `complaints`='$this->complaint', `investigation`='$this->investigations', `course_treatment`='$this->course', `advice_discharge`='$this->advice',`physical_examination`='$this->exam',`discharge_condition`='$this->dis_condtion' WHERE id='$id'";
        //  echo $sql;exit;
          $res=mysqli_query($con,$sql);
          
           $date=date("Y-m-d H:i:s");
      $sql1 = $con->prepare("INSERT INTO `query`(`sql_query`, `created_by`, `created_at`) VALUES (?, ?, ?)");
        $sql1->bind_param("sss", $sql, $_SESSION['name'], $date);
        $sql1->execute();
$sql1->close();
$con->close();
          return $res;
    }
      function getIcdCodes()
    {
        $newlogin = new userlogin;                  
        $con=$newlogin->dbconnect();
        $sql="SELECT id,code,details,substring(details, 1, 100) as data FROM `icd_codes` GROUP by substring(details,1,20) ";
        $res=mysqli_query($con,$sql);
        return $res;
    }
}
?>
<?php
require_once(__DIR__ . '/session_config.php');
include_once('dbconnect.php');


class discharge{
    private $patinetid,$refno,$fname,$age,$sex,$admidate,$illness,$past,$examination,$review,$disdate,$diagnosis,$complaint,$investigations,$course,$advice,$exam,$deptid,$dis_condtion,$files;
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
    
    function setillness($illness) {
        $this->illness = $illness;
    }
    function setpast($past) {
        $this->past = $past;
    }
    function setexamination($examination) {
        $this->examination = $examination;
    }
    function setreview($review) {
        $this->review = $review;
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

                    $patient_id = mysqli_real_escape_string($con,$this->patinetid);
                    $ref_no = mysqli_real_escape_string($con,$this->refno);
                    $patient_name = mysqli_real_escape_string($con,$this->fname);
                    $dept_id = mysqli_real_escape_string($con,$this->deptid);
                    $age = mysqli_real_escape_string($con,$this->age);
                    $sex = mysqli_real_escape_string($con,$this->sex);
                    $admission_date = mysqli_real_escape_string($con,$this->admidate);
                    $discharge_date = mysqli_real_escape_string($con,$this->disdate);
                    $diagnosis = mysqli_real_escape_string($con,$this->diagnosis);
                    $complaints = mysqli_real_escape_string($con,$this->complaint);
                    $investigation = mysqli_real_escape_string($con,$this->investigations);
                    $course_treatment = mysqli_real_escape_string($con,$this->course);
                    $advice_discharge = mysqli_real_escape_string($con,$this->advice);
                    $physical_examination = mysqli_real_escape_string($con,$this->exam);
                    $discharge_condition = mysqli_real_escape_string($con,$this->dis_condtion);
                    $discharge_file = mysqli_real_escape_string($con, isset($this->files) ? $this->files : '');
                    $history = mysqli_real_escape_string($con,$this->illness);
                    $past_history = mysqli_real_escape_string($con,$this->past);
                    $on_examination = mysqli_real_escape_string($con,$this->examination);
                    $review_on = mysqli_real_escape_string($con,$this->review);

                    $sql1="SELECT * FROM `discharge` WHERE `patient_id`='$patient_id'";
                    $res1=mysqli_query($con,$sql1);
                    if(mysqli_num_rows($res1)==0)
                    {
                    $sql="INSERT INTO `discharge`(`patient_id`, `ref_no`, `patient_name`,`dept_id`, `age`, `sex`, `admission_date`, `discharge_date`, `diagnosis`, `complaints`, `investigation`, `course_treatment`, `advice_discharge`,`physical_examination`,`created_by`,`discharge_condition`,`discharge_file`,`history`,`past_history`,`on_examination`,`review_on`) 
                    VALUES ('$patient_id','$ref_no','$patient_name','$dept_id','$age','$sex','$admission_date','$discharge_date','$diagnosis','$complaints','$investigation','$course_treatment','$advice_discharge','$physical_examination','$_SESSION[name]','$discharge_condition','$discharge_file','$history','$past_history','$on_examination','$review_on')";
                    $res=mysqli_query($con,$sql);
              
                     $date=date("Y-m-d H:i:s");
                     $sql1 = $con->prepare("INSERT INTO `query`(`sql_query`, `created_by`, `created_at`) VALUES (?, ?, ?)");
                     $sql1->bind_param("sss", $sql, $_SESSION['name'], $date);
                     $sql1->execute();
                     $sql1->close();

                        $query1="UPDATE central_ipd SET diagnosis='$diagnosis' WHERE ipd_no='$patient_id'";
                        mysqli_query($con,$query1);

                        $query="UPDATE `bed` SET `status`='active' WHERE id=(SELECT `bed_id` FROM `central_ipd` WHERE ipd_no='$patient_id') and `status`='occupied'";
                        mysqli_query($con,$query);
          
                        $query="UPDATE `central_ipd` SET discharge=1,final_bill=1 WHERE ipd_no='$patient_id'";
                        mysqli_query($con,$query);
                        $con->close();
          
                        return $res;
                    }
                    else{
                        echo "<script>alert('Patient Already Discharged Please check the IP Number')</script>";
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

                    $edit_id = (int)$id;
                    $patient_id = mysqli_real_escape_string($con,$this->patinetid);
                    $ref_no = mysqli_real_escape_string($con,$this->refno);
                    $discharge_date = mysqli_real_escape_string($con,$this->disdate);
                    $diagnosis = mysqli_real_escape_string($con,$this->diagnosis);
                    $complaints = mysqli_real_escape_string($con,$this->complaint);
                    $investigation = mysqli_real_escape_string($con,$this->investigations);
                    $course_treatment = mysqli_real_escape_string($con,$this->course);
                    $advice_discharge = mysqli_real_escape_string($con,$this->advice);
                    $physical_examination = mysqli_real_escape_string($con,$this->exam);
                    $discharge_condition = mysqli_real_escape_string($con,$this->dis_condtion);
                    $history = mysqli_real_escape_string($con,$this->illness);
                    $past_history = mysqli_real_escape_string($con,$this->past);
                    $on_examination = mysqli_real_escape_string($con,$this->examination);
                    $review_on = mysqli_real_escape_string($con,$this->review);
                      $file_update_sql = '';
                      if(isset($this->files) && strlen($this->files)>0) {
                          $discharge_file = mysqli_real_escape_string($con,$this->files);
                          $file_update_sql = ", `discharge_file`='$discharge_file'";
                      }

                    $query="update central_ipd set `diagnosis`='$diagnosis' where ipd_no='$patient_id'";
                    mysqli_query($con,$query);
          
                      $sql="UPDATE `discharge` SET `ref_no`='$ref_no',`discharge_date`='$discharge_date', `diagnosis`='$diagnosis', `complaints`='$complaints', `investigation`='$investigation', `course_treatment`='$course_treatment', `advice_discharge`='$advice_discharge',`physical_examination`='$physical_examination',`discharge_condition`='$discharge_condition',`history`='$history',`past_history`='$past_history',`on_examination`='$on_examination',`review_on`='$review_on' $file_update_sql WHERE id='$edit_id'";
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

    function ensurePatientDocumentsTable()
    {
        $newlogin = new userlogin;
        $con = $newlogin->dbconnect();

        $sql = "CREATE TABLE IF NOT EXISTS `patient_documents` (
            `id` INT(11) NOT NULL AUTO_INCREMENT,
            `patient_type` VARCHAR(10) NOT NULL DEFAULT 'IP',
            `patient_no` VARCHAR(100) NOT NULL,
            `patient_name` VARCHAR(255) NOT NULL,
            `document_name` VARCHAR(255) NOT NULL,
            `file_path` VARCHAR(500) NOT NULL,
            `uploaded_by` VARCHAR(100) NOT NULL,
            `uploaded_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `abha_linked` TINYINT(1) NOT NULL DEFAULT 0,
            `linkRequestId` VARCHAR(100) NULL DEFAULT NULL,
            PRIMARY KEY (`id`),
            INDEX `idx_patient_no` (`patient_no`),
            INDEX `idx_uploaded_at` (`uploaded_at`)
        ) ENGINE=InnoDB DEFAULT CHARSET=latin1";
        $res = mysqli_query($con, $sql);

        // Tables created before ABHA linking lack the columns that
        // optreatmentClass1::linkAbhaContextGroups() reads and writes back.
        $cols = array(
            'abha_linked'   => "TINYINT(1) NOT NULL DEFAULT 0",
            'linkRequestId' => "VARCHAR(100) NULL DEFAULT NULL",
        );
        foreach ($cols as $col => $def) {
            $chk = mysqli_query($con, "SHOW COLUMNS FROM `patient_documents` LIKE '$col'");
            if ($chk && mysqli_num_rows($chk) === 0) {
                mysqli_query($con, "ALTER TABLE `patient_documents` ADD COLUMN `$col` $def");
            }
        }

        mysqli_close($con);
        return $res;
    }

    // Returns the IP patient's name plus the central_opd.id whose ABHA the
    // document is linked under (central_ipd.opd_no -> central_opd).
    function getIpdPatientByNo($ipdNo)
    {
        $newlogin = new userlogin;
        $con = $newlogin->dbconnect();

        $stmt = $con->prepare("SELECT ci.`name`, ci.`opd_no`, co.`id` AS opd_id FROM `central_ipd` ci LEFT JOIN `central_opd` co ON co.`opd_no` = ci.`opd_no` WHERE ci.`ipd_no` = ? ORDER BY co.`id` DESC LIMIT 1");
        $stmt->bind_param('s', $ipdNo);
        $stmt->execute();
        $res = $stmt->get_result();
        $row = ($res && $res->num_rows > 0) ? $res->fetch_assoc() : null;

        $stmt->close();
        mysqli_close($con);
        return $row;
    }

    function insertPatientDocument($patientNo, $patientName, $documentName, $relativePath, $uploadedBy)
    {
        $newlogin = new userlogin;
        $con = $newlogin->dbconnect();

        $stmt = $con->prepare("INSERT INTO `patient_documents` (`patient_type`, `patient_no`, `patient_name`, `document_name`, `file_path`, `uploaded_by`) VALUES ('IP', ?, ?, ?, ?, ?)");
        $stmt->bind_param('sssss', $patientNo, $patientName, $documentName, $relativePath, $uploadedBy);

        $ok = $stmt->execute();
        $insertId = $ok ? (int) $stmt->insert_id : 0;

        $stmt->close();
        mysqli_close($con);

        return $ok ? $insertId : false;
    }

    function getRecentIpdPatients($limit = 500)
    {
        $limit = (int) $limit;
        if ($limit <= 0) {
            $limit = 500;
        }

        $newlogin = new userlogin;
        $con = $newlogin->dbconnect();
        $sql = "SELECT `ipd_no`, `name`, `mobile`, `admission_date` FROM `central_ipd` ORDER BY `id` DESC LIMIT $limit";
        $res = mysqli_query($con, $sql);

        $patients = array();
        if ($res) {
            while ($row = mysqli_fetch_assoc($res)) {
                $patients[] = $row;
            }
        }

        mysqli_close($con);
        return $patients;
    }

    function getPatientDocuments($patientNo = '', $limit = 100)
    {
        $newlogin = new userlogin;
        $con = $newlogin->dbconnect();

        $documents = array();
        if (strlen(trim((string) $patientNo)) > 0) {
            $stmt = $con->prepare("SELECT * FROM `patient_documents` WHERE `patient_no` = ? ORDER BY `id` DESC");
            $stmt->bind_param('s', $patientNo);
            $stmt->execute();
            $res = $stmt->get_result();

            while ($res && $row = $res->fetch_assoc()) {
                $documents[] = $row;
            }
            $stmt->close();
        } else {
            $limit = (int) $limit;
            if ($limit <= 0) {
                $limit = 100;
            }

            $sql = "SELECT * FROM `patient_documents` ORDER BY `id` DESC LIMIT $limit";
            $res = mysqli_query($con, $sql);
            if ($res) {
                while ($row = mysqli_fetch_assoc($res)) {
                    $documents[] = $row;
                }
            }
        }

        mysqli_close($con);
        return $documents;
    }
}
?>
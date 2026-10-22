<?php
require_once(__DIR__ . '/session_config.php');
include('dbconnect.php');


class master{

    function setbed($bed) {
        $this->bed = $bed;
      } 
      function setward($ward) {
        $this->ward = $ward;
      } 
      function setdepartment($department) {
        $this->department = $department;
      } 
      function setroga($roga){
        $this->roga = $roga;
      }
      function setprocedure($procedure){
        $this->procedure = $procedure;
      }
      function setsub_procedure($sub_procedure){
        $this->sub_procedure = $sub_procedure;
      }

      function setot_procedure($ot_procedure){
        $this->ot_procedure = $ot_procedure;
      }  
      function setot($ot){
        $this->ot = $ot;
      }
      
      function setprocetype($procetype)
      {
        $this->procetype = $procetype;
      }
      function setprocedure_medic($procedure_medic)
      {
        $this->procedure_medic = $procedure_medic;
      }
        function setdept($dept)
        {
            $this->dept=$dept;
        }
        function setbill_dept($billdept)
        {
            $this->billdept=$billdept;
        }
        function setdoctor($doctor)
        {
            $this->doctor=$doctor;
        }
        function setfrom($from)
        {
            $this->from=$from;
        }
        function setto($to)
        {
            $this->to=$to;
        }
        function setday($day)
        {
            $this->day=$day;
        }


        function setbills($bills)
        {
            $this->bills=$bills;
        }
        function setamount($amount)
        {
            $this->amount=$amount;
        }
        function setbed_no($bed_no)
        {
            $this->bed_no=$bed_no;
        }
        function setdept_type($dept_type)
        {
            $this->dept_type=$dept_type;
        }
        function setunit_no($unit_no)
        {
            $this->unit_no=$unit_no;
        }

        function setunit_type($unit_type) 
        {
        $this->unit_type = $unit_type;
       }
        function setname($name) 
        {
        $this->name = $name;
       }
        function setpercentage($percentage) 
        {
        $this->percentage = $percentage;
       }
       function setchargename($chargename) 
        {
        $this->chargename = $chargename;
       }
       function setlab_test($lab_test) 
        {
        $this->lab_test = $lab_test;
       }
       function setcharge_type($charge_type) 
        {
        $this->charge_type = $charge_type;
       }
       function setdescription($description) 
        {
        $this->description = $description;
       }
       function setcategory($category) 
        {
        $this->category = $category;
       }
        function setpatho_category($patho_category) 
        {
        $this->patho_category = $patho_category;
       }
       function setpara_name($para_name) 
        {
        $this->para_name = $para_name;
       }
       function setrange($range) 
        {
        $this->range = $range;
       }

function setdob($dob)
{
    $this->dob=$dob;
}
function setfname($fname)
{
    $this->fname=$fname;
}
function setlname($lname)
{
    $this->lname=$lname;
}
function setabbname($abbname)
{
    $this->abbname=$abbname;
}
function setsex($sex)
{
    $this->sex=$sex;
}
function setdoctor_type($doctor_type)
{
    $this->doctor_type=$doctor_type;
}
function setspeciality($speciality)
{
    $this->speciality=$speciality;
}
function setschedule_type($schedule_type)
{
    $this->schedule_type=$schedule_type;
}
function setregistration_no($registration_no)
{
    $this->registration_no=$registration_no;
}
function setaadhar($aadhar)
{
    $this->aadhar=$aadhar;
}
function setpancard($pancard)
{
    $this->pancard=$pancard;
}
function setqualification($qualification)
{
    $this->qualification=$qualification;
}
function setcontact($contact)
{
    $this->contact=$contact;
}
function setemail($email)
{
    $this->email=$email;
}
function setvisit($visit)
{
    $this->visit=$visit;
}
function setresidence($residence)
{
    $this->residence=$residence;
}
function setcountry($country)
{
    $this->country=$country;
}
function setstate($state)
{
    $this->state=$state;
}
function setcity($city)
{
    $this->city=$city;
}
function setpin($pin)
{
    $this->pin=$pin;
}
function setclinic($clinic)
{
    $this->clinic=$clinic;
}
function setc_country($c_country)
{
    $this->c_country=$c_country;
}
function setc_state($c_state)
{
    $this->c_state=$c_state;
}
function setc_city($c_city)
{
    $this->c_city=$c_city;
}
function setc_pin($c_pin)
{
    $this->c_pin=$c_pin;
}
function setc_tel($c_tel)
{
    $this->c_tel=$c_tel;
}
function setbank_type($bank_type)
{
    $this->bank_type=$bank_type;
}
function setaccount_no($account_no)
{
    $this->account_no=$account_no;
}
function setifsc($ifsc)
{
    $this->ifsc=$ifsc;
}
function setConsultation($Consultation)
{
    $this->consultation=$Consultation;
}

function seticd($icd)
{
    $this->icd=$icd;
}

function setdetails($details)
{
    $this->details=$details;
}


function setdiag_name($diag_name)
{
    $this->diag_name=$diag_name;
}

function setdih($dih)
{
    $this->dih=$dih;
}

function setmin_age($min_age)
{
    $this->min_age=$min_age;
}

function setmax_age($max_age)
{
    $this->max_age=$max_age;
}
function setgender($gender)
{
    $this->gender=$gender;
}

    function selectDept()
    {
        $newlogin = new userlogin;                  
        $con=$newlogin->dbconnect();
        $sql="SELECT * FROM `department` where status='active' order by dept_name ";
        $res=mysqli_query($con,$sql);
        return $res;
    }
    function selectDept1()
    {
        $newlogin = new userlogin;                  
        $con=$newlogin->dbconnect();
        $sql="SELECT * FROM `department` order by dept_name ";
        $res=mysqli_query($con,$sql);
        return $res;
    }
    
     function selectDoctor()
    {
        $newlogin = new userlogin;                  
        $con=$newlogin->dbconnect();
        $sql="SELECT * FROM `doctor` WHERE `status`='active' order by name";
        $res=mysqli_query($con,$sql);
        return $res;
    }
    
    
    function insertBed()
    {
        $newlogin = new userlogin;                  
        $con=$newlogin->dbconnect();
        $sql="INSERT INTO `bed`(`bed_number`, `department`, `ward`, `status`) VALUES ('$this->bed', '$this->department', '$this->ward','active')";
        $res=mysqli_query($con,$sql);
        
              $date=date("Y-m-d H:i:s");
      $sql1 = $con->prepare("INSERT INTO `query`(`sql_query`, `created_by`, `created_at`) VALUES (?, ?, ?)");
        $sql1->bind_param("sss", $sql, $_SESSION['name'], $date);
        $sql1->execute();
$sql1->close();
$con->close();

        return $res;
    } 
    
     function updateBed($id)
    {
        $newlogin = new userlogin;                  
        $con=$newlogin->dbconnect();
        $sql="UPDATE `bed`SET `bed_number`='$this->bed', `department`='$this->department', `ward`='$this->ward' WHERE id=$id";
        $res=mysqli_query($con,$sql);
      
            $date=date("Y-m-d H:i:s");
      $sql1 = $con->prepare("INSERT INTO `query`(`sql_query`, `created_by`, `created_at`) VALUES (?, ?, ?)");
        $sql1->bind_param("sss", $sql, $_SESSION['name'], $date);
        $sql1->execute();
$sql1->close();
$con->close();

        return $res;
    } 

    function getBed()
    {
        $newlogin = new userlogin;                  
        $con=$newlogin->dbconnect();
        $sql="SELECT b.*,d.dept_name FROM `bed` b LEFT JOIN `department` d on d.id=b.department WHERE b.`status` !='inactive'";
        $res=mysqli_query($con,$sql);
        return $res;
    }
    function getBedId($id)
    {
        $newlogin = new userlogin;                  
        $con=$newlogin->dbconnect();
        $sql="SELECT b.* FROM `bed` b  WHERE b.id=$id";
        $res=mysqli_query($con,$sql);
        return $res;
    }
    function getRoga()
    {
        $newlogin = new userlogin;                  
        $con=$newlogin->dbconnect();
        $sql="SELECT * FROM roga WHERE `status`='active'";
        $res=mysqli_query($con,$sql);
        return $res;
    }
     function getRogaId($id)
    {
        $newlogin = new userlogin;                  
        $con=$newlogin->dbconnect();
        $sql="SELECT * FROM roga WHERE id=$id";
        $res=mysqli_query($con,$sql);
        return $res;
    }
     function getDiagEdit($id)
    {
        $newlogin = new userlogin;                  
        $con=$newlogin->dbconnect();
        $sql="SELECT * FROM temp_diagnosis WHERE id=$id";
        $res=mysqli_query($con,$sql);
        return $res;
    }
     function getDiagnosis()
    {
        $newlogin = new userlogin;                  
        $con=$newlogin->dbconnect();
        $sql="SELECT t.*,d.dept_name FROM temp_diagnosis t,department d where d.id=dept order by d.id";
        $res=mysqli_query($con,$sql);
        return $res;
    }
    function insertRoga()
    {
      $newlogin = new userlogin;                  
      $con=$newlogin->dbconnect();
      $sql="INSERT INTO `roga`(`roga_name`) VALUES ('$this->roga')";
      // echo $sql; exit;
      $res=mysqli_query($con,$sql);
      
            $date=date("Y-m-d H:i:s");
      $sql1 = $con->prepare("INSERT INTO `query`(`sql_query`, `created_by`, `created_at`) VALUES (?, ?, ?)");
        $sql1->bind_param("sss", $sql, $_SESSION['name'], $date);
        $sql1->execute();
$sql1->close();
$con->close();

      return $res;
    }
    function updateRoga($id)
    {
      $newlogin = new userlogin;                  
      $con=$newlogin->dbconnect();
      $sql="UPDATE `roga` SET `roga_name`='$this->roga' WHERE id=$id";
    //   echo $sql; exit;
      $res=mysqli_query($con,$sql);
       
             $date=date("Y-m-d H:i:s");
      $sql1 = $con->prepare("INSERT INTO `query`(`sql_query`, `created_by`, `created_at`) VALUES (?, ?, ?)");
        $sql1->bind_param("sss", $sql, $_SESSION['name'], $date);
        $sql1->execute();
$sql1->close();
$con->close();

      return $res;
    }

    function getProcedure()
    {
        $newlogin = new userlogin;                  
        $con=$newlogin->dbconnect();
        $sql="SELECT * FROM `procedure` WHERE `status`='active'";
        $res=mysqli_query($con,$sql);
        return $res;
    }
    function getProce($id)
    {
        $newlogin = new userlogin;                  
        $con=$newlogin->dbconnect();
        $sql="SELECT * FROM `procedure` WHERE `id`=$id";
        $res=mysqli_query($con,$sql);
        return $res;
    }
    function insertProcedure()
    {
      $newlogin = new userlogin;                  
      $con=$newlogin->dbconnect();
      $sql="INSERT INTO `procedure`(`procedure_name`) VALUES ('$this->procedure')";
      // echo $sql; exit;
      $res=mysqli_query($con,$sql);
   
         $date=date("Y-m-d H:i:s");
      $sql1 = $con->prepare("INSERT INTO `query`(`sql_query`, `created_by`, `created_at`) VALUES (?, ?, ?)");
        $sql1->bind_param("sss", $sql, $_SESSION['name'], $date);
        $sql1->execute();
$sql1->close();
$con->close();

      return $res;
    }
    function updateProcedure($id)
    {
      $newlogin = new userlogin;                  
      $con=$newlogin->dbconnect();
      $sql="UPDATE `procedure` SET `procedure_name`='$this->procedure' WHERE id=$id";
      // echo $sql; exit;
      $res=mysqli_query($con,$sql);
      
            $date=date("Y-m-d H:i:s");
      $sql1 = $con->prepare("INSERT INTO `query`(`sql_query`, `created_by`, `created_at`) VALUES (?, ?, ?)");
        $sql1->bind_param("sss", $sql, $_SESSION['name'], $date);
        $sql1->execute();
$sql1->close();
$con->close();

      return $res;
    }

    function getSubProcedure()
    {
        $newlogin = new userlogin;                  
        $con=$newlogin->dbconnect();
        $sql="SELECT s.subprocedure_name,p.procedure_name,s.id FROM `sub_procedure` s,`procedure` p WHERE s.`status`='active' and s.procedure_id=p.id";
        $res=mysqli_query($con,$sql);
        return $res;
    }
    
     function getSubProcedureId($id)
    {
        $newlogin = new userlogin;                  
        $con=$newlogin->dbconnect();
        $sql="SELECT s.subprocedure_name,p.procedure_name,p.id FROM `sub_procedure` s,`procedure` p WHERE s.`id`='$id' and s.procedure_id=p.id";
        // echo $sql;
        $res=mysqli_query($con,$sql);
        return $res;
    }
    
    function insertSubProcedure()
    {
      $newlogin = new userlogin;                  
      $con=$newlogin->dbconnect();
      $sql="INSERT INTO `sub_procedure`(`procedure_id`,`subprocedure_name`) VALUES ('$this->procedure','$this->sub_procedure')";
      // echo $sql; exit;
      $res=mysqli_query($con,$sql);
      
            $date=date("Y-m-d H:i:s");
      $sql1 = $con->prepare("INSERT INTO `query`(`sql_query`, `created_by`, `created_at`) VALUES (?, ?, ?)");
        $sql1->bind_param("sss", $sql, $_SESSION['name'], $date);
        $sql1->execute();
$sql1->close();
$con->close();

      return $res;
    }
     function updateSubProcedure($id)
    {
      $newlogin = new userlogin;                  
      $con=$newlogin->dbconnect();
      $sql="UPDATE `sub_procedure` SET `procedure_id`='$this->procedure',`subprocedure_name`='$this->sub_procedure' WHERE id=$id";
    //   echo $sql; exit;
      $res=mysqli_query($con,$sql);
      
            $date=date("Y-m-d H:i:s");
      $sql1 = $con->prepare("INSERT INTO `query`(`sql_query`, `created_by`, `created_at`) VALUES (?, ?, ?)");
        $sql1->bind_param("sss", $sql, $_SESSION['name'], $date);
        $sql1->execute();
$sql1->close();
$con->close();

      return $res;
    }
    function getOt_type()
    {
        $newlogin = new userlogin;                  
        $con=$newlogin->dbconnect();
        $sql="SELECT * FROM `ot_type` WHERE `status`='active'";
        $res=mysqli_query($con,$sql);
        return $res;
    }
    function getOTId($id)
    {
        $newlogin = new userlogin;                  
        $con=$newlogin->dbconnect();
        $sql="SELECT * FROM `ot_type` WHERE `id`='$id'";
        $res=mysqli_query($con,$sql);
        return $res;
    }
    function insertOt_type()
    {
      $newlogin = new userlogin;                  
      $con=$newlogin->dbconnect();
      $sql="INSERT INTO `ot_type`(`ot_name`) VALUES ('$this->ot')";
      // echo $sql; exit;
      $res=mysqli_query($con,$sql);
       
             $date=date("Y-m-d H:i:s");
      $sql1 = $con->prepare("INSERT INTO `query`(`sql_query`, `created_by`, `created_at`) VALUES (?, ?, ?)");
        $sql1->bind_param("sss", $sql, $_SESSION['name'], $date);
        $sql1->execute();
$sql1->close();
$con->close();

      return $res;
    }
    function updateOt_type($id)
    {
      $newlogin = new userlogin;                  
      $con=$newlogin->dbconnect();
      $sql="UPDATE `ot_type` SET `ot_name`='$this->ot' WHERE id=$id";
      // echo $sql; exit;
      $res=mysqli_query($con,$sql);
      
            $date=date("Y-m-d H:i:s");
      $sql1 = $con->prepare("INSERT INTO `query`(`sql_query`, `created_by`, `created_at`) VALUES (?, ?, ?)");
        $sql1->bind_param("sss", $sql, $_SESSION['name'], $date);
        $sql1->execute();
$sql1->close();
$con->close();

      return $res;
    }
    function insertOt_procedure()
    {
      $newlogin = new userlogin;                  
      $con=$newlogin->dbconnect();
      $sql="INSERT INTO `ot_procedures`(`ot_procedure`, `ot_id`) VALUES ('$this->ot_procedure','$this->ot')";
      // echo $sql; exit;
      $res=mysqli_query($con,$sql);
      
            $date=date("Y-m-d H:i:s");
      $sql1 = $con->prepare("INSERT INTO `query`(`sql_query`, `created_by`, `created_at`) VALUES (?, ?, ?)");
        $sql1->bind_param("sss", $sql, $_SESSION['name'], $date);
        $sql1->execute();
$sql1->close();
$con->close();

      return $res;
    }
    
    function updateOt_procedure($id)
    {
      $newlogin = new userlogin;                  
      $con=$newlogin->dbconnect();
      $sql="UPDATE `ot_procedures` SET `ot_procedure`='$this->ot_procedure', `ot_id`='$this->ot' WHERE id=$id";
    //   echo $sql; exit;
      $res=mysqli_query($con,$sql);
       
             $date=date("Y-m-d H:i:s");
      $sql1 = $con->prepare("INSERT INTO `query`(`sql_query`, `created_by`, `created_at`) VALUES (?, ?, ?)");
        $sql1->bind_param("sss", $sql, $_SESSION['name'], $date);
        $sql1->execute();
$sql1->close();
$con->close();

      return $res;
    }
    
    
    function insertProcedure_medic()
    {
      $newlogin = new userlogin;                  
      $con=$newlogin->dbconnect();
      $sql="INSERT INTO `procedure_medicine`(`procedure`, `medicine`) VALUES ('$this->procetype','$this->procedure_medic')";
      // echo $sql; exit;
      $res=mysqli_query($con,$sql);
      
            $date=date("Y-m-d H:i:s");
      $sql1 = $con->prepare("INSERT INTO `query`(`sql_query`, `created_by`, `created_at`) VALUES (?, ?, ?)");
        $sql1->bind_param("sss", $sql, $_SESSION['name'], $date);
        $sql1->execute();
$sql1->close();
$con->close();

      return $res;
    }
    function getProceMedic()
    {
      $newlogin = new userlogin;                  
      $con=$newlogin->dbconnect();
      $sql="SELECT pm.id,pm.medicine,p.procedure_name from `procedure` p, `procedure_medicine` pm WHERE p.id=pm.`procedure` ";
      // echo $sql; 
      $res=mysqli_query($con,$sql);
      return $res;
    }
    function getOTProcedure()
    {
      $newlogin = new userlogin;                  
      $con=$newlogin->dbconnect();
      $sql="SELECT pm.id,pm.ot_procedure,p.ot_name from `ot_type` p, `ot_procedures` pm WHERE p.id=pm.`ot_id` ";
      // echo $sql; 
      $res=mysqli_query($con,$sql);
      return $res;
    }
    function getOTproce($id)
    {
      $newlogin = new userlogin;                  
      $con=$newlogin->dbconnect();
      $sql="SELECT p.id,pm.ot_procedure,p.ot_name from `ot_type` p, `ot_procedures` pm WHERE p.id=pm.`ot_id` and pm.id=$id ";
    //   echo $sql; 
      $res=mysqli_query($con,$sql);
      return $res;
    }
    function insertTimeTable()
    {
        $newlogin = new userlogin;                  
      $con=$newlogin->dbconnect();
      $count= count($this->doctor);
       $date=date("Y-m-d H:i:s");
      for($i=0;$i<$count;$i++)
      {
           $de= $this->dept[$i];
            $do=$this->doctor[$i];
           $fr= $this->from[$i];
            $to=$this->to[$i];
      $sql="INSERT INTO `staff_timetable`(`day`, `dept_id`, `doctor_id`, `start_time`, `end_time`) 
      VALUES ('$this->day','$de','$do','$fr','$to')";
    //   echo $sql;
      $res=mysqli_query($con,$sql);
      
          $sql1 = $con->prepare("INSERT INTO `query`(`sql_query`, `created_by`, `created_at`) VALUES (?, ?, ?)");
        $sql1->bind_param("sss", $sql, $_SESSION['name'], $date);
        $sql1->execute();
$sql1->close();

      }
     
$con->close();
    //   exit;
      return $res;
      
    }
      function getTimeTable($day)
    {
      $newlogin = new userlogin;                  
      $con=$newlogin->dbconnect();
      $sql="SELECT st.*,d.dept_name,u.name,u.last_name FROM staff_timetable st,department d,doctor u WHERE st.day='$day' and d.id=st.dept_id and st.doctor_id=u.id";
    //   echo $sql; 
      $res=mysqli_query($con,$sql);
      return $res;
    }



    function insertBills()
    {
        $newlogin = new userlogin;                  
        $con=$newlogin->dbconnect();
        $sql="INSERT INTO `bills` (`name`,`amount`) VALUES ('$this->bills','$this->amount') ";
        // echo $sql;
        // exit;
        $res=mysqli_query($con,$sql);
         $date=date("Y-m-d H:i:s");
        
        $sql1 = $con->prepare("INSERT INTO `query`(`sql_query`, `created_by`, `created_at`) VALUES (?, ?, ?)");
        $sql1->bind_param("sss", $sql, $_SESSION['name'], $date);
        $sql1->execute();
$sql1->close();
$con->close();

        return $res;
    }
    function updateBills($id)
    {
        $newlogin = new userlogin;                  
        $con=$newlogin->dbconnect();
        $sql="update `bills` set `name`='$this->bills',`amount`='$this->amount'  where id=$id";
        // echo $sql;
        // exit;
        $res=mysqli_query($con,$sql);
         $date=date("Y-m-d H:i:s");
         
         $sql1 = $con->prepare("INSERT INTO `query`(`sql_query`, `created_by`, `created_at`) VALUES (?, ?, ?)");
        $sql1->bind_param("sss", $sql, $_SESSION['name'], $date);
        $sql1->execute();
$sql1->close();
$con->close();

        return $res;
    }
    
     function insertdepartment()
    {
        $newlogin = new userlogin;                  
        $con=$newlogin->dbconnect();
        $sql="INSERT INTO `department` (`dept_name`, `bed`, `unit`, `dept_type`) VALUES ('$this->dept','$this->bed_no','$this->unit_no','$this->dept_type') ";
        $res=mysqli_query($con,$sql);
         $date=date("Y-m-d H:i:s");
        
        $sql1 = $con->prepare("INSERT INTO `query`(`sql_query`, `created_by`, `created_at`) VALUES (?, ?, ?)");
        $sql1->bind_param("sss", $sql, $_SESSION['name'], $date);
        $sql1->execute();
$sql1->close();
$con->close();

        return $res;
    }
    
  function updateDepartment($id)
  {
       $newlogin = new userlogin;                  
        $con=$newlogin->dbconnect();
        $sql="update `department` set `dept_name`='$this->dept', `bed`='$this->bed_no', `unit`='$this->unit_no', `dept_type`='$this->dept_type' where id=$id";
        $res=mysqli_query($con,$sql);
         $date=date("Y-m-d H:i:s");
       
       $sql1 = $con->prepare("INSERT INTO `query`(`sql_query`, `created_by`, `created_at`) VALUES (?, ?, ?)");
        $sql1->bind_param("sss", $sql, $_SESSION['name'], $date);
        $sql1->execute();
$sql1->close();
$con->close();

        return $res;
  }
    function getBills()
    {
        $newlogin = new userlogin;                  
        $con=$newlogin->dbconnect();
        $sql="SELECT * FROM bills ";
        $res=mysqli_query($con,$sql);
        return $res;
    }
    
    function getBedEdit($id)
    {
        $newlogin = new userlogin;                  
        $con=$newlogin->dbconnect();
        $sql="SELECT * FROM bills where id=$id";
        $res=mysqli_query($con,$sql);
        return $res;
    }
    
    function getDeptId($id)
    {
         $newlogin = new userlogin;                  
        $con=$newlogin->dbconnect();
        $sql="SELECT * FROM department where id=$id";
        $res=mysqli_query($con,$sql);
        return $res;
    }
      function insertUnit()
      {
          $newlogin = new userlogin;
          $con=$newlogin->dbconnect();
          $sql="insert into unit(`unit_name`) values ('$this->unit_type')";
          $res=mysqli_query($con,$sql);
           $date=date("Y-m-d H:i:s");
        
        $sql1 = $con->prepare("INSERT INTO `query`(`sql_query`, `created_by`, `created_at`) VALUES (?, ?, ?)");
        $sql1->bind_param("sss", $sql, $_SESSION['name'], $date);
        $sql1->execute();
$sql1->close();
$con->close();

          return $res;
          
      }
       function getUnit()
    {
         $newlogin = new userlogin;                  
        $con=$newlogin->dbconnect();
        $sql="SELECT * FROM unit";
        $res=mysqli_query($con,$sql);
        return $res;
    }
    function insertTax()
      {
          $newlogin = new userlogin;
          $con=$newlogin->dbconnect();
          $sql="insert into tax(`name`,`percentage`) values ('$this->name','$this->percentage')";
          $res=mysqli_query($con,$sql);
           $date=date("Y-m-d H:i:s");
        
        $sql1 = $con->prepare("INSERT INTO `query`(`sql_query`, `created_by`, `created_at`) VALUES (?, ?, ?)");
        $sql1->bind_param("sss", $sql, $_SESSION['name'], $date);
        $sql1->execute();
$sql1->close();
$con->close();

          return $res;
          
      }
       function getTax()
    {
         $newlogin = new userlogin;                  
        $con=$newlogin->dbconnect();
        $sql="SELECT * FROM tax";
        $res=mysqli_query($con,$sql);
        return $res;
    }
    
    function insertChargeType()
     {
         $newlogin = new userlogin;                  
        $con=$newlogin->dbconnect();
        $appointment=$opd=$ipd=$pathology=$radiology=$blood_blank=$ambulance=0;
        if(in_array("Appointment", $this->lab_test))
          {
          	$appointment=1;
            
          }
         if(in_array("OPD", $this->lab_test))
          {
          	$opd=1;
            
          }
          if(in_array("IPD", $this->lab_test))
          {
          	$ipd=1;
            
          }
          if(in_array("Pathology", $this->lab_test))
          {
          	$pathology=1;
            
          }
          if(in_array("Radiology", $this->lab_test))
          {
          	$radiology=1;
            
          }
          if(in_array("Blood Bank", $this->lab_test))
          {
          	$blood_blank=1;
            
          }
          if(in_array("Ambulance", $this->lab_test))
          {
          	$ambulance=1;
            
          }
          
        
        
        $sql="INSERT INTO `charge_type` ( `charge_name`, `appointment`, `opd`, `ipd`, `pathology`, `radiology`, `blood_blank`, `ambulance`) VALUES ('$this->chargename', '$appointment', '$opd', '$ipd', '$pathology', '$radiology', '$blood_blank', '$ambulance')";
        $res=mysqli_query($con,$sql);
         $date=date("Y-m-d H:i:s");
          
          $sql1 = $con->prepare("INSERT INTO `query`(`sql_query`, `created_by`, `created_at`) VALUES (?, ?, ?)");
        $sql1->bind_param("sss", $sql, $_SESSION['name'], $date);
        $sql1->execute();
$sql1->close();
$con->close();

        return $res;
    }
      function getChargeType()
    {
         $newlogin = new userlogin;                  
        $con=$newlogin->dbconnect();
        $sql="SELECT * FROM charge_type";
        $res=mysqli_query($con,$sql);
        return $res;
    }
      function insertCharge()
    {
         $newlogin = new userlogin;                  
        $con=$newlogin->dbconnect();
        $sql="insert into charge(name,charge_type,description) values ('$this->chargename','$this->charge_type','$this->description')";
        $res=mysqli_query($con,$sql);
         $date=date("Y-m-d H:i:s");
      
      $sql1 = $con->prepare("INSERT INTO `query`(`sql_query`, `created_by`, `created_at`) VALUES (?, ?, ?)");
        $sql1->bind_param("sss", $sql, $_SESSION['name'], $date);
        $sql1->execute();
$sql1->close();
$con->close();

        return $res;
    }
    function getCharge()
    {
        $newlogin = new userlogin;                  
        $con=$newlogin->dbconnect();
        $sql="SELECT c.*,ct.charge_name FROM charge c,charge_type ct where c.charge_type=ct.id";
        $res=mysqli_query($con,$sql);
        return $res;
        
    }
    function insertRadioCat()
    {
        $newlogin = new userlogin;                  
        $con=$newlogin->dbconnect();
        $sql="insert into radiology_category(`category`) values ('$this->category')";
        $res=mysqli_query($con,$sql);
         $date=date("Y-m-d H:i:s");
         
         $sql1 = $con->prepare("INSERT INTO `query`(`sql_query`, `created_by`, `created_at`) VALUES (?, ?, ?)");
        $sql1->bind_param("sss", $sql, $_SESSION['name'], $date);
        $sql1->execute();
$sql1->close();
$con->close();

        return $res;
    }
    
      function getRadioCat()
    {
         $newlogin = new userlogin;                  
        $con=$newlogin->dbconnect();
        $sql="SELECT * FROM radiology_category";
        $res=mysqli_query($con,$sql);
        return $res;
    }
    
    function insertRadioUnit()
    {
        $newlogin = new userlogin;                  
        $con=$newlogin->dbconnect();
        $sql="insert into radiology_unit(`unit`) values ('$this->unit_type')";
        $res=mysqli_query($con,$sql);
         $date=date("Y-m-d H:i:s");
         
         $sql1 = $con->prepare("INSERT INTO `query`(`sql_query`, `created_by`, `created_at`) VALUES (?, ?, ?)");
        $sql1->bind_param("sss", $sql, $_SESSION['name'], $date);
        $sql1->execute();
$sql1->close();
$con->close();

        return $res;
    }
     function getRadioUnit()
    {
         $newlogin = new userlogin;                  
        $con=$newlogin->dbconnect();
        $sql="SELECT * FROM radiology_unit";
        $res=mysqli_query($con,$sql);
        return $res;
    }
     function insertRadioPara()
    {
        $newlogin = new userlogin;                  
        $con=$newlogin->dbconnect();
        $sql="insert into radiology_parameter(`parameter`,`range`,`unit`) values ('$this->para_name','$this->range','$this->unit_type')";
        $res=mysqli_query($con,$sql);
         $date=date("Y-m-d H:i:s");
         
         $sql1 = $con->prepare("INSERT INTO `query`(`sql_query`, `created_by`, `created_at`) VALUES (?, ?, ?)");
        $sql1->bind_param("sss", $sql, $_SESSION['name'], $date);
        $sql1->execute();
$sql1->close();
$con->close();

        return $res;
    }
     function getRadioPara()
    {
         $newlogin = new userlogin;                  
        $con=$newlogin->dbconnect();
        $sql="SELECT rp.*,ru.unit as para_unit FROM radiology_parameter rp, radiology_unit ru where rp.unit=ru.id";
        $res=mysqli_query($con,$sql);
        return $res;
    }
     function insertPathoCat()
    {
        $newlogin = new userlogin;                  
        $con=$newlogin->dbconnect();
        $sql="insert into pathology_category(`category`) values ('$this->patho_category')";
        $res=mysqli_query($con,$sql);
         $date=date("Y-m-d H:i:s");
       
       $sql1 = $con->prepare("INSERT INTO `query`(`sql_query`, `created_by`, `created_at`) VALUES (?, ?, ?)");
        $sql1->bind_param("sss", $sql, $_SESSION['name'], $date);
        $sql1->execute();
$sql1->close();
$con->close();

        return $res;
    }
     function getPathoCat()
    {
         $newlogin = new userlogin;                  
        $con=$newlogin->dbconnect();
        $sql="SELECT * FROM pathology_category";
        $res=mysqli_query($con,$sql);
        
        return $res;
    }
       function insertPathoUnit()
    {
        $newlogin = new userlogin;                  
        $con=$newlogin->dbconnect();
        $sql="insert into pathology_unit(`unit`) values ('$this->unit_type')";
        $res=mysqli_query($con,$sql);
         $date=date("Y-m-d H:i:s");
    
    $sql1 = $con->prepare("INSERT INTO `query`(`sql_query`, `created_by`, `created_at`) VALUES (?, ?, ?)");
        $sql1->bind_param("sss", $sql, $_SESSION['name'], $date);
        $sql1->execute();
$sql1->close();
$con->close();

        return $res;
    }
     function getPathoUnit()
    {
         $newlogin = new userlogin;                  
        $con=$newlogin->dbconnect();
        $sql="SELECT * FROM pathology_unit";
        $res=mysqli_query($con,$sql);
        return $res;
    }
     function insertPathoPara()
    {
        $newlogin = new userlogin;                  
        $con=$newlogin->dbconnect();
        $sql="insert into pathology_parameter(`parameter`,`range`,`unit`) values ('$this->para_name','$this->range','$this->unit_type')";
        $res=mysqli_query($con,$sql);
         $date=date("Y-m-d H:i:s");
       
       $sql1 = $con->prepare("INSERT INTO `query`(`sql_query`, `created_by`, `created_at`) VALUES (?, ?, ?)");
        $sql1->bind_param("sss", $sql, $_SESSION['name'], $date);
        $sql1->execute();
$sql1->close();
$con->close();

        return $res;
    }
     function getPathoPara()
    {
         $newlogin = new userlogin;                  
        $con=$newlogin->dbconnect();
        $sql="SELECT pp.*,pu.unit as patho_unit FROM pathology_parameter pp, pathology_unit pu where pp.unit=pu.id";
        $res=mysqli_query($con,$sql);
        return $res;
    }
    function getDoctorRegistration()
    {
        $newlogin = new userlogin;                  
        $con=$newlogin->dbconnect();
        $sql="select u.*,(select d.dept_name FROM department d WHERE d.id=u.department) as dept_name,(select d.dept_name FROM department d WHERE d.id=u.billing_dept) as bill_dept from doctor u where u.role='Doctor' ";
        // echo $sql;
        $res=mysqli_query($con,$sql);
        return $res;
    }
    function getDoctor($id)
    {
        $newlogin = new userlogin;                  
        $con=$newlogin->dbconnect();
        $sql="select u.* from doctor u where u.id='$id' ";
        // echo $sql;
        $res=mysqli_query($con,$sql);
        return $res;
    }
    function insertDoctorDetails()
    {
        $newlogin = new userlogin;                  
        $con=$newlogin->dbconnect();
        $sql="INSERT INTO `doctor`( `name`, `last_name`,  `mobile`, `department`,`billing_dept`, `role`, `qualification`, `email`, `gender`, `dob`,  `status`, `abbreviated_name`, `doctor_type`, `sepeciality`, `schedule_type`, `registration_no`, `aadhar_no`, `pancard`, `visiting_time`, `residence_addr`, `country`, `state`, `city`, `pincode`, `clinic_address`, `clinic_country`, `clinic_state`, `clinic_city`, `clinic_pincode`, `clinic_phone`, `account_type`, `account_no`, `ifsc_no`,`consultation` ) 
        VALUES ('$this->fname','$this->lname','$this->contact','$this->dept','$this->billdept','Doctor','$this->qualification','$this->email','$this->sex','$this->dob','active','$this->abbname','$this->doctor_type','$this->speciality','$this->schedule_type','$this->registration_no','$this->aadhar','$this->pancard','$this->visit','$this->residence','$this->country','$this->state','$this->city','$this->pin','$this->clinic','$this->c_country','$this->c_state','$this->c_city','$this->c_pin','$this->c_tel','$this->bank_type','$this->account_no','$this->ifsc','$this->consultation')";
        $res=mysqli_query($con,$sql);
         $date=date("Y-m-d H:i:s");
         
         $sql1 = $con->prepare("INSERT INTO `query`(`sql_query`, `created_by`, `created_at`) VALUES (?, ?, ?)");
        $sql1->bind_param("sss", $sql, $_SESSION['name'], $date);
        $sql1->execute();
$sql1->close();
$con->close();

        return $res;
    }
    function updateDoctorDetails($id)
    {
        $newlogin = new userlogin;                  
        $con=$newlogin->dbconnect();
        $sql="UPDATE `doctor` SET `name`='$this->fname',`last_name`='$this->lname',`mobile`='$this->contact',`department`='$this->dept',`billing_dept`='$this->billdept',`role`='Doctor',`qualification`='$this->qualification',`email`='$this->email',`gender`='$this->sex',`dob`='$this->dob',`status`='active',`abbreviated_name`='$this->abbname',`doctor_type`='$this->doctor_type',`sepeciality`='$this->speciality',`schedule_type`='$this->schedule_type',`registration_no`='$this->registration_no',`aadhar_no`='$this->aadhar',`pancard`='$this->pancard',`visiting_time`='$this->visit',`residence_addr`='$this->residence',`country`='$this->country',`state`='$this->state',`city`='$this->city',`pincode`='$this->pin',`clinic_address`='$this->clinic',`clinic_country`='$this->c_country',`clinic_state`='$this->c_state',`clinic_city`='$this->c_city',`clinic_pincode`='$this->c_pin',`clinic_phone`='$this->c_tel',`account_type`='$this->bank_type',`account_no`='$this->account_no',`ifsc_no`='$this->ifsc',`consultation`='$this->consultation' where id=$id";
        $res=mysqli_query($con,$sql);
         $date=date("Y-m-d H:i:s");
        
        $sql1 = $con->prepare("INSERT INTO `query`(`sql_query`, `created_by`, `created_at`) VALUES (?, ?, ?)");
        $sql1->bind_param("sss", $sql, $_SESSION['name'], $date);
        $sql1->execute();
$sql1->close();
$con->close();

        return $res;
    }
    function statusUser($id,$stat)
{
    $newlogin = new userlogin;                  
  $con=$newlogin->dbconnect();
  $sql="UPDATE `doctor` set status='$stat' where id='$id'";
  $res=mysqli_query($con,$sql);
   $date=date("Y-m-d H:i:s");

$sql1 = $con->prepare("INSERT INTO `query`(`sql_query`, `created_by`, `created_at`) VALUES (?, ?, ?)");
        $sql1->bind_param("sss", $sql, $_SESSION['name'], $date);
        $sql1->execute();
$sql1->close();
$con->close();

  return $res;
}

            function insertdiagnosis_icd()
            {
                $newlogin = new userlogin;                  
                $con=$newlogin->dbconnect();
                $sql="INSERT INTO `icd_codes` ( `code`, `details`) VALUES ('$this->icd', '$this->details');";
                $res=mysqli_query($con,$sql);
                 $date=date("Y-m-d H:i:s");
                
                $sql1 = $con->prepare("INSERT INTO `query`(`sql_query`, `created_by`, `created_at`) VALUES (?, ?, ?)");
                $sql1->bind_param("sss", $sql, $_SESSION['name'], $date);
                $sql1->execute();
        $sql1->close();
        $con->close();
        
                return $res;
            }
            
            function getIcdDiagEdit($id)
            {
                $newlogin = new userlogin;                  
                $con=$newlogin->dbconnect();
                $sql="SELECT * FROM icd_code WHERE id=$id";
                $res=mysqli_query($con,$sql);
                return $res;
            }
            
            
             function insertdiagnosis()
            {
                $newlogin = new userlogin;                  
                $con=$newlogin->dbconnect();
                $sql="INSERT INTO `temp_diagnosis` ( `dept`, `name`, `days`, `min_age`, `max_age`, `sex`) VALUES ('$this->dept', '$this->diag_name', '$this->dih', '$this->min_age', '$this->max_age', '$this->gender')";
                $res=mysqli_query($con,$sql);
                 $date=date("Y-m-d H:i:s");
                
                $sql1 = $con->prepare("INSERT INTO `query`(`sql_query`, `created_by`, `created_at`) VALUES (?, ?, ?)");
                $sql1->bind_param("sss", $sql, $_SESSION['name'], $date);
                $sql1->execute();
        $sql1->close();
        $con->close();
        
                return $res;
            }
            
             function updatediagnosis($id)
            {
                $newlogin = new userlogin;                  
                $con=$newlogin->dbconnect();
                $sql="UPDATE `temp_diagnosis` 
                        SET 
                          `dept` = '$this->dept',
                          `name` = '$this->diag_name',
                          `days` = '$this->dih',
                          `min_age` = '$this->min_age',
                          `max_age` = '$this->max_age',
                          `sex` = '$this->gender'
                        WHERE `id` = $id";
                $res=mysqli_query($con,$sql);
                 $date=date("Y-m-d H:i:s");
                
                $sql1 = $con->prepare("INSERT INTO `query`(`sql_query`, `created_by`, `created_at`) VALUES (?, ?, ?)");
                $sql1->bind_param("sss", $sql, $_SESSION['name'], $date);
                $sql1->execute();
        $sql1->close();
        $con->close();
        
                return $res;
            }

    // Patient types feed the Patient Type dropdowns on OP/IP registration.
    // Rows are keyed by the patient_type value itself (central_opd/central_ipd
    // store that text, not an id).
    function getPatientTypes()
    {
        $newlogin = new userlogin;
        $con = $newlogin->dbconnect();
        return mysqli_query($con, "SELECT `patient_type` FROM `patient_type` ORDER BY `patient_type`");
    }

    private function patientTypeExists($con, $name, $except = '')
    {
        $st = $con->prepare("SELECT 1 FROM `patient_type` WHERE UPPER(TRIM(`patient_type`)) = UPPER(TRIM(?)) AND `patient_type` <> ? LIMIT 1");
        $st->bind_param("ss", $name, $except);
        $st->execute();
        $found = (bool) $st->get_result()->fetch_row();
        $st->close();
        return $found;
    }

    /** Returns '' on success, or an error code: empty | duplicate | failed. */
    function insertPatientType($name)
    {
        $name = strtoupper(trim((string) $name));
        if ($name === '') return 'empty';
        $newlogin = new userlogin;
        $con = $newlogin->dbconnect();
        if ($this->patientTypeExists($con, $name)) { $con->close(); return 'duplicate'; }

        $st = $con->prepare("INSERT INTO `patient_type`(`patient_type`) VALUES (?)");
        $st->bind_param("s", $name);
        $ok = $st->execute();
        $st->close();
        if ($ok) $this->logMasterQuery($con, "INSERT INTO patient_type(patient_type) VALUES ('$name')");
        $con->close();
        return $ok ? '' : 'failed';
    }

    /** Renames a patient type. Existing patients keep the old text. Same return codes as insertPatientType(). */
    function updatePatientType($old, $name)
    {
        $name = strtoupper(trim((string) $name));
        if ($name === '') return 'empty';
        $newlogin = new userlogin;
        $con = $newlogin->dbconnect();
        if ($this->patientTypeExists($con, $name, (string) $old)) { $con->close(); return 'duplicate'; }

        $st = $con->prepare("UPDATE `patient_type` SET `patient_type` = ? WHERE `patient_type` = ?");
        $st->bind_param("ss", $name, $old);
        $ok = $st->execute();
        $st->close();
        if ($ok) $this->logMasterQuery($con, "UPDATE patient_type SET patient_type='$name' WHERE patient_type='$old'");
        $con->close();
        return $ok ? '' : 'failed';
    }

    private function logMasterQuery($con, $sql)
    {
        $date = date("Y-m-d H:i:s");
        $st = $con->prepare("INSERT INTO `query`(`sql_query`, `created_by`, `created_at`) VALUES (?, ?, ?)");
        $st->bind_param("sss", $sql, $_SESSION['name'], $date);
        $st->execute();
        $st->close();
    }

}
?>
<?php
require_once(__DIR__ . '/session_config.php');
include('dbconnect.php');


class addCerti{ 

    function setipd_no($ipd_no)
    {
        $this->ipd_no=$ipd_no;
    }
    function setmother_name($mother_name)
    {
        $this->mother_name=$mother_name;
    }
    function setbaby_name($baby_name)
    {
        $this->baby_name=$baby_name;
    }
    function setbaby_gender($baby_gender)
    {
        $this->baby_gender=$baby_gender;
    }
    function setmobile_no($mobile_no)
    {
        $this->mobile_no=$mobile_no;
    }
    function setpatient_id($patient_id)
    {
        $this->patient_id=$patient_id;
    }
    function setdoctor_name($doctor_name)
    {
        $this->doctor_name=$doctor_name;
    }
    function setdob($dob)
    {
        $this->dob=$dob;
    }
    function setweight($weight)
    {
        $this->weight=$weight;
    }
    function setheight($height)
    {
        $this->height=$height;
    }
    function setpatient_name($patient_name)
    {
        $this->patient_name=$patient_name;
    }
    function setgender($gender)
    {
        $this->gender=$gender;
    }
    function setdod($dod)
    {
        $this->dod=$dod;
    }
    function setcause($cause)
    {
        $this->cause=$cause;
    }

    function insertBirth()
    {
        $newlogin = new userlogin;                  
        $con=$newlogin->dbconnect();
        $sql="INSERT INTO `birth_record` (`ipd_no`, `mother_name`, `baby_name`, `baby_gender`, `mobile_no`, `patient_id`, `doctor`, `dob`,`weight`,`height`) VALUES ('$this->ipd_no', '$this->mother_name', '$this->baby_name', '$this->baby_gender', '$this->mobile_no', '$this->patient_id', '$this->doctor_name', '$this->dob','$this->weight','$this->height')";
        // echo $sql;
        $res=mysqli_query($con,$sql);
         
          $date=date("Y-m-d H:i:s");
      $sql1 = $con->prepare("INSERT INTO `query`(`sql_query`, `created_by`, `created_at`) VALUES (?, ?, ?)");
        $sql1->bind_param("sss", $sql, $_SESSION['name'], $date);
        $sql1->execute();
$sql1->close();

        return $res;
    }
    function getBirthRecord()
    {
         $newlogin = new userlogin;                  
         $con=$newlogin->dbconnect();
         $sql="select * from birth_record";
         $res=mysqli_query($con,$sql);
         return $res;
    }
     
     
    function updateBirth($id)
    {   
         $newlogin = new userlogin;                  
         $con=$newlogin->dbconnect();
        $sql="update birth_record set `ipd_no`='$this->ipd_no', `mother_name`='$this->mother_name', `baby_name`='$this->baby_name', `baby_gender`='$this->baby_gender', `mobile_no`='$this->mobile_no', `patient_id`='$this->patient_id', `doctor`='$this->doctor_name', `dob`='$this->dob',`weight`='$this->weight',`height`='$this->height' where id=$id";
        $res=mysqli_query($con,$sql);
        
          $date=date("Y-m-d H:i:s");
      $sql1 = $con->prepare("INSERT INTO `query`(`sql_query`, `created_by`, `created_at`) VALUES (?, ?, ?)");
        $sql1->bind_param("sss", $sql, $_SESSION['name'], $date);
        $sql1->execute();
$sql1->close();

         return $res;
    }

    function insertDeath()
    {
        $newlogin = new userlogin;                  
        $con=$newlogin->dbconnect();
        $sql="INSERT INTO `death_record` (`ipd_no`, `name`, `gender`, `mobile_no`, `patient_id`, `doctor`, `dod`, `cause`) VALUES ('$this->ipd_no', '$this->patient_name', '$this->gender', '$this->mobile_no', '$this->patient_id', '$this->doctor_name', '$this->dod', '$this->cause')";
        // echo $sql;
        $res=mysqli_query($con,$sql);
         $date=date("Y-m-d H:i:s");
          $sql1="INSERT INTO `query`(`sql_query`, `created_by`, `created_at`) VALUES ('$sql','$_SESSION[name]','$date')";
          mysqli_query($con,$sql1);
        return $res;
    }
    function getDeathRecord()
    {
         $newlogin = new userlogin;                  
         $con=$newlogin->dbconnect();
         $sql="select * from death_record";
         $res=mysqli_query($con,$sql);
         return $res;
    }
    function updateDeath($id)
    {   
         $newlogin = new userlogin;                  
         $con=$newlogin->dbconnect();
        $sql="update death_record set `ipd_no`='$this->ipd_no', `name`='$this->patient_name', `gender`='$this->gender', `mobile_no`='$this->mobile_no', `patient_id`='$this->patient_id', `doctor`='$this->doctor_name', `dod`='$this->dod', `cause`='$this->cause' where id=$id";
        $res=mysqli_query($con,$sql);
         $date=date("Y-m-d H:i:s");
          $sql1="INSERT INTO `query`(`sql_query`, `created_by`, `created_at`) VALUES ('$sql','$_SESSION[name]','$date')";
          mysqli_query($con,$sql1);
         return $res;
    }
}
?>
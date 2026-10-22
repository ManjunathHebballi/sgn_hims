<?php 
require_once(__DIR__ . '/../../model/session_config.php');
include('../model/dbconnect.php');


class master{
private $group_name,$bill_group,$service_name,$amt_share2doctor,$perc_share2doctor,$perc_share2hospital,$opd_charge,$ipd_charge,$esi_code,$esi_charges,$doctor_share,$doctor,$type,$status;
    function setgroup_name($group_name) {
        $this->group_name = $group_name;
      } 
      function setbill_group($bill_group) {
        $this->bill_group = $bill_group;
      } 
      
      function setservice_name($service_name) {
        $this->service_name = $service_name;
      }
      function setamt_share2doctor($amt_share2doctor) {
        $this->amt_share2doctor = $amt_share2doctor;
      }
      function setperc_share2doctor($perc_share2doctor) {
        $this->perc_share2doctor = $perc_share2doctor;
      }
      function setperc_share2hospital($perc_share2hospital) {
        $this->perc_share2hospital = $perc_share2hospital;
      }
       function setopd_charge($opd_charge) {
        $this->opd_charge = $opd_charge;
      }
      
       function setipd_charge($ipd_charge) {
        $this->ipd_charge = $ipd_charge;
      }
      function setesi_code($esi_code) {
        $this->esi_code = $esi_code;
      }
      function setesi_charges($esi_charges) {
        $this->esi_charges = $esi_charges;
      }
      
       function setdoctor_share($doctor_share) {
        $this->doctor_share = $doctor_share;
      }
      function setdoctor($doctor) {
        $this->doctor = $doctor;
      }
       function settype($type) {
        $this->type = $type;
      }
        function setstatus($status) {
        $this->status = $status;
      }
      
      
      
      
    function getServiceGroupId($id)
    {
        $newlogin = new userlogin;                  
      $con=$newlogin->dbconnect();
      $sql="select * from  `service_group` where id='$id'";
      $res=mysqli_query($con,$sql);
      return $res;
    }
    function getServiceGroup()
    {
        $newlogin = new userlogin;                  
      $con=$newlogin->dbconnect();
      $sql="select * from  `service_group` ";
      $res=mysqli_query($con,$sql);
      return $res;
    }
    function insertServiceGroup()
    {
         $newlogin = new userlogin;                  
          $con=$newlogin->dbconnect();
        $sql="INSERT INTO `service_group`( `name`, `bill_group`, `status`) VALUES ('$this->group_name','$this->bill_group','ACTIVE')";
         $res=mysqli_query($con,$sql);
         $date=date("Y-m-d H:i:s");
$sql1 = $con->prepare("INSERT INTO `query`(`sql_query`, `created_by`, `created_at`) VALUES (?, ?, ?)");
$sql1->bind_param("sss", $sql, $_SESSION['name'], $date);
$sql1->execute();
$sql1->close();
$con->close();
        return $res;
    }
    
    
    function updateServiceGroup($id)
    {
         $newlogin = new userlogin;                  
          $con=$newlogin->dbconnect();
        $sql="update `service_group` set  `name`='$this->group_name', `bill_group`='$this->bill_group' where id=$id";
         $res=mysqli_query($con,$sql);
         $date=date("Y-m-d H:i:s");
$sql1 = $con->prepare("INSERT INTO `query`(`sql_query`, `created_by`, `created_at`) VALUES (?, ?, ?)");
$sql1->bind_param("sss", $sql, $_SESSION['name'], $date);
$sql1->execute();
$sql1->close();
$con->close();
        return $res;
    }
   
    function insertServiceList()
    {
         $newlogin = new userlogin;                  
          $con=$newlogin->dbconnect();
        $sql="INSERT INTO `service_list` ( `name`, `opd_charge`, `ipd_charge`, `is_doctor`, `type`, `service_group_id`,`esi_code`, `esi_amount`, `status`) VALUES ( '$this->service_name', '$this->opd_charge', '$this->ipd_charge', '$this->doctor_share', '$this->type', '$this->group_name','$this->esi_code','$this->esi_charges' ,'Active');";
         $res=mysqli_query($con,$sql);
         $date=date("Y-m-d H:i:s");
$sql1 = $con->prepare("INSERT INTO `query`(`sql_query`, `created_by`, `created_at`) VALUES (?, ?, ?)");
$sql1->bind_param("sss", $sql, $_SESSION['name'], $date);
$sql1->execute();
$sql1->close();
$con->close();
        return $res;
    }
    
    
    
     function updateServiceList($id)
    {
         $newlogin = new userlogin;                  
          $con=$newlogin->dbconnect();
        $sql="update `service_list` set `name`='$this->service_name', `opd_charge`='$this->opd_charge', `ipd_charge`='$this->ipd_charge', `is_doctor`='$this->doctor_share', `type`='$this->type', `service_group_id`='$this->group_name',`status`='$this->status',`esi_code`='$this->esi_code', `esi_amount`='$this->esi_charges' where id=$id";
         $res=mysqli_query($con,$sql);
         $date=date("Y-m-d H:i:s");
$sql1 = $con->prepare("INSERT INTO `query`(`sql_query`, `created_by`, `created_at`) VALUES (?, ?, ?)");
$sql1->bind_param("sss", $sql, $_SESSION['name'], $date);
$sql1->execute();
$sql1->close();
$con->close();
        return $res;
    }
    
    
    
    
    function getServiceList()
    {
        $newlogin = new userlogin;                  
      $con=$newlogin->dbconnect();
      $sql="select sl.*,sg.name as sg_name from  `service_group` sg,service_list sl where service_group_id=sg.id order by sl.name";
      $res=mysqli_query($con,$sql);
      return $res;
    }
    
    function getServiceNameId($id)
    {
        $newlogin = new userlogin;                  
      $con=$newlogin->dbconnect();
      $sql="select sl.* from  service_list sl where  sl.id=$id order by sl.name";
      $res=mysqli_query($con,$sql);
      return $res;
    }
    function getDoctorShareList()
    {
        $newlogin = new userlogin;                  
      $con=$newlogin->dbconnect();
      $sql="select sl.name,concat(d.name,' ',d.last_name) as doctor,amt_share2doctor,perc_share2doctor,perc_share2hospital,dr.status,dr.id from  `doctor_revenue` dr,service_list sl,doctor d where dr.service_id=sl.id and d.id=dr.doctor_id order by sl.name";
      $res=mysqli_query($con,$sql);
      return $res;
    }
    function getDoctor()
    {
        $newlogin = new userlogin;                  
      $con=$newlogin->dbconnect();
      $sql="select * from  `doctor`  where status='active' order by name";
      $res=mysqli_query($con,$sql);
      return $res;
    }
    function getDoctorShareId($id)
    {
        $newlogin = new userlogin;                  
      $con=$newlogin->dbconnect();
      $sql="select * from  `doctor_revenue` where id=$id ";
      $res=mysqli_query($con,$sql);
      return $res;
    }
    function getShareServices()
    {
      $newlogin = new userlogin;                  
      $con=$newlogin->dbconnect();
      $sql="select * from  `service_list` where is_doctor=1 and status='active' order by name";
      $res=mysqli_query($con,$sql);
      return $res;
    }
    function insertDoctorShare()
    {
      $newlogin = new userlogin;                  
      $con=$newlogin->dbconnect();
      $sql1="select * from doctor_revenue where `doctor_id`='$this->doctor' and `service_id`='$this->service_name'";
      $row=mysqli_query($con,$sql1);
      if(mysqli_num_rows($row)==0){
      $sql="INSERT INTO `doctor_revenue`(`doctor_id`, `service_id`, `amt_share2doctor`, `perc_share2doctor`, `perc_share2hospital`, `status`, `created_by`, `created_at`) VALUES 
      ('$this->doctor','$this->service_name','$this->amt_share2doctor','$this->perc_share2doctor','$this->perc_share2hospital','active','$_SESSION[name]',now())";
    //   echo $sql;exit;
      $res=mysqli_query($con,$sql);
      $date=date("Y-m-d H:i:s");
$sql1 = $con->prepare("INSERT INTO `query`(`sql_query`, `created_by`, `created_at`) VALUES (?, ?, ?)");
$sql1->bind_param("sss", $sql, $_SESSION['name'], $date);
$sql1->execute();
$sql1->close();
$con->close();
      }
      else{
          echo "<script>alert('Doctor share already exist Please check')</script>";
          $res=1;
      }
      return $res;
    }
    function updateDoctorShare($id)
    {
        $newlogin = new userlogin;                  
      $con=$newlogin->dbconnect();
      $sql="UPDATE `doctor_revenue` set `doctor_id`='$this->doctor', `service_id`='$this->service_name', `amt_share2doctor`='$this->amt_share2doctor', `perc_share2doctor`='$this->perc_share2doctor', `perc_share2hospital`='$this->perc_share2hospital', `status`='active', `updated_by`='$_SESSION[name]', `updated_at`=now() 
      where id=$id";
      $res=mysqli_query($con,$sql);
      $date=date("Y-m-d H:i:s");
$sql1 = $con->prepare("INSERT INTO `query`(`sql_query`, `created_by`, `created_at`) VALUES (?, ?, ?)");
$sql1->bind_param("sss", $sql, $_SESSION['name'], $date);
$sql1->execute();
$sql1->close();
$con->close();
      return $res;
    }

function getLabTests($filter_department = '', $filter_name = '') {
        $newlogin = new userlogin;
        $con = $newlogin->dbconnect();
        $where = " WHERE `status`='active'";
        if ($filter_department) {
            $where .= " AND testname_header='" . mysqli_real_escape_string($con, $filter_department) . "'";
        }
        if ($filter_name) {
            $where .= " AND testname LIKE '%" . mysqli_real_escape_string($con, $filter_name) . "%'";
        }
        $sql = "SELECT * FROM `lab_test` $where order by testname_header";
        return mysqli_query($con, $sql);
    }
    function insertLabTest($testname, $testprice, $type, $sample, $description, $interpretation = '') {
        $newlogin = new userlogin;
        $con = $newlogin->dbconnect();
        $testname = mysqli_real_escape_string($con, $testname);
        $testprice = mysqli_real_escape_string($con, $testprice);
        $type = mysqli_real_escape_string($con, $type);
        $sample = mysqli_real_escape_string($con, $sample);
        $description = mysqli_real_escape_string($con, $description);
        $interpretation = mysqli_real_escape_string($con, $interpretation);
        $sql1 = "INSERT INTO service_list (name,opd_charge,ipd_charge,type,service_group_id,status) VALUES('$testname','$testprice','$testprice','Internal','27','Active')";
        mysqli_query($con, $sql1);
        $id = mysqli_insert_id($con);
        $query = "INSERT INTO `lab_test`( `testid`, `testname`, `price`, `description`,`testname_header`,`samples`, `interpretation`, `status`) VALUES ('$id','$testname','$testprice','$description','$type','$sample', '$interpretation', 'active')";
         mysqli_query($con, $query);
         return mysqli_insert_id($con);
    }
      function getLabDepartment()
  {
       $newlogin = new userlogin;
      $con = $newlogin->dbconnect();
    $sql="SELECT * FROM lab_department WHERE status='active'";
    $ok = mysqli_query($con,$sql);
    return $ok;
  }
}
?>
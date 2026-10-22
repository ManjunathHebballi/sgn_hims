<?php
require_once(__DIR__ . '/session_config.php');
include('dbconnect.php');

class addClass{ 

var $dob,$fname,$lname,$designation,$contact,$sex,$qualification,$dept,$role,$email,$pass,$lab_department,$sub_store;
function setdob($dob) {
    $this->dob = $dob;
}   
function setfname($fname) {
    $this->fname = $fname;
}   
function setlname($lname) {
    $this->lname = $lname;
}   
function setdesignation($designation) {
    $this->designation = $designation;
}   
function setcontact($contact) {
    $this->contact = $contact;
}   
function setsex($sex) {
    $this->sex = $sex;
}   
function setqualification($qualification) {
    $this->qualification = $qualification;
}   
function setdept($dept) {
    $this->dept = $dept;
}   
function setrole($role) {
    $this->role = $role;
}   
function setemail($email) {
    $this->email = $email;
}   
function setpass($pass) {
    $this->pass = $pass;
}   
function setlab_department($lab_department) {
    // Convert array to JSON if it's an array, otherwise store as-is
    if(is_array($lab_department)) {
        $this->lab_department = json_encode($lab_department);
    } else {
        $this->lab_department = $lab_department;
    }
}   
function setsub_store($sub_store) {
  $this->sub_store = $sub_store;
}

function insertUser() {
//  print_r($_SESSION);exit;
  $newlogin = new userlogin;                  
  $con=$newlogin->dbconnect();
  if($this->lab_department == 'null' || $this->lab_department == '') {
      $lab_department = 'NULL';
    }
    else{
        $lab_department = "'$this->lab_department'";
    }
    if($this->sub_store == 'null' || $this->sub_store == '') {
      $sub_store = 'NULL';
    }
    else{
      $sub_store = "'$this->sub_store'";
    }
      $sql="INSERT INTO `user`( `name`, `last_name`, `designation`, `mobile`, `department`, `role`, `qualification`, `email`, `password`, `gender`, `dob`, `lab_department`, `store_id`) 
      VALUES ('$this->fname','$this->lname','$this->designation','$this->contact','$this->dept','$this->role','$this->qualification','$this->email',md5('$this->pass'),'$this->sex','$this->dob',$lab_department,$sub_store)";
    //   echo $sql;exit;
      
      $res=mysqli_query($con,$sql);


        if ($con->connect_error) {
     die("Connection failed: " . $con->connect_error);
   }
  
       $date=date("Y-m-d H:i:s");
        
        $sql1 = $con->prepare("INSERT INTO `query`(`sql_query`, `created_by`, `created_at`) VALUES (?, ?, ?)");
        $sql1->bind_param("sss", $sql, $_SESSION['name'], $date);
        $sql1->execute();
$sql1->close();
$con->close();
// exit;
   echo "<script>window.location='../add-user.php?success=1'</script>";
     
}

function selectDept()
{
  $newlogin = new userlogin;                  
  $con=$newlogin->dbconnect();
  $sql="SELECT * FROM `department` WHERE `status`='active'";
  $res=mysqli_query($con,$sql);
  return $res;
}
function selectSubDept()
{
  $newlogin = new userlogin;                  
  $con=$newlogin->dbconnect();
  $sql="SELECT * FROM `sub_department` WHERE `status`='active'";
  $res=mysqli_query($con,$sql);
  return $res;
}
function getLabDepartments()
{
  $newlogin = new userlogin;                  
  $con=$newlogin->dbconnect();
  $sql="SELECT * FROM `lab_department` WHERE `status`='active' ORDER BY dept_name";
  $res=mysqli_query($con,$sql);
  return $res;
}
function selectUser()
{
    $newlogin = new userlogin;                  
  $con=$newlogin->dbconnect();
  $sql="SELECT u.*,d.dept_name FROM `user` u left join `department` d on department=d.id WHERE u.`status`='active' order by role ";
  $res=mysqli_query($con,$sql);
  return $res;
}

function getDoctors()
{
     $newlogin = new userlogin;                  
  $con=$newlogin->dbconnect();
  $where='';
  if($_SESSION['role']=='Lab' || $_SESSION['role']=='Lab Admin')
  {
      $where = "where role like 'lab%'";
  }
  $sql="SELECT * FROM `user` $where";
  $res=mysqli_query($con,$sql);
  return $res;
}
function statusUser($id,$stat)
{
    $newlogin = new userlogin;                  
  $con=$newlogin->dbconnect();
  $sql="UPDATE `user` set status='$stat' where id='$id'";
  $res=mysqli_query($con,$sql);
 
        $date=date("Y-m-d H:i:s");
        
        $sql1 = $con->prepare("INSERT INTO `query`(`sql_query`, `created_by`, `created_at`) VALUES (?, ?, ?)");
        $sql1->bind_param("sss", $sql, $_SESSION['name'], $date);
        $sql1->execute();
$sql1->close();
$con->close();


  return $res;
}
function getUser($id)
{
    $newlogin = new userlogin;                  
  $con=$newlogin->dbconnect();
  $sql="SELECT * FROM `user` where id='$id'";
  $res=mysqli_query($con,$sql);
  return $res;
}
function editUser($id)
{
    $newlogin = new userlogin;                  
  $con=$newlogin->dbconnect();
  if(strlen($this->pass)>0)
  {
     $data= ", `password`=md5('$this->pass')";
  }
  else{
      $data='';
  }
  if($this->lab_department == 'null' || $this->lab_department == '') {
      $lab_department = 'NULL';
    }
    else{
        $lab_department = "'$this->lab_department'";
    }
  if($this->sub_store == 'null' || $this->sub_store == '') {
      $sub_store = 'NULL';
    }
    else{
        $sub_store = "'$this->sub_store'";
    }
  $sql="UPDATE `user` SET `name`='$this->fname', `last_name`='$this->lname', `designation`='$this->designation', `mobile`='$this->contact', `department`='$this->dept', `role`='$this->role', `qualification`='$this->qualification', `email`='$this->email', `gender`='$this->sex', `dob`='$this->dob', `lab_department`=$lab_department, `store_id`=$sub_store $data where id='$id'";
  $res=mysqli_query($con,$sql);
// echo $sql;exit;
       $date=date("Y-m-d H:i:s");
        
        $sql1 = $con->prepare("INSERT INTO `query`(`sql_query`, `created_by`, `created_at`) VALUES (?, ?, ?)");
        $sql1->bind_param("sss", $sql, $_SESSION['name'], $date);
        $sql1->execute();
$sql1->close();
$con->close();

  return $res;
}
function getStores()
{
    $newlogin = new userlogin;                  
  $con=$newlogin->dbconnect();
  $sql="SELECT * FROM `stores` WHERE `status`='1'";
  $res=mysqli_query($con,$sql);
  return $res;
}
}
?>
<?php
require_once(__DIR__ . '/session_config.php');
include('dbconnect.php');


class addTPA{ 

    function setname($name)
    {
        $this->name=$name;
    }
    function setcontact_person($contact_person)
    {
        $this->contact_person=$contact_person;
    }
    function setmobile($mobile)
    {
        $this->mobile=$mobile;
    }
    function setaddress($address)
    {
        $this->address=$address;
    }
    
    function insertTPA()
    {
          $newlogin = new userlogin;                  
         $con=$newlogin->dbconnect();
         $date=date('Y-m-d');
         $sql="insert into `tpa`(`name`,`contact_person`,`mobile`,`address`,`date`) values('$this->name','$this->contact_person','$this->mobile','$this->address','$date')";
         $res=mysqli_query($con,$sql);
         
                     
$date=date("Y-m-d H:i:s");
$sql1 = $con->prepare("INSERT INTO `query`(`sql_query`, `created_by`, `created_at`) VALUES (?, ?, ?)");
$sql1->bind_param("sss", $sql, $_SESSION['name'], $date);
$sql1->execute();
$sql1->close();
$con->close();

    
         return $res;
    }
    
    function getTPA()
    {
        $newlogin = new userlogin;                  
         $con=$newlogin->dbconnect();
        
         $sql="select * from tpa";
         $res=mysqli_query($con,$sql);
         return $res;
    }

    
}
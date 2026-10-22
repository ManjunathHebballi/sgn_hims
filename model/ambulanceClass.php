<?php
require_once(__DIR__ . '/session_config.php');
include('dbconnect.php');


class ambulance{ 
    function setnumber($number)
    {
        $this->number=$number;
    }
    function setcharge($charge)
    {
        $this->charge=$charge;
    }
    function setname($name)
    {
        $this->name=$name;
    }
    function setlicense($license)
    {
        $this->license=$license;
    }
    function setassistant($assistant)
    {
        $this->assistant=$assistant;
    }
    function setvehicle_type($vehicle_type)
    {
        $this->vehicle_type=$vehicle_type;
    }
    function setcontact($contact)
    {
        $this->contact=$contact;
    }
     function addAmbulance()
    {
         $newlogin = new userlogin;                  
         $con=$newlogin->dbconnect();
         $sql="INSERT INTO `ambulance`(`vehicle_no`, `charge`, `driver_name`,`driver_licence`, `assistant_name`, `driver_contact`, `type`, `status`) VALUES ('$this->number','$this->charge','$this->name','$this->license','$this->assistant','$this->contact','$this->vehicle_type','ACTIVE')";
         $res=mysqli_query($con,$sql);
       
        $date=date("Y-m-d H:i:s");
      $sql1 = $con->prepare("INSERT INTO `query`(`sql_query`, `created_by`, `created_at`) VALUES (?, ?, ?)");
        $sql1->bind_param("sss", $sql, $_SESSION['name'], $date);
        $sql1->execute();
$sql1->close();

         return mysqli_num_rows($res);
    }
    
    function getAmbulance()
    {
        $newlogin = new userlogin;                  
         $con=$newlogin->dbconnect();
         $sql="SELECT * FROM `ambulance`";
         $res=mysqli_query($con,$sql);
         return $res;
    }
}
?>
<?php
require_once(__DIR__ . '/session_config.php');
include('dbconnect.php');


class manufacture{
    
    function setname($name)
    {
        $this->name=$name;
    }
    function setmobile($mobile)
    {
        $this->mobile=$mobile;
    }
    function setemail($email)
    {
        $this->email=$email;
    }
    function setaddress($address)
    {
        $this->address=$address;
    }
    function setcity($city)
    {
        $this->city=$city;
    }
    function setbalance($balance)
    {
        $this->balance=$balance;
    }
    
    function getCountry()
    {
        $newlogin = new userlogin;                  
          $con=$newlogin->dbconnect();
          $sql="SELECT * FROM `countries` ";
          $res=mysqli_query($con,$sql);
          return $res;
    }
    function insertManufacture()
    {
        $newlogin = new userlogin;                  
          $con=$newlogin->dbconnect();
          $sql="INSERT INTO `pharma_manufacturer`( `manuf_name`, `manuf_mobile`, `manuf_email`, `manuf_address`, `manuf_city`, `prev_balance`) 
          VALUES ('$this->name','$this->mobile','$this->email','$this->address','$this->city','$this->balance')";
          $res=mysqli_query($con,$sql);
          return $res;
    }
    function getManufac($id)
    {
        $newlogin = new userlogin;                  
          $con=$newlogin->dbconnect();
          $sql="SELECT m.*,c.name as country,s.name as state, ci.name as city,s.country_id,ci.state_id FROM `pharma_manufacturer` m,`countries` c, `states` s, `cities` ci  
          WHERE m.manuf_city=ci.id and ci.state_id=s.id and c.id=s.country_id and m.id=$id";
        //   echo $sql;exit;
          $res=mysqli_query($con,$sql);
          return $res;
    }
    function getManufacturer()
    {
        $newlogin = new userlogin;                  
          $con=$newlogin->dbconnect();
          $sql="SELECT m.*,c.name as country,s.name as state, ci.name as city,s.country_id,ci.state_id FROM `pharma_manufacturer` m,`countries` c, `states` s, `cities` ci  
          WHERE m.manuf_city=ci.id and ci.state_id=s.id and c.id=s.country_id";
        //   echo $sql;
          $res=mysqli_query($con,$sql);
          return $res;
    }
    
     function updateManufacture($id,$status)
    {
        $newlogin = new userlogin;                  
          $con=$newlogin->dbconnect();
          $sql="UPDATE `pharma_manufacturer` SET `manuf_name`='$this->name', `manuf_mobile`='$this->mobile', `manuf_email`='$this->email', `manuf_address`='$this->address', `manuf_city`='$this->city', `prev_balance`='$this->balance', status='$status' WHERE id=$id ";
        //   echo $sql;exit;
          $res=mysqli_query($con,$sql);
          return $res;
    }
}

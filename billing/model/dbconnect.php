<?php
require_once(__DIR__ . '/../../model/env.php');
require_once(__DIR__ . '/../../model/session_config.php');

date_default_timezone_set(env('TZ', 'Asia/Calcutta'));


class userlogin {

    var $dbHost,
        $dbUser,
        $dbName,
        $dbPass,
        $dbLink,
        $dbport;


    // Constructor definition

   function __construct () {
    // Connection details come from the .env file in the project root.
    // The fallbacks keep a plain XAMPP checkout working when no .env exists.
        $this->dbHost = env('DB_HOST', 'localhost');
        $this->dbUser = env('DB_USER', 'root');
        $this->dbName = env('DB_NAME', 'hospital');
        $this->dbPass = env('DB_PASSWORD', '');
        $this->dbport = env('DB_PORT', 3306);
    }

    // Backwards compatible alias for the old PHP 4 style constructor,
    // in case any page still calls $obj->userlogin() explicitly.
   function userlogin () {
        $this->__construct();
    }

    // Property get and set
    function getdbhost() {
           return $this->dbHost;
    }

    function setdbhost ($reqdbhost) {
           $this->dbHost = $reqdbhost;
    }

    function getdbuser() {
           return $this->dbUser;
    }

    function setdbName ($reqdbname) {
           $this->dbName = $reqdbname;
    }

    function getdbPass () {
           return $this->dbPass;
    }

    function setdbpass ($reqdbpass) {
           $this->dbPass = $reqdbpass;
    }

    function dbconnect () {
        // Added the following line to prevent showing error details
        //error_reporting(0);
     if(!isset($_SESSION['loginid']) || !isset($_SESSION['name']))
        {
        echo "<script>window.location='../index.php'</script>";
        }

        $con = mysqli_connect($this->dbHost, $this->dbUser, $this->dbPass, $this->dbName, (int)$this->dbport) ;



    if ($con->connect_error) {
          die("Connection failed: " . $con->connect_error);
        }
        return $con;
    }

    function dbdisconnect ($con) {
         mysqli_close($con);

    }


    function verifyPassword($inpuserName, $inpuserPassword) {


        $username = "'" . $inpuserName . "'";
        $userpwd = "'" . $inpuserPassword . "'";
        // Connect to database

        // Select database
        $con=mysqli_connect($this->dbHost, $this->dbUser, $this->dbPass, $this->dbName, (int)$this->dbport);
         
        $query = "SELECT  u.`name`, u.`last_name`, u.`designation`, u.`mobile`, d.`dept_name`, u.`role`,u.`id` FROM `user` u LEFT JOIN `department` d on u.`department`=d.`id`
                   WHERE u.`email` = $username AND u.`password` = md5($userpwd) AND u.`status` = 'active'";
                    // echo $query;exit;      
                  
       $result = mysqli_query($con,$query);
        // $myrow=mysqli_fetch_array($result);
        
       if (mysqli_connect_errno()) {
  echo "Failed to Login: " . mysqli_connect_error();
  exit();
}

       
        return $result;
    } // End verifyPassword()



  
    
} // End User class definition

?>
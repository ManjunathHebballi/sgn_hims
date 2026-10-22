<?php
require_once(__DIR__ . '/env.php');
require_once(__DIR__ . '/session_config.php');
date_default_timezone_set(env('TZ', 'Asia/Calcutta'));

class userlogin {

    var $dbHost,
        $dbUser,
        $dbName,
        $dbPass,
        $dbLink,
        $dbport;


    // Constructor definition

    function __construct() {
    // Connection details come from the .env file in the project root.
    // The fallbacks keep a plain XAMPP checkout working when no .env exists.
        $this->dbHost = env('DB_HOST', 'localhost');
        $this->dbUser = env('DB_USER', 'root');
        $this->dbName = env('DB_NAME', 'hospital');
        $this->dbPass = env('DB_PASSWORD', '');
        $this->dbport = env('DB_PORT', 3306);
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
        if(!isset($_SESSION['loginid']) || !isset($_SESSION['name']))
        {
            // Compute correct path depth so the redirect reaches index.php
            // regardless of whether the calling page is at root or in a subdirectory.
            $scriptDir = dirname(str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '/index.php'));
            $depth = max(0, count(array_filter(explode('/', $scriptDir))) - 1);
            $prefix = $depth > 0 ? str_repeat('../', $depth) : '';
            echo "<script>window.location='{$prefix}index.php';</script>";
            exit;
        }

        $con = mysqli_connect($this->dbHost, $this->dbUser, $this->dbPass,$this->dbName  ) ;



    if ($con->connect_error) {
          die("Connection failed: " . $con->connect_error);
        }
        return $con;
    }
    
    function dbconnect1() {
        // Added the following line to prevent showing error details
        //error_reporting(0);
        


        $con = mysqli_connect($this->dbHost, $this->dbUser, $this->dbPass,$this->dbName) ;



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

        //  $this->dbconnect();
        // Select database
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
        
        try {
            $con = mysqli_connect($this->dbHost, $this->dbUser, $this->dbPass, $this->dbName);
            if (!$con) {
                error_log("MySQL Connection failed: " . mysqli_connect_error());
                die("Failed to Login: Database connection error. Host: {$this->dbHost}, User: {$this->dbUser}, DB: {$this->dbName}");
            }
        } catch (Exception $e) {
            error_log("MySQL Connection exception: " . $e->getMessage());
            die("Failed to Login: " . $e->getMessage());
        }
         
        $query = "SELECT  u.`name`, u.`last_name`, u.`designation`, u.`mobile`, d.`dept_name`, u.`role`,u.`id`,u.lab_department,u.store_id FROM `user` u LEFT JOIN `department` d on u.`department`=d.`id`
                   WHERE u.`email` = $username AND u.`password` = md5($userpwd) AND u.`status` = 'active'";
                    // echo $query;exit;      
                  
       $result = mysqli_query($con,$query);
        // $myrow=mysqli_fetch_array($result);

       
        return $result;
    } // End verifyPassword()


    /**
     * Newest still-valid ABDM gateway API key, or null when none is on file.
     * Uses dbconnect1() because this runs during login, before the session
     * carries loginid — dbconnect() would bounce back to index.php.
     */
    function getAPIKey() {
        $con = $this->dbconnect1();
        $dt  = date('Y-m-d H:i:s');

        $sql = "SELECT `id`, `keyId`, `apiKey`, `expiresAt`
                FROM `abha_api_key`
                WHERE `expiresAt` > ?
                ORDER BY `expiresAt` ASC
                LIMIT 1";

        $stmt = $con->prepare($sql);
        if (!$stmt) {
            return null;
        }
        $stmt->bind_param("s", $dt);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['apiKey'];
        }
        return null;
    }

    /**
     * Store a freshly minted gateway API key. Returns the key so the caller can
     * drop it straight onto the session.
     */
    function insertAPIKey($id, $key, $exp) {
        $con = $this->dbconnect1();

        // A gateway key issued without expirationDays never expires, so the
        // gateway returns expiresAt = null. strtotime(null) is false and date()
        // would turn that into 1970 — getAPIKey() filters on expiresAt > now, so
        // the key would look dead on arrival and every login would mint another
        // one (the gateway caps a facility at 3 active production keys). Store a
        // far-future sentinel instead; expiresAt is NOT NULL in this table.
        $ts = ($exp === null || $exp === '') ? false : strtotime($exp);
        $dt = ($ts === false) ? '2099-12-31 23:59:59' : date("Y-m-d H:i:s", $ts);

        $sql = "INSERT INTO `abha_api_key` (`keyId`, `apiKey`, `expiresAt`)
                VALUES (?, ?, ?)";

        $stmt = $con->prepare($sql);
        if (!$stmt) {
            return $key;
        }
        $stmt->bind_param("sss", $id, $key, $dt);
        $stmt->execute();
        return $key;
    }


} // End User class definition

?>
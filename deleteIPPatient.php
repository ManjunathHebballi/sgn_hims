<?php 
require_once(__DIR__ . '/model/session_config.php');
date_default_timezone_set("Asia/Kolkata");  
include('model/ipClass.php');
$newOP = new addIP;
$dept=$newOP->delteIP($_REQUEST['id']);
echo "<script>window.location='ippatient.php'</script>";
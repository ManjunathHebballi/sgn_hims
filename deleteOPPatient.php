<?php 
require_once(__DIR__ . '/model/session_config.php');
date_default_timezone_set("Asia/Kolkata");  
include('model/opClass.php');
$newOP = new addOP;
$dept=$newOP->delteOP($_REQUEST['id']);
echo "<script>window.location='oppatient.php'</script>";
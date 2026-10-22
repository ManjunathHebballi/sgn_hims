<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once('../model/session_config.php');

$sessionPath = (string)session_save_path();
if (strpos($sessionPath, ';') !== false) {
    $parts = explode(';', $sessionPath);
    $sessionPath = end($parts);
}
$sessionPath = trim((string)$sessionPath);
if ($sessionPath === '') {
    $sessionPath = sys_get_temp_dir();
}
$sessionPath = rtrim($sessionPath, '/\\');
$debugLogFile = $sessionPath . DIRECTORY_SEPARATOR . 'debug_login.log';

// Debug: Log session info
@file_put_contents($debugLogFile, 
    date('Y-m-d H:i:s') . " - Session ID: " . session_id() . "\n" .
    "Session Save Path: " . session_save_path() . "\n",
    FILE_APPEND
);



include('../model/dbconnect.php');
$newLogin = new userlogin;
// print_r($_REQUEST);exit;
$user=$_REQUEST['username'];
$pass=$_REQUEST['password'];
$login=$newLogin->verifyPassword($user,$pass);
if ($login->num_rows == 0)
{
    // The message lives on index.php so it renders in the page instead of a
    // modal alert that blocks the browser until it is dismissed. Only a flag
    // travels in the query string -- never the username that was tried.
    if (!headers_sent()) {
        header('Location: ../index.php?e=1');
    } else {
        echo "<script>window.location='../index.php?e=1';</script>";
    }
    exit;
}
else{
$res=mysqli_fetch_assoc($login);

$_SESSION['name']= $res['name'].' '.$res['last_name'] ;
$_SESSION['designation']= $res['designation'] ;
$_SESSION['department']= $res['dept_name'] ;
$_SESSION['role']= $res['role'] ;
$_SESSION['loginid']= $res['id'] ;
$_SESSION['lab_dept']= $res['lab_department'] ;
$_SESSION['store_id']= $res['store_id'] ;

// ── ABDM / ABHA gateway API key ───────────────────────────────────────────────
// Best-effort provisioning of the Node gateway API key that abha_integration.php
// sends as X-API-Key. This must NEVER block login: short connect/total timeouts,
// and any failure is non-fatal — the key is provisioned on a later login once the
// gateway is reachable again.
require_once('../config/abdm_config.php');

$_SESSION['clientid']     = ABDM_CLIENT_ID;
$_SESSION['clientSecret'] = ABDM_CLIENT_SECRET;
$_SESSION['base_url']     = ABDM_BASE_URL;
$_SESSION['hipId']        = ABDM_HIP_ID;
$_SESSION['hiuId']        = ABDM_HIU_ID;
$_SESSION['x_cm_id']      = ABDM_CM_ID;
$_SESSION['encKey']       = getenv('APP_ENC_KEY') ?: '';

$apiKey = $newLogin->getAPIKey();

$gatewayEmail    = getenv('ABDM_GATEWAY_EMAIL') ?: '';
$gatewayPassword = getenv('ABDM_GATEWAY_PASSWORD') ?: '';

if (!$apiKey && $gatewayEmail !== '' && $gatewayPassword !== '') {
    $baseurl = ABDM_BASE_URL;

    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL            => $baseurl . '/api/auth/login',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING       => '',
        CURLOPT_MAXREDIRS      => 10,
        CURLOPT_CONNECTTIMEOUT => 5,
        CURLOPT_TIMEOUT        => 15,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST  => 'POST',
        CURLOPT_POSTFIELDS     => json_encode(array(
            'email'    => $gatewayEmail,
            'password' => $gatewayPassword,
        )),
        CURLOPT_HTTPHEADER     => array('Content-Type: application/json'),
    ));
    $response  = curl_exec($curl);
    curl_close($curl);
    $response1 = json_decode($response, true);
    $token     = isset($response1['data']['token']) ? $response1['data']['token'] : '';

    if ($token !== '') {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL            => $baseurl . '/api/auth/generate-api-key',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING       => '',
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_CONNECTTIMEOUT => 5,
            CURLOPT_TIMEOUT        => 15,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST  => 'POST',
            // No expirationDays: a gateway key is long-lived infrastructure, and
            // a silent expiry would take ABDM down with no warning the hospital
            // could act on. Revoke by setting isActive=false on the gateway
            // instead. hipId binds the key to this facility, so it cannot act as
            // another hospital (the gateway answers a mismatch with 403).
            CURLOPT_POSTFIELDS     => json_encode(array_filter(array(
                'environment' => 'PROD',
                'name'        => 'Prod API Key for ABHA',
                'permissions' => array('READ', 'WRITE'),
                'hipId'       => ABDM_HIP_ID,
            ), function ($v) { return $v !== '' && $v !== null; })),
            CURLOPT_HTTPHEADER     => array(
                'Authorization: Bearer ' . $token,
                'Content-Type: application/json'
            ),
        ));
        $response  = curl_exec($curl);
        curl_close($curl);
        $response1 = json_decode($response, true);
        if (isset($response1['data']['apiKey'], $response1['data']['keyId'])) {
            $validKeyId = $response1['data']['keyId'];
            $apiKey     = $response1['data']['apiKey'];
            $exp_dt     = isset($response1['data']['expiresAt']) ? $response1['data']['expiresAt'] : null;
            $apiKey     = $newLogin->insertAPIKey($validKeyId, $apiKey, $exp_dt);
        }
    }
}

$_SESSION['abha_api'] = $apiKey;

session_regenerate_id(true);

// Debug: Log session data
@file_put_contents($debugLogFile, 
    date('Y-m-d H:i:s') . " - Session set successfully\n" .
    "Login ID: " . $_SESSION['loginid'] . "\n" .
    "Name: " . $_SESSION['name'] . "\n" .
    "Role: " . $_SESSION['role'] . "\n",
    FILE_APPEND
);

// Force session write before redirect
session_write_close();


// print_r($_SESSION);exit;
if($_SESSION['role']=='Accountant')
{
   echo "<script>window.location='../billing/dashboard.php';</script>";
   exit;
}
elseif($_SESSION['role']=='Store')
{
     echo "<script>window.location='../store_dashboard.php';</script>";
     exit;
}
else{
    echo "<script>window.location='../dashboard.php';</script>";
    exit;
}
}
?>

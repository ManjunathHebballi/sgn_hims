<?php 
require_once(__DIR__ . '/model/session_config.php');
session_start();
date_default_timezone_set("Asia/Kolkata");
include('model/storeClass.php');

$response = array('success' => false, 'message' => '', 'invoices' => array());

if(isset($_POST['supplier_id'])) {
    $supplier_id =  $_POST['supplier_id'];
    
    $newStore = new store;
    $result = $newStore->getUnpaidInvoicesBySupplier($supplier_id);
    
    if($result) {
        $invoices = array();
        while($row = mysqli_fetch_assoc($result)) {
            $invoices[] = $row;
        }
        $response['success'] = true;
        $response['invoices'] = $invoices;
    } else {
        $response['message'] = 'No invoices found';
    }
} else {
    $response['message'] = 'Supplier ID not provided';
}

header('Content-Type: application/json');
echo json_encode($response);
?>
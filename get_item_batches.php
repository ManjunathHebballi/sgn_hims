<?php
require_once(__DIR__ . '/model/session_config.php');
session_start();
date_default_timezone_set("Asia/Kolkata");
include('model/storeClass.php');

$response = array('success' => false, 'message' => '', 'batches' => array());

if(isset($_POST['item_id'])) {
    $item_id = $_POST['item_id'];
    
    $newStore = new store;
    $result = $newStore->getStockByItem($item_id);
    
    if($result) {
        $batches = array();
        while($row = mysqli_fetch_assoc($result)) {
            if($row['available_qty'] > 0) {
                $batches[] = array(
                    'batch_no' => $row['batch_no'],
                    'available_qty' => $row['available_qty'],
                    'expiry_date' => $row['expiry_date'] ? date('d-M-Y', strtotime($row['expiry_date'])) : null
                );
            }
        }
        $response['success'] = true;
        $response['batches'] = $batches;
    } else {
        $response['message'] = 'No stock found';
    }
} else {
    $response['message'] = 'Item ID not provided';
}

header('Content-Type: application/json');
echo json_encode($response);
?>

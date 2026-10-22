<?php 
require_once(__DIR__ . '/model/session_config.php');

error_reporting(E_ALL);
ini_set('display_errors', 1);
date_default_timezone_set("Asia/Kolkata");  

include('model/storeClass.php');

// Check if user is logged in
if(!isset($_SESSION['loginid'])) {
    echo json_encode(['success' => false, 'message' => 'Not authenticated']);
    exit;
}

// Get PO ID from POST
$po_id = isset($_POST['po_id']) ? intval($_POST['po_id']) : 0;

if($po_id == 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid PO ID']);
    exit;
}

$newStore = new store;

// Get PO details to retrieve supplier_id
$po_data = $newStore->getPurchaseOrderById($po_id);
if(!$po_data) {
    echo json_encode(['success' => false, 'message' => 'Purchase Order not found']);
    exit;
}

// Get PO items
$po_items_result = $newStore->getPOItemsByPOId($po_id);
$items = array();

while($item = mysqli_fetch_assoc($po_items_result)) {
    $items[] = array(
        'item_id' => $item['item_id'],
        'item_name' => $item['item_name'],
        'item_code' => $item['item_code'],
        'unit_name' => $item['unit_name'],
        'ordered_quantity' => $item['quantity'],
        'received_quantity' => 0,
        'pending_quantity' => $item['quantity'],
        'rate' => $item['rate'],
        'gst_percent' => isset($item['gst_percent']) ? $item['gst_percent'] : 0,
        'amount' => $item['quantity'] * $item['rate']
    );
}

echo json_encode([
    'success' => true,
    'supplier_id' => $po_data['supplier_id'],
    'po_number' => $po_data['po_number'],
    'items' => $items
]);
?>
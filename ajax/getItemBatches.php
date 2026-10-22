<?php
require_once(__DIR__ . '/../model/session_config.php');
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
include('../model/storeClass.php');
$newStore = new store;

header('Content-Type: application/json');

$item_id = isset($_POST['item_id']) ? intval($_POST['item_id']) : 0;

if($item_id <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid item ID']);
    exit;
}

// Get item details
$item_result = $newStore->getItem($item_id);
if(!$item_result || mysqli_num_rows($item_result) == 0) {
    echo json_encode(['success' => false, 'message' => 'Item not found']);
    exit;
}
$item = mysqli_fetch_assoc($item_result);

// Get item stock/batches
$stock = $newStore->getStockByItem($item_id);
$total_stock = 0;
$batches = array();

if($stock && mysqli_num_rows($stock) > 0) {
    while($row = mysqli_fetch_assoc($stock)) {
        $total_stock += $row['available_qty'];
        $batches[] = array(
            'batch_no' => $row['batch_no'] ? $row['batch_no'] : 'No Batch',
            'available_qty' => $row['available_qty'],
            'expiry_date' => $row['expiry_date']
        );
    }
}

echo json_encode([
    'success' => true,
    'unit_name' => $item['unit_name'],
    'category_id' => $item['category_id'],
    'current_stock' => $total_stock,
    'batches' => $batches
]);
?>

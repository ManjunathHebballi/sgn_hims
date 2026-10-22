<?php 
require_once(__DIR__ . '/../model/session_config.php');
include('../model/dbconnect.php');
 $newlogin = new userlogin;                  
        $db = $newlogin->dbconnect();
        
$item_id = isset($_POST['item_id']) ? intval($_POST['item_id']) : 0;
// $store_id = isset($_POST['store_id']) ? intval($_POST['store_id']) : 0;

if($item_id === 0 ) {
    echo json_encode([]);
    exit();
}

$query = "SELECT id, batch_number, expiry_date, available_quantity, cost_price, status
          FROM cathlab_batch_master
          WHERE item_id = ?  AND status = 'active' AND available_quantity > 0
          ORDER BY expiry_date ASC";


$stmt = mysqli_prepare($db, $query);
mysqli_stmt_bind_param($stmt, "i", $item_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$batches = [];
while($row = mysqli_fetch_assoc($result)) {
    $batches[] = [
        'id' => $row['id'],
        'batch_number' => $row['batch_number'],
        'expiry_date' => $row['expiry_date'],
        'available_quantity' => floatval($row['available_quantity']),
        'cost_price' => floatval($row['cost_price']),
        'status' => $row['status']
    ];
}

echo json_encode($batches);
?>

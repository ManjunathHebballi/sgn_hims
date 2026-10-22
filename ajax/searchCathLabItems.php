<?php 
require_once(__DIR__ . '/../model/session_config.php');
include('../model/dbconnect.php');

// Search for CathLab Items
$search = isset($_POST['search']) ? trim($_POST['search']) : '';

if(strlen($search) < 2) {
    echo json_encode([]);
    exit();
}

$search_term = '%' . mysqli_real_escape_string($db, $search) . '%';

$query = "SELECT 
            csi.id,
            csi.item_code,
            csi.item_name,
            csi.billing_price as price,
            csi.unit_cost,
            csi.current_stock,
            su.unit_name
          FROM cathlab_store_items csi
          LEFT JOIN store_unit su ON csi.unit_id = su.id
          WHERE csi.status = 'active'
          AND (csi.item_name LIKE ? OR csi.item_code LIKE ?)
          ORDER BY csi.current_stock DESC
          LIMIT 15";

$stmt = mysqli_prepare($db, $query);
mysqli_stmt_bind_param($stmt, "ss", $search_term, $search_term);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$items = [];
while($row = mysqli_fetch_assoc($result)) {
    $items[] = [
        'id' => $row['id'],
        'code' => $row['item_code'],
        'name' => $row['item_name'] . ' (' . $row['item_code'] . ')',
        'price' => floatval($row['price']),
        'unit_cost' => floatval($row['unit_cost']),
        'stock' => floatval($row['current_stock']),
        'unit' => $row['unit_name']
    ];
}

echo json_encode($items);
?>

<?php 
require_once(__DIR__ . '/model/session_config.php');
date_default_timezone_set("Asia/Kolkata");
include('model/cathLabClass.php');
$newStore = new cathLabStore;

$item_type = isset($_POST['item_type']) ? $_POST['item_type'] : '';
$stock_status = isset($_POST['stock_status']) ? $_POST['stock_status'] : '';
$search_term = isset($_POST['search_term']) ? $_POST['search_term'] : '';

// Get items based on filters
$items = $newStore->getCathLabItems();
$itemsArray = array();

if($items && mysqli_num_rows($items) > 0) {
    while($item = mysqli_fetch_assoc($items)) {
        $current_stock = $item['current_stock'];
        $min_stock = $item['min_stock'];
        
        // Determine status
        if($current_stock <= 0) {
            $item['status_class'] = 'critical';
            $item['status_text'] = 'Out of Stock';
        } elseif($current_stock <= $min_stock) {
            $item['status_class'] = 'low';
            $item['status_text'] = 'Low Stock';
        } else {
            $item['status_class'] = 'ok';
            $item['status_text'] = 'OK';
        }
        
        // Apply item type filter
        if($item_type && strtolower($item['item_type']) !== strtolower($item_type)) {
            continue;
        }
        
        // Apply stock status filter
        if($stock_status) {
            if($stock_status === 'ok' && $item['status_class'] !== 'ok') continue;
            if($stock_status === 'low' && $item['status_class'] !== 'low') continue;
            if($stock_status === 'critical' && $item['status_class'] !== 'critical') continue;
        }
        
        // Apply search term filter
        if($search_term) {
            $search_lower = strtolower($search_term);
            if(strpos(strtolower($item['item_code']), $search_lower) === false &&
               strpos(strtolower($item['item_name']), $search_lower) === false &&
               strpos(strtolower($item['category_name']), $search_lower) === false) {
                continue;
            }
        }
        
        $itemsArray[] = $item;
    }
}

echo json_encode(['items' => $itemsArray]);
exit();
?>

<?php 
require_once(__DIR__ . '/model/session_config.php');
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "Test: PHP is working<br>";

// Check if storeClass exists
if(file_exists('model/storeClass.php')) {
    echo "✓ storeClass.php found<br>";
    include('model/storeClass.php');
    
    try {
        $store = new store;
        echo "✓ Store class instantiated<br>";
        
        // Try to get inventory
        $inv = $store->getCathLabInventorySummary();
        echo "✓ getCathLabInventorySummary() executed<br>";
        echo "<pre>"; print_r($inv); echo "</pre>";
        
    } catch(Exception $e) {
        echo "✗ Error: " . $e->getMessage() . "<br>";
    }
} else {
    echo "✗ storeClass.php NOT found<br>";
}

// Check if leftbar exists
if(file_exists('leftbar.php')) {
    echo "✓ leftbar.php found<br>";
} else {
    echo "✗ leftbar.php NOT found<br>";
}

// Check if footer exists
if(file_exists('footer.php')) {
    echo "✓ footer.php found<br>";
} else {
    echo "✗ footer.php NOT found<br>";
}
?>

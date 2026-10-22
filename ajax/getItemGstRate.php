<?php
require_once(__DIR__ . '/../model/session_config.php');
include('../model/storeClass.php');
$newStore = new store;

if(isset($_POST['category_id'])) {
    $category_id = $_POST['category_id'];
    $gst_rate = $newStore->getGstRateForCategory($category_id);
    echo $gst_rate;
} else {
    echo 0;
}
?>

<?php
require_once(__DIR__ . '/model/session_config.php');
session_start();
session_destroy();
echo "<script>window.location='index.php';</script>";
?>


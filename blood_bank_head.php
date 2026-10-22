<?php
/**
 * Shared chrome for the blood bank screens (collection / screening / component
 * / stock / issue). Each page sets $page_title before including this, and
 * includes blood_bank_foot.php at the end.
 *
 * The five screens were added together and share one layout; keeping it here
 * means a change to the shell does not have to be repeated five times.
 * Flash messages arrive on the query string from controller/processBloodBank.php.
 */
if (!isset($_SESSION['loginid'])) {
    header('Location: index.php');
    exit;
}
$page_title = isset($page_title) ? $page_title : 'Blood Bank';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title><?= htmlspecialchars($page_title, ENT_QUOTES, 'UTF-8') ?></title>
    <link rel="icon" type="image/png" sizes="16x16" href="./images/favicon.png">
    <link rel="stylesheet" href="./vendor/owl-carousel/css/owl.carousel.min.css">
    <link rel="stylesheet" href="./vendor/owl-carousel/css/owl.theme.default.min.css">
    <link href="./vendor/jqvmap/css/jqvmap.min.css" rel="stylesheet">
    <link href="./css/style.css" rel="stylesheet">
    <link href="./vendor/datatables/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

    <style>
        .bb-stat h2   { margin-bottom: 0; font-weight: 600; }
        .bb-stat p    { margin-bottom: 0; }
        .bb-barcode   { font-family: monospace; font-size: 12.5px; }
        .bb-expiring  { background-color: #fff8e1; }
        .bb-expired   { background-color: #fdecea; }
        table.table td, table.table th { vertical-align: middle; }
    </style>
</head>

<body>

    <div id="preloader">
        <div class="sk-three-bounce">
            <div class="sk-child sk-bounce1"></div>
            <div class="sk-child sk-bounce2"></div>
            <div class="sk-child sk-bounce3"></div>
        </div>
    </div>

    <div id="main-wrapper">

        <?php include('leftbar.php') ?>

        <div class="content-body">
            <div class="container-fluid">

                <?php if (isset($_GET['success']) && $_GET['success'] !== '') { ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="icofont-check-circled"></i>
                        <?= htmlspecialchars($_GET['success'], ENT_QUOTES, 'UTF-8') ?>
                        <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                    </div>
                <?php } ?>
                <?php if (isset($_GET['error']) && $_GET['error'] !== '') { ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="icofont-close-circled"></i>
                        <?= htmlspecialchars($_GET['error'], ENT_QUOTES, 'UTF-8') ?>
                        <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                    </div>
                <?php } ?>

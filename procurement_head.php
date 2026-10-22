<?php
/**
 * Shared chrome for the procurement screens. Each page sets $page_title before
 * including this and includes procurement_foot.php at the end.
 * Flash messages arrive on the query string from controller/processProcurement.php.
 */
if (!isset($_SESSION['loginid'])) {
    header('Location: index.php');
    exit;
}
$page_title = isset($page_title) ? $page_title : 'Procurement';

if (!function_exists('pr_e')) {
    /** Escape for HTML output. */
    function pr_e($v) { return htmlspecialchars((string) $v, ENT_QUOTES, 'UTF-8'); }
}
if (!function_exists('pr_money')) {
    function pr_money($v) { return number_format((float) $v, 2); }
}
if (!function_exists('pr_badge')) {
    /** Colour a workflow status so its state reads at a glance. */
    function pr_badge($status)
    {
        $map = array(
            'DRAFT'            => 'badge-secondary',
            'SUBMITTED'        => 'badge-warning',
            'PENDING_APPROVAL' => 'badge-warning',
            'APPROVED'         => 'badge-success',
            'PUBLISHED'        => 'badge-primary',
            'UNDER_EVALUATION' => 'badge-info',
            'BIDDING_CLOSED'   => 'badge-info',
            'AWARDED'          => 'badge-success',
            'PO_GENERATED'     => 'badge-success',
            'TENDERED'         => 'badge-info',
            'CLOSED'           => 'badge-dark',
            'REJECTED'         => 'badge-danger',
            'CANCELLED'        => 'badge-danger',
        );
        $cls = isset($map[$status]) ? $map[$status] : 'badge-light';
        return '<span class="badge ' . $cls . '">'
             . htmlspecialchars(str_replace('_', ' ', (string) $status), ENT_QUOTES, 'UTF-8')
             . '</span>';
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title><?= pr_e($page_title) ?></title>
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
        .pr-stat h2  { margin-bottom: 0; font-weight: 600; }
        .pr-stat p   { margin-bottom: 0; }
        .pr-l1       { background-color: #e8f5e9; font-weight: 600; }
        .pr-dq       { background-color: #fdecea; text-decoration: line-through; opacity: .7; }
        .pr-num      { font-family: monospace; font-size: 12.5px; }
        .pr-timeline { list-style: none; padding-left: 0; }
        .pr-timeline li { padding: 8px 0; border-bottom: 1px solid #eee; font-size: 14px; }
        .pr-timeline li:last-child { border-bottom: 0; }
        table.table td, table.table th { vertical-align: middle; }
        td.num, th.num { text-align: right; font-variant-numeric: tabular-nums; }
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
                    <div class="alert alert-success alert-dismissible fade show">
                        <i class="icofont-check-circled"></i> <?= pr_e($_GET['success']) ?>
                        <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                    </div>
                <?php } ?>
                <?php if (isset($_GET['error']) && $_GET['error'] !== '') { ?>
                    <div class="alert alert-danger alert-dismissible fade show">
                        <i class="icofont-close-circled"></i> <?= pr_e($_GET['error']) ?>
                        <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                    </div>
                <?php } ?>

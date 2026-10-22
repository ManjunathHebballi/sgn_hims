<?php 
require_once(__DIR__ . '/model/session_config.php');
date_default_timezone_set("Asia/Kolkata");  
include('model/storeClass.php');
$newStore = new store;

if(!isset($_REQUEST['id'])) {
    echo "<script>window.location='list_store_adjustments.php'</script>";
    exit();
}

$adj_id = $_REQUEST['id'];
$adjustment = $newStore->getStockAdjustmentById($adj_id);

if(!$adjustment) {
    echo "<script>alert('Stock Adjustment not found!'); window.location='list_store_adjustments.php'</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="theme-color" content="#319197">

    <title>View Stock Adjustment - <?= $adjustment['adjustment_number']?></title>
    <link rel="icon" type="image/png" sizes="16x16" href="../images/favicon.png">
    <link href="css/style.css" rel="stylesheet">

    <style>
    .adjustment-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 20px;
        border-radius: 10px;
        margin-bottom: 20px;
    }

    .info-card {
        border: 1px solid #e3e6f0;
        border-radius: 8px;
        padding: 20px;
        height: 100%;
        background: #f8f9fc;
    }

    .info-card h5 {
        color: #667eea;
        font-weight: bold;
        margin-bottom: 15px;
        padding-bottom: 10px;
        border-bottom: 2px solid #667eea;
    }

    .info-label {
        font-weight: 600;
        color: #495057;
        display: inline-block;
        width: 150px;
    }

    .adjustment-summary {
        background: #f8f9fc;
        border: 2px solid #e3e6f0;
        border-radius: 8px;
        padding: 20px;
        margin-top: 20px;
    }

    .adjustment-summary-row {
        display: flex;
        justify-content: space-between;
        padding: 12px 0;
        font-size: 16px;
        border-bottom: 1px solid #e3e6f0;
    }

    .adjustment-summary-row:last-child {
        border-bottom: none;
    }

    .badge-add {
        background-color: #d4edda;
        color: #155724;
        padding: 8px 15px;
        border-radius: 20px;
        font-weight: bold;
    }

    .badge-remove {
        background-color: #f8d7da;
        color: #721c24;
        padding: 8px 15px;
        border-radius: 20px;
        font-weight: bold;
    }
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

        <?php include ('leftbar.php')?>

        <div class="content-body">

            <div class="container-fluid">

                <!-- Header -->
                <div class="adjustment-header">
                    <div class="row">
                        <div class="col-md-6">
                            <h4 class="mb-1">📦 Stock Adjustment</h4>
                            <h3><?= $adjustment['adjustment_number']?></h3>
                        </div>
                        <div class="col-md-6 text-right">
                            <?php if($adjustment['adjustment_type'] == 'ADD'): ?>
                                <span class="badge-add">+ ADD STOCK</span>
                            <?php else: ?>
                                <span class="badge-remove">- REMOVE STOCK</span>
                            <?php endif; ?>
                            <br><br>
                            <a href="list_store_adjustments.php" class="btn btn-info btn-sm">
                                <i class="fa fa-arrow-left"></i> Back to List
                            </a>
                            <a href="javascript:window.print()" class="btn btn-light btn-sm">
                                <i class="fa fa-print"></i> Print
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Details -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="info-card">
                            <h5><i class="fa fa-info-circle"></i> ADJUSTMENT INFORMATION</h5>
                            <p><span class="info-label">Adjustment No:</span> <?= $adjustment['adjustment_number']?></p>
                            <p><span class="info-label">Date:</span> <?= date('d-M-Y', strtotime($adjustment['adjustment_date']))?></p>
                            <p><span class="info-label">Reason:</span> <?= $adjustment['reason']?></p>
                            <?php if($adjustment['remarks']): ?>
                            <p><span class="info-label">Remarks:</span> <?= $adjustment['remarks']?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-card">
                            <h5><i class="fa fa-user"></i> ADJUSTMENT BY</h5>
                            <p><span class="info-label">Adjusted By:</span> <?= $adjustment['adjusted_by']?></p>
                            <p><span class="info-label">Created:</span> <?= date('d-M-Y h:i A', strtotime($adjustment['created_at']))?></p>
                            <?php if($adjustment['updated_at'] != $adjustment['created_at']): ?>
                            <p><span class="info-label">Updated:</span> <?= date('d-M-Y h:i A', strtotime($adjustment['updated_at']))?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Item Details -->
                <div class="row mt-3">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header bg-primary text-white">
                                <h5>Item Details</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        <h6 class="font-weight-bold">Item Code</h6>
                                        <p class="text-muted"><?= $adjustment['item_code']?></p>
                                    </div>
                                    <div class="col-md-4">
                                        <h6 class="font-weight-bold">Item Name</h6>
                                        <p class="text-muted"><?= $adjustment['item_name']?></p>
                                    </div>
                                    <div class="col-md-2">
                                        <h6 class="font-weight-bold">Unit</h6>
                                        <p class="text-muted"><?= $adjustment['unit_name']?></p>
                                    </div>
                                    <div class="col-md-3">
                                        <h6 class="font-weight-bold">Batch Number</h6>
                                        <p class="text-muted">
                                            <?php if($adjustment['batch_no']): ?>
                                                <span class="badge badge-info"><?= $adjustment['batch_no']?></span>
                                            <?php else: ?>
                                                <span class="text-danger">—</span>
                                            <?php endif; ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Summary -->
                <div class="adjustment-summary">
                    <div class="adjustment-summary-row">
                        <span><strong>Adjustment Type:</strong></span>
                        <span>
                            <?php if($adjustment['adjustment_type'] == 'ADD'): ?>
                                <span class="badge badge-success">ADD STOCK</span>
                            <?php else: ?>
                                <span class="badge badge-danger">REMOVE STOCK</span>
                            <?php endif; ?>
                        </span>
                    </div>
                    <div class="adjustment-summary-row">
                        <span><strong>Adjustment Quantity:</strong></span>
                        <span class="font-weight-bold text-primary" style="font-size: 18px;">
                            <?php if($adjustment['adjustment_type'] == 'ADD'): ?>
                                +<?= number_format($adjustment['adjustment_qty'], 2)?>
                            <?php else: ?>
                                -<?= number_format($adjustment['adjustment_qty'], 2)?>
                            <?php endif; ?>
                            <?= $adjustment['unit_name']?>
                        </span>
                    </div>
                    <div class="adjustment-summary-row">
                        <span><strong>Reason:</strong></span>
                        <span class="font-weight-bold"><?= $adjustment['reason']?></span>
                    </div>
                </div>

                <!-- Footer Info -->
                <div class="row mt-3">
                    <div class="col-md-12">
                        <div class="alert alert-info">
                            <i class="fa fa-info-circle"></i>
                            <strong>Created:</strong> <?= date('d-M-Y h:i A', strtotime($adjustment['created_at']))?> 
                            by User ID: <?= $adjustment['adjusted_by']?>
                            <?php if($adjustment['updated_at'] != $adjustment['created_at']): ?>
                            | <strong>Last Updated:</strong> <?= date('d-M-Y h:i A', strtotime($adjustment['updated_at']))?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

            </div>

        </div>

        <?php include('footer.php') ?>

    </div>

    <script src="vendor/global/global.min.js"></script>
    <script src="js/quixnav-init.js"></script>
    <script src="js/custom.min.js"></script>

</body>

</html>

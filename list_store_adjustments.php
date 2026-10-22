<?php 
require_once(__DIR__ . '/model/session_config.php');

date_default_timezone_set("Asia/Kolkata");  
include('model/storeClass.php');
$newStore = new store;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="theme-color" content="#319197">

    <title>Stock Adjustments List</title>
    <link rel="icon" type="image/png" sizes="16x16" href="../images/favicon.png">
    <link href="css/style.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap4.min.css">

    <style>
    .badge-add {
        background-color: #d4edda;
        color: #155724;
        font-weight: bold;
    }

    .badge-remove {
        background-color: #f8d7da;
        color: #721c24;
        font-weight: bold;
    }

    .reason-badge {
        display: inline-block;
        padding: 5px 10px;
        border-radius: 3px;
        background-color: #e7f3ff;
        color: #004085;
        font-size: 12px;
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
                <div class="row mb-3">
                    <div class="col-md-6">
                        <h4 class="text-uppercase">📦 Stock Adjustments</h4>
                    </div>
                    <div class="col-md-6 text-right">
                        <a href="add_store_adjustment.php" class="btn btn-primary">
                            <i class="fa fa-plus"></i> New Adjustment
                        </a>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="adjustmentTable" class="table table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th>Adjustment No</th>
                                                <th>Date</th>
                                                <th>Item</th>
                                                <th>Batch</th>
                                                <th>Type</th>
                                                <th>Quantity</th>
                                                <th>Reason</th>
                                                <th>Adjusted By</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                            $adjustments = $newStore->getStockAdjustments();
                                            while($adj = mysqli_fetch_assoc($adjustments)):
                                            ?>
                                            <tr>
                                                <td>
                                                    <strong><?= $adj['adjustment_number']?></strong>
                                                </td>
                                                <td><?= date('d-M-Y', strtotime($adj['adjustment_date']))?></td>
                                                <td>
                                                    <strong><?= $adj['item_code']?></strong><br>
                                                    <small class="text-muted"><?= $adj['item_name']?></small>
                                                </td>
                                                <td>
                                                    <?php if($adj['batch_no']): ?>
                                                        <span class="badge badge-info"><?= $adj['batch_no']?></span>
                                                    <?php else: ?>
                                                        <span class="text-muted">—</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php if($adj['adjustment_type'] == 'ADD'): ?>
                                                        <span class="badge badge-add">+ ADD</span>
                                                    <?php else: ?>
                                                        <span class="badge badge-remove">- REMOVE</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="text-right">
                                                    <strong><?= number_format($adj['adjustment_qty'], 2)?> <?= $adj['unit_name']?></strong>
                                                </td>
                                                <td>
                                                    <span class="reason-badge"><?= $adj['reason']?></span>
                                                </td>
                                                <td><?= $adj['adjusted_by']?></td>
                                                <td>
                                                    <a href="view_store_adjustment.php?id=<?= $adj['id']?>"
                                                        class="btn btn-sm btn-info" title="View Details">
                                                        <i class="fa fa-eye"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                            <?php endwhile; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
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
    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap4.min.js"></script>

    <script>
    $(document).ready(function() {
        $('#adjustmentTable').DataTable({
            "order": [
                [1, "desc"]
            ],
            "pageLength": 25
        });
    });
    </script>

</body>

</html>

<?php 
require_once(__DIR__ . '/model/session_config.php');
date_default_timezone_set("Asia/Kolkata");  
include('model/storeClass.php');
$newStore = new store;

if(!isset($_REQUEST['id'])) {
    echo "<script>window.location='list_store_po.php'</script>";
    exit();
}

$po_id = $_REQUEST['id'];
$po= $newStore->getPurchaseOrderById($po_id);


if(!$po) {
    echo "<script>alert('Purchase Order not found!'); window.location='list_store_po.php'</script>";
    exit();
}

$po_items = $newStore->getPOItemsByPOId($po_id);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="theme-color" content="#319197">

    <title>View Purchase Order - <?= $po['po_number']?></title>
    <link rel="icon" type="image/png" sizes="16x16" href="../images/favicon.png">
    <link href="css/style.css" rel="stylesheet">

    <style>
    .po-header {
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

    .po-items-table thead {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .po-items-table thead th {
        font-weight: 600;
        border: none;
        padding: 12px 8px;
    }

    .summary-box {
        background: #f8f9fc;
        border: 2px solid #e3e6f0;
        border-radius: 8px;
        padding: 20px;
        margin-top: 20px;
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        padding: 8px 0;
        font-size: 14px;
    }

    .summary-row.total {
        border-top: 2px solid #667eea;
        padding-top: 12px;
        margin-top: 10px;
        font-size: 18px;
        font-weight: bold;
        color: #667eea;
    }

    .badge-pending {
        background-color: #ffc107;
        color: #000;
        padding: 5px 10px;
        border-radius: 5px;
    }

    .badge-approved {
        background-color: #007bff;
        color: white;
        padding: 5px 10px;
        border-radius: 5px;
    }

    .badge-partially-received {
        background-color: #17a2b8;
        color: white;
        padding: 5px 10px;
        border-radius: 5px;
    }

    .badge-completed {
        background-color: #28a745;
        color: white;
        padding: 5px 10px;
        border-radius: 5px;
    }

    .badge-cancelled {
        background-color: #dc3545;
        color: white;
        padding: 5px 10px;
        border-radius: 5px;
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
                <div class="po-header">
                    <div class="row">
                        <div class="col-md-6">
                            <h4 class="mb-1">📦 Purchase Order</h4>
                            <h3><?= $po['po_number']?></h3>
                        </div>
                        <div class="col-md-6 text-right">
                            <?php
                            $status = $po['status'];
                            if($status == 'PENDING') {
                                echo '<span class="badge-pending">PENDING</span>';
                            } else if($status == 'APPROVED') {
                                echo '<span class="badge-approved">APPROVED</span>';
                            } else if($status == 'PARTIALLY_RECEIVED') {
                                echo '<span class="badge-partially-received">PARTIALLY RECEIVED</span>';
                            } else if($status == 'COMPLETED' || $status == 'RECEIVED') {
                                echo '<span class="badge-completed">RECEIVED</span>';
                            } else if($status == 'CANCELLED') {
                                echo '<span class="badge-cancelled">CANCELLED</span>';
                            }
                            ?>
                            <br><br>
                            <a href="list_store_po.php" class="btn btn-info btn-sm">
                                <i class="fa fa-arrow-left"></i> Back to List
                            </a>
                            <a href="print_store_po.php?id=<?= $po['id']?>" target="_blank"
                                class="btn btn-light btn-sm">
                                <i class="fa fa-print"></i> Print PO
                            </a>
                            <?php if($status == 'PENDING'): ?>
                            <a href="add_store_po.php?id=<?= $po['id']?>" class="btn btn-warning btn-sm">
                                <i class="fa fa-edit"></i> Edit PO
                            </a>
                            <?php if(isset($_SESSION['role']) && $_SESSION['role'] == 'Admin'): ?>
                            <form action="controller/processStore.php" method="POST" class="d-inline"
                                onsubmit="return confirm('Approve PO <?= $po['po_number']?>? Once approved, GRN can be created against it.');">
                                <input type="hidden" name="po_id" value="<?= $po['id']?>">
                                <button type="submit" name="submit" value="approve_po" class="btn btn-primary btn-sm">
                                    <i class="fa fa-check"></i> Approve PO
                                </button>
                            </form>
                            <?php else: ?>
                            <button type="button" class="btn btn-secondary btn-sm" disabled title="Only Admin can approve PO">
                                <i class="fa fa-shield"></i> Admin Approval Required
                            </button>
                            <?php endif; ?>
                            <button type="button" class="btn btn-secondary btn-sm" disabled>
                                <i class="fa fa-lock"></i> Approve to Create GRN
                            </button>
                            <?php endif; ?>
                            <?php if($status == 'APPROVED' || $status == 'PARTIALLY_RECEIVED'): ?>
                            <a href="add_store_grn.php?po_id=<?= $po['id']?>" class="btn btn-success btn-sm">
                                <i class="fa fa-truck"></i> Create GRN
                            </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- PO Details -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="info-card">
                            <h5><i class="fa fa-info-circle"></i> PO INFORMATION</h5>
                            <p><span class="info-label">PO Number:</span> <?= $po['po_number']?></p>
                            <p><span class="info-label">PO Date:</span>
                                <?= date('d-M-Y', strtotime($po['po_date']))?></p>
                            <?php if($po['expected_date']): ?>
                            <p><span class="info-label">Expected Delivery:</span>
                                <?= date('d-M-Y', strtotime($po['expected_date']))?></p>
                            <?php endif; ?>
                            <?php if($po['payment_terms']): ?>
                            <p><span class="info-label">Payment Terms:</span> <?= $po['payment_terms']?></p>
                            <?php endif; ?>
                            <?php if($po['remarks']): ?>
                            <p><span class="info-label">Remarks:</span> <?= $po['remarks']?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-card">
                            <h5><i class="fa fa-truck"></i> SUPPLIER DETAILS</h5>
                            <p><span class="info-label">Supplier Name:</span> <?= $po['supplier_name']?></p>
                            <?php if($po['phone']): ?>
                            <p><span class="info-label">Phone:</span> <?= $po['phone']?></p>
                            <?php endif; ?>
                            <?php if($po['email']): ?>
                            <p><span class="info-label">Email:</span> <?= $po['email']?></p>
                            <?php endif; ?>
                            <?php if($po['address']): ?>
                            <p><span class="info-label">Address:</span> <?= $po['address']?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- PO Items -->
                <div class="row mt-3">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header bg-primary text-white">
                                <h5>Purchase Order Items</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered po-items-table">
                                        <thead>
                                            <tr>
                                                <th width="4%">Sl</th>
                                                <th width="10%">Item Code</th>
                                                <th width="24%">Item Description</th>
                                                <th width="8%">Unit</th>
                                                <th width="8%" class="text-right">Qty</th>
                                                <th width="8%" class="text-right">Rate</th>
                                                <th width="9%" class="text-right">Amount</th>
                                                <th width="6%" class="text-right">GST%</th>
                                                <th width="9%" class="text-right">GST Amt</th>
                                                <th width="10%" class="text-right">Net Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                            $sn = 1;
                                            $total_qty = 0;
                                            $total_amount = 0;
                                            $total_gst = 0;
                                            $total_net = 0;
                                            mysqli_data_seek($po_items, 0);
                                            while($item = mysqli_fetch_assoc($po_items)):
                                                $item_amount = $item['quantity'] * $item['rate'];
                                                $gst_percent = isset($item['gst_percent']) ? $item['gst_percent'] : 0;
                                                $gst_amount = isset($item['gst_amount']) ? $item['gst_amount'] : 0;
                                                $net_amount = isset($item['net_amount']) ? $item['net_amount'] : $item_amount;
                                                
                                                $total_qty += $item['quantity'];
                                                $total_amount += $item_amount;
                                                $total_gst += $gst_amount;
                                                $total_net += $net_amount;
                                            ?>
                                            <tr>
                                                <td class="text-center"><?= $sn?></td>
                                                <td><?= $item['item_code']?></td>
                                                <td><?= $item['item_name']?></td>
                                                <td class="text-center"><?= $item['unit_name']?></td>
                                                <td class="text-right"><?= number_format($item['quantity'], 2)?></td>
                                                <td class="text-right">₹<?= number_format($item['rate'], 2)?></td>
                                                <td class="text-right">₹<?= number_format($item_amount, 2)?></td>
                                                <td class="text-right"><?= number_format($gst_percent, 2)?>%</td>
                                                <td class="text-right">₹<?= number_format($gst_amount, 2)?></td>
                                                <td class="text-right font-weight-bold">₹<?= number_format($net_amount, 2)?></td>
                                            </tr>
                                            <?php 
                                            $sn++;
                                            endwhile; 
                                            ?>
                                        </tbody>
                                        <tfoot style="background-color: #f8f9fa; font-weight: bold;">
                                            <tr>
                                                <td colspan="4" class="text-right">Total Items: <strong><?= $sn-1?></strong></td>
                                                <td class="text-right"><strong><?= number_format($total_qty, 2)?></strong></td>
                                                <td colspan="1"></td>
                                                <td class="text-right"><strong>₹<?= number_format($total_amount, 2)?></strong></td>
                                                <td colspan="1"></td>
                                                <td class="text-right"><strong>₹<?= number_format($total_gst, 2)?></strong></td>
                                                <td class="text-right"><strong>₹<?= number_format($total_net, 2)?></strong></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Summary -->
                <div class="row mt-3">
                    <div class="col-md-8"></div>
                    <div class="col-md-4">
                        <div class="summary-box">
                            <div class="summary-row" style="background-color: #f0f0f0; padding: 10px; border-radius: 4px;">
                                <span><strong>Base Amount (Before Tax):</strong></span>
                                <span><strong>₹<?= number_format($total_amount, 2)?></strong></span>
                            </div>
                            <div class="summary-row" style="background-color: #fff3cd; padding: 10px; margin-top: 8px; border-radius: 4px;">
                                <span><strong>Total GST:</strong></span>
                                <span><strong style="color: #856404;">₹<?= number_format($total_gst, 2)?></strong></span>
                            </div>
                            <div class="summary-row total" style="background-color: #f8d7da; padding: 12px; margin-top: 8px; border-radius: 4px; font-size: 16px;">
                                <span><strong>TOTAL PAYABLE:</strong></span>
                                <span><strong style="color: #721c24;">₹<?= number_format($total_net, 2)?></strong></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer Info -->
                <div class="row mt-3">
                    <div class="col-md-12">
                        <div class="alert alert-info">
                            <i class="fa fa-info-circle"></i>
                            <strong>Created:</strong> <?= date('d-M-Y h:i A', strtotime($po['created_at']))?> by User
                            ID:
                            <?= $po['created_by']?>
                            <?php if($po['updated_at'] != $po['created_at']): ?>
                            | <strong>Last Updated:</strong> <?= date('d-M-Y h:i A', strtotime($po['updated_at']))?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->


            </div>

        </div>

        <?php include('footer.php') ?>

    </div>

    <script src="vendor/global/global.min.js"></script>
    <script src="js/quixnav-init.js"></script>
    <script src="js/custom.min.js"></script>

</body>

</html>
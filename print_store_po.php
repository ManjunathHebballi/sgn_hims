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
$po = $newStore->getPurchaseOrderById($po_id);

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
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Print Purchase Order - <?= $po['po_number']?></title>

    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #f8f9fa;
        padding: 20px 0;
    }

    .po-wrapper {
        background: white;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        max-width: 210mm;
        margin: 0 auto;
        padding: 30px;
    }

    .header-logo {
        max-width: 100px;
        max-height: 100px;
    }

    .po-header {
        border-bottom: 3px solid #667eea;
        padding-bottom: 20px;
        margin-bottom: 30px;
    }

    .hospital-name {
        font-size: 26px;
        font-weight: bold;
        color: #2c3e50;
        margin-bottom: 5px;
    }

    .hospital-location {
        font-size: 14px;
        color: #6c757d;
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

    .info-card p {
        margin-bottom: 8px;
        font-size: 13px;
    }

    .info-label {
        font-weight: 600;
        color: #495057;
        display: inline-block;
        width: 130px;
    }

    .table-po {
        font-size: 12px;
        margin-top: 20px;
    }

    .table-po thead {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .table-po thead th {
        font-weight: 600;
        border: none;
        padding: 12px 8px;
    }

    .table-po tbody td {
        padding: 10px 8px;
        vertical-align: middle;
    }

    .table-po tfoot {
        background-color: #f8f9fc;
        font-weight: bold;
    }

    .summary-card {
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

    .badge-status {
        padding: 5px 15px;
        border-radius: 5px;
        font-size: 14px;
        font-weight: bold;
    }

    .badge-pending {
        background-color: #ffc107;
        color: #000;
    }

    .badge-partial {
        background-color: #17a2b8;
        color: white;
    }

    .badge-completed {
        background-color: #28a745;
        color: white;
    }

    .badge-cancelled {
        background-color: #dc3545;
        color: white;
    }

    .signature-box {
        text-align: center;
        margin-top: 80px;
    }

    .signature-line {
        border-top: 2px solid #333;
        display: inline-block;
        width: 200px;
        margin-top: 60px;
        padding-top: 8px;
        font-weight: 600;
    }

    @media print {
        body {
            padding: 0;
            margin: 0;
            background: white;
        }

        .no-print {
            display: none !important;
        }

        .po-wrapper {
            box-shadow: none;
            padding: 15px;
            max-width: 100%;
            margin: 0;
        }

        .po-header {
            margin-bottom: 15px;
            padding-bottom: 10px;
        }

        .info-card {
            padding: 15px;
        }

        .summary-card {
            margin-top: 10px;
        }

        .signature-box {
            margin-top: 40px;
        }

        .signature-line {
            margin-top: 40px;
        }

        /* Force columns to stay side by side in print */
        .col-md-6 {
            width: 50%;
            float: left;
        }

        .col-md-4 {
            width: 33.333333%;
            float: left;
        }

        .col-md-8 {
            width: 66.666667%;
            float: left;
        }

        .row::after {
            content: "";
            display: table;
            clear: both;
        }

        .summary-card {
            page-break-inside: avoid;
            background: #f8f9fc !important;
            border: 2px solid #e3e6f0 !important;
        }

        .summary-row {
            page-break-inside: avoid;
        }

        @page {
            margin: 5mm;
            size: A4;
        }
    }

    .print-button {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 1000;
    }
    </style>

</head>

<body>

    <button onclick="window.print()" class="btn btn-primary print-button no-print">
        <i class="fas fa-print"></i> Print Purchase Order
    </button>

    <div class="po-wrapper">
        <div class="container-fluid">

            <!-- Header -->
            <div class="po-header">
                <div class="row align-items-center mb-3">
                    <div class="col-sm-2 text-center">
                        <img src="images/logo.png" class="header-logo" alt="Hospital Logo">
                    </div>
                    <div class="col-sm-10 text-center">
                        <h2 class="hospital-name mb-1"><?= $_SESSION['college_name']?></h2>
                        <p class="hospital-location mb-0"><?= $_SESSION['college_location']?></p>
                    </div>
                </div>

                <h4 class="text-center font-weight-bold mb-3">Store Department - Purchase Order</h4>

                <div class="d-flex justify-content-between align-items-center text-muted">
                    <div>
                        <p class="mb-0"><i class="fas fa-file-invoice"></i> PO Number:
                            <strong><?= $po['po_number']?></strong>
                        </p>
                        <p class="mb-0"><i class="far fa-calendar-alt"></i> PO Date:
                            <strong><?= date('d/m/Y', strtotime($po['po_date']))?></strong>
                        </p>
                    </div>
                    <div>
                        <?php
                        $status = $po['status'];
                        if($status == 'PENDING') {
                            echo '<span class="badge-status badge-pending">PENDING</span>';
                        } else if($status == 'PARTIALLY_RECEIVED') {
                            echo '<span class="badge-status badge-partial">PARTIAL</span>';
                        } else if($status == 'COMPLETED') {
                            echo '<span class="badge-status badge-completed">COMPLETED</span>';
                        } else if($status == 'CANCELLED') {
                            echo '<span class="badge-status badge-cancelled">CANCELLED</span>';
                        }
                        ?>
                    </div>
                </div>
            </div>

            <!-- Supplier & PO Info -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="info-card">
                        <h5><i class="fas fa-truck"></i> SUPPLIER DETAILS</h5>
                        <p><span class="info-label">Supplier Name:</span> <?= $po['supplier_name']?></p>
                        <?php if($po['phone']) { ?>
                        <p><span class="info-label">Phone:</span> <?= $po['phone']?></p>
                        <?php } ?>
                        <?php if($po['email']) { ?>
                        <p><span class="info-label">Email:</span> <?= $po['email']?></p>
                        <?php } ?>
                        <?php if($po['address']) { ?>
                        <p><span class="info-label">Address:</span> <?= $po['address']?></p>
                        <?php } ?>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="info-card">
                        <h5><i class="fas fa-info-circle"></i> ORDER INFORMATION</h5>
                        <?php if($po['expected_date']) { ?>
                        <p><span class="info-label">Expected Delivery:</span>
                            <?= date('d-M-Y', strtotime($po['expected_date']))?></p>
                        <?php } ?>
                        <?php if($po['payment_terms']) { ?>
                        <p><span class="info-label">Payment Terms:</span> <?= $po['payment_terms']?></p>
                        <?php } ?>
                        <p><span class="info-label">Status:</span>
                            <?php
                            if($status == 'PENDING') {
                                echo '<span class="text-warning">Pending</span>';
                            } else if($status == 'PARTIALLY_RECEIVED') {
                                echo '<span class="text-info">Partially Received</span>';
                            } else if($status == 'COMPLETED') {
                                echo '<span class="text-success">Completed</span>';
                            } else if($status == 'CANCELLED') {
                                echo '<span class="text-danger">Cancelled</span>';
                            }
                            ?>
                        </p>
                        <?php if($po['remarks']) { ?>
                        <p><span class="info-label">Remarks:</span> <?= $po['remarks']?></p>
                        <?php } ?>
                    </div>
                </div>
            </div>

            <!-- Items Table -->
            <div class="table-responsive">
                <table class="table table-po table-bordered table-hover">
                    <thead>
                        <tr>
                            <th width="3%">Sl</th>
                            <th width="10%">Item Code</th>
                            <th width="22%">Item Description</th>
                            <th width="8%">Unit</th>
                            <th width="10%" class="text-right">Qty</th>
                            <th width="8%" class="text-right">Rate</th>
                            <th width="8%" class="text-right">Amount</th>
                            <th width="6%" class="text-right">GST%</th>
                            <th width="10%" class="text-right">GST Amt</th>
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
                        while($item = mysqli_fetch_assoc($po_items)) {
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
                        } 
                        ?>
                    </tbody>
                    <tfoot>
                        <tr style="background-color: #f5f5f5; font-weight: bold;">
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

            <!-- Summary Section -->
            <div class="row mt-4">
                <div class="col-md-8"></div>
                <div class="col-md-4">
                    <div class="summary-card">
                        <div class="summary-row" style="background-color: #f0f0f0;">
                            <span><strong>Base Amount (Before Tax):</strong></span>
                            <span><strong>₹<?= number_format($total_amount, 2)?></strong></span>
                        </div>
                        <div class="summary-row" style="background-color: #fff3cd;">
                            <span><strong>Total GST:</strong></span>
                            <span><strong style="color: #856404;">₹<?= number_format($total_gst, 2)?></strong></span>
                        </div>
                        <div class="summary-row total" style="background-color: #f8d7da; font-size: 16px;">
                            <span><strong>TOTAL PAYABLE:</strong></span>
                            <span><strong style="color: #721c24;">₹<?= number_format($total_net, 2)?></strong></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Notes -->
            <?php if($po['remarks']) { ?>
            <div class="alert alert-warning mt-4" role="alert">
                <strong><i class="fas fa-sticky-note"></i> Special Instructions:</strong> <?= $po['remarks']?>
            </div>
            <?php } ?>

            <!-- Terms & Conditions -->
            <div class="mt-4">
                <h6 class="font-weight-bold">Terms & Conditions:</h6>
                <ol style="font-size: 11px; line-height: 1.6;">
                    <li>Please supply the goods as per specifications mentioned above.</li>
                    <li>Delivery should be made on or before the expected delivery date.</li>
                    <li>All items must be properly packed and labeled.</li>
                    <li>Payment will be made as per the agreed payment terms.</li>
                    <li>Goods once supplied will not be taken back or exchanged.</li>
                </ol>
            </div>

            <!-- Footer -->
            <div class="row mt-5 pt-4 border-top">
                <div class="col-md-4">
                    <div class="signature-box">
                        <div class="signature-line">Prepared By</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="signature-box">
                        <div class="signature-line">Verified By</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="signature-box">
                        <div class="signature-line">Authorized Signature</div>
                    </div>
                </div>
            </div>

            <div class="text-center text-muted mt-4" style="font-size: 11px;">
                <p class="mb-1">PO Created: <?= date('d-M-Y h:i A', strtotime($po['created_at']))?> | Created By:
                    <?= $po['created_by']?></p>
                <p class="mb-0">Printed on: <?= date('d-M-Y h:i A')?> | This is a computer-generated purchase order</p>
            </div>

        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <script>
    // Auto print on load (optional)
    // window.onload = function() { window.print(); }
    </script>

</body>

</html>
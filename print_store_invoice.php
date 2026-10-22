<?php 
require_once(__DIR__ . '/model/session_config.php');
date_default_timezone_set("Asia/Kolkata");  
include('model/storeClass.php');
$newStore = new store;

if(!isset($_REQUEST['id'])) {
    echo "<script>window.location='store_invoice_list.php'</script>";
    exit();
}

$invoice_id = $_REQUEST['id'];
$invoice_result = $newStore->getPurchaseInvoice($invoice_id);
$invoice = mysqli_fetch_assoc($invoice_result);

if(!$invoice) {
    echo "<script>alert('Invoice not found!'); window.location='store_invoice_list.php'</script>";
    exit();
}

$items = $newStore->getPurchaseInvoiceItems($invoice_id);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Print Invoice - <?= $invoice['invoice_number']?></title>

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

    .invoice-wrapper {
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

    .invoice-header {
        border-bottom: 3px solid #007bff;
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

    .invoice-badge {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 15px;
        border-radius: 10px;
        text-align: center;
        margin: 20px 0;
    }

    .invoice-badge h4 {
        margin: 0;
        font-size: 20px;
        letter-spacing: 1px;
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

    .table-invoice {
        font-size: 12px;
        margin-top: 20px;
    }

    .table-invoice thead {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .table-invoice thead th {
        font-weight: 600;
        border: none;
        padding: 12px 8px;
    }

    .table-invoice tbody td {
        padding: 10px 8px;
        vertical-align: middle;
    }

    .table-invoice tfoot {
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

    .summary-row.paid {
        color: #28a745;
        font-weight: 600;
    }

    .summary-row.balance {
        color: #dc3545;
        font-weight: 600;
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

        .invoice-wrapper {
            box-shadow: none;
            padding: 15px;
            max-width: 100%;
            margin: 0;
        }

        .invoice-header {
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

        .col-md-5 {
            width: 41.666667%;
            float: right;
        }

        .col-md-7 {
            width: 58.333333%;
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
        <i class="fas fa-print"></i> Print Invoice
    </button>

    <div class="invoice-wrapper">
        <div class="container-fluid">

            <!-- Header -->
            <div class="invoice-header">
                <div class="row align-items-center mb-3">
                    <div class="col-sm-2 text-center">
                        <img src="images/logo.png" class="header-logo" alt="Hospital Logo">
                    </div>
                    <div class="col-sm-10 text-center">
                        <h2 class="hospital-name mb-1"><?= $_SESSION['college_name']?></h2>
                        <p class="hospital-location mb-0"><?= $_SESSION['college_location']?></p>
                    </div>
                </div>

                <h4 class="text-center font-weight-bold mb-3">Store Department - Purchase Invoice</h4>

                <div class="d-flex justify-content-between text-muted">
                    <p class="mb-0"><i class="fas fa-file-invoice"></i> Invoice Number:
                        <strong><?= $invoice['invoice_number']?></strong>
                    </p>
                    <p class="mb-0"><i class="far fa-calendar-alt"></i> Invoice Date:
                        <strong><?= date('d/m/Y', strtotime($invoice['invoice_date']))?></strong>
                    </p>
                </div>
            </div>

            <!-- Supplier & Invoice Info -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="info-card">
                        <h5><i class="fas fa-truck"></i> SUPPLIER DETAILS</h5>
                        <p><span class="info-label">Supplier Name:</span> <?= $invoice['supplier_name']?></p>
                        <?php if($invoice['phone']) { ?>
                        <p><span class="info-label">Phone:</span> <?= $invoice['phone']?></p>
                        <?php } ?>
                        <?php if($invoice['email']) { ?>
                        <p><span class="info-label">Email:</span> <?= $invoice['email']?></p>
                        <?php } ?>
                        <?php if($invoice['address']) { ?>
                        <p><span class="info-label">Address:</span> <?= $invoice['address']?></p>
                        <?php } ?>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="info-card">
                        <h5><i class="fas fa-info-circle"></i> INVOICE INFORMATION</h5>
                        <p><span class="info-label">Due Date:</span>
                            <?= date('d-M-Y', strtotime($invoice['due_date']))?></p>
                        <p><span class="info-label">Payment Terms:</span> <?= $invoice['payment_terms']?></p>
                        <p><span class="info-label">Payment Status:</span>
                            <?php 
                            if($invoice['payment_status'] == 'UNPAID') {
                                echo '<span class="badge badge-danger">UNPAID</span>';
                            } elseif($invoice['payment_status'] == 'PARTIALLY_PAID') {
                                echo '<span class="badge badge-warning">PARTIALLY PAID</span>';
                            } else {
                                echo '<span class="badge badge-success">PAID</span>';
                            }
                            ?>
                        </p>
                        <?php if($invoice['remarks']) { ?>
                        <p><span class="info-label">Remarks:</span> <?= $invoice['remarks']?></p>
                        <?php } ?>
                    </div>
                </div>
            </div>

            <!-- Items Table -->
            <div class="table-responsive">
                <table class="table table-invoice table-bordered table-hover">
                    <thead>
                        <tr>
                            <th width="4%">Sl</th>
                            <th width="12%">Item Code</th>
                            <th width="22%">Item Description</th>
                            <th width="10%">Batch No</th>
                            <th width="8%">Qty</th>
                            <th width="8%">Rate</th>
                            <th width="8%">Amount</th>
                            <th width="6%">Tax%</th>
                            <th width="8%">Tax</th>
                            <th width="6%">Disc%</th>
                            <th width="10%">Net Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $sn = 1;
                        $total_qty = 0;
                        mysqli_data_seek($items, 0);
                        while($item = mysqli_fetch_assoc($items)) {
                            $total_qty += $item['quantity'];
                        ?>
                        <tr>
                            <td class="text-center"><?= $sn?></td>
                            <td><?= $item['item_code']?></td>
                            <td><?= $item['item_name']?> <small class="text-muted">(<?= $item['unit_name']?>)</small>
                            </td>
                            <td class="text-center"><?= $item['batch_no'] ? $item['batch_no'] : '-'?></td>
                            <td class="text-right"><?= number_format($item['quantity'], 2)?></td>
                            <td class="text-right">₹<?= number_format($item['rate'], 2)?></td>
                            <td class="text-right">₹<?= number_format($item['amount'], 2)?></td>
                            <td class="text-center"><?= $item['tax_percent']?>%</td>
                            <td class="text-right">₹<?= number_format($item['tax_amount'], 2)?></td>
                            <td class="text-center"><?= $item['discount_percent']?>%</td>
                            <td class="text-right font-weight-bold">₹<?= number_format($item['net_amount'], 2)?></td>
                        </tr>
                        <?php 
                        $sn++;
                        } 
                        ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="4" class="text-right">Total Items: <strong><?= $sn-1?></strong></td>
                            <td class="text-right"><strong><?= number_format($total_qty, 2)?></strong></td>
                            <td colspan="6"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <!-- Summary Section -->
            <div class="row">
                <div class="col-md-7"></div>
                <div class="col-md-5">
                    <div class="summary-card">
                        <div class="summary-row">
                            <span>Sub Total:</span>
                            <span>₹<?= number_format($invoice['total_amount'], 2)?></span>
                        </div>
                        <div class="summary-row">
                            <span>Tax Amount:</span>
                            <span>₹<?= number_format($invoice['tax_amount'], 2)?></span>
                        </div>
                        <div class="summary-row">
                            <span>Discount:</span>
                            <span>- ₹<?= number_format($invoice['discount_amount'], 2)?></span>
                        </div>
                        <div class="summary-row total">
                            <span>NET AMOUNT:</span>
                            <span>₹<?= number_format($invoice['net_amount'], 2)?></span>
                        </div>
                        <div class="summary-row paid">
                            <span><i class="fas fa-check-circle"></i> Paid Amount:</span>
                            <span>₹<?= number_format($invoice['paid_amount'], 2)?></span>
                        </div>
                        <div class="summary-row balance">
                            <span><i class="fas fa-exclamation-circle"></i> Balance Due:</span>
                            <span>₹<?= number_format($invoice['balance_amount'], 2)?></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Notes -->
            <?php if($invoice['remarks']) { ?>
            <div class="alert alert-warning mt-4" role="alert">
                <strong><i class="fas fa-sticky-note"></i> Notes:</strong> <?= $invoice['remarks']?>
            </div>
            <?php } ?>

            <!-- Footer -->
            <div class="row mt-5 pt-4 border-top">
                <div class="col-md-6">
                    <div class="signature-box">
                        <div class="signature-line">Prepared By</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="signature-box">
                        <div class="signature-line">Authorized Signature</div>
                    </div>
                </div>
            </div>

            <div class="text-center text-muted mt-4" style="font-size: 11px;">
                <p class="mb-1">Invoice Created: <?= date('d-M-Y h:i A', strtotime($invoice['created_at']))?> | User ID:
                    <?= $invoice['created_by']?></p>
                <p class="mb-0">Printed on: <?= date('d-M-Y h:i A')?> | This is a computer-generated invoice</p>
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
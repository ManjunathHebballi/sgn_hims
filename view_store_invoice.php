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
$payment_allocations = $newStore->getPaymentAllocations($invoice_id);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="theme-color" content="#319197">

    <title>View Purchase Invoice - <?= $invoice['invoice_number']?></title>
    <link rel="icon" type="image/png" sizes="16x16" href="../images/favicon.png">
    <link href="css/style.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

    <style>
    @media print {
        .no-print {
            display: none !important;
        }

        .content-body {
            padding: 0 !important;
            margin: 0 !important;
        }

        .card {
            border: none !important;
            box-shadow: none !important;
        }
    }

    .invoice-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 30px;
        border-radius: 10px 10px 0 0;
    }

    .invoice-box {
        border: 2px solid #667eea;
        border-radius: 10px;
        overflow: hidden;
        margin-bottom: 20px;
    }

    .info-section {
        background-color: #f8f9fa;
        padding: 20px;
        border-radius: 5px;
        margin-bottom: 20px;
    }

    .status-badge {
        font-size: 14px;
        padding: 8px 15px;
    }

    .item-table th {
        background-color: #667eea;
        color: white;
    }

    .payment-table th {
        background-color: #28a745;
        color: white;
    }

    .total-section {
        background-color: #f0f8ff;
        padding: 20px;
        border-radius: 5px;
    }

    .amount-row {
        font-size: 16px;
        margin-bottom: 10px;
    }

    .grand-total {
        font-size: 20px;
        font-weight: bold;
        color: #667eea;
        border-top: 2px solid #667eea;
        padding-top: 10px;
        margin-top: 10px;
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
                <div class="row mb-3 no-print">
                    <div class="col-md-6">
                        <h4 class="text-uppercase">📄 Purchase Invoice Details</h4>
                    </div>
                    <div class="col-md-6 text-right">
                        <a href="print_store_invoice.php?id=<?= $invoice_id ?>" target="_blank" class="btn btn-primary">
                            <i class="fa fa-print"></i> Print Invoice
                        </a>
                        <a href="store_invoice_list.php" class="btn btn-secondary">
                            <i class="fa fa-arrow-left"></i> Back to List
                        </a>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="invoice-box">
                            <div class="invoice-header">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h3 class="mb-0">PURCHASE INVOICE</h3>
                                        <h5><?= $invoice['invoice_number']?></h5>
                                    </div>
                                    <div class="col-md-6 text-right">
                                        <h5>Alvas Hospital</h5>
                                        <p class="mb-0">Store Department</p>
                                        <p class="mb-0">Date: <?= date('d-M-Y', strtotime($invoice['invoice_date']))?>
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="card-body">
                                <!-- Supplier & Invoice Info -->
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="info-section">
                                            <h5 class="text-primary">Supplier Details</h5>
                                            <hr>
                                            <p class="mb-1"><strong>Name:</strong> <?= $invoice['supplier_name']?></p>
                                            <?php if($invoice['phone']) { ?>
                                            <p class="mb-1"><strong>Phone:</strong> <?= $invoice['phone']?></p>
                                            <?php } ?>
                                            <?php if($invoice['email']) { ?>
                                            <p class="mb-1"><strong>Email:</strong> <?= $invoice['email']?></p>
                                            <?php } ?>
                                            <?php if($invoice['address']) { ?>
                                            <p class="mb-1"><strong>Address:</strong> <?= $invoice['address']?></p>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="info-section">
                                            <h5 class="text-primary">Invoice Information</h5>
                                            <hr>
                                            <p class="mb-1"><strong>Invoice Date:</strong>
                                                <?= date('d-M-Y', strtotime($invoice['invoice_date']))?></p>
                                            <p class="mb-1"><strong>Due Date:</strong>
                                                <?= date('d-M-Y', strtotime($invoice['due_date']))?></p>
                                            <p class="mb-1"><strong>Payment Terms:</strong>
                                                <?= $invoice['payment_terms']?></p>
                                            <p class="mb-1"><strong>Payment Status:</strong>
                                                <?php 
                                                if($invoice['payment_status'] == 'UNPAID') {
                                                    echo '<span class="badge badge-danger status-badge">UNPAID</span>';
                                                } elseif($invoice['payment_status'] == 'PARTIALLY_PAID') {
                                                    echo '<span class="badge badge-warning status-badge">PARTIALLY PAID</span>';
                                                } else {
                                                    echo '<span class="badge badge-success status-badge">PAID</span>';
                                                }
                                                ?>
                                            </p>
                                            <?php if($invoice['remarks']) { ?>
                                            <p class="mb-1"><strong>Remarks:</strong> <?= $invoice['remarks']?></p>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>

                                <!-- Items Table -->
                                <div class="row mt-3">
                                    <div class="col-md-12">
                                        <h5 class="text-primary">Invoice Items</h5>
                                        <div class="table-responsive">
                                            <table class="table table-bordered item-table">
                                                <thead>
                                                    <tr>
                                                        <th width="5%">Sl.No</th>
                                                        <th width="15%">Item Code</th>
                                                        <th width="25%">Item Name</th>
                                                        <th width="10%">Batch No</th>
                                                        <th width="8%">Qty</th>
                                                        <th width="8%">Rate</th>
                                                        <th width="8%">Amount</th>
                                                        <th width="6%">Tax%</th>
                                                        <th width="8%">Tax Amt</th>
                                                        <th width="6%">Disc%</th>
                                                        <th width="8%">Net Amt</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php 
                                                    $sn = 1;
                                                    $total_qty = 0;
                                                    while($item = mysqli_fetch_assoc($items)) {
                                                        $total_qty += $item['quantity'];
                                                    ?>
                                                    <tr>
                                                        <td><?= $sn?></td>
                                                        <td><?= $item['item_code']?></td>
                                                        <td><?= $item['item_name']?> (<?= $item['unit_name']?>)</td>
                                                        <td><?= $item['batch_no'] ? $item['batch_no'] : '-'?></td>
                                                        <td class="text-right"><?= number_format($item['quantity'], 2)?>
                                                        </td>
                                                        <td class="text-right">₹<?= number_format($item['rate'], 2)?>
                                                        </td>
                                                        <td class="text-right">₹<?= number_format($item['amount'], 2)?>
                                                        </td>
                                                        <td class="text-center"><?= $item['tax_percent']?>%</td>
                                                        <td class="text-right">
                                                            ₹<?= number_format($item['tax_amount'], 2)?></td>
                                                        <td class="text-center"><?= $item['discount_percent']?>%</td>
                                                        <td class="text-right">
                                                            <strong>₹<?= number_format($item['net_amount'], 2)?></strong>
                                                        </td>
                                                    </tr>
                                                    <?php 
                                                    $sn++;
                                                    } 
                                                    ?>
                                                    <tr class="table-secondary">
                                                        <td colspan="4" class="text-right"><strong>Total Items:
                                                                <?= $sn-1?></strong></td>
                                                        <td class="text-right">
                                                            <strong><?= number_format($total_qty, 2)?></strong>
                                                        </td>
                                                        <td colspan="6"></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <!-- Amount Summary -->
                                <div class="row mt-3">
                                    <div class="col-md-7"></div>
                                    <div class="col-md-5">
                                        <div class="total-section">
                                            <div class="amount-row">
                                                <div class="row">
                                                    <div class="col-6">Sub Total:</div>
                                                    <div class="col-6 text-right">
                                                        ₹<?= number_format($invoice['total_amount'], 2)?></div>
                                                </div>
                                            </div>
                                            <div class="amount-row">
                                                <div class="row">
                                                    <div class="col-6">Tax Amount:</div>
                                                    <div class="col-6 text-right">
                                                        ₹<?= number_format($invoice['tax_amount'], 2)?></div>
                                                </div>
                                            </div>
                                            <div class="amount-row">
                                                <div class="row">
                                                    <div class="col-6">Discount:</div>
                                                    <div class="col-6 text-right">-
                                                        ₹<?= number_format($invoice['discount_amount'], 2)?></div>
                                                </div>
                                            </div>
                                            <div class="grand-total">
                                                <div class="row">
                                                    <div class="col-6">Net Amount:</div>
                                                    <div class="col-6 text-right">
                                                        ₹<?= number_format($invoice['net_amount'], 2)?></div>
                                                </div>
                                            </div>
                                            <div class="amount-row mt-3">
                                                <div class="row">
                                                    <div class="col-6">Paid Amount:</div>
                                                    <div class="col-6 text-right text-success">
                                                        <strong>₹<?= number_format($invoice['paid_amount'], 2)?></strong>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="amount-row">
                                                <div class="row">
                                                    <div class="col-6">Balance Amount:</div>
                                                    <div class="col-6 text-right text-danger">
                                                        <strong>₹<?= number_format($invoice['balance_amount'], 2)?></strong>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Payment Allocations -->
                                <?php if(mysqli_num_rows($payment_allocations) > 0) { ?>
                                <div class="row mt-4">
                                    <div class="col-md-12">
                                        <h5 class="text-success">Payment History</h5>
                                        <div class="table-responsive">
                                            <table class="table table-bordered payment-table">
                                                <thead>
                                                    <tr>
                                                        <th>Sl.No</th>
                                                        <th>Payment Number</th>
                                                        <th>Payment Date</th>
                                                        <th>Payment Mode</th>
                                                        <th>Reference</th>
                                                        <th>Allocated Amount</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php 
                                                    $sn = 1;
                                                    mysqli_data_seek($payment_allocations, 0); // Reset pointer
                                                    while($payment = mysqli_fetch_assoc($payment_allocations)) {
                                                        // Get payment details
                                                        $payment_details = $newStore->getSupplierPayment($payment['payment_id']);
                                                        $pay_detail = mysqli_fetch_assoc($payment_details);
                                                    ?>
                                                    <tr>
                                                        <td><?= $sn?></td>
                                                        <td><?= $pay_detail['payment_number']?></td>
                                                        <td><?= date('d-M-Y', strtotime($pay_detail['payment_date']))?>
                                                        </td>
                                                        <td><?= $pay_detail['payment_mode']?></td>
                                                        <td><?= $pay_detail['reference_number'] ? $pay_detail['reference_number'] : '-'?>
                                                        </td>
                                                        <td class="text-right">
                                                            ₹<?= number_format($payment['allocated_amount'], 2)?></td>
                                                    </tr>
                                                    <?php 
                                                    $sn++;
                                                    } 
                                                    ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <?php } ?>

                                <!-- Footer -->
                                <div class="row mt-5">
                                    <div class="col-md-12">
                                        <hr>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <p class="mb-0"><strong>Created By:</strong> User ID:
                                                    <?= $invoice['created_by']?></p>
                                                <p class="mb-0"><strong>Created At:</strong>
                                                    <?= date('d-M-Y h:i A', strtotime($invoice['created_at']))?></p>
                                            </div>
                                            <div class="col-md-6 text-right">
                                                <p class="mb-0">_________________________</p>
                                                <p class="mb-0"><strong>Authorized Signature</strong></p>
                                            </div>
                                        </div>
                                    </div>
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

</body>

</html>
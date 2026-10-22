<?php 
require_once(__DIR__ . '/model/session_config.php');

date_default_timezone_set("Asia/Kolkata");  
include('model/storeClass.php');
$newStore = new store;

$payment_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$payment = $newStore->getSupplierPaymentById($payment_id);
$allocations = $newStore->getPaymentAllocations($payment_id);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="theme-color" content="#319197">

    <title>View Payment - <?= $payment['payment_number']?></title>
    <link rel="icon" type="image/png" sizes="16x16" href="../images/favicon.png">
    <link href="css/style.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <style>
    .payment-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 20px;
        border-radius: 5px 5px 0 0;
    }

    .info-label {
        font-weight: 600;
        color: #666;
    }

    .info-value {
        font-weight: 400;
        color: #333;
    }

    .allocation-table th {
        background-color: #28a745;
        color: white;
    }

    .badge-unpaid {
        background-color: #dc3545;
    }

    .badge-partial {
        background-color: #ffc107;
    }

    .badge-paid {
        background-color: #28a745;
    }

    @media print {
        .no-print {
            display: none;
        }
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
                        <h4 class="text-uppercase">💳 Payment Details</h4>
                    </div>
                    <div class="col-md-6 text-right">
                        <a href="store_payment_list.php" class="btn btn-secondary">
                            <i class="fa fa-arrow-left"></i> Back to List
                        </a>
                        <a href="print_store_payment.php?id=<?= $payment_id?>" target="_blank"
                            class="btn btn-primary">
                            <i class="fa fa-print"></i> Print
                        </a>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="payment-header">
                                <div class="row">
                                    <div class="col-md-8">
                                        <h4><i class="fas fa-receipt"></i> Payment #<?= $payment['payment_number']?>
                                        </h4>
                                        <p class="mb-0">Supplier: <?= $payment['supplier_name']?></p>
                                    </div>
                                    <div class="col-md-4 text-right">
                                        <h3>₹<?= number_format($payment['payment_amount'], 2)?></h3>
                                        <small><?= date('d-M-Y', strtotime($payment['payment_date']))?></small>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <table class="table table-borderless">
                                            <tr>
                                                <td class="info-label">Payment Number:</td>
                                                <td class="info-value"><?= $payment['payment_number']?></td>
                                            </tr>
                                            <tr>
                                                <td class="info-label">Payment Date:</td>
                                                <td class="info-value">
                                                    <?= date('d-M-Y', strtotime($payment['payment_date']))?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="info-label">Payment Mode:</td>
                                                <td class="info-value"><?= $payment['payment_mode']?></td>
                                            </tr>
                                            <tr>
                                                <td class="info-label">Reference Number:</td>
                                                <td class="info-value">
                                                    <?= $payment['reference_number'] ?: 'N/A'?>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="col-md-6">
                                        <table class="table table-borderless">
                                            <tr>
                                                <td class="info-label">Supplier Name:</td>
                                                <td class="info-value"><?= $payment['supplier_name']?></td>
                                            </tr>
                                            <tr>
                                                <td class="info-label">Contact:</td>
                                                <td class="info-value"><?= $payment['contact_number']?></td>
                                            </tr>
                                            <tr>
                                                <td class="info-label">Email:</td>
                                                <td class="info-value"><?= $payment['email']?></td>
                                            </tr>
                                            <tr>
                                                <td class="info-label">Remarks:</td>
                                                <td class="info-value"><?= $payment['remarks'] ?: 'N/A'?></td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <?php if(mysqli_num_rows($allocations) > 0): ?>
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header bg-success text-white">
                                <h5>Payment Allocation to Invoices</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered allocation-table">
                                        <thead>
                                            <tr>
                                                <th>Invoice Number</th>
                                                <th>Invoice Date</th>
                                                <th>Due Date</th>
                                                <th class="text-right">Invoice Amount</th>
                                                <th class="text-right">Allocated Amount</th>
                                                <th class="text-center">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                            $total_allocated = 0;
                                            while($alloc = mysqli_fetch_assoc($allocations)):
                                                $total_allocated += $alloc['allocated_amount'];
                                            ?>
                                            <tr>
                                                <td><?= $alloc['invoice_number']?></td>
                                                <td><?= date('d-M-Y', strtotime($alloc['invoice_date']))?></td>
                                                <td><?= date('d-M-Y', strtotime($alloc['due_date']))?></td>
                                                <td class="text-right">₹<?= number_format($alloc['net_amount'], 2)?>
                                                </td>
                                                <td class="text-right">
                                                    ₹<?= number_format($alloc['allocated_amount'], 2)?></td>
                                                <td class="text-center">
                                                    <?php 
                                                    if($alloc['payment_status'] == 'PAID') {
                                                        echo '<span class="badge badge-paid">Paid</span>';
                                                    } else if($alloc['payment_status'] == 'PARTIALLY_PAID') {
                                                        echo '<span class="badge badge-partial">Partial</span>';
                                                    } else {
                                                        echo '<span class="badge badge-unpaid">Unpaid</span>';
                                                    }
                                                    ?>
                                                </td>
                                            </tr>
                                            <?php endwhile; ?>
                                        </tbody>
                                        <tfoot>
                                            <tr class="table-secondary">
                                                <td colspan="4" class="text-right"><strong>Total Allocated:</strong>
                                                </td>
                                                <td class="text-right"><strong>₹<?= number_format($total_allocated, 2)?>
                                                    </strong></td>
                                                <td></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>

                                <?php if($payment['payment_amount'] > $total_allocated): ?>
                                <div class="alert alert-info">
                                    <strong>Advance Amount:</strong>
                                    ₹<?= number_format($payment['payment_amount'] - $total_allocated, 2)?>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php else: ?>
                <div class="row">
                    <div class="col-md-12">
                        <div class="alert alert-warning">
                            <i class="fa fa-info-circle"></i> This payment was recorded as an advance payment. No
                            invoices were allocated.
                        </div>
                    </div>
                </div>
                <?php endif; ?>

            </div>

        </div>

        <?php include('footer.php') ?>

    </div>

    <script src="vendor/global/global.min.js"></script>
    <script src="js/quixnav-init.js"></script>
    <script src="js/custom.min.js"></script>

</body>

</html>

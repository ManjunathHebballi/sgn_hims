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
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Print Payment - <?= $payment['payment_number']?></title>

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

    .payment-wrapper {
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

    .payment-header-section {
        border-bottom: 3px solid #28a745;
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

    .payment-badge {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        color: white;
        padding: 15px;
        border-radius: 10px;
        text-align: center;
        margin: 20px 0;
    }

    .payment-badge h4 {
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
        color: #28a745;
        font-weight: bold;
        margin-bottom: 15px;
        padding-bottom: 10px;
        border-bottom: 2px solid #28a745;
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

    .table-allocation {
        font-size: 12px;
        margin-top: 20px;
    }

    .table-allocation thead {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        color: white;
    }

    .table-allocation thead th {
        font-weight: 600;
        border: none;
        padding: 12px 8px;
    }

    .table-allocation tbody td {
        padding: 10px 8px;
        vertical-align: middle;
    }

    .table-allocation tfoot {
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
        border-top: 2px solid #28a745;
        padding-top: 12px;
        margin-top: 10px;
        font-size: 18px;
        font-weight: bold;
        color: #28a745;
    }

    .summary-row.advance {
        color: #ffc107;
        font-weight: 600;
    }

    .badge-unpaid {
        background-color: #dc3545;
        color: white;
        padding: 3px 8px;
        border-radius: 3px;
    }

    .badge-partial {
        background-color: #ffc107;
        color: #333;
        padding: 3px 8px;
        border-radius: 3px;
    }

    .badge-paid {
        background-color: #28a745;
        color: white;
        padding: 3px 8px;
        border-radius: 3px;
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

        .payment-wrapper {
            box-shadow: none;
            padding: 15px;
            max-width: 100%;
            margin: 0;
        }

        .payment-header-section {
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

    <button onclick="window.print()" class="btn btn-success print-button no-print">
        <i class="fas fa-print"></i> Print Payment
    </button>

    <div class="payment-wrapper">
        <div class="container-fluid">

            <!-- Header -->
            <div class="payment-header-section">
                <div class="row align-items-center mb-3">
                    <div class="col-sm-2 text-center">
                        <img src="images/logo.png" class="header-logo" alt="Hospital Logo">
                    </div>
                    <div class="col-sm-10 text-center">
                        <h2 class="hospital-name mb-1"><?= $_SESSION['college_name']?></h2>
                        <p class="hospital-location mb-0"><?= $_SESSION['college_location']?></p>
                    </div>
                </div>

                <h4 class="text-center font-weight-bold mb-3">Store Department - Payment Voucher</h4>

                <div class="d-flex justify-content-between text-muted">
                    <p class="mb-0"><i class="fas fa-receipt"></i> Payment Number:
                        <strong><?= $payment['payment_number']?></strong>
                    </p>
                    <p class="mb-0"><i class="far fa-calendar-alt"></i> Payment Date:
                        <strong><?= date('d/m/Y', strtotime($payment['payment_date']))?></strong>
                    </p>
                </div>
            </div>

            <!-- Payment & Supplier Details -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="info-card">
                        <h5><i class="fas fa-money-bill-wave"></i> PAYMENT DETAILS</h5>
                        <p><span class="info-label">Payment Number:</span> <?= $payment['payment_number']?></p>
                        <p><span class="info-label">Payment Date:</span>
                            <?= date('d-M-Y', strtotime($payment['payment_date']))?></p>
                        <p><span class="info-label">Payment Mode:</span> <?= $payment['payment_mode']?></p>
                        <p><span class="info-label">Reference Number:</span>
                            <?= $payment['reference_number'] ?: 'N/A'?></p>
                        <?php if($payment['remarks']) { ?>
                        <p><span class="info-label">Remarks:</span> <?= $payment['remarks']?></p>
                        <?php } ?>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="info-card">
                        <h5><i class="fas fa-truck"></i> SUPPLIER DETAILS</h5>
                        <p><span class="info-label">Supplier Name:</span> <?= $payment['supplier_name']?></p>
                        <?php if($payment['contact_number']) { ?>
                        <p><span class="info-label">Contact Number:</span> <?= $payment['contact_number']?></p>
                        <?php } ?>
                        <?php if($payment['email']) { ?>
                        <p><span class="info-label">Email:</span> <?= $payment['email']?></p>
                        <?php } ?>
                        <?php if($payment['address']) { ?>
                        <p><span class="info-label">Address:</span> <?= $payment['address']?></p>
                        <?php } ?>
                    </div>
                </div>
            </div>

            <!-- Payment Allocation -->
            <?php if(mysqli_num_rows($allocations) > 0): ?>
            <div class="table-responsive">
                <table class="table table-allocation table-bordered table-hover">
                    <thead>
                        <tr>
                            <th width="5%">Sl</th>
                            <th width="20%">Invoice Number</th>
                            <th width="15%">Invoice Date</th>
                            <th width="15%">Due Date</th>
                            <th width="15%" class="text-right">Invoice Amount</th>
                            <th width="15%" class="text-right">Allocated Amount</th>
                            <th width="15%" class="text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $sn = 1;
                        $total_allocated = 0;
                        mysqli_data_seek($allocations, 0);
                        while($alloc = mysqli_fetch_assoc($allocations)):
                            $total_allocated += $alloc['allocated_amount'];
                        ?>
                        <tr>
                            <td class="text-center"><?= $sn?></td>
                            <td><?= $alloc['invoice_number']?></td>
                            <td><?= date('d-M-Y', strtotime($alloc['invoice_date']))?></td>
                            <td><?= date('d-M-Y', strtotime($alloc['due_date']))?></td>
                            <td class="text-right">₹<?= number_format($alloc['net_amount'], 2)?></td>
                            <td class="text-right font-weight-bold">₹<?= number_format($alloc['allocated_amount'], 2)?>
                            </td>
                            <td class="text-center">
                                <?php 
                                if($alloc['payment_status'] == 'PAID') {
                                    echo '<span class="badge-paid">Paid</span>';
                                } else if($alloc['payment_status'] == 'PARTIALLY_PAID') {
                                    echo '<span class="badge-partial">Partial</span>';
                                } else {
                                    echo '<span class="badge-unpaid">Unpaid</span>';
                                }
                                ?>
                            </td>
                        </tr>
                        <?php 
                        $sn++;
                        endwhile; 
                        ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="5" class="text-right">Total Invoices: <strong><?= $sn-1?></strong></td>
                            <td class="text-right"><strong>₹<?= number_format($total_allocated, 2)?></strong></td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <!-- Summary Section -->
            <div class="row">
                <div class="col-md-8"></div>
                <div class="col-md-4">
                    <div class="summary-card">
                        <div class="summary-row total">
                            <span>TOTAL PAYMENT:</span>
                            <span>₹<?= number_format($payment['payment_amount'], 2)?></span>
                        </div>
                        <div class="summary-row">
                            <span><i class="fas fa-check-circle"></i> Allocated:</span>
                            <span>₹<?= number_format($total_allocated, 2)?></span>
                        </div>
                        <?php if($payment['payment_amount'] > $total_allocated): ?>
                        <div class="summary-row advance">
                            <span><i class="fas fa-wallet"></i> Advance:</span>
                            <span>₹<?= number_format($payment['payment_amount'] - $total_allocated, 2)?></span>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php else: ?>
            <div class="alert alert-warning mt-4" role="alert">
                <strong><i class="fa fa-info-circle"></i> Advance Payment:</strong> This payment was recorded as an
                advance payment. No invoices were allocated at the time of payment.
            </div>
            <?php endif; ?>
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
                        <div class="signature-line">Approved By</div>
                    </div>
                </div>
            </div>

            <div class="text-center text-muted mt-4" style="font-size: 11px;">
                <p class="mb-1">Payment Created: <?= date('d-M-Y h:i A', strtotime($payment['created_at']))?> |
                    Created
                    By: <?= $payment['created_by']?></p>
                <p class="mb-0">Printed on: <?= date('d-M-Y h:i A')?> | This is a computer-generated payment
                    voucher
                </p>
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
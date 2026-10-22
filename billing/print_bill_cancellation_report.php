<?php 
require_once(__DIR__ . '/../model/session_config.php');
if(!isset($_SESSION['loginid']))
{
    echo "<script>window.location='index.php';</script>";
}
include('model/reportClass.php');
$newReport = new report;

$from = isset($_REQUEST['from']) ? $_REQUEST['from'] : date('Y-m-d');
$to = isset($_REQUEST['to']) ? $_REQUEST['to'] : date('Y-m-d');
$service_type = isset($_REQUEST['service_type']) ? $_REQUEST['service_type'] : '';
$search = isset($_REQUEST['search']) ? $_REQUEST['search'] : '';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Bill Cancellation Report</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css"
        integrity="sha384-zCbKRCUGaJDkqS1kPbPd7TveP5iyJE0EjAuZQTgFLD2ylzuqKfdKlfG/eSrtxUkn" crossorigin="anonymous">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500&display=swap" rel="stylesheet">

    <style>
    body {
        font-family: 'Poppins', sans-serif;
        font-size: 10px;
        margin: 0;
        padding: 0;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
        font-size: 9px;
    }

    table,
    th,
    td {
        border: 1px solid #000;
    }

    th {
        background-color: #333;
        color: #fff;
        padding: 4px 2px;
        text-align: left;
        font-size: 9px;
        -webkit-print-color-adjust: exact;
    }

    td {
        padding: 3px 2px;
        font-size: 9px;
    }

    .text-right {
        text-align: right;
    }

    .text-center {
        text-align: center;
    }

    .summary-table {
        width: 60%;
        margin-bottom: 20px;
    }

    .total-row {
        font-weight: bold;
        background-color: #f0f0f0;
        -webkit-print-color-adjust: exact;
    }

    @media print {
        .no-print {
            display: none;
        }

        @page {
            size: A4 landscape;
            margin: 8mm;
        }
    }
    </style>
</head>

<body onload="window.print()">

    <div class="container-fluid p-0">
        <div class="card border-0">
            <div class="card-body">
                    <div class="row">
                        <div class="col-sm-2">
                            <img src="assets/logo.png" class="img-fluid" width="120" alt="">
                        </div>
                        <div class="col-sm-10 align-items-center">
                            <h2 class="text-center text-black"><?= $_SESSION['college_name'] ?></h2>
                            <p class="text-center text-black">
                                <?= $_SESSION['college_location'] ?>,<?= $_SESSION['college_phone'] ?></p>
                        </div>
                        
                    </div>
                    <h4 class="text-center text-black">Bill Cancellation Report</h4>
                    <p class="text-center">From: <?= date('d-m-Y', strtotime($from)) ?> To:
                        <?= date('d-m-Y', strtotime($to)) ?></p>
                    <?php if($service_type) { ?>
                    <p class="text-center">Service Type: <strong><?= $service_type ?></strong></p>
                    <?php } ?>
              
                <hr>

                <button class="no-print" onclick="window.print()"
                    style="margin-bottom: 15px; padding: 10px 20px; font-size: 14px;">Print Report</button>

                <!-- Summary Table -->
                <h4>Summary by Service Type</h4>
                <table class="summary-table">
                    <thead>
                        <tr>
                            <th>Service Type</th>
                            <th class="text-right">Total Bills</th>
                            <th class="text-right">Total Amount</th>
                            <th class="text-right">Total Discount</th>
                            <th class="text-right">Total Paid</th>
                            <th class="text-right">Total Returned</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
            $summary = $newReport->getBillCancellationSummary($from, $to, $service_type);
            $total_bills = 0;
            $total_amount = 0;
            $total_discount = 0;
            $total_paid = 0;
            $total_returned = 0;
            while($sum = mysqli_fetch_assoc($summary)) {
                $total_bills += $sum['total_bills'];
                $total_amount += $sum['total_amount'];
                $total_discount += $sum['total_discount'];
                $total_paid += $sum['total_paid'];
                $total_returned += $sum['total_returned'];
            ?>
                        <tr>
                            <td><?= $sum['service_type'] ?></td>
                            <td class="text-right"><?= $sum['total_bills'] ?></td>
                            <td class="text-right">₹ <?= number_format($sum['total_amount'], 2) ?></td>
                            <td class="text-right">₹ <?= number_format($sum['total_discount'], 2) ?></td>
                            <td class="text-right">₹ <?= number_format($sum['total_paid'], 2) ?></td>
                            <td class="text-right">₹ <?= number_format($sum['total_returned'], 2) ?></td>
                        </tr>
                        <?php } ?>
                        <tr class="total-row">
                            <td>TOTAL</td>
                            <td class="text-right"><?= $total_bills ?></td>
                            <td class="text-right">₹ <?= number_format($total_amount, 2) ?></td>
                            <td class="text-right">₹ <?= number_format($total_discount, 2) ?></td>
                            <td class="text-right">₹ <?= number_format($total_paid, 2) ?></td>
                            <td class="text-right">₹ <?= number_format($total_returned, 2) ?></td>
                        </tr>
                    </tbody>
                </table>

                <!-- Detailed Report -->
                <h4>Cancelled Bills Details</h4>
                <table>
                    <thead>
                        <tr>
                            <th>Sl No</th>
                            <th>Date</th>
                            <th>Bill No</th>
                            <th>Service</th>
                            <th>OPD No</th>
                            <th>IPD No</th>
                            <th>Patient Name</th>
                            <th>Age/Gender</th>
                            <th>Services</th>
                            <th class="text-right">Bill Amt</th>
                            <th class="text-right">Discount</th>
                            <th class="text-right">Net Amt</th>
                            <th class="text-right">Paid</th>
                            <th class="text-right">Returned</th>
                            <th>Returned By</th>
                            <th>Returned At</th>
                            <th>Cancelled By</th>
                            <th>Reason</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
            $result = $newReport->getBillCancellationReport($from, $to, $service_type, $search);
            $sn = 1;
            $grand_bill = 0;
            $grand_discount = 0;
            $grand_net = 0;
            $grand_paid = 0;
            $grand_returned = 0;
            
            while($res = mysqli_fetch_assoc($result)) {
                $net_amount = $res['gross_total'] - $res['service_discount'] - $res['discount_amount'];
                $grand_bill += $res['gross_total'];
                $grand_discount += ($res['service_discount'] + $res['discount_amount']);
                $grand_net += $net_amount;
                $grand_paid += $res['paid_amount'];
                $grand_returned += $res['returned_amount'];
            ?>
                        <tr>
                            <td class="text-center"><?= $sn++ ?></td>
                            <td><?= date('d-m-Y', strtotime($res['date'])) ?></td>
                            <td><?= $res['bill_no'] ?></td>
                            <td><?= strtoupper($res['service_type']) == 'PATHOLOGY' ? 'LAB' : $res['service_type'] ?></td>
                            <td><?= $res['opd_no'] ?></td>
                            <td><?= $res['ipd_no'] ?></td>
                            <td><?= $res['name'] ?></td>
                            <td><?= $res['age'] ?>/<?= substr($res['gender'], 0, 1) ?></td>
                            <td><?= $res['services'] ?></td>
                            <td class="text-right">₹ <?= number_format($res['gross_total'], 2) ?></td>
                            <td class="text-right">₹
                                <?= number_format($res['service_discount'] + $res['discount_amount'], 2) ?>
                            </td>
                            <td class="text-right">₹ <?= number_format($net_amount, 2) ?></td>
                            <td class="text-right">₹ <?= number_format($res['paid_amount'], 2) ?></td>
                            <td class="text-right">₹ <?= number_format($res['returned_amount'], 2) ?></td>
                            <td><?= $res['returned_by'] ?></td>
                            <td><?= $res['returned_at'] && $res['returned_at'] != '0000-00-00 00:00:00' ? date('d-m-Y H:i', strtotime($res['returned_at'])) : '' ?>
                            </td>
                            <td><?= $res['updated_by'] ?></td>
                            <td><?= $res['cancellation_note'] ?></td>
                        </tr>
                        <?php } ?>
                        <tr class="total-row">
                            <td colspan="9" class="text-right"><strong>GRAND TOTAL</strong></td>
                            <td class="text-right"><strong>₹ <?= number_format($grand_bill, 2) ?></strong></td>
                            <td class="text-right"><strong>₹ <?= number_format($grand_discount, 2) ?></strong></td>
                            <td class="text-right"><strong>₹ <?= number_format($grand_net, 2) ?></strong></td>
                            <td class="text-right"><strong>₹ <?= number_format($grand_paid, 2) ?></strong></td>
                            <td class="text-right"><strong>₹ <?= number_format($grand_returned, 2) ?></strong></td>
                            <td colspan="4"></td>
                        </tr>
                    </tbody>
                </table>

                <div style="margin-top: 30px;">
                    <p>Prepared By: <?= $_SESSION['name'] ?></p>
                    <p>Signature: _____________________</p>
                </div>

            </div>
        </div>
    </div>

</body>

</html>
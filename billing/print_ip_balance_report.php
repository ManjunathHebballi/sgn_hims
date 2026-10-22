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
$search = isset($_REQUEST['search']) ? $_REQUEST['search'] : '';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>IP Balance Report - Print</title>

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
        text-align: center;
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
                            <?= $_SESSION['college_location'] ?>, <?= $_SESSION['college_phone'] ?></p>
                    </div>
                </div>
                <h4 class="text-center text-black">IP Bills with Balance Details</h4>
                <p class="text-center">From: <?= date('d-m-Y', strtotime($from)) ?> to
                    <?= date('d-m-Y', strtotime($to)) ?></p>
                <hr>

                <button class="no-print" onclick="window.print()"
                    style="margin-bottom: 15px; padding: 10px 20px; font-size: 14px;">Print Report</button>

                <!-- Detailed Report -->
                <table>
                    <thead>
                        <tr>
                            <th rowspan="2">Sl No</th>
                            <th rowspan="2">IP No</th>
                            <th rowspan="2">Name</th>
                            <th rowspan="2">Bill No.</th>
                            <th colspan="2">In Patient</th>
                            <th colspan="2">Laboratory</th>
                            <th colspan="2">X Ray</th>
                            <th rowspan="2">Balance</th>
                            <th rowspan="2">Scheme</th>
                        </tr>
                        <tr>
                            <th>Bills</th>
                            <th>Receipt</th>
                            <th>Bills</th>
                            <th>Receipt</th>
                            <th>Bills</th>
                            <th>Receipt</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $result = $newReport->getIPBalanceReport($from, $to, $search);
                        $sn = 1;
                        $total_ip_bills = 0;
                        $total_ip_receipt = 0;
                        $total_lab_bills = 0;
                        $total_lab_receipt = 0;
                        $total_xray_bills = 0;
                        $total_xray_receipt = 0;
                        $total_balance = 0;
                        
                        $current_date = '';
                        $date_ip_bills = 0;
                        $date_ip_receipt = 0;
                        $date_lab_bills = 0;
                        $date_lab_receipt = 0;
                        $date_xray_bills = 0;
                        $date_xray_receipt = 0;
                        $date_balance = 0;
                        
                        while($res = mysqli_fetch_assoc($result)) {
                            // Check if date changed
                            if($current_date != '' && $current_date != $res['date']) {
                                // Print date subtotal
                                ?>
                        <tr style="background-color: #ffc107; font-weight: bold;">
                            <td colspan="4" class="text-right">Sub Total -
                                <?= date('d-m-Y', strtotime($current_date)) ?></td>
                            <td class="text-right"><?= number_format($date_ip_bills, 0) ?></td>
                            <td class="text-right"><?= number_format($date_ip_receipt, 0) ?></td>
                            <td class="text-right"><?= number_format($date_lab_bills, 0) ?></td>
                            <td class="text-right"><?= number_format($date_lab_receipt, 0) ?></td>
                            <td class="text-right"><?= number_format($date_xray_bills, 0) ?></td>
                            <td class="text-right"><?= number_format($date_xray_receipt, 0) ?></td>
                            <td class="text-right"><?= number_format($date_balance, 0) ?></td>
                            <td></td>
                        </tr>
                        <?php
                                // Reset date totals
                                $date_ip_bills = 0;
                                $date_ip_receipt = 0;
                                $date_lab_bills = 0;
                                $date_lab_receipt = 0;
                                $date_xray_bills = 0;
                                $date_xray_receipt = 0;
                                $date_balance = 0;
                            }
                            
                            // Print date header if new date
                            if($current_date != $res['date']) {
                                ?>
                        <tr style="background-color: #007bff; color: white; font-weight: bold;">
                            <td colspan="12"
                                style="text-align: center; padding: 8px; font-size: 12px; letter-spacing: 1px;">BILL
                                DATE: <?= strtoupper(date('d-m-Y', strtotime($res['date']))) ?></td>
                        </tr>
                        <?php
                                $current_date = $res['date'];
                            }
                            
                            $total_ip_bills += $res['ip_bills'];
                            $total_ip_receipt += $res['ip_receipt'];
                            $total_lab_bills += $res['lab_bills'];
                            $total_lab_receipt += $res['lab_receipt'];
                            $total_xray_bills += $res['radio_bills'];
                            $total_xray_receipt += $res['radio_receipt'];
                            $total_balance += $res['balance'];

                            $date_ip_bills += $res['ip_bills'];
                            $date_ip_receipt += $res['ip_receipt'];
                            $date_lab_bills += $res['lab_bills'];
                            $date_lab_receipt += $res['lab_receipt'];
                            $date_xray_bills += $res['radio_bills'];
                            $date_xray_receipt += $res['radio_receipt'];
                            $date_balance += $res['balance'];
                            ?>
                        <tr>
                            <td class="text-center"><?= $sn++ ?></td>
                            <td><?= $res['ipd_no'] ?></td>
                            <td><?= $res['name'] ?></td>
                            <td class="text-center"><?= $res['bill_no'] ?></td>
                            <td class="text-right"><?= number_format($res['ip_bills'], 0) ?></td>
                            <td class="text-right"><?= number_format($res['ip_receipt'], 0) ?></td>
                            <td class="text-right"><?= number_format($res['lab_bills'], 0) ?></td>
                            <td class="text-right"><?= number_format($res['lab_receipt'], 0) ?></td>
                            <td class="text-right"><?= number_format($res['radio_bills'], 0) ?></td>
                            <td class="text-right"><?= number_format($res['radio_receipt'], 0) ?></td>
                            <td class="text-right"><strong><?= number_format($res['balance'], 0) ?></strong></td>
                            <td><?= $res['scheme'] ?></td>
                        </tr>
                        <?php 
                        }
                        
                        // Print last date subtotal
                        if($current_date != '') {
                        ?>
                        <tr style="background-color: #ffc107; font-weight: bold;">
                            <td colspan="4" class="text-right">Sub Total -
                                <?= date('d-m-Y', strtotime($current_date)) ?></td>
                            <td class="text-right"><?= number_format($date_ip_bills, 0) ?></td>
                            <td class="text-right"><?= number_format($date_ip_receipt, 0) ?></td>
                            <td class="text-right"><?= number_format($date_lab_bills, 0) ?></td>
                            <td class="text-right"><?= number_format($date_lab_receipt, 0) ?></td>
                            <td class="text-right"><?= number_format($date_xray_bills, 0) ?></td>
                            <td class="text-right"><?= number_format($date_xray_receipt, 0) ?></td>
                            <td class="text-right"><?= number_format($date_balance, 0) ?></td>
                            <td></td>
                        </tr>
                        <?php } ?>

                        <tr class="total-row" style="background-color: #28a745; color: white;">
                            <td colspan="4" class="text-right"><strong>GRAND TOTAL</strong></td>
                            <td class="text-right"><strong><?= number_format($total_ip_bills, 0) ?></strong></td>
                            <td class="text-right"><strong><?= number_format($total_ip_receipt, 0) ?></strong></td>
                            <td class="text-right"><strong><?= number_format($total_lab_bills, 0) ?></strong></td>
                            <td class="text-right"><strong><?= number_format($total_lab_receipt, 0) ?></strong></td>
                            <td class="text-right"><strong><?= number_format($total_xray_bills, 0) ?></strong></td>
                            <td class="text-right"><strong><?= number_format($total_xray_receipt, 0) ?></strong></td>
                            <td class="text-right"><strong><?= number_format($total_balance, 0) ?></strong></td>
                            <td></td>
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
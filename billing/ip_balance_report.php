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
    <title>IP Balance Report</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="./images/favicon.png">
    <link rel="stylesheet" href="./vendor/owl-carousel/css/owl.carousel.min.css">
    <link rel="stylesheet" href="./vendor/owl-carousel/css/owl.theme.default.min.css">
    <link href="./vendor/jqvmap/css/jqvmap.min.css" rel="stylesheet">
    <link href="./css/style.css" rel="stylesheet">
    <!-- Datatable -->
    <link href="./vendor/datatables/css/jquery.dataTables.min.css" rel="stylesheet">
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
        <?php include('leftbar.php') ?>

        <div class="content-body">
            <div class="container-fluid">
                <div class="row page-titles mx-0">
                    <div class="col-sm-6 p-md-0">
                        <div class="welcome-text">
                            <h4>IP Bills with Balance Details</h4>
                        </div>
                    </div>
                    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                            <li class="breadcrumb-item active"><a href="javascript:void(0)">IP Balance Report</a></li>
                        </ol>
                    </div>
                </div>

                <!-- Filter Form -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Filter Report</h4>
                            </div>
                            <div class="card-body">
                                <form method="GET" action="">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>From Date</label>
                                                <input type="date" name="from" class="form-control" value="<?= $from ?>"
                                                    required>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>To Date</label>
                                                <input type="date" name="to" class="form-control" value="<?= $to ?>"
                                                    required>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Search (IPD No/Name)</label>
                                                <input type="text" name="search" class="form-control"
                                                    value="<?= $search ?>" placeholder="Enter IPD No or Name">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>&nbsp;</label>
                                                <button type="submit" class="btn btn-primary btn-block">Generate
                                                    Report</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                                <a href="print_ip_balance_report.php?from=<?= $from ?>&to=<?= $to ?>&search=<?= $search ?>"
                                    target="_blank" class="btn btn-info btn-sm">
                                    <i class="fa fa-print"></i> Print Report
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Report Table -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">IP Bills with Balance Details - From
                                    <?= date('d-m-Y', strtotime($from)) ?> to <?= date('d-m-Y', strtotime($to)) ?></h4>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="datatable" class="display table table-bordered"
                                        style="min-width: 845px; font-size: 12px;">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th rowspan="2">Sl No</th>
                                                <th rowspan="2">IP No</th>
                                                <th rowspan="2">Name</th>
                                                <th rowspan="2">Bill No.</th>
                                                <th rowspan="2">Adjusted</th>
                                                <th colspan="2" class="text-center">In Patient</th>
                                                <th colspan="2" class="text-center">Laboratory</th>
                                                <th colspan="2" class="text-center">Radiology</th>
                                                <th colspan="2" class="text-center">Dialysis</th>
                                                <th colspan="2" class="text-center">Blood Center</th>
                                                <th rowspan="2" class="text-right">Balance</th>
                                                <th rowspan="2">Scheme</th>
                                            </tr>
                                            <tr>
                                                <th class="text-right">Bills</th>
                                                <th class="text-right">Receipt</th>
                                                <th class="text-right">Bills</th>
                                                <th class="text-right">Receipt</th>
                                                <th class="text-right">Bills</th>
                                                <th class="text-right">Receipt</th>
                                                <th class="text-right">Bills</th>
                                                <th class="text-right">Receipt</th>
                                                <th class="text-right">Bills</th>
                                                <th class="text-right">Receipt</th>
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
                                            $total_radio_bills = 0;
                                            $total_radio_receipt = 0;
                                            $total_dialysis_bills = 0;
                                            $total_dialysis_receipt = 0;
                                            $total_blood_bills = 0;
                                            $total_blood_receipt = 0;
                                            $total_balance = 0;
                                            
                                            $current_date = '';
                                            $date_ip_bills = 0;
                                            $date_ip_receipt = 0;
                                            $date_lab_bills = 0;
                                            $date_lab_receipt = 0;
                                            $date_radio_bills = 0;
                                            $date_radio_receipt = 0;
                                            $date_dialysis_bills = 0;
                                            $date_dialysis_receipt = 0;
                                            $date_blood_bills = 0;
                                            $date_blood_receipt = 0;
                                            $date_balance = 0;
                                            
                                            while($res = mysqli_fetch_assoc($result)) {
                                                // Check if date changed
                                                if($current_date != '' && $current_date != $res['date']) {
                                                    // Print date subtotal
                                                    ?>
                                            <tr class="bg-warning text-dark font-weight-bold">
                                                <td colspan="4" class="text-right">Sub Total</td>
                                                <td class="text-right"><?= number_format($date_ip_adjusted, 0) ?></td>
                                                <td class="text-right"><?= number_format($date_ip_bills, 0) ?></td>
                                                <td class="text-right"><?= number_format($date_ip_receipt, 0) ?></td>
                                                <td class="text-right"><?= number_format($date_lab_bills, 0) ?></td>
                                                <td class="text-right"><?= number_format($date_lab_receipt, 0) ?></td>
                                                <td class="text-right"><?= number_format($date_radio_bills, 0) ?></td>
                                                <td class="text-right"><?= number_format($date_radio_receipt, 0) ?></td>
                                                <td class="text-right"><?= number_format($date_dialysis_bills, 0) ?>
                                                </td>
                                                <td class="text-right"><?= number_format($date_dialysis_receipt, 0) ?>
                                                </td>
                                                <td class="text-right"><?= number_format($date_blood_bills, 0) ?></td>
                                                <td class="text-right"><?= number_format($date_blood_receipt, 0) ?></td>
                                                <td class="text-right"><?= number_format($date_balance, 0) ?></td>
                                                <td></td>
                                            </tr>
                                            <?php
                                                    // Reset date totals
                                                    $date_ip_bills = 0;
                                                    $date_ip_receipt = 0;
                                                    $date_ip_adjusted = 0;
                                                    $date_lab_bills = 0;
                                                    $date_lab_receipt = 0;
                                                    $date_radio_bills = 0;
                                                    $date_radio_receipt = 0;
                                                    $date_dialysis_bills = 0;
                                                    $date_dialysis_receipt = 0;
                                                    $date_blood_bills = 0;
                                                    $date_blood_receipt = 0;
                                                    $date_balance = 0;
                                                }
                                                
                                                // Print date header if new date
                                                if($current_date != $res['date']) {
                                                   
                                                    ?>
                                            <tr class="bg-primary text-white">
                                                <td colspan="16" class="font-weight-bold text-left text-black" style="padding: 10px; font-size: 14px; letter-spacing: 1px;">
                                                    <i class="fa fa-calendar"></i> BILL DATE: <?= strtoupper(date('d-m-Y', strtotime($res['date']))) ?>
                                                </td>
                                            </tr>
                                            <?php
                                                    $current_date = $res['date'];
                                                }
                                                
                                                // Update totals
                                                $total_ip_bills += $res['ip_bills'];
                                                $total_ip_receipt += $res['ip_receipt'];
                                                $total_lab_bills += $res['lab_bills'];
                                                $total_lab_receipt += $res['lab_receipt'];
                                                $total_radio_bills += $res['radio_bills'];
                                                $total_radio_receipt += $res['radio_receipt'];
                                                $total_dialysis_bills += $res['dialysis_bills'];
                                                $total_dialysis_receipt += $res['dialysis_receipt'];
                                                $total_blood_bills += $res['blood_bills'];
                                                $total_blood_receipt += $res['blood_receipt'];
                                                $total_balance += $res['balance'];
                                                
                                                $date_ip_bills += $res['ip_bills'];
                                                $date_ip_receipt += $res['ip_receipt'];
                                                $date_lab_bills += $res['lab_bills'];
                                                $date_lab_receipt += $res['lab_receipt'];
                                                $date_radio_bills += $res['radio_bills'];
                                                $date_radio_receipt += $res['radio_receipt'];
                                                $date_dialysis_bills += $res['dialysis_bills'];
                                                $date_dialysis_receipt += $res['dialysis_receipt'];
                                                $date_blood_bills += $res['blood_bills'];
                                                $date_blood_receipt += $res['blood_receipt'];
                                                $date_balance += $res['balance'];
                                                $date_ip_adjusted += $res['ip_adjusted'];
                                            ?>
                                            <tr>
                                                <td><?= $sn++ ?></td>
                                                <td><?= $res['ipd_no'] ?></td>
                                                <td><?= $res['name'] ?></td>
                                                <td><?= $res['bill_no']?></td>
                                                    <td><?= number_format($res['ip_adjusted'], 0) ?></td>
                                                <td class="text-right"><?= number_format($res['ip_bills'], 0) ?></td>
                                                <td class="text-right"><?= number_format($res['ip_receipt'], 0) ?></td>
                                                <td class="text-right"><?= number_format($res['lab_bills'], 0) ?></td>
                                                <td class="text-right"><?= number_format($res['lab_receipt'], 0) ?></td>
                                                <td class="text-right"><?= number_format($res['radio_bills'], 0) ?></td>
                                                <td class="text-right"><?= number_format($res['radio_receipt'], 0) ?>
                                                </td>
                                                <td class="text-right"><?= number_format($res['dialysis_bills'], 0) ?>
                                                </td>
                                                <td class="text-right"><?= number_format($res['dialysis_receipt'], 0) ?>
                                                </td>
                                                <td class="text-right"><?= number_format($res['blood_bills'], 0) ?></td>
                                                <td class="text-right"><?= number_format($res['blood_receipt'], 0) ?>
                                                </td>
                                                <td class="text-right"><?= number_format($res['balance'], 0) ?></td>
                                                <td><?= $res['scheme'] ?></td>
                                            </tr>
                                            <?php 
                                            }
                                            
                                            // Print last date subtotal
                                            if($current_date != '') {
                                            ?>
                                            <tr class="bg-warning text-dark font-weight-bold">
                                                <td colspan="4" class="text-right">Sub Total</td>
                                                <td class="text-right"><?= number_format($date_ip_adjusted, 0) ?></td>
                                                <td class="text-right"><?= number_format($date_ip_bills, 0) ?></td>
                                                <td class="text-right"><?= number_format($date_ip_receipt, 0) ?></td>
                                                
                                                <td class="text-right"><?= number_format($date_lab_bills, 0) ?></td>
                                                <td class="text-right"><?= number_format($date_lab_receipt, 0) ?></td>
                                                <td class="text-right"><?= number_format($date_radio_bills, 0) ?></td>
                                                <td class="text-right"><?= number_format($date_radio_receipt, 0) ?></td>
                                                <td class="text-right"><?= number_format($date_dialysis_bills, 0) ?>
                                                </td>
                                                <td class="text-right"><?= number_format($date_dialysis_receipt, 0) ?>
                                                </td>
                                                <td class="text-right"><?= number_format($date_blood_bills, 0) ?></td>
                                                <td class="text-right"><?= number_format($date_blood_receipt, 0) ?></td>
                                                <td class="text-right"><?= number_format($date_balance, 0) ?></td>
                                                <td></td>
                                            </tr>
                                            <?php } ?>

                                            <tr class="bg-success text-white font-weight-bold">
                                                <td colspan="4" class="text-right">GRAND TOTAL</td>
                                                <td class="text-right"><?= number_format($total_ip_bills, 0) ?></td>
                                                <td class="text-right"><?= number_format($total_ip_receipt, 0) ?></td>
                                                <td class="text-right"><?= number_format($total_lab_bills, 0) ?></td>
                                                <td class="text-right"><?= number_format($total_lab_receipt, 0) ?></td>
                                                <td class="text-right"><?= number_format($total_radio_bills, 0) ?></td>
                                                <td class="text-right"><?= number_format($total_radio_receipt, 0) ?>
                                                </td>
                                                <td class="text-right"><?= number_format($total_dialysis_bills, 0) ?>
                                                </td>
                                                <td class="text-right"><?= number_format($total_dialysis_receipt, 0) ?>
                                                </td>
                                                <td class="text-right"><?= number_format($total_blood_bills, 0) ?></td>
                                                <td class="text-right"><?= number_format($total_blood_receipt, 0) ?>
                                                </td>
                                                <td class="text-right"><?= number_format($total_balance, 0) ?></td>
                                                <td></td>
                                            </tr>
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

    <!-- Required vendors -->
    <script src="./vendor/global/global.min.js"></script>
    <script src="./js/quixnav-init.js"></script>
    <script src="./js/custom.min.js"></script>

    <!-- Datatable -->
    <script src="./vendor/datatables/js/jquery.dataTables.min.js"></script>
    <script src="./js/plugins-init/datatables.init.js"></script>

    <script>
    $(document).ready(function() {
        $('#datatable').DataTable({
            "pageLength": 25,
            "order": [
                [1, "asc"]
            ]
        });
    });
    </script>
</body>

</html>
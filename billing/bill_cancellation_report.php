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
                            <h4>Bill Cancellation Report</h4>
                        </div>
                    </div>
                    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                            <li class="breadcrumb-item active"><a href="javascript:void(0)">Bill Cancellation Report</a>
                            </li>
                        </ol>
                    </div>
                </div>

                <!-- Filter Form -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Filter Reports</h4>
                            </div>
                            <div class="card-body">
                                <form method="POST" action="">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>From Date</label>
                                                <input type="date" class="form-control" name="from" value="<?= $from ?>"
                                                    required>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>To Date</label>
                                                <input type="date" class="form-control" name="to" value="<?= $to ?>"
                                                    required>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Service Type</label>
                                                <select class="form-control" name="service_type">
                                                    <option value="">All Services</option>
                                                    <option value="OP" <?= $service_type == 'OP' ? 'selected' : '' ?>>
                                                        OPD</option>
                                                    <option value="IP" <?= $service_type == 'IP' ? 'selected' : '' ?>>
                                                        IPD</option>
                                                    <option value="Pathology"
                                                        <?= $service_type == 'Pathology' ? 'selected' : '' ?>>Lab
                                                    </option>
                                                    <option value="Radiology"
                                                        <?= $service_type == 'Radiology' ? 'selected' : '' ?>>Radiology
                                                    </option>
                                                    <option value="Dialysis"
                                                        <?= $service_type == 'Dialysis' ? 'selected' : '' ?>>Dialysis
                                                    </option>
                                                    <option value="Blood Bank"
                                                        <?= $service_type == 'Blood Bank' ? 'selected' : '' ?>>Blood
                                                        Centre</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Search</label>
                                                <input type="text" class="form-control" name="search"
                                                    value="<?= $search ?>" placeholder="Patient/Bill No">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <button type="submit" class="btn btn-primary float-right" name="submit">
                                                <i class="bi bi-search"></i> Search
                                            </button>
                                            <a href="print_bill_cancellation_report.php?from=<?= $from ?>&to=<?= $to ?>&service_type=<?= $service_type ?>&search=<?= $search ?>"
                                                class="btn btn-warning float-right mr-2" target="_blank">
                                                <i class="bi bi-printer"></i> Print
                                            </a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Summary Cards -->
                <?php
                $summary = $newReport->getBillCancellationSummary($from, $to, $service_type);
                $total_bills = 0;
                $total_amount = 0;
                $total_returned = 0;
                ?>
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Summary by Service Type</h4>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead class="thead-dark">
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
                                            while($sum = mysqli_fetch_assoc($summary)) {
                                                $total_bills += $sum['total_bills'];
                                                $total_amount += $sum['total_amount'];
                                                $total_returned += $sum['total_returned'];
                                            ?>
                                            <tr>
                                                <td><?= $sum['service_type'] ?></td>
                                                <td class="text-right"><?= $sum['total_bills'] ?></td>
                                                <td class="text-right">₹ <?= number_format($sum['total_amount'], 2) ?>
                                                </td>
                                                <td class="text-right">₹ <?= number_format($sum['total_discount'], 2) ?>
                                                </td>
                                                <td class="text-right">₹ <?= number_format($sum['total_paid'], 2) ?>
                                                </td>
                                                <td class="text-right">₹ <?= number_format($sum['total_returned'], 2) ?>
                                                </td>
                                            </tr>
                                            <?php } ?>
                                            <tr class="font-weight-bold bg-light">
                                                <td>TOTAL</td>
                                                <td class="text-right"><?= $total_bills ?></td>
                                                <td class="text-right">₹ <?= number_format($total_amount, 2) ?></td>
                                                <td class="text-right" colspan="2"></td>
                                                <td class="text-right">₹ <?= number_format($total_returned, 2) ?></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Detailed Report -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Cancelled Bills Details</h4>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="datatable" class="display table table-bordered"
                                        style="min-width: 845px; font-size: 12px;">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th>Sl No</th>
                                                <th>Date</th>
                                                <th>Bill No</th>
                                                <th>Service Type</th>
                                                <th>OPD No</th>
                                                <th>IPD No</th>
                                                <th>Patient Name</th>
                                                <th>Age/Gender</th>
                                                <th>Services</th>
                                                <th>Bill Amount</th>
                                                <th>Discount</th>
                                                <th>Net Amount</th>
                                                <th>Paid</th>
                                                <th>Returned</th>
                                                <th>Cancelled By</th>
                                                <th>Cancelled At</th>
                                                <th>Returned By</th>
                                                <th>Returned At</th>
                                                <th>Reason
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $result = $newReport->getBillCancellationReport($from, $to, $service_type, $search);
                                            $sn = 1;
                                            while($res = mysqli_fetch_assoc($result)) {
                                                $net_amount = $res['gross_total'] - $res['service_discount'] - $res['discount_amount'];
                                            ?>
                                            <tr>
                                                <td><?= $sn++ ?></td>
                                                <td><?= date('d-m-Y', strtotime($res['date'])) ?></td>
                                                <td><?= $res['bill_no'] ?></td>
                                                <td><span class="badge badge-primary"><?= strtoupper($res['service_type']) == 'PATHOLOGY' ? 'LAB' : $res['service_type'] ?></span>
                                                </td>
                                                <td><?= $res['opd_no'] ?></td>
                                                <td><?= $res['ipd_no'] ?></td>
                                                <td><?= $res['name'] ?></td>
                                                <td><?= $res['age'] ?>/<?= substr($res['gender'], 0, 1) ?></td>
                                                <td><?= $res['services'] ?></td>
                                                <td class="text-right">₹ <?= number_format($res['gross_total'], 2) ?>
                                                </td>
                                                <td class="text-right">₹
                                                    <?= number_format($res['service_discount'] + $res['discount_amount'], 2) ?>
                                                </td>
                                                <td class="text-right">₹ <?= number_format($net_amount, 2) ?></td>
                                                <td class="text-right">₹ <?= number_format($res['paid_amount'], 2) ?>
                                                </td>
                                                <td class="text-right">₹
                                                    <?= number_format($res['returned_amount'], 2) ?></td>
                                                <td><?= $res['updated_by'] ?></td>
                                                <td><?= date('d-m-Y H:i', strtotime($res['cancelled_at'])) ?></td>
                                                <td><?= $res['returned_by'] ?></td>
                                                <td><?= $res['returned_at'] && $res['returned_at'] != '0000-00-00 00:00:00' ? date('d-m-Y H:i', strtotime($res['returned_at'])) : '' ?>
                                                </td>
                                                <td><?= $res['cancellation_note'] ?></td>
                                            </tr>
                                            <?php } ?>
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
                [1, "desc"]
            ],
            "columnDefs": [{
                "orderable": false,
                "targets": [7, 15]
            }]
        });
    });
    </script>
</body>

</html>
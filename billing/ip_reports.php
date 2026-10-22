<?php

date_default_timezone_set("Asia/Kolkata");

require_once(__DIR__ . '/../model/session_config.php');
$date = date("Y-m-d");

include('model/reportClass.php');
$newReport = new report;

if (isset($_REQUEST['op_from'])) {
    $op_from = $_REQUEST['op_from'];
} else {
    $op_from = date("Y-m-d");
}
if (isset($_REQUEST['op_to'])) {
    $op_to = $_REQUEST['op_to'];
} else {
    $op_to = $date;
}
if (isset($_REQUEST['dept_from'])) {
    $dept_from = $_REQUEST['dept_from'];
} else {
    $dept_from = $date;
}
if (isset($_REQUEST['dept_to'])) {
    $dept_to = $_REQUEST['dept_to'];
} else {
    $dept_to = $date;
}
if (isset($_REQUEST['cent_from'])) {
    $cent_from = $_REQUEST['cent_from'];
} else {
    $cent_from = $date;
}
if (isset($_REQUEST['cent_to'])) {
    $cent_to = $_REQUEST['cent_to'];
} else {
    $cent_to = $date;
}

if (isset($_REQUEST['proce_from'])) {
    $proce_from = $_REQUEST['proce_from'];
} else {
    $proce_from = $date;
}
if (isset($_REQUEST['proce_to'])) {
    $proce_to = $_REQUEST['proce_to'];
} else {
    $proce_to = $date;
}
if (isset($_REQUEST['proced_from'])) {
    $proced_from = $_REQUEST['proced_from'];
} else {
    $proced_from = $date;
}
if (isset($_REQUEST['proced_to'])) {
    $proced_to = $_REQUEST['proced_to'];
} else {
    $proced_to = $date;
}
if (isset($_REQUEST['gen'])) {
    $gen = $_REQUEST['gen'];
} else {
    $gen = "";
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>IP Report</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="./images/favicon.png">
    <link rel="stylesheet" href="./vendor/owl-carousel/css/owl.carousel.min.css">
    <link rel="stylesheet" href="./vendor/owl-carousel/css/owl.theme.default.min.css">
    <link href="./vendor/jqvmap/css/jqvmap.min.css" rel="stylesheet">
    <link href="./css/style.css" rel="stylesheet">

    <link href="./vendor/datatables/css/jquery.dataTables.min.css" rel="stylesheet">


    <!-- DataTables -->

    <link href="assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/libs/datatables.net-select-bs4/css/select.bootstrap4.min.css" rel="stylesheet" type="text/css" />






</head>

<body>

    <!--*******************
        Preloader start
    ********************-->
    <div id="preloader">
        <div class="sk-three-bounce">
            <div class="sk-child sk-bounce1"></div>
            <div class="sk-child sk-bounce2"></div>
            <div class="sk-child sk-bounce3"></div>
        </div>
    </div>
    <!--*******************
        Preloader end
    ********************-->


    <!--**********************************
        Main wrapper start
    ***********************************-->
    <div id="main-wrapper">

        <?php include('leftbar.php'); ?>

        <!-- View -->





        <!--**********************************
            Content body start
        ***********************************-->
        <div class="content-body text-uppercase">
            <!-- row -->
            <div class="container-fluid">

                <div class="row mb-4">
                    <h4 class="text-uppercase ml-3">IP Report</h4>
                </div>


                <ul class="nav nav-pills mb-3 justify-content-around" id="pills-tab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="pills-home-tab" data-toggle="pill" href="#pills-home" role="tab" aria-controls="pills-home" aria-selected="true"> Summary Report</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="pills-deptwise-tab" data-toggle="pill" href="#pills-deptwise" role="tab" aria-controls="pills-deptwise" aria-selected="false"> &nbsp; Department wise Report</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="pills-combinedop-tab" data-toggle="pill" href="#pills-combinedop" role="tab" aria-controls="pills-combinedop" aria-selected="false">Doctor Wise Report</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="pills-Count-tab" data-toggle="pill" href="#pills-Count" role="tab" aria-controls="pills-Count" aria-selected="false">Service wise Report</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="pills-consol-tab" data-toggle="pill" href="#pills-consol" role="tab" aria-controls="pills-consol" aria-selected="false">Category Wise Report</a>
                    </li>



                </ul>






                <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab"><br>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <h4>IP Report </h4><br>

                                            </div>
                                        </div>
                                        <form action="#pills-home-tab" method="GET">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="exampleInputEmail1"> From Date </label>
                                                        <input type="date" class="form-control" name="op_from" id="op_from" required value="<?= $op_from ?>">

                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="exampleInputEmail1"> To Date </label>
                                                        <input type="date" class="form-control" name="op_to" id="op_to" required value="<?= $op_to ?>">

                                                    </div>
                                                </div>

                                                <?php if ($_SESSION['role'] == 'Admin'  || $_SESSION['loginid'] == '57') { ?>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="exampleInputEmail1"> Type </label>
                                                            <select class="form-control" name="gen" id="gen">
                                                                <option value="" <?php if ($_REQUEST['gen'] == "") echo "selected"; ?>>All</option>
                                                                <option value="0" <?php if ($_REQUEST['gen'] == "0") echo "selected"; ?>>Original</option>
                                                                <option value="1" <?php if ($_REQUEST['gen'] == "1") echo "selected"; ?>>Generated</option>
                                                            </select>

                                                        </div>
                                                    </div>
                                                <?php } ?>

                                            </div>

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <button type="submit" class="btn btn-success  float-right btn-rounded" name="submit"><span><i class="bi bi-search"></i></span> Search</button>
                                                </div>
                                            </div>
                                        </form>

                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <h5 class="ml-4">IP Summary Report <span class="text-danger">
                                                        <?php if (isset($_REQUEST['op_from']) && isset($_REQUEST['op_to'])) {
                                                            echo "Between " .  date('d.m.Y', strtotime($_REQUEST['op_from'])) . " and " . date('d.m.Y', strtotime($_REQUEST['op_to']));
                                                        } ?>
                                                    </span></h5>
                                            </div>

                                        </div>
                                        <hr>


                                        <table id="datatable-buttons2" class="table table-responsive text-wrap text-left  no-footer " role="grid" aria-describedby="datatable-buttons_info">
                                            <thead class="thead-dark">
                                                <tr>
                                                    <th>Sl NO</th>
                                                    <th>Date</th>
                                                    <th>Bill NO</th>
                                                    <th>OP Reg No</th>
                                                    <th>IP Reg No</th>
                                                    <th>Name</th>
                                                    <th>Gender</th>
                                                    <th>Gross Amt</th>
                                                    <th>Discount Amount</th>
                                                    <th>Adjusted Advance Amt</th>
                                                    <th>Returned Amt</th>
                                                    <th>Net Amt</th>
                                                    <th>Paid Amt</th>
                                                    <th>Balance Amt</th>
                                                    <th>Collected By</th>


                                                </tr>
                                            </thead>
                                            <tbody class="text-left text-black">
                                                <?php $ot = $newReport->getIPDetails($op_from, $op_to, 'IP', $gen);
                                                $sn = 1;
                                                $amount = $serv_disc = $paid = $adv = $tot = $discount_amount = $bal = 0;
                                                while ($row = mysqli_fetch_assoc($ot)) {
                                                ?>
                                                    <tr>
                                                        <td><?= $sn ?></td>
                                                        <td><?= date("d.m.Y", strtotime($row['date'])) ?></td>
                                                        <td><?= $row['bill_no'] ?></td>
                                                        <td><?= $row['opd_no'] ?></td>
                                                        <td><?= $row['ipd_no'] ?></td>
                                                        <td><?= $row['name'] ?></td>
                                                        <td><?= $row['gender'] ?></td>
                                                        <td><?= $row['amount'] ?></td>
                                                        <td><?= $row['discount_amount'] ?></td>
                                                        <td><?= $row['advance'] ?></td>
                                                        <td><?= $row['returned_amount'] ?></td>
                                                        <td><?= $row['amount'] - $row['discount_amount'] - abs($row['advance'] - $row['returned_amount']) ?></td>
                                                        <td><?= $row['paid_amount'] ?></td>
                                                        <td><?= $row['outstand_amount'] ?></td>
                                                        <td><?= $row['collected_by'] ?></td>

                                                    </tr>
                                                <?php $sn++;
                                                    $amount += $row['amount'];
                                                    $serv_disc += $row['returned_amount'];
                                                    $paid += $row['paid_amount'];
                                                    $discount_amount += $row['discount_amount'];
                                                    $adv += $row['advance'];
                                                    $tot += $row['amount'] - $row['discount_amount'] - $row['advance'] - $row['returned_amount'];
                                                    $bal += $row['outstand_amount'];
                                                } ?>
                                            </tbody>
                                            <tfoot>
                                                <th colspan="6"></th>
                                                <th class="text-right">Total</th>
                                                <th><?= $amount ?></th>
                                                <th><?= $discount_amount ?></th>
                                                <th><?= $adv ?></th>
                                                <th><?= $serv_disc ?></th>

                                                <th><?= $tot ?></th>
                                                <th><?= $paid ?></th>
                                                <th><?= $bal ?></th>
                                                <th></th>
                                            </tfoot>
                                        </table>

                                    </div>
                                </div>
                            </div>
                        </div>





                    </div>
                    <div class="tab-pane fade  " id="pills-deptwise" role="tabpanel" aria-labelledby="pills-deptwise-tab"><br>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <h4>Department wise Report </h4><br>

                                            </div>
                                        </div>
                                        <form action="#pills-deptwise-tab" method="GET">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="exampleInputEmail1"> From Date </label>
                                                        <input type="date" class="form-control" name="dept_from" id="dept_from" required value="<?= $dept_from ?>">

                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="exampleInputEmail1"> To Date </label>
                                                        <input type="date" class="form-control" name="dept_to" id="dept_to" required value="<?= $dept_to ?>">

                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="exampleInputEmail1"> Department </label>
                                                        <select class="form-control" name="dept" id="dept">
                                                            <option value="">ALL</option>
                                                            <?php $dept = $newReport->department();
                                                            while ($row = mysqli_fetch_assoc($dept)) { ?>
                                                                <option value="<?= $row['id'] ?>" <?php if ($_REQUEST['dept'] == $row['id']) echo 'selected'; ?>><?= $row['dept_name'] ?></option>
                                                            <?php } ?>
                                                        </select>

                                                    </div>
                                                </div>



                                            </div>


                                            <div class="row">
                                                <div class="col-md-12">
                                                    <button type="submit" class="btn btn-success  float-right btn-rounded" name="submit"><span><i class="bi bi-search"></i></span> Search</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">

                                            <div class="col-md-6">
                                                <h5 class="ml-4">Department Wise Report <span class="text-danger">
                                                        <?php if (isset($dept_from) && isset($dept_to)) {
                                                            echo "Between " .  date('d.m.Y', strtotime($dept_from)) . " and " . date('d.m.Y', strtotime($dept_to));
                                                        } ?>
                                                    </span></h5>
                                            </div>
                                            <div class="col-md-6">
                                                <a href='print_dept_wise_report_ip.php?from=<?= $dept_from ?>&to=<?= $dept_to ?>&dept=<?= $_REQUEST['dept'] ?>&type=IP' class="btn btn-danger btn-sm float-right">Print Summary</a>
                                                <a href='print_dept_detailed_report_ip.php?from=<?= $dept_from ?>&to=<?= $dept_to ?>&dept=<?= $_REQUEST['dept'] ?>&type=IP' class="btn btn-warning btn-sm mr-2 float-right">Print Detailed</a>
                                            </div>
                                        </div>
                                        <hr>


                                        <table id="datatable-buttons3" class="table text-wrap text-left">
                                            <thead class="thead-dark">
                                                <tr>
                                                    <th>Sl NO</th>
                                                    <th>Departmwent Name</th>
                                                    <th>Bill Count</th>
                                                    <th>Gross Amt</th>
                                                    <th>Service Level Disc</th>
                                                    <th>Bill Amount</th>
                                                    <th>Discount Amount</th>
                                                    <th>Net Amount</th>
                                                    <th>Adjusted Adv Amt</th>
                                                    <th>Returned Amount</th>
                                                    <th>Paid Amount</th>
                                                    <th>Total Amount</th>
                                                    <th>Outstading Amount</th>



                                                </tr>
                                            </thead>
                                            <tbody class="text-left text-black">
                                                <?php $dept = $newReport->getDept_IP($dept_from, $dept_to, 'IP', $_REQUEST['dept']);
                                                $sn = 1;
                                                $grstotal = $srvtotal = $disctotal = $billtotal = $nettotal = $paidtotal = $outtotal = $billcount = $adv = $ret = $tot = 0;
                                                while ($row = mysqli_fetch_assoc($dept)) { ?>
                                                    <tr>
                                                        <td><?= $sn ?></td>
                                                        <td><?= $row['dept_name'] ?></td>
                                                        <td><?= $row['bill'] ?></td>
                                                        <td><?= $row['amount'] + $row['service_level_discount'] + $row['returned_amount'] ?></td>
                                                        <td><?= $row['service_level_discount'] ?></td>
                                                        <td><?= $row['amount'] + $row['returned_amount'] ?></td>
                                                        <td><?= $row['discount'] ?></td>
                                                        <td><?= $row['amount'] - $row['discount'] + $row['returned_amount'] ?></td>
                                                        <td><?= $row['advance'] ?></td>
                                                        <td><?= $row['returned_amount'] ?></td>
                                                        <td><?= $row['paid_amount'] ?></td>
                                                        <td><?= $row['paid_amount'] - $row['returned_amount'] + $row['advance'] ?></td>
                                                        <td><?= $row['amount'] - $row['discount'] - $row['paid_amount'] - $row['advance'] + $row['returned_amount'] ?></td>
                                                    </tr>
                                                <?php
                                                    $billcount += $row['bill'];
                                                    $grstotal += $row['amount'] + $row['service_level_discount'] + $row['returned_amount'];
                                                    $srvtotal += $row['service_level_discount'];
                                                    $disctotal += $row['amount'] + $row['returned_amount'];
                                                    $billtotal += $row['discount'];
                                                    $nettotal += $row['amount'] - $row['discount'] + $row['returned_amount'];
                                                    $paidtotal += $row['paid_amount'];
                                                    $adv += $row['advance'];
                                                    $ret += $row['returned_amount'];
                                                    $tot += $row['paid_amount'] - $row['returned_amount'] + $row['advance'];
                                                    $outtotal += $row['amount'] - $row['discount'] - $row['paid_amount'] - $row['advance'] + $row['returned_amount'];
                                                    $sn++;
                                                } ?>


                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th></th>
                                                    <th class="text-right">Total</th>
                                                    <th><?= $billcount ?></th>
                                                    <th><?= $grstotal ?></th>
                                                    <th><?= $srvtotal ?></th>
                                                    <th><?= $disctotal ?></th>
                                                    <th><?= $billtotal ?></th>
                                                    <th><?= $nettotal ?></th>
                                                    <th><?= $adv ?></th>
                                                    <th><?= $ret ?></th>
                                                    <th><?= $paidtotal ?></th>
                                                    <th><?= $tot ?></th>
                                                    <th><?= $outtotal ?></th>
                                                </tr>
                                            </tfoot>
                                        </table>

                                    </div>
                                </div>
                            </div>
                        </div>


                    </div>
                    <div class="tab-pane fade  " id="pills-combinedop" role="tabpanel" aria-labelledby="pills-combinedop-tab"><br>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <h4>Doctor wise Report </h4><br>

                                            </div>
                                        </div>
                                        <form action="#pills-combinedop-tab" method="GET">

                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="exampleInputEmail1"> From Date </label>
                                                        <input type="date" class="form-control" name="cent_from" id="cent_from" value='<?= $cent_from ?>' required>

                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="exampleInputEmail1"> To Date </label>
                                                        <input type="date" class="form-control" name="cent_to" id="cent_to" value='<?= $cent_to ?>' required>

                                                    </div>
                                                </div>



                                            </div>


                                            <div class="row">
                                                <div class="col-md-12">
                                                    <button type="submit" class="btn btn-success  float-right btn-rounded" name="submit"><span><i class="bi bi-search"></i></span> Search</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">

                                                <h5 class="ml-4">Doctor Wise Report <span class="text-danger">
                                                        <?php if (isset($cent_from) && isset($cent_to)) {
                                                            echo "Between " .  date('d.m.Y', strtotime($cent_from)) . " and " . date('d.m.Y', strtotime($cent_to));
                                                        } ?>
                                                    </span></h5>

                                            </div>
                                            <div class="col-md-6">
                                                <a href="print_doctor_wise_report_ip.php?from=<?= $cent_from ?>&to=<?= $cent_to ?>&type=IP" target="_blank" class="float-right badge badge-pill badge-light"><i class="icofont-print"></i> Print </a>
                                            </div>
                                        </div>
                                        <hr>


                                        <table id="datatable-buttons4" class="table text-wrap text-left">
                                            <thead class="thead-dark">
                                                <tr>
                                                    <th>Sl NO</th>
                                                    <th>Doctor Name</th>
                                                    <th>Bill Count</th>
                                                    <th>Gross Amt</th>
                                                    <th>Service Level Disc</th>
                                                    <th>Bill Amount</th>
                                                    <th>Discount Amount</th>
                                                    <th>Net Amount</th>
                                                    <th>Adjusted Adv Amt</th>
                                                    <th>Paid Amount</th>
                                                    <th>Returned Amount</th>
                                                    <th>Outstading Amount</th>


                                                </tr>
                                            </thead>
                                            <tbody class="text-left text-black">
                                                <?php
                                                $cent = $newReport->getDoctorBillIP($cent_from, $cent_to, 'IP');
                                                $sn = 1;
                                                $grstotal = $srvtotal = $disctotal = $billtotal = $nettotal = $paidtotal = $outtotal = $billcount = $adv = $ret = 0;
                                                while ($row = mysqli_fetch_assoc($cent)) { ?>
                                                    <tr>
                                    </div>
                                    <td><?= $sn ?></td>
                                    <td><?= $row['doctor'] ?></td>
                                    <td><?= $row['bill'] ?></td>
                                    <td><?= $row['amount'] + $row['service_level_discount'] ?></td>
                                    <td><?= $row['service_level_discount'] ?></td>
                                    <td><?= $row['amount'] ?></td>
                                    <td><?= $row['discount'] ?></td>
                                    <td><?= $row['amount'] - $row['discount'] ?></td>
                                    <td><?= $row['advance'] ?></td>
                                    <td><?= $row['returned_amount'] ?></td>
                                    <td><?= $row['paid_amount'] ?></td>
                                    <td><?= $row['amount'] - $row['discount'] - $row['paid_amount'] - $row['advance'] + $row['returned_amount'] ?></td>
                                    </tr>
                                <?php
                                                    $billcount += $row['bill'];
                                                    $grstotal += $row['amount'] + $row['service_level_discount'];
                                                    $srvtotal += $row['service_level_discount'];
                                                    $disctotal += $row['amount'];
                                                    $billtotal += $row['discount'];
                                                    $nettotal += $row['amount'] - $row['discount'];
                                                    $paidtotal += $row['paid_amount'];
                                                    $adv += $row['advance'];
                                                    $ret += $row['returned_amount'];
                                                    $outtotal += $row['amount'] - $row['discount'] - $row['paid_amount'] - $row['advance'] + $row['returned_amount'];
                                                    $sn++;
                                                } ?>


                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th></th>
                                        <th class="text-right">Total</th>
                                        <th><?= $billcount ?></th>
                                        <th><?= $grstotal ?></th>
                                        <th><?= $srvtotal ?></th>
                                        <th><?= $disctotal ?></th>
                                        <th><?= $billtotal ?></th>
                                        <th><?= $nettotal ?></th>
                                        <th><?= $adv ?></th>
                                        <th><?= $ret ?></th>
                                        <th><?= $paidtotal ?></th>
                                        <th><?= $outtotal ?></th>
                                    </tr>
                                </tfoot>
                                </table>

                                </div>
                            </div>
                        </div>
                    </div>


                </div>
                <div class="tab-pane fade  " id="pills-Count" role="tabpanel" aria-labelledby="pills-Count-tab"><br>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h4>Service wise Report </h4><br>

                                        </div>
                                    </div>
                                    <form action="#pills-Count-tab" method="GET">

                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1"> From Date </label>
                                                    <input type="date" class="form-control" name="proce_from" id="proce_from" value="<?= $proce_from ?>" required>

                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1"> To Date </label>
                                                    <input type="date" class="form-control" name="proce_to" id="proce_to" value="<?= $proce_to ?>" required>

                                                </div>
                                            </div>



                                        </div>


                                        <div class="row">
                                            <div class="col-md-12">
                                                <button type="submit" class="btn btn-success  float-right btn-rounded" name="submit"><span><i class="bi bi-search"></i></span> Search</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <h5 class="ml-4">Service wise Report <span class="text-danger">
                                                <?php if (isset($_REQUEST['proce_from']) && isset($_REQUEST['proce_to'])) {
                                                    echo "Between " .  date('d.m.Y', strtotime($_REQUEST['proce_from'])) . " and " . date('d.m.Y', strtotime($_REQUEST['proce_to']));
                                                } ?>
                                            </span></h5>
                                    </div>
                                    <hr>

                                    <div class="table-responsive">
                                        <table id="example3" class="table table-hoverde display text-left">
                                            <thead class="thead-dark">
                                                <tr>
                                                    <th>Sl NO</th>
                                                    <th>Service Name</th>
                                                    <th>Bill Count</th>
                                                    <th>Unit</th>
                                                    <th>Amount</th>
                                                    <th>Service Lvl Disc</th>
                                                    <th>Bill Amount</th>




                                                </tr>
                                            </thead>
                                            <tbody class="text-left text-black">
                                                <?php $sn = 1;
                                                $bill = $unit = $price = $gross = $disc = $total = 0;
                                                $data = $newReport->getServiceReportIP($proce_from, $proce_to, 'IP');
                                                while ($row = mysqli_fetch_assoc($data)) {
                                                ?>
                                                    <tr>
                                                        <td><?= $sn ?></td>
                                                        <td><?= $row['name'] ?></td>
                                                        <td><?= $row['bill'] ?></td>
                                                        <td><?= $row['unit'] ?></td>
                                                        <td><?= $row['price'] ?></td>
                                                        <td><?= $row['service_level_discount'] ?></td>
                                                        <td><?= ($row['price'] - $row['service_level_discount']) ?></td>

                                                    </tr>
                                                <?php
                                                    $bill += $row['bill'];
                                                    $unit += $row['unit'];
                                                    $price += $row['price'];
                                                    $disc += $row['service_level_discount'];
                                                    $total += ($row['price']) - $row['service_level_discount'];
                                                    $sn++;
                                                } ?>


                                            </tbody>
                                            <tfoot>
                                                <th></th>
                                                <th class="text-right">Total</th>
                                                <th><?= $bill ?></th>
                                                <th><?= $unit ?></th>
                                                <th><?= $price ?></th>
                                                <th><?= $disc ?></th>
                                                <th><?= $total ?></th>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade  " id="pills-consol" role="tabpanel" aria-labelledby="pills-consol-tab"><br>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h4>Patient Category Wise Report</h4><br>

                                        </div>
                                    </div>
                                    <form action="#pills-consol-tab" method="GET">

                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1"> From Date </label>
                                                    <input type="date" class="form-control" name="proced_from" id="proced_from" value="<?= $proced_from ?>" required>

                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1"> To Date </label>
                                                    <input type="date" class="form-control" name="proced_to" id="proced_to" value="<?= $proced_to ?>" required>

                                                </div>
                                            </div>



                                        </div>


                                        <div class="row">
                                            <div class="col-md-12">
                                                <button type="submit" class="btn btn-success  float-right btn-rounded" name="submit"><span><i class="bi bi-search"></i></span> Search</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <h5 class="ml-4">Patient Category Wise Report <span class="text-danger"><?php if (isset($_REQUEST['proced_from']) && isset($_REQUEST['proced_to'])) {
                                                                                                                    echo "Between " .  date('d.m.Y', strtotime($_REQUEST['proced_from'])) . " and " . date('d.m.Y', strtotime($_REQUEST['proced_to']));
                                                                                                                } ?></span></h5>
                                    </div>
                                    <hr>

                                    <div class="table-responsive">
                                        <table id="example3" class="table table-hoverde display text-left">
                                            <thead class="thead-dark">
                                                <tr>
                                                    <th>Sl NO</th>
                                                    <th>Category Name</th>
                                                    <th>Bill Count</th>
                                                    <th>Gross Amt</th>
                                                    <th>Service Level Disc</th>
                                                    <th>Bill Amount</th>
                                                    <th>Discount Amount</th>
                                                    <th>Net Amount</th>
                                                    <th>Adjusted Advance amt</th>
                                                    <th>Paid Amount</th>
                                                    <th>Outstading Amount</th>


                                                </tr>
                                            </thead>
                                            <tbody class="text-left text-black">
                                                <?php
                                                $cent = $newReport->getCategoryBillIP($proced_from, $proced_to, 'IP');
                                                $sn = 1;
                                                $grstotal = $srvtotal = $disctotal = $billtotal = $nettotal = $paidtotal = $outtotal = $billcount = $adv = 0;
                                                while ($row = mysqli_fetch_assoc($cent)) { ?>
                                                    <tr>
                                    </div>
                                    <td><?= $sn ?></td>
                                    <td><?= $row['charge_type'] ?></td>
                                    <td><?= $row['bill'] ?></td>
                                    <td><?= $row['amount'] + $row['service_level_discount'] ?></td>
                                    <td><?= $row['service_level_discount'] ?></td>
                                    <td><?= $row['amount'] ?></td>
                                    <td><?= $row['discount'] ?></td>
                                    <td><?= $row['amount'] - $row['discount'] ?></td>
                                    <td><?= $row['advance'] ?></td>
                                    <td><?= $row['paid_amount'] ?></td>
                                    <td><?= $row['amount'] - $row['discount'] - $row['paid_amount'] - $row['advance'] ?></td>
                                    </tr>
                                <?php
                                                    $billcount += $row['bill'];
                                                    $grstotal += $row['amount'] + $row['service_level_discount'];
                                                    $srvtotal += $row['service_level_discount'];
                                                    $disctotal += $row['amount'];
                                                    $billtotal += $row['discount'];
                                                    $nettotal += $row['amount'] - $row['discount'];
                                                    $adv += $row['advance'];
                                                    $paidtotal += $row['paid_amount'];
                                                    $outtotal += $row['amount'] - $row['discount'] - $row['paid_amount'] - $row['advance'];
                                                    $sn++;
                                                } ?>


                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th></th>
                                        <th class="text-right">Total</th>
                                        <th><?= $billcount ?></th>
                                        <th><?= $grstotal ?></th>
                                        <th><?= $srvtotal ?></th>
                                        <th><?= $disctotal ?></th>
                                        <th><?= $billtotal ?></th>
                                        <th><?= $nettotal ?></th>
                                        <th><?= $adv ?></th>
                                        <th><?= $paidtotal ?></th>
                                        <th><?= $outtotal ?></th>
                                    </tr>
                                </tfoot>
                                </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>




    </div>
    </div>
    <!--**********************************
            Content body end
        ***********************************-->


    <!--**********************************
            Footer start
        ***********************************-->
    <?php include('footer.php') ?>
    <!--**********************************
            Footer end
        ***********************************-->

    <!--**********************************
           Support ticket button start
        ***********************************-->

    <!--**********************************
           Support ticket button end
        ***********************************-->


    </div>
    <!--**********************************
        Main wrapper end
    ***********************************-->

    <!--**********************************
        Scripts
    ***********************************-->
    <!-- Required vendors -->
    <script src="./vendor/global/global.min.js"></script>
    <script src="./js/quixnav-init.js"></script>
    <script src="./js/custom.min.js"></script>




    <!-- Required datatable js -->
    <script src="assets/libs/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
    <!-- Buttons examples -->
    <script src="assets/libs/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
    <script src="assets/libs/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js"></script>
    <script src="assets/libs/jszip/jszip.min.js"></script>
    <script src="assets/libs/pdfmake/build/pdfmake.min.js"></script>
    <script src="assets/libs/pdfmake/build/vfs_fonts.js"></script>
    <script src="assets/libs/datatables.net-buttons/js/buttons.html5.min.js"></script>
    <script src="assets/libs/datatables.net-buttons/js/buttons.print.min.js"></script>
    <script src="assets/libs/datatables.net-buttons/js/buttons.colVis.min.js"></script>

    <script src="assets/libs/datatables.net-keytable/js/dataTables.keyTable.min.js"></script>
    <script src="assets/libs/datatables.net-select/js/dataTables.select.min.js"></script>


    <!-- Datatable init js -->
    <script src="assets/js/pages/datatables.init.js"></script>



    <script>
        $(document).ready(function() {
            $('#datatable-buttons2').DataTable({

                dom: 'Bfrtip',
                responsive: true,
                pageLength: 25,
                // lengthMenu: [0, 5, 10, 20, 50, 100, 200, 500],

                buttons: [

                    {
                        extend: 'pdfHtml5',
                        title: 'IP Report <?= date("d.m.Y", strtotime($op_from)) ?> to <?= date("d.m.Y", strtotime($op_to)) ?>',



                        orientation: 'landscape',
                        pageSize: 'LEGAL',
                        footer: true
                    },
                    {
                        extend: 'excelHtml5',
                        title: 'IP Report <?= date("d.m.Y", strtotime($op_from)) ?> to <?= date("d.m.Y", strtotime($op_to)) ?>',
                        footer: true
                    }
                ]

            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $('#datatable-buttons3').DataTable({

                dom: 'Bfrtip',
                responsive: true,
                pageLength: 25,
                // lengthMenu: [0, 5, 10, 20, 50, 100, 200, 500],

                buttons: [

                    {
                        extend: 'pdfHtml5',
                        title: 'Department wise IP Report from <?= date("d.m.Y", strtotime($dept_from)) ?> to <?= date("d.m.Y", strtotime($dept_to)) ?>',
                        orientation: 'landscape',
                        pageSize: 'LEGAL',
                        footer: true
                    }
                ]

            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $('#datatable-buttons4').DataTable({

                dom: 'Bfrtip',
                responsive: true,
                pageLength: 25,
                // lengthMenu: [0, 5, 10, 20, 50, 100, 200, 500],

                buttons: [

                    {
                        extend: 'pdfHtml5',
                        title: 'Center OP',
                        orientation: 'landscape',
                        pageSize: 'LEGAL',
                        footer: true
                    }
                ]

            });
        });
    </script>

    <script>
        if (window.location.href.split("#")[1] != undefined) {
            var collection = document.getElementsByClassName(' show active');
            for (let i = 0; i < collection.length; i++) {
                // collection[i].classList.remove("show"); 
                collection[i].classList.remove("active");
            }
            var collection1 = document.getElementsByClassName(' active');
            for (let i = 0; i < collection1.length; i++) {
                collection1[i].classList.remove("active");
            }
            var id = window.location.href.split("#")[1];
            document.getElementById(id).className += " active";
            //    alert(.length);
            var int = id.split("-");
            var flag = 0;
            var element = '';
            for (let index = 0; index < int.length - 1; index++) {
                if (flag == 1) {
                    element += '-';
                }
                element += int[index];
                flag = 1;

            }
            // alert()
            document.getElementById(element).className += " show active";
        }
    </script>


</body>

</html>
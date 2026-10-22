<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once(__DIR__ . '/../model/session_config.php');

date_default_timezone_set("Asia/Kolkata");
include('model/dashClass.php');
$newOP = new dashboard;

// Get today's date
$today = date('Y-m-d');
$this_month_start = date('Y-m-01');
$this_month_end = date('Y-m-t');

// Today's Collections
$today_collections = $newOP->getDayWiseCollection($today, $today, '', '');
// print_r($today_collections);
$today_total = 0;
$today_cash = 0;
$today_upi = 0;
$today_card = 0;
$today_bills_count = 0;
// $row = mysqli_fetch_assoc($today_collections);
// print_r($today_collections);
while ($row = mysqli_fetch_assoc($today_collections)) {
    $today_total += ($row['cash'] + $row['upi'] + $row['card'] + $row['cheque'] + $row['bank']);
    $today_cash += $row['cash'];
    $today_upi += $row['upi'];
    $today_card += $row['card'];
    $today_bills_count += (int)$row['bills_count'];
}
// echo "$today_total - $today_cash - $today_upi - $today_card";
// exit;
// Month's Collections
$month_collections = $newOP->getDayWiseCollection($this_month_start, $this_month_end, '', '');
$month_total = 0;
$month_cash = 0;
$month_upi = 0;
$month_card = 0;
$month_bank = 0;
$month_cheque = 0;
$month_bills_count = 0;
while ($row = mysqli_fetch_assoc($month_collections)) {
    $month_total += ($row['cash'] + $row['upi'] + $row['card'] + $row['cheque'] + $row['bank']);
    $month_cash += $row['cash'];
    $month_upi += $row['upi'];
    $month_card += $row['card'];
    $month_bank += $row['bank'];
    $month_cheque += $row['cheque'];
    $month_bills_count += (int)$row['bills_count'];
}

// Get patient counts
$tot_opd = $newOP->getTotalopd();
$tot_ipd = $newOP->getTotalipd();
$data = $newOP->getTotalpatient();
$deta = explode("|", $data);
$ipd = $deta[1];
$opd = $deta[0];
$today_patients = $ipd + $opd;

// Outstanding bills count
$outstanding_bills = $newOP->getOutstandingBillsCount();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Dashboard</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="./images/favicon.png">
    <link rel="stylesheet" href="./vendor/owl-carousel/css/owl.carousel.min.css">
    <link rel="stylesheet" href="./vendor/owl-carousel/css/owl.theme.default.min.css">
    <link href="./vendor/jqvmap/css/jqvmap.min.css" rel="stylesheet">
    <link href="./css/style.css" rel="stylesheet">
    <link rel="stylesheet" href="icofont/icofont.min.css">

    <!-- <link href="./vendor/pg-calendar/css/pignose.calendar.min.css" rel="stylesheet"> -->
    <link href="./vendor/chartist/css/chartist.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <!-- Sweet Alert-->
    <link href="css/sweetalert2/sweetalert2.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css" rel="stylesheet"
        type="text/css" />
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>


    <style>
        .badge-light-info {
            background-color: #f8f5ff;
            color: #7239ea;
        }

        table.dataTable tbody td {
            padding: 2px !important;
        }

        .badge-light-danger {
            background-color: #fff5f8;
            color: #f1416c;
        }

        .badge-light-success {
            background-color: #e8fff3;
            color: #50cd89;
        }

        .badge-light-warning {
            background-color: #fbf5eb;
            color: #ffaa16;
        }

        .icon2 {

            width: 60%
        }
    </style>

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

    <div aria-live="polite" id="notification" aria-atomic="true">
        <?php if (isset($_REQUEST['success'])) { ?>

            <div class="alert alert-success solid alert-dismissible fade show">
                <button type="button" class="close h-100" data-dismiss="alert" aria-label="Close"><span><i
                            class="mdi mdi-close"></i></span>
                </button>
                <strong>Success!</strong> OP Patient inserted
            </div>
        <?php   }
        ?>
    </div>

    <!--**********************************
        Main wrapper start
    ***********************************-->
    <div id="main-wrapper">






        <?php include('leftbar.php') ?>


        <!--**********************************
            Content body start
        ***********************************-->
        <div class="content-body">
            <!-- row -->



            <div class="grey-bg container-fluid">
                <section id="minimal-statistics">
                    <div class="row">
                        <div class="col-12 mt-3 mb-1">
                            <h4 class="text-uppercase">Billing & Accounts Dashboard</h4>
                            <p class="text-muted">Real-time billing statistics and collections</p>
                        </div>
                    </div>

                    <!-- Today's Collection Stats -->
                    <div class="row">
                        <div class="col-md-3">
                            <div class="card dash-card">
                                <div class="card-body">
                                    <div class="row ">
                                        <div class="col-md-9">
                                            <h5 class="text-left text-uppercase">Today's Collection</h5>
                                            <h2>₹<?= number_format($today_total, 0) ?></h2>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="icon">
                                                <span><img src="assets/icons/receipt(1).png" class="img-fluid"
                                                        alt=""></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="card dash-card">
                                <div class="card-body">
                                    <div class="row ">
                                        <div class="col-md-9">
                                            <h5 class="text-left text-uppercase">Cash Collection</h5>
                                            <h2>₹<?= number_format($today_cash, 0) ?></h2>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="icon">
                                                <span><img src="assets/icons/cash.png" class="img-fluid" alt=""></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="card dash-card">
                                <div class="card-body">
                                    <div class="row ">
                                        <div class="col-md-9">
                                            <h5 class="text-left text-uppercase">UPI Collection</h5>
                                            <h2>₹<?= number_format($today_upi, 0) ?></h2>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="icon">
                                                <span><img src="assets/icons/phone.avif" class="img-fluid"
                                                        alt=""></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="card dash-card">
                                <div class="card-body">
                                    <div class="row ">
                                        <div class="col-md-9">
                                            <h5 class="text-left text-uppercase">Card Collection</h5>
                                            <h2>₹<?= number_format($today_card, 0) ?></h2>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="icon">
                                                <span><img src="assets/icons/card.png" class="img-fluid"
                                                        alt=""></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Month's Collection & Patient Stats -->
                    <div class="row">
                        <div class="col-md-3">
                            <div class="card dash-card">
                                <div class="card-body">
                                    <div class="row ">
                                        <div class="col-md-9">
                                            <h5 class="text-left text-uppercase">This Month Collection</h5>
                                            <h2>₹<?= number_format($month_total, 0) ?></h2>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="icon">
                                                <span><img src="assets/icons/report(1).png" class="img-fluid"
                                                        alt=""></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>



                        <div class="col-md-3">
                            <div class="card dash-card">
                                <div class="card-body">
                                    <div class="row ">
                                        <div class="col-md-9">
                                            <h5 class="text-left text-uppercase">Outstanding Amount</h5>
                                            <h2>₹<?= number_format($outstanding_bills['amount'], 0) ?></h2>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="icon">
                                                <span><img src="assets/icons/report(1).png" class="img-fluid" alt=""></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>




                        <div class="col-md-3">
                            <div class="card dash-card">
                                <div class="card-body">
                                    <div class="row ">
                                        <div class="col-md-9">
                                            <h5 class="text-left text-uppercase">Outstanding Bills</h5>
                                            <h2><?= $outstanding_bills['count'] ?></h2>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="icon">
                                                <span><img src="assets/icons/report(1).png" class="img-fluid" alt=""></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="card dash-card">
                                <div class="card-body">
                                    <div class="row ">
                                        <div class="col-md-9">
                                            <h5 class="text-left text-uppercase">Bills Today</h5>
                                            <h2><?= $today_bills_count ?></h2>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="icon">
                                                <span><img src="assets/icons/receipt(1).png" class="img-fluid" alt=""></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                    </div>



                    <div class="row">
                        <div class="col-md-6">
                            <div class="card p-3">
                                <div class="card-header bg-light">
                                    <h4 class="text-dark">
                                        Month Wise OPD Revenue Collection of the Year <?php echo date('Y'); ?>
                                    </h4>
                                </div>
                                <div class="card-card">
                                    <div id="opd"></div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card p-3">
                                <div class="card-header bg-light">
                                    <h4 class="text-dark">
                                        Month Wise IPD Revenue Collection of the Year <?php echo date('Y'); ?>
                                    </h4>
                                </div>
                                <div class="card-card">
                                    <div id="ipd"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--                Month Wise IPD Bill Generated-->
                    <!--            </h4>-->
                    <!--    </div>-->
                    <!--    <div class="card-card">-->


                    <!--<div id="ipdbill"></div>-->



                    <!--  </div>-->
                    <!--    </div>-->
                    <!--  </div>-->




                    <div class="row">
                        <div class="col-lg-6">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Today's Collection Summary by Service Type</h4>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered mb-0">
                                            <thead>
                                                <tr class="text-center font-weight-bold">
                                                    <th>Service Type</th>
                                                    <th>Bills Count</th>
                                                    <th>Amount (₹)</th>
                                                </tr>
                                            </thead>
                                            <tbody class="text-center">
                                                <?php
                                                $service_collections = $newOP->getDayWiseCollection($today, $today, '', '');
                                                $service_totals = [];
                                                while ($row = mysqli_fetch_assoc($service_collections)) {
                                                    $total = $row['cash'] + $row['upi'] + $row['card'] + $row['cheque'] + $row['bank'];
                                                    if ($total > 0) {
                                                        $service_type = strtoupper($row['service_type']);
                                                        if ($service_type === 'Pathology') {
                                                            $service_type = 'LAB';
                                                        }
                                                ?>
                                                        <tr>
                                                            <td><?= $service_type ?></td>
                                                            <td><span><?= (int)$row['bills_count'] ?></span></td>
                                                            <td><span class="badge badge-light-success">₹<?= number_format($total, 0) ?></span>
                                                            </td>
                                                        </tr>
                                                <?php }
                                                } ?>
                                                <tr class="bg-light font-weight-bold">
                                                    <td colspan="2">TOTAL</td>
                                                    <td><span class="badge badge-success">₹<?= number_format($today_total, 0) ?></span>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">This Month Collection by Payment Mode</h4>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered mb-0">
                                            <thead>
                                                <tr class="text-center font-weight-bold">
                                                    <th>Payment Mode</th>
                                                    <th>Amount (₹)</th>
                                                    <th>Percentage</th>
                                                </tr>
                                            </thead>
                                            <tbody class="text-center">
                                                <tr>
                                                    <td><span class="badge badge-info">Cash</span></td>
                                                    <td><span>₹<?= number_format($month_cash, 0) ?></span></td>
                                                    <td><span
                                                            class="badge badge-light-info"><?= $month_total > 0 ? number_format(($month_cash / $month_total) * 100, 1) : 0 ?>%</span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><span class="badge badge-primary">UPI</span></td>
                                                    <td><span>₹<?= number_format($month_upi, 0) ?></span></td>
                                                    <td><span
                                                            class="badge badge-light-primary"><?= $month_total > 0 ? number_format(($month_upi / $month_total) * 100, 1) : 0 ?>%</span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><span class="badge badge-warning">Card</span></td>
                                                    <td><span>₹<?= number_format($month_card, 0) ?></span></td>
                                                    <td><span
                                                            class="badge badge-light-warning"><?= $month_total > 0 ? number_format(($month_card / $month_total) * 100, 1) : 0 ?>%</span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><span class="badge badge-secondary">Bank</span></td>
                                                    <td><span>₹<?= number_format($month_bank, 0) ?></span></td>
                                                    <td><span
                                                            class="badge badge-light-secondary"><?= $month_total > 0 ? number_format(($month_bank / $month_total) * 100, 1) : 0 ?>%</span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><span class="badge badge-success">Cheque</span></td>
                                                    <td><span>₹<?= number_format($month_cheque, 0) ?></span></td>
                                                    <td><span
                                                            class="badge badge-light-success"><?= $month_total > 0 ? number_format(($month_cheque / $month_total) * 100, 1) : 0 ?>%</span>
                                                    </td>
                                                </tr>
                                                <tr class="bg-light font-weight-bold">
                                                    <td>TOTAL</td>
                                                    <td colspan="2"><span
                                                            class="badge badge-success">₹<?= number_format($month_total, 0) ?></span>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>



                </section>
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
    <script>
        window.setTimeout(function() {
            $(".alert").fadeTo(500, 0).slideUp(500, function() {
                $(this).remove();
            });
        }, 4000);
    </script>




    <?php $res = $newOP->chartOpdPatient();
    while ($row4 = mysqli_fetch_assoc($res)) {
        $n_arr[$row4['month']] = $row4['count'];
    }
    for ($l = 1; $l <= 12; $l++) {
        if (!isset($n_arr[$l])) {
            $n_arr[$l] = 0;
        }
    }
    ksort($n_arr);
    $n_a["OPD Patients"] =
        array(
            "data" => $n_arr,
        );
    ?>
    <?php $res = $newOP->chartIpdPatient();
    while ($row4 = mysqli_fetch_assoc($res)) {
        $i_arr[$row4['month']] = $row4['count'];
    }
    for ($l = 1; $l <= 12; $l++) {
        if (!isset($i_arr[$l])) {
            $i_arr[$l] = 0;
        }
    }
    ksort($i_arr);
    $i_a["IPD Patients"] =
        array(
            "data" => $i_arr,
        );

    ?>



    <script>
        var options = {
            series: [{
                name: 'OPD Patients',
                data: [<?= implode(",", $n_a['OPD Patients']['data']) ?>]
            }, ],
            chart: {
                height: 350,
                type: 'bar',
            },
            plotOptions: {
                bar: {
                    borderRadius: 10,
                    dataLabels: {
                        position: 'top', // top, center, bottom
                    },
                }
            },
            dataLabels: {
                enabled: true,
                formatter: function(val) {
                    return val;
                },
                offsetY: -20,
                style: {
                    fontSize: '12px',
                    colors: ["#304758"]
                }
            },

            xaxis: {
                categories: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
                position: 'top',
                axisBorder: {
                    show: false
                },
                axisTicks: {
                    show: false
                },
                crosshairs: {
                    fill: {
                        type: 'gradient',
                        gradient: {
                            colorFrom: '#D8E3F0',
                            colorTo: '#BED1E6',
                            stops: [0, 100],
                            opacityFrom: 0.4,
                            opacityTo: 0.5,
                        }
                    }
                },
                tooltip: {
                    enabled: true,
                }
            },
            yaxis: {
                axisBorder: {
                    show: false
                },
                axisTicks: {
                    show: false,
                },
                labels: {
                    show: false,
                    formatter: function(val) {
                        return val;
                    }
                }

            },
            title: {
                text: 'OPD Monthly Revenue',
                floating: true,
                offsetY: 330,
                align: 'center',
                style: {
                    color: '#444'
                }
            }
        };

        var chart = new ApexCharts(document.querySelector("#opd"), options);
        chart.render();



        var options = {
            series: [{
                name: 'IPD Revenue',
                data: [<?= implode(",", $i_a['IPD Patients']['data']) ?>]
            }, ],
            chart: {
                height: 350,
                type: 'bar',
            },
            plotOptions: {
                bar: {
                    borderRadius: 10,
                    dataLabels: {
                        position: 'top', // top, center, bottom
                    },
                }
            },
            dataLabels: {
                enabled: true,
                formatter: function(val) {
                    return val;
                },
                offsetY: -20,
                style: {
                    fontSize: '12px',
                    colors: ["#304758"]
                }
            },

            xaxis: {
                categories: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
                position: 'top',
                axisBorder: {
                    show: false
                },
                axisTicks: {
                    show: false
                },
                crosshairs: {
                    fill: {
                        type: 'gradient',
                        color: '20BF55',
                        gradient: {

                            colorFrom: '#20BF55',
                            colorTo: '#01BAEF',
                            stops: [0, 100],
                            opacityFrom: 0.4,
                            opacityTo: 0.5,
                        }
                    }
                },
                tooltip: {
                    enabled: true,
                }
            },
            yaxis: {
                axisBorder: {
                    show: false
                },
                axisTicks: {
                    show: false,
                },
                labels: {
                    show: false,
                    formatter: function(val) {
                        return val;
                    }
                }

            },
            title: {
                text: 'IPD Monthly Revenue',
                floating: true,
                offsetY: 330,
                align: 'center',
                style: {
                    color: '#444'
                }
            }

        };

        var chart = new ApexCharts(document.querySelector("#ipd"), options);
        chart.render();
    </script>

    <!-- Sweet Alerts js -->
    <script src="css/sweetalert2/sweetalert2.min.js"></script>

    <!-- Sweet alert init js-->
    <script src="js/sweet-alerts.init.js"></script>

    <!-- <script src="./vendor/chartist/js/chartist.min.js"></script> -->

    <script src="./vendor/moment/moment.min.js"></script>
    <!-- <script src="./vendor/pg-calendar/js/pignose.calendar.min.js"></script> -->


    <!-- <script src="./js/dashboard/dashboard-2.js"></script> -->

    <!--<script src="charts.js"></script>-->


</body>

</html>
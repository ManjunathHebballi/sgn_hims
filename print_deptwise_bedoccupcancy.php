<?php

date_default_timezone_set("Asia/Kolkata");

require_once(__DIR__ . '/model/session_config.php');
$date = date("Y-m-d");
$beddept_from = $_REQUEST['from'];
$beddept_to = $_REQUEST['to'];
include('model/reportClass.php');
$newReport = new report;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Deptwise Report</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="./images/favicon.png">
    <link rel="stylesheet" href="./vendor/owl-carousel/css/owl.carousel.min.css">
    <link rel="stylesheet" href="./vendor/owl-carousel/css/owl.theme.default.min.css">
    <link href="./vendor/jqvmap/css/jqvmap.min.css" rel="stylesheet">
    <link href="./css/style.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">

    <!-- Datatable -->
    <link href="./vendor/datatables/css/jquery.dataTables.min.css" rel="stylesheet">

    <!-- DataTables -->
    <link href="assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/libs/datatables.net-select-bs4/css//select.bootstrap4.min.css" rel="stylesheet" type="text/css" />

    <!-- Responsive datatable examples -->
    <link href="assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500&display=swap" rel="stylesheet">

</head>

<style>
    td {

        font-size: 15px
    }

    @media print {

        .dark {
            background-color: black;

            -webkit-print-color-adjust: exact;
        }
    }
</style>

<body style=" font-family: 'Poppins', sans-serif;" onload="window.print()">

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
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <!-- <div class="col-sm-1">
                            <img src="assets/logo.png" class="" alt="" width="100">

                        </div>
                        <div class="col-sm-10  align-items-center">
                        <h5 class="text-center text-black"><?= $_SESSION['college_society'] ?></h5>
                        <h2 class="text-center text-black"><?= $_SESSION['college_name'] ?></h2>
                        <h5 class="text-center text-black"><?= $_SESSION['college_location'] ?></h5>
                        <h5 class="text-center text-black"><?= $_SESSION['college_phone'] ?></h5>

                        </div> -->
                        <br>
                        <br>
                        <br>
                        <br>
                        <br>
                        <br>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <h5 class="ml-4">Dept wise Bed Occupancy<span class="text-danger"> <?php if (isset($_REQUEST['from']) && isset($_REQUEST['to'])) { ?>
                                                    Between <?= date("d.m.Y", strtotime($_REQUEST['from'])) ?> and <?= date("d.m.Y", strtotime($_REQUEST['to'])) ?>
                                                <?php } ?></span></h5>
                                    </div>
                                    <hr>

                                    <div class="table-responsive">
                                        <table id="example3" class="table table-hoverde display text-center">
                                            <thead class="thead-dark">
                                                <tr>
                                                    <th>Sl NO</th>
                                                    <th>Deparment Name</th>
                                                    <th>Remaining</th>
                                                    <th>Admisstion</th>
                                                    <th>Discharge</th>
                                                    <th>Total</th>






                                                </tr>
                                            </thead>
                                            <tbody class="text-center text-black">

                                                <?php $sn = 1;
                                                $diff = date_diff(date_create($beddept_from), date_create($beddept_to));
                                                $interval = $diff->format("%a");
                                                if ($interval == 0) {
                                                    $interval = 1;
                                                }
                                                // echo "<script>alert('$interval')</script>";
                                                $totramin = $occupied = $discharge = $bed = 0;
                                                $totbed = $newReport->getTotalBed($beddept_from);
                                                $bedDept = $newReport->getDeptBed($beddept_from, $beddept_to);
                                                while ($row = mysqli_fetch_assoc($bedDept)) {
                                                    $rema = $row['bed'] - $row['occupied'] + $row['discharge'];
                                                ?>
                                                    <tr>
                                                        <td><?= $sn ?></td>
                                                        <td><?= $row['dept_name'] ?></td>
                                                        <td><?= $rema ?></td>
                                                        <td><?= $row['occupied'] ?></td>
                                                        <td><?= $row['discharge'] ?></td>
                                                        <td><?= $row['bed'] ?></td>
                                                    </tr>
                                                <?php $sn++;
                                                    $totramin += $rema;
                                                    $occupied += $row['occupied'];
                                                    $discharge += $row['discharge'];
                                                    $bed += $row['bed'];
                                                } ?>
                                                <tr>
                                                    <td></td>
                                                    <td class="font-weight-bold">Total</td>
                                                    <td class="font-weight-bold"><?= $totramin ?></td>
                                                    <td class="font-weight-bold"><?= $occupied ?></td>
                                                    <td class="font-weight-bold"><?= $discharge ?></td>
                                                    <td class="font-weight-bold"><?= $bed . " ( " . round((($bed * 100) / $totbed) / $interval, 2) . "% )" ?></td>
                                                </tr>



                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <script src="./vendor/global/global.min.js"></script>
                    <script src="./js/quixnav-init.js"></script>
                    <script src="./js/custom.min.js"></script>


                    <!-- Datatable -->



</body>

</html>
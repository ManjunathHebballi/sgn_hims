<?php

/**
 * ABHA Registration Report
 * -----------------------------------------------------------------------------
 * How many patients registered in a period carry an ABHA, split OPD vs IPD.
 *
 * Four views, one shared date range:
 *   Summary   - headline counts + department-wise and day-wise coverage
 *   OPD       - one row per OPD registration, ABHA shown or "Not Registered"
 *   IPD       - one row per admission, same treatment
 *   Day Wise  - registrations per day
 *   Beds      - bed occupancy for the window, ward-wise and per day, with the
 *               share of occupied bed-days held by ABHA patients
 *
 * The IPD numbers fall back to the ABHA on the OPD registration the admission
 * came through, because ipregistration.php never captures one itself. See the
 * note above report::abhaIPExpr() in model/reportClass.php.
 */
require_once(__DIR__ . '/model/session_config.php');
date_default_timezone_set("Asia/Kolkata");

include('model/reportClass.php');
$newReport = new report;

// ── Filters ─────────────────────────────────────────────────────────────────
// Default window is the current month, which is what the ABDM monthly return
// asks for. Every view shares the same range so the tabs stay comparable.
$ab_from = isset($_REQUEST['ab_from']) && $_REQUEST['ab_from'] !== ''
    ? $_REQUEST['ab_from'] : date('Y-m-01');
$ab_to   = isset($_REQUEST['ab_to']) && $_REQUEST['ab_to'] !== ''
    ? $_REQUEST['ab_to']   : date('Y-m-d');
$ab_dept   = isset($_REQUEST['ab_dept'])   ? (int) $_REQUEST['ab_dept'] : 0;
$ab_status = isset($_REQUEST['ab_status']) ? $_REQUEST['ab_status']     : '';
if (!in_array($ab_status, array('', 'with', 'without'), true)) {
    $ab_status = '';
}

$op   = $newReport->abhaOPSummary($ab_from, $ab_to, $ab_dept);
$ip   = $newReport->abhaIPSummary($ab_from, $ab_to, $ab_dept);

$totalPatients = $op['total'] + $ip['total'];
$totalAbha     = $op['with_abha'] + $ip['with_abha'];
$totalPercent  = $totalPatients > 0 ? round(($totalAbha * 100) / $totalPatients, 2) : 0;

// Bed occupancy is a census of everyone in a bed during the window, not just
// admissions made in it, so the ABHA Status filter does not apply to it.
$beds = $newReport->abhaBedOccupancy($ab_from, $ab_to, $ab_dept);

$printQS = 'ab_from=' . urlencode($ab_from) . '&ab_to=' . urlencode($ab_to)
    . '&ab_dept=' . $ab_dept . '&ab_status=' . urlencode($ab_status);

/** Escape for HTML output. */
if (!function_exists("ab_e")) {
    /** Escape for HTML output. */
    function ab_e($v)
    {
        return htmlspecialchars((string) $v, ENT_QUOTES, 'UTF-8');
    }
}

/** Bar showing what share of a group holds an ABHA. */
function ab_bar($with, $total)
{
    $pct = $total > 0 ? round(($with * 100) / $total, 2) : 0;
    $cls = $pct >= 75 ? 'bg-success' : ($pct >= 40 ? 'bg-warning' : 'bg-danger');
    echo '<div class="progress" style="height:14px;">'
        . '<div class="progress-bar ' . $cls . '" role="progressbar" style="width:' . $pct . '%"'
        . ' aria-valuenow="' . $pct . '" aria-valuemin="0" aria-valuemax="100"></div></div>'
        . '<small class="text-muted">' . $pct . '%</small>';
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>ABHA Registration Report</title>
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

    <style>
        .abha-stat h2 {
            margin-bottom: 0;
            font-weight: 600;
        }

        .abha-stat p {
            margin-bottom: 0;
        }

        .abha-no {
            color: #b71c1c;
        }

        .abha-yes {
            color: #1b5e20;
            font-weight: 500;
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

    <!--**********************************
        Main wrapper start
    ***********************************-->
    <div id="main-wrapper">

        <?php include('leftbar.php') ?>

        <!--**********************************
            Content body start
        ***********************************-->
        <div class="content-body">
            <div class="container-fluid">

                <div class="row mb-3 ml-1">
                    <h4 class="text-uppercase">ABHA Registration Report</h4>
                </div>

                <!-- ── Shared filter ─────────────────────────────────────── -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <h4>Select Criteria</h4><br>
                                <form action="abha_reports.php" method="GET">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>From Date</label>
                                                <input type="date" class="form-control" name="ab_from" required value="<?= ab_e($ab_from) ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>To Date</label>
                                                <input type="date" class="form-control" name="ab_to" required value="<?= ab_e($ab_to) ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Department</label>
                                                <select name="ab_dept" class="form-control">
                                                    <option value="0">All Departments</option>
                                                    <?php
                                                    $depts = $newReport->getDept();
                                                    while ($d = mysqli_fetch_assoc($depts)) { ?>
                                                        <option value="<?= (int) $d['id'] ?>" <?= $ab_dept == $d['id'] ? 'selected' : '' ?>>
                                                            <?= ab_e($d['dept_name']) ?>
                                                        </option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>ABHA Status</label>
                                                <select name="ab_status" class="form-control">
                                                    <option value="" <?= $ab_status === ''        ? 'selected' : '' ?>>All Patients</option>
                                                    <option value="with" <?= $ab_status === 'with'    ? 'selected' : '' ?>>With ABHA</option>
                                                    <option value="without" <?= $ab_status === 'without' ? 'selected' : '' ?>>Without ABHA</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <a href="print_abha_report.php?<?= $printQS ?>" target="_blank"
                                                class="btn btn-danger btn-rounded float-right ml-2">
                                                <i class="icofont-print"></i> Print
                                            </a>
                                            <button type="submit" class="btn btn-success btn-rounded float-right" name="submit">
                                                <i class="bi bi-search"></i> Search
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ── Headline counts ───────────────────────────────────── -->
                <div class="row">
                    <div class="col-xl-3 col-md-6">
                        <div class="card">
                            <div class="card-body text-center abha-stat">
                                <p class="text-muted mb-1">Total Patients Registered</p>
                                <h2><?= $totalPatients ?></h2>
                                <p class="text-muted"><small>OPD <?= $op['total'] ?> &nbsp;|&nbsp; IPD <?= $ip['total'] ?></small></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="card">
                            <div class="card-body text-center abha-stat">
                                <p class="text-muted mb-1">Registered with ABHA</p>
                                <h2 class="text-success"><?= $totalAbha ?></h2>
                                <p class="text-muted"><small>OPD <?= $op['with_abha'] ?> &nbsp;|&nbsp; IPD <?= $ip['with_abha'] ?></small></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="card">
                            <div class="card-body text-center abha-stat">
                                <p class="text-muted mb-1">Without ABHA</p>
                                <h2 class="text-danger"><?= $totalPatients - $totalAbha ?></h2>
                                <p class="text-muted"><small>OPD <?= $op['without_abha'] ?> &nbsp;|&nbsp; IPD <?= $ip['without_abha'] ?></small></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="card">
                            <div class="card-body text-center abha-stat">
                                <p class="text-muted mb-1">ABHA Coverage</p>
                                <h2 class="text-primary"><?= $totalPercent ?>%</h2>
                                <p class="text-muted"><small>Care contexts linked: <?= $op['care_linked'] + $ip['care_linked'] ?></small></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ── Bed occupancy headline ────────────────────────────── -->
                <div class="row">
                    <div class="col-xl-3 col-md-6">
                        <div class="card">
                            <div class="card-body text-center abha-stat">
                                <p class="text-muted mb-1">Total Beds</p>
                                <h2><?= $beds['beds'] ?></h2>
                                <p class="text-muted"><small><?= $beds['days'] ?> day(s) in period</small></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="card">
                            <div class="card-body text-center abha-stat">
                                <p class="text-muted mb-1">Bed Occupancy</p>
                                <h2 class="text-primary"><?= $beds['occupancy'] ?>%</h2>
                                <p class="text-muted"><small>Avg. occupied beds / day: <?= $beds['avg_census'] ?></small></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="card">
                            <div class="card-body text-center abha-stat">
                                <p class="text-muted mb-1">Occupied Bed-Days</p>
                                <h2><?= $beds['bed_days'] ?></h2>
                                <p class="text-muted"><small>With ABHA <?= $beds['bed_days_abha'] ?>
                                        (<?= $beds['bed_days'] > 0 ? round(($beds['bed_days_abha'] * 100) / $beds['bed_days'], 2) : 0 ?>%)</small></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="card">
                            <div class="card-body text-center abha-stat">
                                <p class="text-muted mb-1">Occupied on <?= date('d.m.Y', strtotime($ab_to)) ?></p>
                                <h2 class="text-success"><?= $beds['census_to'] ?></h2>
                                <p class="text-muted"><small>With ABHA <?= $beds['census_to_abha'] ?> &nbsp;|&nbsp; Without <?= $beds['census_to'] - $beds['census_to_abha'] ?></small></p>
                            </div>
                        </div>
                    </div>
                </div>

                <ul class="nav nav-pills mb-3 justify-content-around" id="pills-tab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="pills-summary-tab" data-toggle="pill" href="#pills-summary"
                            role="tab" aria-controls="pills-summary" aria-selected="true">Summary</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="pills-opd-tab" data-toggle="pill" href="#pills-opd"
                            role="tab" aria-controls="pills-opd" aria-selected="false">OPD Patient List</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="pills-ipd-tab" data-toggle="pill" href="#pills-ipd"
                            role="tab" aria-controls="pills-ipd" aria-selected="false">IPD Patient List</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="pills-day-tab" data-toggle="pill" href="#pills-day"
                            role="tab" aria-controls="pills-day" aria-selected="false">Day Wise</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="pills-bed-tab" data-toggle="pill" href="#pills-bed"
                            role="tab" aria-controls="pills-bed" aria-selected="false">Bed Occupancy</a>
                    </li>
                </ul>

                <div class="tab-content" id="pills-tabContent">

                    <!-- ── Summary tab ───────────────────────────────────── -->
                    <div class="tab-pane fade show active" id="pills-summary" role="tabpanel" aria-labelledby="pills-summary-tab"><br>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="ml-2">ABHA Registration Summary
                                            <span class="text-danger">
                                                Between <?= date('d.m.Y', strtotime($ab_from)) ?> and <?= date('d.m.Y', strtotime($ab_to)) ?>
                                            </span>
                                        </h5>
                                        <hr>
                                        <div class="table-responsive">
                                            <table class="table table-bordered text-center">
                                                <thead class="thead-dark">
                                                    <tr>
                                                        <th>Patient Type</th>
                                                        <th>Total Registered</th>
                                                        <th>With ABHA</th>
                                                        <th>Without ABHA</th>
                                                        <th>Unique ABHA</th>
                                                        <th>Care Context Linked</th>
                                                        <th style="min-width:140px;">ABHA Coverage</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td><b>OPD</b></td>
                                                        <td><?= $op['total'] ?></td>
                                                        <td class="abha-yes"><?= $op['with_abha'] ?></td>
                                                        <td class="abha-no"><?= $op['without_abha'] ?></td>
                                                        <td><?= $op['unique_abha'] ?></td>
                                                        <td><?= $op['care_linked'] ?></td>
                                                        <td><?php ab_bar($op['with_abha'], $op['total']); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><b>IPD</b></td>
                                                        <td><?= $ip['total'] ?></td>
                                                        <td class="abha-yes"><?= $ip['with_abha'] ?></td>
                                                        <td class="abha-no"><?= $ip['without_abha'] ?></td>
                                                        <td><?= $ip['unique_abha'] ?></td>
                                                        <td><?= $ip['care_linked'] ?></td>
                                                        <td><?php ab_bar($ip['with_abha'], $ip['total']); ?></td>
                                                    </tr>
                                                    <tr class="font-weight-bold bg-light">
                                                        <td>TOTAL</td>
                                                        <td><?= $totalPatients ?></td>
                                                        <td class="abha-yes"><?= $totalAbha ?></td>
                                                        <td class="abha-no"><?= $totalPatients - $totalAbha ?></td>
                                                        <td>&mdash;</td>
                                                        <td><?= $op['care_linked'] + $ip['care_linked'] ?></td>
                                                        <td><?php ab_bar($totalAbha, $totalPatients); ?></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <p class="text-muted mb-0"><small>
                                                "Unique ABHA" counts distinct ABHA numbers, so a patient who visited several
                                                times in the period is counted once. An IPD admission is credited with the
                                                ABHA captured on the OPD registration it was admitted through.
                                            </small></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="ml-2">Department Wise &mdash; OPD</h5>
                                        <hr>
                                        <div class="table-responsive">
                                            <table class="table table-bordered text-center">
                                                <thead class="thead-dark">
                                                    <tr>
                                                        <th>Department</th>
                                                        <th>Total</th>
                                                        <th>With ABHA</th>
                                                        <th>Without</th>
                                                        <th>%</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $res = $newReport->abhaDeptWise($ab_from, $ab_to, 'OP');
                                                    $any = false;
                                                    while ($r = $res->fetch_assoc()) {
                                                        $any = true;
                                                        $t = (int) $r['total'];
                                                        $w = (int) $r['with_abha']; ?>
                                                        <tr>
                                                            <td class="text-left"><?= ab_e($r['dept_name']) ?></td>
                                                            <td><?= $t ?></td>
                                                            <td class="abha-yes"><?= $w ?></td>
                                                            <td class="abha-no"><?= $t - $w ?></td>
                                                            <td><?= $t > 0 ? round(($w * 100) / $t, 2) : 0 ?>%</td>
                                                        </tr>
                                                    <?php }
                                                    if (!$any) { ?>
                                                        <tr>
                                                            <td colspan="5" class="text-muted">No OPD registrations in this period.</td>
                                                        </tr>
                                                    <?php } ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="ml-2">Department Wise &mdash; IPD</h5>
                                        <hr>
                                        <div class="table-responsive">
                                            <table class="table table-bordered text-center">
                                                <thead class="thead-dark">
                                                    <tr>
                                                        <th>Department</th>
                                                        <th>Total</th>
                                                        <th>With ABHA</th>
                                                        <th>Without</th>
                                                        <th>%</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $res = $newReport->abhaDeptWise($ab_from, $ab_to, 'IP');
                                                    $any = false;
                                                    while ($r = $res->fetch_assoc()) {
                                                        $any = true;
                                                        $t = (int) $r['total'];
                                                        $w = (int) $r['with_abha']; ?>
                                                        <tr>
                                                            <td class="text-left"><?= ab_e($r['dept_name']) ?></td>
                                                            <td><?= $t ?></td>
                                                            <td class="abha-yes"><?= $w ?></td>
                                                            <td class="abha-no"><?= $t - $w ?></td>
                                                            <td><?= $t > 0 ? round(($w * 100) / $t, 2) : 0 ?>%</td>
                                                        </tr>
                                                    <?php }
                                                    if (!$any) { ?>
                                                        <tr>
                                                            <td colspan="5" class="text-muted">No IPD admissions in this period.</td>
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

                    <!-- ── OPD list ──────────────────────────────────────── -->
                    <div class="tab-pane fade" id="pills-opd" role="tabpanel" aria-labelledby="pills-opd-tab"><br>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="ml-2">OPD Patients
                                            <span class="text-danger">
                                                Between <?= date('d.m.Y', strtotime($ab_from)) ?> and <?= date('d.m.Y', strtotime($ab_to)) ?>
                                            </span>
                                        </h5>
                                        <hr>
                                        <div class="table-responsive">
                                            <table id="abha-opd-table" class="table table-bordered text-wrap text-center">
                                                <thead class="thead-dark">
                                                    <tr>
                                                        <th>Sl. No.</th>
                                                        <th>UHID</th>
                                                        <th>OP No.</th>
                                                        <th>Date</th>
                                                        <th>Patient Name</th>
                                                        <th>Age / Gender</th>
                                                        <th>Mobile</th>
                                                        <th>Department</th>
                                                        <th>Patient Type</th>
                                                        <th>ABHA Number</th>
                                                        <th>ABHA Address</th>
                                                        <th>Care Linked</th>
                                                        <th>Created By</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td colspan="13" class="text-muted">Loading&hellip;</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ── IPD list ──────────────────────────────────────── -->
                    <div class="tab-pane fade" id="pills-ipd" role="tabpanel" aria-labelledby="pills-ipd-tab"><br>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="ml-2">IPD Patients
                                            <span class="text-danger">
                                                Between <?= date('d.m.Y', strtotime($ab_from)) ?> and <?= date('d.m.Y', strtotime($ab_to)) ?>
                                            </span>
                                        </h5>
                                        <hr>
                                        <div class="table-responsive">
                                            <table id="abha-ipd-table" class="table table-bordered text-wrap text-center">
                                                <thead class="thead-dark">
                                                    <tr>
                                                        <th>Sl. No.</th>
                                                        <th>UHID</th>
                                                        <th>IP No.</th>
                                                        <th>Admission Date</th>
                                                        <th>Patient Name</th>
                                                        <th>Age / Gender</th>
                                                        <th>Mobile</th>
                                                        <th>Department</th>
                                                        <th>Patient Type</th>
                                                        <th>Ward</th>
                                                        <th>ABHA Number</th>
                                                        <th>Care Linked</th>
                                                        <th>Created By</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td colspan="13" class="text-muted">Loading&hellip;</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ── Day wise ──────────────────────────────────────── -->
                    <div class="tab-pane fade" id="pills-day" role="tabpanel" aria-labelledby="pills-day-tab"><br>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="ml-2">Day Wise ABHA Registration
                                            <span class="text-danger">
                                                Between <?= date('d.m.Y', strtotime($ab_from)) ?> and <?= date('d.m.Y', strtotime($ab_to)) ?>
                                            </span>
                                        </h5>
                                        <hr>
                                        <div class="table-responsive">
                                            <table id="abha-day-table" class="table table-bordered text-center">
                                                <thead class="thead-dark">
                                                    <tr>
                                                        <th>Date</th>
                                                        <th>OPD Total</th>
                                                        <th>OPD with ABHA</th>
                                                        <th>IPD Total</th>
                                                        <th>IPD with ABHA</th>
                                                        <th>Day Total</th>
                                                        <th>Total with ABHA</th>
                                                        <th>%</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td colspan="8" class="text-muted">Loading&hellip;</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ── Bed occupancy ─────────────────────────────────── -->
                    <div class="tab-pane fade" id="pills-bed" role="tabpanel" aria-labelledby="pills-bed-tab"><br>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="ml-2">Ward Wise Bed Occupancy
                                            <span class="text-danger">
                                                Between <?= date('d.m.Y', strtotime($ab_from)) ?> and <?= date('d.m.Y', strtotime($ab_to)) ?>
                                            </span>
                                        </h5>
                                        <hr>
                                        <div class="table-responsive">
                                            <table class="table table-bordered text-center">
                                                <thead class="thead-dark">
                                                    <tr>
                                                        <th>Ward</th>
                                                        <th>Total Beds</th>
                                                        <th>Occupied Bed-Days</th>
                                                        <th>Bed-Days with ABHA</th>
                                                        <th>Avg. Occupied / Day</th>
                                                        <th style="min-width:140px;">Bed Occupancy</th>
                                                        <th>Occupied on <?= date('d.m.Y', strtotime($ab_to)) ?></th>
                                                        <th>of which with ABHA</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($beds['wards'] as $ward => $w) { ?>
                                                        <tr>
                                                            <td class="text-left"><?= ab_e($ward) ?></td>
                                                            <td><?= $w['beds'] ?></td>
                                                            <td><?= $w['bed_days'] ?></td>
                                                            <td class="abha-yes"><?= $w['bed_days_abha'] ?></td>
                                                            <td><?= $w['avg_census'] ?></td>
                                                            <td><?php ab_bar($w['bed_days'], $w['beds'] * $beds['days']); ?></td>
                                                            <td><?= $w['census_to'] ?></td>
                                                            <td class="abha-yes"><?= $w['census_to_abha'] ?></td>
                                                        </tr>
                                                    <?php }
                                                    if (empty($beds['wards'])) { ?>
                                                        <tr>
                                                            <td colspan="8" class="text-muted">No beds configured for the selected department.</td>
                                                        </tr>
                                                    <?php } ?>
                                                    <tr class="font-weight-bold bg-light">
                                                        <td>TOTAL</td>
                                                        <td><?= $beds['beds'] ?></td>
                                                        <td><?= $beds['bed_days'] ?></td>
                                                        <td class="abha-yes"><?= $beds['bed_days_abha'] ?></td>
                                                        <td><?= $beds['avg_census'] ?></td>
                                                        <td><?php ab_bar($beds['bed_days'], $beds['beds'] * $beds['days']); ?></td>
                                                        <td><?= $beds['census_to'] ?></td>
                                                        <td class="abha-yes"><?= $beds['census_to_abha'] ?></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <p class="text-muted mb-0"><small>
                                                A bed-day is one patient in a bed at the end of a day: the admission day counts,
                                                the discharge day does not (same rule as the Bed Occupancy Report).
                                                Occupancy % = occupied bed-days &divide; (beds &times; days in period).
                                                Every patient in a bed during the period is counted, including those admitted
                                                earlier, so the ABHA Status filter does not apply here.
                                            </small></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="ml-2">Day Wise Bed Occupancy</h5>
                                        <hr>
                                        <div class="table-responsive">
                                            <table id="abha-bed-table" class="table table-bordered text-center">
                                                <thead class="thead-dark">
                                                    <tr>
                                                        <th>Date</th>
                                                        <th>Total Beds</th>
                                                        <th>Occupied</th>
                                                        <th>Vacant</th>
                                                        <th>Occupancy %</th>
                                                        <th>Occupied with ABHA</th>
                                                        <th>Occupied without ABHA</th>
                                                        <th>ABHA %</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($beds['daily'] as $d) {
                                                        $o = $d['occupied'];
                                                        $a = $d['with_abha']; ?>
                                                        <tr>
                                                            <td><?= date('d.m.Y', strtotime($d['date'])) ?></td>
                                                            <td><?= $beds['beds'] ?></td>
                                                            <td><?= $o ?></td>
                                                            <td><?= max($beds['beds'] - $o, 0) ?></td>
                                                            <td><?= $beds['beds'] > 0 ? round(($o * 100) / $beds['beds'], 2) : 0 ?>%</td>
                                                            <td class="abha-yes"><?= $a ?></td>
                                                            <td class="abha-no"><?= $o - $a ?></td>
                                                            <td><?= $o > 0 ? round(($a * 100) / $o, 2) : 0 ?>%</td>
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
            </div>
        </div>
        <!--**********************************
            Content body end
        ***********************************-->

        <?php include('footer.php') ?>

    </div>
    <!--**********************************
        Main wrapper end
    ***********************************-->

    <!--**********************************
        Scripts
    ***********************************-->
    <script src="./vendor/global/global.min.js"></script>
    <script src="./js/quixnav-init.js"></script>
    <script src="./js/custom.min.js"></script>

    <!-- DataTables -->
    <script src="assets/libs/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="assets/libs/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
    <script src="assets/libs/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js"></script>
    <script src="assets/libs/jszip/jszip.min.js"></script>
    <script src="assets/libs/pdfmake/build/pdfmake.min.js"></script>
    <script src="assets/libs/pdfmake/build/vfs_fonts.js"></script>
    <script src="assets/libs/datatables.net-buttons/js/buttons.html5.min.js"></script>
    <script src="assets/libs/datatables.net-buttons/js/buttons.print.min.js"></script>
    <script src="assets/libs/datatables.net-buttons/js/buttons.colVis.min.js"></script>

    <script>
        // The detail tabs pull their rows from abha_report_data.php the first
        // time they are opened. Rendering all three inline made a one-month
        // report a multi-megabyte page that froze the browser while DataTables
        // parsed it; the summary is all a page load needs to carry.
        $(document).ready(function() {
            var period = '<?= date("d.m.Y", strtotime($ab_from)) ?> to <?= date("d.m.Y", strtotime($ab_to)) ?>';
            var query = <?= json_encode(array("ab_from" => $ab_from, "ab_to" => $ab_to, "ab_dept" => $ab_dept, "ab_status" => $ab_status)) ?>;

            var sections = {
                'pills-opd-tab': {
                    section: 'opd',
                    table: '#abha-opd-table',
                    title: 'ABHA Report - OPD',
                    cols: 13
                },
                'pills-ipd-tab': {
                    section: 'ipd',
                    table: '#abha-ipd-table',
                    title: 'ABHA Report - IPD',
                    cols: 13
                },
                'pills-day-tab': {
                    section: 'day',
                    table: '#abha-day-table',
                    title: 'ABHA Report - Day Wise',
                    cols: 8
                }
            };
            var loaded = {};

            function load(tabId) {
                var cfg = sections[tabId];
                if (!cfg || loaded[tabId]) {
                    return;
                }
                loaded[tabId] = true;

                $.get('abha_report_data.php', $.extend({
                        section: cfg.section
                    }, query))
                    .done(function(html) {
                        $(cfg.table + ' tbody').html(html);
                        $(cfg.table).DataTable({
                            dom: 'Bfrtip',
                            responsive: true,
                            pageLength: 25,
                            deferRender: true,
                            buttons: [{
                                    extend: 'excelHtml5',
                                    title: cfg.title + ' ' + period
                                },
                                {
                                    extend: 'pdfHtml5',
                                    title: cfg.title + ' ' + period,
                                    orientation: 'landscape',
                                    pageSize: 'LEGAL'
                                },
                                {
                                    extend: 'print',
                                    title: cfg.title + ' ' + period
                                }
                            ]
                        });
                    })
                    .fail(function() {
                        loaded[tabId] = false;
                        $(cfg.table + ' tbody').html(
                            '<tr><td colspan="' + cfg.cols + '" class="text-danger">' +
                            'Could not load this list. Please try again.</td></tr>'
                        );
                    });
            }

            // Bed occupancy is rendered with the page (one row per day, so it
            // stays small); it only needs DataTables once it is visible.
            var bedTable = null;

            function initBeds() {
                if (bedTable) {
                    return;
                }
                bedTable = $('#abha-bed-table').DataTable({
                    dom: 'Bfrtip',
                    pageLength: 31,
                    ordering: false,
                    buttons: [{
                            extend: 'excelHtml5',
                            title: 'Bed Occupancy ' + period
                        },
                        {
                            extend: 'pdfHtml5',
                            title: 'Bed Occupancy ' + period,
                            orientation: 'landscape'
                        },
                        {
                            extend: 'print',
                            title: 'Bed Occupancy ' + period
                        }
                    ]
                });
            }

            $('a[data-toggle="pill"]').on('shown.bs.tab', function(e) {
                if (e.target.id === 'pills-bed-tab') {
                    initBeds();
                }
                load(e.target.id);
            });

            // Honour a #pills-…-tab already in the URL on arrival.
            var active = $('a[data-toggle="pill"].active').attr('id');
            if (active === 'pills-bed-tab') {
                initBeds();
            }
            if (active) {
                load(active);
            }
        });
    </script>

</body>

</html>
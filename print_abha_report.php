<?php
/**
 * Printable ABHA Registration Report — opened from abha_reports.php.
 * Takes the same ab_from / ab_to / ab_dept / ab_status filters on the query
 * string and prints the summary followed by the OPD and IPD patient lists.
 */
require_once(__DIR__ . '/model/session_config.php');
date_default_timezone_set("Asia/Kolkata");

include('model/reportClass.php');
$newReport = new report;

$ab_from = isset($_REQUEST['ab_from']) && $_REQUEST['ab_from'] !== ''
    ? $_REQUEST['ab_from'] : date('Y-m-01');
$ab_to   = isset($_REQUEST['ab_to']) && $_REQUEST['ab_to'] !== ''
    ? $_REQUEST['ab_to']   : date('Y-m-d');
$ab_dept   = isset($_REQUEST['ab_dept'])   ? (int) $_REQUEST['ab_dept'] : 0;
$ab_status = isset($_REQUEST['ab_status']) ? $_REQUEST['ab_status']     : '';
if (!in_array($ab_status, array('', 'with', 'without'), true)) {
    $ab_status = '';
}

$op = $newReport->abhaOPSummary($ab_from, $ab_to, $ab_dept);
$ip = $newReport->abhaIPSummary($ab_from, $ab_to, $ab_dept);

$totalPatients = $op['total'] + $ip['total'];
$totalAbha     = $op['with_abha'] + $ip['with_abha'];
$totalPercent  = $totalPatients > 0 ? round(($totalAbha * 100) / $totalPatients, 2) : 0;

$deptName = 'All Departments';
if ($ab_dept > 0) {
    $depts = $newReport->getDept();
    while ($d = mysqli_fetch_assoc($depts)) {
        if ((int) $d['id'] === $ab_dept) { $deptName = $d['dept_name']; break; }
    }
}
$statusLabel = $ab_status === 'with' ? 'With ABHA only'
             : ($ab_status === 'without' ? 'Without ABHA only' : 'All Patients');

if (!function_exists("ab_e")) {
    /** Escape for HTML output. */
    function ab_e($v) { return htmlspecialchars((string) $v, ENT_QUOTES, 'UTF-8'); }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Print ABHA Registration Report</title>
    <link rel="icon" type="image/png" sizes="16x16" href="./images/favicon.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="stylesheet" href="print_style.css">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500&display=swap" rel="stylesheet">
</head>

<style>
    td, th { font-size: 10px; }
    h6.section { margin-top: 18px; }
    .page-break { page-break-before: always; }

    @media print {
        .thead-dark th {
            background-color: #343a40 !important;
            color: #fff !important;
            -webkit-print-color-adjust: exact;
        }
    }
</style>

<body style="font-family: 'Poppins', sans-serif;" onload="window.print()">

    <div id="main-wrapper">
        <div class="container">

            <div class="row">
                <div class="col-md-1 mr-3">
                    <img src="assets/logo.png" alt="">
                </div>
                <div class="col-md-10 align-items-center">
                    <h2 class="text-center text-black"><?= ab_e($_SESSION['college_name']) ?></h2>
                    <h5 class="text-center text-black"><?= ab_e($_SESSION['college_location']) ?></h5>
                </div>
            </div>

            <h4 class="text-center text-black">ABHA Registration Report</h4>

            <div class="d-flex justify-content-between">
                <p class="mb-1">Report Period: <?= date('d.m.Y', strtotime($ab_from)) ?> to <?= date('d.m.Y', strtotime($ab_to)) ?></p>
                <p class="mb-1">Generated on: <?= date('d.m.Y H:i') ?></p>
            </div>
            <div class="d-flex justify-content-between">
                <p class="mb-1">Department: <?= ab_e($deptName) ?></p>
                <p class="mb-1">Filter: <?= ab_e($statusLabel) ?></p>
            </div>

            <!-- ── Summary ─────────────────────────────────────────────── -->
            <h6 class="section text-black"><b>1. Summary</b></h6>
            <table class="table table-bordered text-center">
                <thead class="thead-dark">
                    <tr>
                        <th>Patient Type</th>
                        <th>Total Registered</th>
                        <th>With ABHA</th>
                        <th>Without ABHA</th>
                        <th>Unique ABHA</th>
                        <th>Care Context Linked</th>
                        <th>ABHA Coverage %</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><b>OPD</b></td>
                        <td><?= $op['total'] ?></td>
                        <td><?= $op['with_abha'] ?></td>
                        <td><?= $op['without_abha'] ?></td>
                        <td><?= $op['unique_abha'] ?></td>
                        <td><?= $op['care_linked'] ?></td>
                        <td><?= $op['percent'] ?>%</td>
                    </tr>
                    <tr>
                        <td><b>IPD</b></td>
                        <td><?= $ip['total'] ?></td>
                        <td><?= $ip['with_abha'] ?></td>
                        <td><?= $ip['without_abha'] ?></td>
                        <td><?= $ip['unique_abha'] ?></td>
                        <td><?= $ip['care_linked'] ?></td>
                        <td><?= $ip['percent'] ?>%</td>
                    </tr>
                    <tr class="font-weight-bold">
                        <td>TOTAL</td>
                        <td><?= $totalPatients ?></td>
                        <td><?= $totalAbha ?></td>
                        <td><?= $totalPatients - $totalAbha ?></td>
                        <td>&mdash;</td>
                        <td><?= $op['care_linked'] + $ip['care_linked'] ?></td>
                        <td><?= $totalPercent ?>%</td>
                    </tr>
                </tbody>
            </table>

            <!-- ── OPD list ────────────────────────────────────────────── -->
            <h6 class="section text-black"><b>2. OPD Patients</b></h6>
            <table class="table table-bordered text-wrap text-center">
                <thead class="thead-dark">
                    <tr>
                        <th>Sl.</th>
                        <th>UHID</th>
                        <th>OP No.</th>
                        <th>Date</th>
                        <th>Patient Name</th>
                        <th>Age/Gender</th>
                        <th>Mobile</th>
                        <th>Department</th>
                        <th>Patient Type</th>
                        <th>ABHA Number</th>
                        <th>ABHA Address</th>
                        <th>Linked</th>
                        <th>Created By</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $res = $newReport->abhaOPDetails($ab_from, $ab_to, $ab_dept, $ab_status);
                    $i = 1;
                    while ($r = $res->fetch_assoc()) { ?>
                        <tr>
                            <td><?= $i++ ?></td>
                            <td><?= ab_e($r['uhid']) ?></td>
                            <td><?= ab_e($r['opd_no']) ?></td>
                            <td><?= $r['date'] ? date('d.m.Y', strtotime($r['date'])) : '' ?></td>
                            <td class="text-left"><?= ab_e($r['name']) ?></td>
                            <td><?= ab_e($r['age']) ?>/<?= ab_e($r['gender']) ?></td>
                            <td><?= ab_e($r['mobile']) ?></td>
                            <td><?= ab_e($r['dept_name']) ?></td>
                            <td><?= ab_e($r['patient_type']) ?></td>
                            <td><?= $r['abha_number'] ? ab_e($r['abha_number']) : 'Not Registered' ?></td>
                            <td><?= ab_e($r['abha_address']) ?></td>
                            <td class="<?= ((int) $r['abha_linked'] === 1) ? 'abha-yes' : 'abha-no' ?>"><?= ((int) $r['abha_linked'] === 1) ? 'Linked' : 'Not Linked' ?></td>
                            <td><?= ab_e($r['created_by']) ?></td>
                        </tr>
                    <?php }
                    if ($i === 1) { ?>
                        <tr><td colspan="13">No OPD registrations for the selected criteria.</td></tr>
                    <?php } ?>
                </tbody>
            </table>

            <!-- ── IPD list ────────────────────────────────────────────── -->
            <h6 class="section text-black page-break"><b>3. IPD Patients</b></h6>
            <table class="table table-bordered text-wrap text-center">
                <thead class="thead-dark">
                    <tr>
                        <th>Sl.</th>
                        <th>UHID</th>
                        <th>IP No.</th>
                        <th>Admission Date</th>
                        <th>Patient Name</th>
                        <th>Age/Gender</th>
                        <th>Mobile</th>
                        <th>Department</th>
                        <th>Patient Type</th>
                        <th>Ward</th>
                        <th>ABHA Number</th>
                        <th>Linked</th>
                        <th>Created By</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $res = $newReport->abhaIPDetails($ab_from, $ab_to, $ab_dept, $ab_status);
                    $j = 1;
                    while ($r = $res->fetch_assoc()) { ?>
                        <tr>
                            <td><?= $j++ ?></td>
                            <td><?= ab_e($r['uhid']) ?></td>
                            <td><?= ab_e($r['ipd_no']) ?></td>
                            <td><?= $r['admission_date'] ? date('d.m.Y', strtotime($r['admission_date'])) : '' ?></td>
                            <td class="text-left"><?= ab_e($r['name']) ?></td>
                            <td><?= ab_e($r['age']) ?>/<?= ab_e($r['gender']) ?></td>
                            <td><?= ab_e($r['mobile']) ?></td>
                            <td><?= ab_e($r['dept_name']) ?></td>
                            <td><?= ab_e($r['patient_type']) ?></td>
                            <td><?= ab_e($r['ward']) ?></td>
                            <td><?= $r['abha_number'] ? ab_e($r['abha_number']) : 'Not Registered' ?></td>
                            <td class="<?= ((int) $r['abha_linked'] === 1) ? 'abha-yes' : 'abha-no' ?>"><?= ((int) $r['abha_linked'] === 1) ? 'Linked' : 'Not Linked' ?></td>
                            <td><?= ab_e($r['created_by']) ?></td>
                        </tr>
                    <?php }
                    if ($j === 1) { ?>
                        <tr><td colspan="13">No IPD admissions for the selected criteria.</td></tr>
                    <?php } ?>
                </tbody>
            </table>

            <div class="row mt-4">
                <div class="col-md-12 text-right">
                    <h6>Total Patients Registered : <?= $totalPatients ?></h6>
                    <h6>Total Registered with ABHA : <?= $totalAbha ?></h6>
                    <h6>Total without ABHA : <?= $totalPatients - $totalAbha ?></h6>
                    <h6>ABHA Coverage : <?= $totalPercent ?>%</h6>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-md-12">
                    <button onclick="window.print()" class="btn btn-danger float-right d-print-none">
                        <i class="ri-printer-line"></i> Print
                    </button>
                </div>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js" integrity="sha384-+sLIOodYLS7CIrQpBjl+C7nPvqq+FbNUBDunl/OZv93DB7Ln/533i8e/mZXLi/P+" crossorigin="anonymous"></script>

</body>

</html>

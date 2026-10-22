<?php
require_once(__DIR__ . '/model/session_config.php');
date_default_timezone_set("Asia/Kolkata");
include('model/dbconnect.php');

$date      = isset($_REQUEST['date'])      ? $_REQUEST['date']      : date('Y-m-d');
$dept      = isset($_REQUEST['dept'])      ? (int)$_REQUEST['dept'] : 0;
$type      = isset($_REQUEST['type'])      ? $_REQUEST['type']      : 'total';
$dept_name = isset($_REQUEST['dept_name']) ? $_REQUEST['dept_name'] : '';

$login = new userlogin();
$con   = $login->dbconnect();

$safeDate = mysqli_real_escape_string($con, $date);
$safeDept = (int)$dept;

$typeCond = '';
if ($type === 'new') {
    $typeCond = " AND op.old_patient = 0";
} elseif ($type === 'old') {
    $typeCond = " AND op.old_patient = 1";
}

$deptCond = '';
if ($safeDept > 0) {
    $deptCond = " AND op.dept_id = $safeDept";
}

$sql = "SELECT op.opd_no, op.name, op.age, op.gender, op.mobile, op.address,
           op.doctor, op.patient_type, op.old_patient,
               d.dept_name
        FROM central_opd op
        LEFT JOIN department d ON d.id = op.dept_id
        WHERE DATE(op.date) = '$safeDate'
          $deptCond
          $typeCond
        ORDER BY op.id";

$result = mysqli_query($con, $sql);

$typeLabel = 'All';
if ($type === 'new') $typeLabel = 'New';
elseif ($type === 'old') $typeLabel = 'Old (Follow-Up)';

$displayDate = date('d.m.Y', strtotime($date));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Daily OPD - <?= htmlspecialchars($dept_name) ?> - <?= $displayDate ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="print_style.css">
    <style>
        body { font-family: 'Arial', sans-serif; font-size: 13px; }

        .page-header { text-align: center; margin-bottom: 10px; }
        .page-header h5, .page-header h6 { margin: 2px 0; }

        .report-meta { display: flex; justify-content: space-between; margin-bottom: 6px; font-size: 12px; }

        table { width: 100%; border-collapse: collapse; font-size: 12px; }
        th, td { border: 1px solid #333; padding: 4px 6px; vertical-align: middle; }
        thead th { background-color: #222; color: #fff; text-align: center; font-weight: 600; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        tbody tr:nth-child(even) { background-color: #f5f5f5; }
        .text-center { text-align: center; }
        .total-row th { background-color: #ddd; }

        .badge-new  { background: #28a745; color: #fff; padding: 2px 6px; border-radius: 3px; }
        .badge-old  { background: #007bff; color: #fff; padding: 2px 6px; border-radius: 3px; }

        @media print {
            @page { size: portrait; margin: 10mm; }
            .no-print { display: none !important; }
            thead th { background-color: #222 !important; color: #fff !important; }
            tbody tr:nth-child(even) { background-color: #f5f5f5 !important; }
        }
    </style>
</head>
<body onload="window.print()">

<div class="no-print text-right mb-2">
    <button onclick="window.print()" class="btn btn-sm btn-primary">&#128424; Print</button>
    <button onclick="window.close()" class="btn btn-sm btn-secondary ml-1">Close</button>
</div>

<div class="d-flex align-items-center mb-2">
    <img src="assets/logo.png" alt="Logo" width="100" style="margin-right:15px;">
    <div class="page-header flex-grow-1">
        <h6><?= htmlspecialchars($_SESSION['college_society'] ?? '') ?></h6>
        <h4><?= htmlspecialchars($_SESSION['college_name'] ?? '') ?></h4>
        <h6><?= htmlspecialchars($_SESSION['college_location'] ?? '') ?></h6>
        <h6><?= htmlspecialchars($_SESSION['college_phone'] ?? '') ?></h6>
    </div>
</div>

<h5 class="text-center mb-1">Daily OPD Patient List</h5>
<div class="report-meta">
    <span>
        <strong>Date:</strong> <?= $displayDate ?>
        &nbsp;&nbsp;
        <strong>Department:</strong> <?= htmlspecialchars($dept_name ?: 'All Departments') ?>
        &nbsp;&nbsp;
        <strong>Type:</strong> <?= htmlspecialchars($typeLabel) ?>
    </span>
    <span><strong>Generated:</strong> <?= date('d.m.Y H:i') ?></span>
</div>

<table>
    <thead>
        <tr>
            <th class="text-center" style="width:40px;">Sl No</th>
            <th>OPD No</th>
            <th>Patient Name</th>
            <th class="text-center">Age</th>
            <th class="text-center">Gender</th>
            <th>Department</th>
            <th>Doctor</th>
            <th class="text-center">Type</th>
            <th>Mobile</th>
            <th>Address</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $sn = 1;
        $total = $newCount = $oldCount = 0;
        if ($result && mysqli_num_rows($result) > 0):
            while ($row = mysqli_fetch_assoc($result)):
                $isNew = ($row['old_patient'] == 0);
                $total++;
                if ($isNew) $newCount++; else $oldCount++;
        ?>
        <tr>
            <td class="text-center"><?= $sn++ ?></td>
            <td><?= htmlspecialchars($row['opd_no']) ?></td>
            <td><?= htmlspecialchars($row['name']) ?></td>
            <td class="text-center"><?= htmlspecialchars($row['age']) ?></td>
            <td class="text-center"><?= htmlspecialchars($row['gender']) ?></td>
            <td><?= htmlspecialchars($row['dept_name']) ?></td>
            <td><?= htmlspecialchars($row['doctor']) ?></td>
            <td class="text-center">
                <?php if ($isNew): ?>
                    <span class="badge-new">New</span>
                <?php else: ?>
                    <span class="badge-old">Old</span>
                <?php endif; ?>
            </td>
            <td><?= htmlspecialchars($row['mobile']) ?></td>
            <td><?= htmlspecialchars($row['address']) ?></td>
        </tr>
        <?php endwhile; else: ?>
        <tr><td colspan="10" class="text-center">No patients found for the selected criteria.</td></tr>
        <?php endif; ?>
    </tbody>
    <tfoot>
        <tr class="total-row">
            <th colspan="7" class="text-right">Total Patients:</th>
            <th class="text-center"><?= $total ?></th>
            <th colspan="2">New: <?= $newCount ?> &nbsp;|&nbsp; Old/Follow-up: <?= $oldCount ?></th>
        </tr>
    </tfoot>
</table>

</body>
</html>

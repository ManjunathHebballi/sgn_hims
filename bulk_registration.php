<?php
/**
 * Bulk OPD registration / IPD admission from a CSV file (Admin only).
 *
 * Each row goes through model/registrationService.php, the same code as the
 * registration API, so numbering, tokens and bed handling match a normal
 * registration. Rows are independent: a bad row is reported and skipped, the
 * rest are still imported.
 *
 *   ?template=opd|ipd  downloads a CSV template
 *   POST type=opd|ipd + csv file  imports it and shows a per-row result
 */
require_once(__DIR__ . '/model/session_config.php');
require_once(__DIR__ . '/model/dbconnect.php');
require_once(__DIR__ . '/model/registrationService.php');
date_default_timezone_set("Asia/Kolkata");

$login = new userlogin;
$con = $login->dbconnect(); // redirects to login when there is no session
if (($_SESSION['role'] ?? '') !== 'Admin') {
    http_response_code(403);
    exit('Bulk upload is available to Admin users only.');
}

const BULK_MAX_ROWS = 500;

$templates = [
    'opd' => [
        'columns' => ['name', 'gender', 'age', 'mobile', 'department', 'doctor', 'date', 'dob', 'address', 'city',
            'district', 'state', 'pincode', 'parent_name', 'marital_status', 'religion', 'caste', 'occupation',
            'patient_type', 'diagnosis', 'mlc', 'abha_number', 'abha_address'],
        'example' => ['RAVI KUMAR', 'Male', '34', '9876543210', 'GENERAL MEDICINE', 'Dr Name', date('Y-m-d'), '',
            'MG Road', 'Hubli', 'Dharwad', 'Karnataka', '580020', '', 'Married', '', '', '', 'GENERAL', '', '0', '', ''],
        'required' => ['name', 'gender', 'age', 'mobile', 'department', 'doctor'],
    ],
    'ipd' => [
        'columns' => ['opd_no', 'department', 'doctor', 'ward', 'bed_number', 'bed_id', 'admission_date', 'diagnosis',
            'patient_type', 'policy', 'insurance_name', 'coverage_type', 'refer_by', 'cmo', 'mlc', 'remark'],
        'example' => ['OP202610090001', 'GENERAL MEDICINE', 'Dr Name', 'Male', 'M01', '', date('Y-m-d') . ' 10:30',
            'Fever', 'GENERAL', '', '', '', '', '', '0', ''],
        'required' => ['opd_no', 'department', 'doctor', 'bed_number or bed_id'],
    ],
];

// ── Template download ───────────────────────────────────────────────────────
if (isset($_GET['template'], $templates[$_GET['template']])) {
    $t = $templates[$_GET['template']];
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="' . $_GET['template'] . '_bulk_template.csv"');
    $out = fopen('php://output', 'w');
    fputcsv($out, $t['columns']);
    fputcsv($out, $t['example']);
    fclose($out);
    exit;
}

/** YYYY-MM-DD[ HH:MM[:SS]] from YYYY-MM-DD, DD-MM-YYYY or DD/MM/YYYY (time optional). '' stays ''. */
function bulk_normalize_date($v, $keepTime)
{
    $v = trim($v);
    if ($v === '') return '';
    if (!preg_match('#^(\d{1,4})[-/.](\d{1,2})[-/.](\d{1,4})(?:[ T](\d{1,2}):(\d{2})(?::(\d{2}))?)?$#', $v, $m)) {
        return $v; // let the service reject it with its own message
    }
    if (strlen($m[1]) === 4) { [$y, $mo, $d] = [$m[1], $m[2], $m[3]]; }
    else                     { [$d, $mo, $y] = [$m[1], $m[2], $m[3]]; }
    if (strlen($y) !== 4 || !checkdate((int) $mo, (int) $d, (int) $y)) return $v;
    $date = sprintf('%04d-%02d-%02d', $y, $mo, $d);
    if ($keepTime && isset($m[4]) && $m[4] !== '') {
        $date .= sprintf(' %02d:%02d:%02d', $m[4], $m[5], $m[6] ?? 0);
    }
    return $date;
}

// ── Import ──────────────────────────────────────────────────────────────────
$results = null;
$error = '';
$type = $_POST['type'] ?? 'opd';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($templates[$type])) {
        $error = 'Choose OPD or IPD.';
    } elseif (empty($_FILES['csv']) || $_FILES['csv']['error'] !== UPLOAD_ERR_OK) {
        $error = 'Please choose a CSV file to upload.';
    } elseif (strtolower(pathinfo($_FILES['csv']['name'], PATHINFO_EXTENSION)) !== 'csv') {
        $error = 'Only .csv files are accepted. In Excel use File → Save As → CSV.';
    } else {
        $fh = fopen($_FILES['csv']['tmp_name'], 'r');
        $header = fgetcsv($fh);
        if (!$header) {
            $error = 'The file is empty.';
        } else {
            // Normalise headers: strip a UTF-8 BOM, lower-case, spaces to underscores.
            $header[0] = preg_replace('/^\xEF\xBB\xBF/', '', (string) $header[0]);
            $header = array_map(function ($h) { return preg_replace('/\s+/', '_', strtolower(trim((string) $h))); }, $header);

            $rows = [];
            $line = 1;
            while (($cells = fgetcsv($fh)) !== false) {
                $line++;
                if (count(array_filter($cells, function ($c) { return trim((string) $c) !== ''; })) === 0) continue;
                $rows[] = [$line, $cells];
            }
            if (count($rows) > BULK_MAX_ROWS) {
                $error = 'The file has ' . count($rows) . ' rows; the limit is ' . BULK_MAX_ROWS . ' per upload. Split it into smaller files.';
            } elseif (!$rows) {
                $error = 'No data rows found under the header.';
            }
        }
        fclose($fh);

        if ($error === '') {
            set_time_limit(0);
            mysqli_set_charset($con, 'utf8mb4');
            $createdBy = (string) ($_SESSION['name'] ?? 'BULK');
            $results = [];
            foreach ($rows as [$line, $cells]) {
                $in = [];
                foreach ($header as $i => $col) {
                    if ($col !== '') $in[$col] = trim((string) ($cells[$i] ?? ''));
                }
                foreach (['date', 'dob'] as $k) {
                    if (isset($in[$k])) $in[$k] = bulk_normalize_date($in[$k], false);
                }
                if (isset($in['admission_date'])) $in['admission_date'] = bulk_normalize_date($in['admission_date'], true);

                $r = $type === 'opd'
                    ? reg_register_opd($con, $in, $createdBy)
                    : reg_register_ipd($con, $in, $createdBy);
                $results[] = [
                    'line' => $line,
                    'name' => $r['data']['name'] ?? ($in['name'] ?? ($in['opd_no'] ?? '')),
                    'ok' => $r['ok'],
                    'message' => $r['message'],
                    'number' => $r['ok'] ? ($type === 'opd' ? $r['data']['opd_no'] : $r['data']['ipd_no']) : '',
                    'uhid' => $r['data']['uhid'] ?? '',
                ];
            }
        }
    }
}

// Reference lists for filling in the CSV.
$departments = mysqli_query($con, "SELECT id, dept_name FROM department WHERE status='active' ORDER BY dept_name");
$doctors = mysqli_query($con, "SELECT id, TRIM(CONCAT(name,' ',last_name)) AS full_name FROM doctor WHERE role='Doctor' AND status='active' ORDER BY name");
$beds = mysqli_query($con, "SELECT id, bed_number, ward FROM bed WHERE status='active' ORDER BY ward, bed_number");

function bulk_e($v) { return htmlspecialchars((string) $v, ENT_QUOTES, 'UTF-8'); }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="theme-color" content="#319197">
    <title>Bulk OPD / IPD Upload</title>
    <link rel="icon" type="image/png" sizes="16x16" href="./images/favicon.png">
    <link href="css/style.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <style>
        .bulk-ok  { color: #1e7e34; font-weight: 600; }
        .bulk-err { color: #c82333; font-weight: 600; }
        .ref-list { max-height: 260px; overflow-y: auto; }
        .col-chip { display: inline-block; background: #eef2f7; border-radius: 3px; padding: 1px 6px; margin: 2px; font-family: monospace; font-size: 12px; }
        .col-chip.req { background: #fde2e1; }
    </style>
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
                <div class="row mb-3">
                    <div class="col-md-12">
                        <h4 class="text-uppercase">Bulk OPD / IPD Upload</h4>
                    </div>
                </div>

                <?php if ($error !== '') { ?>
                    <div class="alert alert-danger"><?= bulk_e($error) ?></div>
                <?php } ?>

                <?php if ($results !== null) {
                    $okCount = count(array_filter($results, function ($r) { return $r['ok']; }));
                    $failCount = count($results) - $okCount; ?>
                    <div class="card">
                        <div class="card-body">
                            <h5><?= strtoupper(e($type)) ?> import result:
                                <span class="bulk-ok"><?= $okCount ?> imported</span>,
                                <span class="bulk-err"><?= $failCount ?> failed</span>
                                <button type="button" class="btn btn-sm btn-outline-primary float-right" id="download-result">Download result CSV</button>
                            </h5>
                            <?php if ($failCount) { ?>
                                <p class="text-muted mb-2">Failed rows were not saved. Fix them in your file, keep only those rows, and upload again.</p>
                            <?php } ?>
                            <div class="table-responsive">
                                <table class="table table-bordered table-sm text-center" id="result-table">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>CSV Line</th>
                                            <th>Patient</th>
                                            <th>Status</th>
                                            <th><?= $type === 'opd' ? 'OPD No.' : 'IPD No.' ?></th>
                                            <th>UHID</th>
                                            <th>Message</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($results as $r) { ?>
                                            <tr>
                                                <td><?= (int) $r['line'] ?></td>
                                                <td class="text-left"><?= bulk_e($r['name']) ?></td>
                                                <td class="<?= $r['ok'] ? 'bulk-ok' : 'bulk-err' ?>"><?= $r['ok'] ? 'Imported' : 'Failed' ?></td>
                                                <td><?= bulk_e($r['number']) ?></td>
                                                <td><?= bulk_e($r['uhid']) ?></td>
                                                <td class="text-left"><?= bulk_e($r['message']) ?></td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                <?php } ?>

                <div class="row">
                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-body">
                                <h5>Upload CSV</h5>
                                <form method="post" enctype="multipart/form-data" id="bulk-form">
                                    <div class="form-group">
                                        <label>Upload type</label><br>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="type" id="type-opd" value="opd" <?= $type === 'opd' ? 'checked' : '' ?>>
                                            <label class="form-check-label" for="type-opd">OPD Registration</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="type" id="type-ipd" value="ipd" <?= $type === 'ipd' ? 'checked' : '' ?>>
                                            <label class="form-check-label" for="type-ipd">IPD Admission</label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="csv">CSV file (max <?= BULK_MAX_ROWS ?> rows)</label>
                                        <input type="file" class="form-control" name="csv" id="csv" accept=".csv,text/csv" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary" id="bulk-submit">Upload &amp; Import</button>
                                    <a class="btn btn-outline-secondary ml-2" href="?template=opd">OPD template</a>
                                    <a class="btn btn-outline-secondary ml-1" href="?template=ipd">IPD template</a>
                                </form>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-body">
                                <h5>How to fill the CSV</h5>
                                <ul class="pl-3 mb-2">
                                    <li>Keep the header row from the template. Columns can be in any order; extra columns are ignored.</li>
                                    <li><b>department</b> and <b>doctor</b>: the id or the exact name from the lists on the right.</li>
                                    <li>Dates: <code>YYYY-MM-DD</code>, <code>DD-MM-YYYY</code> or <code>DD/MM/YYYY</code>. Blank date = today.</li>
                                    <li><b>mobile</b>: 10 digits. In Excel, format the column as Text so it is not turned into 9.88E+09.</li>
                                    <li><b>gender</b>: Male, Female or Other.</li>
                                    <li><b>IPD</b>: the patient must already have an OPD number. Give <b>bed_number</b> (with <b>ward</b>) or <b>bed_id</b>; the bed must be free. Name, age, mobile, etc. are taken from the OPD registration.</li>
                                    <li>Each row is saved on its own. Uploading the same file twice registers the patients twice.</li>
                                </ul>
                                <p class="mb-1"><b>OPD columns</b> (red = required):</p>
                                <?php foreach ($templates['opd']['columns'] as $c) { ?><span class="col-chip <?= in_array($c, $templates['opd']['required'], true) ? 'req' : '' ?>"><?= bulk_e($c) ?></span><?php } ?>
                                <p class="mb-1 mt-2"><b>IPD columns</b> (red = required):</p>
                                <?php foreach ($templates['ipd']['columns'] as $c) { ?><span class="col-chip <?= in_array($c, ['opd_no', 'department', 'doctor'], true) ? 'req' : '' ?>"><?= bulk_e($c) ?></span><?php } ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-body">
                                <h5>Departments</h5>
                                <div class="ref-list">
                                    <table class="table table-sm table-bordered mb-0">
                                        <thead><tr><th>id</th><th>department</th></tr></thead>
                                        <tbody>
                                            <?php while ($d = mysqli_fetch_assoc($departments)) { ?>
                                                <tr><td><?= (int) $d['id'] ?></td><td><?= bulk_e($d['dept_name']) ?></td></tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <h5>Doctors</h5>
                                <div class="ref-list">
                                    <table class="table table-sm table-bordered mb-0">
                                        <thead><tr><th>id</th><th>doctor</th></tr></thead>
                                        <tbody>
                                            <?php while ($d = mysqli_fetch_assoc($doctors)) { ?>
                                                <tr><td><?= (int) $d['id'] ?></td><td><?= bulk_e($d['full_name']) ?></td></tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <h5>Free Beds</h5>
                                <div class="ref-list">
                                    <table class="table table-sm table-bordered mb-0">
                                        <thead><tr><th>bed_id</th><th>bed_number</th><th>ward</th></tr></thead>
                                        <tbody>
                                            <?php while ($b = mysqli_fetch_assoc($beds)) { ?>
                                                <tr><td><?= (int) $b['id'] ?></td><td><?= bulk_e($b['bed_number']) ?></td><td><?= bulk_e($b['ward']) ?></td></tr>
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

    <script src="./vendor/global/global.min.js"></script>
    <script src="./js/quixnav-init.js"></script>
    <script src="./js/custom.min.js"></script>
    <script>
        // Stop a double click from importing the file twice.
        $('#bulk-form').on('submit', function () {
            $('#bulk-submit').prop('disabled', true).text('Importing… please wait');
        });

        $('#download-result').on('click', function () {
            var rows = [];
            $('#result-table tr').each(function () {
                rows.push($(this).children().map(function () {
                    return '"' + $(this).text().trim().replace(/"/g, '""') + '"';
                }).get().join(','));
            });
            var blob = new Blob(['﻿' + rows.join('\r\n')], { type: 'text/csv;charset=utf-8' });
            var a = document.createElement('a');
            a.href = URL.createObjectURL(blob);
            a.download = 'bulk_<?= bulk_e($type) ?>_result.csv';
            a.click();
        });
    </script>
</body>

</html>

<?php
require_once(__DIR__ . '/model/session_config.php');
include('model/optreatmentClass1.php');

$newTreat = new addOTTreat;
$patient = $newTreat->getPatient($_REQUEST['id']);
$res = mysqli_fetch_assoc($patient);

$examRes = $newTreat->getOPExamination($_REQUEST['id']);
$examRow = mysqli_fetch_assoc($examRes);
if (!$examRow) {
    $examRow = array();
}

$medicineRows = array();
$medicineRes = $newTreat->getPatienMedicDoc($_REQUEST['id']);
while ($medicineRow = mysqli_fetch_assoc($medicineRes)) {
    $medicineRows[] = $medicineRow;
}

$labRows = array();
$labRes = $newTreat->getPatientLab($_REQUEST['id']);
while ($labRow = mysqli_fetch_assoc($labRes)) {
    $labRows[] = $labRow;
}

$radioRows = array();
$radioRes = $newTreat->getPatientLabRadio($_REQUEST['id']);
while ($radioRow = mysqli_fetch_assoc($radioRes)) {
    $radioRows[] = $radioRow;
}

$escape = function ($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
};

$hasText = function ($value) {
    return strlen(trim((string)$value)) > 0;
};

$renderEditorValue = function ($value) use ($escape) {
    $value = trim((string)$value);
    if ($value === '') {
        return '';
    }

    if (strip_tags($value) !== $value) {
        return $value;
    }

    return nl2br($escape($value));
};

$vitals = array(
    'BP' => isset($examRow['bp']) ? $examRow['bp'] : '',
    'RBS (mg/dL)' => isset($examRow['rbs']) ? $examRow['rbs'] : '',
    'PR (bpm)' => isset($examRow['pr']) ? $examRow['pr'] : '',
    'Ht (cms)' => isset($examRow['ht']) ? $examRow['ht'] : '',
    'Waist (cms)' => isset($examRow['waist']) ? $examRow['waist'] : '',
    'TP (F)' => isset($examRow['tp']) ? $examRow['tp'] : '',
    'RR (cpm)' => isset($examRow['rr']) ? $examRow['rr'] : '',
    'SpO2 (%)' => isset($examRow['spo2']) ? $examRow['spo2'] : '',
    'Weight (kgs)' => isset($examRow['weight']) && $examRow['weight']!=0 ? $examRow['weight'] : '',
    'HC (cms)' => isset($examRow['hc']) ? $examRow['hc'] : '',
    'BMI' => isset($examRow['bmi']) ? $examRow['bmi'] : ''
);

$detailFields = array(
    'Comorbidities' => isset($examRow['comorbidities']) ? $examRow['comorbidities'] : '',
    'Hypersensitivity' => isset($examRow['hypersensitivity']) ? $examRow['hypersensitivity'] : '',
    'Chief Complaint' => isset($examRow['complaints']) ? $examRow['complaints'] : '',
    'Associated With' => isset($examRow['associated_with']) ? $examRow['associated_with'] : '',
    'Diagnosis' => isset($examRow['diagnosis']) ? $examRow['diagnosis'] : '',
    'Medical Advice' => isset($examRow['medical_advice']) ? $examRow['medical_advice'] : '',
    'Advice' => isset($examRow['advice']) ? $examRow['advice'] : ''
);

$nextVisitDate = '';
if (
    isset($examRow['next_visit_date']) &&
    $examRow['next_visit_date'] !== '' &&
    $examRow['next_visit_date'] !== '0000-00-00' &&
    $examRow['next_visit_date'] !== '1970-01-01'
) {
    $nextVisitDate = date('d.m.Y', strtotime($examRow['next_visit_date']));
}

$referredToName = '';
if (isset($examRow['referred_to']) && trim((string)$examRow['referred_to']) !== '') {
    $doctorRes = $newTreat->getDoctor();
    while ($doctorRow = mysqli_fetch_assoc($doctorRes)) {
        if ((string)$doctorRow['id'] === (string)$examRow['referred_to']) {
            $referredToName = trim($doctorRow['name'] . ' ' . $doctorRow['last_name']);
            break;
        }
    }
}

$visitDate = isset($res['date']) && $res['date'] !== '' ? date('d.m.Y', strtotime($res['date'])) : '';
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="icon" type="image/png" sizes="16x16" href="./images/favicon.png">
    <link href="./vendor/jqvmap/css/jqvmap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <title>Print OP Casesheet</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #f4f7fb;
            color: #1f2a36;
        }

        .sheet {
            max-width: 1100px;
            margin: 16px auto;
            background: #ffffff;
            border: 1px solid #d9e1ea;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(20, 44, 75, 0.09);
            overflow: hidden;
        }

        .sheet-header {
            padding: 16px 18px;
            border-bottom: 1px solid #d9e1ea;
        }

        .hospital-title {
            font-weight: 700;
            line-height: 1.2;
            margin: 0;
            font-size: 28px;
            text-transform: uppercase;
            color: #0f3d6d;
        }

        .doctor-title {
            font-weight: 700;
            margin: 0;
            font-size: 22px;
        }

        .doctor-meta {
            margin: 2px 0 0;
            font-size: 14px;
            color: #4b5a69;
            line-height: 1.5;
        }

        .patient-strip {
            margin: 14px 18px 0;
            border: 1px solid #c9d9eb;
            border-radius: 8px;
            background: #edf5ff;
            padding: 10px 12px;
        }

        .section {
            margin: 14px 18px;
        }

        .section-title {
            margin: 0 0 8px;
            font-weight: 600;
            font-size: 18px;
            color: #103c6a;
        }

        .field-card {
            border: 1px solid #dbe4ee;
            border-radius: 8px;
            background: #f8fbff;
            padding: 8px 10px;
            height: 100%;
        }

        .field-label {
            font-size: 12px;
            font-weight: 600;
            color: #5f6e7d;
            text-transform: uppercase;
            margin-bottom: 3px;
        }

        .field-value {
            font-size: 14px;
            color: #1f2a36;
        }

        .content-box {
            border: 1px solid #dbe4ee;
            border-radius: 8px;
            background: #f9fbff;
            padding: 10px;
            min-height: 48px;
        }

        .table-wrap {
            border: 1px solid #dbe4ee;
            border-radius: 8px;
            overflow: hidden;
        }

        .table thead th {
            background: #184f87;
            color: #ffffff;
            border-color: #184f87;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            padding: 9px 8px;
        }

        .table td {
            border-color: #dbe4ee;
            font-size: 13px;
            padding: 8px;
            vertical-align: middle;
        }

        .table tbody tr:nth-child(even) {
            background: #f8fbff;
        }

        .qr-box {
            text-align: right;
        }

        .footer {
            margin: 8px 18px 14px;
            color: #324252;
        }

        @media (max-width: 767px) {
            .hospital-title {
                font-size: 22px;
            }

            .doctor-title {
                font-size: 18px;
            }

            .qr-box {
                text-align: center;
            }
        }

        @media print {
            @page {
                margin: 10mm;
            }

            body {
                background: #ffffff;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .sheet {
                box-shadow: none;
                border-color: #bfcada;
                margin: 0;
                max-width: 100%;
            }

            .table thead th {
                background: #d7e2ef !important;
                color: #1f2a36 !important;
                border-color: #bfcada !important;
            }

            .table td,
            .table th,
            .field-card,
            .content-box,
            .table-wrap,
            .patient-strip {
                border-color: #bfcada !important;
            }

            body::before {
                content: "";
                position: fixed;
                top: 36%;
                left: 34%;
                width: 500px;
                height: 500px;
                margin-left: -100px;
                margin-top: -100px;
                background-image: url('assets/logo.png');
                background-size: contain;
                background-repeat: no-repeat;
                opacity: 0.12;
                pointer-events: none;
            }
        }
    </style>
</head>

<body onload="window.print()">
    <div class="sheet">
        <div class="sheet-header">
            <div class="row align-items-center">
                <div class="col-sm-4 mb-3 mb-sm-0">
                    <h2 class="hospital-title">SGN<br>HOSPITAL</h2>
                </div>
                <div class="col-sm-3 text-center mb-3 mb-sm-0">
                    <img src="assets/logo.png" class="img-fluid" width="150" alt="Hospital Logo">
                </div>
                <div class="col-sm-5 text-sm-right">
                    <h3 class="doctor-title text-uppercase"><?= $escape(isset($res['doc_name']) ? $res['doc_name'] : '') ?></h3>
                    <p class="doctor-meta">
                        <?= $escape(isset($res['qualification']) ? $res['qualification'] : '') ?><br>
                         <?= $escape(isset($res['unit']) ? $res['unit'] : '') ?><br>
                        <strong><?= $escape(isset($res['designation']) ? $res['designation'] : '') ?></strong>
                    </p>
                </div>
            </div>
        </div>

        <div class="patient-strip">
            <div class="row align-items-center">
                <div class="col-md-9">
                    <h5 class="mb-0 text-uppercase">
                        <?= $escape(isset($res['opd_no']) ? $res['opd_no'] : '') ?> :
                        <?= $escape(isset($res['name']) ? $res['name'] : '') ?>
                        (<?= $escape(isset($res['age']) ? $res['age'] : '') ?>y, <?= $escape(isset($res['gender']) ? $res['gender'] : '') ?>)
                        <?= $hasText(isset($res['mobile']) ? $res['mobile'] : '') ? ' - ' . $escape($res['mobile']) : '' ?>
                    </h5>
                </div>
                <div class="col-md-3 text-md-right mt-2 mt-md-0">
                    <strong>Date:</strong> <?= $escape($visitDate) ?>
                </div>
            </div>
        </div>

        <div class="section">
            <div class="row">
                <?php foreach ($detailFields as $label => $value) { ?>
                    <?php if ($hasText($value)) { ?>
                        <div class="col-md-6 mb-2">
                            <div class="field-card">
                                <div class="field-label"><?= $label ?></div>
                                <div class="field-value"><?= $escape($value) ?></div>
                            </div>
                        </div>
                    <?php } ?>
                <?php } ?>
            </div>
        </div>

        <?php
        $hasVitals = false;
        foreach ($vitals as $vitalValue) {
            if ($hasText($vitalValue)) {
                $hasVitals = true;
                break;
            }
        }
        ?>
        <?php if ($hasVitals) { ?>
            <div class="section">
                <h5 class="section-title">Vitals</h5>
                <div class="row">
                    <?php foreach ($vitals as $label => $value) { ?>
                        <?php if ($hasText($value)) { ?>
                            <div class="col-md-3 col-6 mb-2">
                                <div class="field-card">
                                    <div class="field-label"><?= $label ?></div>
                                    <div class="field-value"><?= $escape($value) ?></div>
                                </div>
                            </div>
                        <?php } ?>
                    <?php } ?>
                </div>
            </div>
        <?php } ?>

        <?php if ($hasText(isset($examRow['history']) ? $examRow['history'] : '')) { ?>
            <div class="section">
                <h5 class="section-title">History</h5>
                <div class="content-box"><?= $renderEditorValue($examRow['history']) ?></div>
            </div>
        <?php } ?>

        <?php if ($hasText(isset($examRow['examination']) ? $examRow['examination'] : '')) { ?>
            <div class="section">
                <h5 class="section-title">Examination</h5>
                <div class="content-box"><?= $renderEditorValue($examRow['examination']) ?></div>
            </div>
        <?php } ?>

        <div class="section">
            <h5 class="section-title">Prescription</h5>
            <div class="table-wrap">
                <table class="table table-bordered mb-0">
                    <thead>
                        <tr>
                            <th>Sl. No</th>
                            <th>Medicine</th>
                            <th>Dosage</th>
                            <th>Timing</th>
                            <th>Frequency</th>
                            <th>Duration</th>
                            <th>Qty</th>
                            <th>Instructions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($medicineRows) > 0) { ?>
                            <?php $sn = 1; ?>
                            <?php foreach ($medicineRows as $row) { ?>
                                <tr>
                                    <td><?= $sn ?></td>
                                    <td><?= $escape(isset($row['name']) ? $row['name'] : '') ?></td>
                                    <td><?= $escape(isset($row['dosage']) ? $row['dosage'] : '') ?></td>
                                    <td><?= $escape(isset($row['when']) ? $row['when'] : '') ?></td>
                                    <td><?= $escape(isset($row['frequency']) ? $row['frequency'] : '') ?></td>
                                    <td><?= $escape(isset($row['days']) ? $row['days'] : '') ?></td>
                                    <td><?= $escape(isset($row['qty']) ? $row['qty'] : '') ?></td>
                                    <td><?= $escape(isset($row['instruction']) ? $row['instruction'] : '') ?></td>
                                </tr>
                                <?php $sn++; ?>
                            <?php } ?>
                        <?php } else { ?>
                            <tr>
                                <td colspan="8" class="text-center">No medicine added.</td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>

        <?php if (count($labRows) > 0 || $hasText(isset($examRow['lab_observation']) ? $examRow['lab_observation'] : '')) { ?>
            <div class="section">
                <h5 class="section-title">Lab Investigations</h5>
                <?php if (count($labRows) > 0) { ?>
                    <div class="table-wrap mb-2">
                        <table class="table table-bordered mb-0">
                            <thead>
                                <tr>

                                    <th>Sl. No</th>
                                    <th>Test</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $sn = 1; ?>
                                <?php foreach ($labRows as $row) { ?>
                                    <tr>
                                        <td><?= $sn ?></td>
                                        <td><?= $escape(isset($row['name']) ? $row['name'] : '') ?></td>
                                       </tr>
                                    <?php $sn++; ?>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                <?php } ?>

                <?php if ($hasText(isset($examRow['lab_observation']) ? $examRow['lab_observation'] : '')) { ?>
                    <div class="field-label mb-1">Laboratory Observation</div>
                    <div class="content-box"><?= $renderEditorValue($examRow['lab_observation']) ?></div>
                <?php } ?>
            </div>
        <?php } ?>

        <?php if (count($radioRows) > 0 || $hasText(isset($examRow['radiology_observation']) ? $examRow['radiology_observation'] : '')) { ?>
            <div class="section">
                <h5 class="section-title">Radiology</h5>
                <?php if (count($radioRows) > 0) { ?>
                    <div class="table-wrap mb-2">
                        <table class="table table-bordered mb-0">
                            <thead>
                                <tr>
                                    <th>Sl. No</th>
                                    <th>Test</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $sn = 1; ?>
                                <?php foreach ($radioRows as $row) { ?>
                                    <tr>
                                        <td><?= $sn ?></td>
                                        <?php // Ordered test name only — the per-parameter results are not listed here. ?>
                                        <td><?= $escape(isset($row['test_name']) ? $row['test_name'] : '') ?></td>
                                    </tr>
                                    <?php $sn++; ?>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                <?php } ?>

                <?php if ($hasText(isset($examRow['radiology_observation']) ? $examRow['radiology_observation'] : '')) { ?>
                    <div class="field-label mb-1">Radiology Observation</div>
                    <div class="content-box"><?= $renderEditorValue($examRow['radiology_observation']) ?></div>
                <?php } ?>
            </div>
        <?php } ?>

        <?php if ($hasText($nextVisitDate) || $hasText($referredToName)) { ?>
            <div class="section">
                <h5 class="section-title">Follow Up</h5>
                <div class="row">
                    <?php if ($hasText($nextVisitDate)) { ?>
                        <div class="col-md-4 mb-2">
                            <div class="field-card">
                                <div class="field-label">Next Visit Date</div>
                                <div class="field-value"><?= $escape($nextVisitDate) ?></div>
                            </div>
                        </div>
                    <?php } ?>

                    <?php if ($hasText($referredToName)) { ?>
                        <div class="col-md-8 mb-2">
                            <div class="field-card">
                                <div class="field-label">Referred To</div>
                                <div class="field-value"><?= $escape($referredToName) ?></div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        <?php } ?>

        <div class="section">
            <div class="row align-items-end">
                <div class="col-md-8"></div>
                <div class="col-md-4 qr-box">
                    <img
                        src="https://api.qrserver.com/v1/create-qr-code/?data=https://nexenstial.in/sankalpayurvedahospital/print_opcasesheet.php?id=<?= urlencode($_REQUEST['id']) ?>&amp;size=120x120"
                        title="Exam QR Code"
                        width="120"
                        alt="QR"
                    >
                </div>
            </div>
        </div>
    </div>

    <div class="footer text-center">
        <h5 class="mb-1 text-uppercase"><b><?= $escape(isset($res['doc_name']) ? $res['doc_name'] : '') ?></b></h5>
        <h6 class="mb-1">Phone : 0836-2772972</h6>
        <h6 class="mb-0">Jalna, Maharashtra</h6>
    </div>
</body>
</html>
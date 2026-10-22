<?php
require_once(__DIR__ . '/model/session_config.php');
error_reporting(E_ALL);
ini_set('display_errors', 1);
date_default_timezone_set("Asia/Kolkata");
include('model/cathLabClass.php');

$procedure_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($procedure_id <= 0) {
    $_SESSION['error_message'] = "Procedure ID not provided";
    header('Location: list_cathlab_procedure.php');
    exit();
}

$cathLab = new cathLab;

try {
    $procedure = $cathLab->getCathLabProcedureById($procedure_id);

    if (!$procedure) {
        $_SESSION['error_message'] = "Procedure not found";
        header('Location: list_cathlab_procedure.php');
        exit();
    }

    $items_result = $cathLab->getCathLabProcedureItems($procedure_id);
} catch (Exception $e) {
    $_SESSION['error_message'] = "Error loading procedure details: " . $e->getMessage();
    header('Location: list_cathlab_procedure.php');
    exit();
}

$items = array();
$total_qty = 0;
$total_cost = 0;

if ($items_result && $items_result instanceof mysqli_result) {
    while ($row = mysqli_fetch_assoc($items_result)) {
        $items[] = $row;
        $qty = (float)($row['quantity_consumed'] ?? 0);
        $cost = (float)($row['cost_price'] ?? 0);
        $total_qty += $qty;
        $total_cost += ($qty * $cost);
    }
}

if ($total_qty == 0 && isset($procedure['total_quantity'])) {
    $total_qty = (float)$procedure['total_quantity'];
}

if ($total_cost == 0 && isset($procedure['total_cost'])) {
    $total_cost = (float)$procedure['total_cost'];
}

$status = strtolower((string)($procedure['status'] ?? 'pending'));
$status_class = 'badge-warning';
if ($status === 'completed') {
    $status_class = 'badge-success';
} elseif ($status === 'cancelled') {
    $status_class = 'badge-danger';
} elseif ($status === 'in-progress') {
    $status_class = 'badge-info';
}

$billed_status = strtolower((string)($procedure['billed_status'] ?? 'pending'));
$billed_class = 'badge-warning';
if ($billed_status === 'billed') {
    $billed_class = 'badge-success';
} elseif ($billed_status === 'cancelled') {
    $billed_class = 'badge-danger';
}

$procedure_date = !empty($procedure['procedure_date']) ? date('d-m-Y', strtotime($procedure['procedure_date'])) : '-';
$procedure_time = !empty($procedure['procedure_time']) ? date('h:i A', strtotime($procedure['procedure_time'])) : '-';
$created_at = !empty($procedure['created_at']) ? date('d-m-Y h:i A', strtotime($procedure['created_at'])) : '-';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="theme-color" content="#319197">

    <title>CathLab Procedure Details</title>
    <link rel="icon" type="image/png" sizes="16x16" href="../images/favicon.png">
    <link href="css/style.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

    <style>
    .content-body {
        margin-top: 120px;
    }

    .page-chip {
        display: inline-block;
        background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
        color: white;
        padding: 8px 16px;
        border-radius: 20px;
        font-weight: bold;
        margin-bottom: 20px;
    }

    .section-card {
        border-radius: 8px;
        border: 1px solid #e9ecef;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    .section-header {
        background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
        color: #fff;
        border-radius: 8px 8px 0 0;
        padding: 14px 18px;
        font-weight: 600;
    }

    .meta-box {
        background: #f8fbfd;
        border-left: 4px solid #17a2b8;
        border-radius: 6px;
        padding: 14px;
        margin-bottom: 14px;
    }

    .meta-label {
        font-size: 11px;
        color: #6c757d;
        text-transform: uppercase;
        margin-bottom: 4px;
    }

    .meta-value {
        font-size: 15px;
        color: #212529;
        font-weight: 600;
        word-break: break-word;
    }

    .table thead th {
        background: #138496;
        color: #fff;
        border-color: #138496;
        vertical-align: middle;
    }

    .summary-strip {
        background: #eef8fb;
        border: 1px solid #cdeaf1;
        border-radius: 8px;
        padding: 16px;
    }

    .summary-strip .value {
        font-size: 24px;
        font-weight: 700;
        color: #0f6b7b;
    }

    .summary-strip .label {
        font-size: 12px;
        color: #566;
        text-transform: uppercase;
    }
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

    <div id="main-wrapper" style="display: block; opacity: 1;">

        <?php include ('leftbar.php')?>

        <div class="content-body">
            <div class="container-fluid">
                <div class="row mb-3">
                    <div class="col-md-12">
                        <span class="page-chip"><i class="fa fa-file-medical mr-1"></i> PROCEDURE DETAILS</span>
                        <h4 class="text-uppercase mb-1">CathLab Procedure</h4>
                        <p class="text-muted mb-0">Complete details and consumed items for procedure <strong><?= htmlspecialchars($procedure['procedure_code'] ?? '-') ?></strong></p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="section-card mb-3">
                            <div class="section-header"><i class="fa fa-info-circle mr-1"></i> Procedure Information</div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="meta-box">
                                            <div class="meta-label">Procedure Code</div>
                                            <div class="meta-value"><?= htmlspecialchars($procedure['procedure_code'] ?? '-') ?></div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="meta-box">
                                            <div class="meta-label">Procedure Date</div>
                                            <div class="meta-value"><?= $procedure_date ?></div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="meta-box">
                                            <div class="meta-label">Procedure Time</div>
                                            <div class="meta-value"><?= $procedure_time ?></div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="meta-box">
                                            <div class="meta-label">Duration (Minutes)</div>
                                            <div class="meta-value"><?= (int)($procedure['duration_minutes'] ?? 0) ?></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="meta-box">
                                            <div class="meta-label">Patient Name</div>
                                            <div class="meta-value"><?= htmlspecialchars($procedure['patient_name'] ?? '-') ?></div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="meta-box">
                                            <div class="meta-label">Patient ID</div>
                                            <div class="meta-value"><?= htmlspecialchars((string)($procedure['patient_id'] ?? '-')) ?></div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="meta-box">
                                            <div class="meta-label">Admission ID</div>
                                            <div class="meta-value"><?= htmlspecialchars((string)($procedure['admission_id'] ?? '-')) ?></div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="meta-box">
                                            <div class="meta-label">Doctor</div>
                                            <div class="meta-value"><?= htmlspecialchars($procedure['doctor_name'] ?? '-') ?></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="meta-box">
                                            <div class="meta-label">Procedure Type</div>
                                            <div class="meta-value"><?= htmlspecialchars($procedure['procedure_type'] ?? '-') ?></div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="meta-box">
                                            <div class="meta-label">Clinical Status</div>
                                            <div class="meta-value"><span class="badge <?= $status_class ?>"><?= ucfirst($status) ?></span></div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="meta-box">
                                            <div class="meta-label">Billing Status</div>
                                            <div class="meta-value"><span class="badge <?= $billed_class ?>"><?= ucfirst(str_replace('_', ' ', $billed_status)) ?></span></div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="meta-box">
                                            <div class="meta-label">Created On</div>
                                            <div class="meta-value"><?= $created_at ?></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="meta-box mb-0">
                                            <div class="meta-label">Observations</div>
                                            <div class="meta-value"><?= nl2br(htmlspecialchars($procedure['observations'] ?? '-')) ?></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="section-card mb-3">
                            <div class="section-header"><i class="fa fa-list mr-1"></i> Consumed Items</div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped table-sm mb-3">
                                        <thead>
                                            <tr>
                                                <th>Sl No</th>
                                                <th>Item Code</th>
                                                <th>Item Name</th>
                                                <th>Batch No</th>
                                                <th>Expiry</th>
                                                <th class="text-right">Qty</th>
                                                <th class="text-right">Cost Price</th>
                                                <th class="text-right">Amount</th>
                                                <th>Status</th>
                                                <th>Remarks</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (count($items) > 0) {
                                                $sn = 1;
                                                foreach ($items as $item) {
                                                    $qty = (float)($item['quantity_consumed'] ?? 0);
                                                    $cost = (float)($item['cost_price'] ?? 0);
                                                    $amount = $qty * $cost;
                                                    $expiry_text = !empty($item['expiry_date']) ? date('d-m-Y', strtotime($item['expiry_date'])) : '-';
                                            ?>
                                            <tr>
                                                <td><?= $sn++ ?></td>
                                                <td><?= htmlspecialchars($item['item_code'] ?? '-') ?></td>
                                                <td><?= htmlspecialchars($item['item_name'] ?? '-') ?></td>
                                                <td><?= htmlspecialchars($item['batch_number'] ?? '-') ?></td>
                                                <td><?= $expiry_text ?></td>
                                                <td class="text-right"><?= number_format($qty, 2) ?></td>
                                                <td class="text-right"><?= number_format($cost, 2) ?></td>
                                                <td class="text-right"><strong><?= number_format($amount, 2) ?></strong></td>
                                                <td><?= htmlspecialchars(ucfirst((string)($item['status'] ?? 'pending'))) ?></td>
                                                <td><?= htmlspecialchars($item['remarks'] ?? '-') ?></td>
                                            </tr>
                                            <?php }
                                            } else { ?>
                                            <tr>
                                                <td colspan="10" class="text-center text-muted">No item records found for this procedure.</td>
                                            </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>

                                <div class="row summary-strip text-center">
                                    <div class="col-md-3">
                                        <div class="value"><?= count($items) ?></div>
                                        <div class="label">Line Items</div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="value"><?= number_format($total_qty, 2) ?></div>
                                        <div class="label">Total Quantity</div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="value">₹<?= number_format($total_cost, 2) ?></div>
                                        <div class="label">Total Cost</div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="value">₹<?= number_format((float)($procedure['billed_amount'] ?? 0), 2) ?></div>
                                        <div class="label">Billed Amount</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-12">
                        <a href="list_cathlab_procedure.php" class="btn btn-info">
                            <i class="fa fa-arrow-left"></i> Back to Procedure List
                        </a>
                        <a href="print_cathlab_procedure.php?id=<?= (int)$procedure['id'] ?>" class="btn btn-secondary" target="_blank">
                            <i class="fa fa-print"></i> Print Procedure
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <?php include ('footer.php')?>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        var pre = document.getElementById('preloader');
        if (pre) {
            pre.style.display = 'none';
        }
    });
    </script>

</body>

</html>

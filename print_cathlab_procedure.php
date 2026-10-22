<?php
require_once(__DIR__ . '/model/session_config.php');
date_default_timezone_set("Asia/Kolkata");
include('model/cathLabClass.php');

$procedure_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($procedure_id <= 0) {
    echo "<script>window.location='list_cathlab_procedure.php';</script>";
    exit();
}

$cathLab = new cathLab;

try {
    $procedure = $cathLab->getCathLabProcedureById($procedure_id);
    if (!$procedure) {
        echo "<script>alert('Procedure not found'); window.location='list_cathlab_procedure.php';</script>";
        exit();
    }

    $items_result = $cathLab->getCathLabProcedureItems($procedure_id);
} catch (Exception $e) {
    echo "<script>alert('Error loading procedure details'); window.location='list_cathlab_procedure.php';</script>";
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

$procedure_date = !empty($procedure['procedure_date']) ? date('d-m-Y', strtotime($procedure['procedure_date'])) : '-';
$procedure_time = !empty($procedure['procedure_time']) ? date('h:i A', strtotime($procedure['procedure_time'])) : '-';
$print_time = date('d-m-Y h:i A');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Print Procedure - <?= htmlspecialchars($procedure['procedure_code'] ?? '') ?></title>

    <style>
    * {
        box-sizing: border-box;
    }

    body {
        margin: 0;
        padding: 0;
        font-family: Arial, sans-serif;
        font-size: 12px;
        color: #111;
        background: #fff;
    }

    .print-btn {
        position: fixed;
        top: 14px;
        right: 14px;
        z-index: 99;
        border: 0;
        background: #007bff;
        color: #fff;
        padding: 8px 14px;
        border-radius: 4px;
        font-size: 13px;
        cursor: pointer;
    }

    .sheet {
        width: 210mm;
        margin: 0 auto;
        padding: 10mm;
    }

    .header {
        border-bottom: 2px solid #007bff;
        padding-bottom: 10px;
        margin-bottom: 15px;
        text-align: center;
    }

    .header-brand {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 12px;
        margin-bottom: 6px;
    }

    .header-logo {
        width: 62px;
        height: 62px;
        object-fit: contain;
        border-radius: 6px;
    }

    .header h1 {
        margin: 0;
        font-size: 20px;
        color: #007bff;
    }

    .header h2 {
        margin: 6px 0 0;
        font-size: 16px;
        color: #222;
    }

    .header p {
        margin: 6px 0 0;
        font-size: 11px;
        color: #555;
    }

    .block {
        border: 1px solid #d9d9d9;
        padding: 10px;
        margin-bottom: 12px;
        border-radius: 4px;
    }

    .block-title {
        font-size: 13px;
        font-weight: bold;
        color: #007bff;
        margin: 0 0 8px;
        border-bottom: 1px solid #d9d9d9;
        padding-bottom: 5px;
    }

    .grid {
        display: table;
        width: 100%;
    }

    .row {
        display: table-row;
    }

    .cell {
        display: table-cell;
        width: 50%;
        padding: 4px 0;
        vertical-align: top;
    }

    .label {
        font-weight: bold;
        color: #333;
        min-width: 130px;
        display: inline-block;
    }

    .items {
        width: 100%;
        border-collapse: collapse;
        margin-top: 8px;
    }

    .items th,
    .items td {
        border: 1px solid #d9d9d9;
        padding: 6px;
        font-size: 11px;
    }

    .items th {
        background: #007bff;
        color: #fff;
        text-align: left;
    }

    .text-right {
        text-align: right;
    }

    .text-center {
        text-align: center;
    }

    .summary {
        margin-top: 10px;
        float: right;
        width: 320px;
        border: 1px solid #007bff;
        border-radius: 4px;
        padding: 8px;
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        padding: 4px 0;
        font-size: 12px;
    }

    .summary-row.total {
        border-top: 1px solid #ccc;
        margin-top: 6px;
        padding-top: 8px;
        font-weight: bold;
    }

    .footer {
        clear: both;
        margin-top: 26px;
        border-top: 1px solid #d9d9d9;
        padding-top: 8px;
        font-size: 10px;
        color: #666;
        text-align: center;
    }

    @media print {
        .print-btn {
            display: none;
        }

        @page {
            size: A4;
            margin: 6mm;
        }

        .sheet {
            width: 100%;
            margin: 0;
            padding: 0;
        }
    }
    </style>
</head>

<body>
    <button class="print-btn" onclick="window.print()">Print</button>

    <div class="sheet">
        <div class="header">
            <div class="header-brand">
                <img src="images/Nexenstial TM.jpg" alt="Hospital Logo" class="header-logo">
                <h1><?= htmlspecialchars($_SESSION['college_name'] ?? 'Hospital Management System') ?></h1>
            </div>
            <h2>CathLab Procedure Report</h2>
            <p>Generated on: <?= $print_time ?></p>
        </div>

        <div class="block">
            <div class="block-title">Procedure Information</div>
            <div class="grid">
                <div class="row">
                    <div class="cell"><span class="label">Procedure Code:</span> <?= htmlspecialchars($procedure['procedure_code'] ?? '-') ?></div>
                    <div class="cell"><span class="label">Procedure Type:</span> <?= htmlspecialchars($procedure['procedure_type'] ?? '-') ?></div>
                </div>
                <div class="row">
                    <div class="cell"><span class="label">Procedure Date:</span> <?= $procedure_date ?></div>
                    <div class="cell"><span class="label">Procedure Time:</span> <?= $procedure_time ?></div>
                </div>
                <div class="row">
                    <div class="cell"><span class="label">Doctor:</span> <?= htmlspecialchars($procedure['doctor_name'] ?? '-') ?></div>
                    <div class="cell"><span class="label">Duration (Minutes):</span> <?= (int)($procedure['duration_minutes'] ?? 0) ?></div>
                </div>
                <div class="row">
                    <div class="cell"><span class="label">Procedure Status:</span> <?= htmlspecialchars(ucfirst((string)($procedure['status'] ?? 'pending'))) ?></div>
                    <div class="cell"><span class="label">Billing Status:</span> <?= htmlspecialchars(ucfirst(str_replace('_', ' ', (string)($procedure['billed_status'] ?? 'pending')))) ?></div>
                </div>
            </div>
        </div>

        <div class="block">
            <div class="block-title">Patient Information</div>
            <div class="grid">
                <div class="row">
                    <div class="cell"><span class="label">Patient Name:</span> <?= htmlspecialchars($procedure['patient_name'] ?? '-') ?></div>
                    <div class="cell"><span class="label">Patient ID:</span> <?= htmlspecialchars((string)($procedure['patient_id'] ?? '-')) ?></div>
                </div>
                <div class="row">
                    <div class="cell"><span class="label">Admission ID:</span> <?= htmlspecialchars((string)($procedure['admission_id'] ?? '-')) ?></div>
                    <div class="cell"><span class="label">Created By:</span> <?= htmlspecialchars((string)($procedure['created_by'] ?? '-')) ?></div>
                </div>
            </div>
        </div>

        <div class="block">
            <div class="block-title">Procedure Items</div>
            <table class="items">
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
                        <td class="text-right"><?= number_format($amount, 2) ?></td>
                        <td><?= htmlspecialchars(ucfirst((string)($item['status'] ?? 'pending'))) ?></td>
                    </tr>
                    <?php }
                    } else { ?>
                    <tr>
                        <td colspan="9" class="text-center">No item records found for this procedure.</td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>

            <div class="summary">
                <div class="summary-row">
                    <span>Total Line Items</span>
                    <strong><?= count($items) ?></strong>
                </div>
                <div class="summary-row">
                    <span>Total Quantity</span>
                    <strong><?= number_format($total_qty, 2) ?></strong>
                </div>
                <div class="summary-row">
                    <span>Total Cost</span>
                    <strong>₹ <?= number_format($total_cost, 2) ?></strong>
                </div>
                <div class="summary-row total">
                    <span>Billed Amount</span>
                    <strong>₹ <?= number_format((float)($procedure['billed_amount'] ?? 0), 2) ?></strong>
                </div>
            </div>
        </div>

        <div class="block">
            <div class="block-title">Observations</div>
            <?= nl2br(htmlspecialchars($procedure['observations'] ?? '-')) ?>
        </div>

        <div class="footer">
            Printed by <?= htmlspecialchars($_SESSION['name'] ?? 'User') ?> | This is a system-generated document.
        </div>
    </div>
</body>

</html>

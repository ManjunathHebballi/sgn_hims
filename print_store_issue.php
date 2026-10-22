<?php 
require_once(__DIR__ . '/model/session_config.php');
date_default_timezone_set("Asia/Kolkata");  
include('model/storeClass.php');
$newStore = new store;

if(!isset($_REQUEST['id'])) {
    echo "<script>window.location='store_issue_list.php'</script>";
    exit();
}

$issue_id = $_REQUEST['id'];
$issue_res = $newStore->getIssue($issue_id);
$issue = mysqli_fetch_assoc($issue_res);

if(!$issue) {
    echo "<script>alert('Issue not found!'); window.location='store_issue_list.php'</script>";
    exit();
}

$items_res = $newStore->getIssueItems($issue_id);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Issue - <?= $issue['issue_number']?></title>

    <style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: Arial, sans-serif;
        font-size: 12px;
        line-height: 1.4;
        color: #000;
        background: #fff;
    }

    .issue-wrapper {
        max-width: 210mm;
        margin: 0 auto;
        padding: 10mm;
        background: white;
    }

    .header {
        display: flex;
        align-items: center;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 3px solid #667eea;
    }

    .header-logo {
        max-width: 80px;
        max-height: 80px;
        margin-right: 20px;
        flex-shrink: 0;
    }

    .header-content {
        flex-grow: 1;
        text-align: center;
    }

    .header h1 {
        color: #667eea;
        font-size: 24px;
        margin: 5px 0;
    }

    .header h2 {
        color: #333;
        font-size: 18px;
        margin: 5px 0;
    }

    .header p {
        font-size: 11px;
        color: #666;
        margin: 3px 0;
    }

    .issue-title {
        background: #667eea;
        color: white;
        padding: 10px;
        text-align: center;
        font-size: 16px;
        font-weight: bold;
        margin: 20px 0;
        border-radius: 5px;
    }

    .info-section {
        display: table;
        width: 100%;
        margin-bottom: 20px;
    }

    .info-card {
        display: table-cell;
        width: 48%;
        vertical-align: top;
        border: 1px solid #ddd;
        padding: 12px;
        background: #f9f9f9;
    }

    .info-card:first-child {
        margin-right: 4%;
    }

    .info-card h3 {
        color: #667eea;
        font-size: 13px;
        margin-bottom: 10px;
        padding-bottom: 5px;
        border-bottom: 2px solid #667eea;
    }

    .info-row {
        margin: 6px 0;
        font-size: 11px;
    }

    .info-label {
        font-weight: bold;
        display: inline-block;
        width: 100px;
        color: #333;
    }

    .info-value {
        color: #555;
    }

    .items-table {
        width: 100%;
        border-collapse: collapse;
        margin: 20px 0;
        font-size: 10px;
    }

    .items-table thead {
        background: #667eea;
        color: white;
    }

    .items-table th {
        padding: 8px 5px;
        text-align: left;
        font-weight: bold;
        border: 1px solid #ddd;
    }

    .items-table td {
        padding: 6px 5px;
        border: 1px solid #ddd;
    }

    .items-table tbody tr:nth-child(even) {
        background-color: #f9f9f9;
    }

    .items-table tfoot {
        background: #f0f0f0;
        font-weight: bold;
    }

    .text-right {
        text-align: right;
    }

    .text-center {
        text-align: center;
    }

    .summary-box {
        float: right;
        width: 250px;
        border: 2px solid #667eea;
        padding: 10px;
        margin-top: 10px;
        background: #f9f9f9;
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        padding: 5px 0;
        font-size: 12px;
    }

    .summary-row.total {
        border-top: 2px solid #667eea;
        padding-top: 8px;
        margin-top: 8px;
        font-size: 14px;
        font-weight: bold;
        color: #667eea;
    }

    .signature-section {
        display: table;
        width: 100%;
        margin-top: 40px;
    }

    .signature-box {
        display: table-cell;
        width: 48%;
        text-align: center;
        padding: 10px;
    }

    .signature-line {
        border-top: 1px solid #000;
        margin-top: 50px;
        padding-top: 5px;
        font-size: 11px;
        font-weight: bold;
    }

    .footer {
        margin-top: 30px;
        padding-top: 15px;
        border-top: 2px solid #667eea;
        text-align: center;
        font-size: 10px;
        color: #666;
    }

    .print-btn {
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 10px 20px;
        background: #667eea;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 14px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
    }

    .print-btn:hover {
        background: #5568d3;
    }

    .status-badge {
        background: #28a745;
        color: white;
        padding: 3px 10px;
        border-radius: 3px;
        font-size: 11px;
        font-weight: bold;
    }

    .status-badge.cancelled {
        background: #dc3545;
    }

    @media print {
        .print-btn {
            display: none;
        }

        @page {
            size: A4;
            margin: 5mm;
        }

        body {
            margin: 0;
            padding: 0;
        }

        .issue-wrapper {
            max-width: 100%;
            padding: 0;
        }

        .info-card {
            page-break-inside: avoid;
        }

        .items-table {
            page-break-inside: avoid;
        }

        .signature-section {
            page-break-inside: avoid;
        }
    }
    </style>

</head>

<body>

    <button class="print-btn" onclick="window.print()">
        <i class="fa fa-print"></i> Print Issue
    </button>

    <div class="issue-wrapper">

        <!-- Header -->
        <div class="header">
            <img src="images/logo.png" class="header-logo" alt="Hospital Logo">
            <div class="header-content">
                <h1><?= isset($_SESSION['college_name']) ? $_SESSION['college_name'] : 'Hospital Name'?></h1>
                <h2>Store Issue Document</h2>
                <p><?= isset($_SESSION['college_location']) ? $_SESSION['college_location'] : 'Hospital Address'?></p>
            </div>
        </div>

        <!-- Issue Title -->
        <div class="issue-title">
            ISSUE NO: <?= $issue['issue_number']?>
            <span style="float: right;">DATE: <?= date('d-M-Y', strtotime($issue['issue_date']))?></span>
        </div>

        <!-- Info Cards -->
        <div class="info-section">
            <div class="info-card">
                <h3>ISSUE INFORMATION</h3>
                <div class="info-row">
                    <span class="info-label">Issue Number:</span>
                    <span class="info-value"><?= $issue['issue_number']?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Issue Date:</span>
                    <span class="info-value"><?= date('d-M-Y', strtotime($issue['issue_date']))?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Department:</span>
                    <span class="info-value"><?= $issue['department']?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Status:</span>
                    <span class="status-badge <?= $issue['status'] != 'ISSUED' ? 'cancelled' : '' ?>">
                        <?= $issue['status']?>
                    </span>
                </div>
            </div>

            <div class="info-card">
                <h3>ISSUE DETAILS</h3>
                <?php if($issue['requested_by']): ?>
                <div class="info-row">
                    <span class="info-label">Requested By:</span>
                    <span class="info-value"><?= $issue['requested_by']?></span>
                </div>
                <?php endif; ?>
                <div class="info-row">
                    <span class="info-label">Issued By:</span>
                    <span class="info-value"><?= $_SESSION['name'] ?? 'System'?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Issue Time:</span>
                    <span class="info-value"><?= date('d-M-Y H:i', strtotime($issue['created_at']))?></span>
                </div>
                <?php if($issue['remarks']): ?>
                <div class="info-row">
                    <span class="info-label">Remarks:</span>
                    <span class="info-value"><?= $issue['remarks']?></span>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Items Table -->
        <table class="items-table">
            <thead>
                <tr>
                    <th width="5%">S.No</th>
                    <th width="15%">Item Code</th>
                    <th width="30%">Item Name</th>
                    <th width="10%">Unit</th>
                    <th width="10%">Quantity</th>
                    <th width="15%">Batch No</th>
                    <th width="15%">Serial No</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $sno = 1;
                $total_qty = 0;
                while($item = mysqli_fetch_assoc($items_res)) {
                    $total_qty += $item['quantity'];
                ?>
                <tr>
                    <td class="text-center"><?= $sno++?></td>
                    <td><?= $item['item_code']?></td>
                    <td><?= $item['item_name']?></td>
                    <td class="text-center"><?= $item['unit_name']?></td>
                    <td class="text-right"><?= number_format($item['quantity'], 2)?></td>
                    <td class="text-center"><?= $item['batch_no'] ? $item['batch_no'] : '-'?></td>
                    <td class="text-center"><?= $item['serial_no'] ? $item['serial_no'] : '-'?></td>
                </tr>
                <?php } ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4" class="text-right"><strong>TOTAL ITEMS ISSUED:</strong></td>
                    <td class="text-right"><strong><?= number_format($total_qty, 2)?></strong></td>
                    <td colspan="2"></td>
                </tr>
            </tfoot>
        </table>

        <!-- Signature Section -->
        <div class="signature-section">
            <div class="signature-box">
                <div class="signature-line">Issued By</div>
            </div>
            <div class="signature-box">
                <div class="signature-line">Received By</div>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>
                <strong>Created By:</strong> <?= $_SESSION['name']?> |
                <strong>Created On:</strong> <?= date('d-M-Y h:i A', strtotime($issue['created_at']))?>
            </p>
            <p style="margin-top: 5px;">
                <strong>Printed On:</strong> <?= date('d-M-Y h:i A')?>
            </p>
        </div>

    </div>

</body>

</html>

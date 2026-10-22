<?php 
require_once(__DIR__ . '/model/session_config.php');
date_default_timezone_set("Asia/Kolkata");  
include('model/storeClass.php');
$newStore = new store;

if(!isset($_REQUEST['id'])) {
    echo "<script>window.location='list_store_grn.php'</script>";
    exit();
}

$grn_id = $_REQUEST['id'];
$grn = $newStore->getGRNById($grn_id);

if(!$grn) {
    echo "<script>alert('GRN not found!'); window.location='list_store_grn.php'</script>";
    exit();
}

$grn_items = $newStore->getGRNItemsByGRNId($grn_id);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>GRN - <?= $grn['grn_number']?></title>

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

    .grn-wrapper {
        max-width: 210mm;
        margin: 0 auto;
        padding: 10mm;
        background: white;
    }

    .header {
        text-align: center;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 3px solid #11998e;
    }

    .header-logo {
        max-width: 80px;
        max-height: 80px;
        margin-bottom: 10px;
    }

    .header h1 {
        color: #11998e;
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

    .grn-title {
        background: #11998e;
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
        color: #11998e;
        font-size: 13px;
        margin-bottom: 10px;
        padding-bottom: 5px;
        border-bottom: 2px solid #11998e;
    }

    .info-row {
        margin: 6px 0;
        font-size: 11px;
    }

    .info-label {
        font-weight: bold;
        display: inline-block;
        width: 120px;
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
        background: #11998e;
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
        border: 2px solid #11998e;
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
        border-top: 2px solid #11998e;
        padding-top: 8px;
        margin-top: 8px;
        font-size: 14px;
        font-weight: bold;
        color: #11998e;
    }

    .terms-section {
        clear: both;
        margin-top: 30px;
        padding-top: 15px;
        border-top: 1px dashed #ccc;
    }

    .terms-section h4 {
        color: #11998e;
        font-size: 13px;
        margin-bottom: 8px;
    }

    .terms-section ul {
        margin-left: 20px;
        font-size: 10px;
        line-height: 1.6;
    }

    .signature-section {
        display: table;
        width: 100%;
        margin-top: 40px;
    }

    .signature-box {
        display: table-cell;
        width: 30%;
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
        border-top: 2px solid #11998e;
        text-align: center;
        font-size: 10px;
        color: #666;
    }

    .print-btn {
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 10px 20px;
        background: #11998e;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 14px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
    }

    .print-btn:hover {
        background: #0e7d6f;
    }

    .status-badge {
        background: #28a745;
        color: white;
        padding: 3px 10px;
        border-radius: 3px;
        font-size: 11px;
        font-weight: bold;
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

        .grn-wrapper {
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
        <i class="fa fa-print"></i> Print GRN
    </button>

    <div class="grn-wrapper">

        <!-- Header -->
        <div class="header">
            <img src="images/logo.png" class="header-logo" alt="Hospital Logo">
            <h1><?= isset($_SESSION['college_name']) ? $_SESSION['college_name'] : 'Hospital Name'?></h1>
            <h2>Goods Receipt Note (GRN)</h2>
            <p><?= isset($_SESSION['college_location']) ? $_SESSION['college_location'] : 'Hospital Address'?></p>
        </div>

        <!-- GRN Title -->
        <div class="grn-title">
            GRN NO: <?= $grn['grn_number']?>
            <span style="float: right;">DATE: <?= date('d-M-Y', strtotime($grn['received_date']))?></span>
        </div>

        <!-- Info Cards -->
        <div class="info-section">
            <div class="info-card">
                <h3>SUPPLIER DETAILS</h3>
                <div class="info-row">
                    <span class="info-label">Supplier Name:</span>
                    <span class="info-value"><?= $grn['supplier_name']?></span>
                </div>
                <?php if(isset($grn['phone']) && $grn['phone']): ?>
                <div class="info-row">
                    <span class="info-label">Phone:</span>
                    <span class="info-value"><?= $grn['phone']?></span>
                </div>
                <?php endif; ?>
                <?php if(isset($grn['email']) && $grn['email']): ?>
                <div class="info-row">
                    <span class="info-label">Email:</span>
                    <span class="info-value"><?= $grn['email']?></span>
                </div>
                <?php endif; ?>
                <?php if(isset($grn['address']) && $grn['address']): ?>
                <div class="info-row">
                    <span class="info-label">Address:</span>
                    <span class="info-value"><?= $grn['address']?></span>
                </div>
                <?php endif; ?>
            </div>

            <div class="info-card">
                <h3>GRN INFORMATION</h3>
                <div class="info-row">
                    <span class="info-label">GRN Date:</span>
                    <span class="info-value"><?= date('d-M-Y', strtotime($grn['received_date']))?></span>
                </div>
                <?php if($grn['po_number']): ?>
                <div class="info-row">
                    <span class="info-label">PO Reference:</span>
                    <span class="info-value"><?= $grn['po_number']?></span>
                </div>
                <?php endif; ?>
                <?php if($grn['invoice_number']): ?>
                <div class="info-row">
                    <span class="info-label">Invoice Number:</span>
                    <span class="info-value"><?= $grn['invoice_number']?></span>
                </div>
                <?php endif; ?>
                <?php if($grn['invoice_date']): ?>
                <div class="info-row">
                    <span class="info-label">Invoice Date:</span>
                    <span class="info-value"><?= date('d-M-Y', strtotime($grn['invoice_date']))?></span>
                </div>
                <?php endif; ?>
                <div class="info-row">
                    <span class="info-label">Status:</span>
                    <span class="status-badge">RECEIVED</span>
                </div>
            </div>
        </div>

        <!-- Items Table -->
        <table class="items-table">
            <thead>
                <tr>
                    <th width="4%">S.No</th>
                    <th width="18%">Item Name</th>
                    <th width="8%">Unit</th>
                    <th width="9%">Quantity</th>
                    <th width="9%">Rate</th>
                    <th width="10%">Amount</th>
                    <th width="6%">GST %</th>
                    <th width="10%">GST Amt</th>
                    <th width="10%">Net Amt</th>
                    <th width="8%">Batch No</th>
                    <th width="8%">Expiry Date</th>
                    <th width="8%">Serial No</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $sno = 1;
                $total_qty = 0;
                $total_amount = 0;
                $total_gst_amount = 0;
                $total_net_amount = 0;
                while($item = mysqli_fetch_assoc($grn_items)) {
                    $total_qty += $item['quantity'];
                    $item_amount = isset($item['amount']) ? (float)$item['amount'] : 0;
                    $item_gst_percent = isset($item['gst_percent']) ? (float)$item['gst_percent'] : 0;
                    $item_gst_amount = isset($item['gst_amount']) ? (float)$item['gst_amount'] : 0;
                    $item_net_amount = isset($item['net_amount']) && $item['net_amount'] !== null ? (float)$item['net_amount'] : ($item_amount + $item_gst_amount);
                    $total_amount += $item_amount;
                    $total_gst_amount += $item_gst_amount;
                    $total_net_amount += $item_net_amount;
                ?>
                <tr>
                    <td class="text-center"><?= $sno++?></td>
                    <td><?= $item['item_name']?></td>
                    <td class="text-center"><?= $item['unit_name']?></td>
                    <td class="text-right"><?= number_format($item['quantity'], 2)?></td>
                    <td class="text-right">₹<?= number_format($item['rate'], 2)?></td>
                    <td class="text-right">₹<?= number_format($item_amount, 2)?></td>
                    <td class="text-right"><?= number_format($item_gst_percent, 2)?>%</td>
                    <td class="text-right">₹<?= number_format($item_gst_amount, 2)?></td>
                    <td class="text-right">₹<?= number_format($item_net_amount, 2)?></td>
                    <td class="text-center"><?= $item['batch_no'] ? $item['batch_no'] : '-'?></td>
                    <td class="text-center">
                        <?= $item['expiry_date'] ? date('d-M-Y', strtotime($item['expiry_date'])) : '-'?></td>
                    <td class="text-center"><?= $item['serial_no'] ? $item['serial_no'] : '-'?></td>
                </tr>
                <?php } ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" class="text-right"><strong>TOTAL:</strong></td>
                    <td class="text-right"><strong><?= number_format($total_qty, 2)?></strong></td>
                    <td></td>
                    <td class="text-right"><strong>₹<?= number_format($total_amount, 2)?></strong></td>
                    <td></td>
                    <td class="text-right"><strong>₹<?= number_format($total_gst_amount, 2)?></strong></td>
                    <td class="text-right"><strong>₹<?= number_format($total_net_amount, 2)?></strong></td>
                    <td colspan="3"></td>
                </tr>
            </tfoot>
        </table>

        <!-- Summary Box -->
        <div class="summary-box">
            <div class="summary-row">
                <span>TOTAL AMOUNT:</span>
                <span>₹<?= number_format($total_amount, 2)?></span>
            </div>
            <div class="summary-row">
                <span>TOTAL GST:</span>
                <span>₹<?= number_format($total_gst_amount, 2)?></span>
            </div>
            <div class="summary-row total">
                <span>TOTAL NET AMOUNT:</span>
                <span>₹<?= number_format($total_net_amount, 2)?></span>
            </div>
        </div>

        <!-- Remarks -->
        <?php if($grn['remarks']): ?>
        <div style="clear: both; margin-top: 20px; padding: 10px; background: #f9f9f9; border-left: 3px solid #11998e;">
            <strong>Remarks:</strong> <?= $grn['remarks']?>
        </div>
        <?php endif; ?>

        <!-- Terms & Conditions -->
        <div class="terms-section">
            <h4>Terms & Conditions:</h4>
            <ul>
                <li>All items received have been inspected and verified against the purchase order</li>
                <li>Batch numbers and expiry dates have been recorded for all applicable items</li>
                <li>Any discrepancies or damages must be reported within 24 hours</li>
                <li>Stock has been updated in the inventory management system</li>
            </ul>
        </div>

        <!-- Signature Section -->
        <div class="signature-section">
            <div class="signature-box">
                <div class="signature-line">Received By</div>
            </div>
            <div class="signature-box">
                <div class="signature-line">Verified By</div>
            </div>
            <div class="signature-box">
                <div class="signature-line">Authorized By</div>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>
                <strong>Created By:</strong> <?= $_SESSION['name']?> |
                <strong>Created On:</strong> <?= date('d-M-Y h:i A', strtotime($grn['created_at']))?>
            </p>
            <p style="margin-top: 5px;">
                <strong>Printed On:</strong> <?= date('d-M-Y h:i A')?>
            </p>
        </div>

    </div>

</body>

</html>
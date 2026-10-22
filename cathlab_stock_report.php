<?php
require_once(__DIR__ . '/model/session_config.php');
date_default_timezone_set("Asia/Kolkata");
include('model/storeClass.php');

$newStore = new store;

function toArray($result) {
    $rows = array();
    if ($result && $result instanceof mysqli_result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $rows[] = $row;
        }
    }
    return $rows;
}

$reportType = isset($_GET['report']) ? trim($_GET['report']) : 'current_stock';
$validReports = array('current_stock', 'low_stock', 'expiry_alerts', 'ledger');
if (!in_array($reportType, $validReports, true)) {
    $reportType = 'current_stock';
}

$items = toArray($newStore->getCathLabItems());
$lowStock = toArray($newStore->getCathLabLowStockItems());
$expiryAlerts = toArray($newStore->getCathLabExpiryAlerts());

$selectedItemId = isset($_GET['item_id']) ? (int)$_GET['item_id'] : 0;
$ledgerRows = array();
if ($selectedItemId > 0) {
    $ledgerRows = toArray($newStore->getItemStockLedger($selectedItemId));
}

$totalStockQty = 0;
foreach ($items as $item) {
    $totalStockQty += (float)($item['current_stock'] ?? 0);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="theme-color" content="#319197">
    <title>CathLab Stock Report</title>
    <link rel="icon" type="image/png" sizes="16x16" href="../images/favicon.png">
    <link href="css/style.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <style>
    .report-nav .btn {
        margin: 3px 3px 0 0;
    }

    .report-card {
        border-radius: 8px;
    }

    .summary-badge {
        font-size: 14px;
        padding: 8px 12px;
    }

    .expiring-soon {
        background-color: #fff3cd;
    }

    .expired {
        background-color: #f8d7da;
    }

    @media print {
        #page-topbar,
        .topnav,
        .report-nav,
        .no-print,
        .btn {
            display: none !important;
        }

        .content-body {
            margin-top: 0 !important;
        }
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

    <div id="main-wrapper">
        <?php include('leftbar.php'); ?>

        <div class="content-body">
            <div class="container-fluid">
                <div class="row mb-3">
                    <div class="col-md-8">
                        <h4 class="text-uppercase mb-1">CathLab Stock Report</h4>
                        <small class="text-muted">Stock position, low stock, expiry alerts and stock ledger</small>
                    </div>
                    <div class="col-md-4 text-md-right mt-2 mt-md-0">
                        <span class="badge badge-primary summary-badge">Total Items: <?= count($items) ?></span>
                        <span class="badge badge-success summary-badge">Total Qty: <?= number_format($totalStockQty, 2) ?></span>
                    </div>
                </div>

                <div class="row mb-3 no-print">
                    <div class="col-md-12 report-nav">
                        <a href="?report=current_stock" class="btn btn-sm <?= $reportType === 'current_stock' ? 'btn-primary' : 'btn-outline-primary' ?>">Current Stock</a>
                        <a href="?report=low_stock" class="btn btn-sm <?= $reportType === 'low_stock' ? 'btn-warning' : 'btn-outline-warning' ?>">Low Stock</a>
                        <a href="?report=expiry_alerts" class="btn btn-sm <?= $reportType === 'expiry_alerts' ? 'btn-danger' : 'btn-outline-danger' ?>">Expiry Alerts</a>
                        <a href="?report=ledger" class="btn btn-sm <?= $reportType === 'ledger' ? 'btn-info' : 'btn-outline-info' ?>">Item Ledger</a>
                    </div>
                </div>

                <?php if ($reportType === 'current_stock') { ?>
                <div class="card report-card">
                    <div class="card-header">
                        <h5 class="mb-0 d-inline">Current Stock</h5>
                        <div class="float-right no-print">
                            <button class="btn btn-sm btn-success" onclick="exportTableToCSV('current_stock_table','cathlab_current_stock.csv')">Export CSV</button>
                            <button class="btn btn-sm btn-primary" onclick="window.print()">Print</button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover table-sm" id="current_stock_table">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>Sl No</th>
                                        <th>Item Code</th>
                                        <th>Item Name</th>
                                        <th>Category</th>
                                        <th>Unit</th>
                                        <th class="text-right">Current Stock</th>
                                        <th class="text-right">Reorder Level</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (count($items) > 0) {
                                        $sn = 1;
                                        foreach ($items as $row) { ?>
                                    <tr>
                                        <td><?= $sn++ ?></td>
                                        <td><?= htmlspecialchars($row['item_code'] ?? '') ?></td>
                                        <td><?= htmlspecialchars($row['item_name'] ?? '') ?></td>
                                        <td><?= htmlspecialchars($row['category'] ?? '') ?></td>
                                        <td><?= htmlspecialchars($row['unit'] ?? '') ?></td>
                                        <td class="text-right"><strong><?= number_format((float)($row['current_stock'] ?? 0), 2) ?></strong></td>
                                        <td class="text-right"><?= number_format((float)($row['min_stock'] ?? 0), 2) ?></td>
                                    </tr>
                                    <?php }
                                    } else { ?>
                                    <tr>
                                        <td colspan="7" class="text-center text-muted">No stock data found.</td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <?php } ?>

                <?php if ($reportType === 'low_stock') { ?>
                <div class="card report-card">
                    <div class="card-header bg-warning">
                        <h5 class="mb-0 d-inline">Low Stock Alert</h5>
                        <div class="float-right no-print">
                            <button class="btn btn-sm btn-dark" onclick="exportTableToCSV('low_stock_table','cathlab_low_stock.csv')">Export CSV</button>
                            <button class="btn btn-sm btn-light" onclick="window.print()">Print</button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover table-sm" id="low_stock_table">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>Sl No</th>
                                        <th>Item Code</th>
                                        <th>Item Name</th>
                                        <th>Category</th>
                                        <th>Unit</th>
                                        <th class="text-right">Current Stock</th>
                                        <th class="text-right">Reorder Level</th>
                                        <th class="text-right">Shortfall</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (count($lowStock) > 0) {
                                        $sn = 1;
                                        foreach ($lowStock as $row) {
                                            $current = (float)($row['current_stock'] ?? 0);
                                            $reorder = (float)($row['reorder_level'] ?? 0);
                                            $shortfall = $reorder - $current;
                                        ?>
                                    <tr>
                                        <td><?= $sn++ ?></td>
                                        <td><?= htmlspecialchars($row['item_code'] ?? '') ?></td>
                                        <td><?= htmlspecialchars($row['item_name'] ?? '') ?></td>
                                        <td><?= htmlspecialchars($row['category'] ?? '') ?></td>
                                        <td><?= htmlspecialchars($row['unit'] ?? '') ?></td>
                                        <td class="text-right text-danger"><strong><?= number_format($current, 2) ?></strong></td>
                                        <td class="text-right"><?= number_format($reorder, 2) ?></td>
                                        <td class="text-right text-primary"><strong><?= number_format($shortfall > 0 ? $shortfall : 0, 2) ?></strong></td>
                                    </tr>
                                    <?php }
                                    } else { ?>
                                    <tr>
                                        <td colspan="8" class="text-center text-muted">No low stock items found.</td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <?php } ?>

                <?php if ($reportType === 'expiry_alerts') { ?>
                <div class="card report-card">
                    <div class="card-header bg-danger text-white">
                        <h5 class="mb-0 d-inline">Expiry Alerts</h5>
                        <div class="float-right no-print">
                            <button class="btn btn-sm btn-light" onclick="exportTableToCSV('expiry_table','cathlab_expiry_alerts.csv')">Export CSV</button>
                            <button class="btn btn-sm btn-dark" onclick="window.print()">Print</button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover table-sm" id="expiry_table">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>Sl No</th>
                                        <th>Item Name</th>
                                        <th>Batch No</th>
                                        <th>Expiry Date</th>
                                        <th class="text-right">Available Qty</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (count($expiryAlerts) > 0) {
                                        $sn = 1;
                                        foreach ($expiryAlerts as $row) {
                                            $status = strtoupper(trim((string)($row['status'] ?? '')));
                                            $trClass = $status === 'EXPIRED' ? 'expired' : 'expiring-soon';
                                            $expDate = !empty($row['expiry_date']) ? date('d-m-Y', strtotime($row['expiry_date'])) : 'N/A';
                                        ?>
                                    <tr class="<?= $trClass ?>">
                                        <td><?= $sn++ ?></td>
                                        <td><?= htmlspecialchars($row['item_name'] ?? '') ?></td>
                                        <td><?= htmlspecialchars($row['batch_number'] ?? '') ?></td>
                                        <td><?= $expDate ?></td>
                                        <td class="text-right"><?= number_format((float)($row['available_quantity'] ?? 0), 2) ?></td>
                                        <td><strong><?= htmlspecialchars($status ?: 'N/A') ?></strong></td>
                                    </tr>
                                    <?php }
                                    } else { ?>
                                    <tr>
                                        <td colspan="6" class="text-center text-muted">No expiry alerts found.</td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <?php } ?>

                <?php if ($reportType === 'ledger') { ?>
                <div class="card report-card">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0">Item Stock Ledger</h5>
                    </div>
                    <div class="card-body">
                        <form method="GET" class="form-inline mb-3 no-print">
                            <input type="hidden" name="report" value="ledger">
                            <label class="mr-2"><strong>Select Item:</strong></label>
                            <select name="item_id" class="form-control mr-2" required>
                                <option value="">-- Select --</option>
                                <?php foreach ($items as $item) { ?>
                                <option value="<?= (int)$item['id'] ?>" <?= $selectedItemId === (int)$item['id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars(($item['item_code'] ?? '') . ' - ' . ($item['item_name'] ?? '')) ?>
                                </option>
                                <?php } ?>
                            </select>
                            <button type="submit" class="btn btn-primary btn-sm mr-2">View Ledger</button>
                            <?php if ($selectedItemId > 0) { ?>
                            <button type="button" class="btn btn-success btn-sm mr-2" onclick="exportTableToCSV('ledger_table','cathlab_item_ledger.csv')">Export CSV</button>
                            <button type="button" class="btn btn-light btn-sm" onclick="window.print()">Print</button>
                            <?php } ?>
                        </form>

                        <?php if ($selectedItemId > 0) { ?>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover table-sm" id="ledger_table">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>Sl No</th>
                                        <th>Date & Time</th>
                                        <th>Item</th>
                                        <th>Batch</th>
                                        <th>Transaction</th>
                                        <th class="text-right">Qty In</th>
                                        <th class="text-right">Qty Out</th>
                                        <th class="text-right">Balance</th>
                                        <th>Reference</th>
                                        <th>Remarks</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (count($ledgerRows) > 0) {
                                        $sn = 1;
                                        foreach ($ledgerRows as $row) {
                                            $createdAt = !empty($row['created_at']) ? date('d-m-Y H:i', strtotime($row['created_at'])) : 'N/A';
                                            $ref = trim((string)($row['reference_type'] ?? '')) . ' #' . trim((string)($row['reference_id'] ?? ''));
                                            $movementQty = (float)($row['quantity'] ?? 0);
                                            $outTypes = array('PROCEDURE_RESERVE', 'PROCEDURE_CONSUME', 'BILLING_DEDUCT', 'BILLING_OUT', 'DISCARD', 'TRANSFER');
                                            $isOut = in_array((string)($row['transaction_type'] ?? ''), $outTypes, true);
                                            $qtyIn = isset($row['quantity_in']) ? (float)$row['quantity_in'] : ($isOut ? 0 : $movementQty);
                                            $qtyOut = isset($row['quantity_out']) ? (float)$row['quantity_out'] : ($isOut ? $movementQty : 0);
                                            $balanceDisplay = isset($row['balance_quantity']) ? number_format((float)$row['balance_quantity'], 2) : '-';
                                        ?>
                                    <tr>
                                        <td><?= $sn++ ?></td>
                                        <td><?= $createdAt ?></td>
                                        <td><?= htmlspecialchars($row['item_name'] ?? '') ?></td>
                                        <td><?= htmlspecialchars($row['batch_number'] ?? '') ?></td>
                                        <td><?= htmlspecialchars($row['transaction_type'] ?? '') ?></td>
                                        <td class="text-right text-success"><?= number_format($qtyIn, 2) ?></td>
                                        <td class="text-right text-danger"><?= number_format($qtyOut, 2) ?></td>
                                        <td class="text-right"><strong><?= $balanceDisplay ?></strong></td>
                                        <td><?= htmlspecialchars(trim($ref, ' #')) ?></td>
                                        <td><?= htmlspecialchars($row['remarks'] ?? '') ?></td>
                                    </tr>
                                    <?php }
                                    } else { ?>
                                    <tr>
                                        <td colspan="10" class="text-center text-muted">No ledger entries found for selected item.</td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                        <?php } else { ?>
                        <div class="alert alert-info mb-0">Select an item and click <strong>View Ledger</strong> to load stock movements.</div>
                        <?php } ?>
                    </div>
                </div>
                <?php } ?>

            </div>
        </div>
    </div>

    <script>
    function exportTableToCSV(tableId, filename) {
        const table = document.getElementById(tableId);
        if (!table) {
            return;
        }
        const rows = table.querySelectorAll('tr');
        const csv = [];

        rows.forEach(function(row) {
            const cols = row.querySelectorAll('th, td');
            const data = [];
            cols.forEach(function(col) {
                let text = col.innerText.replace(/\n/g, ' ').replace(/\s+/g, ' ').trim();
                text = '"' + text.replace(/"/g, '""') + '"';
                data.push(text);
            });
            csv.push(data.join(','));
        });

        const csvString = csv.join('\n');
        const blob = new Blob([csvString], {
            type: 'text/csv;charset=utf-8;'
        });
        const link = document.createElement('a');
        const url = URL.createObjectURL(blob);

        link.setAttribute('href', url);
        link.setAttribute('download', filename);
        link.style.visibility = 'hidden';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }
    </script>

    <script src="plugins/common/common.min.js"></script>
    <script src="js/custom.min.js"></script>
    <script src="js/settings.js"></script>
    <script src="js/gleek.js"></script>
    <script src="js/styleSwitcher.js"></script>
</body>

</html>

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

$statusFilter = isset($_GET['status']) ? strtoupper(trim($_GET['status'])) : 'ALL';
$validStatuses = array('ALL', 'EXPIRED', 'EXPIRING_SOON');
if (!in_array($statusFilter, $validStatuses, true)) {
    $statusFilter = 'ALL';
}

$alerts = toArray($newStore->getCathLabExpiryAlerts());

if ($statusFilter !== 'ALL') {
    $alerts = array_values(array_filter($alerts, function ($row) use ($statusFilter) {
        return strtoupper(trim((string)($row['status'] ?? ''))) === $statusFilter;
    }));
}

$expiredCount = 0;
$expiringSoonCount = 0;
foreach ($alerts as $row) {
    $st = strtoupper(trim((string)($row['status'] ?? '')));
    if ($st === 'EXPIRED') {
        $expiredCount++;
    }
    if ($st === 'EXPIRING_SOON') {
        $expiringSoonCount++;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="theme-color" content="#319197">
    <title>CathLab Expiry Alerts</title>
    <link rel="icon" type="image/png" sizes="16x16" href="../images/favicon.png">
    <link href="css/style.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <style>
    .summary-badge {
        font-size: 14px;
        padding: 8px 12px;
    }

    .expired {
        background-color: #f8d7da;
    }

    .expiring-soon {
        background-color: #fff3cd;
    }

    @media print {
        #page-topbar,
        .topnav,
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
                    <div class="col-md-7">
                        <h4 class="text-uppercase mb-1">CathLab Expiry Alerts</h4>
                        <small class="text-muted">Batch-wise expiry monitoring for CathLab inventory</small>
                    </div>
                    <div class="col-md-5 text-md-right mt-2 mt-md-0">
                        <span class="badge badge-danger summary-badge">Expired: <?= $expiredCount ?></span>
                        <span class="badge badge-warning summary-badge">Expiring Soon: <?= $expiringSoonCount ?></span>
                        <span class="badge badge-primary summary-badge">Total: <?= count($alerts) ?></span>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header bg-danger text-white">
                        <h5 class="mb-0 d-inline">Expiry Alert Report</h5>
                        <div class="float-right no-print">
                            <button class="btn btn-sm btn-light" onclick="exportTableToCSV('expiry_alerts_table', 'cathlab_expiry_alerts.csv')">Export CSV</button>
                            <button class="btn btn-sm btn-dark" onclick="window.print()">Print</button>
                        </div>
                    </div>
                    <div class="card-body">
                        <form method="GET" class="form-inline mb-3 no-print">
                            <label class="mr-2"><strong>Status:</strong></label>
                            <select class="form-control mr-2" name="status">
                                <option value="ALL" <?= $statusFilter === 'ALL' ? 'selected' : '' ?>>All</option>
                                <option value="EXPIRED" <?= $statusFilter === 'EXPIRED' ? 'selected' : '' ?>>Expired</option>
                                <option value="EXPIRING_SOON" <?= $statusFilter === 'EXPIRING_SOON' ? 'selected' : '' ?>>Expiring Soon</option>
                            </select>
                            <button type="submit" class="btn btn-primary btn-sm">Apply</button>
                            <a href="cathlab_expiry_alerts.php" class="btn btn-outline-secondary btn-sm ml-2">Reset</a>
                        </form>

                        <div class="table-responsive">
                            <table class="table table-bordered table-hover table-sm" id="expiry_alerts_table">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>Sl No</th>
                                        <th>Item Code</th>
                                        <th>Item Name</th>
                                        <th>Batch No</th>
                                        <th>Store</th>
                                        <th>Expiry Date</th>
                                        <th class="text-right">Days to Expiry</th>
                                        <th class="text-right">Available Qty</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (count($alerts) > 0) {
                                        $sn = 1;
                                        foreach ($alerts as $row) {
                                            $status = strtoupper(trim((string)($row['status'] ?? '')));
                                            $daysToExpiry = (int)($row['days_to_expiry'] ?? 0);
                                            $rowClass = $status === 'EXPIRED' ? 'expired' : 'expiring-soon';
                                            $expiryDate = !empty($row['expiry_date']) ? date('d-m-Y', strtotime($row['expiry_date'])) : 'N/A';
                                    ?>
                                    <tr class="<?= $rowClass ?>">
                                        <td><?= $sn++ ?></td>
                                        <td><?= htmlspecialchars($row['item_code'] ?? '') ?></td>
                                        <td><?= htmlspecialchars($row['item_name'] ?? '') ?></td>
                                        <td><?= htmlspecialchars($row['batch_number'] ?? '') ?></td>
                                        <td><?= htmlspecialchars($row['store_name'] ?? '') ?></td>
                                        <td><?= $expiryDate ?></td>
                                        <td class="text-right"><?= $daysToExpiry ?></td>
                                        <td class="text-right"><?= number_format((float)($row['available_quantity'] ?? 0), 2) ?></td>
                                        <td><strong><?= htmlspecialchars($status ?: 'N/A') ?></strong></td>
                                    </tr>
                                    <?php }
                                    } else { ?>
                                    <tr>
                                        <td colspan="9" class="text-center text-muted">No expiry alerts found.</td>
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
        const blob = new Blob([csvString], { type: 'text/csv;charset=utf-8;' });
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

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

$selectedDate = isset($_GET['date']) ? trim($_GET['date']) : '';
$isValidDate = ($selectedDate !== '' && preg_match('/^\d{4}-\d{2}-\d{2}$/', $selectedDate));

$reportRows = $isValidDate
    ? toArray($newStore->getCathLabCostVsRevenue($selectedDate))
    : toArray($newStore->getCathLabCostVsRevenue());

$totalProcedures = 0;
$totalCost = 0;
$totalRevenue = 0;

foreach ($reportRows as $row) {
    $totalProcedures += (int)($row['total_procedures'] ?? 0);
    $totalCost += (float)($row['total_cost'] ?? 0);
    $totalRevenue += (float)($row['total_revenue'] ?? 0);
}

$totalMargin = $totalRevenue - $totalCost;
$marginPercent = $totalRevenue > 0 ? (($totalMargin / $totalRevenue) * 100) : 0;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="theme-color" content="#319197">
    <title>CathLab Cost vs Revenue</title>
    <link rel="icon" type="image/png" sizes="16x16" href="../images/favicon.png">
    <link href="css/style.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <style>
    .metric-card {
        border-radius: 8px;
        color: #fff;
        padding: 15px;
        margin-bottom: 15px;
    }

    .metric-card h6 {
        margin: 0 0 8px 0;
        opacity: 0.95;
    }

    .metric-value {
        font-size: 22px;
        font-weight: 700;
    }

    .bg-proc { background: linear-gradient(135deg, #007bff, #0062cc); }
    .bg-cost { background: linear-gradient(135deg, #dc3545, #b02a37); }
    .bg-rev { background: linear-gradient(135deg, #28a745, #1f8a37); }
    .bg-mar { background: linear-gradient(135deg, #17a2b8, #117a8b); }

    .negative-row {
        background-color: #f8d7da;
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
                    <div class="col-md-8">
                        <h4 class="text-uppercase mb-1">CathLab Cost vs Revenue</h4>
                        <small class="text-muted">Procedure-wise cost, revenue and margin analysis</small>
                    </div>
                    <div class="col-md-4 text-md-right mt-2 mt-md-0 no-print">
                        <button class="btn btn-success btn-sm" onclick="exportTableToCSV('cost_revenue_table','cathlab_cost_vs_revenue.csv')">Export CSV</button>
                        <button class="btn btn-primary btn-sm" onclick="window.print()">Print</button>
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col-md-3">
                        <div class="metric-card bg-proc">
                            <h6>Total Procedures</h6>
                            <div class="metric-value"><?= number_format($totalProcedures) ?></div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="metric-card bg-cost">
                            <h6>Total Cost</h6>
                            <div class="metric-value">₹ <?= number_format($totalCost, 2) ?></div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="metric-card bg-rev">
                            <h6>Total Revenue</h6>
                            <div class="metric-value">₹ <?= number_format($totalRevenue, 2) ?></div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="metric-card bg-mar">
                            <h6>Net Margin</h6>
                            <div class="metric-value">₹ <?= number_format($totalMargin, 2) ?> (<?= number_format($marginPercent, 2) ?>%)</div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0">Cost vs Revenue Report</h5>
                    </div>
                    <div class="card-body">
                        <form method="GET" class="form-inline mb-3 no-print">
                            <label class="mr-2"><strong>Procedure Date:</strong></label>
                            <input type="date" class="form-control mr-2" name="date" value="<?= htmlspecialchars($selectedDate) ?>">
                            <button type="submit" class="btn btn-primary btn-sm">Apply</button>
                            <a href="cathlab_cost_vs_revenue.php" class="btn btn-outline-secondary btn-sm ml-2">Reset</a>
                        </form>

                        <div class="table-responsive">
                            <table class="table table-bordered table-hover table-sm" id="cost_revenue_table">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>Sl No</th>
                                        <th>Procedure Date</th>
                                        <th class="text-right">Procedures</th>
                                        <th class="text-right">Total Cost (₹)</th>
                                        <th class="text-right">Total Revenue (₹)</th>
                                        <th class="text-right">Margin (₹)</th>
                                        <th class="text-right">Margin %</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (count($reportRows) > 0) {
                                        $sn = 1;
                                        foreach ($reportRows as $row) {
                                            $cost = (float)($row['total_cost'] ?? 0);
                                            $revenue = (float)($row['total_revenue'] ?? 0);
                                            $margin = (float)($row['margin'] ?? ($revenue - $cost));
                                            $percent = isset($row['margin_percent']) ? (float)$row['margin_percent'] : ($revenue > 0 ? (($margin / $revenue) * 100) : 0);
                                            $isNegative = $margin < 0;
                                            $dateView = !empty($row['procedure_date']) ? date('d-m-Y', strtotime($row['procedure_date'])) : 'N/A';
                                    ?>
                                    <tr class="<?= $isNegative ? 'negative-row' : '' ?>">
                                        <td><?= $sn++ ?></td>
                                        <td><?= $dateView ?></td>
                                        <td class="text-right"><?= number_format((int)($row['total_procedures'] ?? 0)) ?></td>
                                        <td class="text-right"><?= number_format($cost, 2) ?></td>
                                        <td class="text-right"><?= number_format($revenue, 2) ?></td>
                                        <td class="text-right"><strong><?= number_format($margin, 2) ?></strong></td>
                                        <td class="text-right"><strong><?= number_format($percent, 2) ?>%</strong></td>
                                    </tr>
                                    <?php }
                                    } else { ?>
                                    <tr>
                                        <td colspan="7" class="text-center text-muted">No cost vs revenue data found for selected criteria.</td>
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

<?php 
require_once(__DIR__ . '/model/session_config.php');
error_reporting(E_ALL);
ini_set('display_errors', 1);

date_default_timezone_set("Asia/Kolkata");  
include('model/storeClass.php');
$newStore = new store;

// Get dashboard statistics
$total_items = 0;
$total_stock_value = 0;
$low_stock_items = 0;
$expiring_soon = 0;
$today_grns = 0;
$today_issues = 0;

// Total items and stock value
$items_query = $newStore->getActiveItems();
if($items_query) {
    $total_items = mysqli_num_rows($items_query);
}

// Stock value calculation
$stock_value_query = $newStore->getStockValue();
if($stock_value_query && $row = mysqli_fetch_assoc($stock_value_query)) {
    $total_stock_value = $row['total_value'] ?? 0;
}

// Low stock items
$low_stock_query = $newStore->getLowStockItems_dash();
if($low_stock_query) {
    $low_stock_items = mysqli_num_rows($low_stock_query);
}

// Expiring items (next 30 days)
$expiring_query = $newStore->getExpiringItems(30);
if($expiring_query) {
    $expiring_soon = mysqli_num_rows($expiring_query);
}

// Today's GRNs
$today = date('Y-m-d');
$today_grns_query = $newStore->getTodayGRNCount($today);
if($today_grns_query && $row = mysqli_fetch_assoc($today_grns_query)) {
    $today_grns = $row['count'] ?? 0;
}

// Today's Issues
$today_issues_query = $newStore->getTodayIssueCount($today);
if($today_issues_query && $row = mysqli_fetch_assoc($today_issues_query)) {
    $today_issues = $row['count'] ?? 0;
}

// Recent activities
$recent_grns = $newStore->getRecentGRNs(5);
$recent_issues = $newStore->getRecentIssues(5);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="theme-color" content="#319197">

    <title>Store Dashboard - Alvas Hospital</title>
    <link rel="icon" type="image/png" sizes="16x16" href="../images/favicon.png">
    <link href="css/style.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

    <style>
        body {
            background: #f5f6fa;
        }
        .stat-card {
            border-radius: 10px;
            padding: 24px 20px 20px 20px;
            color: #222;
            margin-bottom: 24px;
            background: #fff;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
            position: relative;
            border: 1px solid #e5e7eb;
            transition: box-shadow 0.2s;
        }
        .stat-card:hover {
            box-shadow: 0 4px 16px rgba(0,0,0,0.10);
        }
        .stat-card h3 {
            font-size: 2.2rem;
            font-weight: 600;
            margin: 8px 0 2px 0;
            color: #2d3748;
        }
        .stat-card p {
            font-size: 1.05rem;
            margin: 0;
            color: #6c757d;
            opacity: 1;
        }
        .stat-card small {
            color: #a0aec0;
        }
        .stat-card i {
            font-size: 2.5rem;
            opacity: 0.5;
            position: absolute;
            right: 18px;
            top: 18px;
            color: #a0aec0;
        }
        .bg-primary-custom, .bg-success-custom, .bg-warning-custom, .bg-info-custom, .bg-danger-custom, .bg-purple-custom {
            background: #fff !important;
        }
        .quick-action-btn {
            width: 100%;
            padding: 13px;
            margin-bottom: 10px;
            border-radius: 7px;
            font-size: 15px;
            font-weight: 500;
            background: #f5f6fa;
            color: #222;
            border: 1px solid #e5e7eb;
            transition: background 0.2s, color 0.2s;
        }
        .quick-action-btn:hover {
            background: #e2e8f0;
            color: #1a202c;
        }
        .activity-card {
            max-height: 400px;
            overflow-y: auto;
            background: #fff;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
        }
        .activity-item {
            padding: 13px 16px;
            border-left: 3px solid #4a5568;
            margin-bottom: 10px;
            background: #f7fafc;
            border-radius: 5px;
        }
        .activity-item:hover {
            background: #f1f5f9;
        }
        .badge-lg {
            padding: 7px 11px;
            font-size: 13px;
            background: #e2e8f0;
            color: #222;
            border-radius: 12px;
        }
        .alert-item {
            padding: 14px 18px;
            border-left: 4px solid #e2e8f0;
            margin-bottom: 10px;
            border-radius: 5px;
            background: #fff;
        }
        .alert-low-stock {
            border-left-color: #f6c23e;
            background: #fffbe6;
        }
        .alert-expiring {
            border-left-color: #e53e3e;
            background: #fff5f5;
        }
        .card-header {
            background: #f7fafc !important;
            color: #222 !important;
            border-bottom: 1px solid #e5e7eb;
        }
        .btn-primary, .btn-success, .btn-info, .btn-warning, .btn-secondary, .btn-dark {
            color: #fff !important;
        }
        .btn-block {
            margin-top: 5px;
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

        <?php include ('leftbar.php')?>

        <div class="content-body">

            <div class="container-fluid">
                
                <!-- Page Header -->
                <div class="row mb-4">
                    <div class="col-md-12">
                        <h2 class="text-uppercase"><i class="fas fa-warehouse"></i> Store Dashboard</h2>
                        <p class="text-muted">Overview of store inventory and activities</p>
                    </div>
                </div>

                <!-- Statistics Cards Row 1 -->
                <div class="row">
                    <div class="col-lg-4 col-md-6">
                        <div class="stat-card position-relative">
                            <i class="fas fa-boxes"></i>
                            <p>Total Items</p>
                            <h3><?= number_format($total_items) ?></h3>
                            <small>Active inventory items</small>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <div class="stat-card position-relative">
                            <i class="fas fa-rupee-sign"></i>
                            <p>Stock Value</p>
                            <h3>₹<?= number_format($total_stock_value, 2) ?></h3>
                            <small>Total inventory value</small>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <div class="stat-card position-relative">
                            <i class="fas fa-exclamation-triangle"></i>
                            <p>Low Stock Items</p>
                            <h3><?= number_format($low_stock_items) ?></h3>
                            <small>Items below minimum level</small>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-4 col-md-6">
                        <div class="stat-card position-relative">
                            <i class="fas fa-calendar-times"></i>
                            <p>Expiring Soon</p>
                            <h3><?= number_format($expiring_soon) ?></h3>
                            <small>Items expiring in 30 days</small>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <div class="stat-card position-relative">
                            <i class="fas fa-truck-loading"></i>
                            <p>Today's GRNs</p>
                            <h3><?= number_format($today_grns) ?></h3>
                            <small>Goods received today</small>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <div class="stat-card position-relative">
                            <i class="fas fa-dolly"></i>
                            <p>Today's Issues</p>
                            <h3><?= number_format($today_issues) ?></h3>
                            <small>Items issued today</small>
                        </div>
                    </div>
                </div>

                <!-- Main Content Row -->
                <div class="row">
                    
                  

                    <!-- Recent GRNs -->
                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0"><i class="fas fa-truck"></i> Recent GRNs</h5>
                            </div>
                            <div class="card-body activity-card">
                                <?php 
                                if($recent_grns && mysqli_num_rows($recent_grns) > 0) {
                                    while($grn = mysqli_fetch_assoc($recent_grns)) {
                                ?>
                                <div class="activity-item">
                                    <div class="d-flex justify-content-between">
                                        <strong><?= $grn['grn_number'] ?></strong>
                                        <span class="badge badge-primary badge-lg"><?= date('d-M-Y', strtotime($grn['received_date'])) ?></span>
                                    </div>
                                    <small class="text-muted">
                                        <i class="fas fa-building"></i> <?= htmlspecialchars($grn['supplier_name'] ?? 'N/A') ?>
                                    </small><br>
                                    <small><strong>₹<?= number_format($grn['total_amount'], 2) ?></strong></small>
                                </div>
                                <?php 
                                    }
                                } else {
                                    echo '<p class="text-muted text-center">No recent GRNs</p>';
                                }
                                ?>
                            </div>
                            <div class="card-footer">
                                <a href="list_store_grn.php" class="btn btn-sm btn-primary btn-block">View All GRNs</a>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Issues -->
                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-header bg-success text-white">
                                <h5 class="mb-0"><i class="fas fa-share"></i> Recent Issues</h5>
                            </div>
                            <div class="card-body activity-card">
                                <?php 
                                if($recent_issues && mysqli_num_rows($recent_issues) > 0) {
                                    while($issue = mysqli_fetch_assoc($recent_issues)) {
                                ?>
                                <div class="activity-item" style="border-left-color: #28a745;">
                                    <div class="d-flex justify-content-between">
                                        <strong><?= $issue['issue_number'] ?></strong>
                                        <span class="badge badge-success badge-lg"><?= date('d-M-Y', strtotime($issue['issue_date'])) ?></span>
                                    </div>
                                    <small class="text-muted">
                                        <i class="fas fa-building"></i> <?= htmlspecialchars($issue['department']) ?>
                                    </small><br>
                                    <small><i class="fas fa-user"></i> <?= htmlspecialchars($issue['requested_by'] ?? 'N/A') ?></small>
                                </div>
                                <?php 
                                    }
                                } else {
                                    echo '<p class="text-muted text-center">No recent issues</p>';
                                }
                                ?>
                            </div>
                            <div class="card-footer">
                                <a href="store_issue_list.php" class="btn btn-sm btn-success btn-block">View All Issues</a>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Alerts Row -->
                <div class="row mt-4">
                    
                    <!-- Low Stock Alerts -->
                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-header bg-warning text-white">
                                <h5 class="mb-0"><i class="fas fa-exclamation-circle"></i> Low Stock Alerts</h5>
                            </div>
                            <div class="card-body" style="max-height: 350px; overflow-y: auto;">
                                <?php 
                                $low_stock_list = $newStore->getLowStockItems_dash();
                                if($low_stock_list && mysqli_num_rows($low_stock_list) > 0) {
                                    while($item = mysqli_fetch_assoc($low_stock_list)) {
                                ?>
                                <div class="alert-item alert-low-stock">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <strong><?= htmlspecialchars($item['item_name']) ?></strong>
                                            <br><small class="text-muted"><?= $item['item_code'] ?></small>
                                        </div>
                                        <div class="text-right">
                                            <span class="badge badge-warning">Stock: <?= $item['current_stock'] ?></span>
                                            <br><small class="text-muted">Min: <?= $item['min_stock'] ?></small>
                                        </div>
                                    </div>
                                </div>
                                <?php 
                                    }
                                } else {
                                    echo '<p class="text-muted text-center">No low stock items</p>';
                                }
                                ?>
                            </div>
                        </div>
                    </div>

                    <!-- Expiring Items -->
                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-header bg-danger text-white">
                                <h5 class="mb-0"><i class="fas fa-clock"></i> Expiring Items (30 Days)</h5>
                            </div>
                            <div class="card-body" style="max-height: 350px; overflow-y: auto;">
                                <?php 
                                $expiring_list = $newStore->getExpiringItems(30);
                                if($expiring_list && mysqli_num_rows($expiring_list) > 0) {
                                    while($item = mysqli_fetch_assoc($expiring_list)) {
                                        $days_to_expire = ceil((strtotime($item['expiry_date']) - time()) / (60 * 60 * 24));
                                ?>
                                <div class="alert-item alert-expiring">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <strong><?= htmlspecialchars($item['item_name']) ?></strong>
                                            <br><small class="text-muted">Batch: <?= htmlspecialchars($item['batch_no'] ?? 'N/A') ?></small>
                                        </div>
                                        <div class="text-right">
                                            <span class="badge badge-danger"><?= date('d-M-Y', strtotime($item['expiry_date'])) ?></span>
                                            <br><small class="text-muted"><?= $days_to_expire ?> days left</small>
                                        </div>
                                    </div>
                                </div>
                                <?php 
                                    }
                                } else {
                                    echo '<p class="text-muted text-center">No expiring items</p>';
                                }
                                ?>
                            </div>
                        </div>
                    </div>

                </div>

            </div>

        </div>

        <?php include('footer.php') ?>

    </div>

    <script src="vendor/global/global.min.js"></script>
    <script src="js/quixnav-init.js"></script>
    <script src="js/custom.min.js"></script>

    <script>
        // Auto refresh dashboard every 5 minutes
        setTimeout(function() {
            location.reload();
        }, 300000);
    </script>

</body>

</html>

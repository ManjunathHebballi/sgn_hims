<?php 
require_once(__DIR__ . '/model/session_config.php');
error_reporting(E_ALL);
ini_set('display_errors', 1);

date_default_timezone_set("Asia/Kolkata");  

// Initialize variables with default values
$cathLabInventory = array('total_items' => 0, 'total_available' => 0, 'total_value' => 0);
$monthlyGRNCount = array('total' => 0, 'total_cost' => 0, 'total_qty' => 0);
$monthlyBillingCount = array('total' => 0, 'total_amount' => 0, 'avg_amount' => 0);
$lowStockItems = array();
$recentGRNs = array();
$recentBillings = array();

// Try to load storeClass and get data
if(file_exists('model/storeClass.php')) {
    include('model/storeClass.php');
    $newStore = new store;
    
    try {
        // Get inventory summary
        $inv = $newStore->getCathLabInventorySummary();
        if($inv && is_array($inv)) {
            $cathLabInventory = $inv;
        }
        
        // Get GRN count
        $grn = $newStore->getCathLabGRNCount(date('Y-m'));
        if($grn && is_array($grn)) {
            $monthlyGRNCount = $grn;
        }
        
        // Get billing count
        $bill = $newStore->getCathLabBillingCount(date('Y-m'));
        if($bill && is_array($bill)) {
            $monthlyBillingCount = $bill;
        }
        
        // Get low stock items
        $lowStockItemsResult = $newStore->getCathLabLowStockItems();
        if($lowStockItemsResult && mysqli_num_rows($lowStockItemsResult) > 0) {
            while($item = mysqli_fetch_assoc($lowStockItemsResult)) {
                $lowStockItems[] = $item;
            }
        }
        
        // Get recent GRNs
        $recent_grns = $newStore->getRecentCathLabGRNs(10);
        if($recent_grns && is_array($recent_grns)) {
            $recentGRNs = $recent_grns;
        }
        
        // Get recent billings
        $recent_bills = $newStore->getRecentCathLabBillings(10);
        if($recent_bills && is_array($recent_bills)) {
            $recentBillings = $recent_bills;
        }
    } catch(Exception $e) {
        error_log("CathLab Dashboard Error: " . $e->getMessage());
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

    <title>CathLab Store Dashboard</title>
    <link rel="icon" type="image/png" sizes="16x16" href="../images/favicon.png">
    <link href="css/style.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
    body {
        background-color: #f5f7fa;
    }

    .cathlab-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 30px;
        border-radius: 10px;
        margin-bottom: 30px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .cathlab-header h1 {
        margin: 0;
        font-size: 32px;
        font-weight: bold;
    }

    .cathlab-header p {
        margin: 5px 0 0 0;
        opacity: 0.9;
    }

    .stat-card {
        background: white;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 20px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        border-top: 4px solid;
        transition: transform 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
    }

    .stat-card.grn {
        border-top-color: #667eea;
    }

    .stat-card.billing {
        border-top-color: #ff6b6b;
    }

    .stat-card.inventory {
        border-top-color: #28a745;
    }

    .stat-card.alert {
        border-top-color: #ffc107;
    }

    .stat-icon {
        font-size: 24px;
        margin-bottom: 10px;
    }

    .stat-number {
        font-size: 28px;
        font-weight: bold;
        color: #333;
    }

    .stat-label {
        font-size: 12px;
        text-transform: uppercase;
        color: #666;
        margin-top: 5px;
    }

    .action-buttons {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 10px;
        margin-bottom: 30px;
    }

    .action-btn {
        padding: 15px;
        border-radius: 8px;
        text-decoration: none;
        color: white;
        font-weight: bold;
        text-align: center;
        transition: all 0.3s ease;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 8px;
    }

    .action-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        text-decoration: none;
    }

    .action-btn i {
        font-size: 24px;
    }

    .btn-grn {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .btn-billing {
        background: linear-gradient(135deg, #ff6b6b 0%, #ee5a6f 100%);
    }

    .btn-inventory {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    }

    .btn-reports {
        background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
    }

    .table-responsive {
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .table {
        margin-bottom: 0;
    }

    .table thead th {
        background-color: #f8f9fa;
        border-bottom: 2px solid #dee2e6;
        font-weight: 600;
    }

    .badge-pending {
        background-color: #ffc107;
        color: #333;
    }

    .badge-completed {
        background-color: #28a745;
        color: white;
    }

    .low-stock {
        background-color: #fff3cd;
        padding: 10px;
        border-radius: 5px;
        margin-bottom: 10px;
        border-left: 4px solid #ffc107;
    }

    .card-section {
        background: white;
        padding: 20px;
        border-radius: 8px;
        margin-bottom: 20px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .section-title {
        font-size: 18px;
        font-weight: bold;
        color: #333;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 2px solid #667eea;
    }

    .empty-state {
        text-align: center;
        padding: 40px;
        color: #999;
    }

    .empty-state i {
        font-size: 48px;
        margin-bottom: 10px;
        opacity: 0.5;
    }

    .chart-container {
        position: relative;
        height: 300px;
        margin-bottom: 20px;
    }

    .content-body {
        margin-top: 120px;
    }
    </style>

</head>

<body>

    <div id="preloader" style="display: none; opacity: 0;">
        <div class="sk-three-bounce">
            <div class="sk-child sk-bounce1"></div>
            <div class="sk-child sk-bounce2"></div>
            <div class="sk-child sk-bounce3"></div>
        </div>
    </div>

    <div id="main-wrapper" style="display: block; opacity: 1;">

        <?php 
        if(file_exists('leftbar.php')) {
            include ('leftbar.php');
        } else {
            echo "<!-- leftbar.php not found -->";
        }
        ?>

        <div class="content-body">

            <div class="container-fluid">

                <!-- Header -->
                <div class="cathlab-header">
                    <h1>🏥 CathLab Sub-Store Management</h1>
                    <p>Manage consumables, process GRNs, and bill procedures directly to patients</p>
                </div>

                <!-- Quick Action Buttons -->
                <div class="action-buttons">
                    <a href="add_cathlab_grn.php" class="action-btn btn-grn">
                        <i class="fas fa-clipboard-list"></i>
                        <span>New GRN</span>
                    </a>
                    <a href="cathlab_billing.php" class="action-btn btn-billing">
                        <i class="fas fa-credit-card"></i>
                        <span>Bill Patient</span>
                    </a>
                    <a href="add_cathlab_store_item.php" class="action-btn btn-inventory">
                        <i class="fas fa-boxes"></i>
                        <span>Manage Items</span>
                    </a>
                    <a href="cathlab_reports.php" class="action-btn btn-reports">
                        <i class="fas fa-chart-bar"></i>
                        <span>View Reports</span>
                    </a>
                </div>

                <!-- Statistics Row -->
                <div class="row">
                    <div class="col-md-3">
                        <div class="stat-card grn">
                            <div class="stat-icon">📦</div>
                            <div class="stat-number"><?= isset($monthlyGRNCount) ? $monthlyGRNCount['total'] : 0 ?></div>
                            <div class="stat-label">GRNs This Month</div>
                            <small class="text-muted">Last 30 days</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card billing">
                            <div class="stat-icon">💳</div>
                            <div class="stat-number"><?= isset($monthlyBillingCount) ? $monthlyBillingCount['total'] : 0 ?></div>
                            <div class="stat-label">Billings This Month</div>
                            <small class="text-muted">₹ <?= isset($monthlyBillingCount) ? number_format($monthlyBillingCount['total_amount'], 2) : '0.00' ?></small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card inventory">
                            <div class="stat-icon">📊</div>
                            <div class="stat-number"><?= isset($cathLabInventory) ? $cathLabInventory['total_items'] : 0 ?></div>
                            <div class="stat-label">Total Items in Stock</div>
                            <small class="text-muted">Value: ₹ <?= isset($cathLabInventory) ? number_format($cathLabInventory['total_value'], 2) : '0.00' ?></small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card alert">
                            <div class="stat-icon">⚠️</div>
                            <div class="stat-number"><?= (isset($lowStockItems) && is_array($lowStockItems)) ? count($lowStockItems) : 0 ?></div>
                            <div class="stat-label">Low Stock Items</div>
                            <small class="text-muted">Require reorder</small>
                        </div>
                    </div>
                </div>

                <!-- Main Content Row -->
                <div class="row">

                    <!-- Low Stock Items Alert -->
                    <div class="col-md-6">
                        <div class="card-section">
                            <div class="section-title">⚠️ Low Stock Items</div>
                            <?php 
                            if(is_array($lowStockItems) && !empty($lowStockItems)) {
                                foreach($lowStockItems as $item) {
                                    echo '
                                    <div class="low-stock">
                                        <strong>' . htmlspecialchars($item['item_name']) . '</strong><br>
                                        <small>Current: ' . (isset($item['current_stock']) ? number_format($item['current_stock'], 2) : '0') . ' | Min: ' . (isset($item['reorder_level']) ? $item['reorder_level'] : '0') . '</small>
                                    </div>
                                    ';
                                }
                            } else {
                                echo '<div class="empty-state"><i class="fas fa-check-circle"></i><p>All items are adequately stocked</p></div>';
                            }
                            ?>
                        </div>
                    </div>

                    <!-- Quick Stats -->
                    <div class="col-md-6">
                        <div class="card-section">
                            <div class="section-title">📈 Monthly Trends</div>
                            <div class="chart-container">
                                <canvas id="trendsChart"></canvas>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Recent GRNs -->
                <div class="row mt-3">
                    <div class="col-md-12">
                        <div class="card-section">
                            <div class="section-title">📝 Recent GRNs</div>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>GRN Number</th>
                                            <th>Date</th>
                                            <th>Supplier</th>
                                            <th>Items</th>
                                            <th>Total Value</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        if(is_array($recentGRNs) && !empty($recentGRNs)) {
                                            foreach($recentGRNs as $grn) {
                                                echo '
                                                <tr>
                                                    <td><strong>' . htmlspecialchars($grn['grn_number']) . '</strong></td>
                                                    <td>' . date('d-M-Y', strtotime($grn['grn_date'])) . '</td>
                                                    <td>' . htmlspecialchars($grn['supplier_name']) . '</td>
                                                    <td>' . (isset($grn['item_count']) ? $grn['item_count'] : 0) . '</td>
                                                    <td>₹ ' . number_format(isset($grn['total_cost']) ? $grn['total_cost'] : 0, 2) . '</td>
                                                    <td><span class="badge badge-completed">Completed</span></td>
                                                    <td><a href="view_cathlab_grn.php?id=' . $grn['id'] . '" class="btn btn-sm btn-info">View</a></td>
                                                </tr>
                                                ';
                                            }
                                        } else {
                                            echo '<tr><td colspan="7" class="text-center text-muted">No GRNs yet</td></tr>';
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                            <a href="cathlab_grn_list.php" class="btn btn-sm btn-outline-primary mt-3">View All GRNs</a>
                        </div>
                    </div>
                </div>

                <!-- Recent Billings -->
                <div class="row mt-3">
                    <div class="col-md-12">
                        <div class="card-section">
                            <div class="section-title">💳 Recent Billings</div>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Billing ID</th>
                                            <th>Patient IP</th>
                                            <th>Patient Name</th>
                                            <th>Procedure</th>
                                            <th>Billing Date</th>
                                            <th>Amount</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        if(is_array($recentBillings) && !empty($recentBillings)) {
                                            foreach($recentBillings as $billing) {
                                                echo '
                                                <tr>
                                                    <td><strong>' . htmlspecialchars($billing['billing_number']) . '</strong></td>
                                                    <td>' . htmlspecialchars($billing['patient_id']) . '</td>
                                                    <td>' . htmlspecialchars($billing['patient_name']) . '</td>
                                                    <td>' . htmlspecialchars(isset($billing['procedure_type']) ? $billing['procedure_type'] : 'N/A') . '</td>
                                                    <td>' . date('d-M-Y', strtotime($billing['billing_date'])) . '</td>
                                                    <td><strong>₹ ' . number_format(isset($billing['total_amount']) ? $billing['total_amount'] : 0, 2) . '</strong></td>
                                                    <td><span class="badge badge-completed">' . (isset($billing['status']) ? ucfirst($billing['status']) : 'Pending') . '</span></td>
                                                    <td><a href="view_cathlab_billing.php?id=' . $billing['id'] . '" class="btn btn-sm btn-info">View</a></td>
                                                </tr>
                                                ';
                                            }
                                        } else {
                                            echo '<tr><td colspan="8" class="text-center text-muted">No billings yet</td></tr>';
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                            <a href="cathlab_billing_list.php" class="btn btn-sm btn-outline-primary mt-3">View All Billings</a>
                        </div>
                    </div>
                </div>

            </div>

        </div>

        <?php 
        if(file_exists('footer.php')) {
            include ('footer.php');
        } else {
            echo "<!-- footer.php not found -->";
        }
        ?>

    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

    <script>
        // Show main wrapper and hide preloader on ready
        $(document).ready(function() {
            $('#main-wrapper').show();
            $('#preloader').hide();
            
            // Trends Chart
            const ctx = document.getElementById('trendsChart');
            if(ctx) {
                try {
                    new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4'],
                            datasets: [
                                {
                                    label: 'GRNs',
                                    data: [5, 8, 6, 10],
                                    borderColor: '#667eea',
                                    backgroundColor: 'rgba(102, 126, 234, 0.1)',
                                    tension: 0.4
                                },
                                {
                                    label: 'Billings',
                                    data: [3, 6, 8, 5],
                                    borderColor: '#ff6b6b',
                                    backgroundColor: 'rgba(255, 107, 107, 0.1)',
                                    tension: 0.4
                                }
                            ]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    position: 'top',
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            }
                        }
                    });
                } catch(e) {
                    console.log("Chart error: " + e);
                }
            }
        });

        // Show content even if jQuery fails
        window.addEventListener('load', function() {
            document.getElementById('main-wrapper').style.display = 'block';
            document.getElementById('preloader').style.display = 'none';
        });
    </script>

</body>

</html>

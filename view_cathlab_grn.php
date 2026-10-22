<?php 
require_once(__DIR__ . '/model/session_config.php');
error_reporting(E_ALL);
ini_set('display_errors', 1);
date_default_timezone_set("Asia/Kolkata");  
include('model/cathLabClass.php');

$grn_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if($grn_id == 0) {
    $_SESSION['error_message'] = "GRN ID not provided";
    header('Location: cathlab_store_dashboard.php');
    exit();
}

$cathLab = new cathLab;

try {
    $grn = $cathLab->getCathLabGrnById($grn_id);
    
    if(!$grn) {
        $_SESSION['error_message'] = "GRN not found";
        header('Location: cathlab_store_dashboard.php');
        exit();
    }
} catch(Exception $e) {
    $_SESSION['error_message'] = "Error: " . $e->getMessage();
    header('Location: cathlab_store_dashboard.php');
    exit();
}

// Get GRN items using cathLabClass method
try {
    $grn_items_result = $cathLab->getCathLabGrnItems($grn_id);
} catch(Exception $e) {
    $_SESSION['error_message'] = "Error loading GRN items: " . $e->getMessage();
    header('Location: cathlab_store_dashboard.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="theme-color" content="#319197">

    <title>CathLab GRN Details</title>
    <link rel="icon" type="image/png" sizes="16x16" href="../images/favicon.png">
    <link href="css/style.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

    <style>
    .grn-badge {
        display: inline-block;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 8px 16px;
        border-radius: 20px;
        font-weight: bold;
        margin-bottom: 20px;
    }

    .grn-header {
        background-color: #f8f9fa;
        padding: 20px;
        border-radius: 8px;
        border-left: 5px solid #667eea;
        margin-bottom: 20px;
    }

    .grn-header h5 {
        color: #667eea;
        margin-top: 0;
    }

    .header-row {
        display: flex;
        gap: 20px;
        flex-wrap: wrap;
    }

    .header-item {
        flex: 1;
        min-width: 200px;
    }

    .header-item label {
        font-size: 12px;
        color: #999;
        text-transform: uppercase;
    }

    .header-item .value {
        font-size: 16px;
        font-weight: bold;
        color: #333;
        margin-top: 5px;
    }

    .items-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    .items-table th {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 12px;
        text-align: left;
        font-weight: bold;
    }

    .items-table td {
        padding: 12px;
        border-bottom: 1px solid #eee;
    }

    .items-table tr:hover {
        background-color: #f8f9fa;
    }

    .summary-box {
        background: linear-gradient(135deg, #f0f0ff 0%, #e8eaf6 100%);
        padding: 20px;
        border-radius: 8px;
        margin-top: 20px;
        display: flex;
        gap: 20px;
        justify-content: space-around;
    }

    .summary-item {
        text-align: center;
    }

    .summary-item .label {
        font-size: 12px;
        color: #667;
        text-transform: uppercase;
    }

    .summary-item .value {
        font-size: 24px;
        font-weight: bold;
        color: #667eea;
        margin-top: 10px;
    }

    .batch-info {
        background-color: #fff3cd;
        padding: 8px 12px;
        border-radius: 4px;
        font-size: 12px;
        margin-top: 5px;
    }

    .expiry-warning {
        color: #d32f2f;
        font-weight: bold;
    }

    .card-header.cathlab {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .content-body {
        margin-top: 120px;
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
                        <span class="grn-badge">📦 GRN DETAILS</span>
                        <h4 class="text-uppercase">CathLab Goods Receipt Note</h4>
                    </div>
                </div>

                <!-- GRN Header Information -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header cathlab">
                                <h5><?= htmlspecialchars($grn['grn_number']) ?></h5>
                            </div>
                            <div class="card-body">
                                <div class="grn-header">
                                    <h5>GRN Information</h5>
                                    <div class="header-row">
                                        <div class="header-item">
                                            <label>GRN Number</label>
                                            <div class="value"><?= htmlspecialchars($grn['grn_number']) ?></div>
                                        </div>
                                        <div class="header-item">
                                            <label>GRN Date</label>
                                            <div class="value"><?= date('d-m-Y', strtotime($grn['grn_date'])) ?></div>
                                        </div>
                                        <div class="header-item">
                                            <label>Supplier</label>
                                            <div class="value"><?= htmlspecialchars($grn['supplier_name']) ?></div>
                                        </div>
                                        <div class="header-item">
                                            <label>Status</label>
                                            <div class="value">
                                                <span class="badge badge-success">Completed</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="grn-header">
                                    <h5>Additional Details</h5>
                                    <div class="header-row">
                                        <div class="header-item">
                                            <label>Remarks</label>
                                            <div class="value"><?= htmlspecialchars($grn['remarks'] ?? '-') ?></div>
                                        </div>
                                        <div class="header-item">
                                            <label>Received By</label>
                                            <div class="value"><?= htmlspecialchars($grn['created_by'] ?? 'System') ?></div>
                                        </div>
                                        <div class="header-item">
                                            <label>Created On</label>
                                            <div class="value"><?= date('d-m-Y H:i', strtotime($grn['created_at'])) ?></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- GRN Items Table -->
                <div class="row mt-3">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header cathlab">
                                <h5>Received Items</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="items-table">
                                        <thead>
                                            <tr>
                                                <th>Item Code</th>
                                                <th>Item Name</th>
                                                <th>Quantity</th>
                                                <th>Unit</th>
                                                <th>Cost Price</th>
                                                <th>Total Cost</th>
                                                <th>Batch Details</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                            $total_qty = 0;
                                            $total_cost = 0;
                                            
                                            while($item = mysqli_fetch_assoc($grn_items_result)) {
                                                $total_qty += $item['quantity'];
                                                $total_cost += $item['total_cost'];
                                                
                                                $is_expired = strtotime($item['expiry_date']) < time();
                                                $expiry_class = $is_expired ? 'expiry-warning' : '';
                                            ?>
                                                <tr>
                                                    <td><strong><?= htmlspecialchars($item['item_code']) ?></strong></td>
                                                    <td><?= htmlspecialchars($item['item_name']) ?></td>
                                                    <td class="text-center"><?= number_format($item['quantity'], 2) ?></td>
                                                    <td><?= htmlspecialchars($item['unit_name'] ?? 'PCS') ?></td>
                                                    <td>₹<?= number_format($item['cost_price'], 2) ?></td>
                                                    <td><strong>₹<?= number_format($item['total_cost'], 2) ?></strong></td>
                                                    <td>
                                                        <div class="batch-info">
                                                            <strong>Batch:</strong> <?= htmlspecialchars($item['batch_number']) ?><br>
                                                            <strong>Expiry:</strong> <span class="<?= $expiry_class ?>"><?= date('d-m-Y', strtotime($item['expiry_date'])) ?></span>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Summary -->
                                <div class="summary-box">
                                    <div class="summary-item">
                                        <div class="label">Total Items</div>
                                        <div class="value"><?= mysqli_num_rows($grn_items_result) ?></div>
                                    </div>
                                    <div class="summary-item">
                                        <div class="label">Total Quantity</div>
                                        <div class="value"><?= number_format($total_qty, 2) ?></div>
                                    </div>
                                    <div class="summary-item">
                                        <div class="label">Total Cost</div>
                                        <div class="value">₹<?= number_format($total_cost, 2) ?></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="row mt-3 mb-3">
                    <div class="col-md-12">
                        <a href="cathlab_store_dashboard.php" class="btn btn-info btn-lg">
                            <i class="fa fa-arrow-left"></i> Back to Dashboard
                        </a>
                        <button onclick="window.print()" class="btn btn-secondary btn-lg">
                            <i class="fa fa-print"></i> Print GRN
                        </button>
                    </div>
                </div>

            </div>

        </div>

        <?php include ('footer.php')?>

    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#preloader').hide();
            $('#main-wrapper').show();
        });
    </script>

</body>

</html>

<?php 
require_once(__DIR__ . '/model/session_config.php');
date_default_timezone_set("Asia/Kolkata");  
include('model/cathLabClass.php');
$newStore = new cathLab;

$item_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if(!$item_id) {
    echo "<script>alert('Invalid item ID'); window.location.href='add_cathlab_store_item.php';</script>";
    exit();
}

// Get item details
$item_result = $newStore->getCathLabItemById($item_id);
$item = mysqli_fetch_assoc($item_result);

if(!$item) {
    echo "<script>alert('Item not found'); window.location.href='add_cathlab_store_item.php';</script>";
    exit();
}

// Determine stock status
$current_stock = $item['current_stock'];
$min_stock = $item['min_stock'];

if($current_stock <= 0) {
    $status_class = 'critical';
    $status_text = 'Out of Stock';
} elseif($current_stock <= $min_stock) {
    $status_class = 'low';
    $status_text = 'Low Stock';
} else {
    $status_class = 'ok';
    $status_text = 'OK';
}

$type_class = strtolower(str_replace(' ', '-', $item['item_type']));
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="theme-color" content="#319197">

    <title>CathLab Item Details</title>
    <link rel="icon" type="image/png" sizes="16x16" href="../images/favicon.png">
    <link href="css/style.css" rel="stylesheet">
    <style>
    .detail-header {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        color: white;
        padding: 30px;
        border-radius: 8px;
        margin-bottom: 30px;
    }

    .detail-header h2 {
        margin: 0;
        font-weight: bold;
    }

    .detail-badge {
        display: inline-block;
        background: rgba(255,255,255,0.2);
        padding: 6px 12px;
        border-radius: 20px;
        margin-top: 10px;
        font-size: 14px;
    }

    .detail-card {
        border-left: 4px solid #28a745;
        border-radius: 0;
        margin-bottom: 20px;
    }

    .detail-card .card-header {
        background-color: #f8f9fa;
        border-bottom: 2px solid #28a745;
        font-weight: bold;
    }

    .detail-row {
        display: flex;
        padding: 15px 0;
        border-bottom: 1px solid #e3e6f0;
    }

    .detail-row:last-child {
        border-bottom: none;
    }

    .detail-label {
        font-weight: bold;
        color: #495057;
        width: 30%;
        min-width: 200px;
    }

    .detail-value {
        width: 70%;
        color: #212529;
    }

    .inventory-status {
        display: inline-block;
        padding: 6px 12px;
        border-radius: 4px;
        font-size: 12px;
        font-weight: bold;
        text-transform: uppercase;
    }

    .status-ok {
        background-color: #d4edda;
        color: #155724;
    }

    .status-low {
        background-color: #fff3cd;
        color: #856404;
    }

    .status-critical {
        background-color: #f8d7da;
        color: #721c24;
    }

    .item-type-badge {
        display: inline-block;
        padding: 6px 12px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: bold;
    }

    .consumable {
        background-color: #e7f3ff;
        color: #004085;
    }

    .medical-device {
        background-color: #fff3cd;
        color: #856404;
    }

    .implant {
        background-color: #e2e3e5;
        color: #383d41;
    }

    .price-box {
        background-color: #f8f9fa;
        padding: 15px;
        border-radius: 6px;
        margin-bottom: 10px;
    }

    .price-label {
        font-size: 12px;
        color: #6c757d;
        text-transform: uppercase;
        margin-bottom: 5px;
    }

    .price-value {
        font-size: 24px;
        font-weight: bold;
        color: #28a745;
    }

    .action-buttons {
        margin-top: 30px;
    }

    .action-buttons .btn {
        margin-right: 10px;
    }

    #preloader {
        display: none;
        opacity: 0;
    }

    #main-wrapper {
        display: block;
        opacity: 1;
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

    <div id="main-wrapper">

        <?php include ('leftbar.php')?>

        <div class="content-body">

            <div class="container-fluid">
                
                <!-- Item Header -->
                <div class="detail-header">
                    <h2><?php echo htmlspecialchars($item['item_name']); ?></h2>
                    <span class="detail-badge">Item Code: <?php echo htmlspecialchars($item['item_code']); ?></span>
                    <br>
                    <span class="detail-badge">
                        <span class="item-type-badge <?php echo $type_class; ?>">
                            <?php echo htmlspecialchars($item['item_type']); ?>
                        </span>
                    </span>
                </div>

                <!-- Item Details -->
                <div class="row">
                    <div class="col-md-8">
                        <div class="card detail-card">
                            <div class="card-header">
                                <i class="fa fa-info-circle"></i> Item Information
                            </div>
                            <div class="card-body">
                                <div class="detail-row">
                                    <div class="detail-label">Item Code:</div>
                                    <div class="detail-value">
                                        <strong><?php echo htmlspecialchars($item['item_code']); ?></strong>
                                    </div>
                                </div>

                                <div class="detail-row">
                                    <div class="detail-label">Item Name:</div>
                                    <div class="detail-value">
                                        <strong><?php echo htmlspecialchars($item['item_name']); ?></strong>
                                    </div>
                                </div>

                                <div class="detail-row">
                                    <div class="detail-label">Item Type:</div>
                                    <div class="detail-value">
                                        <span class="item-type-badge <?php echo $type_class; ?>">
                                            <?php echo htmlspecialchars($item['item_type']); ?>
                                        </span>
                                    </div>
                                </div>

                                <div class="detail-row">
                                    <div class="detail-label">Category:</div>
                                    <div class="detail-value">
                                        <strong><?php echo htmlspecialchars($item['category_name']); ?></strong>
                                    </div>
                                </div>

                                <div class="detail-row">
                                    <div class="detail-label">Unit of Measurement:</div>
                                    <div class="detail-value">
                                        <strong><?php echo htmlspecialchars($item['unit_name']); ?></strong>
                                    </div>
                                </div>

                                <div class="detail-row">
                                    <div class="detail-label">Description:</div>
                                    <div class="detail-value">
                                        <?php echo htmlspecialchars($item['description'] ?: 'N/A'); ?>
                                    </div>
                                </div>

                                <div class="detail-row">
                                    <div class="detail-label">Expiry Date Required:</div>
                                    <div class="detail-value">
                                        <span class="badge badge-<?php echo $item['is_expiry_required'] ? 'success' : 'secondary'; ?>">
                                            <?php echo $item['is_expiry_required'] ? 'Yes' : 'No'; ?>
                                        </span>
                                    </div>
                                </div>

                                <div class="detail-row">
                                    <div class="detail-label">Serial Number Required:</div>
                                    <div class="detail-value">
                                        <span class="badge badge-<?php echo $item['is_serial_required'] ? 'success' : 'secondary'; ?>">
                                            <?php echo $item['is_serial_required'] ? 'Yes' : 'No'; ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Pricing & Stock Sidebar -->
                    <div class="col-md-4">
                        <!-- Pricing Card -->
                        <div class="card detail-card">
                            <div class="card-header">
                                <i class="fa fa-tag"></i> Pricing
                            </div>
                            <div class="card-body">
                                <div class="price-box">
                                    <div class="price-label">Unit Cost</div>
                                    <div class="price-value">₹ <?php echo number_format($item['unit_cost'], 2); ?></div>
                                </div>

                                <div class="price-box">
                                    <div class="price-label">Billing Price</div>
                                    <div class="price-value">₹ <?php echo number_format($item['billing_price'], 2); ?></div>
                                </div>

                                <div class="price-box">
                                    <div class="price-label">Markup</div>
                                    <div class="price-value">
                                        <?php 
                                        if($item['unit_cost'] > 0) {
                                            $markup = (($item['billing_price'] - $item['unit_cost']) / $item['unit_cost']) * 100;
                                            echo number_format($markup, 2) . '%';
                                        } else {
                                            echo 'N/A';
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Stock Card -->
                        <div class="card detail-card">
                            <div class="card-header">
                                <i class="fa fa-boxes"></i> Inventory
                            </div>
                            <div class="card-body">
                                <div class="price-box">
                                    <div class="price-label">Current Stock</div>
                                    <div class="price-value" style="color: #495057;">
                                        <?php echo number_format($current_stock, 2); ?>
                                    </div>
                                </div>

                                <div class="price-box">
                                    <div class="price-label">Stock Status</div>
                                    <div style="margin-top: 5px;">
                                        <span class="inventory-status status-<?php echo $status_class; ?>">
                                            <?php echo $status_text; ?>
                                        </span>
                                    </div>
                                </div>

                                <div class="price-box">
                                    <div class="price-label">Minimum Stock Level</div>
                                    <div class="price-value" style="color: #495057; font-size: 18px;">
                                        <?php echo number_format($min_stock, 2); ?>
                                    </div>
                                </div>

                                <div class="price-box">
                                    <div class="price-label">Reorder Level</div>
                                    <div class="price-value" style="color: #495057; font-size: 18px;">
                                        <?php echo number_format($item['reorder_level'], 2); ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Card -->
                        <div class="card detail-card">
                            <div class="card-header">
                                <i class="fa fa-cog"></i> Actions
                            </div>
                            <div class="card-body action-buttons">
                                <a href="add_cathlab_store_item.php" class="btn btn-secondary btn-block">
                                    <i class="fa fa-arrow-left"></i> Back to List
                                </a>
                                <button class="btn btn-primary btn-block" onclick="editItem(<?php echo $item_id; ?>)">
                                    <i class="fa fa-edit"></i> Edit Item
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>

        <?php include ('footer.php')?>

    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

    <script>
        function editItem(itemId) {
            // alert('Edit functionality coming soon - Item ID: ' + itemId);
            window.location.href = 'edit_cathlab_item.php?id=' + itemId;
        }
    </script>

</body>

</html>

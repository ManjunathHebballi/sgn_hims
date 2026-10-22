<?php 
require_once(__DIR__ . '/model/session_config.php');
date_default_timezone_set("Asia/Kolkata");  
include('model/cathLabClass.php');
$newStore = new cathLab;
$item_type = isset($_REQUEST['item_type']) ? $_REQUEST['item_type'] : '';
$stock_status = isset($_REQUEST['stock_status']) ? $_REQUEST['stock_status'] : '';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="theme-color" content="#319197">

    <title>CathLab Store Items</title>
    <link rel="icon" type="image/png" sizes="16x16" href="../images/favicon.png">
    <link href="css/style.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet">

    <style>
    .cathlab-badge {
        display: inline-block;
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        color: white;
        padding: 8px 16px;
        border-radius: 20px;
        font-weight: bold;
        margin-bottom: 20px;
    }

    .card-header.cathlab {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        color: white;
    }

    .item-card {
        background-color: #f8f9fa;
        padding: 15px;
        border-radius: 8px;
        border-left: 4px solid #28a745;
        margin-bottom: 15px;
    }

    .inventory-status {
        display: inline-block;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 11px;
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

    .table th {
        background-color: #f8f9fa;
        border-bottom: 2px solid #dee2e6;
    }

    .item-type-badge {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 12px;
        font-size: 11px;
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

    #preloader {
        display: none;
        opacity: 0;
    }

    #main-wrapper {
        display: block;
        opacity: 1;
    }

    .content-body {
        margin-top: 20px;
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
                <div class="row mb-3">
                    <div class="col-md-12">
                        <span class="cathlab-badge">🏥 CATHLAB SUB-STORE</span>
                        <h4 class="text-uppercase">📦 Manage CathLab Consumables & Medical Items</h4>
                        <p class="text-muted">Add, update, and manage items specific to CathLab procedures</p>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="row mb-3">
                    <div class="col-md-12">
                        <button class="btn btn-success btn-lg" data-toggle="modal" data-target="#addItemModal">
                            <i class="fa fa-plus"></i> Add New Item
                        </button>
                        <a href="cathlab_store_dashboard.php" class="btn btn-info btn-lg">
                            <i class="fa fa-arrow-left"></i> Back to Dashboard
                        </a>
                        <button class="btn btn-warning btn-lg" data-toggle="modal" data-target="#bulkUploadModal">
                            <i class="fa fa-upload"></i> Bulk Import
                        </button>
                    </div>
                </div>

                <!-- Filter Section -->
                <div class="row mb-3">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                               <form id="filterForm" method="GET" action="add_cathlab_store_item.php">
                                   <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Item Type</label>
                                            <select class="form-control select2" name="item_type" id="filterItemType">
                                                <option value="">All Types</option>
                                                <option value="consumable" <?php echo ($item_type == 'consumable') ? 'selected' : ''; ?>>Consumable</option>
                                                <option value="medical_device" <?php echo ($item_type == 'medical_device') ? 'selected' : ''; ?>>Medical Device</option>
                                                <option value="implant" <?php echo ($item_type == 'implant') ? 'selected' : ''; ?>>Implant</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Stock Status</label>
                                            <select class="form-control select2" name="stock_status" id="filterStockStatus">
                                                <option value="">All Status</option>
                                                <option value="ok" <?php echo ($stock_status == 'ok') ? 'selected' : ''; ?>>Adequate</option>
                                                <option value="low" <?php echo ($stock_status == 'low') ? 'selected' : ''; ?>>Low Stock</option>
                                                <option value="critical" <?php echo ($stock_status == 'critical') ? 'selected' : ''; ?>>Critical</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Search Item</label>
                                            <input type="text" class="form-control" name="search" id="searchItem" value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>" placeholder="Item name, code, category...">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>&nbsp;</label>
                                            <button type="submit" class="btn btn-primary btn-block" >
                                                <i class="fa fa-search"></i> Search
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Items List -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header cathlab">
                                <h5>CathLab Items</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover" id="cathLabItemsTable">
                                        <thead>
                                            <tr>
                                                <th>Item Code</th>
                                                <th>Item Name</th>
                                                <th>Category</th>
                                                <th>Type</th>
                                                <th>Unit</th>
                                                <th>Current Stock</th>
                                                <th>Min Stock</th>
                                                <th>Status</th>
                                                <th>Unit Cost</th>
                                                <th>Billing Price</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                            $items = $newStore->getCathLabItems($_REQUEST['item_type'], $_REQUEST['stock_status'], $_REQUEST['search_term']);
                                            // print_r($items);
                                            if($items && mysqli_num_rows($items) > 0) {
                                                while($item = mysqli_fetch_assoc($items)) {
                                                    $current_stock = $item['current_stock'];
                                                    $min_stock = $item['min_stock'];
                                                    
                                                    if($current_stock <= 0) {
                                                        $status = 'critical';
                                                        $status_text = 'Out of Stock';
                                                    } elseif($current_stock <= $min_stock) {
                                                        $status = 'low';
                                                        $status_text = 'Low Stock';
                                                    } else {
                                                        $status = 'ok';
                                                        $status_text = 'OK';
                                                    }

                                                    $type_class = strtolower(str_replace(' ', '-', $item['item_type']));
                                                    
                                                    echo '
                                                    <tr>
                                                        <td><strong>' . htmlspecialchars($item['item_code']) . '</strong></td>
                                                        <td>' . htmlspecialchars($item['item_name']) . '</td>
                                                        <td><small>' . htmlspecialchars($item['category_name']) . '</small></td>
                                                        <td><span class="item-type-badge ' . $type_class . '">' . $item['item_type'] . '</span></td>
                                                        <td>' . htmlspecialchars($item['unit_name']) . '</td>
                                                        <td><strong>' . number_format($current_stock, 2) . '</strong></td>
                                                        <td>' . $min_stock . '</td>
                                                        <td><span class="inventory-status status-' . $status . '">' . $status_text . '</span></td>
                                                        <td>₹ ' . number_format($item['unit_cost'], 2) . '</td>
                                                        <td>₹ ' . number_format($item['billing_price'], 2) . '</td>
                                                        <td>
                                                            <button class="btn btn-sm btn-primary" onclick="editItem(' . $item['id'] . ')" data-toggle="modal" data-target="#editItemModal">
                                                                <i class="fa fa-edit"></i>
                                                            </button>
                                                            <button class="btn btn-sm btn-info" onclick="viewItemDetails(' . $item['id'] . ')">
                                                                <i class="fa fa-eye"></i>
                                                            </button>
                                                            <button class="btn btn-sm btn-danger" onclick="deleteItem(' . $item['id'] . ')">
                                                                <i class="fa fa-trash"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                    ';
                                                }
                                            } else {
                                                echo '<tr><td colspan="11" class="text-center text-muted">No items found. <a href="#addItemModal" data-toggle="modal">Add one now</a></td></tr>';
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>

        <?php include ('footer.php')?>

    </div>

    <!-- Add Item Modal -->
    <div class="modal fade" id="addItemModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header cathlab">
                    <h5 class="modal-title">Add New CathLab Item</h5>
                    <button type="button" class="close text-white" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <form action="controller/processCathLab.php" method="POST">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Item Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="item_name" required>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Item Code <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="item_code" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Item Type <span class="text-danger">*</span></label>
                                    <select class="form-control" name="item_type" required>
                                        <option value="">Select Type</option>
                                        <option value="consumable">Consumable</option>
                                        <option value="medical_device">Medical Device</option>
                                        <option value="implant">Implant</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Category <span class="text-danger">*</span></label>
                                    <select class="form-control select2" name="category_id" required>
                                        <option value="">Select Category</option>
                                        <?php 
                                        $categories = $newStore->getStoreCategories();
                                        if($categories && mysqli_num_rows($categories) > 0) {
                                            while($cat = mysqli_fetch_assoc($categories)) {
                                                echo "<option value='{$cat['id']}'>{$cat['category_name']}</option>";
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Unit of Measurement <span class="text-danger">*</span></label>
                                    <select class="form-control select2" name="unit_id" required>
                                        <option value="">Select Unit</option>
                                        <?php 
                                        $units = $newStore->getStoreUnits();
                                        if($units && mysqli_num_rows($units) > 0) {
                                            while($unit = mysqli_fetch_assoc($units)) {
                                                echo "<option value='{$unit['id']}'>{$unit['unit_name']}</option>";
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Unit Cost (₹) <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" name="unit_cost" step="0.01" min="0" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Billing Price (₹) <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" name="billing_price" step="0.01" min="0" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Minimum Stock Level <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" name="min_stock" step="0.01" min="0" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Reorder Level <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" name="reorder_level" step="0.01" min="0" required>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>
                                <input type="checkbox" name="is_expiry_required"> Expiry Date Required
                            </label>
                        </div>

                        <div class="form-group">
                            <label>
                                <input type="checkbox" name="is_serial_required"> Serial Number Required
                            </label>
                        </div>

                        <div class="form-group">
                            <label>Description</label>
                            <textarea class="form-control" name="description" rows="3" placeholder="Item description..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" name="action" value="addCathLabItem" class="btn btn-success">
                            <i class="fa fa-save"></i> Add Item
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bulk Upload Modal -->
    <div class="modal fade" id="bulkUploadModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header cathlab">
                    <h5 class="modal-title">Bulk Import Items</h5>
                    <button type="button" class="close text-white" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <form action="controller/processCathLab.php" method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Upload CSV File <span class="text-danger">*</span></label>
                            <input type="file" class="form-control-file" name="csv_file" accept=".csv" required>
                            <small class="form-text text-muted">
                                CSV columns: Item Code, Item Name, Category, Type, Unit, Unit Cost, Billing Price, Min Stock, Description
                            </small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" name="action" value="bulkImportCathLabItems" class="btn btn-success">
                            <i class="fa fa-upload"></i> Import
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

    <script>
        $(document).ready(function() {
            $('.select2').select2();
            $('#cathLabItemsTable').DataTable({
                "pageLength": 25,
                "order": [[1, "asc"]]
            });
        });

        function applyFilters() {
            // Implement filtering logic
            alert('Filtering will be implemented with backend pagination');
        }

        function editItem(itemId) {
            // Load item data and populate form
            window.location.href = 'edit_cathlab_item.php?id=' + itemId;
        }

        function viewItemDetails(itemId) {
            // Open item details view
            window.location.href = 'view_cathlab_item.php?id=' + itemId;
        }

        function deleteItem(itemId) {
            if(confirm('Are you sure you want to delete this item?')) {
                $.ajax({
                    url: 'controller/processCathLab.php',
                    type: 'POST',
                    data: {
                        action: 'deleteCathLabItem',
                        item_id: itemId
                    },
                    success: function(response) {
                        alert('Item deleted successfully');
                        location.reload();
                    },
                    error: function() {
                        alert('Error deleting item');
                    }
                });
            }
        }
    </script>

</body>

</html>

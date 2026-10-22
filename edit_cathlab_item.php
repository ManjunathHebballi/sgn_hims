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

// Get categories and units for dropdowns
$categories = $newStore->getStoreCategories();
$units = $newStore->getStoreUnits();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="theme-color" content="#319197">

    <title>Edit CathLab Item</title>
    <link rel="icon" type="image/png" sizes="16x16" href="../images/favicon.png">
    <link href="css/style.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />

    <style>
    .edit-header {
        background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
        color: white;
        padding: 30px;
        border-radius: 8px;
        margin-bottom: 30px;
    }

    .edit-header h2 {
        margin: 0;
        font-weight: bold;
    }

    .form-card {
        border-left: 4px solid #007bff;
        border-radius: 0;
        margin-bottom: 20px;
    }

    .form-card .card-header {
        background-color: #f8f9fa;
        border-bottom: 2px solid #007bff;
        font-weight: bold;
    }

    .form-section {
        margin-bottom: 30px;
    }

    .form-section h5 {
        color: #007bff;
        font-weight: bold;
        margin-bottom: 15px;
        padding-bottom: 10px;
        border-bottom: 2px solid #e3e6f0;
    }

    .required-field::after {
        content: " *";
        color: #dc3545;
    }

    .save-buttons {
        margin-top: 30px;
        padding-top: 20px;
        border-top: 2px solid #e3e6f0;
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
                
                <!-- Edit Header -->
                <div class="edit-header">
                    <h2>✏️ Edit CathLab Item</h2>
                    <p style="margin-top: 10px; margin-bottom: 0;">Modify item details and pricing</p>
                </div>

                <!-- Edit Form -->
                <div class="row">
                    <div class="col-md-10 offset-md-1">
                        <form action="controller/processCathLab.php" method="POST">
                            <input type="hidden" name="action" value="updateCathLabItem">
                            <input type="hidden" name="item_id" value="<?php echo $item_id; ?>">

                            <!-- Basic Information -->
                            <div class="card form-card">
                                <div class="card-header">
                                    <i class="fa fa-info-circle"></i> Basic Information
                                </div>
                                <div class="card-body form-section">
                                    <div class="form-group">
                                        <label class="required-field">Item Code</label>
                                        <input type="text" class="form-control" name="item_code" value="<?php echo htmlspecialchars($item['item_code']); ?>" readonly>
                                        <small class="form-text text-muted">Item code cannot be changed</small>
                                    </div>

                                    <div class="form-group">
                                        <label class="required-field">Item Name</label>
                                        <input type="text" class="form-control" name="item_name" value="<?php echo htmlspecialchars($item['item_name']); ?>" required>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="required-field">Item Type</label>
                                                <select class="form-control" name="item_type" required>
                                                    <option value="">Select Type</option>
                                                    <option value="consumable" <?php echo $item['item_type'] === 'consumable' ? 'selected' : ''; ?>>Consumable</option>
                                                    <option value="medical_device" <?php echo $item['item_type'] === 'medical_device' ? 'selected' : ''; ?>>Medical Device</option>
                                                    <option value="implant" <?php echo $item['item_type'] === 'implant' ? 'selected' : ''; ?>>Implant</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="required-field">Category</label>
                                                <select class="form-control select2" name="category_id" required>
                                                    <option value="">Select Category</option>
                                                    <?php 
                                                    if($categories && mysqli_num_rows($categories) > 0) {
                                                        while($cat = mysqli_fetch_assoc($categories)) {
                                                            $selected = $cat['id'] == $item['category_id'] ? 'selected' : '';
                                                            echo "<option value='{$cat['id']}' $selected>{$cat['category_name']}</option>";
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="required-field">Unit of Measurement</label>
                                        <select class="form-control select2" name="unit_id" required>
                                            <option value="">Select Unit</option>
                                            <?php 
                                            if($units && mysqli_num_rows($units) > 0) {
                                                while($unit = mysqli_fetch_assoc($units)) {
                                                    $selected = $unit['id'] == $item['unit_id'] ? 'selected' : '';
                                                    echo "<option value='{$unit['id']}' $selected>{$unit['unit_name']}</option>";
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label>Description</label>
                                        <textarea class="form-control" name="description" rows="3" placeholder="Item description..."><?php echo htmlspecialchars($item['description'] ?: ''); ?></textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- Pricing Information -->
                            <div class="card form-card">
                                <div class="card-header">
                                    <i class="fa fa-tag"></i> Pricing Information
                                </div>
                                <div class="card-body form-section">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="required-field">Unit Cost (₹)</label>
                                                <input type="number" class="form-control" name="unit_cost" step="0.01" min="0" value="<?php echo $item['unit_cost']; ?>" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="required-field">Billing Price (₹)</label>
                                                <input type="number" class="form-control" name="billing_price" step="0.01" min="0" value="<?php echo $item['billing_price']; ?>" required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="alert alert-info">
                                        <small>
                                            <strong>Markup:</strong> 
                                            <?php 
                                            if($item['unit_cost'] > 0) {
                                                $markup = (($item['billing_price'] - $item['unit_cost']) / $item['unit_cost']) * 100;
                                                echo number_format($markup, 2) . '%';
                                            } else {
                                                echo 'N/A';
                                            }
                                            ?>
                                        </small>
                                    </div>
                                </div>
                            </div>

                            <!-- Inventory Information -->
                            <div class="card form-card">
                                <div class="card-header">
                                    <i class="fa fa-boxes"></i> Inventory Information
                                </div>
                                <div class="card-body form-section">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="required-field">Minimum Stock Level</label>
                                                <input type="number" class="form-control" name="min_stock" step="0.01" min="0" value="<?php echo $item['min_stock']; ?>" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="required-field">Reorder Level</label>
                                                <input type="number" class="form-control" name="reorder_level" step="0.01" min="0" value="<?php echo $item['reorder_level']; ?>" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Requirements -->
                            <div class="card form-card">
                                <div class="card-header">
                                    <i class="fa fa-check-square"></i> Item Requirements
                                </div>
                                <div class="card-body form-section">
                                    <div class="form-group">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="expiryRequired" name="is_expiry_required" value="1" <?php echo $item['is_expiry_required'] ? 'checked' : ''; ?>>
                                            <label class="custom-control-label" for="expiryRequired">
                                                Expiry Date Required
                                            </label>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="serialRequired" name="is_serial_required" value="1" <?php echo $item['is_serial_required'] ? 'checked' : ''; ?>>
                                            <label class="custom-control-label" for="serialRequired">
                                                Serial Number Required
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Save Buttons -->
                            <div class="save-buttons">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fa fa-save"></i> Save Changes
                                </button>
                                <a href="view_cathlab_item.php?id=<?php echo $item_id; ?>" class="btn btn-secondary btn-lg">
                                    <i class="fa fa-times"></i> Cancel
                                </a>
                            </div>
                        </form>
                    </div>
                </div>

            </div>

        </div>

        <?php include ('footer.php')?>

    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

    <script>
        $(document).ready(function() {
            $('.select2').select2();
        });
    </script>

</body>

</html>

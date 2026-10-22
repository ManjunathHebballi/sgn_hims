<?php 
require_once(__DIR__ . '/model/session_config.php');
date_default_timezone_set("Asia/Kolkata");  
include('model/storeClass.php');
$newStore = new store;
$adjustment_number = $newStore->generateAdjustmentNumber();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="theme-color" content="#319197">

    <title>Stock Adjustment Entry</title>
    <link rel="icon" type="image/png" sizes="16x16" href="../images/favicon.png">
    <link href="css/style.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />

    <style>
    .adjustment-form {
        background-color: #f8f9fa;
        padding: 20px;
        border-radius: 8px;
        border-left: 4px solid #007bff;
    }

    /* Toggle Switch Styling */
    .toggle-switch {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-top: 8px;
    }

    .toggle-label {
        display: flex;
        align-items: center;
        cursor: pointer;
        user-select: none;
    }

    .toggle-input {
        display: none;
    }

    .toggle-slider {
        position: relative;
        display: inline-block;
        width: 60px;
        height: 32px;
        background-color: #e0e0e0;
        border-radius: 16px;
        transition: all 0.3s ease;
        margin-right: 10px;
        border: 2px solid #ccc;
    }

    .toggle-slider::after {
        content: '';
        position: absolute;
        width: 26px;
        height: 26px;
        border-radius: 50%;
        background-color: white;
        top: 2px;
        left: 2px;
        transition: all 0.3s ease;
        box-shadow: 0 2px 4px rgba(0,0,0,0.2);
    }

    .toggle-input:checked + .toggle-slider {
        background-color: #28a745;
        border-color: #28a745;
    }

    .toggle-input:checked + .toggle-slider::after {
        left: 32px;
    }

    .toggle-text {
        font-weight: 600;
        font-size: 14px;
        min-width: 100px;
    }

    .toggle-text.add {
        color: #28a745;
    }

    .toggle-text.remove {
        color: #dc3545;
    }

    .adjustment-type-badge {
        display: inline-block;
        padding: 8px 15px;
        border-radius: 20px;
        font-weight: bold;
        margin: 5px 0;
    }

    .adjustment-type-badge.add {
        background-color: #d4edda;
        color: #155724;
    }

    .adjustment-type-badge.remove {
        background-color: #f8d7da;
        color: #721c24;
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
                        <h4 class="text-uppercase">📦 Stock Adjustment Entry</h4>
                    </div>
                </div>

                <form action="controller/processStore.php" method='POST' id="adjustmentForm">

                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header bg-primary text-white">
                                    <h5>Adjustment Details</h5>
                                </div>
                                <div class="card-body adjustment-form">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Adjustment Number <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="adjustment_number"
                                                    value="<?= $adjustment_number?>" readonly required>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Adjustment Date <span class="text-danger">*</span></label>
                                                <input type="date" class="form-control" name="adjustment_date"
                                                    value="<?= date('Y-m-d')?>" required>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Item <span class="text-danger">*</span></label>
                                                <select class="form-control" name="item_id" id="item_id" required>
                                                    <option value="">Select Item</option>
                                                    <?php 
                                                    $items = $newStore->getActiveItems();
                                                    while($item = mysqli_fetch_assoc($items)) {
                                                        echo "<option value='{$item['id']}' data-category='{$item['category_id']}'>{$item['item_code']} - {$item['item_name']}</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Unit</label>
                                                <input type="text" class="form-control" id="unit_name" readonly>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Batch Number</label>
                                                <select class="form-control" name="batch_number" id="batch_select">
                                                    <option value="">Select Batch (Optional)</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Adjustment Type <span class="text-danger">*</span></label>
                                                <div class="toggle-switch">
                                                    <label class="toggle-label">
                                                        <input type="checkbox" class="toggle-input" id="type_toggle" name="adjustment_type_toggle">
                                                        <span class="toggle-slider"></span>
                                                        <span class="toggle-text remove" id="toggle-text">REMOVE Stock</span>
                                                    </label>
                                                </div>
                                                <input type="hidden" id="adjustment_type_value" name="adjustment_type" value="REMOVE">
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Current Stock</label>
                                                <input type="text" class="form-control" id="current_stock" readonly>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Adjustment Quantity <span class="text-danger">*</span></label>
                                                <input type="number" class="form-control" name="adjustment_qty" 
                                                    id="adjustment_qty" step="0.01" min="0" required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Reason for Adjustment <span class="text-danger">*</span></label>
                                                <select class="form-control" name="reason" required>
                                                    <option value="">Select Reason</option>
                                                    <option value="Damaged">Damaged</option>
                                                    <option value="Expired">Expired</option>
                                                    <option value="Lost">Lost</option>
                                                    <option value="Stock Count Discrepancy">Stock Count Discrepancy</option>
                                                    <option value="Physical Verification">Physical Verification</option>
                                                    <option value="Transfer">Transfer</option>
                                                    <option value="Return">Return</option>
                                                    <option value="Opening Stock">Opening Stock</option>
                                                    <option value="Other">Other</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Additional Remarks</label>
                                                <input type="text" class="form-control" name="remarks" 
                                                    placeholder="Any additional details...">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="alert alert-info" id="preview" style="display:none;">
                                                <strong>Summary:</strong>
                                                <div id="summary-text"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 text-right">
                            <button type="reset" class="btn btn-secondary">
                                <i class="fa fa-refresh"></i> Clear
                            </button>
                            <button type="submit" class="btn btn-success btn-lg" name="submit" value="store_adjustment">
                                <i class="fa fa-save"></i> Save Adjustment
                            </button>
                        </div>
                    </div>

                    <input type="hidden" name="condition" value="new">

                </form>

            </div>

        </div>

        <?php include('footer.php') ?>

    </div>

    <script src="vendor/global/global.min.js"></script>
    <script src="js/quixnav-init.js"></script>
    <script src="js/custom.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

    <script>
    $(document).ready(function() {
        $('#item_id').select2({
            placeholder: 'Select Item',
            width: '100%'
        });

        // Load item details when item is selected
        $('#item_id').change(function() {
            var item_id = $(this).val();
            if (item_id) {
                $.ajax({
                    url: 'ajax/getItemBatches.php',
                    type: 'POST',
                    data: {
                        item_id: item_id
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            // Update unit
                            $('#unit_name').val(response.unit_name);
                            $('#current_stock').val(parseFloat(response.current_stock).toFixed(2));

                            // Update batch dropdown
                            var batchSelect = $('#batch_select');
                            batchSelect.html('<option value="">Select Batch (Optional)</option>');
                            
                            if (response.batches.length > 0) {
                                response.batches.forEach(function(batch) {
                                    batchSelect.append(
                                        '<option value="' + batch.batch_no + '">' + 
                                        batch.batch_no + ' (' + batch.available_qty + ' available)' + 
                                        '</option>'
                                    );
                                });
                            }
                        }
                    }
                });
            } else {
                $('#unit_name').val('');
                $('#current_stock').val('');
                $('#batch_select').html('<option value="">Select Batch (Optional)</option>');
            }
        });

        // Update preview on form changes
        function updatePreview() {
            var item_text = $('#item_id option:selected').text();
            var adj_type = $('input[name="adjustment_type"]:checked').val();
            var adj_qty = $('#adjustment_qty').val();
            var reason = $('select[name="reason"]').val();

            if (item_text && adj_type && adj_qty && reason) {
                var summary = item_text + ' | Type: <strong>' + adj_type + '</strong> | Qty: <strong>' + adj_qty + '</strong> | Reason: <strong>' + reason + '</strong>';
                $('#summary-text').html(summary);
                $('#preview').show();
            } else {
                $('#preview').hide();
            }
        }

        $('input[name="adjustment_type"], #adjustment_qty, select[name="reason"]').change(updatePreview);
        $('input[name="adjustment_type"], #adjustment_qty, select[name="reason"]').on('input', updatePreview);
    });
    </script>

</body>

</html>

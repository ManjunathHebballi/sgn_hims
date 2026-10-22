<?php
require_once(__DIR__ . '/model/session_config.php');
error_reporting(E_ALL);
ini_set('display_errors', 1);
date_default_timezone_set("Asia/Kolkata");

include('model/storeClass.php');
$newStore = new store;

// Get CathLab store ID
$cathlab_store_result = mysqli_query($db, "SELECT id FROM store WHERE store_type = 'cathlab' LIMIT 1");
$cathlab_store = mysqli_fetch_assoc($cathlab_store_result);
$store_id = $cathlab_store['id'] ?? 0;

if($store_id == 0) {
    die("CathLab store not configured. Please contact administrator.");
}

// Generate GRN number
$grn_result = mysqli_query($db, "SELECT MAX(CAST(SUBSTRING(grn_number, LOCATE('-', grn_number, LOCATE('-', grn_number) + 1) + 1) AS UNSIGNED)) as max_num FROM store_grn WHERE store_id = $store_id");
$grn_row = mysqli_fetch_assoc($grn_result);
$next_num = ($grn_row['max_num'] ?? 0) + 1;
$grn_number = "CATHLAB-GRN-" . date('Y-m-d') . "-" . str_pad($next_num, 4, '0', STR_PAD_LEFT);

// Get suppliers
$suppliers_result = mysqli_query($db, "SELECT id, supplier_name, phone FROM store_supplier WHERE status = 'active'");

// Get CathLab items
$items_result = mysqli_query($db, "SELECT id, item_code, item_name, cost_price, unit_id FROM cathlab_store_items WHERE store_id = $store_id AND status = 'active'");

// Get units
$units_result = mysqli_query($db, "SELECT id, unit_name FROM store_unit");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="theme-color" content="#667eea">
    <title>CathLab GRN Entry</title>
    <link rel="icon" type="image/png" sizes="16x16" href="../images/favicon.png">
    <link href="css/style.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

    <style>
    .page-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 30px;
        border-radius: 8px;
        margin-bottom: 30px;
    }

    .page-header h1 {
        margin: 0;
        font-size: 28px;
    }

    .page-header p {
        margin: 5px 0 0 0;
        opacity: 0.9;
    }

    .section-card {
        background: white;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 20px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        border-left: 4px solid #667eea;
    }

    .section-title {
        font-size: 16px;
        font-weight: bold;
        margin-bottom: 15px;
        color: #333;
        padding-bottom: 10px;
        border-bottom: 2px solid #667eea;
    }

    .item-row {
        background: #f8f9fa;
        padding: 12px;
        margin-bottom: 10px;
        border-radius: 5px;
        border-left: 3px solid #667eea;
    }

    .summary-box {
        background: linear-gradient(135deg, #f0f0ff 0%, #e8eaf6 100%);
        padding: 15px;
        border-radius: 5px;
        text-align: center;
        margin: 10px 0;
    }

    .summary-label {
        font-size: 12px;
        color: #666;
        text-transform: uppercase;
    }

    .summary-value {
        font-size: 24px;
        font-weight: bold;
        color: #667eea;
    }

    .btn-group-custom {
        display: flex;
        gap: 10px;
        margin-top: 20px;
    }

    .btn-group-custom .btn {
        flex: 1;
        padding: 12px;
        font-weight: bold;
    }

    .mandatory-field {
        color: #dc3545;
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

                <!-- Header -->
                <div class="page-header">
                    <h1><i class="fas fa-boxes"></i> CathLab GRN Entry</h1>
                    <p>Process goods receipt for CathLab consumables and implants</p>
                </div>

                <form action="controller/processCathLab.php" method="POST" id="grnForm">
                    <input type="hidden" name="store_id" value="<?= $store_id ?>">
                    <input type="hidden" name="action" value="saveCathLabGRN">

                    <!-- GRN Header Section -->
                    <div class="section-card">
                        <div class="section-title"><i class="fas fa-file-alt"></i> GRN Header Details</div>

                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>GRN Number <span class="mandatory-field">*</span></label>
                                    <input type="text" class="form-control" name="grn_number" value="<?= $grn_number ?>" readonly>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>GRN Date <span class="mandatory-field">*</span></label>
                                    <input type="date" class="form-control" name="grn_date" value="<?= date('Y-m-d') ?>" required>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Supplier <span class="mandatory-field">*</span></label>
                                    <select class="form-control select2" name="supplier_id" id="supplier_id" required>
                                        <option value="">-- Select Supplier --</option>
                                        <?php 
                                        mysqli_data_seek($suppliers_result, 0);
                                        while($supplier = mysqli_fetch_assoc($suppliers_result)) {
                                            echo "<option value='{$supplier['id']}'>{$supplier['supplier_name']} ({$supplier['phone']})</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Invoice Number</label>
                                    <input type="text" class="form-control" name="invoice_number" placeholder="Supplier Invoice #">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Invoice Date</label>
                                    <input type="date" class="form-control" name="invoice_date">
                                </div>
                            </div>

                            <div class="col-md-9">
                                <div class="form-group">
                                    <label>Remarks</label>
                                    <input type="text" class="form-control" name="remarks" placeholder="Any special notes about this GRN">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Items Section -->
                    <div class="section-card">
                        <div class="section-title"><i class="fas fa-plus-circle"></i> Add Items to GRN</div>

                        <div class="row">
                            <div class="col-md-3">
                                <label>Item <span class="mandatory-field">*</span></label>
                                <select class="form-control select2" id="item_id" name="temp_item_id">
                                    <option value="">-- Select Item --</option>
                                    <?php 
                                    mysqli_data_seek($items_result, 0);
                                    while($item = mysqli_fetch_assoc($items_result)) {
                                        echo "<option value='{$item['id']}' data-price='{$item['cost_price']}'>{$item['item_name']} ({$item['item_code']})</option>";
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="col-md-2">
                                <label>Quantity <span class="mandatory-field">*</span></label>
                                <input type="number" class="form-control" id="quantity" placeholder="Qty" step="0.01" min="0.01">
                            </div>

                            <div class="col-md-2">
                                <label>Cost Price <span class="mandatory-field">*</span></label>
                                <input type="number" class="form-control" id="cost_price" placeholder="Price" step="0.01" min="0">
                            </div>

                            <div class="col-md-2">
                                <label>Batch No <span class="mandatory-field">*</span></label>
                                <input type="text" class="form-control" id="batch_number" placeholder="Batch #">
                            </div>

                            <div class="col-md-2">
                                <label>Expiry Date <span class="mandatory-field">*</span></label>
                                <input type="date" class="form-control" id="expiry_date">
                            </div>

                            <div class="col-md-1">
                                <label>&nbsp;</label>
                                <button type="button" class="btn btn-success btn-block" onclick="addItemToGRN()">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </div>

                        <hr class="my-3">

                        <!-- Items Added List -->
                        <div id="itemsContainer">
                            <p class="text-muted text-center">No items added yet</p>
                        </div>
                    </div>

                    <!-- Summary Section -->
                    <div class="section-card">
                        <div class="section-title"><i class="fas fa-chart-bar"></i> GRN Summary</div>

                        <div class="row">
                            <div class="col-md-3">
                                <div class="summary-box">
                                    <div class="summary-label">Total Items</div>
                                    <div class="summary-value" id="totalItems">0</div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="summary-box">
                                    <div class="summary-label">Total Quantity</div>
                                    <div class="summary-value" id="totalQty">0</div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="summary-box">
                                    <div class="summary-label">Total Cost Value</div>
                                    <div class="summary-value">₹ <span id="totalCost">0.00</span></div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Received By <span class="mandatory-field">*</span></label>
                                    <input type="text" class="form-control" name="received_by" value="<?= $_SESSION['name'] ?? '' ?>" readonly>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="btn-group-custom">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Save CathLab GRN
                        </button>
                        <button type="reset" class="btn btn-secondary">
                            <i class="fas fa-redo"></i> Clear Form
                        </button>
                        <a href="dashboard.php" class="btn btn-info">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                    </div>

                </form>

            </div>
        </div>

        <?php include('footer.php'); ?>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

    <script>
        $(document).ready(function() {
            $('.select2').select2();
            
            // Update cost price when item is selected
            $('#item_id').change(function() {
                const price = $(this).find('option:selected').data('price');
                $('#cost_price').val(price || '');
            });
        });

        let grnItems = [];

        function addItemToGRN() {
            const itemId = $('#item_id').val();
            const itemName = $('#item_id option:selected').text();
            const quantity = parseFloat($('#quantity').val());
            const costPrice = parseFloat($('#cost_price').val());
            const batchNo = $('#batch_number').val();
            const expiryDate = $('#expiry_date').val();

            if (!itemId || !quantity || !costPrice || !batchNo || !expiryDate) {
                alert('Please fill all mandatory fields (Item, Qty, Price, Batch, Expiry)');
                return;
            }

            const totalAmount = (quantity * costPrice).toFixed(2);

            grnItems.push({
                item_id: itemId,
                item_name: itemName,
                quantity: quantity,
                cost_price: costPrice,
                batch_number: batchNo,
                expiry_date: expiryDate,
                total_amount: totalAmount
            });

            renderItems();
            clearItemForm();
        }

        function renderItems() {
            let html = '';
            let totalItems = 0;
            let totalQty = 0;
            let totalCost = 0;

            grnItems.forEach((item, index) => {
                totalItems++;
                totalQty += item.quantity;
                totalCost += parseFloat(item.total_amount);

                html += `
                    <div class="item-row">
                        <div class="row align-items-center">
                            <div class="col-md-4">
                                <strong>${item.item_name}</strong><br>
                                <small>Batch: ${item.batch_number} | Expiry: ${item.expiry_date}</small>
                            </div>
                            <div class="col-md-2">
                                Qty: <strong>${item.quantity}</strong>
                            </div>
                            <div class="col-md-2">
                                Price: ₹<strong>${item.cost_price.toFixed(2)}</strong>
                            </div>
                            <div class="col-md-2">
                                Amount: ₹<strong>${item.total_amount}</strong>
                            </div>
                            <div class="col-md-2 text-right">
                                <button type="button" class="btn btn-sm btn-danger" onclick="removeItem(${index})">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                `;
            });

            $('#itemsContainer').html(html || '<p class="text-muted text-center">No items added yet</p>');
            $('#totalItems').text(totalItems);
            $('#totalQty').text(totalQty.toFixed(2));
            $('#totalCost').text(totalCost.toFixed(2));

            // Add hidden inputs for form submission
            updateHiddenInputs();
        }

        function removeItem(index) {
            grnItems.splice(index, 1);
            renderItems();
        }

        function clearItemForm() {
            $('#item_id').val('').trigger('change');
            $('#quantity').val('');
            $('#cost_price').val('');
            $('#batch_number').val('');
            $('#expiry_date').val('');
        }

        function updateHiddenInputs() {
            let html = '';
            grnItems.forEach((item, index) => {
                html += `
                    <input type="hidden" name="items[${index}][item_id]" value="${item.item_id}">
                    <input type="hidden" name="items[${index}][quantity]" value="${item.quantity}">
                    <input type="hidden" name="items[${index}][cost_price]" value="${item.cost_price}">
                    <input type="hidden" name="items[${index}][batch_number]" value="${item.batch_number}">
                    <input type="hidden" name="items[${index}][expiry_date]" value="${item.expiry_date}">
                `;
            });
            
            $('#grnForm').find('.hidden-items').remove();
            if(html) {
                $('#grnForm').append(`<div class="hidden-items">${html}</div>`);
            }
        }

        // Form validation
        $('#grnForm').submit(function(e) {
            if(grnItems.length === 0) {
                e.preventDefault();
                alert('Please add at least one item to the GRN');
                return false;
            }
        });
    </script>

</body>

</html>

<?php 
require_once(__DIR__ . '/model/session_config.php');
error_reporting(E_ALL);
ini_set('display_errors', 1);
date_default_timezone_set("Asia/Kolkata");  
include('model/cathLabClass.php');
$newStore = new cathLab;
$grn_number = $newStore->generateCathLabGRNNumber();

$po_id = isset($_GET['po_id']) ? intval($_GET['po_id']) : 0;
$po_data = null;
if($po_id > 0) {
    $po_data = $newStore->getPurchaseOrderById($po_id);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="theme-color" content="#319197">

    <title>CathLab GRN Entry</title>
    <link rel="icon" type="image/png" sizes="16x16" href="../images/favicon.png">
    <link href="css/style.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />

    <style>
    .cathlab-badge {
        display: inline-block;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 8px 16px;
        border-radius: 20px;
        font-weight: bold;
        margin-bottom: 20px;
    }

    .item-row {
        background-color: #f8f9fa;
        padding: 10px;
        margin-bottom: 10px;
        border-radius: 5px;
        border-left: 4px solid #667eea;
    }

    .card-header.cathlab {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .form-section-header {
        background-color: #f0f0f5;
        padding: 15px;
        border-radius: 5px;
        margin-top: 20px;
        margin-bottom: 10px;
        font-weight: bold;
        color: #667eea;
    }

    .quantity-controls {
        display: flex;
        gap: 10px;
        align-items: center;
    }

    .quantity-controls button {
        padding: 5px 10px;
        font-size: 12px;
    }

    .item-summary {
        background-color: #e8eaf6;
        padding: 15px;
        border-radius: 5px;
        margin-top: 10px;
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
                        <span class="cathlab-badge">🏥 CATHLAB SUB-STORE</span>
                        <h4 class="text-uppercase">📦 CathLab GRN (Goods Receipt Note) Entry</h4>
                        <p class="text-muted">Process consumables and medical items received at CathLab</p>
                    </div>
                </div>

                <form action="controller/processCathLab.php" method='POST' id="grnForm">

                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header cathlab">
                                    <h5>GRN Details</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>GRN Number <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="grn_number"
                                                    value="<?= $grn_number?>" readonly required>
                                                <input type="hidden" name="grn_type" value="cathlab">
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>GRN Date <span class="text-danger">*</span></label>
                                                <input type="date" class="form-control" name="grn_date"
                                                    value="<?= date('Y-m-d')?>" required>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Purchase Order</label>
                                                <select class="form-control select2" name="po_id" id="po_id">
                                                    <option value="">Select PO (Optional)</option>
                                                    <?php 
                                                    $pos = $newStore->getPendingPurchaseOrders();
                                                    if($pos && mysqli_num_rows($pos) > 0) {
                                                        while($po = mysqli_fetch_assoc($pos)) {
                                                            $selected = ($po['id'] == $po_id) ? 'selected' : '';
                                                            echo "<option value='{$po['id']}' {$selected}>{$po['po_number']} - {$po['supplier_name']}</option>";
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Supplier <span class="text-danger">*</span></label>
                                                <select class="form-control select2" name="supplier_id" id="supplier_id"
                                                    required>
                                                    <option value="">Select Supplier</option>
                                                    <?php 
                                                    $suppliers = $newStore->getActiveSuppliers();
                                                    if($suppliers && mysqli_num_rows($suppliers) > 0) {
                                                        while($sup = mysqli_fetch_assoc($suppliers)) {
                                                            $selected = ($po_data && $sup['id'] == $po_data['supplier_id']) ? 'selected' : '';
                                                            echo "<option value='{$sup['id']}' {$selected}>{$sup['supplier_name']}</option>";
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Invoice Number</label>
                                                <input type="text" class="form-control" name="invoice_number"
                                                    placeholder="Supplier Invoice No">
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Invoice Date</label>
                                                <input type="date" class="form-control" name="invoice_date">
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Remarks</label>
                                                <input type="text" class="form-control" name="remarks"
                                                    placeholder="Any remarks about the goods received">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Items Section -->
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header cathlab">
                                    <h5>Received Items</h5>
                                </div>
                                <div class="card-body">
                                    <div class="form-section-header">
                                        ➕ Add Item to GRN
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Select Item <span class="text-danger">*</span></label>
                                                <select class="form-control select2" id="item_id" name="temp_item_id">
                                                    <option value="">Choose Consumable/Medical Item</option>
                                                    <?php 
                                                    $items = $newStore->getStoreItems();
                                                    if($items && mysqli_num_rows($items) > 0) {
                                                        while($item = mysqli_fetch_assoc($items)) {
                                                            echo "<option value='{$item['id']}' data-unit='{$item['unit_id']}'>{$item['item_name']} ({$item['item_code']})</option>";
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label>Quantity <span class="text-danger">*</span></label>
                                                <input type="number" class="form-control" id="temp_quantity" name="temp_quantity" 
                                                    placeholder="Qty" min="1" step="0.01">
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label>Rate <span class="text-danger">*</span></label>
                                                <input type="number" class="form-control" id="temp_rate" name="temp_rate" 
                                                    placeholder="Unit Rate" min="0" step="0.01">
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label>Batch No</label>
                                                <input type="text" class="form-control" id="temp_batch_no" name="temp_batch_no" 
                                                    placeholder="Batch No">
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label>Expiry Date</label>
                                                <input type="date" class="form-control" id="temp_expiry_date" name="temp_expiry_date">
                                            </div>
                                        </div>

                                        <div class="col-md-1">
                                            <div class="form-group">
                                                <label>&nbsp;</label>
                                                <button type="button" class="btn btn-success btn-block" onclick="addItemToGRN()">
                                                    <i class="fa fa-plus"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <hr>

                                    <div id="itemsContainer">
                                        <!-- Items will be added here dynamically -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Summary Section -->
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header cathlab">
                                    <h5>GRN Summary</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="item-summary">
                                                <div class="text-muted small">Total Items</div>
                                                <div class="h4" id="totalItems">0</div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="item-summary">
                                                <div class="text-muted small">Total Quantity</div>
                                                <div class="h4" id="totalQty">0</div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="item-summary">
                                                <div class="text-muted small">Total Amount</div>
                                                <div class="h4" id="totalAmount">₹ 0.00</div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="item-summary">
                                                <div class="text-muted small">Received By</div>
                                               <input class="form-control" name="received_by" value="<?php echo $_SESSION['name']; ?>" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="row mt-3 mb-3">
                        <div class="col-md-12">
                            <button type="submit" name="action" value="saveCathLabGRN" class="btn btn-primary btn-lg">
                                <i class="fa fa-save"></i> Save CathLab GRN
                            </button>
                            <button type="reset" class="btn btn-secondary btn-lg">
                                <i class="fa fa-refresh"></i> Clear Form
                            </button>
                            <a href="cathlab_store_dashboard.php" class="btn btn-info btn-lg">
                                <i class="fa fa-arrow-left"></i> Back to Dashboard
                            </a>
                        </div>
                    </div>

                </form>

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
            $('#preloader').hide();
            $('#main-wrapper').show();
        });

        let grnItems = [];

        function addItemToGRN() {
            const itemId = $('#item_id').val();
            const quantity = parseFloat($('#temp_quantity').val());
            const rate = parseFloat($('#temp_rate').val());
            const batchNo = $('#temp_batch_no').val();
            const expiryDate = $('#temp_expiry_date').val();

            if (!itemId || !quantity || !rate) {
                alert('Please fill in Item, Quantity, and Rate');
                return;
            }

            const itemText = $('#item_id option:selected').text();
            const amount = (quantity * rate).toFixed(2);

            const item = {
                item_id: itemId,
                item_name: itemText,
                quantity: quantity,
                rate: rate,
                amount: amount,
                batch_no: batchNo,
                expiry_date: expiryDate
            };

            grnItems.push(item);
            renderGRNItems();
            clearItemForm();
        }

        function renderGRNItems() {
            let html = '';
            let totalQty = 0;
            let totalAmount = 0;

            grnItems.forEach((item, index) => {
                totalQty += item.quantity;
                totalAmount += parseFloat(item.amount);

                html += `
                    <div class="item-row">
                        <div class="row">
                            <div class="col-md-4">
                                <strong>${item.item_name}</strong>
                            </div>
                            <div class="col-md-2">
                                <span>Qty: ${item.quantity}</span>
                            </div>
                            <div class="col-md-2">
                                <span>Rate: ₹${item.rate.toFixed(2)}</span>
                            </div>
                            <div class="col-md-2">
                                <span>Amount: ₹${item.amount}</span>
                            </div>
                            <div class="col-md-2 text-right">
                                <button type="button" class="btn btn-sm btn-danger" onclick="removeItem(${index})">
                                    <i class="fa fa-trash"></i> Remove
                                </button>
                            </div>
                        </div>
                        ${item.batch_no ? `<small class="text-muted">Batch: ${item.batch_no} | Expiry: ${item.expiry_date}</small>` : ''}
                    </div>
                `;
            });

            $('#itemsContainer').html(html);
            $('#totalItems').text(grnItems.length);
            $('#totalQty').text(totalQty.toFixed(2));
            $('#totalAmount').text('₹ ' + totalAmount.toFixed(2));

            // Add hidden inputs for form submission
            let hiddenInputs = '';
            grnItems.forEach((item, index) => {
                hiddenInputs += `
                    <input type="hidden" name="grn_items[${index}][item_id]" value="${item.item_id}">
                    <input type="hidden" name="grn_items[${index}][quantity]" value="${item.quantity}">
                    <input type="hidden" name="grn_items[${index}][rate]" value="${item.rate}">
                    <input type="hidden" name="grn_items[${index}][batch_no]" value="${item.batch_no}">
                    <input type="hidden" name="grn_items[${index}][expiry_date]" value="${item.expiry_date}">
                `;
            });
            $('#grnForm').append(`<div id="hiddenItems">${hiddenInputs}</div>`);
        }

        function removeItem(index) {
            grnItems.splice(index, 1);
            renderGRNItems();
        }

        function clearItemForm() {
            $('#item_id').val('').trigger('change');
            $('#temp_quantity').val('');
            $('#temp_rate').val('');
            $('#temp_batch_no').val('');
            $('#temp_expiry_date').val('');
        }

        // Form validation before submission
        $('#grnForm').on('submit', function(e) {
            if (grnItems.length === 0) {
                e.preventDefault();
                alert('Please add at least one item to the GRN');
                return false;
            }
        });
    </script>

<input class="form-control" name="received_by" value="<?php echo $_SESSION['name']; ?>" readonly></body>

</html>

<?php 
require_once(__DIR__ . '/model/session_config.php');
error_reporting(E_ALL);
ini_set('display_errors', 1);
date_default_timezone_set("Asia/Kolkata");  
include('model/storeClass.php');
$newStore = new store;

// Check if editing
$editMode = false;
$po = null;
$po_items = null;
$po_number = $newStore->generatePONumber();

if(isset($_REQUEST['id']) && !empty($_REQUEST['id'])) {
    $editMode = true;
    $po_id = $_REQUEST['id'];
    
    $po = $newStore->getPurchaseOrderById($po_id);
       
    if(!$po) {
        echo "<script>alert('Purchase Order not found!'); window.location='list_store_po.php'</script>";
        exit();
    }
    
    // Prevent editing non-PENDING orders
    if($po['status'] != 'PENDING') {
        echo "<script>alert('Only PENDING Purchase Orders can be edited!'); window.location='view_store_po.php?id=$po_id'</script>";
        exit();
    }
    
    $po_number = $po['po_number'];
    $po_items = $newStore->getPOItemsByPOId($po_id);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="theme-color" content="#319197">

    <title><?php echo $editMode ? 'Edit' : 'Create'; ?> Purchase Order - <?php if($editMode) echo $po['po_number']; ?></title>
    <link rel="icon" type="image/png" sizes="16x16" href="../images/favicon.png">
    <link href="css/style.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />

    <style>
    .item-row {
        background-color: #f8f9fa;
        padding: 8px;
        margin-bottom: 8px;
        border-radius: 5px;
        border-left: 4px solid #007bff;
    }
    
    .item-row .form-group {
        margin-bottom: 0.5rem;
    }
    
    .item-row label {
        margin-bottom: 0.2rem;
    }
    
    .item-row .form-control-sm {
        font-size: 0.85rem;
        padding: 0.25rem 0.5rem;
        height: auto;
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
                        <h4 class="text-uppercase">
                            <?php if($editMode): ?>
                                ✏️ Edit Purchase Order (<?php echo $po['po_number']; ?>)
                            <?php else: ?>
                                🛒 Purchase Order Entry
                            <?php endif; ?>
                        </h4>
                    </div>
                </div>

                <form action="controller/processStore.php" method='POST' id="poForm">

                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header bg-primary text-white">
                                    <h5>PO Details</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>PO Number <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="po_number"
                                                    value="<?= $po_number?>" readonly required>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>PO Date <span class="text-danger">*</span></label>
                                                <input type="date" class="form-control" name="po_date"
                                                    value="<?php echo $editMode ? date('Y-m-d', strtotime($po['po_date'])) : date('Y-m-d'); ?>" required>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Supplier <span class="text-danger">*</span></label>
                                                <select class="form-control" name="supplier_id" id="supplier_id"
                                                    required>
                                                    <option value="">Select Supplier</option>
                                                    <?php 
                                    $suppliers = $newStore->getActiveSuppliers();
                                    while($sup = mysqli_fetch_assoc($suppliers)) {
                                        $selected = ($editMode && $sup['id'] == $po['supplier_id']) ? 'selected' : '';
                                        echo "<option value='{$sup['id']}' $selected>{$sup['supplier_name']}</option>";
                                    }
                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Expected Delivery Date</label>
                                                <input type="date" class="form-control" name="expected_delivery_date"
                                                    value="<?php echo $editMode && $po['expected_date'] ? date('Y-m-d', strtotime($po['expected_date'])) : ''; ?>">
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Payment Terms</label>
                                                <select class="form-control" name="payment_terms">
                                                    <option value="IMMEDIATE" <?php echo ($editMode && $po['payment_terms'] == 'IMMEDIATE') ? 'selected' : ''; ?>>Immediate</option>
                                                    <option value="NET_7" <?php echo ($editMode && $po['payment_terms'] == 'NET_7') ? 'selected' : ''; ?>>Net 7 Days</option>
                                                    <option value="NET_15" <?php echo ($editMode && $po['payment_terms'] == 'NET_15') || (!$editMode) ? 'selected' : ''; ?>>Net 15 Days</option>
                                                    <option value="NET_30" <?php echo ($editMode && $po['payment_terms'] == 'NET_30') ? 'selected' : ''; ?>>Net 30 Days</option>
                                                    <option value="NET_45" <?php echo ($editMode && $po['payment_terms'] == 'NET_45') ? 'selected' : ''; ?>>Net 45 Days</option>
                                                    <option value="NET_60" <?php echo ($editMode && $po['payment_terms'] == 'NET_60') ? 'selected' : ''; ?>>Net 60 Days</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Remarks</label>
                                                <input type="text" class="form-control" name="remarks"
                                                    value="<?php echo $editMode ? htmlspecialchars($po['remarks']) : ''; ?>"
                                                    placeholder="Any special instructions">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header bg-success text-white">
                                    <h5>Purchase Order Items</h5>
                                </div>
                                <div class="card-body">
                                    <div id="itemsContainer">
                                        <!-- Items will be added here -->
                                    </div>

                                    <button type="button" class="btn btn-sm btn-primary" id="addItemBtn">
                                        <i class="fa fa-plus"></i> Add Item
                                    </button>

                                    <div class="row mt-3">
                                        <div class="col-md-6"></div>
                                        <div class="col-md-6">
                                            <div class="card bg-light">
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <h6><strong>Amount: ₹<span id="totalAmount">0.00</span></strong></h6>
                                                        </div>
                                                        <div class="col-md-6 text-right">
                                                            <span class="badge badge-success">GST Enabled</span>
                                                        </div>
                                                    </div>
                                                    <hr>
                                                    <h6>GST: ₹<span id="totalGst">0.00</span></h6>
                                                    <hr>
                                                    <h5 class="text-right"><strong>Total (with GST): ₹<span id="totalNetAmount">0.00</span></strong></h5>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 text-right">
                            <input type="hidden" name="total_amount" id="total_amount_input" value="0">
                            <button type="submit" class="btn btn-success btn-lg" name="submit" value="store_po">
                                <i class="fa fa-save"></i> <?php echo $editMode ? 'Update Purchase Order' : 'Save Purchase Order'; ?>
                            </button>
                            <?php if($editMode): ?>
                                <a href="view_store_po.php?id=<?php echo $po['id']; ?>" class="btn btn-secondary btn-lg">
                                    <i class="fa fa-times"></i> Cancel
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>

                    <input type="hidden" name="condition" value="<?php echo $editMode ? 'edit' : 'new'; ?>">
                    <?php if($editMode): ?>
                        <input type="hidden" name="po_id" value="<?php echo $po['id']; ?>">
                    <?php endif; ?>

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
        console.log('Document ready');
        <?php if($editMode): ?>
        console.log('Edit mode: TRUE');
        <?php else: ?>
        console.log('Edit mode: FALSE');
        <?php endif; ?>
        
        $('#supplier_id').select2({
            placeholder: 'Select Supplier',
            width: '100%'
        });

        let itemCount = 0;

        $('#addItemBtn').click(function() {
            addItemRow();
        });

        function addItemRow() {
            itemCount++;
            let html = `
            <div class="item-row" id="item_${itemCount}">
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label><small>Item <span class="text-danger">*</span></small></label>
                            <select class="form-control form-control-sm item-select" name="item_id[]" required>
                                <option value="">Select</option>
                                <?php 
                                $items = $newStore->getActiveItems();
                                while($item = mysqli_fetch_assoc($items)) {
                                    echo "<option value='{$item['id']}' data-unit='{$item['unit_name']}' data-category='{$item['category_id']}'>{$item['item_name']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <label><small>Qty <span class="text-danger">*</span></small></label>
                            <input type="number" class="form-control form-control-sm quantity" name="quantity[]" step="0.01" required>
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <label><small>Unit</small></label>
                            <input type="text" class="form-control form-control-sm unit" readonly style="font-size: 0.85rem;">
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <label><small>Rate <span class="text-danger">*</span></small></label>
                            <input type="number" class="form-control form-control-sm rate" name="rate[]" step="0.01" required>
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <label><small>Amount</small></label>
                            <input type="number" class="form-control form-control-sm amount" name="amount[]" readonly style="font-size: 0.85rem;">
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <label><small>GST %</small></label>
                             <select  class="form-control gst-percent" name="gst_percent[]" step="0.01" required>
                                <option value="0">0%</option>
                                <option value="5">5%</option>
                                <option value="12">12%</option>
                                <option value="18">18%</option>
                                <option value="28">28%</option>
                                <option value="40">40%</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <label><small>GST Amt</small></label>
                            <input type="number" class="form-control form-control-sm gst-amount" name="gst_amount[]" step="0.01" readonly style="font-size: 0.85rem;">
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <label><small>Net Amt</small></label>
                            <input type="number" class="form-control form-control-sm net-amount" name="net_amount[]" step="0.01" readonly style="font-size: 0.85rem;">
                        </div>
                    </div>
                    <div class="col-md-0.5">
                        <div class="form-group">
                            <label>&nbsp;</label><br>
                            <button type="button" class="btn btn-sm btn-danger remove-item" style="padding: 0.25rem 0.5rem; font-size: 0.85rem;">
                                <i class="fa fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            `;

            $('#itemsContainer').append(html);

            // Initialize Select2 for new item dropdown
            $(`#item_${itemCount} .item-select`).select2({
                placeholder: 'Select Item',
                width: '100%'
            });
        }

        // Calculate amount on quantity or rate change
        $(document).on('input', '.quantity, .rate', function() {
            let row = $(this).closest('.item-row');
            let qty = parseFloat(row.find('.quantity').val()) || 0;
            let rate = parseFloat(row.find('.rate').val()) || 0;
            let amount = qty * rate;
            row.find('.amount').val(amount.toFixed(2));
            calculateGstAndTotal(row);
            calculateTotal();
        });
        
        // Calculate GST on percentage change
        $(document).on('input', '.gst-percent', function() {
            let row = $(this).closest('.item-row');
            calculateGstAndTotal(row);
            calculateTotal();
        });
        
        // Calculate GST and Net Amount for a row
        function calculateGstAndTotal(row) {
            let amount = parseFloat(row.find('.amount').val()) || 0;
            let gst_percent = parseFloat(row.find('.gst-percent').val()) || 0;
            let gst_amount = (amount * gst_percent) / 100;
            let net_amount = amount + gst_amount;
            
            row.find('.gst-amount').val(gst_amount.toFixed(2));
            row.find('.net-amount').val(net_amount.toFixed(2));
        }

        // Set unit and auto-fill GST % when item is selected
        $(document).on('change', '.item-select', function() {
            let unit = $(this).find(':selected').data('unit');
            let category = $(this).find(':selected').data('category');
            let row = $(this).closest('.item-row');
            row.find('.unit').val(unit);
            
            // Auto-fill GST % based on category (you can modify this)
            if(category) {
                $.ajax({
                    url: 'ajax/getItemGstRate.php',
                    type: 'POST',
                    data: { category_id: category },
                    success: function(response) {
                        let gst_rate = parseFloat(response) || 0;
                        row.find('.gst-percent').val(gst_rate.toFixed(2));
                        calculateGstAndTotal(row);
                        calculateTotal();
                    }
                });
            }
        });

        // Remove item row
        $(document).on('click', '.remove-item', function() {
            $(this).closest('.item-row').remove();
            calculateTotal();
        });

        // Calculate total amount
        function calculateTotal() {
            let total_amount = 0;
            let total_gst = 0;
            let total_net = 0;
            
            $('.amount').each(function() {
                total_amount += parseFloat($(this).val()) || 0;
            });
            
            $('.gst-amount').each(function() {
                total_gst += parseFloat($(this).val()) || 0;
            });
            
            $('.net-amount').each(function() {
                total_net += parseFloat($(this).val()) || 0;
            });
            
            $('#totalAmount').text(total_amount.toFixed(2));
            $('#totalGst').text(total_gst.toFixed(2));
            $('#totalNetAmount').text(total_net.toFixed(2));
            $('#total_amount_input').val(total_net.toFixed(2));
        }

        // Function to add item row with pre-filled data (for edit mode)
        function addItemRowWithData(item_id, quantity, rate, gst_percent, unit_name, po_item_id = null) {
        itemCount++;
        let html = `
        <div class="item-row" id="item_${itemCount}">
            ${po_item_id ? '<input type="hidden" name="po_item_id[]" value="' + po_item_id + '">' : '<input type="hidden" name="po_item_id[]" value="">'}
            <div class="row">
                <div class="col-md-2">
                    <div class="form-group">
                        <label><small>Item <span class="text-danger">*</span></small></label>
                        <select class="form-control form-control-sm item-select" name="item_id[]" required>
                            <option value="">Select</option>
                            <?php 
                            $items = $newStore->getActiveItems();
                            while($item = mysqli_fetch_assoc($items)) {
                                echo "<option value='{$item['id']}' data-unit='{$item['unit_name']}' data-category='{$item['category_id']}'>{$item['item_name']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="form-group">
                        <label><small>Qty <span class="text-danger">*</span></small></label>
                        <input type="number" class="form-control form-control-sm quantity" name="quantity[]" step="0.01" required>
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="form-group">
                        <label><small>Unit</small></label>
                        <input type="text" class="form-control form-control-sm unit" readonly style="font-size: 0.85rem;">
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="form-group">
                        <label><small>Rate <span class="text-danger">*</span></small></label>
                        <input type="number" class="form-control form-control-sm rate" name="rate[]" step="0.01" required>
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="form-group">
                        <label><small>Amount</small></label>
                        <input type="number" class="form-control form-control-sm amount" name="amount[]" readonly style="font-size: 0.85rem;">
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="form-group">
                        <label><small>GST %</small></label>
                         <select  class="form-control gst-percent" name="gst_percent[]" step="0.01" required>
                            <option value="0">0%</option>
                            <option value="5">5%</option>
                            <option value="12">12%</option>
                            <option value="18">18%</option>
                            <option value="28">28%</option>
                            <option value="40">40%</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="form-group">
                        <label><small>GST Amt</small></label>
                        <input type="number" class="form-control form-control-sm gst-amount" name="gst_amount[]" step="0.01" readonly style="font-size: 0.85rem;">
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="form-group">
                        <label><small>Net Amt</small></label>
                        <input type="number" class="form-control form-control-sm net-amount" name="net_amount[]" step="0.01" readonly style="font-size: 0.85rem;">
                    </div>
                </div>
                <div class="col-md-0.5">
                    <div class="form-group">
                        <label>&nbsp;</label><br>
                        <button type="button" class="btn btn-sm btn-danger remove-item" style="padding: 0.25rem 0.5rem; font-size: 0.85rem;">
                            <i class="fa fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        `;

        $('#itemsContainer').append(html);

        // Capture current itemCount for this specific row
        let currentItemCount = itemCount;

        // Initialize Select2 for new item dropdown
        $(`#item_${currentItemCount} .item-select`).select2({
            placeholder: 'Select Item',
            width: '100%'
        });
        
        // If editing, set the values after a small delay to ensure Select2 is ready
        if(item_id) {
            setTimeout(function() {
                $(`#item_${currentItemCount} .item-select`).val(item_id).trigger('change');
                $(`#item_${currentItemCount} .quantity`).val(quantity);
                $(`#item_${currentItemCount} .rate`).val(rate);
                $(`#item_${currentItemCount} .gst-percent`).val(gst_percent);
                $(`#item_${currentItemCount} .unit`).val(unit_name);
                
                // Calculate amount
                let amount = quantity * rate;
                $(`#item_${currentItemCount} .amount`).val(amount.toFixed(2));
                
                // Calculate GST
                let gst_amount = (amount * gst_percent) / 100;
                let net_amount = amount + gst_amount;
                $(`#item_${currentItemCount} .gst-amount`).val(gst_amount.toFixed(2));
                $(`#item_${currentItemCount} .net-amount`).val(net_amount.toFixed(2));
                
                calculateTotal();
            }, 100);
        }
    }

    // Add first row on load
    <?php if($editMode && $po_items): ?>
        // Load existing items in edit mode
        console.log('Edit mode detected, loading existing items...');
        let existingItems = <?php 
            $itemsArray = [];
            mysqli_data_seek($po_items, 0);
            while($item = mysqli_fetch_assoc($po_items)) {
                $itemsArray[] = $item;
            }
            echo json_encode($itemsArray);
        ?>;
        
        console.log('Existing items:', existingItems);
        
        existingItems.forEach(function(item) {
            console.log('Adding item:', item);
            addItemRowWithData(item.item_id, item.quantity, item.rate, item.gst_percent, item.unit_name, item.id);
        });
    <?php else: ?>
        addItemRow();
    <?php endif; ?>
});
    </script>

</body>

</html>
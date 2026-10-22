<?php 
require_once(__DIR__ . '/model/session_config.php');
error_reporting(E_ALL);
ini_set('display_errors', 1);
date_default_timezone_set("Asia/Kolkata");  
include('model/storeClass.php');
$newStore = new store;
$grn_number = $newStore->generateGRNNumber();

$po_id = isset($_GET['po_id']) ? intval($_GET['po_id']) : 0;
$po_data = null;
if($po_id > 0) {
    $po_data = $newStore->getPurchaseOrderById($po_id);
    if(!$po_data || !in_array($po_data['status'], array('APPROVED', 'PARTIALLY_RECEIVED'))) {
        echo "<script>alert('Only APPROVED Purchase Orders can be used to create GRN.'); window.location='view_store_po.php?id=$po_id'</script>";
        exit();
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

    <title>GRN Entry</title>
    <link rel="icon" type="image/png" sizes="16x16" href="../images/favicon.png">
    <link href="css/style.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />

    <style>
    .item-row {
        background-color: #f8f9fa;
        padding: 10px;
        margin-bottom: 10px;
        border-radius: 5px;
        border-left: 4px solid #28a745;
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
                        <h4 class="text-uppercase">📦 GRN (Goods Receipt Note) Entry</h4>
                    </div>
                </div>

                <form action="controller/processStore.php" method='POST' enctype="multipart/form-data" id="grnForm">

                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header bg-primary text-white">
                                    <h5>GRN Details</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>GRN Number <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="grn_number"
                                                    value="<?= $grn_number?>" readonly required>
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
                                                <select class="form-control" name="po_id" id="po_id">
                                                    <option value="">Select Approved PO (Optional)</option>
                                                    <?php 
                                                    $pos = $newStore->getPendingPurchaseOrders();
                                                    while($po = mysqli_fetch_assoc($pos)) {
                                                        $selected = ($po['id'] == $po_id) ? 'selected' : '';
                                                        echo "<option value='{$po['id']}' {$selected}>{$po['po_number']} - {$po['supplier_name']}</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Supplier <span class="text-danger">*</span></label>
                                                <select class="form-control" name="supplier_id" id="supplier_id"
                                                    required>
                                                    <option value="">Select Supplier</option>
                                                    <?php 
                                                    $suppliers = $newStore->getActiveSuppliers();
                                                    while($sup = mysqli_fetch_assoc($suppliers)) {
                                                        $selected = ($po_data && $sup['id'] == $po_data['supplier_id']) ? 'selected' : '';
                                                        echo "<option value='{$sup['id']}' {$selected}>{$sup['supplier_name']}</option>";
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

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Invoice Upload</label>
                                                <input type="file" class="form-control" name="invoice_file" id="invoice_file"
                                                    accept=".pdf,.jpg,.jpeg,.png">
                                                <small class="text-muted">PDF, JPG, PNG up to 5MB</small>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Remarks</label>
                                                <input type="text" class="form-control" name="remarks"
                                                    placeholder="Any remarks">
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
                                    <h5>GRN Items</h5>
                                </div>
                                <div class="card-body">
                                    <div id="itemsContainer">
                                        <!-- Items will be added here -->
                                    </div>

                                    <div class="row mt-3 grn-summary">
                                        <div class="col-md-4 ml-auto">
                                            <div class="table-responsive">
                                                <table class="table table-sm table-bordered mb-2">
                                                    <tr>
                                                        <th>Total Amount</th>
                                                        <td><input type="number" class="form-control form-control-sm" name="total_amount" id="total_amount" step="0.01" readonly></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Total GST</th>
                                                        <td><input type="number" class="form-control form-control-sm" name="total_gst_amount" id="total_gst_amount" step="0.01" readonly></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Total Net Amount</th>
                                                        <td><input type="number" class="form-control form-control-sm" name="total_net_amount" id="total_net_amount" step="0.01" readonly></td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                    </div>

                                    <button type="button" class="btn btn-sm btn-primary" id="addItemBtn">
                                        <i class="fa fa-plus"></i> Add Item
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 text-right">
                            <button type="submit" class="btn btn-success btn-lg" name="submit" value="store_grn">
                                <i class="fa fa-save"></i> Save GRN
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
        $('#supplier_id, #po_id').select2({
            width: '100%'
        });

        let itemCount = 0;

        $('#addItemBtn').click(function() {
            addItemRow();
        });

        $('#po_id').change(function() {
            var po_id = $(this).val();
            if (po_id) {
                loadPOItems(po_id);
            } else {
                $('#itemsContainer').empty();
                itemCount = 0;
                calculateTotal();
            }
        });

        function calculateRowAmounts(row) {
            let qty = parseFloat(row.find('.received-qty').val()) || 0;
            let rate = parseFloat(row.find('.rate').val()) || 0;
            let amount = qty * rate;
            let gstPercent = parseFloat(row.find('.gst-percent').val()) || 0;
            let gstAmount = (amount * gstPercent) / 100;
            let netAmount = amount + gstAmount;

            row.find('.amount').val(amount.toFixed(2));
            row.find('.gst-amount').val(gstAmount.toFixed(2));
            row.find('.net-amount').val(netAmount.toFixed(2));
        }

        function calculateTotal() {
            let totalAmount = 0;
            let totalGst = 0;
            let totalNet = 0;

            $('.item-row').each(function() {
                totalAmount += parseFloat($(this).find('.amount').val()) || 0;
                totalGst += parseFloat($(this).find('.gst-amount').val()) || 0;
                totalNet += parseFloat($(this).find('.net-amount').val()) || 0;
            });

            $('#total_amount').val(totalAmount.toFixed(2));
            $('#total_gst_amount').val(totalGst.toFixed(2));
            $('#total_net_amount').val(totalNet.toFixed(2));
        }

        function setRowGst(row, gstValue = null) {
            if (gstValue !== null && gstValue !== '' && !isNaN(gstValue)) {
                row.find('.gst-percent').val(String(parseFloat(gstValue) || 0));
                calculateRowAmounts(row);
                calculateTotal();
                return;
            }

            let category = row.find('.item-select option:selected').data('category');
            if (category) {
                $.ajax({
                    url: 'ajax/getItemGstRate.php',
                    type: 'POST',
                    data: { category_id: category },
                    success: function(response) {
                        let gstRate = parseFloat(response) || 0;
                        row.find('.gst-percent').val(String(gstRate));
                        calculateRowAmounts(row);
                        calculateTotal();
                    }
                });
            } else {
                row.find('.gst-percent').val('0');
                calculateRowAmounts(row);
                calculateTotal();
            }
        }

        function loadPOItems(po_id) {
            $.ajax({
                url: 'get_po_items.php',
                type: 'POST',
                data: {
                    po_id: po_id
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        $('#itemsContainer').empty();
                        itemCount = 0;
                        response.items.forEach(function(item) {
                            addItemRow(item);
                        });
                        $('#supplier_id').val(response.supplier_id).trigger('change');
                        calculateTotal();
                    }
                }
            });
        }

        function addItemRow(poItem = null) {
            itemCount++;
            let html = `
            <div class="item-row" id="item_${itemCount}">
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Item <span class="text-danger">*</span></label>
                            <select class="form-control item-select" name="item_id[]" required>
                                <option value="">Select Item</option>
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
                            <label>Ordr Qty</label>
                            <input type="number" class="form-control ordered-qty" step="0.01" readonly>
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <label>Pending</label>
                            <input type="number" class="form-control pending-qty" step="0.01" readonly>
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <label>Recv Qty <span class="text-danger">*</span></label>
                            <input type="number" class="form-control received-qty" name="received_quantity[]" step="0.01" required>
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <label>Unit</label>
                            <input type="text" class="form-control unit" readonly>
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <label>Rate <span class="text-danger">*</span></label>
                            <input type="number" class="form-control rate" name="rate[]" step="0.01" required>
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <label>Amount</label>
                            <input type="number" class="form-control amount" name="amount[]" step="0.01" readonly>
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <label>GST %</label>
                            <select class="form-control gst-percent" name="gst_percent[]">
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
                            <label>GST Amt</label>
                            <input type="number" class="form-control gst-amount" name="gst_amount[]" step="0.01" readonly>
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <label>Net Amt</label>
                            <input type="number" class="form-control net-amount" name="net_amount[]" step="0.01" readonly>
                        </div>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Batch No</label>
                            <input type="text" class="form-control" name="batch_number[]">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Expiry Date</label>
                            <input type="date" class="form-control" name="expiry_date[]">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Serial No</label>
                            <input type="text" class="form-control" name="serial_no[]">
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <label>&nbsp;</label><br>
                            <button type="button" class="btn btn-sm btn-danger remove-item">
                                <i class="fa fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            `;

            $('#itemsContainer').append(html);

            let row = $(`#item_${itemCount}`);
            let $select = row.find('.item-select');
            $select.select2({
                placeholder: 'Select Item',
                width: '100%'
            });

            if (poItem) {
                $select.val(poItem.item_id).trigger('change');
                row.find('.ordered-qty').val(poItem.ordered_quantity || '');
                row.find('.pending-qty').val(poItem.pending_quantity || '');
                row.find('.received-qty').val(poItem.pending_quantity || '');
                row.find('.rate').val(poItem.rate || '');
                row.find('.unit').val(poItem.unit_name || '');
                setRowGst(row, poItem.gst_percent);
            } else {
                calculateRowAmounts(row);
                calculateTotal();
            }
        }

        $(document).on('change', '.item-select', function() {
            let row = $(this).closest('.item-row');
            let unit = $(this).find(':selected').data('unit') || '';
            row.find('.unit').val(unit);
            setRowGst(row);
        });

        $(document).on('input change', '.received-qty, .rate, .gst-percent', function() {
            let row = $(this).closest('.item-row');
            calculateRowAmounts(row);
            calculateTotal();
        });

        $(document).on('click', '.remove-item', function() {
            $(this).closest('.item-row').remove();
            calculateTotal();
        });

        <?php if(!$po_id): ?>
        addItemRow();
        <?php else: ?>
        loadPOItems(<?= $po_id?>);
        <?php endif; ?>
    });
    </script>

</body>

</html>
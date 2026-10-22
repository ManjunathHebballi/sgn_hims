<?php 
require_once(__DIR__ . '/model/session_config.php');
date_default_timezone_set("Asia/Kolkata");  
include('model/storeClass.php');
$newStore = new store;
$invoice_number = $newStore->generateInvoiceNumber();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="theme-color" content="#319197">

    <title>Purchase Invoice Entry</title>
    <link rel="icon" type="image/png" sizes="16x16" href="../images/favicon.png">
    <link href="css/style.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

    <style>
    .invoice-items-table th {
        background-color: #343a40;
        color: white;
        font-size: 12px;
    }

    .invoice-items-table td input,
    .invoice-items-table td select {
        font-size: 12px;
        padding: 5px;
    }

    .total-section {
        background-color: #f8f9fa;
        padding: 20px;
        border-radius: 5px;
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
                        <h4 class="text-uppercase">📄 Purchase Invoice Entry</h4>
                    </div>
                </div>

                <form action="controller/processStore.php" method='POST' id="invoiceForm">

                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header bg-primary text-white">
                                    <h5>Invoice Details</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Invoice Number <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="invoice_number"
                                                    value="<?= $invoice_number?>" readonly required>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Invoice Date <span class="text-danger">*</span></label>
                                                <input type="date" class="form-control" name="invoice_date"
                                                    value="<?= date('Y-m-d')?>" required>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Supplier <span class="text-danger">*</span></label>
                                                <select class="form-control select2" name="supplier_id" id="supplier_id"
                                                    required>
                                                    <option value="">Select Supplier</option>
                                                    <?php 
                                    $suppliers = $newStore->getActiveSuppliers();
                                    while($sup = mysqli_fetch_assoc($suppliers)) {
                                        echo "<option value='{$sup['id']}'>{$sup['supplier_name']} - {$sup['phone']}</option>";
                                    }
                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Payment Terms</label>
                                                <select class="form-control" name="payment_terms" id="payment_terms">
                                                    <option value="NET 15">NET 15 Days</option>
                                                    <option value="NET 30" selected>NET 30 Days</option>
                                                    <option value="NET 45">NET 45 Days</option>
                                                    <option value="NET 60">NET 60 Days</option>
                                                    <option value="IMMEDIATE">Immediate</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Due Date <span class="text-danger">*</span></label>
                                                <input type="date" class="form-control" name="due_date" id="due_date"
                                                    required>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Link to GRN (Optional)</label>
                                                <select class="form-control" name="grn_id">
                                                    <option value="">No GRN Link</option>
                                                    <?php 
                                    $grns = $newStore->getGRNs();
                                    while($grn = mysqli_fetch_assoc($grns)) {
                                        echo "<option value='{$grn['id']}'>{$grn['grn_number']} - {$grn['supplier_name']}</option>";
                                    }
                                    ?>
                                                </select>
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
                                    <h5>Invoice Items</h5>
                                    <button type="button" class="btn btn-light btn-sm float-right"
                                        onclick="addInvoiceRow()">
                                        <i class="fa fa-plus"></i> Add Row
                                    </button>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered invoice-items-table" id="invoiceTable">
                                            <thead>
                                                <tr>
                                                    <th width="5%">S.No</th>
                                                    <th width="25%">Item</th>
                                                    <th width="10%">Batch</th>
                                                    <th width="8%">Qty</th>
                                                    <th width="10%">Rate</th>
                                                    <th width="10%">Amount</th>
                                                    <th width="8%">Tax%</th>
                                                    <th width="8%">Tax Amt</th>
                                                    <th width="8%">Disc%</th>
                                                    <th width="8%">Disc Amt</th>
                                                    <th width="10%">Net Amt</th>
                                                    <th width="5%">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody id="invoiceTableBody">
                                                <tr>
                                                    <td>1</td>
                                                    <td>
                                                        <select name="item_id[]" class="form-control item-select"
                                                            required>
                                                            <option value="">Select Item</option>
                                                            <?php 
                                            $items = $newStore->getActiveItems();
                                            while($item = mysqli_fetch_assoc($items)) {
                                                echo "<option value='{$item['id']}'>{$item['item_code']} - {$item['item_name']}</option>";
                                            }
                                            ?>
                                                        </select>
                                                    </td>
                                                    <td><input type="text" name="batch_no[]" class="form-control"
                                                            placeholder="Batch"></td>
                                                    <td><input type="number" name="quantity[]" class="form-control qty"
                                                            value="1" required></td>
                                                    <td><input type="number" name="rate[]" class="form-control rate"
                                                            step="0.01" value="0" required></td>
                                                    <td><input type="number" name="amount[]" class="form-control amount"
                                                            readonly></td>
                                                    <td><input type="number" name="tax_percent[]"
                                                            class="form-control tax-percent" step="0.01" value="0"></td>
                                                    <td><input type="number" name="tax_amount_item[]"
                                                            class="form-control tax-amount" readonly></td>
                                                    <td><input type="number" name="discount_percent[]"
                                                            class="form-control disc-percent" step="0.01" value="0">
                                                    </td>
                                                    <td><input type="number" name="discount_amount_item[]"
                                                            class="form-control disc-amount" readonly></td>
                                                    <td><input type="number" name="net_amount_item[]"
                                                            class="form-control net-amount" readonly></td>
                                                    <td><button type="button" class="btn btn-danger btn-sm"
                                                            onclick="removeRow(this)">X</button></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-8"></div>
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-body total-section">
                                    <table class="table table-sm table-borderless">
                                        <tr>
                                            <td><strong>Total Amount:</strong></td>
                                            <td class="text-right"><span id="display_total">0.00</span></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Total Tax:</strong></td>
                                            <td class="text-right"><span id="display_tax">0.00</span></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Total Discount:</strong></td>
                                            <td class="text-right"><span id="display_discount">0.00</span></td>
                                        </tr>
                                        <tr class="table-success">
                                            <td><strong>Net Amount:</strong></td>
                                            <td class="text-right">
                                                <h5><span id="display_net">0.00</span></h5>
                                            </td>
                                        </tr>
                                    </table>

                                    <input type="hidden" name="total_amount" id="total_amount">
                                    <input type="hidden" name="tax_amount" id="tax_amount">
                                    <input type="hidden" name="discount_amount" id="discount_amount">
                                    <input type="hidden" name="net_amount" id="net_amount">
                                    <input type="hidden" name="item_count" id="item_count" value="1">
                                    <input type="hidden" name="condition" value="new">

                                    <button type="submit" class="btn btn-success btn-block" name="submit"
                                        value="store_invoice">
                                        <i class="fa fa-save"></i> Save Invoice
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                </form>

            </div>

        </div>

        <?php include('footer.php') ?>

    </div>

    <script src="vendor/global/global.min.js"></script>
    <script src="js/quixnav-init.js"></script>
    <script src="js/custom.min.js"></script>

    <script>
    $(document).ready(function() {
        $('.select2').select2();
        calculateDueDate();

        // Calculate due date based on payment terms
        $('#payment_terms, input[name="invoice_date"]').change(function() {
            calculateDueDate();
        });
    });

    function calculateDueDate() {
        var invoiceDate = new Date($('input[name="invoice_date"]').val());
        var terms = $('#payment_terms').val();
        var days = 30; // default

        if (terms == 'NET 15') days = 15;
        else if (terms == 'NET 30') days = 30;
        else if (terms == 'NET 45') days = 45;
        else if (terms == 'NET 60') days = 60;
        else if (terms == 'IMMEDIATE') days = 0;

        invoiceDate.setDate(invoiceDate.getDate() + days);
        var dueDate = invoiceDate.toISOString().split('T')[0];
        $('#due_date').val(dueDate);
    }

    function addInvoiceRow() {
        var rowCount = $('#invoiceTableBody tr').length + 1;

        var items_html = '<option value="">Select Item</option>';
        <?php 
    $items = $newStore->getActiveItems();
    while($item = mysqli_fetch_assoc($items)) {
        echo "items_html += '<option value=\"{$item['id']}\">{$item['item_code']} - {$item['item_name']}</option>';";
    }
    ?>

        var html = '<tr>' +
            '<td>' + rowCount + '</td>' +
            '<td><select name="item_id[]" class="form-control item-select" required>' + items_html + '</select></td>' +
            '<td><input type="text" name="batch_no[]" class="form-control" placeholder="Batch"></td>' +
            '<td><input type="number" name="quantity[]" class="form-control qty" value="1" required></td>' +
            '<td><input type="number" name="rate[]" class="form-control rate" step="0.01" value="0" required></td>' +
            '<td><input type="number" name="amount[]" class="form-control amount" readonly></td>' +
            '<td><input type="number" name="tax_percent[]" class="form-control tax-percent" step="0.01" value="0"></td>' +
            '<td><input type="number" name="tax_amount_item[]" class="form-control tax-amount" readonly></td>' +
            '<td><input type="number" name="discount_percent[]" class="form-control disc-percent" step="0.01" value="0"></td>' +
            '<td><input type="number" name="discount_amount_item[]" class="form-control disc-amount" readonly></td>' +
            '<td><input type="number" name="net_amount_item[]" class="form-control net-amount" readonly></td>' +
            '<td><button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)">X</button></td>' +
            '</tr>';

        $('#invoiceTableBody').append(html);
        updateItemCount();
    }

    function removeRow(btn) {
        if ($('#invoiceTableBody tr').length > 1) {
            $(btn).closest('tr').remove();
            updateSerialNumbers();
            updateItemCount();
            calculateGrandTotal();
        } else {
            alert('At least one row is required!');
        }
    }

    function updateSerialNumbers() {
        $('#invoiceTableBody tr').each(function(index) {
            $(this).find('td:first').text(index + 1);
        });
    }

    function updateItemCount() {
        $('#item_count').val($('#invoiceTableBody tr').length);
    }

    // Calculate row amounts
    $(document).on('input', '.qty, .rate, .tax-percent, .disc-percent', function() {
        var row = $(this).closest('tr');
        calculateRowAmount(row);
        calculateGrandTotal();
    });

    function calculateRowAmount(row) {
        var qty = parseFloat(row.find('.qty').val()) || 0;
        var rate = parseFloat(row.find('.rate').val()) || 0;
        var tax_percent = parseFloat(row.find('.tax-percent').val()) || 0;
        var disc_percent = parseFloat(row.find('.disc-percent').val()) || 0;

        // Calculate amount
        var amount = qty * rate;
        row.find('.amount').val(amount.toFixed(2));

        // Calculate tax
        var tax_amount = (amount * tax_percent) / 100;
        row.find('.tax-amount').val(tax_amount.toFixed(2));

        // Calculate discount
        var disc_amount = (amount * disc_percent) / 100;
        row.find('.disc-amount').val(disc_amount.toFixed(2));

        // Calculate net amount
        var net_amount = amount + tax_amount - disc_amount;
        row.find('.net-amount').val(net_amount.toFixed(2));
    }

    function calculateGrandTotal() {
        var total_amount = 0;
        var total_tax = 0;
        var total_discount = 0;
        var net_amount = 0;

        $('#invoiceTableBody tr').each(function() {
            total_amount += parseFloat($(this).find('.amount').val()) || 0;
            total_tax += parseFloat($(this).find('.tax-amount').val()) || 0;
            total_discount += parseFloat($(this).find('.disc-amount').val()) || 0;
            net_amount += parseFloat($(this).find('.net-amount').val()) || 0;
        });

        $('#display_total').text(total_amount.toFixed(2));
        $('#display_tax').text(total_tax.toFixed(2));
        $('#display_discount').text(total_discount.toFixed(2));
        $('#display_net').text(net_amount.toFixed(2));

        $('#total_amount').val(total_amount.toFixed(2));
        $('#tax_amount').val(total_tax.toFixed(2));
        $('#discount_amount').val(total_discount.toFixed(2));
        $('#net_amount').val(net_amount.toFixed(2));
    }

    // Form validation
    $('#invoiceForm').submit(function(e) {
        if ($('#invoiceTableBody tr').length == 0) {
            e.preventDefault();
            alert('Please add at least one item!');
            return false;
        }

        var hasItems = false;
        $('#invoiceTableBody tr').each(function() {
            if ($(this).find('.item-select').val() != '') {
                hasItems = true;
            }
        });

        if (!hasItems) {
            e.preventDefault();
            alert('Please select at least one item!');
            return false;
        }

        return true;
    });
    </script>

</body>

</html>
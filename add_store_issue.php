<?php 
require_once(__DIR__ . '/model/session_config.php');
date_default_timezone_set("Asia/Kolkata");  
include('model/storeClass.php');
$newStore = new store;
$issue_number = $newStore->generateIssueNumber();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="theme-color" content="#319197">

    <title>Issue Items to Department</title>
    <link rel="icon" type="image/png" sizes="16x16" href="../images/favicon.png">
    <link href="css/style.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />

    <style>
    .issue-items-table th {
        background-color: #343a40;
        color: white;
        font-size: 12px;
    }

    .issue-items-table td input,
    .issue-items-table td select {
        font-size: 12px;
        padding: 5px;
    }

    .available-stock {
        font-size: 11px;
        color: #28a745;
        font-weight: bold;
    }

    .out-of-stock {
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

        <?php include ('leftbar.php')?>

        <div class="content-body">

            <div class="container-fluid">
                <div class="row mb-3">
                    <div class="col-md-12">
                        <h4 class="text-uppercase">📦 Issue Items to Department</h4>
                    </div>
                </div>

                <form action="controller/processStore.php" method='POST' id="issueForm">

                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header bg-primary text-white">
                                    <h5>Issue Details</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Issue Number <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="issue_number"
                                                    value="<?= $issue_number?>" readonly required>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Issue Date <span class="text-danger">*</span></label>
                                                <input type="date" class="form-control" name="issue_date"
                                                    value="<?= date('Y-m-d')?>" required>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Department <span class="text-danger">*</span></label>
                                                <select class="form-control" name="department" id="department" required>
                                                    <option value="">Select Sub Store</option>
                                                    <?php 
                                                    $substores = $newStore->getSubstores();
                                                    if($substores) {
                                                        while($row = mysqli_fetch_assoc($substores)) {
                                                            echo "<option value='{$row['store_name']}'>{$row['store_name']}</option>";
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Requested By</label>
                                                <input type="text" class="form-control" name="requested_by"
                                                    placeholder="Name of requester">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
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
                                    <h5>Items to Issue</h5>
                                    <button type="button" class="btn btn-light btn-sm float-right"
                                        onclick="addIssueRow()">
                                        <i class="fa fa-plus"></i> Add Row
                                    </button>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered issue-items-table" id="issueTable">
                                            <thead>
                                                <tr>
                                                    <th width="5%">S.No</th>
                                                    <th width="30%">Item</th>
                                                    <th width="20%">Batch No</th>
                                                    <th width="15%">Available Stock</th>
                                                    <th width="15%">Issue Qty</th>
                                                    <th width="10%">Serial No</th>
                                                    <th width="5%">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody id="issueTableBody">
                                                <tr>
                                                    <td>1</td>
                                                    <td>
                                                        <select name="item_id[]" class="form-control item-select"
                                                            onchange="loadBatches(this)" required>
                                                            <option value="">Select Item</option>
                                                            <?php 
                                            $items = $newStore->getActiveItems();
                                            while($item = mysqli_fetch_assoc($items)) {
                                                echo "<option value='{$item['id']}'>{$item['item_code']} - {$item['item_name']}</option>";
                                            }
                                            ?>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select name="batch_no[]" class="form-control batch-select"
                                                            onchange="updateAvailableStock(this)">
                                                            <option value="">Select Item First</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control available-stock"
                                                            readonly>
                                                        <input type="hidden" name="available_qty[]"
                                                            class="available-qty">
                                                    </td>
                                                    <td>
                                                        <input type="number" name="quantity[]"
                                                            class="form-control issue-qty" value="1" min="1" required
                                                            onchange="validateQty(this)">
                                                    </td>
                                                    <td>
                                                        <input type="text" name="serial_no[]" class="form-control"
                                                            placeholder="Serial (optional)">
                                                    </td>
                                                    <td>
                                                        <button type="button" class="btn btn-danger btn-sm"
                                                            onclick="removeRow(this)">X</button>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 text-right">
                            <input type="hidden" name="item_count" id="item_count" value="1">
                            <input type="hidden" name="condition" value="new">
                            <button type="submit" class="btn btn-success btn-lg" name="submit" value="store_issue">
                                <i class="fa fa-save"></i> Issue Items
                            </button>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

    <script>
    $(document).ready(function() {
        // Initialize Select2 on page load
        $('#department').select2({
            placeholder: 'Select Department',
            allowClear: true,
            width: '100%'
        });

        // Initialize any existing item selects
        initializeSelect2();
    });

    function initializeSelect2() {
        // Initialize Select2 on all item selects that don't have it yet
        $('.item-select:not(.select2-hidden-accessible)').select2({
            placeholder: 'Select Item',
            allowClear: true,
            width: '100%'
        });
    }

    function addIssueRow() {
        var rowCount = $('#issueTableBody tr').length + 1;

        var items_html = '<option value="">Select Item</option>';
        <?php 
    $items = $newStore->getActiveItems();
    while($item = mysqli_fetch_assoc($items)) {
        echo "items_html += '<option value=\"{$item['id']}\">{$item['item_code']} - {$item['item_name']}</option>';";
    }
    ?>

        var html = '<tr>' +
            '<td>' + rowCount + '</td>' +
            '<td><select name="item_id[]" class="form-control item-select" onchange="loadBatches(this)" required>' +
            items_html + '</select></td>' +
            '<td><select name="batch_no[]" class="form-control batch-select" onchange="updateAvailableStock(this)"><option value="">Select Item First</option></select></td>' +
            '<td><input type="text" class="form-control available-stock" readonly><input type="hidden" name="available_qty[]" class="available-qty"></td>' +
            '<td><input type="number" name="quantity[]" class="form-control issue-qty" value="1" min="1" required onchange="validateQty(this)"></td>' +
            '<td><input type="text" name="serial_no[]" class="form-control" placeholder="Serial (optional)"></td>' +
            '<td><button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)">X</button></td>' +
            '</tr>';

        $('#issueTableBody').append(html);
        initializeSelect2(); // Initialize Select2 on newly added row
        updateItemCount();
    }

    function removeRow(btn) {
        if ($('#issueTableBody tr').length > 1) {
            $(btn).closest('tr').remove();
            updateSerialNumbers();
            updateItemCount();
        } else {
            alert('At least one row is required!');
        }
    }

    function updateSerialNumbers() {
        $('#issueTableBody tr').each(function(index) {
            $(this).find('td:first').text(index + 1);
        });
    }

    function updateItemCount() {
        $('#item_count').val($('#issueTableBody tr').length);
    }

    function loadBatches(select) {
        var row = $(select).closest('tr');
        var item_id = $(select).val();
        var batch_select = row.find('.batch-select');

        if (item_id) {
            // Reset available stock when loading new batches
            row.find('.available-stock').val('');
            row.find('.available-qty').val('');
            row.find('.issue-qty').val('1').attr('max', '');
            batch_select.data('has-batches', 0);
            
            $.ajax({
                url: 'get_item_batches.php',
                type: 'POST',
                data: {
                    item_id: item_id
                },
                dataType: 'json',
                success: function(response) {
                    batch_select.html('<option value="">Select Batch</option>');
                    if (response.success && response.batches.length > 0) {
                        batch_select.data('has-batches', 1);
                        response.batches.forEach(function(batch) {
                            var batch_text = batch.batch_no ? batch.batch_no : 'No Batch';
                            batch_text += ' (Stock: ' + batch.available_qty + ')';
                            if (batch.expiry_date) {
                                batch_text += ' - Exp: ' + batch.expiry_date;
                            }
                            var batchNo = (batch.batch_no && batch.batch_no !== 'null') ? batch.batch_no : "";
                            var noBatchAttr = (batchNo === "") ? ' data-nobatch="1"' : '';
                            batch_select.append('<option value="' + batchNo + '"' + noBatchAttr +
                                ' data-stock="' + batch.available_qty + '">' + batch_text +
                                '</option>');
                        });
                    } else {
                        batch_select.data('has-batches', 0);
                        batch_select.html('<option value="">No Stock Available</option>');
                        row.find('.available-stock').val('0').addClass('out-of-stock');
                    }
                },
                error: function() {
                    batch_select.data('has-batches', 0);
                    batch_select.html('<option value="">Error loading batches</option>');
                }
            });
        } else {
            batch_select.html('<option value="">Select Item First</option>');
            batch_select.data('has-batches', 0);
            row.find('.available-stock').val('');
            row.find('.available-qty').val('');
        }
    }

    function updateAvailableStock(select) {
        var row = $(select).closest('tr');
        var selected_option = $(select).find('option:selected');
        var stock = selected_option.data('stock') || 0;

        row.find('.available-stock').val(stock).removeClass('out-of-stock');
        row.find('.available-qty').val(stock);

        if (stock == 0) {
            row.find('.available-stock').addClass('out-of-stock');
        }

        // Reset quantity to 1 or max available
        var max_qty = Math.min(stock, 1);
        row.find('.issue-qty').val(max_qty).attr('max', stock);
    }

    function validateQty(input) {
        var row = $(input).closest('tr');
        var available = parseInt(row.find('.available-qty').val()) || 0;
        var requested = parseInt($(input).val()) || 0;

        if (requested > available) {
            alert('Issue quantity cannot exceed available stock (' + available + ')');
            $(input).val(available);
        }

        if (requested < 1) {
            $(input).val(1);
        }
    }

    // Form validation
    $('#issueForm').submit(function(e) {
        if ($('#issueTableBody tr').length == 0) {
            e.preventDefault();
            alert('Please add at least one item!');
            return false;
        }

        var hasItems = false;
        var hasError = false;

        $('#issueTableBody tr').each(function() {
            var item_id = $(this).find('.item-select').val();
            var batch_select = $(this).find('.batch-select');
            var batch = batch_select.val();
            var selected_option = batch_select.find('option:selected');
            var no_batch_selected = selected_option.data('nobatch') == 1;
            var has_batches = batch_select.data('has-batches') == 1;
            var available = parseInt($(this).find('.available-qty').val()) || 0;
            var quantity = parseInt($(this).find('.issue-qty').val()) || 0;

            if (item_id) {
                hasItems = true;

                if (has_batches && !batch && !no_batch_selected) {
                    alert('Please select a batch for the selected item');
                    hasError = true;
                    return false;
                }

                if (quantity > available) {
                    alert('Issue quantity exceeds available stock');
                    hasError = true;
                    return false;
                }

                if (available == 0) {
                    alert('Cannot issue items with zero stock');
                    hasError = true;
                    return false;
                }
            }
        });

        if (!hasItems) {
            e.preventDefault();
            alert('Please select at least one item!');
            return false;
        }

        if (hasError) {
            e.preventDefault();
            return false;
        }

        return true;
    });
    </script>

</body>

</html>
<?php 
require_once(__DIR__ . '/model/session_config.php');
error_reporting(E_ALL);
ini_set('display_errors', 1);
date_default_timezone_set("Asia/Kolkata");  
include('model/storeClass.php');
$newStore = new store;
$payment_number = $newStore->generatePaymentNumber();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="theme-color" content="#319197">

    <title>Supplier Payment Entry</title>
    <link rel="icon" type="image/png" sizes="16x16" href="../images/favicon.png">
    <link href="css/style.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />

    <style>
    .invoice-allocation-table th {
        background-color: #28a745;
        color: white;
        font-size: 13px;
    }

    .payment-summary {
        background-color: #f0f8ff;
        padding: 20px;
        border-radius: 5px;
    }

    .badge-unpaid {
        background-color: #dc3545;
    }

    .badge-partial {
        background-color: #ffc107;
    }

    .badge-paid {
        background-color: #28a745;
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
                        <h4 class="text-uppercase">💳 Supplier Payment Entry</h4>
                    </div>
                </div>

                <form action="controller/processStore.php" method='POST' id="paymentForm">

                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header bg-primary text-white">
                                    <h5>Payment Details</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Payment Number <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="payment_number"
                                                    value="<?= $payment_number?>" readonly required>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Payment Date <span class="text-danger">*</span></label>
                                                <input type="date" class="form-control" name="payment_date"
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
                                        $balance = $newStore->getSupplierBalance($sup['id']);
                                        echo "<option value='{$sup['id']}' data-balance='{$balance}'>{$sup['supplier_name']} - Outstanding: ₹" . number_format($balance, 2) . "</option>";
                                    }
                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Payment Mode <span class="text-danger">*</span></label>
                                                <select class="form-control" name="payment_mode" required>
                                                    <option value="CASH">Cash</option>
                                                    <option value="CHEQUE">Cheque</option>
                                                    <option value="BANK_TRANSFER" selected>Bank Transfer</option>
                                                    <option value="UPI">UPI</option>
                                                    <option value="CARD">Card</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Reference Number</label>
                                                <input type="text" class="form-control" name="reference_number"
                                                    placeholder="Cheque/Trans No">
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Payment Amount <span class="text-danger">*</span></label>
                                                <input type="number" class="form-control" name="payment_amount"
                                                    id="payment_amount" step="0.01" required>
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
                                    <h5>Allocate Payment to Invoices</h5>
                                    <small>Select unpaid/partially paid invoices to allocate this payment</small>
                                </div>
                                <div class="card-body" id="invoiceListDiv" style="display:none;">
                                    <div class="alert alert-info">
                                        <strong>Supplier Outstanding Balance:</strong> ₹<span
                                            id="supplier_balance">0.00</span>
                                    </div>

                                    <div class="table-responsive">
                                        <table class="table table-bordered invoice-allocation-table" id="invoiceTable">
                                            <thead>
                                                <tr>
                                                    <th width="5%">
                                                        <input type="checkbox" id="select_all"> All
                                                    </th>
                                                    <th width="15%">Invoice No</th>
                                                    <th width="12%">Invoice Date</th>
                                                    <th width="12%">Due Date</th>
                                                    <th width="12%">Invoice Amount</th>
                                                    <th width="12%">Paid Amount</th>
                                                    <th width="12%">Balance</th>
                                                    <th width="10%">Status</th>
                                                    <th width="15%">Allocate Amount</th>
                                                </tr>
                                            </thead>
                                            <tbody id="invoiceTableBody">
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="row mt-3">
                                        <div class="col-md-8"></div>
                                        <div class="col-md-4">
                                            <div class="payment-summary">
                                                <table class="table table-sm">
                                                    <tr>
                                                        <td><strong>Payment Amount:</strong></td>
                                                        <td class="text-right"><span id="total_payment">0.00</span></td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Allocated Amount:</strong></td>
                                                        <td class="text-right"><span id="allocated_amount">0.00</span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Remaining:</strong></td>
                                                        <td class="text-right"><span id="remaining_amount">0.00</span>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 text-right">
                            <button type="submit" class="btn btn-success btn-lg" name="submit" value="store_payment">
                                <i class="fa fa-save"></i> Save Payment
                            </button>
                        </div>
                    </div>

                    <input type="hidden" name="condition" value="new">
                    <input type="hidden" name="allocated_total" id="allocated_total">

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
        // Initialize Select2
        $('#supplier_id').select2({
            placeholder: 'Select Supplier',
            allowClear: true,
            width: '100%'
        });

        // Load unpaid invoices when supplier is selected
        $('#supplier_id').change(function() {
            var supplier_id = $(this).val();
            if (supplier_id) {
                $('#invoiceListDiv').show();
                var balance = parseFloat($(this).find(':selected').data('balance')) || 0;
                $('#supplier_balance').text(balance.toFixed(2));
                loadUnpaidInvoices(supplier_id);

            } else {
                $('#invoiceListDiv').hide();
            }
        });

        // Update totals when payment amount changes
        $('#payment_amount').on('input', function() {
            updatePaymentSummary();
        });

        // Select all checkboxes
        $('#select_all').click(function() {
            $('.invoice-checkbox').prop('checked', this.checked);
            autoAllocateAmounts();
        });

        // Auto-allocate when checkbox is clicked
        $(document).on('change', '.invoice-checkbox', function() {
            autoAllocateAmounts();
        });

        // Recalculate when allocation amount is manually changed
        $(document).on('input', '.allocate-input', function() {
            var balance = parseFloat($(this).data('balance'));
            var allocated = parseFloat($(this).val()) || 0;

            if (allocated > balance) {
                alert('Allocated amount cannot exceed invoice balance!');
                $(this).val(balance.toFixed(2));
            }

            updatePaymentSummary();
        });
    });

    function loadUnpaidInvoices(supplier_id) {
        $.ajax({
            url: 'get_unpaid_invoices.php',
            type: 'POST',
            data: {
                supplier_id: supplier_id
            },
            dataType: 'json',
            success: function(response) {
                console.log('Response:', response); // Debug log
                if (response.success) {
                    var html = '';
                    if (response.invoices.length > 0) {
                        response.invoices.forEach(function(inv) {
                            var status_badge = '';
                            if (inv.payment_status == 'UNPAID') {
                                status_badge = '<span class="badge badge-unpaid">Unpaid</span>';
                            } else if (inv.payment_status == 'PARTIALLY_PAID') {
                                status_badge = '<span class="badge badge-partial">Partial</span>';
                            }

                            html += '<tr>' +
                                '<td><input type="checkbox" class="invoice-checkbox" name="invoice_ids[]" value="' +
                                inv.id + '" data-balance="' + inv.balance_amount + '"></td>' +
                                '<td>' + inv.invoice_number + '</td>' +
                                '<td>' + inv.invoice_date + '</td>' +
                                '<td>' + inv.due_date + '</td>' +
                                '<td class="text-right">₹' + parseFloat(inv.net_amount).toFixed(2) +
                                '</td>' +
                                '<td class="text-right">₹' + parseFloat(inv.paid_amount).toFixed(
                                    2) + '</td>' +
                                '<td class="text-right">₹' + parseFloat(inv.balance_amount).toFixed(
                                    2) + '</td>' +
                                '<td>' + status_badge + '</td>' +
                                '<td><input type="number" name="allocated_amounts[]" class="form-control allocate-input" step="0.01" value="0" data-balance="' +
                                inv.balance_amount + '"></td>' +
                                '</tr>';
                        });
                    } else {
                        html =
                            '<tr><td colspan="9" class="text-center">No unpaid invoices found for this supplier</td></tr>';
                    }
                    $('#invoiceTableBody').html(html);
                } else {
                    console.error('Error:', response.message); // Debug log
                    alert('Error loading invoices: ' + response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', xhr.responseText); // Debug log
                alert('Error loading invoices. Please check console for details.');
            }
        });
    }

    function autoAllocateAmounts() {
        var payment_amount = parseFloat($('#payment_amount').val()) || 0;
        var remaining = payment_amount;

        $('.invoice-checkbox:checked').each(function() {
            var row = $(this).closest('tr');
            var balance = parseFloat($(this).data('balance'));
            var allocate_input = row.find('.allocate-input');

            if (remaining > 0) {
                if (remaining >= balance) {
                    allocate_input.val(balance.toFixed(2));
                    remaining -= balance;
                } else {
                    allocate_input.val(remaining.toFixed(2));
                    remaining = 0;
                }
            } else {
                allocate_input.val('0.00');
            }
        });

        // Clear amounts for unchecked invoices
        $('.invoice-checkbox:not(:checked)').each(function() {
            var row = $(this).closest('tr');
            row.find('.allocate-input').val('0.00');
        });

        updatePaymentSummary();
    }

    function updatePaymentSummary() {
        var payment_amount = parseFloat($('#payment_amount').val()) || 0;
        var allocated = 0;

        $('.allocate-input').each(function() {
            allocated += parseFloat($(this).val()) || 0;
        });

        var remaining = payment_amount - allocated;

        $('#total_payment').text(payment_amount.toFixed(2));
        $('#allocated_amount').text(allocated.toFixed(2));
        $('#remaining_amount').text(remaining.toFixed(2));
        $('#allocated_total').val(allocated.toFixed(2));

        // Change color based on remaining amount
        if (remaining < 0) {
            $('#remaining_amount').parent().parent().removeClass('table-success').addClass('table-danger');
        } else if (remaining > 0) {
            $('#remaining_amount').parent().parent().removeClass('table-danger table-success').addClass(
                'table-warning');
        } else {
            $('#remaining_amount').parent().parent().removeClass('table-danger table-warning').addClass(
                'table-success');
        }
    }

    // Form validation
    $('#paymentForm').submit(function(e) {
        var payment_amount = parseFloat($('#payment_amount').val()) || 0;
        var allocated = parseFloat($('#allocated_total').val()) || 0;

        if (payment_amount <= 0) {
            e.preventDefault();
            alert('Payment amount must be greater than zero!');
            return false;
        }

        if (allocated > payment_amount) {
            e.preventDefault();
            alert('Allocated amount cannot exceed payment amount!');
            return false;
        }

        var checked_count = $('.invoice-checkbox:checked').length;
        if (checked_count == 0) {
            if (!confirm('No invoices selected. Payment will be recorded as advance. Continue?')) {
                e.preventDefault();
                return false;
            }
        }

        return true;
    });
    </script>

</body>

</html>
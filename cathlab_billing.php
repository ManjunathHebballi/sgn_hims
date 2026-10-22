<?php
require_once(__DIR__ . '/model/session_config.php');
date_default_timezone_set("Asia/Kolkata");  
include('model/storeClass.php');
include('model/ipClass.php');
$newStore = new store;
$newIP = new addIP;
$billing_number = $newStore->generateCathLabBillingNumber();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="theme-color" content="#319197">

    <title>CathLab Billing</title>
    <link rel="icon" type="image/png" sizes="16x16" href="../images/favicon.png">
    <link href="css/style.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />

    <style>
    .cathlab-badge {
        display: inline-block;
        background: linear-gradient(135deg, #ff6b6b 0%, #ee5a6f 100%);
        color: white;
        padding: 8px 16px;
        border-radius: 20px;
        font-weight: bold;
        margin-bottom: 20px;
    }

    .patient-card {
        background-color: #f8f9fa;
        padding: 15px;
        border-radius: 8px;
        border-left: 5px solid #ff6b6b;
        margin-bottom: 20px;
    }

    .card-header.cathlab {
        background: linear-gradient(135deg, #ff6b6b 0%, #ee5a6f 100%);
        color: white;
    }

    .billing-item-row {
        background-color: #fff3f3;
        padding: 12px;
        margin-bottom: 10px;
        border-radius: 5px;
        border-left: 4px solid #ff6b6b;
    }

    .billing-summary {
        background: linear-gradient(135deg, #fff3f3 0%, #f0f0f5 100%);
        padding: 20px;
        border-radius: 8px;
        margin-top: 20px;
    }

    .amount-box {
        background-color: white;
        padding: 15px;
        border-radius: 5px;
        border: 2px solid #ff6b6b;
        margin-bottom: 10px;
        text-align: center;
    }

    .amount-box .label {
        font-size: 12px;
        color: #666;
        text-transform: uppercase;
    }

    .amount-box .value {
        font-size: 24px;
        font-weight: bold;
        color: #ff6b6b;
    }

    .item-search {
        position: relative;
    }

    .item-search-results {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        background: white;
        border: 1px solid #ddd;
        max-height: 300px;
        overflow-y: auto;
        z-index: 1000;
        display: none;
    }

    .item-search-result {
        padding: 10px;
        cursor: pointer;
        border-bottom: 1px solid #eee;
    }

    .item-search-result:hover {
        background-color: #f0f0f5;
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
                        <span class="cathlab-badge">🏥 CATHLAB BILLING</span>
                        <h4 class="text-uppercase">💳 Direct Billing to Patient</h4>
                        <p class="text-muted">Bill consumables and medical items used during CathLab procedures</p>
                    </div>
                </div>

                <form action="controller/processCathLab.php" method='POST' id="billingForm">

                    <!-- Patient Selection Section -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header cathlab">
                                    <h5>Select Patient</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Search Patient <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="patientSearch" 
                                                    placeholder="Search by IP Number, Name, or Mobile...">
                                                <small class="text-muted">Search for admitted patients in pre/post-operative or CathLab wards</small>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Patient IP Number <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="patient_ip_no" id="patientIpNo" 
                                                    placeholder="IP Number" readonly required>
                                                <input type="hidden" name="patient_id" id="patientId">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Patient Details Display -->
                                    <div id="patientDetailsContainer" style="display:none;">
                                        <div class="patient-card">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <small class="text-muted">Patient Name</small><br>
                                                    <strong id="patientName">-</strong>
                                                </div>
                                                <div class="col-md-3">
                                                    <small class="text-muted">Age/Gender</small><br>
                                                    <strong id="patientAge">-</strong>
                                                </div>
                                                <div class="col-md-3">
                                                    <small class="text-muted">Admission Date</small><br>
                                                    <strong id="patientAdmission">-</strong>
                                                </div>
                                                <div class="col-md-3">
                                                    <small class="text-muted">Ward</small><br>
                                                    <strong id="patientWard">-</strong>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-3">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Billing Date <span class="text-danger">*</span></label>
                                                <input type="date" class="form-control" name="billing_date" 
                                                    value="<?= date('Y-m-d')?>" required>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Procedure Type <span class="text-danger">*</span></label>
                                                <select class="form-control select2" name="procedure_type" required>
                                                    <option value="">Select Procedure</option>
                                                    <option value="angiography">Coronary Angiography</option>
                                                    <option value="angioplasty">Angioplasty</option>
                                                    <option value="stent">Stent Placement</option>
                                                    <option value="atherectomy">Atherectomy</option>
                                                    <option value="other">Other Procedure</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Billing Number</label>
                                                <input type="text" class="form-control" name="billing_number" 
                                                    value="<?= $billing_number?>" readonly>
                                                <input type="hidden" name="billing_type" value="cathlab">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Items to Bill Section -->
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header cathlab">
                                    <h5>Items Consumed During Procedure</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row mb-3">
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label>Select Item <span class="text-danger">*</span></label>
                                                <div class="item-search">
                                                    <input type="text" class="form-control" id="itemSearch" 
                                                        placeholder="Search CathLab consumables...">
                                                    <div class="item-search-results" id="itemSearchResults"></div>
                                                </div>
                                                <input type="hidden" id="selectedItemId" name="temp_item_id">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label>Quantity <span class="text-danger">*</span></label>
                                                <input type="number" class="form-control" id="itemQuantity" 
                                                    placeholder="Qty" min="1" step="0.01">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label>Unit Price</label>
                                                <input type="number" class="form-control" id="itemPrice" 
                                                    placeholder="Price" min="0" step="0.01" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label>Markup %</label>
                                                <input type="number" class="form-control" id="itemMarkup" 
                                                    placeholder="Markup %" value="0" min="0" step="0.01">
                                            </div>
                                        </div>
                                        <div class="col-md-1">
                                            <div class="form-group">
                                                <label>&nbsp;</label>
                                                <button type="button" class="btn btn-success btn-block" onclick="addItemToBilling()">
                                                    <i class="fa fa-plus"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <hr>

                                    <!-- Items Added to Billing -->
                                    <div id="billingItemsContainer">
                                        <!-- Items will be added dynamically -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Billing Summary -->
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <div class="billing-summary">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="amount-box">
                                            <div class="label">Subtotal</div>
                                            <div class="value" id="subtotal">₹ 0.00</div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="amount-box">
                                            <div class="label">Discount</div>
                                            <input type="number" class="form-control" name="discount_amount" 
                                                id="discountAmount" placeholder="0.00" min="0" step="0.01" 
                                                onchange="calculateTotal()">
                                            <small class="text-muted">Discount Amount</small>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="amount-box">
                                            <div class="label">GST (18%)</div>
                                            <div class="value" id="gstAmount">₹ 0.00</div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="amount-box" style="border-color: #28a745; background-color: #f0fff4;">
                                            <div class="label">Total Amount</div>
                                            <div class="value" style="color: #28a745;" id="totalBillingAmount">₹ 0.00</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Remarks and Actions -->
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Remarks</label>
                                <textarea class="form-control" name="remarks" rows="3" 
                                    placeholder="Any additional notes about the procedure or items used..."></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="row mt-3 mb-3">
                        <div class="col-md-12">
                            <button type="submit" name="action" value="saveCathLabBilling" class="btn btn-danger btn-lg">
                                <i class="fa fa-save"></i> Save Billing & Generate Invoice
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
        });

        let billingItems = [];

        // Patient Search
        $('#patientSearch').on('keyup', function() {
            const searchTerm = $(this).val();
            if (searchTerm.length < 2) return;
            
            $.ajax({
                url: 'ajax/searchPatients.php',
                type: 'POST',
                data: { search: searchTerm, status: 'admitted' },
                success: function(response) {
                    const patients = JSON.parse(response);
                    let html = '<ul class="list-group">';
                    patients.forEach(function(patient) {
                        html += `<li class="list-group-item cursor-pointer" onclick="selectPatient('${patient.id}', '${patient.ip_no}', '${patient.name}', '${patient.age}', '${patient.admission_date}', '${patient.ward}')">${patient.ip_no} - ${patient.name}</li>`;
                    });
                    html += '</ul>';
                    // Display results (modify as needed)
                }
            });
        });

        function selectPatient(patientId, ipNo, name, age, admissionDate, ward) {
            $('#patientId').val(patientId);
            $('#patientIpNo').val(ipNo);
            $('#patientName').text(name);
            $('#patientAge').text(age);
            $('#patientAdmission').text(admissionDate);
            $('#patientWard').text(ward);
            $('#patientDetailsContainer').show();
        }

        // Item Search
        $('#itemSearch').on('keyup', function() {
            const searchTerm = $(this).val();
            if (searchTerm.length < 2) {
                $('#itemSearchResults').hide();
                return;
            }
            
            $.ajax({
                url: 'ajax/searchCathLabItems.php',
                type: 'POST',
                data: { search: searchTerm },
                success: function(response) {
                    const items = JSON.parse(response);
                    let html = '';
                    items.forEach(function(item) {
                        html += `<div class="item-search-result" onclick="selectItem('${item.id}', '${item.name}', '${item.price}')">${item.name} (₹${item.price})</div>`;
                    });
                    $('#itemSearchResults').html(html).show();
                }
            });
        });

        function selectItem(itemId, itemName, itemPrice) {
            $('#selectedItemId').val(itemId);
            $('#itemSearch').val(itemName);
            $('#itemPrice').val(itemPrice);
            $('#itemSearchResults').hide();
        }

        function addItemToBilling() {
            const itemId = $('#selectedItemId').val();
            const itemName = $('#itemSearch').val();
            const quantity = parseFloat($('#itemQuantity').val());
            const unitPrice = parseFloat($('#itemPrice').val());
            const markup = parseFloat($('#itemMarkup').val() || 0);

            if (!itemId || !quantity || !unitPrice) {
                alert('Please select item, enter quantity and verify unit price');
                return;
            }

            const billingPrice = unitPrice + (unitPrice * markup / 100);
            const lineAmount = (quantity * billingPrice).toFixed(2);

            const item = {
                item_id: itemId,
                item_name: itemName,
                quantity: quantity,
                unit_price: unitPrice,
                billing_price: billingPrice.toFixed(2),
                markup: markup,
                amount: lineAmount
            };

            billingItems.push(item);
            renderBillingItems();
            clearItemForm();
            calculateTotal();
        }

        function renderBillingItems() {
            let html = '';
            billingItems.forEach((item, index) => {
                html += `
                    <div class="billing-item-row">
                        <div class="row">
                            <div class="col-md-5">
                                <strong>${item.item_name}</strong><br>
                                <small class="text-muted">Cost: ₹${item.unit_price} | Bill: ₹${item.billing_price}</small>
                            </div>
                            <div class="col-md-2">
                                <span>Qty: <strong>${item.quantity}</strong></span>
                            </div>
                            <div class="col-md-2">
                                ${item.markup > 0 ? `<small>Markup: ${item.markup}%</small><br>` : ''}
                                <span>Amount: ₹<strong>${item.amount}</strong></span>
                            </div>
                            <div class="col-md-3 text-right">
                                <button type="button" class="btn btn-sm btn-warning" onclick="editItem(${index})">
                                    <i class="fa fa-edit"></i> Edit
                                </button>
                                <button type="button" class="btn btn-sm btn-danger" onclick="removeItem(${index})">
                                    <i class="fa fa-trash"></i> Remove
                                </button>
                            </div>
                        </div>
                    </div>
                `;
            });

            $('#billingItemsContainer').html(html);

            // Add hidden inputs for form submission
            let hiddenInputs = '';
            billingItems.forEach((item, index) => {
                hiddenInputs += `
                    <input type="hidden" name="billing_items[${index}][item_id]" value="${item.item_id}">
                    <input type="hidden" name="billing_items[${index}][quantity]" value="${item.quantity}">
                    <input type="hidden" name="billing_items[${index}][unit_price]" value="${item.unit_price}">
                    <input type="hidden" name="billing_items[${index}][billing_price]" value="${item.billing_price}">
                    <input type="hidden" name="billing_items[${index}][markup]" value="${item.markup}">
                    <input type="hidden" name="billing_items[${index}][amount]" value="${item.amount}">
                `;
            });
            $('#billingForm').find('#hiddenBillingItems').remove();
            $('#billingForm').append(`<div id="hiddenBillingItems">${hiddenInputs}</div>`);
        }

        function removeItem(index) {
            billingItems.splice(index, 1);
            renderBillingItems();
            calculateTotal();
        }

        function clearItemForm() {
            $('#selectedItemId').val('');
            $('#itemSearch').val('');
            $('#itemQuantity').val('');
            $('#itemPrice').val('');
            $('#itemMarkup').val('0');
        }

        function calculateTotal() {
            let subtotal = 0;
            billingItems.forEach(item => {
                subtotal += parseFloat(item.amount);
            });

            const discount = parseFloat($('#discountAmount').val() || 0);
            const afterDiscount = subtotal - discount;
            const gst = afterDiscount * 0.18;
            const total = afterDiscount + gst;

            $('#subtotal').text('₹ ' + subtotal.toFixed(2));
            $('#gstAmount').text('₹ ' + gst.toFixed(2));
            $('#totalBillingAmount').text('₹ ' + total.toFixed(2));
        }

        // Form validation
        $('#billingForm').on('submit', function(e) {
            if (!$('#patientId').val()) {
                e.preventDefault();
                alert('Please select a patient');
                return false;
            }
            if (billingItems.length === 0) {
                e.preventDefault();
                alert('Please add at least one item to the billing');
                return false;
            }
        });
    </script>

</body>

</html>

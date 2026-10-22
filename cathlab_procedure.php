<?php
require_once(__DIR__ . '/model/session_config.php');
error_reporting(E_ALL);
ini_set('display_errors', 1);
date_default_timezone_set("Asia/Kolkata");

include('model/cathLabClass.php');
$newCathLab = new cathLab;

// Get CathLab store ID


// Get admission ID from parameter
$admission_id = isset($_GET['admission_id']) ? intval($_GET['admission_id']) : 0;

// Get admission and patient details if provided
$admission_data = null;
$patient_data = null;
if($admission_id > 0) {
    try {
        $admission_data = $newCathLab->getAdmissionDetails($admission_id);
        if($admission_data) {
            $patient_data = $admission_data;
        }
    } catch(Exception $e) {
        // Handle error silently
    }
}

// Get doctors
try {
    $doctors_result = $newCathLab->getActiveDoctors();
} catch(Exception $e) {
    $doctors_result = null;
}

// Get CathLab items
try {
    $items_result = $newCathLab->getActiveCathLabItems();
} catch(Exception $e) {
    $items_result = null;
}

// Generate procedure code
try {
    $procedure_code = $newCathLab->generateProcedureCode();
} catch(Exception $e) {
    $procedure_code = "PROC-" . date('Y') . "-0001";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="theme-color" content="#28a745">
    <title>CathLab Procedure Entry</title>
    <link rel="icon" type="image/png" sizes="16x16" href="../images/favicon.png">
    <link href="css/style.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

    <style>
    .page-header {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        color: white;
        padding: 30px;
        border-radius: 8px;
        margin-bottom: 30px;
    }

    .section-card {
        background: white;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 20px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        border-left: 4px solid #28a745;
    }

    .section-title {
        font-size: 16px;
        font-weight: bold;
        margin-bottom: 15px;
        color: #333;
        padding-bottom: 10px;
        border-bottom: 2px solid #28a745;
    }

    .patient-info {
        background: #f0fff4;
        padding: 15px;
        border-radius: 5px;
        border-left: 4px solid #28a745;
        margin-bottom: 15px;
    }

    .patient-info-row {
        display: flex;
        gap: 30px;
        flex-wrap: wrap;
    }

    .patient-info-item {
        flex: 1;
        min-width: 200px;
    }

    .patient-info-label {
        font-size: 11px;
        color: #666;
        text-transform: uppercase;
        font-weight: bold;
    }

    .patient-info-value {
        font-size: 16px;
        color: #333;
        margin-top: 5px;
    }

    .item-row {
        background: #f0fff4;
        padding: 12px;
        margin-bottom: 10px;
        border-radius: 5px;
        border-left: 3px solid #28a745;
    }

    .item-cost-info {
        background: #fff3cd;
        padding: 8px;
        border-radius: 3px;
        font-size: 12px;
        margin-top: 5px;
    }

    .mandatory-field {
        color: #dc3545;
    }

    .batch-selection {
        margin-top: 10px;
        padding: 10px;
        background: #f8f9fa;
        border-radius: 5px;
    }

    .batch-option {
        padding: 8px;
        margin: 5px 0;
        background: white;
        border: 1px solid #ddd;
        border-radius: 3px;
        cursor: pointer;
        transition: all 0.3s;
    }

    .batch-option:hover {
        background: #e8f5e9;
        border-color: #28a745;
    }

    .batch-option input[type="radio"] {
        margin-right: 10px;
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
        <?php include('leftbar.php'); ?>

        <div class="content-body">
            <div class="container-fluid">

                <!-- Header -->
                <div class="page-header">
                    <h1><i class="fas fa-heartbeat"></i> CathLab Procedure Entry</h1>
                    <p>Record procedure details and items consumed during CathLab procedure</p>
                </div>

                <form action="controller/processCathLab.php" method="POST" id="procedureForm">
           
                    <input type="hidden" name="action" value="saveCathLabProcedure">

                    <!-- Patient & Admission Section -->
                    <div class="section-card">
                        <div class="section-title"><i class="fas fa-user-hospital"></i> Patient & Admission Details</div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Search Patient/Admission <span class="mandatory-field">*</span></label>
                      
                                    <input type="text" class="form-control" id="searchAdmission" placeholder="Enter IP No, Patient Name, or Mobile">
                                     <div id="admissionDropdown" class="list-group" style="position: absolute; width: 100%; z-index: 1000; margin-top: 5px; background: white; display: none;"></div>
                                    <small class="form-text text-muted">Type to search for admitted patients</small>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Admission ID <span class="mandatory-field">*</span></label>
                                    <input type="text" class="form-control" name="admission_id" id="admission_id" placeholder="Selected admission ID" readonly required>
                                </div>
                            </div>

                            
                        </div>
                        <!-- Patient Info Display -->
                        <div id="patientInfoContainer" <?= $admission_data ? '' : 'style="display:none;"' ?>>
                            <div class="patient-info">
                                <div class="patient-info-row">
                                    <div class="patient-info-item">
                                        <div class="patient-info-label">Patient Name</div>
                                        <div class="patient-info-value" id="patientName"><?= $patient_data['name'] ?? '-' ?></div>
                                    </div>
                                    <div class="patient-info-item">
                                        <div class="patient-info-label">IP Number</div>
                                        <div class="patient-info-value" id="ipNumber"><?= $admission_data['ip_no'] ?? '-' ?></div>
                                    </div>
                                    <div class="patient-info-item">
                                        <div class="patient-info-label">Age / Gender</div>
                                        <div class="patient-info-value" id="ageGender"><?= ($patient_data['age'] ?? '-') . ' / ' . ($patient_data['gender'] ?? '-') ?></div>
                                    </div>
                                    <div class="patient-info-item">
                                        <div class="patient-info-label">Ward</div>
                                        <div class="patient-info-value" id="wardName"><?= $admission_data['ward'] ?? '-' ?></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Procedure Details -->
                    <div class="section-card">
                        <div class="section-title"><i class="fas fa-clipboard-list"></i> Procedure Details</div>

                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Procedure Code</label>
                                    <input type="text" class="form-control" name="procedure_code" value="<?= $procedure_code ?>" readonly>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Procedure Type <span class="mandatory-field">*</span></label>
                                    <select class="form-control select2" name="procedure_type" required>
                                        <option value="">-- Select Procedure Type --</option>
                                        <option value="angiography">Coronary Angiography</option>
                                        <option value="angioplasty">Angioplasty (PTCA)</option>
                                        <option value="stent">Stent Placement</option>
                                        <option value="atherectomy">Atherectomy</option>
                                        <option value="rotablation">Rotablation</option>
                                        <option value="thrombectomy">Thrombectomy</option>
                                        <option value="valvuloplasty">Valvuloplasty</option>
                                        <option value="other">Other</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Procedure Date <span class="mandatory-field">*</span></label>
                                    <input type="date" class="form-control" name="procedure_date" value="<?= date('Y-m-d') ?>" required>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Procedure Time</label>
                                    <input type="time" class="form-control" name="procedure_time">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Operating Surgeon <span class="mandatory-field">*</span></label>
                                    <select class="form-control select2" name="doctor_name" required>
                                        <option value="">-- Select Doctor --</option>
                                        <?php 
                                        mysqli_data_seek($doctors_result, 0);
                                        while($doctor = mysqli_fetch_assoc($doctors_result)) {
                                            echo "<option value='{$doctor['name']}'>{$doctor['name']}</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Duration (Minutes)</label>
                                    <input type="number" class="form-control" name="duration_minutes" placeholder="Duration" min="0">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Status</label>
                                    <select class="form-control" name="status">
                                        <option value="planned">Planned</option>
                                        <option value="in-progress">In Progress</option>
                                        <option value="completed" selected>Completed</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Observations/Notes</label>
                            <textarea class="form-control" name="observations" rows="3" placeholder="Clinical observations during procedure..."></textarea>
                        </div>
                    </div>

                    <!-- Items Consumed Section -->
                    <div class="section-card">
                        <div class="section-title"><i class="fas fa-syringe"></i> Items Consumed During Procedure</div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label>Select Item <span class="mandatory-field">*</span></label>
                                <select class="form-control select2" id="item_id" name="temp_item_id">
                                    <option value="">-- Select Item --</option>
                                    <?php 
                                    mysqli_data_seek($items_result, 0);
                                    while($item = mysqli_fetch_assoc($items_result)) {
                                        echo "<option value='{$item['id']}' data-cost='{$item['cost_price']}' data-selling='{$item['selling_price']}'>{$item['item_name']} ({$item['item_code']})</option>";
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="col-md-2">
                                <label>Quantity <span class="mandatory-field">*</span></label>
                                <input type="number" class="form-control" id="quantity" placeholder="Qty" step="0.01" min="0.01">
                            </div>

                            <div class="col-md-2">
                                <label>Select Batch</label>
                                <button type="button" class="btn btn-sm btn-info btn-block" onclick="showBatchSelection()">
                                    <i class="fas fa-cube"></i> Choose Batch
                                </button>
                                <input type="hidden" id="selected_batch_id" name="temp_batch_id">
                                <small id="batch_display" class="form-text text-muted">No batch selected</small>
                            </div>

                            <div class="col-md-2">
                                <label>&nbsp;</label>
                                <button type="button" class="btn btn-success btn-block" onclick="addItemToProcedure()">
                                    <i class="fas fa-plus"></i> Add Item
                                </button>
                            </div>
                        </div>

                        <hr class="my-3">

                        <!-- Items Added -->
                        <div id="itemsContainer">
                            <p class="text-muted text-center">No items added yet</p>
                        </div>
                    </div>

                    <!-- Summary -->
                    <div class="section-card">
                        <div class="section-title"><i class="fas fa-calculator"></i> Consumption Summary</div>

                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Total Items Added</label>
                                    <input type="text" class="form-control" id="totalItemsCount" value="0" readonly>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Total Cost Value</label>
                                    <input type="text" class="form-control" id="totalCostValue" value="₹ 0.00" readonly>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Total Selling Value</label>
                                    <input type="text" class="form-control" id="totalSellingValue" value="₹ 0.00" readonly>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Margin</label>
                                    <input type="text" class="form-control" id="totalMargin" value="₹ 0.00" readonly>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="row mt-4 mb-3">
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="fas fa-save"></i> Save CathLab Procedure
                            </button>
                            <button type="reset" class="btn btn-secondary btn-lg">
                                <i class="fas fa-redo"></i> Clear
                            </button>
                            <a href="dashboard.php" class="btn btn-info btn-lg">
                                <i class="fas fa-arrow-left"></i> Back
                            </a>
                        </div>
                    </div>

                </form>

            </div>
        </div>

        <?php include('footer.php'); ?>
    </div>

    <!-- Batch Selection Modal -->
    <div class="modal fade" id="batchModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">Select Batch</h5>
                    <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div id="batchList"></div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

    <script>
        $(document).ready(function() {
              $('#preloader').hide();
            $('#main-wrapper').show();
            $('.select2').select2();

            // Admission search
            let searchTimeout;
            $('#searchAdmission').on('keyup', function() {
                clearTimeout(searchTimeout);
                const searchTerm = $(this).val();
                if(searchTerm.length < 2) return;

                searchTimeout = setTimeout(() => {
                    $.ajax({
                        url: 'ajax/searchAdmission.php',
                        type: 'POST',
                        data: { search: searchTerm },
                        dataType: 'json',
                        success: function(admissions) {
                            if(admissions.length === 0) {
                                $('#admissionDropdown').html('<div class="list-group-item">No results found</div>').show();
                                return;
                            }
                            
                            let html = '';
                            admissions.forEach(adm => {
                                html += `<a href="#" class="list-group-item list-group-item-action" onclick="selectAdmission(${adm.id},  '${adm.name}', '${adm.ip_no}', '${adm.age}/${adm.gender}', '${adm.ward}'); return false;">
                                    <strong>${adm.ip_no}</strong> - ${adm.name} <br>
                                    <small>${adm.ward}</small>
                                </a>`;
                            });
                            
                            $('#admissionDropdown').html(html).show();
                        }
                    });
                }, 300);
            });

            // Item selection - fetch available batches
            $('#item_id').change(function() {
                const cost = $(this).find('option:selected').data('cost');
                const selling = $(this).find('option:selected').data('selling');
                // Update batch list when item changes
                $('#selected_batch_id').val('');
                $('#batch_display').text('No batch selected');
            });
        });

        let procedureItems = [];

        function selectAdmission(admissionId, name, ipNo, ageGender, ward) {
            $('#admission_id').val(admissionId);
            $('#patientName').text(name);
            $('#ipNumber').text(ipNo);
            $('#ageGender').text(ageGender);
            $('#wardName').text(ward);
            $('#patientInfoContainer').show();
            $('#searchAdmission').val(name);
        }

        function showBatchSelection() {
            const itemId = $('#item_id').val();
            if(!itemId) {
                alert('Please select an item first');
                return;
            }

            $.ajax({
                url: 'ajax/getBatches.php',
                type: 'POST',
                data: { item_id: itemId},
                dataType: 'json',
                success: function(batches) {
                    let html = '';
                    if(batches.length === 0) {
                        html = '<p class="text-danger">No available batches for this item</p>';
                    } else {
                        batches.forEach(batch => {
                            html += `
                                <div class="batch-option">
                                    <input type="radio" name="batchRadio" value="${batch.id}" onchange="selectBatch('${batch.id}', '${batch.batch_number}', '${batch.expiry_date}', '${batch.available_quantity}')">
                                    <strong>Batch: ${batch.batch_number}</strong><br>
                                    <small>Expiry: ${batch.expiry_date} | Available: ${batch.available_quantity}</small>
                                </div>
                            `;
                        });
                    }
                    $('#batchList').html(html);
                    $('#batchModal').modal('show');
                }
            });
        }

        function selectBatch(batchId, batchNo, expiryDate, available) {
            $('#selected_batch_id').val(batchId);
            $('#batch_display').text(`${batchNo} (Exp: ${expiryDate}, Avail: ${available})`).addClass('text-success');
            $('#batchModal').modal('hide');
        }

        function addItemToProcedure() {
            const itemId = $('#item_id').val();
            const itemName = $('#item_id option:selected').text();
            const quantity = parseFloat($('#quantity').val());
            const costPrice = parseFloat($('#item_id').find('option:selected').data('cost'));
            const sellingPrice = parseFloat($('#item_id').find('option:selected').data('selling'));
            const batchId = $('#selected_batch_id').val();

            if(!itemId || !quantity || !batchId) {
                alert('Please select Item, Quantity, and Batch');
                return;
            }

            const costValue = (quantity * costPrice).toFixed(2);
            const sellingValue = (quantity * sellingPrice).toFixed(2);

            procedureItems.push({
                item_id: itemId,
                item_name: itemName,
                quantity: quantity,
                cost_price: costPrice,
                selling_price: sellingPrice,
                cost_value: costValue,
                selling_value: sellingValue,
                batch_id: batchId,
                batch_display: $('#batch_display').text()
            });

            renderItems();
            clearItemForm();
        }

        function renderItems() {
            let html = '';
            let totalCost = 0;
            let totalSelling = 0;

            procedureItems.forEach((item, index) => {
                totalCost += parseFloat(item.cost_value);
                totalSelling += parseFloat(item.selling_value);

                html += `
                    <div class="item-row">
                        <div class="row">
                            <div class="col-md-5">
                                <strong>${item.item_name}</strong><br>
                                <small>${item.batch_display}</small>
                            </div>
                            <div class="col-md-2">
                                Qty: ${item.quantity}
                            </div>
                            <div class="col-md-3">
                                <small>Cost: ₹${item.cost_value} | Sell: ₹${item.selling_value}</small>
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
            $('#totalItemsCount').val(procedureItems.length);
            $('#totalCostValue').val('₹ ' + totalCost.toFixed(2));
            $('#totalSellingValue').val('₹ ' + totalSelling.toFixed(2));
            $('#totalMargin').val('₹ ' + (totalSelling - totalCost).toFixed(2));

            updateHiddenInputs();
        }

        function removeItem(index) {
            procedureItems.splice(index, 1);
            renderItems();
        }

        function clearItemForm() {
            $('#item_id').val('').trigger('change');
            $('#quantity').val('');
            $('#selected_batch_id').val('');
            $('#batch_display').text('No batch selected').removeClass('text-success');
        }

        function updateHiddenInputs() {
            let html = '';
            procedureItems.forEach((item, index) => {
                html += `
                    <input type="hidden" name="items[${index}][item_id]" value="${item.item_id}">
                    <input type="hidden" name="items[${index}][batch_id]" value="${item.batch_id}">
                    <input type="hidden" name="items[${index}][quantity]" value="${item.quantity}">
                    <input type="hidden" name="items[${index}][cost_price]" value="${item.cost_price}">
                `;
            });

            $('#procedureForm').find('.hidden-items').remove();
            if(html) {
                $('#procedureForm').append(`<div class="hidden-items">${html}</div>`);
            }
        }

        $('#procedureForm').submit(function(e) {
            if(!$('#admission_id').val()) {
                e.preventDefault();
                alert('Please select a patient/admission');
                return false;
            }
            if(procedureItems.length === 0) {
                e.preventDefault();
                alert('Please add at least one item consumed');
                return false;
            }
        });
        $(document).on('click', function(e) {
    if(!$(e.target).closest('#searchAdmission, #admissionDropdown').length) {
        $('#admissionDropdown').hide();
    }
});
    </script>

</body>

</html>

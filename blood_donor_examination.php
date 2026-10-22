<?php
require_once(__DIR__ . '/model/session_config.php');
include('model/bloodDonorClass.php');
date_default_timezone_set("Asia/Kolkata");
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check authorization and medical staff role
if(!isset($_SESSION['loginid'])) {
    header("Location: login.php");
    exit;
}

// Get donor ID and fetch existing data
$donor_id = $_GET['donor_id'] ?? 0;
if (!$donor_id) {
    header("Location: blood_donor_list.php?error=Invalid donor");
    exit;
}

$donor = new bloodDonor();
$donor->setDonorId($donor_id);
$donor_result = $donor->getDonorDetails();

if (!$donor_result || $donor_result->num_rows == 0) {
    header("Location: blood_donor_list.php?error=Donor not found");
    exit;
}

$donor_data = $donor_result->fetch_assoc();

// Calculate min hemoglobin based on gender
$min_hemoglobin = ($donor_data['gender'] == 'Female') ? 12.5 : 13.5;
$min_weight = 50;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="theme-color" content="#319197">

    <title>Blood Donor Physical Examination</title>
    <link rel="icon" type="image/png" sizes="16x16" href="images/favicon.png">
    <link href="css/style.css" rel="stylesheet">
    <link href="css/sweetalert2/sweetalert2.min.css" rel="stylesheet" type="text/css" />
    <style>
        .content-body {
            margin-top: 120px;
        }
        .form-section {
            background: #f8f9fa;
            padding: 25px;
            border-radius: 8px;
            margin-bottom: 25px;
            border-left: 4px solid #dc3545;
        }
        .section-title {
            color: #dc3545;
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .required::after {
            content: " *";
            color: #dc3545;
            font-weight: bold;
        }
        .form-label {
            font-weight: 500;
            color: #333;
            margin-bottom: 8px;
        }
        .btn-submit {
            background-color: #dc3545;
            color: white;
            padding: 12px 30px;
            font-weight: 600;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .btn-submit:hover {
            background-color: #c82333;
        }
        .medical-staff-badge {
            background-color: #e3f2fd;
            border-left: 4px solid #1976d2;
            padding: 12px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
        .parameter-card {
            background: white;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
        }
        .reference-value {
            font-size: 12px;
            color: #666;
            font-style: italic;
        }
        .input-group-text {
            background-color: #919191;
            border: none;
        }
        .progress-indicator {
            background: #e9ecef;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
            font-size: 14px;
        }
        .status-indicator {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 11px;
            font-weight: bold;
            margin-left: 10px;
        }
        .status-pass {
            background-color: #d4edda;
            color: #155724;
        }
        .status-warning {
            background-color: #fff3cd;
            color: #856404;
        }
        .status-fail {
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

    <div aria-live="polite" id="notification" aria-atomic="true" style="position: absolute; top: 90px; right: 2px; z-index: 99;">
        <?php if(isset($_GET['success'])): ?>
            <div class="alert alert-success solid alert-dismissible fade show">
                <button type="button" class="close h-100" data-dismiss="alert"><span><i class="mdi mdi-close"></i></span></button>
                <strong>Success!</strong> <?php echo htmlspecialchars($_GET['success']); ?>
            </div>
        <?php endif; ?>
        <?php if(isset($_GET['error'])): ?>
            <div class="alert alert-danger solid alert-dismissible fade show">
                <button type="button" class="close h-100" data-dismiss="alert"><span><i class="mdi mdi-close"></i></span></button>
                <strong>Error!</strong> <?php echo htmlspecialchars($_GET['error']); ?>
            </div>
        <?php endif; ?>
    </div>

    <div id="main-wrapper">
        <?php include('leftbar.php'); ?>
        <div class="content-body">
            <div class="container-fluid">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <h4 class="text-uppercase"><i class="ri-heart-pulse-fill"></i> Physical Examination - Stage 3</h4>
                        <small class="text-muted">Donor <?php echo htmlspecialchars($donor_data['donor_unique_id']); ?> - <?php echo htmlspecialchars($donor_data['first_name'] . ' ' . $donor_data['last_name']); ?></small>
                    </div>
                    <div class="col-md-6">
                        <a href="blood_donor_list.php" class="btn btn-secondary float-lg-right ml-lg-3 mt-lg-3">
                            <i class="ri-arrow-left-line"></i> Back to List
                        </a>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-10 offset-md-1">
                        <div class="card">
                            <div class="card-header border-bottom">
                                <h5 class="card-title mb-0"><i class="ri-stethoscope-line"></i> Physical Examination (Medical Staff Only)</h5>
                                <small class="text-muted">Accurate measurements required for safety assessment</small>
                            </div>
                            <div class="card-body">
        <form method="POST" action="controller/processBloodDonor.php" class="needs-validation" novalidate>
            <input type="hidden" name="action" value="submitExamination">
            <input type="hidden" name="donor_id" value="<?php echo $donor_id; ?>">

            <!-- ========== EXAMINATION DATE ========== -->
            <div class="form-section">
                <div class="section-title">
                    <i class="bi bi-calendar-event"></i> Examination Date & Time
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <label class="form-label required">Examination Date</label>
                        <input type="date" class="form-control" name="examination_date" 
                               value="<?php echo date('Y-m-d'); ?>" required>
                    </div>
                </div>
            </div>

            <!-- ========== PHYSICAL MEASUREMENTS ========== -->
            <div class="form-section">
                <div class="section-title">
                    <i class="bi bi-rulers"></i> Physical Measurements
                </div>

                <div class="parameter-card">
                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label required">Weight (kg)</label>
                            <div class="input-group">
                                <input type="number" class="form-control weight-input" name="weight_kg" 
                                       step="0.1" min="30" max="200" required 
                                       placeholder="Minimum 50 kg">
                                <span class="input-group-text">kg</span>
                            </div>
                            <div class="reference-value">
                                <i class="bi bi-info-circle"></i> Minimum weight: <?php echo $min_weight; ?> kg
                            </div>
                            <small id="weight_status" class="status-indicator"></small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Height (cm)</label>
                            <div class="input-group">
                                <input type="number" class="form-control" name="height_cm" 
                                       step="1" min="100" max="250" placeholder="Optional">
                                <span class="input-group-text">cm</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ========== VITAL SIGNS ========== -->
            <div class="form-section">
                <div class="section-title">
                    <i class="bi bi-heart-fill"></i> Vital Signs
                </div>

                <div class="parameter-card">
                    <label class="form-label required">Blood Pressure (mmHg)</label>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="input-group">
                                <input type="number" class="form-control bp-systolic-input" name="blood_pressure_systolic" 
                                       min="50" max="250" required placeholder="Systolic">
                                <span class="input-group-text">/</span>
                                <input type="number" class="form-control bp-diastolic-input" name="blood_pressure_diastolic" 
                                       min="30" max="150" required placeholder="Diastolic">
                                <span class="input-group-text">mmHg</span>
                            </div>
                            <div class="reference-value mt-2">
                                <i class="bi bi-info-circle"></i> Target: 90-180 systolic / 60-100 diastolic
                            </div>
                            <small id="bp_status" class="status-indicator"></small>
                        </div>
                    </div>
                </div>

                <div class="parameter-card">
                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label required">Pulse Rate (bpm)</label>
                            <div class="input-group">
                                <input type="number" class="form-control pulse-input" name="pulse_rate" 
                                       min="20" max="200" required placeholder="Beats per minute">
                                <span class="input-group-text">bpm</span>
                            </div>
                            <div class="reference-value">
                                <i class="bi bi-info-circle"></i> Normal: 50-100 bpm
                            </div>
                            <small id="pulse_status" class="status-indicator"></small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Temperature (°C)</label>
                            <div class="input-group">
                                <input type="number" class="form-control temp-input" name="temperature_celsius" 
                                       step="0.1" min="35" max="41" value="37" placeholder="Optional">
                                <span class="input-group-text">°C</span>
                            </div>
                            <div class="reference-value">
                                <i class="bi bi-info-circle"></i> Normal: 36.5-37.5°C
                            </div>
                            <small id="temp_status" class="status-indicator"></small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ========== BLOOD TESTS ========== -->
            <div class="form-section">
                <div class="section-title">
                    <i class="bi bi-droplet-fill"></i> Blood Tests & Results
                </div>

                <div class="parameter-card">
                    <label class="form-label required">Hemoglobin (g/dL)</label>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="input-group">
                                <input type="number" class="form-control hemoglobin-input" name="hemoglobin_gm_dl" 
                                       step="0.1" min="5" max="20" required placeholder="Hemoglobin level">
                                <span class="input-group-text">g/dL</span>
                            </div>
                            <div class="reference-value">
                                <i class="bi bi-info-circle"></i> Minimum: <?php echo $min_hemoglobin; ?> g/dL 
                                (<?php echo $donor_data['gender']; ?>)
                            </div>
                            <small id="hemoglobin_status" class="status-indicator"></small>
                        </div>
                    </div>
                </div>

                <div class="parameter-card">
                    <div class="row">
                        <div class="col-md-4">
                            <label class="form-label">Platelet Count (x10⁹/L)</label>
                            <input type="number" class="form-control" name="platelet_count" 
                                   step="1" min="0" placeholder="Optional">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">RBC Count (x10⁶/mL)</label>
                            <input type="number" class="form-control" name="rbc_count" 
                                   step="0.1" min="0" placeholder="Optional">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">WBC Count (x10³/mL)</label>
                            <input type="number" class="form-control" name="wbc_count" 
                                   step="0.1" min="0" placeholder="Optional">
                        </div>
                    </div>
                </div>
            </div>

            <!-- ========== INFECTION SCREENING ========== -->
            <div class="form-section">
                <div class="section-title">
                    <i class="bi bi-shield-exclamation"></i> Infection Screening (Optional but Recommended)
                </div>

                <div class="parameter-card">
                    <div class="row">
                        <div class="col-md-6 ">
                            <label class="form-label">HIV Test Result</label>
                            <select class="form-control" name="hiv_result">
                                <option value="Not Tested" selected>Not Tested</option>
                                <option value="Negative">Negative</option>
                                <option value="Positive">Positive</option>
                                <option value="Inconclusive">Inconclusive</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Hepatitis B Test Result</label>
                            <select class="form-control" name="hepatitis_b_result">
                                <option value="Not Tested" selected>Not Tested</option>
                                <option value="Negative">Negative</option>
                                <option value="Positive">Positive</option>
                                <option value="Inconclusive">Inconclusive</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="parameter-card">
                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label">Hepatitis C Test Result</label>
                            <select class="form-control" name="hepatitis_c_result">
                                <option value="Not Tested" selected>Not Tested</option>
                                <option value="Negative">Negative</option>
                                <option value="Positive">Positive</option>
                                <option value="Inconclusive">Inconclusive</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Syphilis Test Result</label>
                            <select class="form-control" name="syphilis_result">
                                <option value="Not Tested" selected>Not Tested</option>
                                <option value="Negative">Negative</option>
                                <option value="Positive">Positive</option>
                                <option value="Inconclusive">Inconclusive</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="parameter-card">
                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label">Malaria Test Result</label>
                            <select class="form-control" name="malaria_result">
                                <option value="Not Tested" selected>Not Tested</option>
                                <option value="Negative">Negative</option>
                                <option value="Positive">Positive</option>
                                <option value="Inconclusive">Inconclusive</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ========== PHYSICAL OBSERVATIONS ========== -->
            <div class="form-section">
                <div class="section-title">
                    <i class="bi bi-eye-fill"></i> Physical Observations
                </div>

                <div class="parameter-card">
                    <label class="form-label">General Observations</label>
                    <textarea class="form-control" name="general_observations" rows="3" 
                              placeholder="General appearance, skin condition, signs of illness, etc."></textarea>
                </div>

                <div class="parameter-card">
                    <label class="form-label">Veins Condition (for phlebotomy)</label>
                    <select class="form-control" name="veins_condition">
                        <option value="Good" selected>Good - Easy access</option>
                        <option value="Fair">Fair - Moderate difficulty</option>
                        <option value="Poor">Poor - Difficult access</option>
                    </select>
                </div>

                <div class="parameter-card">
                    <label class="form-label">Medical Staff Comments</label>
                    <textarea class="form-control" name="medical_staff_comments" rows="3" 
                              placeholder="Additional notes for physicians/colleagues"></textarea>
                </div>
            </div>

            <!-- ========== FORM ACTIONS ========== -->
            <div class="d-flex gap-2 mb-4">
                <button type="submit" class="btn btn-submit">
                    <i class="bi bi-arrow-right"></i> Continue to Eligibility Assessment
                </button>
                <a href="blood_donor_list.php" class="btn btn-light">
                    <i class="bi bi-x"></i> Cancel
                </a>
            </div>

            <!-- Medical Notes -->
            <div class="alert alert-info" role="alert">
                <i class="bi bi-info-circle"></i> <strong>Medical Staff Note:</strong> 
                All measurements must be accurate. Blood donation eligibility depends on these results. 
                If any parameter is below the minimum threshold, the donor may be deferred.
            </div>
        </form>
    </div>

    <!-- Footer -->
    <footer class="bg-light py-3 mt-5 border-top">
        <div class="container text-center text-muted">
            <small>Blood Center Management System • Alva's Hospital • <?php echo date('Y'); ?></small>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        const MIN_WEIGHT = <?php echo $min_weight; ?>;
        const MIN_HEMOGLOBIN = <?php echo $min_hemoglobin; ?>;
        const GENDER = "<?php echo $donor_data['gender']; ?>";

        // Validation functions
        function validateWeight() {
            const weight = parseFloat(document.querySelector('.weight-input').value);
            const status = document.getElementById('weight_status');
            
            if (weight >= MIN_WEIGHT) {
                status.innerHTML = '<span class="status-pass">✓ Eligible</span>';
                return true;
            } else if (weight >= MIN_WEIGHT - 5) {
                status.innerHTML = '<span class="status-warning">⚠ Below threshold</span>';
                return false;
            } else {
                status.innerHTML = '<span class="status-fail">✗ Ineligible</span>';
                return false;
            }
        }

        function validateHemoglobin() {
            const hgb = parseFloat(document.querySelector('.hemoglobin-input').value);
            const status = document.getElementById('hemoglobin_status');
            
            if (hgb >= MIN_HEMOGLOBIN) {
                status.innerHTML = '<span class="status-pass">✓ Eligible</span>';
                return true;
            } else if (hgb >= MIN_HEMOGLOBIN - 1) {
                status.innerHTML = '<span class="status-warning">⚠ Low</span>';
                return false;
            } else {
                status.innerHTML = '<span class="status-fail">✗ Too Low</span>';
                return false;
            }
        }

        function validateBP() {
            const systolic = parseInt(document.querySelector('.bp-systolic-input').value);
            const diastolic = parseInt(document.querySelector('.bp-diastolic-input').value);
            const status = document.getElementById('bp_status');
            
            if (systolic >= 90 && systolic <= 180 && diastolic >= 60 && diastolic <= 100) {
                status.innerHTML = '<span class="status-pass">✓ Normal</span>';
                return true;
            } else {
                status.innerHTML = '<span class="status-warning">⚠ Out of Range</span>';
                return false;
            }
        }

        function validatePulse() {
            const pulse = parseInt(document.querySelector('.pulse-input').value);
            const status = document.getElementById('pulse_status');
            
            if (pulse >= 50 && pulse <= 100) {
                status.innerHTML = '<span class="status-pass">✓ Normal</span>';
                return true;
            } else {
                status.innerHTML = '<span class="status-warning">⚠ Abnormal</span>';
                return false;
            }
        }

        function validateTemp() {
            const temp = parseFloat(document.querySelector('.temp-input').value);
            const status = document.getElementById('temp_status');
            
            if (temp >= 36.5 && temp <= 37.5) {
                status.innerHTML = '<span class="status-pass">✓ Normal</span>';
                return true;
            } else if (temp >= 36 && temp <= 38) {
                status.innerHTML = '<span class="status-warning">⚠ Slightly Elevated</span>';
                return false;
            } else {
                status.innerHTML = '<span class="status-fail">✗ Abnormal</span>';
                return false;
            }
        }

        // Add event listeners for live validation
        document.querySelector('.weight-input').addEventListener('input', validateWeight);
        document.querySelector('.hemoglobin-input').addEventListener('input', validateHemoglobin);
        document.querySelector('.bp-systolic-input').addEventListener('input', validateBP);
        document.querySelector('.bp-diastolic-input').addEventListener('input', validateBP);
        document.querySelector('.pulse-input').addEventListener('input', validatePulse);
        document.querySelector('.temp-input').addEventListener('input', validateTemp);

        // Form validation
        (function() {
            'use strict';
            window.addEventListener('load', function() {
                var forms = document.querySelectorAll('.needs-validation');
                Array.prototype.slice.call(forms).forEach(function(form) {
                    form.addEventListener('submit', function(event) {
                        if (!form.checkValidity()) {
                            event.preventDefault();
                            event.stopPropagation();
                        }
                        form.classList.add('was-validated');
                    }, false);
                });
            });
        })();
    </script>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="vendor/global/global.min.js"></script>
    <script src="vendor/bootstrap-select/dist/js/bootstrap-select.min.js"></script>
    <script src="js/custom.min.js"></script>
    <script src="js/deznav-init.js"></script>
    <script src="css/sweetalert2/sweetalert2.min.js"></script>
</body>
</html>

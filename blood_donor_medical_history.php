<?php
require_once(__DIR__ . '/model/session_config.php');
include('model/bloodDonorClass.php');
date_default_timezone_set("Asia/Kolkata");
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check authorization
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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="theme-color" content="#319197">

    <title>Blood Donor Medical History</title>
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
        .parameter-card {
            background: white;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
        }
        .form-label {
            font-weight: 500;
            color: #333;
            margin-bottom: 8px;
        }
        .dependent-fields {
            display: none;
            background: #fff3cd;
            padding: 15px;
            margin-top: 10px;
            border-left: 3px solid #ffc107;
            border-radius: 5px;
        }
        .dependent-fields.show {
            display: block;
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
    </style>
</head>
<body>

    <!--*******************
        Preloader start
    ********************-->
    <div id="preloader">
        <div class="sk-three-bounce">
            <div class="sk-child sk-bounce1"></div>
            <div class="sk-child sk-bounce2"></div>
            <div class="sk-child sk-bounce3"></div>
        </div>
    </div>
    <!--*******************
        Preloader end
    ********************-->

    <div aria-live="polite" id="notification" aria-atomic="true">
        <?php if(isset($_GET['success'])): ?>
            <div class="alert alert-success solid alert-dismissible fade show">
                <button type="button" class="close h-100" data-dismiss="alert" aria-label="Close"><span><i class="mdi mdi-close"></i></span></button>
                <strong>Success!</strong> <?php echo htmlspecialchars($_GET['success']); ?>
            </div>
        <?php endif; ?>

        <?php if(isset($_GET['error'])): ?>
            <div class="alert alert-danger solid alert-dismissible fade show">
                <button type="button" class="close h-100" data-dismiss="alert" aria-label="Close"><span><i class="mdi mdi-close"></i></span></button>
                <strong>Error!</strong> <?php echo htmlspecialchars($_GET['error']); ?>
            </div>
        <?php endif; ?>
    </div>

    <!--**********************************
        Main wrapper start
    ***********************************-->
    <div id="main-wrapper">

        <?php include('leftbar.php'); ?>

        <!--**********************************
            Content body start
        ***********************************-->
        <div class="content-body">

            <div class="container-fluid">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <h4 class="text-uppercase"><i class="ri-heart-pulse-fill"></i> Medical History - Stage 2</h4>
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
                                <h5 class="card-title mb-0"><i class="ri-clipboard-line"></i> Medical History & Risk Assessment</h5>
                                <small class="text-muted">Answer all questions honestly for accurate risk assessment</small>
                            </div>
                            <div class="card-body">
        <form method="POST" action="controller/processBloodDonor.php" class="needs-validation" novalidate>
            <input type="hidden" name="action" value="submitMedicalHistory">
            <input type="hidden" name="donor_id" value="<?php echo $donor_id; ?>">

            <!-- ========== PAST DONATIONS ========== -->
            <div class="form-section">
                <div class="section-title">
                    <i class="ri-contrast-drop-fill"></i> Donation History
                </div>

                <div class="parameter-card">
                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label">Total Previous Donations</label>
                            <input type="number" class="form-control" name="total_donations" min="0" value="0" 
                                   placeholder="Number of previous donations">
                            <small class="form-text text-muted">Leave as 0 for first-time donors</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ========== MEDICAL CONDITIONS ========== -->
            <div class="form-section">
                <div class="section-title">
                    <i class="ri-stethoscope-line"></i> Medical Conditions
                </div>

                <div class="parameter-card">
                    <div class="form-check">
                        <input type="checkbox" id="recent_illness" name="recent_illness_flag" class="form-check-input">
                        <label for="recent_illness" class="form-check-label">
                            <strong>Recent Illness or Infection?</strong>
                        </label>
                    </div>
                    <div id="illness_details" class="dependent-fields">
                        <label class="form-label">Please describe the illness:</label>
                        <textarea class="form-control" name="illness_description" rows="3" 
                                  placeholder="Type, duration, current status, etc."></textarea>
                        <small class="form-text text-muted">Include fever, cough, diarrhea, etc.</small>
                    </div>
                </div>

                <div class="parameter-card">
                    <div class="form-check">
                        <input type="checkbox" id="recent_surgery" name="recent_surgery_flag" class="form-check-input">
                        <label for="recent_surgery" class="form-check-label">
                            <strong>Recent Surgery or Dental Work?</strong>
                        </label>
                    </div>
                    <div id="surgery_details" class="dependent-fields">
                        <div class="row">
                            <div class="col-md-6">
                                <label class="form-label">Surgery Description:</label>
                                <input type="text" class="form-control" name="surgery_description" 
                                       placeholder="Type of surgery">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Date of Surgery:</label>
                                <input type="date" class="form-control" name="surgery_date">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="parameter-card">
                    <div class="form-check">
                        <input type="checkbox" id="chronic_disease" name="has_chronic_disease" class="form-check-input">
                        <label for="chronic_disease" class="form-check-label">
                            <strong>Chronic Diseases or Long-term Health Conditions?</strong>
                        </label>
                    </div>
                    <div id="chronic_details" class="dependent-fields">
                        <label class="form-label">Which conditions? (comma-separated)</label>
                        <textarea class="form-control" name="chronic_disease_list" rows="3" 
                                  placeholder="E.g., Diabetes, Hypertension, Asthma, Heart Disease, Kidney Disease"></textarea>
                        <small class="text-muted">Note: Chronic diseases may affect eligibility</small>
                    </div>
                </div>
            </div>

            <!-- ========== MEDICATIONS ========== -->
            <div class="form-section">
                <div class="section-title">
                    <i class="ri-medicine-bottle-line"></i> Medications & Treatments
                </div>

                <div class="parameter-card">
                    <div class="form-check">
                        <input type="checkbox" id="on_medication" name="on_medication" class="form-check-input">
                        <label for="on_medication" class="form-check-label">
                            <strong>Currently on Regular Medications?</strong>
                        </label>
                    </div>
                    <div id="medication_details" class="dependent-fields">
                        <label class="form-label">List all current medications:</label>
                        <textarea class="form-control" name="medication_list" rows="3" 
                                  placeholder="Drug names and dosage"></textarea>
                        <small class="form-text text-muted">Some medications may contraindicate donation</small>
                    </div>
                </div>

                <div class="parameter-card">
                    <div class="form-check">
                        <input type="checkbox" id="recent_vaccination" name="recent_vaccination_flag" class="form-check-input">
                        <label for="recent_vaccination" class="form-check-label">
                            <strong>Recent Vaccinations (within 3 months)?</strong>
                        </label>
                    </div>
                    <div id="vaccination_details" class="dependent-fields">
                        <div class="row">
                            <div class="col-md-6">
                                <label class="form-label">Vaccination Type:</label>
                                <input type="text" class="form-control" name="vaccination_type" 
                                       placeholder="E.g., COVID-19, Influenza, etc.">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Date of Vaccination:</label>
                                <input type="date" class="form-control" name="vaccination_date">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ========== RISK ASSESSMENT ========== -->
            <div class="form-section">
                <div class="section-title">
                    <i class="ri-shield-cross-line"></i> Risk Assessment
                </div>

                <div class="parameter-card">
                    <div class="form-check">
                        <input type="checkbox" id="high_risk" name="high_risk_behavior_flag" class="form-check-input">
                        <label for="high_risk" class="form-check-label">
                            <strong>High-Risk Behavior History?</strong>
                        </label>
                    </div>
                    <div id="risk_details" class="dependent-fields">
                        <small class="text-warning d-block mb-2"><strong>Important:</strong> Honest disclosure is critical for blood safety</small>
                        <label class="form-label">Please describe:</label>
                        <textarea class="form-control" name="risk_behavior_description" rows="3" 
                                  placeholder="Substance use, unsafe practices, etc."></textarea>
                    </div>
                </div>

                <div class="parameter-card">
                    <div class="form-check">
                        <input type="checkbox" id="cancer_history" name="has_cancer_history" class="form-check-input">
                        <label for="cancer_history" class="form-check-label">
                            <strong>History of Cancer?</strong>
                        </label>
                    </div>
                </div>

                <div class="parameter-card">
                    <div class="form-check">
                        <input type="checkbox" id="blood_disorder" name="has_blood_disorder" class="form-check-input">
                        <label for="blood_disorder" class="form-check-label">
                            <strong>Blood Disorders (Anemia, Sickle Cell, etc.)?</strong>
                        </label>
                    </div>
                </div>

                <div class="parameter-card">
                    <div class="form-check">
                        <input type="checkbox" id="hiv_risk" name="has_hiv_risk" class="form-check-input">
                        <label for="hiv_risk" class="form-check-label">
                            <strong>HIV Risk or Positive Status?</strong>
                        </label>
                    </div>
                    <small class="text-muted d-block mt-1"><strong>Confidential:</strong> This information will be kept confidential</small>
                </div>

                <div class="parameter-card">
                    <div class="form-check">
                        <input type="checkbox" id="hepatitis_risk" name="hepatitis_risk" class="form-check-input">
                        <label for="hepatitis_risk" class="form-check-label">
                            <strong>Hepatitis B or C Risk/Positive Status?</strong>
                        </label>
                    </div>
                </div>
            </div>

            <!-- ========== OTHER CONDITIONS ========== -->
            <div class="form-section">
                <div class="section-title">
                    <i class="ri-user-heart-line"></i> Other Conditions
                </div>

                <div class="parameter-card">
                    <div class="form-check">
                        <input type="checkbox" id="is_pregnant" name="is_pregnant" class="form-check-input">
                        <label for="is_pregnant" class="form-check-label">
                            <strong>Currently Pregnant or Lactating?</strong>
                        </label>
                    </div>
                </div>

                <div class="parameter-card">
                    <div class="form-check">
                        <input type="checkbox" id="has_allergies" name="has_allergies" class="form-check-input">
                        <label for="has_allergies" class="form-check-label">
                            <strong>Known Allergies?</strong>
                        </label>
                    </div>
                    <div id="allergy_details" class="dependent-fields">
                        <label class="form-label">Describe allergies (food, medication, blood products, etc.):</label>
                        <textarea class="form-control" name="allergy_details" rows="3" 
                                  placeholder="Allergy types and severity"></textarea>
                    </div>
                </div>
            </div>

            <!-- ========== FORM ACTIONS ========== -->
            <div class="d-flex gap-2 mb-4">
                <button type="submit" class="btn btn-submit">
                    <i class="ri-arrow-right-line"></i> Continue to Physical Examination
                </button>
                <a href="blood_donor_list.php" class="btn btn-light">
                    <i class="ri-close-line"></i> Cancel
                </a>
            </div>

            <!-- Medical Notes -->
            <div class="alert alert-warning" role="alert">
                <i class="ri-lock-password-line"></i> <strong>Medical Confidentiality:</strong> All information will be treated as confidential 
                and used only for donor screening and safety assessment.
            </div>
        </form>

    <script>
        // Toggle dependent fields based on checkbox
        function setupDependentFields(checkboxId, fieldId) {
            const checkbox = document.getElementById(checkboxId);
            const fields = document.getElementById(fieldId);
            
            if (checkbox && fields) {
                checkbox.addEventListener('change', function() {
                    if (this.checked) {
                        fields.classList.add('show');
                    } else {
                        fields.classList.remove('show');
                    }
                });
            }
        }

        // Initialize all dependent fields
        setupDependentFields('recent_illness', 'illness_details');
        setupDependentFields('recent_surgery', 'surgery_details');
        setupDependentFields('chronic_disease', 'chronic_details');
        setupDependentFields('on_medication', 'medication_details');
        setupDependentFields('recent_vaccination', 'vaccination_details');
        setupDependentFields('high_risk', 'risk_details');
        setupDependentFields('has_allergies', 'allergy_details');

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

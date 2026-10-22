<?php
require_once(__DIR__ . '/model/session_config.php');
include('model/bloodDonorClass.php');
date_default_timezone_set("Asia/Kolkata");

// Check authorization
if(!isset($_SESSION['loginid'])) {
    header("Location: login.php");
    exit;
}

// Get donor ID from URL
$donor_id = $_GET['donor_id'] ?? 0;
if (!$donor_id) {
    header("Location: blood_donor_list.php?error=Invalid donor");
    exit;
}

// Verify donor eligibility
$donor = new bloodDonor();
$donor->setDonorId($donor_id);
$donor_result = $donor->getDonorDetails();

if (!$donor_result || $donor_result->num_rows == 0) {
    header("Location: blood_donor_list.php?error=Donor not found");
    exit;
}

$donor_data = $donor_result->fetch_assoc();

// Check eligibility
$eligibility_errors = [];
$con = new userlogin();
$db = $con->dbconnect();

// Check 1: Donor status must be Eligible
if ($donor_data['current_status'] != 'Eligible') {
    // Special handling for Pending_Re_Evaluation status
    if ($donor_data['current_status'] == 'Pending_Re_Evaluation') {
        $eligibility_errors[] = "Donor requires medical re-evaluation after deferral clearance";
        $eligibility_errors[] = "Required: Complete Medical History (Stage 2) → Physical Examination (Stage 3) → Medical Officer Approval";
        $eligibility_errors[] = '<a href="blood_donor_re_evaluation.php?donor_id=' . $donor_id . '" style="color: #dc3545; font-weight: bold;">Click here to start re-evaluation process</a>';
    } else {
        $eligibility_errors[] = "Donor status is not Eligible (Current: " . str_replace('_', ' ', $donor_data['current_status']) . ")";
    }
}

// Check 2: Check for active deferral
$deferral_result = $donor->getActiveDeferral();
if ($deferral_result && $deferral_result->num_rows > 0) {
    $deferral = $deferral_result->fetch_assoc();
    $eligibility_errors[] = "Active deferral: " . $deferral['deferral_reason'];
    if ($deferral['deferral_end_date']) {
        $eligibility_errors[] = "Clearance date: " . date('d-M-Y', strtotime($deferral['deferral_end_date']));
    }
}

// Check 3: Check for recently cleared deferrals without re-evaluation
$recent_clearance_result = $donor->getRecentlyClearedDeferral(90);
if ($recent_clearance_result && $recent_clearance_result->num_rows > 0 && $donor_data['current_status'] != 'Eligible') {
    $cleared = $recent_clearance_result->fetch_assoc();
    $eligibility_errors[] = "Deferral cleared on " . date('d-M-Y', strtotime($cleared['clearance_date'])) . " - Medical re-evaluation required";
}

// Check 4: Cooling period validation
if ($donor_data['next_eligible_donation_date'] && $donor_data['next_eligible_donation_date'] > date('Y-m-d')) {
    $days_remaining = ceil((strtotime($donor_data['next_eligible_donation_date']) - time()) / 86400);
    $eligibility_errors[] = "Cooling period not completed. Next eligible date: " . date('d-M-Y', strtotime($donor_data['next_eligible_donation_date'])) . " ($days_remaining days remaining)";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Blood Collection - Stage 2</title>
    <link rel="icon" type="image/png" sizes="16x16" href="images/favicon.png">
    <link href="css/style.css" rel="stylesheet">
    <link href="css/sweetalert2/sweetalert2.min.css" rel="stylesheet" type="text/css" />
    <style>
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
            padding: 20px;
            margin-bottom: 20px;
        }
        .form-label {
            font-weight: 500;
            color: #333;
            margin-bottom: 8px;
        }
        .required-mark {
            color: #dc3545;
            font-weight: bold;
        }
        .btn-collect {
            background-color: #dc3545;
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 5px;
            font-weight: 600;
            cursor: pointer;
        }
        .btn-collect:hover {
            background-color: #c82333;
            color: white;
        }
        .eligibility-alert {
            background-color: #f8d7da;
            border-left: 4px solid #dc3545;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .eligibility-pass {
            background-color: #d4edda;
            border-left: 4px solid #28a745;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .donor-info-card {
            background: white;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 25px;
        }
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
        }
        .info-item {
            padding: 10px;
            background: #f8f9fa;
            border-radius: 5px;
        }
        .info-label {
            font-size: 12px;
            color: #666;
            font-weight: 600;
            text-transform: uppercase;
        }
        .info-value {
            font-size: 16px;
            color: #333;
            font-weight: 600;
            margin-top: 5px;
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
        <?php include('leftbar.php'); ?>
        <div class="content-body">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-10 offset-md-1">
                        <div class="card">
                            <div class="card-header" style="border-bottom: 2px solid #dc3545;">
                                <h4 class="card-title">
                                    <i class="ri-syringe-line"></i> Blood Collection - Stage 2
                                </h4>
                            </div>
                            <div class="card-body">
                                <!-- Donor Information -->
                                <div class="donor-info-card">
                                    <h5 style="color: #dc3545; margin-bottom: 15px;">
                                        <i class="ri-user-3-fill"></i> Donor Information
                                    </h5>
                                    <div class="info-grid">
                                        <div class="info-item">
                                            <div class="info-label">Donor ID</div>
                                            <div class="info-value"><?php echo htmlspecialchars($donor_data['donor_unique_id']); ?></div>
                                        </div>
                                        <div class="info-item">
                                            <div class="info-label">Name</div>
                                            <div class="info-value"><?php echo htmlspecialchars($donor_data['first_name'] . ' ' . $donor_data['last_name']); ?></div>
                                        </div>
                                        <div class="info-item">
                                            <div class="info-label">Blood Group</div>
                                            <div class="info-value"><?php echo htmlspecialchars($donor_data['blood_group'] ?? 'Not Determined'); ?></div>
                                        </div>
                                        <div class="info-item">
                                            <div class="info-label">Age</div>
                                            <div class="info-value"><?php echo $donor_data['age']; ?> years</div>
                                        </div>
                                        <div class="info-item">
                                            <div class="info-label">Gender</div>
                                            <div class="info-value"><?php echo $donor_data['gender']; ?></div>
                                        </div>
                                        <div class="info-item">
                                            <div class="info-label">Current Status</div>
                                            <div class="info-value"><?php echo str_replace('_', ' ', $donor_data['current_status']); ?></div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Eligibility Check Results -->
                                <?php if (count($eligibility_errors) > 0): ?>
                                    <div class="eligibility-alert">
                                        <strong><i class="ri-error-warning-line"></i> Collection Blocked - Eligibility Issues:</strong>
                                        <ul style="margin-top: 10px; margin-bottom: 0;">
                                            <?php foreach ($eligibility_errors as $error): ?>
                                                <li><?php echo htmlspecialchars($error); ?></li>
                                            <?php endforeach; ?>
                                        </ul>
                                        <div style="margin-top: 15px;">
                                            <a href="blood_donor_profile.php?donor_id=<?php echo $donor_id; ?>" class="btn btn-secondary">
                                                <i class="ri-arrow-left-line"></i> Back to Donor Profile
                                            </a>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <div class="eligibility-pass">
                                        <strong><i class="ri-checkbox-circle-line"></i> Eligibility Confirmed</strong>
                                        <p style="margin-bottom: 0; margin-top: 5px;">Donor is eligible for blood collection. Proceed with collection process.</p>
                                    </div>

                                    <!-- Collection Form -->
                                    <form method="POST" action="controller/processBloodCollection.php" id="collectionForm">
                                        <input type="hidden" name="action" value="addCollection">
                                        <input type="hidden" name="donor_id" value="<?php echo $donor_id; ?>">
                                        <input type="hidden" name="donor_unique_id" value="<?php echo htmlspecialchars($donor_data['donor_unique_id']); ?>">

                                        <!-- Pre-Collection Setup -->
                                        <div class="form-section">
                                            <div class="section-title">
                                                <i class="ri-file-list-3-line"></i> Pre-Collection Setup
                                            </div>
                                            <div class="parameter-card">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="form-label">Collection Date <span class="required-mark">*</span></label>
                                                            <input type="date" class="form-control" name="collection_date" value="<?php echo date('Y-m-d'); ?>" required max="<?php echo date('Y-m-d'); ?>">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="form-label">Collection Time <span class="required-mark">*</span></label>
                                                            <input type="time" class="form-control" name="collection_time" value="<?php echo date('H:i'); ?>" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="form-label">Collection Location <span class="required-mark">*</span></label>
                                                            <select class="form-control" name="collection_location" required onchange="toggleCampLocation(this)">
                                                                <option value="">Select Location</option>
                                                                <option value="Blood_Bank" selected>Blood Bank</option>
                                                                <option value="Camp">Camp</option>
                                                                <option value="Mobile_Unit">Mobile Unit</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row" id="campLocationRow" style="display: none;">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label class="form-label">Camp/Mobile Unit Location</label>
                                                            <input type="text" class="form-control" name="camp_location" placeholder="Enter camp/mobile unit address">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Blood Bag & Collection Details -->
                                        <div class="form-section">
                                            <div class="section-title">
                                                <i class="ri-test-tube-line"></i> Blood Bag & Collection Details
                                            </div>
                                            <div class="parameter-card">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="form-label">Blood Bag Type <span class="required-mark">*</span></label>
                                                            <select class="form-control" name="blood_bag_type" required>
                                                                <option value="">Select Bag Type</option>
                                                                <option value="Single Bag (CPDA-1)">Single Bag (CPDA-1) - 450ml</option>
                                                                <option value="Double Bag (CPDA-1)" selected>Double Bag (CPDA-1) - 450ml</option>
                                                                <option value="Triple Bag (CPDA-1)">Triple Bag (CPDA-1) - 450ml</option>
                                                                <option value="Quadruple Bag (CPDA-1)">Quadruple Bag (CPDA-1) - 450ml</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="form-label">Anticoagulant Type <span class="required-mark">*</span></label>
                                                            <select class="form-control" name="anticoagulant_type" required>
                                                                <option value="CPDA-1" selected>CPDA-1</option>
                                                                <option value="ACD-A">ACD-A</option>
                                                                <option value="CPD">CPD</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="form-label">Collection Method <span class="required-mark">*</span></label>
                                                            <select class="form-control" name="collection_method" required>
                                                                <option value="Standard_Venipuncture" selected>Standard Venipuncture</option>
                                                                <option value="Apheresis">Apheresis</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="form-label">Volume Collected (ml) <span class="required-mark">*</span></label>
                                                            <input type="number" class="form-control" name="volume_collected_ml" min="350" max="500" value="450" required>
                                                            <small class="text-muted">Standard: 450ml (Range: 350-500ml)</small>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="form-label">Collection Duration (minutes)</label>
                                                            <input type="number" class="form-control" name="collection_duration_minutes" min="5" max="20" placeholder="Optional">
                                                            <small class="text-muted">Normal: 8-10 minutes</small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Donor Monitoring -->
                                        <div class="form-section">
                                            <div class="section-title">
                                                <i class="ri-heart-pulse-line"></i> Donor Monitoring During Collection
                                            </div>
                                            <div class="parameter-card">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label class="form-label">
                                                                <input type="checkbox" name="adverse_reaction_flag" value="1" onchange="toggleAdverseReaction(this)"> 
                                                                Adverse Reaction Occurred
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div id="adverseReactionDetails" style="display: none;">
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="form-label">Reaction Type</label>
                                                                <select class="form-control" name="adverse_reaction_type">
                                                                    <option value="">Select Type</option>
                                                                    <option value="Vasovagal">Vasovagal (Fainting)</option>
                                                                    <option value="Dizziness">Dizziness</option>
                                                                    <option value="Nausea">Nausea</option>
                                                                    <option value="Vomiting">Vomiting</option>
                                                                    <option value="Hematoma">Hematoma</option>
                                                                    <option value="Nerve_Injury">Nerve Injury</option>
                                                                    <option value="Arterial_Puncture">Arterial Puncture</option>
                                                                    <option value="Other">Other</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="form-label">Severity</label>
                                                                <select class="form-control" name="adverse_reaction_severity">
                                                                    <option value="">Select Severity</option>
                                                                    <option value="Mild">Mild</option>
                                                                    <option value="Moderate">Moderate</option>
                                                                    <option value="Severe">Severe</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="form-label">Managed By</label>
                                                                <input type="text" class="form-control" name="adverse_reaction_managed_by" placeholder="Staff name">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label class="form-label">Reaction Details</label>
                                                                <textarea class="form-control" name="adverse_reaction_notes" rows="3" placeholder="Describe the adverse reaction and intervention taken"></textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Post-Collection Observation -->
                                        <div class="form-section">
                                            <div class="section-title">
                                                <i class="ri-eye-line"></i> Post-Collection Observation
                                            </div>
                                            <div class="parameter-card">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="form-label">Observation Duration (minutes) <span class="required-mark">*</span></label>
                                                            <input type="number" class="form-control" name="observation_duration_minutes" min="10" max="60" value="15" required>
                                                            <small class="text-muted">Standard: 15 minutes</small>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="form-label">Observation Status <span class="required-mark">*</span></label>
                                                            <select class="form-control" name="observation_status" required>
                                                                <option value="Normal_Recovery" selected>Normal Recovery</option>
                                                                <option value="Delayed_Recovery">Delayed Recovery</option>
                                                                <option value="Adverse_Event">Adverse Event</option>
                                                                <option value="Medical_Attention_Required">Medical Attention Required</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label class="form-label">Observation Notes</label>
                                                            <textarea class="form-control" name="observation_notes" rows="2" placeholder="Any observations during post-collection period"></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label class="form-label">
                                                                <input type="checkbox" name="refreshment_provided" value="1" checked> 
                                                                Refreshment Provided to Donor
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Collection Summary -->
                                        <div class="form-section">
                                            <div class="section-title">
                                                <i class="ri-file-text-line"></i> Collection Summary
                                            </div>
                                            <div class="parameter-card">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label class="form-label">Collection Notes</label>
                                                            <textarea class="form-control" name="collection_notes" rows="3" placeholder="Any additional notes about this collection"></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Alert Box -->
                                        <div class="alert" style="background-color: #fff3cd; border-left: 4px solid #ffc107; padding: 15px; margin-bottom: 20px;">
                                            <strong><i class="ri-alert-line"></i> Important:</strong>
                                            <ul style="margin-bottom: 0; margin-top: 10px;">
                                                <li>Blood unit will be created with status: <strong>Collected_Unscreened</strong></li>
                                                <li>Unit will NOT be available for issue until testing is completed</li>
                                                <li>Donor's next eligible donation date will be calculated automatically</li>
                                                <li>All adverse reactions must be documented</li>
                                            </ul>
                                        </div>

                                        <!-- Action Buttons -->
                                        <div class="row">
                                            <div class="col-md-12 text-right">
                                                <a href="blood_donor_profile.php?donor_id=<?php echo $donor_id; ?>" class="btn btn-secondary">
                                                    <i class="ri-arrow-left-line"></i> Cancel
                                                </a>
                                                <button type="submit" class="btn-collect">
                                                    <i class="ri-syringe-line"></i> Complete Collection & Create Blood Unit
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                <?php endif; ?>
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
    <script>
        function toggleCampLocation(select) {
            const campRow = document.getElementById('campLocationRow');
            if (select.value === 'Camp' || select.value === 'Mobile_Unit') {
                campRow.style.display = 'block';
            } else {
                campRow.style.display = 'none';
            }
        }

        function toggleAdverseReaction(checkbox) {
            const details = document.getElementById('adverseReactionDetails');
            details.style.display = checkbox.checked ? 'block' : 'none';
        }

        // Form validation
        document.getElementById('collectionForm')?.addEventListener('submit', function(e) {
            const volume = parseInt(document.querySelector('[name="volume_collected_ml"]').value);
            if (volume < 350 || volume > 500) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Invalid Volume',
                    text: 'Volume collected must be between 350ml and 500ml'
                });
                return false;
            }
        });
    </script>
</body>
</html>

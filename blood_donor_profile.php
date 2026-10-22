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

// Get donor ID
$donor_id = $_GET['donor_id'] ?? 0;
if (!$donor_id) {
    header("Location: blood_donor_list.php?error=Invalid donor");
    exit;
}

$donor = new bloodDonor();
$donor->setDonorId($donor_id);

// Get all donor information
$donor_result = $donor->getDonorDetails();
if (!$donor_result || $donor_result->num_rows == 0) {
    header("Location: blood_donor_list.php?error=Donor not found");
    exit;
}

$donor_data = $donor_result->fetch_assoc();
$medical_result = $donor->getMedicalHistory();
$medical_data = $medical_result ? $medical_result->fetch_assoc() : null;
$exam_result = $donor->getLatestExamination();
$exam_data = $exam_result ? $exam_result->fetch_assoc() : null;
$deferral_result = $donor->getActiveDeferral();
$deferral_data = $deferral_result ? $deferral_result->fetch_assoc() : null;
$audit_result = $donor->getAuditTrail();
$today = date_create(date("Y-m-d")); // Create a DateTime object for today
$birthDate = date_create($donor_data['date_of_birth']); // Create a DateTime object for the date of birth

$interval = date_diff($birthDate, $today); // Calculate the difference
$age = $interval->format('%y');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="theme-color" content="#319197">

    <title>Blood Donor Profile</title>
    <link rel="icon" type="image/png" sizes="16x16" href="images/favicon.png">
    <link href="css/style.css" rel="stylesheet">
    <link href="css/sweetalert2/sweetalert2.min.css" rel="stylesheet" type="text/css" />
    <style>
    .content-body {
            margin-top: 120px;
        }
        .profile-header {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            color: white;
            padding: 30px 0;
            margin-bottom: 30px;
        }
        .profile-title {
            font-size: 28px;
            font-weight: 700;
        }
        .status-badge-large {
            display: inline-block;
            padding: 10px 20px;
            border-radius: 25px;
            font-size: 16px;
            font-weight: 600;
            margin-top: 10px;
        }
        .status-eligible {
            background-color: #d4edda;
            color: #155724;
            border: 2px solid #28a745;
        }
        .status-temp-deferred {
            background-color: #fff3cd;
            color: #856404;
            border: 2px solid #ffc107;
        }
        .status-perm-deferred {
            background-color: #f8d7da;
            color: #721c24;
            border: 2px solid #dc3545;
        }
        .status-registered {
            background-color: #e2e3e5;
            color: #383d41;
            border: 2px solid #6c757d;
        }
        .info-card {
            background: white;
            border-radius: 8px;
            padding: 25px;
            margin-bottom: 25px;
            border: 1px solid #e0e0e0;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }
        .card-title {
            color: #dc3545;
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 20px;
            padding-bottom: 12px;
            border-bottom: 2px solid #f0f0f0;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #f5f5f5;
        }
        .info-row:last-child {
            border-bottom: none;
        }
        .info-label {
            font-weight: 600;
            color: #666;
            min-width: 200px;
        }
        .info-value {
            color: #333;
            flex: 1;
        }
        .tabs-section {
            margin-bottom: 30px;
        }
        .tab-pane {
            background: white;
            border-radius: 0 0 8px 8px;
            padding: 25px;
            border: 1px solid #e0e0e0;
            border-top: none;
        }
        .examination-params {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        .param-card {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            border-left: 4px solid #dc3545;
        }
        .param-label {
            font-size: 12px;
            color: #666;
            font-weight: 600;
            text-transform: uppercase;
            margin-bottom: 5px;
        }
        .param-value {
            font-size: 18px;
            font-weight: 700;
            color: #333;
        }
        .param-unit {
            font-size: 12px;
            color: #999;
        }
        .audit-log-item {
            padding: 12px;
            border-bottom: 1px solid #f0f0f0;
            font-size: 13px;
        }
        .audit-log-item:last-child {
            border-bottom: none;
        }
        .audit-action {
            background-color: #e7f3ff;
            color: #0066cc;
            padding: 4px 8px;
            border-radius: 3px;
            font-weight: 600;
            font-size: 11px;
        }
        .back-button {
            color: white;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-weight: 500;
        }
        .back-button:hover {
            color: #f0f0f0;
        }
        .deferral-alert {
            background-color: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
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
                        <h4 class="text-uppercase"><i class="ri-user-3-line"></i> Donor Profile</h4>
                        <h5><?php echo htmlspecialchars($donor_data['first_name'] . ' ' . $donor_data['last_name']); ?></h5>
                        <?php
                $status = $donor_data['current_status'];
                $status_class = '';
                $status_icon = '';
                
                switch($status) {
                    case 'Eligible':
                        $status_class = 'status-eligible';
                        $status_icon = '✓';
                        break;
                    case 'Deferred_Temporary':
                        $status_class = 'status-temp-deferred';
                        $status_icon = '⏱';
                        break;
                    case 'Deferred_Permanent':
                        $status_class = 'status-perm-deferred';
                        $status_icon = '✗';
                        break;
                    case 'Registered':
                        $status_class = 'status-registered';
                        $status_icon = '◦';
                        break;
                }
                ?>
                <div class="status-badge-large <?php echo $status_class; ?>">
                    <?php echo $status_icon; ?> <?php echo str_replace('_', ' ', $status); ?>
                </div>
            </div>
        </div>
    </div>

    <div class="container" style="max-width: 80%;">
        
        <!-- Alert Messages -->
        <?php if(isset($_GET['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="ri-checkbox-circle-line"></i> <?php echo htmlspecialchars($_GET['success']); ?>
                <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
            </div>
        <?php endif; ?>

        <!-- Active Deferral Alert -->
        <?php if ($deferral_data && $deferral_data['is_active']): ?>
            <div class="deferral-alert">
                <strong><i class="ri-error-warning-line"></i> Active Deferral</strong><br>
                <strong>Reason:</strong> <?php echo htmlspecialchars($deferral_data['deferral_reason']); ?><br>
                <strong>Type:</strong> <?php echo str_replace('_', ' ', $deferral_data['deferral_type']); ?><br>
                <?php if ($deferral_data['deferral_end_date']): ?>
                    <strong>Clearance Date:</strong> <?php echo date('d-M-Y', strtotime($deferral_data['deferral_end_date'])); ?>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <!-- Personal Information -->
        <div class="info-card">
            <div class="card-title">
                <i class="ri-user-3-fill"></i> Personal Information
            </div>
            <div class="info-row">
                <span class="info-label">Donor ID:</span>
                <span class="info-value"><strong><?php echo htmlspecialchars($donor_data['donor_unique_id']); ?></strong></span>
            </div>
            <div class="info-row">
                <span class="info-label">Date of Birth:</span>
                <span class="info-value"><?php echo date('d-M-Y', strtotime($donor_data['date_of_birth'])); ?> (Age: <?php echo $age; ?> years)</span>
            </div>
            <div class="info-row">
                <span class="info-label">Gender:</span>
                <span class="info-value"><?php echo $donor_data['gender']; ?></span>
            </div>
            <div class="info-row">
                <span class="info-label">Blood Group:</span>
                <span class="info-value"><strong><?php echo htmlspecialchars($donor_data['blood_group'] ?? 'Not Determined'); ?></strong></span>
            </div>
            <div class="info-row">
                <span class="info-label">Donor Type:</span>
                <span class="info-value"><?php echo str_replace('_', ' ', $donor_data['donor_type']); ?></span>
            </div>
            <div class="info-row">
                <span class="info-label">Registered On:</span>
                <span class="info-value"><?php echo date('d-M-Y H:i', strtotime($donor_data['created_date'])); ?></span>
            </div>
        </div>

        <!-- Contact Information -->
        <div class="info-card">
            <div class="card-title">
                <i class="ri-phone-fill"></i> Contact Information
            </div>
            <div class="info-row">
                <span class="info-label">Mobile Number:</span>
                <span class="info-value"><a href="tel:<?php echo htmlspecialchars($donor_data['mobile_number']); ?>"><?php echo htmlspecialchars($donor_data['mobile_number']); ?></a></span>
            </div>
            <?php if ($donor_data['alternate_mobile']): ?>
                <div class="info-row">
                    <span class="info-label">Alternate Mobile:</span>
                    <span class="info-value"><a href="tel:<?php echo htmlspecialchars($donor_data['alternate_mobile']); ?>"><?php echo htmlspecialchars($donor_data['alternate_mobile']); ?></a></span>
                </div>
            <?php endif; ?>
            <?php if ($donor_data['email']): ?>
                <div class="info-row">
                    <span class="info-label">Email:</span>
                    <span class="info-value"><a href="mailto:<?php echo htmlspecialchars($donor_data['email']); ?>"><?php echo htmlspecialchars($donor_data['email']); ?></a></span>
                </div>
            <?php endif; ?>
            <div class="info-row">
                <span class="info-label">Address:</span>
                <span class="info-value"><?php echo htmlspecialchars($donor_data['address']); ?>, <?php echo htmlspecialchars($donor_data['city'] . ', ' . $donor_data['state'] . ' - ' . $donor_data['pincode']); ?></span>
            </div>
        </div>

        <!-- Tabs for detailed information -->
        <div class="tabs-section">
            <ul class="nav nav-tabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="medical-tab" data-toggle="tab" href="#medical" role="tab">
                        <i class="ri-heart-pulse-fill"></i> Medical History
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="examination-tab" data-toggle="tab" href="#examination" role="tab">
                        <i class="ri-stethoscope-line"></i> Examination
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="audit-tab" data-toggle="tab" href="#audit" role="tab">
                        <i class="ri-file-list-3-line"></i> Audit Trail
                    </a>
                </li>
            </ul>


            <div class="tab-content">
                <!-- Medical History Tab -->
                <div class="tab-pane fade show active" id="medical" role="tabpanel">
                    <?php if ($medical_data): ?>
                        <h5 class="mb-3">Risk Assessment: <span class="badge badge-info"><?php echo str_replace('_', ' ', $medical_data['risk_assessment_status']); ?></span></h5>
                        
                        <div class="info-row">
                            <span class="info-label">Total Previous Donations:</span>
                            <span class="info-value"><?php echo $medical_data['total_donations']; ?></span>
                        </div>
                        
                        <h6 class="mt-4 mb-3">Health Status:</h6>
                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">
                            <div>
                                <strong>Recent Illness:</strong><br>
                                <?php echo $medical_data['recent_illness_flag'] ? '✓ Yes - ' . htmlspecialchars($medical_data['illness_description']) : '✗ No'; ?>
                            </div>
                            <div>
                                <strong>Recent Surgery:</strong><br>
                                <?php echo $medical_data['recent_surgery_flag'] ? '✓ Yes - ' . htmlspecialchars($medical_data['surgery_description']) : '✗ No'; ?>
                            </div>
                            <div>
                                <strong>Chronic Disease:</strong><br>
                                <?php echo $medical_data['has_chronic_disease'] ? '✓ Yes - ' . htmlspecialchars($medical_data['chronic_disease_list']) : '✗ No'; ?>
                            </div>
                            <div>
                                <strong>Current Medication:</strong><br>
                                <?php echo $medical_data['on_medication'] ? '✓ Yes - ' . htmlspecialchars($medical_data['medication_list']) : '✗ No'; ?>
                            </div>
                            <div>
                                <strong>Recent Vaccination:</strong><br>
                                <?php echo $medical_data['recent_vaccination_flag'] ? '✓ Yes - ' . htmlspecialchars($medical_data['vaccination_type']) : '✗ No'; ?>
                            </div>
                            <div>
                                <strong>High-Risk Behavior:</strong><br>
                                <?php echo $medical_data['high_risk_behavior_flag'] ? '✓ Yes' : '✗ No'; ?>
                            </div>
                            <div>
                                <strong>Cancer History:</strong><br>
                                <?php echo $medical_data['has_cancer_history'] ? '✓ Yes' : '✗ No'; ?>
                            </div>
                            <div>
                                <strong>Blood Disorder:</strong><br>
                                <?php echo $medical_data['has_blood_disorder'] ? '✓ Yes' : '✗ No'; ?>
                            </div>
                            <div>
                                <strong>HIV Risk:</strong><br>
                                <?php echo $medical_data['has_hiv_risk'] ? '✓ Yes' : '✗ No'; ?>
                            </div>
                            <div>
                                <strong>Hepatitis Risk:</strong><br>
                                <?php echo $medical_data['hepatitis_risk'] ? '✓ Yes' : '✗ No'; ?>
                            </div>
                        </div>
                    <?php else: ?>
                        <p class="text-muted"><i class="ri-information-line"></i> Medical history not yet completed</p>
                    <?php endif; ?>
                </div>

                <!-- Examination Tab -->
                <div class="tab-pane fade" id="examination" role="tabpanel">
                    <?php if ($exam_data): ?>
                        <h5 class="mb-3">Examination Date: <strong><?php echo date('d-M-Y', strtotime($exam_data['examination_date'])); ?></strong></h5>
                        
                        <div class="examination-params">
                            <div class="param-card">
                                <div class="param-label">Weight</div>
                                <div class="param-value"><?php echo $exam_data['weight_kg']; ?><span class="param-unit"> kg</span></div>
                                <small class="text-muted">Min: 50 kg | <?php echo $exam_data['weight_eligible'] ? '✓ Pass' : '✗ Fail'; ?></small>
                            </div>
                            <div class="param-card">
                                <div class="param-label">Hemoglobin</div>
                                <div class="param-value"><?php echo $exam_data['hemoglobin_gm_dl']; ?><span class="param-unit"> g/dL</span></div>
                                <small class="text-muted">Status: <?php echo $exam_data['hemoglobin_status']; ?></small>
                            </div>
                            <div class="param-card">
                                <div class="param-label">Blood Pressure</div>
                                <div class="param-value"><?php echo $exam_data['blood_pressure_systolic'] . '/' . $exam_data['blood_pressure_diastolic']; ?><span class="param-unit"> mmHg</span></div>
                                <small class="text-muted"><?php echo $exam_data['bp_eligible'] ? '✓ Normal' : '⚠ Out of Range'; ?></small>
                            </div>
                            <div class="param-card">
                                <div class="param-label">Pulse Rate</div>
                                <div class="param-value"><?php echo $exam_data['pulse_rate']; ?><span class="param-unit"> bpm</span></div>
                                <small class="text-muted"><?php echo $exam_data['pulse_eligible'] ? '✓ Normal' : '⚠ Abnormal'; ?></small>
                            </div>
                            <div class="param-card">
                                <div class="param-label">Temperature</div>
                                <div class="param-value"><?php echo $exam_data['temperature_celsius']; ?><span class="param-unit"> °C</span></div>
                                <small class="text-muted"><?php echo $exam_data['temperature_normal'] ? '✓ Normal' : '⚠ Abnormal'; ?></small>
                            </div>
                        </div>

                        <h6 class="mt-4">Infection Screening:</h6>
                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 15px;">
                            <div><strong>HIV:</strong> <?php echo $exam_data['hiv_result']; ?></div>
                            <div><strong>Hepatitis B:</strong> <?php echo $exam_data['hepatitis_b_result']; ?></div>
                            <div><strong>Hepatitis C:</strong> <?php echo $exam_data['hepatitis_c_result']; ?></div>
                            <div><strong>Syphilis:</strong> <?php echo $exam_data['syphilis_result']; ?></div>
                            <div><strong>Malaria:</strong> <?php echo $exam_data['malaria_result']; ?></div>
                        </div>

                        <?php if ($exam_data['medical_staff_comments']): ?>
                            <h6 class="mt-4">Medical Comments:</h6>
                            <p><?php echo htmlspecialchars($exam_data['medical_staff_comments']); ?></p>
                        <?php endif; ?>
                    <?php else: ?>
                        <p class="text-muted"><i class="ri-information-line"></i> Physical examination not yet completed</p>
                    <?php endif; ?>
                </div>

                <!-- Audit Trail Tab -->
                <div class="tab-pane fade" id="audit" role="tabpanel">
                    <?php if ($audit_result && $audit_result->num_rows > 0): ?>
                        <h5 class="mb-3">Activity History</h5>
                        <div style="max-height: 500px; overflow-y: auto;">
                            <?php while ($audit = $audit_result->fetch_assoc()): ?>
                                <div class="audit-log-item">
                                    <div>
                                        <span class="audit-action"><?php echo str_replace('_', ' ', $audit['action_type']); ?></span>
                                        <span class="text-muted float-end"><?php echo date('d-M-Y H:i', strtotime($audit['performed_date'])); ?></span>
                                    </div>
                                    <small class="text-muted">By: <?php echo htmlspecialchars($audit['performed_by']); ?></small>
                                    <?php if ($audit['new_value']): ?>
                                        <div class="mt-1 text-muted"><small><?php echo htmlspecialchars($audit['new_value']); ?></small></div>
                                    <?php endif; ?>
                                </div>
                            <?php endwhile; ?>
                        </div>
                    <?php else: ?>
                        <p class="text-muted"><i class="ri-information-line"></i> No audit records found</p>
                    <?php endif; ?>
                </div>
            </div>
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
        // Initialize Bootstrap 4 tabs
        $(document).ready(function() {
            $('#medical-tab, #examination-tab, #audit-tab').on('click', function(e) {
                e.preventDefault();
                $(this).tab('show');
            });
        });
    </script>
</body>
</html>

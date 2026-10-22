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

// Get donor details
$donor_result = $donor->getDonorDetails();
if (!$donor_result || $donor_result->num_rows == 0) {
    header("Location: blood_donor_list.php?error=Donor not found");
    exit;
}
$donor_data = $donor_result->fetch_assoc();

// Get examination details
$exam_result = $donor->getLatestExamination();
$exam_data = $exam_result->fetch_assoc() ?? null;

// Get medical history
$history_result = $donor->getMedicalHistory();
$history_data = $history_result->fetch_assoc() ?? null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="theme-color" content="#319197">

    <title>Blood Donor Eligibility Evaluation</title>
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
        .result-card {
            background: white;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 15px;
            border-left: 4px solid #ddd;
        }
        .result-card.pass {
            border-left-color: #28a745;
            background-color: #f0f9f5;
        }
        .result-card.fail {
            border-left-color: #dc3545;
            background-color: #fdf5f5;
        }
        .result-label {
            font-weight: 600;
            color: #333;
            margin-bottom: 5px;
        }
        .result-value {
            font-size: 18px;
            font-weight: bold;
        }
        .result-pass {
            color: #28a745;
        }
        .result-fail {
            color: #dc3545;
        }
        .progress-indicator {
            background: #e9ecef;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .decision-banner {
            padding: 30px;
            border-radius: 8px;
            margin: 20px 0;
            text-align: center;
        }
        .decision-banner.eligible {
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
            border: 2px solid #28a745;
            color: #155724;
        }
        .decision-banner.deferred {
            background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
            border: 2px solid #dc3545;
            color: #721c24;
        }
        .decision-title {
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 10px;
        }
        .btn-proceed {
            background-color: #28a745;
            color: white;
            padding: 12px 30px;
            font-weight: 600;
            border: none;
            border-radius: 5px;
        }
        .btn-proceed:hover {
            background-color: #218838;
            color: white;
        }
        .btn-defer {
            background-color: #dc3545;
            color: white;
            padding: 12px 30px;
            font-weight: 600;
            border: none;
            border-radius: 5px;
        }
        .btn-defer:hover {
            background-color: #c82333;
            color: white;
        }
        .donor-info-card {
            background: #e7f3ff;
            border-left: 4px solid #0066cc;
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
                        <h4 class="text-uppercase"><i class="ri-checkbox-circle-line"></i> Eligibility Evaluation - Stage 4</h4>
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
                                <h5 class="card-title mb-0"><i class="ri-shield-check-line"></i> Eligibility Evaluation</h5>
                                <small class="text-muted">Review examination and medical history for final determination</small>
                            </div>
                            <div class="card-body">

        <!-- Progress -->
        <div class="progress-indicator">
            <strong>Progress:</strong> Registration (✓) → Medical History (✓) → Examination (✓) → Eligibility (<strong>Current</strong>) → Deferral (if needed)
        </div>

        <!-- Examination Results Summary -->
        <div class="form-section">
            <div class="section-title">
                <i class="bi bi-bar-chart"></i> Examination Results Summary
            </div>

            <?php if ($exam_data): ?>
                <div class="row">
                    <div class="col-md-6">
                        <div class="result-card <?php echo ($exam_data['weight_eligible'] ? 'pass' : 'fail'); ?>">
                            <div class="result-label">Weight</div>
                            <div class="result-value">
                                <span class="<?php echo ($exam_data['weight_eligible'] ? 'result-pass' : 'result-fail'); ?>">
                                    <?php echo $exam_data['weight_kg']; ?> kg
                                </span>
                            </div>
                            <small class="text-muted">Min: 50 kg | Status: <?php echo ($exam_data['weight_eligible'] ? '✓ Pass' : '✗ Fail'); ?></small>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="result-card <?php echo ($exam_data['hemoglobin_eligible'] ? 'pass' : 'fail'); ?>">
                            <div class="result-label">Hemoglobin</div>
                            <div class="result-value">
                                <span class="<?php echo ($exam_data['hemoglobin_eligible'] ? 'result-pass' : 'result-fail'); ?>">
                                    <?php echo $exam_data['hemoglobin_gm_dl']; ?> g/dL
                                </span>
                            </div>
                            <small class="text-muted">
                                Min: <?php echo ($donor_data['gender'] == 'Female') ? '12.5' : '13.5'; ?> g/dL | 
                                Status: <?php echo ($exam_data['hemoglobin_eligible'] ? '✓ Pass' : '✗ Low'); ?>
                            </small>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="result-card <?php echo ($exam_data['bp_eligible'] ? 'pass' : 'fail'); ?>">
                            <div class="result-label">Blood Pressure</div>
                            <div class="result-value">
                                <span class="<?php echo ($exam_data['bp_eligible'] ? 'result-pass' : 'result-fail'); ?>">
                                    <?php echo $exam_data['blood_pressure_systolic'] . '/' . $exam_data['blood_pressure_diastolic']; ?> mmHg
                                </span>
                            </div>
                            <small class="text-muted">Target: 90-180/60-100 | Status: <?php echo ($exam_data['bp_eligible'] ? '✓ Pass' : '⚠ Out of Range'); ?></small>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="result-card <?php echo ($exam_data['pulse_eligible'] ? 'pass' : 'fail'); ?>">
                            <div class="result-label">Pulse Rate</div>
                            <div class="result-value">
                                <span class="<?php echo ($exam_data['pulse_eligible'] ? 'result-pass' : 'result-fail'); ?>">
                                    <?php echo $exam_data['pulse_rate']; ?> bpm
                                </span>
                            </div>
                            <small class="text-muted">Target: 50-100 bpm | Status: <?php echo ($exam_data['pulse_eligible'] ? '✓ Pass' : '⚠ Abnormal'); ?></small>
                        </div>
                    </div>
                </div>

                <?php if ($exam_data['overall_physical_eligible']): ?>
                    <div class="alert alert-success mt-3" role="alert">
                        <i class="bi bi-check-circle"></i> <strong>Physical Examination Status:</strong> All parameters are within acceptable range
                    </div>
                <?php else: ?>
                    <div class="alert alert-warning mt-3" role="alert">
                        <i class="bi bi-exclamation-triangle"></i> <strong>Physical Examination Status:</strong> Some parameters are outside acceptable range
                    </div>
                <?php endif; ?>

            <?php else: ?>
                <div class="alert alert-danger" role="alert">
                    <i class="bi bi-exclamation-circle"></i> No examination record found
                </div>
            <?php endif; ?>
        </div>

        <!-- Medical History Review -->
        <?php if ($history_data): ?>
            <div class="form-section">
                <div class="section-title">
                    <i class="bi bi-file-medical"></i> Medical History Risk Assessment
                </div>

                <div class="result-card">
                    <div class="result-label">Risk Assessment Status</div>
                    <div class="result-value">
                        <?php 
                        $risk_class = '';
                        $risk_icon = '';
                        if ($history_data['risk_assessment_status'] == 'Low_Risk') {
                            $risk_class = 'result-pass';
                            $risk_icon = '✓';
                        } elseif ($history_data['risk_assessment_status'] == 'Medium_Risk') {
                            $risk_class = 'text-warning';
                            $risk_icon = '⚠';
                        } else {
                            $risk_class = 'result-fail';
                            $risk_icon = '✗';
                        }
                        ?>
                        <span class="<?php echo $risk_class; ?>">
                            <?php echo $risk_icon; ?> <?php echo str_replace('_', ' ', $history_data['risk_assessment_status']); ?>
                        </span>
                    </div>
                    <small class="text-muted">Risk Flags: <?php echo $history_data['risk_flags_count']; ?></small>
                </div>

                <?php if ($history_data['high_risk_behavior_flag'] || $history_data['has_hiv_risk'] || $history_data['hepatitis_risk']): ?>
                    <div class="alert alert-danger mt-3" role="alert">
                        <i class="bi bi-exclamation-triangle"></i> <strong>High-Risk Factors Detected:</strong><br>
                        This donor has declared or shown high-risk factors that may lead to permanent deferral.
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <!-- Eligibility Decision Form -->
        <form method="POST" action="controller/processBloodDonor.php" id="eligibility_form">
            <input type="hidden" name="action" value="evaluateEligibility">
            <input type="hidden" name="donor_id" value="<?php echo $donor_id; ?>">

            <div class="form-section">
                <div class="section-title">
                    <i class="bi bi-pencil-square"></i> Eligibility Decision
                </div>

                <p class="text-muted">Click the button below to auto-evaluate eligibility based on examination and medical history data.</p>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-submit" style="background-color: #007bff;">
                        <i class="bi bi-arrow-right"></i> Auto-Evaluate Eligibility
                    </button>
                </div>
            </div>
        </form>

        <!-- Disclaimer -->
        <div class="alert alert-info" role="alert">
            <i class="bi bi-info-circle"></i> <strong>System Information:</strong> 
            The eligibility decision is based on medical parameters and risk assessment. Medical professionals 
            can override this decision if deemed medically appropriate.
        </div>
    </div>

    <!-- Footer -->
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

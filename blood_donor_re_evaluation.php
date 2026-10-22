<?php
require_once(__DIR__ . '/model/session_config.php');
include('model/bloodDonorClass.php');
date_default_timezone_set("Asia/Kolkata");

// Check authorization
if(!isset($_SESSION['loginid'])) {
    header("Location: login.php");
    exit;
}

$donor_id = $_GET['donor_id'] ?? 0;
if (!$donor_id) {
    header("Location: blood_donor_list.php?error=Invalid donor");
    exit;
}

// Get donor details
$donor = new bloodDonor();
$donor->setDonorId($donor_id);
$donor_result = $donor->getDonorDetails();

if (!$donor_result || $donor_result->num_rows == 0) {
    header("Location: blood_donor_list.php?error=Donor not found");
    exit;
}

$donor_data = $donor_result->fetch_assoc();

// Verify donor is in Pending_Re_Evaluation status
if ($donor_data['current_status'] != 'Pending_Re_Evaluation') {
    header("Location: blood_donor_profile.php?donor_id=$donor_id&error=Donor is not pending re-evaluation");
    exit;
}

// Get cleared deferral details using model class
$donor->setDonorId($donor_id);
$deferral_result = $donor->getClearedDeferralDetails();
$deferral_data = $deferral_result ? mysqli_fetch_assoc($deferral_result) : null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Donor Re-Evaluation Workflow</title>
    <link rel="icon" type="image/png" sizes="16x16" href="images/favicon.png">
    <link href="css/style.css" rel="stylesheet">
    <style>
        .re-eval-container {
            max-width: 1000px;
            margin: 0 auto;
        }
        .info-card {
            background: white;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 25px;
            margin-bottom: 25px;
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
        .workflow-steps {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
            position: relative;
        }
        .workflow-step {
            flex: 1;
            text-align: center;
            position: relative;
            padding: 20px 10px;
        }
        .workflow-step::after {
            content: '';
            position: absolute;
            top: 40px;
            right: -50%;
            width: 100%;
            height: 2px;
            background: #ddd;
            z-index: -1;
        }
        .workflow-step:last-child::after {
            display: none;
        }
        .step-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: #f8f9fa;
            border: 3px solid #ddd;
            margin: 0 auto 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }
        .step-completed .step-icon {
            background: #d4edda;
            border-color: #28a745;
            color: #28a745;
        }
        .step-current .step-icon {
            background: #fff3cd;
            border-color: #ffc107;
            color: #856404;
            animation: pulse 2s infinite;
        }
        .step-pending .step-icon {
            background: #f8f9fa;
            border-color: #ddd;
            color: #999;
        }
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }
        .step-title {
            font-weight: 600;
            color: #333;
            margin-top: 10px;
        }
        .alert-warning {
            background-color: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 25px;
        }
        .alert-info {
            background-color: #d1ecf1;
            border-left: 4px solid #17a2b8;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 25px;
        }
        .action-button {
            background-color: #dc3545;
            color: white;
            padding: 15px 40px;
            border: none;
            border-radius: 5px;
            font-weight: 600;
            font-size: 16px;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }
        .action-button:hover {
            background-color: #c82333;
            color: white;
        }
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
        }
        .info-item {
            padding: 15px;
            background: #f8f9fa;
            border-radius: 5px;
            border-left: 4px solid #dc3545;
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
    <div id="main-wrapper">
        <?php include('leftbar.php'); ?>
        <div class="content-body">
            <div class="container-fluid">
                <div class="re-eval-container">
                    <div class="card">
                        <div class="card-header" style="border-bottom: 2px solid #dc3545;">
                            <h4 class="card-title">
                                <i class="ri-refresh-line"></i> Donor Re-Evaluation Workflow
                            </h4>
                        </div>
                        <div class="card-body">
                            
                            <!-- Alert: Deferral Cleared -->
                            <div class="alert-warning">
                                <strong><i class="ri-alert-line"></i> Deferral Period Completed</strong>
                                <p style="margin-top: 10px; margin-bottom: 0;">
                                    This donor's temporary deferral has expired. A complete medical re-evaluation is required before donation can proceed.
                                </p>
                            </div>

                            <!-- Donor Information -->
                            <div class="info-card">
                                <div class="card-title">
                                    <i class="ri-user-3-fill"></i> Donor Information
                                </div>
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
                                        <div class="info-label">Current Status</div>
                                        <div class="info-value" style="color: #ffc107;"><?php echo str_replace('_', ' ', $donor_data['current_status']); ?></div>
                                    </div>
                                </div>
                            </div>

                            <!-- Previous Deferral Details -->
                            <?php if ($deferral_data): ?>
                            <div class="info-card">
                                <div class="card-title">
                                    <i class="ri-history-line"></i> Previous Deferral Details
                                </div>
                                <div class="info-grid">
                                    <div class="info-item">
                                        <div class="info-label">Deferral Reason</div>
                                        <div class="info-value"><?php echo htmlspecialchars($deferral_data['deferral_reason']); ?></div>
                                    </div>
                                    <div class="info-item">
                                        <div class="info-label">Deferral Type</div>
                                        <div class="info-value"><?php echo $deferral_data['deferral_type']; ?></div>
                                    </div>
                                    <div class="info-item">
                                        <div class="info-label">End Date</div>
                                        <div class="info-value"><?php echo date('d-M-Y', strtotime($deferral_data['deferral_end_date'])); ?></div>
                                    </div>
                                    <div class="info-item">
                                        <div class="info-label">Cleared On</div>
                                        <div class="info-value"><?php echo $deferral_data['clearance_date'] ? date('d-M-Y', strtotime($deferral_data['clearance_date'])) : 'N/A'; ?></div>
                                    </div>
                                </div>
                                <?php if ($deferral_data['deferral_notes']): ?>
                                <div style="margin-top: 15px;">
                                    <strong>Previous Notes:</strong>
                                    <p style="margin-top: 5px; color: #666;"><?php echo nl2br(htmlspecialchars($deferral_data['deferral_notes'])); ?></p>
                                </div>
                                <?php endif; ?>
                            </div>
                            <?php endif; ?>

                            <!-- Re-Evaluation Workflow Steps -->
                            <div class="info-card">
                                <div class="card-title">
                                    <i class="ri-route-line"></i> Re-Evaluation Process
                                </div>
                                
                                <div class="workflow-steps">
                                    <div class="workflow-step step-current">
                                        <div class="step-icon">
                                            <i class="ri-refresh-line"></i>
                                        </div>
                                        <div class="step-title">Review Clearance</div>
                                        <div style="font-size: 12px; color: #666; margin-top: 5px;">Current Step</div>
                                    </div>
                                    <div class="workflow-step step-pending">
                                        <div class="step-icon">
                                            <i class="ri-heart-pulse-line"></i>
                                        </div>
                                        <div class="step-title">Medical History</div>
                                        <div style="font-size: 12px; color: #666; margin-top: 5px;">Stage 2</div>
                                    </div>
                                    <div class="workflow-step step-pending">
                                        <div class="step-icon">
                                            <i class="ri-stethoscope-line"></i>
                                        </div>
                                        <div class="step-title">Examination</div>
                                        <div style="font-size: 12px; color: #666; margin-top: 5px;">Stage 3</div>
                                    </div>
                                    <div class="workflow-step step-pending">
                                        <div class="step-icon">
                                            <i class="ri-checkbox-circle-line"></i>
                                        </div>
                                        <div class="step-title">Eligibility</div>
                                        <div style="font-size: 12px; color: #666; margin-top: 5px;">Final Approval</div>
                                    </div>
                                </div>

                                <div class="alert-info">
                                    <strong><i class="ri-information-line"></i> Required Actions:</strong>
                                    <ol style="margin-top: 10px; margin-bottom: 0; padding-left: 20px;">
                                        <li><strong>Medical History Review:</strong> Complete fresh medical questionnaire to check if previous deferral condition is resolved</li>
                                        <li><strong>Physical Examination:</strong> Conduct vital signs check, hemoglobin test, and overall health assessment</li>
                                        <li><strong>Medical Officer Approval:</strong> Final decision on eligibility based on re-evaluation results</li>
                                    </ol>
                                </div>

                                <!-- Action Buttons -->
                                <div style="margin-top: 30px; text-align: center;">
                                    <a href="blood_donor_profile.php?donor_id=<?php echo $donor_id; ?>" class="btn btn-secondary" style="margin-right: 10px;">
                                        <i class="ri-arrow-left-line"></i> Back to Profile
                                    </a>
                                    <a href="blood_donor_medical_history.php?donor_id=<?php echo $donor_id; ?>&re_evaluation=1" class="action-button">
                                        <i class="ri-clipboard-line"></i> Start Medical History (Stage 2)
                                    </a>
                                </div>

                                <!-- Warning Note -->
                                <div style="margin-top: 20px; padding: 15px; background: #f8d7da; border-radius: 5px; border-left: 4px solid #dc3545;">
                                    <strong><i class="ri-error-warning-line"></i> Important:</strong> 
                                    Donor cannot proceed to blood collection until all re-evaluation stages are completed and medical officer approves eligibility.
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="vendor/global/global.min.js"></script>
    <script src="js/custom.min.js"></script>
    <script src="js/deznav-init.js"></script>
</body>
</html>

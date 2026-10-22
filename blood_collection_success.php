<?php
require_once(__DIR__ . '/model/session_config.php');
include('model/bloodDonorClass.php');
date_default_timezone_set("Asia/Kolkata");

// Check authorization
if(!isset($_SESSION['loginid'])) {
    header("Location: login.php");
    exit;
}

$collection_id = $_GET['collection_id'] ?? 0;
$unit_id = $_GET['unit_id'] ?? 0;

if (!$collection_id || !$unit_id) {
    header("Location: blood_donor_list.php");
    exit;
}

// Fetch collection details using model class
$donor = new bloodDonor();
$result = $donor->getCollectionWithUnitDetails($collection_id, $unit_id);
if(!$result || mysqli_num_rows($result) == 0) {
    header("Location: blood_donor_list.php?error=Collection not found");
    exit;
}

$data = mysqli_fetch_assoc($result);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Blood Collection Success</title>
    <link rel="icon" type="image/png" sizes="16x16" href="images/favicon.png">
    <link href="css/style.css" rel="stylesheet">
    <style>
        .success-container {
            max-width: 900px;
            margin: 0 auto;
        }
        .success-header {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            padding: 40px;
            border-radius: 10px 10px 0 0;
            text-align: center;
        }
        .success-icon {
            font-size: 72px;
            margin-bottom: 20px;
        }
        .info-card {
            background: white;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 25px;
            margin-bottom: 20px;
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
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }
        .info-item {
            padding: 15px;
            background: #f8f9fa;
            border-radius: 8px;
            border-left: 4px solid #dc3545;
        }
        .info-label {
            font-size: 12px;
            color: #666;
            font-weight: 600;
            text-transform: uppercase;
            margin-bottom: 5px;
        }
        .info-value {
            font-size: 18px;
            color: #333;
            font-weight: 600;
        }
        .barcode-display {
            font-family: 'Courier New', monospace;
            font-size: 24px;
            font-weight: bold;
            background: #f8f9fa;
            padding: 20px;
            text-align: center;
            border: 2px dashed #dc3545;
            border-radius: 8px;
            margin: 20px 0;
        }
        .status-badge {
            display: inline-block;
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 14px;
        }
        .status-unscreened {
            background-color: #fff3cd;
            color: #856404;
            border: 2px solid #ffc107;
        }
        .alert-box {
            background-color: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 20px;
            border-radius: 5px;
            margin: 20px 0;
        }
        .btn-print {
            background-color: #17a2b8;
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 5px;
            font-weight: 600;
            cursor: pointer;
        }
        .btn-print:hover {
            background-color: #138496;
        }
    </style>
</head>
<body>
    <div id="main-wrapper">
        <?php include('leftbar.php'); ?>
        <div class="content-body">
            <div class="container-fluid">
                <div class="success-container">
                    <div class="success-header">
                        <div class="success-icon">
                            <i class="ri-checkbox-circle-fill"></i>
                        </div>
                        <h2>Blood Collection Completed Successfully!</h2>
                        <p style="margin-top: 15px; font-size: 18px;">Blood unit created and ready for testing</p>
                    </div>

                    <div class="card" style="border-radius: 0 0 10px 10px;">
                        <div class="card-body">
                            
                            <!-- Donor Information -->
                            <div class="info-card">
                                <div class="card-title">
                                    <i class="ri-user-3-fill"></i> Donor Information
                                </div>
                                <div class="info-grid">
                                    <div class="info-item">
                                        <div class="info-label">Donor ID</div>
                                        <div class="info-value"><?php echo htmlspecialchars($data['donor_unique_id']); ?></div>
                                    </div>
                                    <div class="info-item">
                                        <div class="info-label">Donor Name</div>
                                        <div class="info-value"><?php echo htmlspecialchars($data['first_name'] . ' ' . $data['last_name']); ?></div>
                                    </div>
                                    <div class="info-item">
                                        <div class="info-label">Blood Group</div>
                                        <div class="info-value"><?php echo htmlspecialchars($data['blood_group']); ?></div>
                                    </div>
                                </div>
                            </div>

                            <!-- Blood Unit Details -->
                            <div class="info-card">
                                <div class="card-title">
                                    <i class="ri-test-tube-fill"></i> Blood Unit Details
                                </div>
                                
                                <div class="barcode-display">
                                    <div style="font-size: 14px; color: #666; margin-bottom: 10px;">UNIT BARCODE</div>
                                    <?php echo htmlspecialchars($data['unit_barcode']); ?>
                                </div>

                                <div class="info-grid">
                                    <div class="info-item">
                                        <div class="info-label">Bag Number</div>
                                        <div class="info-value"><?php echo htmlspecialchars($data['bag_number']); ?></div>
                                    </div>
                                    <div class="info-item">
                                        <div class="info-label">Collection Number</div>
                                        <div class="info-value"><?php echo htmlspecialchars($data['collection_number']); ?></div>
                                    </div>
                                    <div class="info-item">
                                        <div class="info-label">Volume Collected</div>
                                        <div class="info-value"><?php echo $data['volume_collected_ml']; ?> ml</div>
                                    </div>
                                    <div class="info-item">
                                        <div class="info-label">Collection Date</div>
                                        <div class="info-value"><?php echo date('d-M-Y', strtotime($data['collection_date'])); ?></div>
                                    </div>
                                    <div class="info-item">
                                        <div class="info-label">Expiry Date</div>
                                        <div class="info-value"><?php echo date('d-M-Y', strtotime($data['expiry_date'])); ?></div>
                                    </div>
                                    <div class="info-item">
                                        <div class="info-label">Unit Status</div>
                                        <div class="info-value">
                                            <span class="status-badge status-unscreened">
                                                <i class="ri-lock-line"></i> <?php echo str_replace('_', ' ', $data['unit_status']); ?>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Important Alert -->
                            <div class="alert-box">
                                <strong><i class="ri-alert-line"></i> Important Next Steps:</strong>
                                <ul style="margin-top: 10px; margin-bottom: 0;">
                                    <li><strong>Unit Status:</strong> <?php echo str_replace('_', ' ', $data['unit_status']); ?> - NOT USABLE</li>
                                    <li><strong>Required Action:</strong> This blood unit must undergo testing and screening (Stage 3)</li>
                                    <li><strong>Inventory Lock:</strong> Unit cannot be issued until testing is completed and approved</li>
                                    <li><strong>Traceability:</strong> Unit barcode <?php echo $data['unit_barcode']; ?> links to donor <?php echo $data['donor_unique_id']; ?></li>
                                </ul>
                            </div>

                            <!-- Action Buttons -->
                            <div class="row" style="margin-top: 30px;">
                                <div class="col-md-12 text-center">
                                    <button onclick="window.print()" class="btn-print">
                                        <i class="ri-printer-line"></i> Print Collection Summary
                                    </button>
                                    <a href="blood_donor_profile.php?donor_id=<?php echo $data['donor_id']; ?>" class="btn btn-secondary">
                                        <i class="ri-user-line"></i> View Donor Profile
                                    </a>
                                    <a href="blood_donor_list.php" class="btn btn-primary">
                                        <i class="ri-team-line"></i> Back to Donor List
                                    </a>
                                    <a href="add_blood_collection.php?donor_id=<?php echo $data['donor_id']; ?>" class="btn" style="background-color: #dc3545; color: white;">
                                        <i class="ri-add-line"></i> New Collection
                                    </a>
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

<?php
require_once(__DIR__ . '/model/session_config.php');
include('model/bloodDonorClass.php');
date_default_timezone_set("Asia/Kolkata");

// Check authorization
if(!isset($_SESSION['loginid'])) {
    header("Location: login.php");
    exit;
}

// Get donors pending re-evaluation
$bloodDonor = new bloodDonor();
$result = $bloodDonor->getDonorsPendingReEvaluation();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Donors Pending Re-Evaluation</title>
    <link rel="icon" type="image/png" sizes="16x16" href="images/favicon.png">
    <link href="css/style.css" rel="stylesheet">
    <style>
    .content-body {
            margin-top: 120px;
        }
        .priority-high {
            background-color: #f8d7da;
            color: #721c24;
            padding: 4px 12px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 11px;
        }
        .priority-normal {
            background-color: #fff3cd;
            color: #856404;
            padding: 4px 12px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 11px;
        }
        .days-badge {
            background-color: #d1ecf1;
            color: #0c5460;
            padding: 4px 12px;
            border-radius: 12px;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div id="main-wrapper">
        <?php include('leftbar.php'); ?>
        <div class="content-body">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">
                                    <i class="ri-refresh-line"></i> Donors Pending Re-Evaluation
                                </h4>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th>Donor ID</th>
                                                <th>Name</th>
                                                <th>Blood Group</th>
                                                <th>Mobile</th>
                                                <th>Previous Deferral Reason</th>
                                                <th>Cleared On</th>
                                                <th>Days Since</th>
                                                <th>Priority</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if($result && mysqli_num_rows($result) > 0): ?>
                                                <?php while($row = mysqli_fetch_assoc($result)): ?>
                                                    <tr>
                                                        <td><strong><?php echo htmlspecialchars($row['donor_unique_id']); ?></strong></td>
                                                        <td><?php echo htmlspecialchars($row['donor_name']); ?></td>
                                                        <td><strong><?php echo htmlspecialchars($row['blood_group']); ?></strong></td>
                                                        <td><?php echo htmlspecialchars($row['mobile_number']); ?></td>
                                                        <td><?php echo htmlspecialchars($row['deferral_reason'] ?? 'N/A'); ?></td>
                                                        <td><?php echo $row['clearance_date'] ? date('d-M-Y', strtotime($row['clearance_date'])) : 'N/A'; ?></td>
                                                        <td>
                                                            <span class="days-badge">
                                                                <?php echo $row['days_since_clearance'] ?? 0; ?> days
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <?php if($row['re_evaluation_priority'] == 'High'): ?>
                                                                <span class="priority-high">HIGH</span>
                                                            <?php else: ?>
                                                                <span class="priority-normal">NORMAL</span>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td>
                                                            <a href="blood_donor_re_evaluation.php?donor_id=<?php echo $row['donor_id']; ?>" class="btn btn-sm btn-primary">
                                                                <i class="ri-clipboard-line"></i> Start Re-Evaluation
                                                            </a>
                                                            <a href="blood_donor_profile.php?donor_id=<?php echo $row['donor_id']; ?>" class="btn btn-sm btn-secondary">
                                                                <i class="ri-eye-line"></i> View
                                                            </a>
                                                        </td>
                                                    </tr>
                                                <?php endwhile; ?>
                                            <?php else: ?>
                                                <tr>
                                                    <td colspan="9" class="text-center">
                                                        <i class="ri-checkbox-circle-line" style="font-size: 48px; color: #28a745;"></i>
                                                        <p style="margin-top: 10px;">No donors pending re-evaluation</p>
                                                    </td>
                                                </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
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

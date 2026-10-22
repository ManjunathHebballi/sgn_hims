<?php
/**
 * Automated Deferral Clearance Script
 * Purpose: Check and clear expired temporary deferrals daily
 * Execution: Run via cron job daily at 2 AM
 * Cron: 0 2 * * * /usr/bin/php /path/to/check_expired_deferrals.php
 */

// Set execution path
if (php_sapi_name() !== 'cli') {
    die('This script must be run from command line');
}

// Include database connection and model class
require_once(__DIR__ . '/../model/dbconnect.php');
require_once(__DIR__ . '/../model/bloodDonorClass.php');
date_default_timezone_set("Asia/Kolkata");

// Log file
$log_file = __DIR__ . '/logs/deferral_clearance_' . date('Y-m') . '.log';
$log_dir = dirname($log_file);
if (!is_dir($log_dir)) {
    mkdir($log_dir, 0755, true);
}

function logMessage($message) {
    global $log_file;
    $timestamp = date('Y-m-d H:i:s');
    file_put_contents($log_file, "[$timestamp] $message\n", FILE_APPEND);
    echo "[$timestamp] $message\n";
}

try {
    logMessage("=== Starting Automated Deferral Clearance Process ===");
    
    $con = new userlogin();
    $db = $con->dbconnect1(); // Use non-session connection for automated scripts
    
    if (!$db) {
        throw new Exception("Database connection failed");
    }
    
    // Start transaction
    mysqli_begin_transaction($db);
    
    // Step 1: Find all expired temporary deferrals using model class
    $result = bloodDonor::getExpiredDeferrals($db);
    
    if (!$result) {
        throw new Exception("Failed to query expired deferrals: " . mysqli_error($db));
    }
    
    $expired_count = mysqli_num_rows($result);
    logMessage("Found $expired_count expired deferral(s) to process");
    
    if ($expired_count == 0) {
        logMessage("No expired deferrals to process today");
        mysqli_commit($db);
        logMessage("=== Process Completed Successfully ===");
        exit(0);
    }
    
    $cleared_count = 0;
    $error_count = 0;
    
    while ($deferral = mysqli_fetch_assoc($result)) {
        $deferral_id = $deferral['deferral_id'];
        $donor_id = $deferral['donor_id'];
        $donor_unique_id = $deferral['donor_unique_id'];
        $donor_name = $deferral['first_name'] . ' ' . $deferral['last_name'];
        $mobile = $deferral['mobile_number'];
        $end_date = $deferral['deferral_end_date'];
        
        logMessage("Processing Donor: $donor_unique_id - $donor_name (Deferral ID: $deferral_id)");
        
        try {
            // Step 2: Deactivate the deferral using model method
            if (!bloodDonor::clearDeferralById($db, $deferral_id)) {
                throw new Exception("Failed to clear deferral: " . mysqli_error($db));
            }
            
            // Step 3: Update donor status to Pending_Re_Evaluation using model method
            $update_result = bloodDonor::updateDonorToPendingReEvaluation($db, $donor_id);
            if (!$update_result['success']) {
                throw new Exception("Failed to update donor status: " . mysqli_error($db));
            }
            
            $rows_affected = $update_result['rows_affected'];
            
            // Step 4: Create audit trail using model method
            bloodDonor::createAuditTrail($db, $donor_id, 'DEFERRAL_CLEARED', 'Deferred_Temporary', 'Pending_Re_Evaluation');
            
            // Step 5: Add to re-evaluation queue using model method
            bloodDonor::addToReEvaluationQueue($db, $donor_id);
            
            logMessage("✓ Successfully cleared deferral for $donor_unique_id (Rows affected: $rows_affected)");
            $cleared_count++;
            
            // Optional: Send SMS/Email notification
            // sendNotification($mobile, "Your blood donation deferral period is complete. Please visit for medical re-evaluation.");
            
        } catch (Exception $e) {
            logMessage("✗ Error processing donor $donor_unique_id: " . $e->getMessage());
            $error_count++;
            // Continue with next deferral instead of failing entire batch
        }
    }
    
    // Commit transaction
    mysqli_commit($db);
    
    logMessage("=== Process Summary ===");
    logMessage("Total Expired: $expired_count");
    logMessage("Successfully Cleared: $cleared_count");
    logMessage("Errors: $error_count");
    logMessage("=== Process Completed ===");
    
    // Close database connection
    mysqli_close($db);
    
    exit(0);
    
} catch (Exception $e) {
    if (isset($db)) {
        mysqli_rollback($db);
    }
    logMessage("CRITICAL ERROR: " . $e->getMessage());
    logMessage("=== Process Failed ===");
    exit(1);
}

/**
 * Optional: Send SMS/Email notification function
 */
// function sendNotification($mobile, $message) {
    // Implement SMS/Email gateway integration here
    // Example: SMS Gateway API call
    // logMessage("Notification sent to $mobile");
// }
?>

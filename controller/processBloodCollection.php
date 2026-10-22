<?php
require_once(__DIR__ . '/../model/session_config.php');
include('../model/bloodDonorClass.php');
date_default_timezone_set("Asia/Kolkata");

// Check authorization
if(!isset($_SESSION['loginid'])) {
    header("Location: ../login.php");
    exit;
}

if($_REQUEST['action'] == 'addCollection') {
    
    $donor_id = $_REQUEST['donor_id'];
    $donor_unique_id = $_REQUEST['donor_unique_id'];
    $collection_date = $_REQUEST['collection_date'];
    $collection_time = $_REQUEST['collection_time'];
    $collection_location = $_REQUEST['collection_location'];
    $camp_location = $_REQUEST['camp_location'] ?? null;
    
    $blood_bag_type = $_REQUEST['blood_bag_type'];
    $anticoagulant_type = $_REQUEST['anticoagulant_type'];
    $volume_collected_ml = $_REQUEST['volume_collected_ml'];
    $collection_method = $_REQUEST['collection_method'];
    $collection_duration_minutes = $_REQUEST['collection_duration_minutes'] ?? null;
    
    $adverse_reaction_flag = isset($_REQUEST['adverse_reaction_flag']) ? 1 : 0;
    $adverse_reaction_type = $_REQUEST['adverse_reaction_type'] ?? null;
    $adverse_reaction_severity = $_REQUEST['adverse_reaction_severity'] ?? null;
    $adverse_reaction_notes = $_REQUEST['adverse_reaction_notes'] ?? null;
    $adverse_reaction_managed_by = $_REQUEST['adverse_reaction_managed_by'] ?? null;
    
    $observation_duration_minutes = $_REQUEST['observation_duration_minutes'];
    $observation_status = $_REQUEST['observation_status'];
    $observation_notes = $_REQUEST['observation_notes'] ?? null;
    $refreshment_provided = isset($_REQUEST['refreshment_provided']) ? 1 : 0;
    
    $collection_notes = $_REQUEST['collection_notes'] ?? null;
    
    $created_by = $_SESSION['loginid'];
    $collected_by_name = $_SESSION['name'];
    
    // Validate volume range
    if($volume_collected_ml < 350 || $volume_collected_ml > 500) {
        header("Location: ../add_blood_collection.php?donor_id=$donor_id&error=Invalid volume collected (350-500ml required)");
        exit;
    }
    
    try {
        $donor = new bloodDonor();
        $con = new userlogin();
        $db = $con->dbconnect();
        
        if(!$db) {
            throw new Exception("Database connection failed");
        }
        
        // Start transaction
        mysqli_begin_transaction($db);
        
        // 1. Generate unique collection number (BC-YYYYMMDD-XXXX)
        $collection_number = generateCollectionNumber($db);
        
        // 2. Get donor details for blood group and gender
        $donor->setDonorId($donor_id);
        $donor_result = $donor->getDonorDetails();
        $donor_data = $donor_result->fetch_assoc();
        $blood_group = $donor_data['blood_group'] ?? 'Unknown';
        $gender = $donor_data['gender'];
        
        // Determine Rh factor
        $rh_factor = (strpos($blood_group, '+') !== false) ? 'Positive' : 'Negative';
        
        // 3. Calculate observation times
        $observation_start = date('Y-m-d H:i:s', strtotime("$collection_date $collection_time"));
        $observation_end = date('Y-m-d H:i:s', strtotime($observation_start . " + $observation_duration_minutes minutes"));
        $discharged_time = $observation_end;
        
        // 4. Insert blood collection record
        $sql_collection = "INSERT INTO blood_collections (
            collection_number, donor_id, donor_unique_id, collection_date, collection_time,
            collection_location, camp_location, collected_by_staff_id, collected_by_name,
            blood_bag_type, anticoagulant_type, volume_collected_ml, collection_method,
            collection_duration_minutes, adverse_reaction_flag, adverse_reaction_type,
            adverse_reaction_severity, adverse_reaction_notes, adverse_reaction_managed_by,
            observation_start_time, observation_end_time, observation_duration_minutes,
            observation_status, observation_notes, donor_discharged_time, refreshment_provided,
            collection_status, collection_notes, created_by, created_date
        ) VALUES (
            '$collection_number', $donor_id, '$donor_unique_id', '$collection_date', '$collection_time',
            '$collection_location', " . ($camp_location ? "'$camp_location'" : "NULL") . ", $created_by, '$collected_by_name',
            '$blood_bag_type', '$anticoagulant_type', $volume_collected_ml, '$collection_method',
            " . ($collection_duration_minutes ? $collection_duration_minutes : "NULL") . ", $adverse_reaction_flag, " . ($adverse_reaction_type ? "'$adverse_reaction_type'" : "NULL") . ",
            " . ($adverse_reaction_severity ? "'$adverse_reaction_severity'" : "NULL") . ", " . ($adverse_reaction_notes ? "'" . mysqli_real_escape_string($db, $adverse_reaction_notes) . "'" : "NULL") . ", " . ($adverse_reaction_managed_by ? "'$adverse_reaction_managed_by'" : "NULL") . ",
            '$observation_start', '$observation_end', $observation_duration_minutes,
            '$observation_status', " . ($observation_notes ? "'" . mysqli_real_escape_string($db, $observation_notes) . "'" : "NULL") . ", '$discharged_time', $refreshment_provided,
            'Completed', " . ($collection_notes ? "'" . mysqli_real_escape_string($db, $collection_notes) . "'" : "NULL") . ", $created_by, NOW()
        )";
        
        if(!mysqli_query($db, $sql_collection)) {
            throw new Exception("Failed to create collection record: " . mysqli_error($db));
        }
        
        $collection_id = mysqli_insert_id($db);
        
        // 5. Generate unique blood unit barcode and bag number
        $unit_barcode = generateUnitBarcode($db);
        $bag_number = generateBagNumber($db);
        
        // 6. Calculate expiry date based on bag type (whole blood default 35 days)
        $shelf_life_days = 35; // Default for single bag
        if(strpos($blood_bag_type, 'Double') !== false || strpos($blood_bag_type, 'Triple') !== false || strpos($blood_bag_type, 'Quadruple') !== false) {
            $shelf_life_days = 42;
        }
        $expiry_date = date('Y-m-d', strtotime($collection_date . " + $shelf_life_days days"));
        
        // 7. Insert blood unit record (LOCKED - NOT USABLE)
        $sql_unit = "INSERT INTO blood_units (
            unit_barcode, bag_number, collection_id, donor_id, blood_group, rh_factor,
            component_type, volume_ml, collection_date, expiry_date, unit_status,
            is_usable, is_available_for_issue, screening_status, created_by, created_date
        ) VALUES (
            '$unit_barcode', '$bag_number', $collection_id, $donor_id, '$blood_group', '$rh_factor',
            'Whole_Blood', $volume_collected_ml, '$collection_date', '$expiry_date', 'Collected_Unscreened',
            0, 0, 'Pending', $created_by, NOW()
        )";
        
        if(!mysqli_query($db, $sql_unit)) {
            throw new Exception("Failed to create blood unit: " . mysqli_error($db));
        }
        
        $unit_id = mysqli_insert_id($db);
        
        // 8. Calculate cooling period (56 days for males, 90 days for females, 2 days for apheresis)
        $cooling_days = ($gender == 'Male') ? 56 : 90;
        if($collection_method == 'Apheresis') {
            $cooling_days = 2;
        }
        $next_eligible_date = date('Y-m-d', strtotime($collection_date . " + $cooling_days days"));
        
        // 9. Update donor master table
        $sql_update_donor = "UPDATE blood_donor_master SET 
            last_donation_date = '$collection_date',
            total_donations_lifetime = total_donations_lifetime + 1,
            next_eligible_donation_date = '$next_eligible_date',
            last_modified_date = NOW(),
            last_modified_by = '$collected_by_name'
            WHERE donor_id = $donor_id";
        
        if(!mysqli_query($db, $sql_update_donor)) {
            throw new Exception("Failed to update donor record: " . mysqli_error($db));
        }
        
        // 10. Insert cooling period tracker
        $sql_cooling = "INSERT INTO donor_cooling_periods (
            donor_id, last_donation_date, next_eligible_date, cooling_days, donation_type, created_date
        ) VALUES (
            $donor_id, '$collection_date', '$next_eligible_date', $cooling_days, '$collection_method', NOW()
        )";
        
        mysqli_query($db, $sql_cooling); // Non-critical, don't throw on failure
        
        // 11. Log adverse event if occurred
        if($adverse_reaction_flag && $adverse_reaction_type) {
            $event_time = date('Y-m-d H:i:s', strtotime("$collection_date $collection_time"));
            $event_resolved = ($observation_status == 'Normal_Recovery') ? 1 : 0;
            
            $sql_adverse = "INSERT INTO collection_adverse_events (
                collection_id, donor_id, event_time, event_type, event_severity,
                event_description, intervention_required, event_resolved, recorded_by, recorded_date
            ) VALUES (
                $collection_id, $donor_id, '$event_time', '$adverse_reaction_type', '$adverse_reaction_severity',
                '" . mysqli_real_escape_string($db, $adverse_reaction_notes) . "', 1, $event_resolved, $created_by, NOW()
            )";
            
            mysqli_query($db, $sql_adverse); // Non-critical
        }
        
        // 12. Create audit trail entry
        $action_type = "BLOOD_COLLECTION";
        $new_value = "Collection: $collection_number | Unit: $unit_barcode | Volume: {$volume_collected_ml}ml | Status: Collected_Unscreened";
        
        $sql_audit = "INSERT INTO blood_donor_audit_log (
            donor_id, action_type, old_value, new_value, performed_by, performed_date
        ) VALUES (
            $donor_id, '$action_type', NULL, '" . mysqli_real_escape_string($db, $new_value) . "', '$collected_by_name', NOW()
        )";
        
        mysqli_query($db, $sql_audit); // Non-critical
        
        // Commit transaction
        mysqli_commit($db);
        
        // Success - redirect to collection summary or blood unit page
        header("Location: ../blood_collection_success.php?collection_id=$collection_id&unit_id=$unit_id&success=Blood collection completed successfully");
        exit;
        
    } catch (Exception $e) {
        // Rollback on error
        if(isset($db)) {
            mysqli_rollback($db);
        }
        
        error_log("Blood Collection Error: " . $e->getMessage());
        header("Location: ../add_blood_collection.php?donor_id=$donor_id&error=" . urlencode($e->getMessage()));
        exit;
    }
}

// Helper function to generate unique collection number
function generateCollectionNumber($db) {
    $date_prefix = date('Ymd');
    $sql = "SELECT collection_number FROM blood_collections 
            WHERE collection_number LIKE 'BC-$date_prefix-%' 
            ORDER BY collection_id DESC LIMIT 1";
    $result = mysqli_query($db, $sql);
    
    if($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $last_number = $row['collection_number'];
        $parts = explode('-', $last_number);
        $sequence = intval($parts[2]) + 1;
    } else {
        $sequence = 1;
    }
    
    return 'BC-' . $date_prefix . '-' . str_pad($sequence, 4, '0', STR_PAD_LEFT);
}

// Helper function to generate unique unit barcode
function generateUnitBarcode($db) {
    $year = date('Y');
    $sql = "SELECT unit_barcode FROM blood_units 
            WHERE unit_barcode LIKE 'BU-$year%' 
            ORDER BY unit_id DESC LIMIT 1";
    $result = mysqli_query($db, $sql);
    
    if($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $last_barcode = $row['unit_barcode'];
        $parts = explode('-', $last_barcode);
        $sequence = intval($parts[2]) + 1;
    } else {
        $sequence = 1;
    }
    
    return 'BU-' . $year . '-' . str_pad($sequence, 6, '0', STR_PAD_LEFT);
}

// Helper function to generate unique bag number
function generateBagNumber($db) {
    $date_prefix = date('Ymd');
    $sql = "SELECT bag_number FROM blood_units 
            WHERE bag_number LIKE 'BAG-$date_prefix%' 
            ORDER BY unit_id DESC LIMIT 1";
    $result = mysqli_query($db, $sql);
    
    if($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $last_bag = $row['bag_number'];
        $parts = explode('-', $last_bag);
        $sequence = intval($parts[2]) + 1;
    } else {
        $sequence = 1;
    }
    
    return 'BAG-' . $date_prefix . '-' . str_pad($sequence, 4, '0', STR_PAD_LEFT);
}
?>

<?php
/**
 * Patient Details API
 * Fetches patient information by UHID or Mobile Number
 * Returns: JSON response with patient details
 */

// Set headers for JSON response
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Allow-Headers: Content-Type');

// Start session if not already started
if(!isset($_SESSION)) { 
    session_start(); 
}

// Include database connection
require_once(__DIR__ . '/../model/dbconnect.php');

// Set timezone
date_default_timezone_set("Asia/Kolkata");

/**
 * Function to fetch patient details by UHID or Mobile
 */
function getPatientDetails($searchValue, $searchType) {
    try {
        $newlogin = new userlogin();
        $con = $newlogin->dbconnect1(); // Using dbconnect1 (no session check for API)
        
        // Sanitize input
        $searchValue = mysqli_real_escape_string($con, trim($searchValue));
        
        // Build query based on search type
        if ($searchType === 'uhid') {
            $sql = "SELECT `uhid`, `name`, `mobile`, `opd_no`, `age`, `gender`, `address`, `date` 
                    FROM `central_opd` 
                    WHERE `uhid` = '$searchValue' 
                    ORDER BY `id` DESC 
                    LIMIT 1";
        } elseif ($searchType === 'mobile') {
            $sql = "SELECT `uhid`, `name`, `mobile`, `opd_no`, `age`, `gender`, `address`, `date` 
                    FROM `central_opd` 
                    WHERE `mobile` = '$searchValue' 
                    ORDER BY `id` DESC 
                    LIMIT 1";
        } else {
            return [
                'success' => false,
                'message' => 'Invalid search type. Use "uhid" or "mobile".',
                'data' => null
            ];
        }
        
        // Execute query
        $result = mysqli_query($con, $sql);
        
        if (!$result) {
            return [
                'success' => false,
                'message' => 'Database query failed: ' . mysqli_error($con),
                'data' => null
            ];
        }
        
        // Check if patient found
        if (mysqli_num_rows($result) > 0) {
            $patient = mysqli_fetch_assoc($result);
            
            return [
                'success' => true,
                'message' => 'Patient details retrieved successfully',
                'data' => [
                    'uhid' => $patient['uhid'],
                    'name' => $patient['name'],
                    'mobile' => $patient['mobile'],
                    'opd_no' => $patient['opd_no'],
                    'age' => $patient['age'],
                    'gender' => $patient['gender'],
                    'address' => $patient['address'],
                    'last_visit_date' => $patient['date']
                ]
            ];
        } else {
            return [
                'success' => false,
                'message' => 'No patient found with the provided ' . $searchType,
                'data' => null
            ];
        }
        
    } catch (Exception $e) {
        return [
            'success' => false,
            'message' => 'Error: ' . $e->getMessage(),
            'data' => null
        ];
    }
}

/**
 * Main API Logic
 */
$response = [];

// Check request method
if ($_SERVER['REQUEST_METHOD'] === 'GET' || $_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Initialize variables
    $uhid = '';
    $mobile = '';
    
    // Check if request body contains JSON
    $contentType = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';
    
    if (strpos($contentType, 'application/json') !== false) {
        // Get JSON input from request body
        $input = file_get_contents('php://input');
        $data = json_decode($input, true);
        
        if (json_last_error() === JSON_ERROR_NONE && is_array($data)) {
            $uhid = isset($data['uhid']) ? $data['uhid'] : '';
            $mobile = isset($data['mobile']) ? $data['mobile'] : '';
        } else {
            $response = [
                'success' => false,
                'message' => 'Invalid JSON format in request body',
                'data' => null
            ];
            echo json_encode($response, JSON_PRETTY_PRINT);
            exit;
        }
    } else {
        // Get parameters from URL/form data (backward compatibility)
        $uhid = isset($_REQUEST['uhid']) ? $_REQUEST['uhid'] : '';
        $mobile = isset($_REQUEST['mobile']) ? $_REQUEST['mobile'] : '';
    }
    
    // Validate input
    if (empty($uhid) && empty($mobile)) {
        $response = [
            'success' => false,
            'message' => 'Please provide either UHID or Mobile number',
            'data' => null
        ];
    } elseif (!empty($uhid)) {
        // Search by UHID
        $response = getPatientDetails($uhid, 'uhid');
    } elseif (!empty($mobile)) {
        // Search by Mobile
        $response = getPatientDetails($mobile, 'mobile');
    }
    
} else {
    $response = [
        'success' => false,
        'message' => 'Invalid request method. Use GET or POST.',
        'data' => null
    ];
}

// Return JSON response
echo json_encode($response, JSON_PRETTY_PRINT);
exit;

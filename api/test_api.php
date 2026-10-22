<?php
/**
 * Simple PHP Script to Test Patient Details API
 * Run this file directly to test API functionality
 */

// Configuration
$base_url = "http://localhost/hospital/api/patient_details.php";

echo "<!DOCTYPE html>
<html>
<head>
    <title>API Test Results</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; background: #f5f5f5; }
        .test-case { background: white; padding: 20px; margin: 20px 0; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .test-title { color: #333; font-size: 18px; font-weight: bold; margin-bottom: 10px; }
        .success { color: #155724; background: #d4edda; padding: 10px; border-radius: 5px; }
        .error { color: #721c24; background: #f8d7da; padding: 10px; border-radius: 5px; }
        .info { color: #004085; background: #cce5ff; padding: 10px; border-radius: 5px; margin-bottom: 20px; }
        pre { background: #f8f9fa; padding: 15px; border-radius: 5px; overflow-x: auto; }
        h1 { color: #667eea; }
    </style>
</head>
<body>
    <h1>🧪 Patient Details API - Test Results</h1>
    <div class='info'>
        <strong>API Endpoint:</strong> $base_url<br>
        <strong>Test Date:</strong> " . date('Y-m-d H:i:s') . "
    </div>";

/**
 * Test Case 1: Search by Valid UHID
 */
echo "<div class='test-case'>
        <div class='test-title'>Test Case 1: Search by Valid UHID</div>";

$test_uhid = "ALV2024001"; // Replace with actual UHID from your database
$url = $base_url . "?uhid=" . urlencode($test_uhid);

echo "<p><strong>Request URL:</strong> $url</p>";

$response = @file_get_contents($url);

if ($response !== false) {
    $data = json_decode($response, true);
    
    if ($data['success']) {
        echo "<div class='success'>✓ Test Passed - Patient Found</div>";
        echo "<pre>" . print_r($data, true) . "</pre>";
    } else {
        echo "<div class='error'>✗ Test Result: " . $data['message'] . "</div>";
        echo "<pre>" . print_r($data, true) . "</pre>";
    }
} else {
    echo "<div class='error'>✗ Connection Error: Unable to connect to API</div>";
}

echo "</div>";

/**
 * Test Case 2: Search by Valid Mobile Number
 */
echo "<div class='test-case'>
        <div class='test-title'>Test Case 2: Search by Valid Mobile Number</div>";

$test_mobile = "9876543210"; // Replace with actual mobile from your database
$url = $base_url . "?mobile=" . urlencode($test_mobile);

echo "<p><strong>Request URL:</strong> $url</p>";

$response = @file_get_contents($url);

if ($response !== false) {
    $data = json_decode($response, true);
    
    if ($data['success']) {
        echo "<div class='success'>✓ Test Passed - Patient Found</div>";
        echo "<pre>" . print_r($data, true) . "</pre>";
    } else {
        echo "<div class='error'>✗ Test Result: " . $data['message'] . "</div>";
        echo "<pre>" . print_r($data, true) . "</pre>";
    }
} else {
    echo "<div class='error'>✗ Connection Error: Unable to connect to API</div>";
}

echo "</div>";

/**
 * Test Case 3: Search with Invalid UHID
 */
echo "<div class='test-case'>
        <div class='test-title'>Test Case 3: Search with Invalid UHID (Should Fail)</div>";

$test_uhid = "INVALID999999";
$url = $base_url . "?uhid=" . urlencode($test_uhid);

echo "<p><strong>Request URL:</strong> $url</p>";

$response = @file_get_contents($url);

if ($response !== false) {
    $data = json_decode($response, true);
    
    if (!$data['success']) {
        echo "<div class='success'>✓ Test Passed - Correctly returned error for invalid UHID</div>";
        echo "<pre>" . print_r($data, true) . "</pre>";
    } else {
        echo "<div class='error'>✗ Test Failed: Should not find patient with invalid UHID</div>";
        echo "<pre>" . print_r($data, true) . "</pre>";
    }
} else {
    echo "<div class='error'>✗ Connection Error: Unable to connect to API</div>";
}

echo "</div>";

/**
 * Test Case 4: No Parameters
 */
echo "<div class='test-case'>
        <div class='test-title'>Test Case 4: No Parameters (Should Return Error)</div>";

$url = $base_url;

echo "<p><strong>Request URL:</strong> $url</p>";

$response = @file_get_contents($url);

if ($response !== false) {
    $data = json_decode($response, true);
    
    if (!$data['success']) {
        echo "<div class='success'>✓ Test Passed - Correctly returned error for missing parameters</div>";
        echo "<pre>" . print_r($data, true) . "</pre>";
    } else {
        echo "<div class='error'>✗ Test Failed: Should require at least one parameter</div>";
        echo "<pre>" . print_r($data, true) . "</pre>";
    }
} else {
    echo "<div class='error'>✗ Connection Error: Unable to connect to API</div>";
}

echo "</div>";

/**
 * Test Case 5: JSON Response Validation
 */
echo "<div class='test-case'>
        <div class='test-title'>Test Case 5: JSON Response Format Validation</div>";

$test_uhid = "ALV2024001";
$url = $base_url . "?uhid=" . urlencode($test_uhid);

$response = @file_get_contents($url);

if ($response !== false) {
    $data = json_decode($response, true);
    
    $required_keys = ['success', 'message', 'data'];
    $all_present = true;
    
    foreach ($required_keys as $key) {
        if (!array_key_exists($key, $data)) {
            $all_present = false;
            echo "<div class='error'>✗ Missing required key: $key</div>";
        }
    }
    
    if ($all_present) {
        echo "<div class='success'>✓ Test Passed - All required JSON keys present</div>";
        
        if ($data['data'] !== null) {
            $data_keys = ['uhid', 'name', 'mobile'];
            foreach ($data_keys as $key) {
                if (array_key_exists($key, $data['data'])) {
                    echo "<p>✓ Data contains: $key = " . $data['data'][$key] . "</p>";
                }
            }
        }
    }
} else {
    echo "<div class='error'>✗ Connection Error: Unable to connect to API</div>";
}

echo "</div>";

echo "<div class='info'>
        <strong>Note:</strong> Replace test UHID and mobile numbers in this script with actual values from your database for accurate testing.
    </div>
</body>
</html>";
?>

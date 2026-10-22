<?php
// Simple test to check if RetailXpress API is accessible

$curl = curl_init();

curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://api.retailxpress.ai/v1/external/inventory?page=1&limit=5&sort_by=name&sort_order=asc',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'GET',
    CURLOPT_HTTPHEADER => array(
        'x-api-key: 8417daa38eb33753f60ecbabe28d359bea4b4589d8c791f563b00b3de7c53543'
    ),
));

$response = curl_exec($curl);
$httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
$error = curl_error($curl);

curl_close($curl);

header('Content-Type: application/json');

echo "HTTP Code: " . $httpCode . "\n\n";

if ($error) {
    echo "cURL Error: " . $error . "\n\n";
}

echo "Response:\n";
echo $response;

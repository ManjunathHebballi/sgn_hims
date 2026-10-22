<?php
require_once(__DIR__ . '/../model/session_config.php');

// API proxy to fetch medicines from RetailXpress
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {

    if ($_POST['action'] === 'get_medicines') {
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $limit = isset($_POST['limit']) ? intval($_POST['limit']) : 20;
        $search = isset($_POST['search']) ? trim($_POST['search']) : '';

        // Build query parameters
        $queryParams = [
            'page' => $page,
            'limit' => $limit,
            'sort_by' => 'name',
            'sort_order' => 'asc'
        ];

        if (!empty($search)) {
            $queryParams['search'] = $search;
        }

        $url = 'https://api.retailxpress.ai/v1/external/inventory?' . http_build_query($queryParams);

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
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

        if ($error) {
            echo json_encode([
                'status' => false,
                'message' => 'API request failed: ' . $error
            ]);
        } else if ($httpCode !== 200) {
            echo json_encode([
                'status' => false,
                'message' => 'API returned error code: ' . $httpCode
            ]);
        } else {
            echo $response;
        }
        exit;
    }
}

// Invalid request
echo json_encode([
    'status' => false,
    'message' => 'Invalid request'
]);

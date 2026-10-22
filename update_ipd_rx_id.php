<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
date_default_timezone_set("Asia/Kolkata");
set_time_limit(0); // Long-running batch script

include('model/dbconnect.php');
$con = new userlogin();
$db = $con->dbconnect1(); // No session required

// ── 1. Add rx_id column to central_ipd if it doesn't exist ──────────────────
$checkCol = mysqli_query($db, "SHOW COLUMNS FROM `central_ipd` LIKE 'rx_id'");
if (mysqli_num_rows($checkCol) === 0) {
    mysqli_query($db, "ALTER TABLE `central_ipd` ADD COLUMN `rx_id` varchar(100) DEFAULT NULL");
    echo "Column rx_id added to central_ipd.<br>\n";
} else {
    echo "Column rx_id already exists in central_ipd.<br>\n";
}

// ── 2. Fetch IPD records that still need an rx_id ───────────────────────────
// Join central_opd to get the UHID (unique_code for the API)
$sql = "SELECT ci.id, ci.name, ci.mobile, ci.address, ci.gender, ci.opd_no,
               co.uhid
        FROM central_ipd ci
        LEFT JOIN central_opd co ON co.opd_no = ci.opd_no
        WHERE (ci.rx_id IS NULL OR ci.rx_id = '') and ci.discharge=0 and ci.is_generated=0
        ORDER BY ci.id ASC";

$result = mysqli_query($db, $sql);
if (!$result) {
    die("Query failed: " . mysqli_error($db) . "\n");
}

$total   = mysqli_num_rows($result);
$updated = 0;
$failed  = 0;

echo "Total records to process: {$total}<br>\n";
flush();

// ── 3. Process each record ───────────────────────────────────────────────────
while ($row = mysqli_fetch_assoc($result)) {
    $ipdId      = (int)$row['id'];
    $name       = (string)$row['name'];
    $address    = (string)$row['address'];
    $uhid       = (string)$row['uhid'];
    $customerId = null;

    // Normalise gender
    $gender = strtoupper(trim((string)$row['gender']));
    if (in_array($gender, ['M', 'MALE'])) {
        $gender = 'MALE';
    } elseif (in_array($gender, ['F', 'FEMALE'])) {
        $gender = 'FEMALE';
    } else {
        $gender = strtoupper($gender); // leave as-is for OTHER / TRANSGENDER etc.
    }

    // Normalise phone
    $phoneClean = preg_replace('/\D+/', '', (string)$row['mobile']);

    // ── Step 1: Search existing customer by phone ────────────────────────────
    if (!empty($phoneClean)) {
        $searchCurl = curl_init();
        curl_setopt_array($searchCurl, [
            CURLOPT_URL            => 'https://api.retailxpress.ai/v1/external/customer?page=1&limit=20&sort_by=createdAt&sort_order=desc&search=' . urlencode($phoneClean),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING       => '',
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST  => 'GET',
            CURLOPT_HTTPHEADER     => [
                'x-api-key: 8417daa38eb33753f60ecbabe28d359bea4b4589d8c791f563b00b3de7c53543'
            ],
        ]);
        $searchResponse = curl_exec($searchCurl);
        curl_close($searchCurl);
        $searchResult = json_decode($searchResponse, true);

        // Match by phone AND name
        $candidates = [];
        if (isset($searchResult['data']['items']) && is_array($searchResult['data']['items'])) {
            $candidates = $searchResult['data']['items'];
        } elseif (isset($searchResult['data']) && is_array($searchResult['data'])) {
            $candidates = $searchResult['data'];
        }

        foreach ($candidates as $candidate) {
            if (
                isset($candidate['full_name']) &&
                strcasecmp(trim($candidate['full_name']), trim($name)) === 0
            ) {
                $customerId = $candidate['id'];
                break;
            }
        }
    }

    // ── Step 2: Create customer if not found ─────────────────────────────────
    if (empty($customerId)) {
        $customerPayload = [
            "full_name"    => $name,
            "phone_number" => $phoneClean,
            "address"      => $address,
            "gender"       => $gender,
            "unique_code"  => $uhid
        ];

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL            => 'https://api.retailxpress.ai/v1/external/customer',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING       => '',
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST  => 'POST',
            CURLOPT_POSTFIELDS     => json_encode($customerPayload),
            CURLOPT_HTTPHEADER     => [
                'x-api-key: 8417daa38eb33753f60ecbabe28d359bea4b4589d8c791f563b00b3de7c53543',
                'Content-Type: application/json'
            ],
        ]);
        $response = curl_exec($curl);
        curl_close($curl);
        $createResult = json_decode($response, true);
        $customerId   = $createResult['data']['id'] ?? null;
    }

    // ── Step 3: Update central_ipd.rx_id ────────────────────────────────────
    if (!empty($customerId)) {
        $safeId = mysqli_real_escape_string($db, $customerId);
        $update = mysqli_query($db, "UPDATE `central_ipd` SET `rx_id`='$safeId' WHERE `id`=$ipdId");
        if ($update) {
            $updated++;
            echo "ID {$ipdId} ({$name}) → rx_id = {$customerId}<br>\n";
        } else {
            $failed++;
            echo "ID {$ipdId} ({$name}) → UPDATE failed: " . mysqli_error($db) . "<br>\n";
        }
    } else {
        $failed++;
        echo "ID {$ipdId} ({$name}) → Could not get/create customer from API<br>\n";
    }

    flush();
}

// ── 4. Summary ───────────────────────────────────────────────────────────────
echo "<br>\n<b>Done.</b> Updated: {$updated} | Failed/Skipped: {$failed} | Total: {$total}<br>\n";

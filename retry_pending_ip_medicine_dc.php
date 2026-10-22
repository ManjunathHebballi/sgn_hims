<?php
require_once(__DIR__ . '/model/session_config.php');
include(__DIR__ . '/model/dbconnect.php');

date_default_timezone_set('Asia/Calcutta');

function h($value)
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function tableColumnExists($con, $table, $column)
{
    $tableSafe = mysqli_real_escape_string($con, $table);
    $columnSafe = mysqli_real_escape_string($con, $column);
    $res = mysqli_query($con, "SHOW COLUMNS FROM `$tableSafe` LIKE '$columnSafe'");
    return $res && mysqli_num_rows($res) > 0;
}

function getPendingStatusColumn($con)
{
    $candidates = array('delivery_status', 'status', 'rx_status');
    foreach ($candidates as $column) {
        if (tableColumnExists($con, 'ip_medicine', $column)) {
            return $column;
        }
    }
    return '';
}

function fetchPendingRows($con, $patientId = '', $limit = 0)
{
    $statusColumn = getPendingStatusColumn($con);

    $where = " WHERE (im.`dc_no` IS NULL OR im.`dc_no`='')
               AND ci.`rx_id` IS NOT NULL AND ci.`rx_id`!=''
               AND rp.`rx_id` IS NOT NULL AND rp.`rx_id`!=''";

    if ($statusColumn !== '') {
        $where .= " AND UPPER(COALESCE(im.`$statusColumn`, 'PENDING'))='PENDING'";
    }

    if ($patientId !== '') {
        $patientSafe = mysqli_real_escape_string($con, $patientId);
        $where .= " AND im.`patient_id`='$patientSafe'";
    }

    $limitSql = '';
    if ($limit > 0) {
        $limitSql = ' LIMIT ' . (int)$limit;
    }

    $sql = "SELECT
                im.`id`,
                im.`patient_id`,
                im.`qty`,
                rp.`rx_id` AS `product_id`,
                rp.`product_name`,
                ci.`rx_id` AS `customer_id`,
                ci.`patient_type`,
                im.`created_by`,
                DATE(im.`created_at`) AS `issue_date`
            FROM `ip_medicine` im
            INNER JOIN `central_ipd` ci ON ci.`ipd_no`=im.`patient_id`
            INNER JOIN `rx_pharmacy` rp ON rp.`id`=im.`medicine`
            $where
            ORDER BY im.`patient_id` ASC, DATE(im.`created_at`) ASC, im.`id` ASC$limitSql";

    $res = mysqli_query($con, $sql);
    $rows = array();

    if ($res) {
        while ($row = mysqli_fetch_assoc($res)) {
            $rows[] = $row;
        }
    }

    return array($rows, $statusColumn, $sql);
}

function groupRowsForPayload($rows)
{
    $batches = array();

    foreach ($rows as $row) {
        $key = $row['patient_id'] . '|' . $row['customer_id'] . '|' . $row['issue_date'];
        if (!isset($batches[$key])) {
            $batches[$key] = array(
                'patient_id' => $row['patient_id'],
                'customer_id' => $row['customer_id'],
                'patient_type' => !empty($row['patient_type']) ? $row['patient_type'] : 'STD',
                'created_by' => !empty($row['created_by']) ? $row['created_by'] : 'Unknown',
                'issue_date' => !empty($row['issue_date']) ? $row['issue_date'] : date('Y-m-d'),
                'ids' => array(),
                'items' => array()
            );
        }

        $batches[$key]['ids'][] = (int)$row['id'];
        $batches[$key]['items'][] = array(
            'position' => count($batches[$key]['items']) + 1,
            'product_id' => (string)$row['product_id'],
            'quantity' => (int)$row['qty']
        );
    }

    return array_values($batches);
}

function sendRetailXDeliveryChallan($payload)
{
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api.retailxpress.ai/v1/external/retailer-delivery-challan',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => json_encode($payload),
        CURLOPT_HTTPHEADER => array(
            'x-api-key: 8417daa38eb33753f60ecbabe28d359bea4b4589d8c791f563b00b3de7c53543',
            'Content-Type: application/json'
        ),
    ));

    $response = curl_exec($curl);
    $curlError = curl_error($curl);
    $httpCode = (int)curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);

    $decoded = json_decode($response, true);

    return array(
        'http_code' => $httpCode,
        'curl_error' => $curlError,
        'raw_response' => $response,
        'delivery_challan_id' => $decoded['data']['id'] ?? null,
        'delivery_challan_number' => $decoded['data']['delivery_challan_number'] ?? null,
    );
}

$login = new userlogin();
$con = $login->dbconnect1();

$run = isset($_REQUEST['run']) && $_REQUEST['run'] === '1';
$dryRun = isset($_REQUEST['dry_run']) && $_REQUEST['dry_run'] === '1';
$patientFilter = isset($_REQUEST['patient_id']) ? trim($_REQUEST['patient_id']) : '';
$limit = isset($_REQUEST['limit']) ? max(0, (int)$_REQUEST['limit']) : 0;
$indentBy = isset($_SESSION['name']) ? $_SESSION['name'] : 'Pending DC Retry Script';

list($rows, $statusColumn, $debugSql) = fetchPendingRows($con, $patientFilter, $limit);
$batches = groupRowsForPayload($rows);

$results = array();
$successCount = 0;

if ($run) {
    foreach ($batches as $batch) {
        $payload = array(
            'customer_id' => (string)$batch['customer_id'],
            'issue_date' => date('d-m-Y', strtotime($batch['issue_date'])),
            'status' => 'PENDING',
            'reference_number' => (string)$batch['patient_id'],
            'description' => "Patient Type: {$batch['patient_type']}\n Indent By : {$batch['created_by']}\n Status Column: {$statusColumn}",
            'items' => $batch['items']
        );

        $api = array(
            'http_code' => 0,
            'curl_error' => '',
            'raw_response' => 'dry_run=1',
            'delivery_challan_id' => null,
            'delivery_challan_number' => null,
        );

        if (!$dryRun) {
            $api = sendRetailXDeliveryChallan($payload);
        }

        $updated = 0;
        if (!$dryRun && !empty($api['delivery_challan_id']) && !empty($api['delivery_challan_number'])) {
            $idCsv = implode(',', $batch['ids']);
            $challanIdSafe = mysqli_real_escape_string($con, $api['delivery_challan_id']);
            $challanNoSafe = mysqli_real_escape_string($con, $api['delivery_challan_number']);
            $updSql = "UPDATE `ip_medicine` SET `rx_so_id`='$challanIdSafe', `dc_no`='$challanNoSafe' WHERE `id` IN ($idCsv)";
            if (mysqli_query($con, $updSql)) {
                $updated = mysqli_affected_rows($con);
            }
        }

        if ($dryRun || $updated > 0) {
            $successCount++;
        }

        $results[] = array(
            'patient_id' => $batch['patient_id'],
            'customer_id' => $batch['customer_id'],
            'rows' => count($batch['ids']),
            'http_code' => $api['http_code'],
            'dc_id' => $api['delivery_challan_id'],
            'dc_no' => $api['delivery_challan_number'],
            'updated' => $updated,
            'curl_error' => $api['curl_error'],
            'raw_response' => $api['raw_response']
        );
    }
}

mysqli_close($con);
?>
<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>Retry Pending IP Medicine DC</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        .row {
            margin-bottom: 10px;
        }

        label {
            display: inline-block;
            width: 140px;
        }

        input[type="text"],
        input[type="number"] {
            padding: 5px;
            width: 240px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
            vertical-align: top;
        }

        th {
            background: #f4f4f4;
        }

        .muted {
            color: #666;
            font-size: 12px;
        }
    </style>
</head>

<body>
    <h2>Retry Pending IP Medicine DC</h2>

    <form method="get">
        <div class="row">
            <label>Patient ID</label>
            <input type="text" name="patient_id" value="<?= h($patientFilter) ?>" placeholder="Optional, e.g. IP000123">
        </div>
        <div class="row">
            <label>Limit Rows</label>
            <input type="number" name="limit" min="0" value="<?= h($limit) ?>" placeholder="0 = no limit">
        </div>
        <div class="row">
            <label>Dry Run</label>
            <input type="checkbox" name="dry_run" value="1" <?= $dryRun ? 'checked' : '' ?>>
            <span class="muted">Checked = no API update in db, only simulation</span>
        </div>
        <div class="row">
            <button type="submit" name="run" value="1">Run Retry</button>
        </div>
    </form>

    <div class="row muted">Detected pending status column: <?= h($statusColumn !== '' ? $statusColumn : 'not found; only dc_no filter used') ?></div>
    <div class="row muted">Matched pending rows: <?= h(count($rows)) ?> | Grouped API calls: <?= h(count($batches)) ?></div>

    <?php if ($run): ?>
        <h3>Execution Result</h3>
        <div class="row">Successful batches: <?= h($successCount) ?> / <?= h(count($batches)) ?></div>

        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Patient</th>
                    <th>Customer ID</th>
                    <th>Rows</th>
                    <th>HTTP</th>
                    <th>DC ID</th>
                    <th>DC No</th>
                    <th>Updated Rows</th>
                    <th>cURL Error</th>
                    <th>API Response</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($results)): ?>
                    <tr>
                        <td colspan="10">No pending records found.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($results as $i => $r): ?>
                        <tr>
                            <td><?= h($i + 1) ?></td>
                            <td><?= h($r['patient_id']) ?></td>
                            <td><?= h($r['customer_id']) ?></td>
                            <td><?= h($r['rows']) ?></td>
                            <td><?= h($r['http_code']) ?></td>
                            <td><?= h($r['dc_id']) ?></td>
                            <td><?= h($r['dc_no']) ?></td>
                            <td><?= h($r['updated']) ?></td>
                            <td><?= h($r['curl_error']) ?></td>
                            <td>
                                <pre style="white-space: pre-wrap; max-width: 450px;"><?= h($r['raw_response']) ?></pre>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <details style="margin-top: 20px;">
        <summary>Debug SQL</summary>
        <pre><?= h($debugSql) ?></pre>
    </details>
</body>

</html>
<?php
/**
 * Retail Webhook Receiver (single page API)
 * Endpoint for event callbacks like delivery-challan-delivered and bill-generated.
 */

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, X-Webhook-Token');

date_default_timezone_set('Asia/Kolkata');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    echo json_encode(array('success' => true, 'message' => 'Preflight OK'));
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    echo json_encode(array(
        'success' => true,
        'message' => 'Retail webhook endpoint is active',
        'endpoint' => 'POST /hospital/api/retail_webhook.php',
        'server_time' => date('Y-m-d H:i:s')
    ));
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(array('success' => false, 'message' => 'Only POST is allowed'));
    exit;
}

require_once(__DIR__ . '/../model/dbconnect.php');

function safeHeaders()
{
    if (function_exists('getallheaders')) {
        $h = getallheaders();
        return is_array($h) ? $h : array();
    }

    $headers = array();
    foreach ($_SERVER as $k => $v) {
        if (strpos($k, 'HTTP_') === 0) {
            $name = str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($k, 5)))));
            $headers[$name] = $v;
        }
    }
    return $headers;
}

function firstValue($payload, $paths, $default = '')
{
    foreach ($paths as $path) {
        $current = $payload;
        $parts = explode('.', $path);
        $found = true;

        foreach ($parts as $part) {
            if (!is_array($current) || !array_key_exists($part, $current)) {
                $found = false;
                break;
            }
            $current = $current[$part];
        }

        if ($found && $current !== null && trim((string)$current) !== '') {
            return (string)$current;
        }
    }

    return $default;
}

function parseIdList($value)
{
    $ids = array();

    if (is_array($value)) {
        foreach ($value as $id) {
            $id = (int)$id;
            if ($id > 0) {
                $ids[] = $id;
            }
        }
        return array_values(array_unique($ids));
    }

    $value = trim((string)$value);
    if ($value === '') {
        return $ids;
    }

    if (substr($value, 0, 1) === '[') {
        $decoded = json_decode($value, true);
        if (is_array($decoded)) {
            foreach ($decoded as $id) {
                $id = (int)$id;
                if ($id > 0) {
                    $ids[] = $id;
                }
            }
            return array_values(array_unique($ids));
        }
    }

    $parts = explode(',', $value);
    foreach ($parts as $id) {
        $id = (int)trim($id);
        if ($id > 0) {
            $ids[] = $id;
        }
    }

    return array_values(array_unique($ids));
}

function getIpMedicineIdsFromWebhookLog($con, $deliveryChallanId)
{
    $ids = array();
    $safeDeliveryChallanId = mysqli_real_escape_string($con, (string)$deliveryChallanId);
    if ($safeDeliveryChallanId === '') {
        return $ids;
    }

    $sql = "SELECT `ip_medicine_ids`
            FROM `api_webhook_events`
            WHERE `delivery_challan_id`='$safeDeliveryChallanId'
              AND `ip_medicine_ids` IS NOT NULL
              AND TRIM(`ip_medicine_ids`) <> ''
            ORDER BY `id` DESC
            LIMIT 1";
    $res = mysqli_query($con, $sql);
    if ($res && mysqli_num_rows($res) > 0) {
        $row = mysqli_fetch_assoc($res);
        if (isset($row['ip_medicine_ids'])) {
            $ids = parseIdList($row['ip_medicine_ids']);
        }
    }

    return $ids;
}

function ensureWebhookTable($con)
{
    $sql = "CREATE TABLE IF NOT EXISTS `api_webhook_events` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `event_type` varchar(100) DEFAULT NULL,
        `event_status` varchar(100) DEFAULT NULL,
        `delivery_challan_id` varchar(150) DEFAULT NULL,
        `delivery_challan_number` varchar(150) DEFAULT NULL,
        `return_number` varchar(150) DEFAULT NULL,
        `bill_id` varchar(150) DEFAULT NULL,
        `ip_medicine_ids` text,
        `source` varchar(150) DEFAULT NULL,
        `payload_json` longtext,
        `headers_json` longtext,
        `processing_result` longtext,
        `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (`id`),
        KEY `idx_event_type` (`event_type`),
        KEY `idx_delivery_challan_id` (`delivery_challan_id`),
        KEY `idx_bill_id` (`bill_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

    mysqli_query($con, $sql);

    // Backward-compatible schema upgrades for existing installations.
    if (!tableColumnExists($con, 'api_webhook_events', 'delivery_challan_id')) {
        mysqli_query($con, "ALTER TABLE `api_webhook_events` ADD COLUMN `delivery_challan_id` varchar(150) DEFAULT NULL");
        mysqli_query($con, "ALTER TABLE `api_webhook_events` ADD KEY `idx_delivery_challan_id` (`delivery_challan_id`)");
    }

    if (!tableColumnExists($con, 'api_webhook_events', 'delivery_challan_number')) {
        mysqli_query($con, "ALTER TABLE `api_webhook_events` ADD COLUMN `delivery_challan_number` varchar(150) DEFAULT NULL");
    }

    if (!tableColumnExists($con, 'api_webhook_events', 'return_number')) {
        mysqli_query($con, "ALTER TABLE `api_webhook_events` ADD COLUMN `return_number` varchar(150) DEFAULT NULL AFTER `delivery_challan_number`");
    }
}

function tableColumnExists($con, $table, $column)
{
    $table = mysqli_real_escape_string($con, $table);
    $column = mysqli_real_escape_string($con, $column);
    $chk = mysqli_query($con, "SHOW COLUMNS FROM `$table` LIKE '$column'");
    return ($chk && mysqli_num_rows($chk) > 0);
}

function ensureIpMedicineDescriptionColumn($con)
{
    $candidateColumns = array('description', 'discription', 'remarks', 'remark', 'notes', 'note');
    foreach ($candidateColumns as $column) {
        if (tableColumnExists($con, 'ip_medicine', $column)) {
            return $column;
        }
    }

    mysqli_query($con, "ALTER TABLE `ip_medicine` ADD COLUMN `description` varchar(255) DEFAULT NULL AFTER `dc_no`");

    if (tableColumnExists($con, 'ip_medicine', 'description')) {
        return 'description';
    }

    return '';
}

function ensureRoundAssessmentColumns($con)
{
    if (!tableColumnExists($con, 'ip_round_assessment', 'price')) {
        mysqli_query($con, "ALTER TABLE `ip_round_assessment` ADD COLUMN `price` decimal(10,2) NOT NULL DEFAULT '0.00' AFTER `qty`");
    }
    if (!tableColumnExists($con, 'ip_round_assessment', 'description')) {
        mysqli_query($con, "ALTER TABLE `ip_round_assessment` ADD COLUMN `description` varchar(255) DEFAULT NULL AFTER `price`");
    }
}

function getPatientIdsFromIpMedicineWhere($con, $ipMedWhere)
{
    $patientIds = array();
    if (trim((string)$ipMedWhere) === '') {
        return $patientIds;
    }

    $patRes = mysqli_query($con, "SELECT DISTINCT `patient_id` FROM `ip_medicine` WHERE $ipMedWhere");
    if ($patRes) {
        while ($patRow = mysqli_fetch_assoc($patRes)) {
            if (isset($patRow['patient_id']) && trim((string)$patRow['patient_id']) !== '') {
                $patientIds[] = trim((string)$patRow['patient_id']);
            }
        }
    }

    return array_values(array_unique($patientIds));
}

function getActiveIpdPatientIdsByCustomerId($con, $customerId)
{
    $patientIds = array();
    $safeCustomerId = mysqli_real_escape_string($con, trim((string)$customerId));
    if ($safeCustomerId === '') {
        return $patientIds;
    }

    $sql = "SELECT DISTINCT `ipd_no`
            FROM `central_ipd`
            WHERE `rx_id`='$safeCustomerId' AND `discharge`=0
            ORDER BY `id` DESC";
    $res = mysqli_query($con, $sql);
    if ($res) {
        while ($row = mysqli_fetch_assoc($res)) {
            if (isset($row['ipd_no']) && trim((string)$row['ipd_no']) !== '') {
                $patientIds[] = trim((string)$row['ipd_no']);
            }
        }
    }

    return array_values(array_unique($patientIds));
}

function insertRoundAssessmentRows($con, $patientIds, $serviceId, $price, $description, $createdBy)
{
    $inserted = 0;
    $safePrice = mysqli_real_escape_string($con, (string)round((float)$price));
    $safeDesc = mysqli_real_escape_string($con, (string)$description);
    $safeCreatedBy = mysqli_real_escape_string($con, (string)$createdBy);
    $serviceId = (int)$serviceId;

    foreach ($patientIds as $patientId) {
        $safePid = mysqli_real_escape_string($con, trim((string)$patientId));
        if ($safePid === '') {
            continue;
        }

        $dupSql = "SELECT `id` FROM `ip_round_assessment`
                   WHERE `patient_id`='$safePid'
                     AND `service_id`='$serviceId'
                     AND `description`='$safeDesc'
                     AND `status`='active'
                   ORDER BY `id` DESC
                   LIMIT 1";
        $dupRes = mysqli_query($con, $dupSql);
        if ($dupRes && mysqli_num_rows($dupRes) > 0) {
            continue;
        }

        $insSql = "INSERT INTO `ip_round_assessment`
            (`patient_id`,`round_datetime`,`service_id`,`doctor_id`,`qty`,`price`,`description`,`status`,`created_by`,`created_at`)
            VALUES
            ('$safePid',NOW(),$serviceId,0,'1.00','$safePrice','$safeDesc','active','$safeCreatedBy',NOW())";
        if (mysqli_query($con, $insSql)) {
            $inserted++;
        }
    }

    return $inserted;
}

$headers = safeHeaders();
$rawInput = file_get_contents('php://input');
$payload = json_decode($rawInput, true);

if (!is_array($payload)) {
    $payload = array();
}

if (empty($payload) && !empty($_POST)) {
    $payload = $_POST;
}

$expectedToken = getenv('RETAIL_WEBHOOK_TOKEN');
if ($expectedToken !== false && trim($expectedToken) !== '') {
    $incomingToken = '';
    if (isset($headers['X-Webhook-Token'])) {
        $incomingToken = trim((string)$headers['X-Webhook-Token']);
    } elseif (isset($payload['token'])) {
        $incomingToken = trim((string)$payload['token']);
    } elseif (isset($_REQUEST['token'])) {
        $incomingToken = trim((string)$_REQUEST['token']);
    }

    if ($incomingToken !== trim((string)$expectedToken)) {
        http_response_code(401);
        echo json_encode(array('success' => false, 'message' => 'Unauthorized webhook token'));
        exit;
    }
}

$eventType = strtolower(firstValue($payload, array(
    'event_type', 'event', 'type', 'task', 'action',
    'data.event_type', 'data.event', 'data.type'
), 'unknown'));

$eventStatus = strtolower(firstValue($payload, array(
    'event_status', 'status', 'state',
    'data.event_status', 'data.status', 'data.state'
), ''));

if ($eventStatus === '') {
    if (strpos($eventType, 'return') !== false) {
        $eventStatus = 'returned';
    } elseif (strpos($eventType, 'delivered') !== false) {
        $eventStatus = 'delivered';
    }
}

$deliveryChallanId = firstValue($payload, array(
    'delivery_challan_id',
    'retailer_delivery_challan_id',
    'challan_id',
    'data.retailer_delivery_challan_id',
    'data.delivery_challan_id',
    'data.challan_id',
    'data.retailer_delivery_challan.id'
), '');

$billId = firstValue($payload, array(
    'bill_id',
    'invoice_id',
    'billing_id',
    'data.bill_id',
    'data.invoice_id',
    'data.billing_id'
), '');

$deliveryChallanNo = firstValue($payload, array(
    'delivery_challan_number',
    'dc_no',
    'data.retailer_delivery_challan.delivery_challan_number',
    'data.delivery_challan_number',
    'data.dc_no',
    'return_number',
    'data.return_number'
), '');

$returnNumber = firstValue($payload, array(
    'return_number',
    'return_no',
    'delivery_challan_return_number',
    'data.return_number',
    'data.return_no',
    'data.delivery_challan_return_number',
    'retailer_delivery_challan_return.return_number',
    'data.retailer_delivery_challan_return.return_number',
    'delivery_challan_return.return_number',
    'data.delivery_challan_return.return_number'
), '');

if ($returnNumber === '' && (strpos($eventType, 'return') !== false || $eventStatus === 'returned')) {
    $returnNumber = $deliveryChallanNo;
}

$webhookTotalAmount = firstValue($payload, array(
    'total_return_amount',
    'sub_total_return_amount',
    'total_amount',
    'data.total_return_amount',
    'data.sub_total_return_amount',
    'data.total_amount',
    'amount',
    'data.amount'
), '0');

$customerId = firstValue($payload, array(
    'customer_id',
    'retailer_customer_id',
    'patient_customer_id',
    'data.customer_id',
    'data.retailer_customer_id',
    'data.patient_customer_id',
    'data.customer.id',
    'data.retailer_customer.id'
), '');

if ($customerId === '' && isset($_REQUEST['customer_id'])) {
    $customerId = trim((string)$_REQUEST['customer_id']);
}

$ipMedicineIds = array();
if (isset($payload['ip_medicine_ids'])) {
    $ipMedicineIds = parseIdList($payload['ip_medicine_ids']);
} elseif (isset($payload['medicine_ids'])) {
    $ipMedicineIds = parseIdList($payload['medicine_ids']);
} elseif (isset($payload['local_ids'])) {
    $ipMedicineIds = parseIdList($payload['local_ids']);
} elseif (isset($_REQUEST['ip_medicine_ids'])) {
    $ipMedicineIds = parseIdList($_REQUEST['ip_medicine_ids']);
}

$source = isset($headers['User-Agent']) ? (string)$headers['User-Agent'] : 'unknown';

$response = array(
    'success' => true,
    'message' => 'Webhook received',
    'event_type' => $eventType,
    'event_status' => $eventStatus,
    'delivery_challan_id' => $deliveryChallanId,
    'delivery_challan_number' => $deliveryChallanNo,
    'return_number' => $returnNumber,
    'bill_id' => $billId,
    'customer_id' => $customerId,
    'ip_medicine_ids' => $ipMedicineIds,
    'updates' => array()
);

try {
    $login = new userlogin();
    $con = $login->dbconnect1();

    ensureWebhookTable($con);

    if ($deliveryChallanId !== '') {
        $safeDeliveryChallanId = mysqli_real_escape_string($con, $deliveryChallanId);

        if (!empty($ipMedicineIds)) {
            $idCsv = implode(',', $ipMedicineIds);
            $sql = "UPDATE `ip_medicine` SET `rx_so_id`='$safeDeliveryChallanId' WHERE `id` IN ($idCsv)";
            $ok = mysqli_query($con, $sql);
            $response['updates']['delivery_challan_linked_by_ids'] = $ok ? mysqli_affected_rows($con) : 0;
        }
    }

    $isDeliveredEvent = (
    (
        strpos($eventType, 'retailer.delivery_challan.delivered') !== false ||
        strpos($eventType, 'delivery_challan.delivered') !== false ||
        strpos($eventType, 'delivery_challan_delivered') !== false ||
        strpos($eventType, 'delivery_challan') !== false
    )
    && strtolower(trim($eventStatus)) === 'delivered'
);

    if ($isDeliveredEvent) {
        $statusColumn = '';
        if (tableColumnExists($con, 'ip_medicine', 'delivery_status')) {
            $statusColumn = 'delivery_status';
        } elseif (tableColumnExists($con, 'ip_medicine', 'status')) {
            $statusColumn = 'status';
        } elseif (tableColumnExists($con, 'ip_medicine', 'rx_status')) {
            $statusColumn = 'rx_status';
        }

        // Build the WHERE clause used both for UPDATE and for fetching patient_ids.
        $ipMedWhere = '';
        if (!empty($ipMedicineIds)) {
            $idCsv = implode(',', $ipMedicineIds);
            $ipMedWhere = "`id` IN ($idCsv)";
        } elseif ($deliveryChallanId !== '') {
            $safeDeliveryChallanId = mysqli_real_escape_string($con, $deliveryChallanId);
            $ipMedWhere = "`rx_so_id`='$safeDeliveryChallanId'";
        } elseif ($deliveryChallanNo !== '' && tableColumnExists($con, 'ip_medicine', 'dc_no')) {
            $safeDcNo = mysqli_real_escape_string($con, $deliveryChallanNo);
            $ipMedWhere = "`dc_no`='$safeDcNo'";
        }

        if ($statusColumn !== '' && $ipMedWhere !== '') {
            $safeDelivered = mysqli_real_escape_string($con, 'DELIVERED');
            $sql = "UPDATE `ip_medicine` SET `$statusColumn`='$safeDelivered' WHERE $ipMedWhere";
            $ok = mysqli_query($con, $sql);
            $response['updates']['delivery_status_updated'] = $ok ? mysqli_affected_rows($con) : 0;
        } elseif ($ipMedWhere === '') {
            $response['updates']['delivery_note'] = 'No ip_medicine_ids, delivery_challan_id, or dc_no to match rows for delivered status update';
        } else {
            $response['updates']['delivery_note'] = 'No status column found in ip_medicine (checked: delivery_status, status, rx_status)';
        }

        $patientIds = getPatientIdsFromIpMedicineWhere($con, $ipMedWhere);
        if (!empty($patientIds)) {
            $response['updates']['delivery_patient_source'] = 'ip_medicine';
            $response['updates']['delivery_patient_count'] = count($patientIds);
        } 

        if (empty($patientIds) && $customerId !== '') {
            $fallbackPatientIds = getActiveIpdPatientIdsByCustomerId($con, $customerId);
            if (count($fallbackPatientIds) === 1) {
                $patientIds = $fallbackPatientIds;
                $response['updates']['delivery_patient_source'] = 'central_ipd_rx_id';
                $response['updates']['delivery_patient_count'] = 1;
            } elseif (count($fallbackPatientIds) > 1) {
                $response['updates']['delivery_round_assessment_note'] = 'Multiple active IPD admissions found for customer_id; round assessment not inserted';
            } else {
                $response['updates']['delivery_round_assessment_note'] = 'No active IPD admission found for customer_id fallback';
            }
        }

        if (!empty($patientIds)) {
            ensureRoundAssessmentColumns($con);
            $roundInserted = insertRoundAssessmentRows(
                $con,
                $patientIds,
                1399,
                $webhookTotalAmount,
                '(' . $deliveryChallanNo . ')',
                'webhook'
            );
            $response['updates']['round_assessment_inserted'] = $roundInserted;
        } elseif (!isset($response['updates']['delivery_round_assessment_note'])) {
            $response['updates']['delivery_round_assessment_note'] = 'No patient found for delivered round assessment insert';
        }
    }

    $isReturnEvent = (
        strpos($eventType, 'delivery_challan.return') !== false ||
        strpos($eventType, 'delivery_challan.returned') !== false ||
        strpos($eventType, 'delivery_challan_return') !== false ||
        strpos($eventType, 'retailer.delivery_challan.return') !== false ||
        strpos($eventType, 'retailer.delivery_challan.returned') !== false ||
        (strpos($eventType, 'delivery_challan') !== false && $eventStatus === 'returned') ||
        $eventStatus === 'returned'
    );

    if ($isReturnEvent) {
        // Build the WHERE clause only for finding impacted patient_ids.
        $ipMedWhere = '';
        if (!empty($ipMedicineIds)) {
            $idCsv = implode(',', $ipMedicineIds);
            $ipMedWhere = "`id` IN ($idCsv)";
        } elseif ($deliveryChallanId !== '') {
            $safeDeliveryChallanId = mysqli_real_escape_string($con, $deliveryChallanId);
            $ipMedWhere = "`rx_so_id`='$safeDeliveryChallanId'";
        } elseif ($deliveryChallanNo !== '' && tableColumnExists($con, 'ip_medicine', 'dc_no')) {
            $safeDcNo = mysqli_real_escape_string($con, $deliveryChallanNo);
            $ipMedWhere = "`dc_no`='$safeDcNo'";
        }

        // Fallback: recover ip_medicine_ids from previously logged webhook events of the same challan.
        if ($ipMedWhere === '' && $deliveryChallanId !== '') {
            $recoveredIds = getIpMedicineIdsFromWebhookLog($con, $deliveryChallanId);
            if (!empty($recoveredIds)) {
                $idCsv = implode(',', $recoveredIds);
                $ipMedWhere = "`id` IN ($idCsv)";
                $ipMedicineIds = $recoveredIds;
                $response['updates']['return_ip_medicine_ids_recovered'] = count($recoveredIds);
            }
        }

        $patientIds = getPatientIdsFromIpMedicineWhere($con, $ipMedWhere);
        if (!empty($patientIds)) {
            $response['updates']['return_patient_source'] = 'ip_medicine';
            $response['updates']['return_patient_count'] = count($patientIds);
        }

        if (empty($patientIds) && $customerId !== '') {
            $fallbackPatientIds = getActiveIpdPatientIdsByCustomerId($con, $customerId);
            if (count($fallbackPatientIds) === 1) {
                $patientIds = $fallbackPatientIds;
                $response['updates']['return_patient_source'] = 'central_ipd_rx_id';
                $response['updates']['return_patient_count'] = 1;
            } elseif (count($fallbackPatientIds) > 1) {
                $response['updates']['round_assessment_return_note'] = 'Multiple active IPD admissions found for customer_id; return round assessment not inserted';
            } else {
                $response['updates']['round_assessment_return_note'] = 'No active IPD admission found for customer_id fallback';
            }
        }

        if ($ipMedWhere !== '') {
            $returnReferenceNo = $returnNumber !== '' ? $returnNumber : $deliveryChallanNo;
            $descriptionColumn = ensureIpMedicineDescriptionColumn($con);
            if ($descriptionColumn !== '') {
                $returnText = $returnReferenceNo !== '' ? '(' . $returnReferenceNo . ')' : '(RETURN)';
                $safeReturnText = mysqli_real_escape_string($con, $returnText);
                $descSql = "UPDATE `ip_medicine`
                            SET `$descriptionColumn` = CASE
                                WHEN `$descriptionColumn` IS NULL OR TRIM(`$descriptionColumn`) = '' THEN '$safeReturnText'
                                WHEN `$descriptionColumn` LIKE '%$safeReturnText%' THEN `$descriptionColumn`
                                ELSE CONCAT(TRIM(`$descriptionColumn`), ' ', '$safeReturnText')
                            END
                            WHERE $ipMedWhere";
                $ok = mysqli_query($con, $descSql);
                $response['updates']['ip_medicine_description_updated'] = $ok ? mysqli_affected_rows($con) : 0;
            } else {
                $response['updates']['ip_medicine_description_note'] = 'No description column available in ip_medicine';
            }

        }

        if (!empty($patientIds)) {
            ensureRoundAssessmentColumns($con);

            // Always persist return values as negative charges.
            $returnAmount = -1 * abs((float)$webhookTotalAmount);
            $returnReferenceNo = $returnNumber !== '' ? $returnNumber : $deliveryChallanNo;
            $roundReturnInserted = insertRoundAssessmentRows(
                $con,
                $patientIds,
                2604,
                $returnAmount,
                $returnReferenceNo !== '' ? '(' . $returnReferenceNo . ')' : '(RETURN)',
                'webhook'
            );
            $response['updates']['round_assessment_return_inserted'] = $roundReturnInserted;
        } elseif (!isset($response['updates']['round_assessment_return_note'])) {
            $response['updates']['round_assessment_return_note'] = 'No ip_medicine_ids, delivery_challan_id, or dc_no to match rows for return update';
        }
    }

    $isBillEvent = (
        strpos($eventType, 'bill') !== false ||
        strpos($eventType, 'invoice') !== false ||
        $eventStatus === 'billed' ||
        $eventStatus === 'invoiced'
    );

    if ($isBillEvent && $billId !== '') {
        $safeBillId = mysqli_real_escape_string($con, $billId);
        $safeDeliveryChallanId = mysqli_real_escape_string($con, $deliveryChallanId);

        if (tableColumnExists($con, 'ip_medicine', 'rx_bill_id')) {
            if (!empty($ipMedicineIds)) {
                $idCsv = implode(',', $ipMedicineIds);
                $sql = "UPDATE `ip_medicine` SET `rx_bill_id`='$safeBillId' WHERE `id` IN ($idCsv)";
                $ok = mysqli_query($con, $sql);
                $response['updates']['bill_linked_by_ids'] = $ok ? mysqli_affected_rows($con) : 0;
            } elseif ($deliveryChallanId !== '') {
                $sql = "UPDATE `ip_medicine` SET `rx_bill_id`='$safeBillId' WHERE `rx_so_id`='$safeDeliveryChallanId'";
                $ok = mysqli_query($con, $sql);
                $response['updates']['bill_linked_by_delivery_challan'] = $ok ? mysqli_affected_rows($con) : 0;
            }
        } else {
            $response['updates']['bill_note'] = 'Column ip_medicine.rx_bill_id not found; event logged only';
        }
    }

    $safeEventType = mysqli_real_escape_string($con, $eventType);
    $safeEventStatus = mysqli_real_escape_string($con, $eventStatus);
    $safeDeliveryChallanId = mysqli_real_escape_string($con, $deliveryChallanId);
    $safeDeliveryChallanNo = mysqli_real_escape_string($con, $deliveryChallanNo);
    $safeReturnNumber = mysqli_real_escape_string($con, $returnNumber);
    $safeBillId = mysqli_real_escape_string($con, $billId);
    $safeIpIds = mysqli_real_escape_string($con, json_encode($ipMedicineIds));
    $safeSource = mysqli_real_escape_string($con, $source);
    $safePayload = mysqli_real_escape_string($con, json_encode($payload, JSON_UNESCAPED_SLASHES));
    $safeHeaders = mysqli_real_escape_string($con, json_encode($headers, JSON_UNESCAPED_SLASHES));
    $safeProcessing = mysqli_real_escape_string($con, json_encode($response['updates'], JSON_UNESCAPED_SLASHES));

    $logSql = "INSERT INTO `api_webhook_events`
        (`event_type`,`event_status`,`delivery_challan_id`,`delivery_challan_number`,`return_number`,`bill_id`,`ip_medicine_ids`,`source`,`payload_json`,`headers_json`,`processing_result`,`created_at`)
        VALUES
        ('$safeEventType','$safeEventStatus','$safeDeliveryChallanId','$safeDeliveryChallanNo','$safeReturnNumber','$safeBillId','$safeIpIds','$safeSource','$safePayload','$safeHeaders','$safeProcessing',NOW())";
    mysqli_query($con, $logSql);

    $response['event_log_id'] = mysqli_insert_id($con);

    $con->close();
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(array(
        'success' => false,
        'message' => 'Webhook processing failed',
        'error' => $e->getMessage()
    ));
    exit;
}

echo json_encode($response, JSON_PRETTY_PRINT);
exit;
?>
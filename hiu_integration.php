<?php
// Milestone 3 (HIU) action handlers. The HIMS UI (fetchHealthRecords.php) posts
// here; each action proxies to the Node gateway's /api/milestone3/* endpoints
// with an ABDM Bearer token + X-API-Key. The gateway handles ABDM, key material,
// persistence, decryption; PHP is a thin proxy. Mirrors abha_integration.php.
// session_config.php forces the project session save path and starts the session.
// It must run before anything reads $_SESSION — the gateway API key lives there.
require_once __DIR__ . '/model/session_config.php';
header("Content-Type: application/json");
require_once __DIR__ . '/config/abdm_config.php';
require_once __DIR__ . '/model/dbconnect.php';

$input = $_POST;
if (empty($input)) {
    $raw = file_get_contents("php://input");
    $decoded = json_decode($raw, true);
    $input = is_array($decoded) ? $decoded : [];
}

// Defensive: these are seeded in index.php at login; re-seed from config so this
// endpoint works even if the browser deep-linked without passing through index.
$baseurl = $_SESSION['base_url'] ?? ABDM_BASE_URL;
$apiKey  = $_SESSION['abha_api'] ?? '';
if ($apiKey === '') {
    exit(json_encode(['success' => false, 'error' => 'Not authenticated (missing gateway API key). Please re-login.']));
}

$db  = new userlogin();
$con = $db->dbconnect();

$page = isset($input['page']) ? preg_replace('/[^a-zA-Z0-9_]/', '', $input['page']) : '';

function isoTimestamp() {
    return gmdate("Y-m-d\TH:i:s.000\Z");
}

function generateUuidV4() {
    return sprintf(
        '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        mt_rand(0, 0xffff), mt_rand(0, 0xffff),
        mt_rand(0, 0xffff),
        mt_rand(0, 0x0fff) | 0x4000,
        mt_rand(0, 0x3fff) | 0x8000,
        mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
    );
}

// Normalise a datetime-local / date string to ISO-8601 UTC (ABDM requirement).
function toIso($v) {
    $v = trim((string) $v);
    if ($v === '') return '';
    $ts = strtotime($v);
    return $ts ? gmdate("Y-m-d\TH:i:s.000\Z", $ts) : '';
}

// Resolve the consent "requester" (ordering doctor) from the logged-in user.
function resolveRequester(mysqli $con): array {
    $name = trim((string) ($_SESSION['name'] ?? ''));
    $reg  = ABDM_REQUESTER_DEFAULT_REGNO;
    if ($name !== '') {
        $safe = mysqli_real_escape_string($con, $name);
        $q = mysqli_query(
            $con,
            "SELECT registration_no, TRIM(CONCAT(name,' ',COALESCE(last_name,''))) AS full
             FROM doctor
             WHERE TRIM(CONCAT(name,' ',COALESCE(last_name,'')))='$safe' OR name='$safe'
             ORDER BY id DESC LIMIT 1"
        );
        $row = $q ? mysqli_fetch_assoc($q) : null;
        if ($row) {
            if (!empty($row['registration_no'])) $reg = $row['registration_no'];
            if (!empty($row['full'])) $name = $row['full'];
        }
    }
    if ($name === '') $name = 'HIU Requester';
    return ['name' => $name, 'reg' => (string) $reg];
}

// Call a Node gateway endpoint. Returns [rawResponse, httpCode, curlError].
function nodeCall(string $method, string $path, string $accessToken, ?array $body, array $extraHeaders = []): array {
    global $baseurl, $apiKey;
    $verify = defined('ABDM_VERIFY_SSL') ? ABDM_VERIFY_SSL : true;

    $headers = [
        "X-API-Key: $apiKey",
        'Content-Type: application/json',
        'X-CM-ID: ' . ABDM_CM_ID,
    ];
    if ($accessToken !== '') $headers[] = "Authorization: Bearer $accessToken";
    foreach ($extraHeaders as $h) $headers[] = $h;

    $ch = curl_init();
    $opts = [
        CURLOPT_URL            => rtrim($baseurl, '/') . $path,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT        => 45,
        CURLOPT_SSL_VERIFYPEER => $verify,
        CURLOPT_SSL_VERIFYHOST => $verify ? 2 : 0,
        CURLOPT_HTTPHEADER     => $headers,
    ];
    $m = strtoupper($method);
    if ($m === 'GET') {
        $opts[CURLOPT_HTTPGET] = true;
    } elseif ($m === 'POST') {
        $opts[CURLOPT_POST] = true;
        $opts[CURLOPT_POSTFIELDS] = json_encode($body ?? []);
    } else {
        $opts[CURLOPT_CUSTOMREQUEST] = $m;
        if ($body !== null) $opts[CURLOPT_POSTFIELDS] = json_encode($body);
    }
    curl_setopt_array($ch, $opts);
    $resp = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $err  = curl_error($ch);
    curl_close($ch);
    return [$resp, $code, $err];
}

// ── hiu_consent_init ────────────────────────────────────────────────────────
// Raise a consent request. The REQUEST-ID we send correlates the async on-init
// callback, so we return it to the browser to poll status by.
if ($page === 'hiu_consent_init') {
    $accessToken = $input['accessToken'] ?? '';
    $abhaAddress = trim($input['abhaAddress'] ?? '');
    $hiTypes     = $input['hiTypes'] ?? [];

    if (ABDM_HIU_ID === '') {
        exit(json_encode(['success' => false, 'error' => 'ABDM_HIU_ID is not configured on the server.']));
    }
    if ($abhaAddress === '') {
        exit(json_encode(['success' => false, 'error' => 'Patient ABHA address is required.']));
    }
    if (!is_array($hiTypes) || count($hiTypes) === 0) {
        exit(json_encode(['success' => false, 'error' => 'Select at least one HI type.']));
    }

    $from  = toIso($input['from'] ?? '');
    $to    = toIso($input['to'] ?? '');
    $erase = toIso($input['dataEraseAt'] ?? '');
    if ($from === '' || $to === '' || $erase === '') {
        exit(json_encode(['success' => false, 'error' => 'From / To / Data-erase dates are required.']));
    }

    $req         = resolveRequester($con);
    $purposeCode = preg_replace('/[^A-Z]/', '', strtoupper($input['purposeCode'] ?? 'CAREMGT')) ?: 'CAREMGT';
    $purposeText = trim($input['purposeText'] ?? 'Care Management') ?: 'Care Management';
    $requestId   = generateUuidV4();

    // Display-only patient identity for the consent-history list (M3-004); the
    // gateway persists these but does NOT forward them to ABDM.
    $patientName       = trim((string) ($input['patientName'] ?? ''));
    $patientAbhaNumber = trim((string) ($input['patientAbhaNumber'] ?? ''));

    // Which HIP's records this consent covers.
    //
    // ABDM treats a null `consent.hip` as "every HIP this patient has linked", so
    // a fetch would return records from other facilities too. Scope the request to
    // this facility's HIP id, so only records this hospital created come back.
    // The caller may name a different HIP explicitly, and
    // ABDM_HIU_FETCH_ALL_HIPS=on in .env restores the unscoped behaviour.
    $hipScope = trim((string) ($input['hipId'] ?? ''));
    if ($hipScope === '' && !ABDM_HIU_FETCH_ALL_HIPS) {
        $hipScope = (string) ABDM_HIP_ID;
    }

    $body = [
        'patientName'       => $patientName,
        'patientAbhaNumber' => $patientAbhaNumber,
        'consent' => [
            'purpose' => [
                'text'   => $purposeText,
                'code'   => $purposeCode,
                'refUri' => 'http://terminology.hl7.org/ValueSet/v3-PurposeOfUse',
            ],
            'patient'      => ['id' => $abhaAddress],
            'hiu'          => ['id' => ABDM_HIU_ID],
            'hip'          => ($hipScope !== '') ? ['id' => $hipScope] : null,
            'careContexts' => null,
            'requester'    => [
                'name'       => $req['name'],
                'identifier' => [
                    'type'   => 'REGNO',
                    'value'  => $req['reg'] !== '' ? $req['reg'] : 'NA',
                    'system' => ABDM_REQUESTER_SYSTEM,
                ],
            ],
            'hiTypes'    => array_values($hiTypes),
            'permission' => [
                'accessMode' => 'VIEW',
                'dateRange'  => ['from' => $from, 'to' => $to],
                'dataEraseAt' => $erase,
                'frequency'  => ['unit' => 'HOUR', 'value' => 1, 'repeats' => 0],
            ],
        ],
    ];

    [$resp, $code, $err] = nodeCall(
        'POST',
        '/api/milestone3/consent/request/init',
        $accessToken,
        $body,
        ['REQUEST-ID: ' . $requestId, 'TIMESTAMP: ' . isoTimestamp(), 'X-HIU-ID: ' . ABDM_HIU_ID]
    );
    $data = json_decode($resp, true);
    echo json_encode([
        'success'   => $code >= 200 && $code < 300,
        'httpCode'  => $code,
        'requestId' => $requestId,
        // '' means the consent was left open to every HIP the patient has linked.
        'hipScope'  => $hipScope,
        'requester' => $req['name'],
        'node'      => $data,
        'error'     => $err ?: ($data['error'] ?? ($data['errors'] ?? null)),
    ]);
    exit;

// ── hiu_consent_status ──────────────────────────────────────────────────────
// Poll the gateway's stored state (webhook-driven) by our requestId. Returns the
// status + any granted artefacts (consentId(s)) once the patient approves.
} elseif ($page === 'hiu_consent_status') {
    $accessToken = $input['accessToken'] ?? '';
    $id = trim($input['consentRequestId'] ?? ($input['requestId'] ?? ''));
    if ($id === '') {
        exit(json_encode(['success' => false, 'error' => 'requestId is required.']));
    }
    [$resp, $code, $err] = nodeCall(
        'GET',
        '/api/milestone3/consent-requests/' . rawurlencode($id),
        $accessToken,
        null,
        ['X-HIU-ID: ' . ABDM_HIU_ID]
    );
    $data      = json_decode($resp, true);
    $request   = $data['data']['request'] ?? null;
    $artefacts = $data['data']['artefacts'] ?? [];
    echo json_encode([
        'success'          => $code >= 200 && $code < 300,
        'status'           => $request['status'] ?? 'UNKNOWN',
        'consentRequestId' => $request['consentRequestId'] ?? null,
        'artefacts'        => $artefacts,
        'node'             => $data,
        'error'            => $err ?: null,
    ]);
    exit;

// ── hiu_fetch_consent ───────────────────────────────────────────────────────
// Ask ABDM for a granted artefact's full detail. The grant notification often
// carries only the artefact id, so until this runs the stored artefact has no
// `permission` — and the patient's ACTUAL granted terms (date range, expiry,
// HI types, care contexts) are unknown. ABDM answers asynchronously on the
// on-fetch webhook, so the caller should re-read the list a moment later.
} elseif ($page === 'hiu_fetch_consent') {
    $accessToken = $input['accessToken'] ?? '';
    $consentId   = trim($input['consentId'] ?? '');
    if ($consentId === '') {
        exit(json_encode(['success' => false, 'error' => 'consentId is required.']));
    }
    [$resp, $code, $err] = nodeCall(
        'POST',
        '/api/milestone3/consent/fetch',
        $accessToken,
        ['consentId' => $consentId],
        ['REQUEST-ID: ' . generateUuidV4(), 'TIMESTAMP: ' . isoTimestamp(), 'X-HIU-ID: ' . ABDM_HIU_ID]
    );
    $data = json_decode($resp, true);
    echo json_encode(['success' => $code >= 200 && $code < 300, 'node' => $data, 'error' => $err ?: null]);
    exit;

// ── hiu_hi_request ──────────────────────────────────────────────────────────
// Request the actual data. Minimal body → the gateway generates the ECDH key
// material + dataPushUrl and sends the HI request to ABDM.
} elseif ($page === 'hiu_hi_request') {
    $accessToken = $input['accessToken'] ?? '';
    $consentId   = trim($input['consentId'] ?? '');
    if ($consentId === '') {
        exit(json_encode(['success' => false, 'error' => 'consentId is required.']));
    }
    $from = toIso($input['from'] ?? '');
    $to   = toIso($input['to'] ?? '');
    $body = ['consentId' => $consentId];
    if ($from !== '' && $to !== '') {
        $body['dateRange'] = ['from' => $from, 'to' => $to];
    }
    [$resp, $code, $err] = nodeCall(
        'POST',
        '/api/milestone3/health-information/request',
        $accessToken,
        $body,
        ['REQUEST-ID: ' . generateUuidV4(), 'TIMESTAMP: ' . isoTimestamp(), 'X-HIU-ID: ' . ABDM_HIU_ID]
    );
    $data = json_decode($resp, true);
    echo json_encode([
        'success'   => $code >= 200 && $code < 300,
        'httpCode'  => $code,
        'consentId' => $consentId,
        'node'      => $data,
        'error'     => $err ?: ($data['error'] ?? ($data['errors'] ?? null)),
    ]);
    exit;

// ── hiu_get_data ────────────────────────────────────────────────────────────
// Poll for the decrypted bundles (view-only). Keyed by consentId (the UI has it)
// or transactionId.
} elseif ($page === 'hiu_get_data') {
    $accessToken   = $input['accessToken'] ?? '';
    $consentId     = trim($input['consentId'] ?? '');
    $transactionId = trim($input['transactionId'] ?? '');
    if ($consentId === '' && $transactionId === '') {
        exit(json_encode(['success' => false, 'error' => 'consentId or transactionId is required.']));
    }
    $q = $transactionId !== ''
        ? 'transactionId=' . rawurlencode($transactionId)
        : 'consentId=' . rawurlencode($consentId);
    [$resp, $code, $err] = nodeCall(
        'GET',
        '/api/milestone3/health-data?' . $q,
        $accessToken,
        null,
        ['X-HIU-ID: ' . ABDM_HIU_ID]
    );
    $data = json_decode($resp, true);
    echo json_encode([
        'success' => $code >= 200 && $code < 300,
        'ready'   => $data['ready'] ?? false,
        'count'   => $data['count'] ?? 0,
        'entries' => $data['entries'] ?? [],
        'error'   => $err ?: null,
    ]);
    exit;

// ── hiu_stored_records ──────────────────────────────────────────────────────
// Previously-fetched (cached) records for a patient — reuse them until the
// consent expires instead of re-running the full consent+fetch flow.
} elseif ($page === 'hiu_stored_records') {
    $accessToken = $input['accessToken'] ?? '';
    $abha        = trim($input['abhaAddress'] ?? '');
    if ($abha === '') {
        exit(json_encode(['success' => false, 'error' => 'abhaAddress is required.']));
    }
    [$resp, $code, $err] = nodeCall(
        'GET',
        '/api/milestone3/health-data/by-patient?abha=' . rawurlencode($abha),
        $accessToken,
        null,
        ['X-HIU-ID: ' . ABDM_HIU_ID]
    );
    $data = json_decode($resp, true) ?: [];
    $data['success'] = $code >= 200 && $code < 300;
    if ($err) $data['error'] = $err;
    echo json_encode($data);
    exit;

// ── hiu_consent_list (M3-004: consent request history) ──────────────────────
} elseif ($page === 'hiu_consent_list') {
    $accessToken = $input['accessToken'] ?? '';
    $abha        = trim($input['abhaAddress'] ?? '');
    $qs = 'limit=100';
    if ($abha !== '') $qs .= '&patient=' . rawurlencode($abha);
    [$resp, $code, $err] = nodeCall(
        'GET',
        '/api/milestone3/consent-requests?' . $qs,
        $accessToken,
        null,
        ['X-HIU-ID: ' . ABDM_HIU_ID]
    );
    $data = json_decode($resp, true) ?: [];
    echo json_encode([
        'success' => $code >= 200 && $code < 300,
        'data'    => $data['data'] ?? [],
        'error'   => $err ?: null,
    ]);
    exit;

// ── hiu_consent_artefact ────────────────────────────────────────────────────
// Read a granted consent artefact so the UI can show what it actually covers
// (care-context count + source HIP). An artefact with zero care-contexts means
// there is nothing for any facility to push — the "no data" explanation.
} elseif ($page === 'hiu_consent_artefact') {
    $accessToken = $input['accessToken'] ?? '';
    $consentId   = trim($input['consentId'] ?? '');
    if ($consentId === '') {
        exit(json_encode(['success' => false, 'error' => 'consentId is required.']));
    }
    [$resp, $code, $err] = nodeCall(
        'GET',
        '/api/milestone3/consents/' . rawurlencode($consentId),
        $accessToken,
        null,
        ['X-HIU-ID: ' . ABDM_HIU_ID]
    );
    $data = json_decode($resp, true) ?: [];
    $art  = $data['data'] ?? null;
    $cc   = (is_array($art) && isset($art['careContexts']) && is_array($art['careContexts']))
        ? count($art['careContexts']) : 0;
    echo json_encode([
        'success'      => $code >= 200 && $code < 300,
        'careContexts' => $cc,
        'hipId'        => is_array($art) ? ($art['hipId'] ?? null) : null,
        'fetched'      => is_array($art) ? ($art['fetched'] ?? null) : null,
        'status'       => is_array($art) ? ($art['status'] ?? null) : null,
        'error'        => $err ?: null,
    ]);
    exit;

} else {
    echo json_encode(['success' => false, 'error' => 'Unknown action: ' . $page]);
    exit;
}

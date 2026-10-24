<?php
// session_config.php forces the project session save path and starts the session.
// It has to run before anything reads $_SESSION — the ABDM credentials this file
// needs (base_url, clientid, abha_api) all live there.
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

function esc($str) {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

/**
 * Whether a care-context group has already been linked to ABDM.
 * The group referenceNumber encodes the source: the id is the segment after the
 * last '/', and the prefix selects the table that carries `abha_linked`.
 *   INV/{billId}        → billing.id
 *   DIAGRPT/{sampleId}  → sample_status.id
 *   HDR-DIAG/{sampleId} → sample_status.id
 *   DIS-…/{patientId}, HDR-DIS/, IPPRES/, IPWELL/ → central_ipd.ipd_no
 *   OPCON-/WELL-/PRES-/IMMUNI-/HDR-…/{opdId} → central_opd.id
 */
function ccGroupLinked(mysqli $con, string $ref, string $hiType, array $careContexts = []): bool
{
    // Uploaded documents: one group holds every document of the IP patient, so
    // it is linked only when each document in it is.
    if (ccIsDocumentGroup($ref, $hiType)) {
        $ids = ccDocumentIds($ref, $careContexts);
        if (!$ids) return false;
        $q   = mysqli_query($con, "SELECT COUNT(*) AS n FROM `patient_documents` WHERE `id` IN (" . implode(',', $ids) . ") AND `abha_linked`=1");
        $row = $q ? mysqli_fetch_assoc($q) : null;
        return $row && (int) $row['n'] === count($ids);
    }

    $target = ccGroupTarget($ref, $hiType);
    if (!$target) return false;
    [$tbl, $col, $id] = $target;
    $idEsc = mysqli_real_escape_string($con, $id);

    $q   = mysqli_query($con, "SELECT abha_linked FROM `$tbl` WHERE `$col`='$idEsc' LIMIT 1");
    $row = $q ? mysqli_fetch_assoc($q) : null;
    return $row && !empty($row['abha_linked']);
}

/** [table, column, id] holding the `abha_linked` flag for a group, or null. See ccGroupLinked(). */
function ccGroupTarget(string $ref, string $hiType): ?array
{
    if ($ref === '') return null;
    $id = (($pos = strrpos($ref, '/')) !== false) ? substr($ref, $pos + 1) : $ref;
    if ($id === '') return null;

    if (stripos($ref, 'HDR-DIAG') === 0 || stripos($ref, 'DIAGRPT') === 0) {
        return ['sample_status', 'id', $id];
    }
    if (stripos($ref, 'INV') === 0) {
        return ['billing', 'id', $id];
    }
    if (stripos($ref, 'DIS-') === 0 || stripos($ref, 'HDR-DIS/') === 0 || stripos($ref, 'IPPRES') === 0 || stripos($ref, 'IPWELL') === 0 || $hiType === 'DischargeSummary') {
        // IPD groups (DIS-, HDR-DIS/, IPPRES/, IPWELL/) all carry the ipd_no after the
        // last '/', and share the single central_ipd.abha_linked flag.
        return ['central_ipd', 'ipd_no', $id];
    }
    return ['central_opd', 'id', $id];
}

/**
 * An uploaded patient document group (patient_documents.php, discharge summary
 * file): `HDR-{yyyymmdd}/{ipd_no}`, as api/patient_history.php emits it, whose
 * careContext refs are patient_documents ids. The HDR-DIS / HDR-DIAG / HDR-OPC
 * groups are different records and are matched by ccGroupTarget().
 */
function ccIsDocumentGroup(string $ref, string $hiType): bool
{
    return $hiType === 'HealthDocumentRecord' && preg_match('#^HDR-\d{8}/#', $ref) === 1;
}

/** patient_documents ids in a document group, or every document of that IP patient when the group has none listed. */
function ccDocumentIds(string $ref, array $careContexts): array
{
    $ids = [];
    foreach ($careContexts as $cc) {
        $id = (string) ($cc['referenceNumber'] ?? '');
        if (ctype_digit($id)) $ids[] = (int) $id;
    }
    if ($ids) return array_values(array_unique($ids));

    $ipdNo = substr($ref, strrpos($ref, '/') + 1);
    if ($ipdNo === '') return [];
    $db  = new userlogin();
    $con = $db->dbconnect();
    $st  = $con->prepare("SELECT `id` FROM `patient_documents` WHERE `patient_no`=?");
    if ($st) {
        $st->bind_param('s', $ipdNo);
        $st->execute();
        $res = $st->get_result();
        while ($res && $r = $res->fetch_assoc()) $ids[] = (int) $r['id'];
        $st->close();
    }
    mysqli_close($con);
    return $ids;
}

/**
 * Record that the given care-context groups were submitted to ABDM, the same
 * flag the automatic link (optreatmentClass1::linkAbhaContextGroups) writes.
 * The ABHA report and ccGroupLinked() read it.
 */
function ccGroupsMarkLinked(mysqli $con, array $groups): void
{
    foreach ($groups as $grp) {
        $ref    = (string) ($grp['referenceNumber'] ?? '');
        $hiType = (string) ($grp['hiType'] ?? '');
        if (ccIsDocumentGroup($ref, $hiType)) {
            $ids = ccDocumentIds($ref, is_array($grp['careContexts'] ?? null) ? $grp['careContexts'] : []);
            if ($ids) {
                mysqli_query($con, "UPDATE `patient_documents` SET `abha_linked`=1 WHERE `id` IN (" . implode(',', $ids) . ")");
            }
            continue;
        }
        $target = ccGroupTarget($ref, $hiType);
        if (!$target) continue;
        [$tbl, $col, $id] = $target;
        $st = $con->prepare("UPDATE `$tbl` SET `abha_linked`=1 WHERE `$col`=? LIMIT 1");
        if (!$st) continue;
        $st->bind_param('s', $id);
        $st->execute();
        $st->close();
    }
}

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

/**
 * Mint a MILESTONE-2 access token (HIP scope) from the m2 gateway session.
 * The care-context link endpoints live behind the milestone-2 gateway, which
 * rejects the milestone-1 token minted by get_token with WSO2 900901
 * "Invalid Credentials". This mirrors the (working) auto-link flow in
 * optreatmentClass1::linkAbhaCareContexts. Returns '' on any failure so callers
 * can fall back to the client-supplied token.
 */
function getMilestone2Token(string $baseurl, string $apiKey): string {
    $clientId     = $_SESSION['clientid']     ?? '';
    $clientSecret = $_SESSION['clientSecret'] ?? '';
    if ($clientId === '' || $clientSecret === '' || $baseurl === '') {
        return '';
    }
    $ch = curl_init("$baseurl/api/milestone2/abdm-gateway/session");
    curl_setopt_array($ch, [
        CURLOPT_POST           => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT        => 30,
        CURLOPT_HTTPHEADER     => [
            "X-API-Key: $apiKey",
            "Content-Type: application/json",
        ],
        CURLOPT_POSTFIELDS => json_encode([
            "grantType"    => "client_credentials",
            "clientId"     => $clientId,
            "clientSecret" => $clientSecret,
        ]),
    ]);
    $res = curl_exec($ch);
    curl_close($ch);
    $data = json_decode((string) $res, true);
    return (string) ($data['data']['accessToken'] ?? $data['accessToken'] ?? '');
}

/**
 * RSA-OAEP encrypt using the ABDM public certificate stored in session.
 * The certificate/key is fetched via get_public_key and cached in $_SESSION.
 */
function abdmEncrypt(string $plaintext): string {
    $pubKeyPem = $_SESSION['abdm_public_key'] ?? '';
    if (!$pubKeyPem) {
        return $plaintext; // no key cached yet — ABDM will reject, surface real error
    }
    $key = openssl_pkey_get_public($pubKeyPem);
    if ($key === false) {
        return $plaintext;
    }
    $encrypted = '';
    openssl_public_encrypt($plaintext, $encrypted, $key, OPENSSL_PKCS1_OAEP_PADDING);
    return base64_encode($encrypted);
}

$baseurl = $_SESSION['base_url'] ?? '';
$apiKey  = $_SESSION['abha_api'] ?? '';

// ── get_token ─────────────────────────────────────────────────────────────────
if ($page === 'get_token') {
    $clientId     = $_SESSION['clientid'] ?? '';
    $clientSecret = $_SESSION['clientSecret'] ?? '';

    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL            => "$baseurl/api/milestone1/abdm/session",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING       => '',
        CURLOPT_MAXREDIRS      => 10,
        CURLOPT_TIMEOUT        => 30,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST  => 'POST',
        CURLOPT_POSTFIELDS     => json_encode([
            "grantType"    => "client_credentials",
            "clientId"     => $clientId,
            "clientSecret" => $clientSecret,
        ]),
        CURLOPT_HTTPHEADER     => [
            "X-API-Key: $apiKey",
            'Content-Type: application/json',
        ],
    ]);
    $response = curl_exec($ch);
    if ($response === false) {
        echo json_encode(['error' => curl_error($ch)]);
    } else {
        echo $response;
    }
    curl_close($ch);
    exit;

// ── get_public_key ────────────────────────────────────────────────────────────
} elseif ($page === 'get_public_key') {
    $accessToken = $input['accessToken'] ?? '';
    if (!$accessToken) {
        exit(json_encode(["error" => "Missing access token"]));
    }

    $ch = curl_init("$baseurl/api/milestone1/abdm/session/certificate");
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER     => [
            "Authorization: Bearer $accessToken",
            "X-API-Key: $apiKey",
        ],
        CURLOPT_TIMEOUT        => 30,
        CURLOPT_SSL_VERIFYPEER => true,
        CURLOPT_SSL_VERIFYHOST => 2,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_ENCODING       => '',
        CURLOPT_MAXREDIRS      => 10,
        CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST  => 'GET',
    ]);
    $res = curl_exec($ch);
    if ($res === false) {
        echo json_encode(['error' => curl_error($ch)]);
        curl_close($ch);
        exit;
    }
    curl_close($ch);

    // Cache public key in session so subsequent encrypt calls can use it.
    // ABDM returns: {"success":true,"data":{"publicKey":"<base64-DER>","encryptionAlgorithm":"..."}}
    $certData = json_decode($res, true);
    $rawB64 = $certData['data']['publicKey']
           ?? $certData['publicKey']
           ?? $certData['certificate']
           ?? null;
    if ($rawB64) {
        // Convert raw base64-DER to PEM so openssl_pkey_get_public() accepts it
        $_SESSION['abdm_public_key'] = "-----BEGIN PUBLIC KEY-----\n"
            . chunk_split($rawB64, 64, "\n")
            . "-----END PUBLIC KEY-----\n";
    } elseif (strpos(trim((string)$res), '-----BEGIN') !== false) {
        $_SESSION['abdm_public_key'] = trim((string)$res);
    }

    echo $res;
    exit;

// ── send_otp ──────────────────────────────────────────────────────────────────
} elseif ($page === 'send_otp') {
    $value       = isset($input['aadhaar']) ? preg_replace('/[^a-zA-Z0-9@._\-]/', '', $input['aadhaar']) : '';
    $accessToken = $input['accessToken'] ?? '';
    $searchType  = $input['searchType'] ?? '';
    $verifyMode  = $input['verifyMode'] ?? 'mobile'; // for abhaNumber: "mobile" or "aadhaar"

    // ABHA Address uses a completely different ABDM endpoint (PHR web login flow).
    // The caller can request either mobile OTP (otpSystem "abdm") or Aadhaar OTP
    // (otpSystem "aadhaar" → OTP delivered to the Aadhaar-linked mobile).
    if ($searchType === 'abhaAddress') {
        $otpSystem = ($verifyMode === 'aadhaar') ? 'aadhaar' : 'abdm';
        $ch = curl_init("$baseurl/api/milestone1/abha/verification/abha-address/send-otp");
        curl_setopt_array($ch, [
            CURLOPT_POST           => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER     => [
                "Content-Type: application/json",
                "X-API-Key: $apiKey",
                "Authorization: Bearer $accessToken",
            ],
            CURLOPT_POSTFIELDS     => json_encode([
                "abhaAddress" => strtolower(trim((string)$value)),
                "otpSystem"   => $otpSystem,
            ]),
        ]);
        $res = curl_exec($ch);
        curl_close($ch);
        echo $res;
        exit;
    }

    $loginHintMap = [
        'aadhaar'    => 'aadhaar',
        'mobile'     => 'mobile',
        'abhaNumber' => 'abha-number',
    ];
    if (!isset($loginHintMap[$searchType])) {
        exit(json_encode(['success' => false, 'message' => "searchType '$searchType' is not supported."]));
    }
    $loginHint = $loginHintMap[$searchType];

    $ch = curl_init("$baseurl/api/milestone1/abha/verification/send-otp");
    curl_setopt_array($ch, [
        CURLOPT_POST           => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER     => [
            "Content-Type: application/json",
            "X-API-Key: $apiKey",
            "Authorization: Bearer $accessToken",
        ],
        CURLOPT_POSTFIELDS     => json_encode([
            "value"     => $value,
            "loginHint" => $loginHint,
        ]),
    ]);
    $res = curl_exec($ch);
    curl_close($ch);
    echo $res;
    exit;

// ── profile_mobile_send_otp (update logged-in profile's mobile: step 1) ───────
// Sends an OTP to a NEW mobile using the profile login token (X-Token).
} elseif ($page === 'profile_mobile_send_otp') {
    $accessToken = $input['accessToken'] ?? '';
    $xToken      = $input['xToken'] ?? '';
    $mobile      = isset($input['mobile']) ? preg_replace('/[^0-9]/', '', $input['mobile']) : '';
    if ($mobile === '' || $xToken === '') {
        exit(json_encode(["error" => "Missing mobile or xToken"]));
    }
    $ch = curl_init("$baseurl/api/milestone1/abha/verification/profile/mobile/send-otp");
    curl_setopt_array($ch, [
        CURLOPT_POST           => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER     => [
            "Content-Type: application/json",
            "X-API-Key: $apiKey",
            "Authorization: Bearer $accessToken",
            "X-Token: $xToken",
        ],
        CURLOPT_POSTFIELDS     => json_encode(["mobile" => $mobile]),
    ]);
    $res = curl_exec($ch);
    curl_close($ch);
    echo $res;
    exit;

// ── profile_mobile_verify_otp (update logged-in profile's mobile: step 2) ──────
} elseif ($page === 'profile_mobile_verify_otp') {
    $accessToken = $input['accessToken'] ?? '';
    $xToken      = $input['xToken'] ?? '';
    $txnId       = isset($input['txnId']) ? preg_replace('/[^a-zA-Z0-9\-]/', '', $input['txnId']) : '';
    $otp         = isset($input['otp']) ? preg_replace('/[^0-9]/', '', $input['otp']) : '';
    if ($txnId === '' || $otp === '' || $xToken === '') {
        exit(json_encode(["error" => "Missing txnId, otp or xToken"]));
    }
    $ch = curl_init("$baseurl/api/milestone1/abha/verification/profile/mobile/verify-otp");
    curl_setopt_array($ch, [
        CURLOPT_POST           => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER     => [
            "Content-Type: application/json",
            "X-API-Key: $apiKey",
            "Authorization: Bearer $accessToken",
            "X-Token: $xToken",
        ],
        CURLOPT_POSTFIELDS     => json_encode(["txnId" => $txnId, "otp" => $otp]),
    ]);
    $res = curl_exec($ch);
    curl_close($ch);
    echo $res;
    exit;

// ── search_abha_by_mobile (mobile login: step 1) ──────────────────────────────
// Find the ABHA account(s) linked to a mobile number. A mobile can map to several
// ABHA numbers; the caller picks one by index for the OTP step.
} elseif ($page === 'search_abha_by_mobile') {
    $accessToken = $input['accessToken'] ?? '';
    $mobile      = isset($input['mobile']) ? preg_replace('/[^0-9]/', '', $input['mobile']) : '';
    if ($mobile === '') {
        exit(json_encode(["error" => "Missing mobile"]));
    }
    $ch = curl_init("$baseurl/api/milestone1/abha/verification/abha/search");
    curl_setopt_array($ch, [
        CURLOPT_POST           => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER     => [
            "Content-Type: application/json",
            "X-API-Key: $apiKey",
            "Authorization: Bearer $accessToken",
        ],
        CURLOPT_POSTFIELDS     => json_encode(["mobile" => $mobile]),
    ]);
    $res = curl_exec($ch);
    curl_close($ch);
    echo $res;
    exit;

// ── login_otp_by_index (mobile login: step 2) ─────────────────────────────────
// Request the login OTP for the ABHA the user selected (by index) from the search.
} elseif ($page === 'login_otp_by_index') {
    $accessToken = $input['accessToken'] ?? '';
    $txnId       = isset($input['txnId']) ? preg_replace('/[^a-zA-Z0-9\-]/', '', $input['txnId']) : '';
    $index       = isset($input['index']) ? preg_replace('/[^0-9]/', '', (string) $input['index']) : '';
    if ($txnId === '' || $index === '') {
        exit(json_encode(["error" => "Missing txnId or index"]));
    }
    $ch = curl_init("$baseurl/api/milestone1/abha/verification/login/request-otp-by-index");
    curl_setopt_array($ch, [
        CURLOPT_POST           => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER     => [
            "Content-Type: application/json",
            "X-API-Key: $apiKey",
            "Authorization: Bearer $accessToken",
        ],
        CURLOPT_POSTFIELDS     => json_encode(["txnId" => $txnId, "index" => $index]),
    ]);
    $res = curl_exec($ch);
    curl_close($ch);
    echo $res;
    exit;

// ── verify_otp ────────────────────────────────────────────────────────────────
} elseif ($page === 'verify_otp') {
    $txnId       = $input['txnId'] ?? '';
    $otp         = isset($input['otp']) ? preg_replace('/[^0-9]/', '', $input['otp']) : '';
    $searchType  = $input['searchType'] ?? '';
    $accessToken = $input['accessToken'] ?? '';

    // ABHA Address uses the PHR web login verify endpoint (different scope + ABDM URL).
    // Mirror the OTP system chosen at send-otp time: explicit otpSystem wins, else
    // derive it from verifyMode ("aadhaar" → Aadhaar OTP, otherwise mobile OTP).
    if ($searchType === 'abhaAddress') {
        $verifyMode = $input['verifyMode'] ?? '';
        $otpSystem  = $input['otpSystem'] ?? (($verifyMode === 'aadhaar') ? 'aadhaar' : 'abdm');
        $ch = curl_init("$baseurl/api/milestone1/abha/verification/abha-address/verify-otp");
        curl_setopt_array($ch, [
            CURLOPT_POST           => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER     => [
                "Content-Type: application/json",
                "X-API-Key: $apiKey",
                "Authorization: Bearer $accessToken",
            ],
            CURLOPT_POSTFIELDS     => json_encode([
                "txnId"     => $txnId,
                "otp"       => $otp,
                "otpSystem" => $otpSystem,
            ]),
        ]);
        $res = curl_exec($ch);
        curl_close($ch);
        echo $res;
        exit;
    }

    // ABDM valid scopes for profile login: aadhaar-verify, mobile-verify.
    // abha-number OTP goes to linked mobile → mobile-verify.
    $scopeMap = [
        'aadhaar'    => 'aadhaar-verify',
        'mobile'     => 'mobile-verify',
        'abhaNumber' => 'mobile-verify',
    ];
    $scopeSuffix = $scopeMap[$searchType] ?? 'mobile-verify';

    $ch = curl_init("$baseurl/api/milestone1/abha/verification/verify-otp");
    curl_setopt_array($ch, [
        CURLOPT_POST           => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER     => [
            "Content-Type: application/json",
            "X-API-Key: $apiKey",
            "Authorization: Bearer $accessToken",
        ],
        CURLOPT_POSTFIELDS     => json_encode([
            "txnId" => $txnId,
            "otp"   => $otp,
            "scope" => ["abha-login", $scopeSuffix],
        ]),
    ]);
    $res = curl_exec($ch);
    curl_close($ch);
    echo $res;
    exit;

// ── enroll_otp ────────────────────────────────────────────────────────────────
} elseif ($page === 'enroll_otp') {
    $aadhaar     = isset($input['aadhaar']) ? preg_replace('/[^0-9]/', '', $input['aadhaar']) : '';
    $accessToken = $input['accessToken'] ?? '';

    $headers = [
        "Content-Type: application/json",
        "X-API-Key: $apiKey",
    ];
    if ($accessToken) {
        $headers[] = "Authorization: Bearer $accessToken";
    }

    $ch = curl_init("$baseurl/api/milestone1/abha/enrollment/send-otp");
    curl_setopt_array($ch, [
        CURLOPT_POST           => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER     => $headers,
        CURLOPT_POSTFIELDS     => json_encode(["aadhaar" => $aadhaar]),
    ]);
    $res = curl_exec($ch);
    curl_close($ch);
    echo $res;
    exit;

// ── create_abha ───────────────────────────────────────────────────────────────
} elseif ($page === 'create_abha') {
    $otp         = isset($input['otp']) ? preg_replace('/[^0-9]/', '', $input['otp']) : '';
    $txnId       = isset($input['txnId']) ? preg_replace('/[^a-zA-Z0-9\-]/', '', $input['txnId']) : '';
    $mobile      = isset($input['mobile']) ? preg_replace('/[^0-9]/', '', $input['mobile']) : '';
    $accessToken = $input['accessToken'] ?? '';

    $ch = curl_init("$baseurl/api/milestone1/abha/enrollment/create-by-aadhaar");
    curl_setopt_array($ch, [
        CURLOPT_POST           => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER     => [
            "Content-Type: application/json",
            "X-API-Key: $apiKey",
            "Authorization: Bearer $accessToken",
        ],
        CURLOPT_POSTFIELDS     => json_encode([
            "txnId"  => $txnId,
            "otp"    => $otp,
            "mobile" => $mobile,
        ]),
    ]);
    $response = curl_exec($ch);
    curl_close($ch);
    echo $response;
    exit;

// ── enroll_bio (create ABHA by Aadhaar fingerprint / iris) ────────────────────
// `pid` is the PidData XML captured by the UIDAI RD service on the operator's
// PC. It is already encrypted for UIDAI — forward it untouched, never log it.
} elseif ($page === 'enroll_bio') {
    $aadhaar     = isset($input['aadhaar']) ? preg_replace('/[^0-9]/', '', $input['aadhaar']) : '';
    $mobile      = isset($input['mobile']) ? preg_replace('/[^0-9]/', '', $input['mobile']) : '';
    $bioType     = (isset($input['bioType']) && in_array($input['bioType'], ['finger', 'iris'], true)) ? $input['bioType'] : '';
    $pid         = isset($input['pid']) && is_string($input['pid']) ? $input['pid'] : '';
    $accessToken = $input['accessToken'] ?? '';

    if (strlen($aadhaar) !== 12 || $bioType === '' || $pid === ''
        || strlen($pid) > 200000 || strpos($pid, '<PidData') === false) {
        exit(json_encode(["success" => false, "message" => "Invalid biometric enrollment request"]));
    }

    $body = ["aadhaar" => $aadhaar, "bioType" => $bioType, "pid" => $pid];
    if (preg_match('/^[6-9][0-9]{9}$/', $mobile)) {
        $body["mobile"] = $mobile;
    }

    $ch = curl_init("$baseurl/api/milestone1/abha/enrollment/create-by-biometric");
    curl_setopt_array($ch, [
        CURLOPT_POST           => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER     => [
            "Content-Type: application/json",
            "X-API-Key: $apiKey",
            "Authorization: Bearer $accessToken",
        ],
        CURLOPT_POSTFIELDS     => json_encode($body),
    ]);
    $response = curl_exec($ch);
    curl_close($ch);
    echo $response;
    exit;

// ── Aadhaar face authentication (QR) ──────────────────────────────────────────
// The desk shows a QR (the face-auth deep link); the patient's phone does the
// face capture in the ABHA app while the page polls face_status. One transaction
// serves create (enroll_face) and the Aadhaar face login (face_login); the
// mobile-search flow starts its own with face_find_request.
} elseif (in_array($page, ['face_init', 'face_status', 'enroll_face', 'face_login', 'face_find_request', 'face_find_verify'], true)) {
    $accessToken = $input['accessToken'] ?? '';
    $txnId       = isset($input['txnId']) ? preg_replace('/[^a-zA-Z0-9\-]/', '', (string) $input['txnId']) : '';
    $aadhaar     = isset($input['aadhaar']) ? preg_replace('/[^0-9]/', '', (string) $input['aadhaar']) : '';
    $mobile      = isset($input['mobile']) ? preg_replace('/[^0-9]/', '', (string) $input['mobile']) : '';
    $index       = isset($input['index']) ? preg_replace('/[^0-9]/', '', (string) $input['index']) : '';

    $faceRoutes = [
        'face_init'         => 'enrollment/face/init',
        'face_status'       => 'enrollment/face/status',
        'enroll_face'       => 'enrollment/create-by-face',
        'face_login'        => 'verification/face/login',
        'face_find_request' => 'verification/face/find/request',
        'face_find_verify'  => 'verification/face/find/verify',
    ];
    $body = [];
    if ($page !== 'face_init') {
        if ($txnId === '') {
            exit(json_encode(["success" => false, "message" => "Missing txnId"]));
        }
        $body["txnId"] = $txnId;
    }
    if ($page === 'enroll_face' || $page === 'face_login') {
        if (strlen($aadhaar) !== 12) {
            exit(json_encode(["success" => false, "message" => "Invalid Aadhaar number"]));
        }
        $body["aadhaar"] = $aadhaar;
    }
    if ($page === 'enroll_face' && preg_match('/^[6-9][0-9]{9}$/', $mobile)) {
        $body["mobile"] = $mobile;
    }
    if ($page === 'face_find_request') {
        if ($index === '') {
            exit(json_encode(["success" => false, "message" => "Missing index"]));
        }
        $body["index"] = $index;
    }

    $ch = curl_init("$baseurl/api/milestone1/abha/" . $faceRoutes[$page]);
    curl_setopt_array($ch, [
        CURLOPT_POST           => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER     => [
            "Content-Type: application/json",
            "X-API-Key: $apiKey",
            "Authorization: Bearer $accessToken",
        ],
        CURLOPT_POSTFIELDS     => json_encode((object) $body),
    ]);
    $response = curl_exec($ch);
    curl_close($ch);
    echo $response;
    exit;

// ── abha_suggestion (TC-008: fetch suggested ABHA addresses) ───────────────────
} elseif ($page === 'abha_suggestion') {
    $txnId       = isset($input['txnId']) ? preg_replace('/[^a-zA-Z0-9\-]/', '', $input['txnId']) : '';
    $accessToken = $input['accessToken'] ?? '';
    if ($txnId === '') {
        exit(json_encode(["error" => "Missing txnId"]));
    }

    $ch = curl_init("$baseurl/api/milestone1/abha/enrollment/address-suggestions?txnId=" . urlencode($txnId));
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER     => [
            "Content-Type: application/json",
            "X-API-Key: $apiKey",
            "Authorization: Bearer $accessToken",
        ],
    ]);
    $response = curl_exec($ch);
    curl_close($ch);
    echo $response;
    exit;

// ── set_abha_address (TC-008: set chosen/preferred ABHA address) ───────────────
} elseif ($page === 'set_abha_address') {
    $txnId       = isset($input['txnId']) ? preg_replace('/[^a-zA-Z0-9\-]/', '', $input['txnId']) : '';
    // ABDM wants the BARE username (e.g. "harshal1997"). Drop any "@domain" the
    // client may include, then keep only valid username characters (incl. hyphen).
    $abhaAddress = '';
    if (isset($input['abhaAddress'])) {
        $username    = preg_replace('/@.*/', '', (string) $input['abhaAddress']);
        // ABHA addresses are case-insensitive — register the username lowercase.
        $abhaAddress = strtolower(preg_replace('/[^a-zA-Z0-9._-]/', '', $username));
    }
    $xToken      = $input['xToken'] ?? '';
    $accessToken = $input['accessToken'] ?? '';
    if ($txnId === '' || $abhaAddress === '' || $xToken === '') {
        exit(json_encode(["error" => "Missing txnId, abhaAddress or xToken"]));
    }

    $ch = curl_init("$baseurl/api/milestone1/abha/enrollment/create-address");
    curl_setopt_array($ch, [
        CURLOPT_POST           => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER     => [
            "Content-Type: application/json",
            "X-API-Key: $apiKey",
            "Authorization: Bearer $accessToken",
            "X-Token: $xToken",
        ],
        CURLOPT_POSTFIELDS     => json_encode([
            "txnId"       => $txnId,
            "abhaAddress" => $abhaAddress,
            "preferred"   => true,
        ]),
    ]);
    $response = curl_exec($ch);
    curl_close($ch);
    echo $response;
    exit;

// ── download_abha ─────────────────────────────────────────────────────────────
} elseif ($page === 'download_abha') {
    $accessToken = $input['accessToken'] ?? '';
    $xToken      = $input['xToken'] ?? '';
    if (!$accessToken) {
        exit(json_encode(["error" => "Missing access token"]));
    }

    $ch = curl_init("$baseurl/api/milestone1/abha/card/download");
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER     => [
            "Authorization: Bearer $accessToken",
            "X-API-Key: $apiKey",
            "X-Token: $xToken",
        ],
        CURLOPT_TIMEOUT        => 30,
        CURLOPT_SSL_VERIFYPEER => true,
        CURLOPT_SSL_VERIFYHOST => 2,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_ENCODING       => '',
        CURLOPT_MAXREDIRS      => 10,
        CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST  => 'GET',
    ]);
    $res = curl_exec($ch);
    if ($res === false) {
        echo json_encode(['error' => curl_error($ch)]);
    } else {
        if (ob_get_length()) {
            ob_end_clean();
        }
        header("Content-Type: image/png");
        header("Content-Disposition: attachment; filename=\"abha-card.png\"");
        header("Content-Length: " . strlen($res));
        echo $res;
    }
    curl_close($ch);
    exit;

// ── download_abha_phr ─────────────────────────────────────────────────────────
// ABHA-address (PHR web login) card download. Uses the PHR transaction token
// (X-Token) from the abha-address verify step; different ABDM endpoint than the
// enrollment card above.
} elseif ($page === 'download_abha_phr') {
    $accessToken = $input['accessToken'] ?? '';
    $xToken      = $input['xToken'] ?? '';
    if (!$accessToken) {
        exit(json_encode(["error" => "Missing access token"]));
    }

    $ch = curl_init("$baseurl/api/milestone1/abha/verification/abha-address/card");
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER     => [
            "Authorization: Bearer $accessToken",
            "X-API-Key: $apiKey",
            "X-Token: $xToken",
        ],
        CURLOPT_TIMEOUT        => 30,
        CURLOPT_SSL_VERIFYPEER => true,
        CURLOPT_SSL_VERIFYHOST => 2,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_ENCODING       => '',
        CURLOPT_MAXREDIRS      => 10,
        CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST  => 'GET',
    ]);
    $res = curl_exec($ch);
    if ($res === false) {
        echo json_encode(['error' => curl_error($ch)]);
    } else {
        if (ob_get_length()) {
            ob_end_clean();
        }
        header("Content-Type: image/png");
        header("Content-Disposition: attachment; filename=\"abha-card.png\"");
        header("Content-Length: " . strlen($res));
        echo $res;
    }
    curl_close($ch);
    exit;

// ── abha_profile ──────────────────────────────────────────────────────────────
} elseif ($page === 'abha_profile') {
    $accessToken = $input['accessToken'] ?? '';
    $xToken      = $input['xToken'] ?? '';
    if (!$accessToken) {
        exit(json_encode(["error" => "Missing access token"]));
    }

    $ch = curl_init("$baseurl/api/milestone1/abha/profile");
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER     => [
            "Authorization: Bearer $accessToken",
            "Content-Type: application/json",
            "X-API-Key: $apiKey",
            "X-Token: $xToken",
        ],
        CURLOPT_TIMEOUT        => 30,
        CURLOPT_SSL_VERIFYPEER => true,
        CURLOPT_SSL_VERIFYHOST => 2,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_ENCODING       => '',
        CURLOPT_MAXREDIRS      => 10,
        CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST  => 'GET',
    ]);
    $res = curl_exec($ch);
    if ($res === false) {
        echo json_encode(['error' => curl_error($ch)]);
    } else {
        echo $res;
    }
    curl_close($ch);
    exit;

// ── send_profile_otp ──────────────────────────────────────────────────────────
// Triggers ABDM to send OTP to user's mobile for verifying a selected ABHA account
} elseif ($page === 'send_profile_otp') {
    $accessToken = $input['accessToken'] ?? '';
    $txnId       = $input['txnId'] ?? '';
    $jwtToken    = $input['jwtToken'] ?? '';
    // Strip non-digits then reformat as XX-XXXX-XXXX-XXXX (Node validates this pattern)
    $abhaRaw    = preg_replace('/[^0-9]/', '', $input['abhaNumber'] ?? '');
    $abhaNumber = strlen($abhaRaw) === 14
        ? substr($abhaRaw, 0, 2) . '-' . substr($abhaRaw, 2, 4) . '-' . substr($abhaRaw, 6, 4) . '-' . substr($abhaRaw, 10, 4)
        : $abhaRaw;
    if (!$accessToken) {
        exit(json_encode(["error" => "Missing access token"]));
    }

    $ch = curl_init("$baseurl/api/milestone1/abha/verification/verify-user/login");
    curl_setopt_array($ch, [
        CURLOPT_POST           => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER     => [
            "Authorization: Bearer $accessToken",
            "X-API-Key: $apiKey",
            "T-token: Bearer $jwtToken",
            "Content-Type: application/json",
        ],
        CURLOPT_POSTFIELDS     => json_encode([
            "txnId"      => $txnId,
            "ABHANumber" => $abhaNumber,
        ]),
    ]);
    $response = curl_exec($ch);
    curl_close($ch);
    echo $response;
    exit;

// ── verify_profile_otp ────────────────────────────────────────────────────────
// Verifies the OTP from send_profile_otp; returns x-token for profile access
} elseif ($page === 'verify_profile_otp') {
    $accessToken = $input['accessToken'] ?? '';
    $txnId       = $input['txnId'] ?? '';
    $otp         = isset($input['otp']) ? preg_replace('/[^0-9]/', '', $input['otp']) : '';
    if (!$accessToken) {
        exit(json_encode(["error" => "Missing access token"]));
    }

    // Second OTP verify after verify/user: reuse /verify-otp with mobile-verify scope.
    // Node normalises scope so "mobile-verify" is always correct here.
    $ch = curl_init("$baseurl/api/milestone1/abha/verification/verify-otp");
    curl_setopt_array($ch, [
        CURLOPT_POST           => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER     => [
            "Authorization: Bearer $accessToken",
            "X-API-Key: $apiKey",
            "Content-Type: application/json",
        ],
        CURLOPT_POSTFIELDS     => json_encode([
            "txnId" => $txnId,
            "otp"   => $otp,
            "scope" => ["abha-login", "mobile-verify"],
        ]),
    ]);
    $response = curl_exec($ch);
    curl_close($ch);
    echo $response;
    exit;

// ── update_profile_mobile ─────────────────────────────────────────────────────
// Updates mobile number linked to user's ABHA profile
} elseif ($page === 'update_profile_mobile') {
    $accessToken = $input['accessToken'] ?? '';
    $xToken      = $input['xToken'] ?? '';
    $mobile      = isset($input['mobile']) ? preg_replace('/[^0-9]/', '', $input['mobile']) : '';
    if (!$accessToken) {
        exit(json_encode(["error" => "Missing access token"]));
    }
    if (!preg_match('/^\d{10}$/', $mobile)) {
        exit(json_encode(["error" => "Invalid mobile number"]));
    }

    $ch = curl_init("$baseurl/api/milestone1/abha/profile/mobile");
    curl_setopt_array($ch, [
        CURLOPT_CUSTOMREQUEST  => 'PUT',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER     => [
            "Authorization: Bearer $accessToken",
            "X-API-Key: $apiKey",
            "X-Token: $xToken",
            "Content-Type: application/json",
        ],
        CURLOPT_POSTFIELDS     => json_encode(["mobile" => $mobile]),
    ]);
    $response = curl_exec($ch);
    curl_close($ch);
    echo $response;
    exit;

// ── mobile_update_send_otp ────────────────────────────────────────────────────
// Send an OTP to a NEW mobile number (enrollment mobile-verify sub-flow). Uses the
// enrollment txnId from ABHA creation; the node layer encrypts the mobile.
} elseif ($page === 'mobile_update_send_otp') {
    $accessToken = $input['accessToken'] ?? '';
    $txnId       = isset($input['txnId']) ? preg_replace('/[^a-zA-Z0-9\-]/', '', $input['txnId']) : '';
    $mobile      = isset($input['mobile']) ? preg_replace('/[^0-9]/', '', $input['mobile']) : '';
    if ($txnId === '' || $mobile === '') {
        exit(json_encode(["error" => "Missing txnId or mobile"]));
    }

    $ch = curl_init("$baseurl/api/milestone1/abha/enrollment/mobile-update/send-otp");
    curl_setopt_array($ch, [
        CURLOPT_POST           => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER     => [
            "Content-Type: application/json",
            "X-API-Key: $apiKey",
            "Authorization: Bearer $accessToken",
        ],
        CURLOPT_POSTFIELDS     => json_encode(["txnId" => $txnId, "mobile" => $mobile]),
    ]);
    $res = curl_exec($ch);
    curl_close($ch);
    echo $res;
    exit;

// ── mobile_update_verify_otp ──────────────────────────────────────────────────
// Verify the OTP sent to the new mobile → updates it on the ABHA profile.
} elseif ($page === 'mobile_update_verify_otp') {
    $accessToken = $input['accessToken'] ?? '';
    $txnId       = isset($input['txnId']) ? preg_replace('/[^a-zA-Z0-9\-]/', '', $input['txnId']) : '';
    $otp         = isset($input['otp']) ? preg_replace('/[^0-9]/', '', $input['otp']) : '';
    if ($txnId === '' || $otp === '') {
        exit(json_encode(["error" => "Missing txnId or otp"]));
    }

    $ch = curl_init("$baseurl/api/milestone1/abha/enrollment/mobile-update/verify-otp");
    curl_setopt_array($ch, [
        CURLOPT_POST           => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER     => [
            "Content-Type: application/json",
            "X-API-Key: $apiKey",
            "Authorization: Bearer $accessToken",
        ],
        CURLOPT_POSTFIELDS     => json_encode(["txnId" => $txnId, "otp" => $otp]),
    ]);
    $res = curl_exec($ch);
    curl_close($ch);
    echo $res;
    exit;

// ── get_patient_care_contexts (M2) ───────────────────────────────────────────
// Fetch patient demographics + available care contexts for linking
} elseif ($page === 'get_patient_care_contexts') {
    $identifier     = trim($input['identifier'] ?? '');
    $identifierType = trim($input['identifier_type'] ?? 'auto');

    if ($identifier === '') {
        exit(json_encode(['success' => false, 'error' => 'identifier is required']));
    }

    $db  = new userlogin();
    $con = $db->dbconnect();

    // Determine search column and patient_history payload. $historyQuery holds the
    // same identifier as flat keys, which is what patient_history.php reads when it
    // is run in-process (it falls back to $_REQUEST for every identifier).
    $patient = null;
    $historyPayload = [];
    $historyQuery = [];

    if ($identifierType === 'opd_no' || preg_match('/^OP\d+$/i', $identifier)) {
        $sVal = mysqli_real_escape_string($con, strtoupper($identifier));
        $res  = mysqli_query($con, "SELECT id,name,gender,dob,age,mobile,abha_number,abha_address FROM central_opd WHERE opd_no='$sVal' LIMIT 1");
        $patient = $res ? mysqli_fetch_assoc($res) : null;
        $historyPayload = ['opd_no' => strtoupper($identifier)];
        $historyQuery   = ['opd_no' => strtoupper($identifier)];
    } elseif ($identifierType === 'ipd_no' || preg_match('/^IP\d+$/i', $identifier)) {
        // Inpatient — take the DOB (and age) from the patient's linked OPD
        // registration. central_opd.dob is only filled for patients registered
        // since the column was added, so the age fallback below still matters.
        $sVal = mysqli_real_escape_string($con, strtoupper($identifier));
        $res  = mysqli_query($con, "SELECT id,name,gender,opd_no,mobile,abha_number,abha_address FROM central_ipd WHERE ipd_no='$sVal' ORDER BY id DESC LIMIT 1");
        $patient = $res ? mysqli_fetch_assoc($res) : null;
        if ($patient) {
            $patient['dob'] = '';
            $patient['age'] = '';
            if (!empty($patient['opd_no'])) {
                $oVal = mysqli_real_escape_string($con, (string) $patient['opd_no']);
                $oRow = mysqli_fetch_assoc(mysqli_query($con, "SELECT dob,age FROM central_opd WHERE opd_no='$oVal' ORDER BY id DESC LIMIT 1")) ?: [];
                if (!empty($oRow['dob']) && $oRow['dob'] !== '0000-00-00') {
                    $patient['dob'] = $oRow['dob'];
                }
                if (!empty($oRow['age'])) {
                    $patient['age'] = $oRow['age'];
                }
            }
        }
        $historyPayload = ['ipd_no' => strtoupper($identifier)];
        $historyQuery   = ['ipd_no' => strtoupper($identifier)];
    } elseif ($identifierType === 'mobile' || preg_match('/^\d{10}$/', $identifier)) {
        $sVal = mysqli_real_escape_string($con, $identifier);
        $res  = mysqli_query($con, "SELECT id,name,gender,dob,age,mobile,abha_number,abha_address FROM central_opd WHERE REPLACE(REPLACE(mobile,' ',''),'-','')='$sVal' ORDER BY id DESC LIMIT 1");
        $patient = $res ? mysqli_fetch_assoc($res) : null;
        $historyPayload = ['mobile' => $identifier, 'patient' => ['mobile' => $identifier]];
        $historyQuery   = ['mobile' => $identifier];
    } elseif (strpos($identifier, '@') !== false) {
        $sVal = mysqli_real_escape_string($con, $identifier);
        $res  = mysqli_query($con, "SELECT id,name,gender,dob,age,mobile,abha_number,abha_address FROM central_opd WHERE LOWER(abha_address)=LOWER('$sVal') LIMIT 1");
        $patient = $res ? mysqli_fetch_assoc($res) : null;
        $historyPayload = ['patient' => ['id' => $identifier]];
        $historyQuery   = ['abha_address' => $identifier];
    } else {
        $sVal = mysqli_real_escape_string($con, preg_replace('/[^0-9]/', '', $identifier));
        $res  = mysqli_query($con, "SELECT id,name,gender,dob,age,mobile,abha_number,abha_address FROM central_opd WHERE REPLACE(abha_number,'-','')='$sVal' LIMIT 1");
        $patient = $res ? mysqli_fetch_assoc($res) : null;
        $historyPayload = ['patient' => ['verifiedIdentifiers' => [['type' => 'abha_number', 'value' => $sVal]]]];
        $historyQuery   = ['abha_number' => $sVal];
    }

    if (!$patient) {
        exit(json_encode(['success' => false, 'error' => 'Patient not found with the given identifier']));
    }

    // Year of birth, from the real DOB when the row has one (supports YYYY-MM-DD
    // or DD-MM-YYYY). central_opd.dob is NULL for every patient registered before
    // that column existed, so fall back to deriving the year from age ("54Y").
    $dob   = $patient['dob'] ?? '';
    $parts = $dob ? preg_split('/[-\/]/', $dob) : [];
    $yearOfBirth = '';
    if (count($parts) === 3) {
        $yearOfBirth = strlen($parts[0]) === 4 ? $parts[0] : $parts[2];
    } elseif (!empty($patient['age'])) {
        $ageYears = (int) preg_replace('/[^0-9].*$/', '', trim((string) $patient['age']));
        if ($ageYears > 0 && $ageYears < 130) {
            $yearOfBirth = (string) ((int) date('Y') - $ageYears);
        }
    }
    $patient['year_of_birth'] = $yearOfBirth;

    // Care contexts come from api/patient_history.php. Run it in-process whenever it
    // sits beside this file: the old self-call went out to the site's public URL and
    // came back in through the proxy, which answers server-originated requests with
    // HTTP 502 even though the same endpoint serves browsers fine — and the empty
    // list that produced was indistinguishable from a patient with no records.
    $historyFile = __DIR__ . '/api/patient_history.php';
    if (is_readable($historyFile) && $historyQuery) {
        // patient_history.php reads every identifier from $_REQUEST when the JSON
        // body doesn't carry it, and finishes with exit() — so seed the request,
        // buffer its output, and complete this response from a shutdown handler.
        foreach ($historyQuery as $qKey => $qVal) {
            $_REQUEST[$qKey] = $qVal;
            $_GET[$qKey]     = $qVal;
        }

        $ccPatient = $patient;
        $obBase    = ob_get_level();

        register_shutdown_function(function () use ($ccPatient, $obBase) {
            $raw = '';
            while (ob_get_level() > $obBase) {
                $raw = (string) ob_get_clean() . $raw;
            }

            $decoded = json_decode($raw, true);
            if (!is_array($decoded) || !array_key_exists('patient', $decoded)) {
                echo json_encode([
                    'success' => false,
                    'error'   => 'Could not load care contexts — the records service ' .
                                 'returned an unreadable response.',
                    'patient' => $ccPatient,
                ]);
                return;
            }

            $groups = is_array($decoded['patient']) ? $decoded['patient'] : [];

            // patient_history.php closes the shared handle on its way out.
            $ccDb  = new userlogin();
            $ccCon = $ccDb->dbconnect();
            foreach ($groups as &$grp) {
                $grp['linked'] = ccGroupLinked(
                    $ccCon,
                    (string) ($grp['referenceNumber'] ?? ''),
                    (string) ($grp['hiType'] ?? ''),
                    is_array($grp['careContexts'] ?? null) ? $grp['careContexts'] : []
                );
            }
            unset($grp);

            echo json_encode([
                'success'      => true,
                'patient'      => $ccPatient,
                'careContexts' => $groups,
            ]);
        });

        // Its own include of ../model/dbconnect.php is relative to that directory.
        @chdir(dirname($historyFile));
        ob_start();
        include $historyFile;
        exit;
    }

    // Fallback for installs where api/ lives on another host. This is a self-call
    // over HTTP, so it can fail for reasons that have nothing to do with the patient:
    // the host being unable to reach its own public URL (hairpin NAT), a TLS/CA
    // problem, or a WAF sitting in front of the API.
    $localUrl = HIMS_API_BASE_URL . 'patient_history.php';
    $ch = curl_init($localUrl);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => json_encode($historyPayload),
        CURLOPT_HTTPHEADER     => ['Content-Type: application/json'],
        CURLOPT_TIMEOUT        => 15,
    ]);
    $historyResp = curl_exec($ch);
    $curlErr     = curl_error($ch);
    $httpCode    = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    $history = is_string($historyResp) ? json_decode($historyResp, true) : null;

    // A failed lookup used to fall through as an empty list, which the UI renders as
    // "No care contexts found for this patient" — indistinguishable from a patient
    // who genuinely has none, and impossible to diagnose from the screen. Report it.
    if (!is_array($history) || !array_key_exists('patient', $history)) {
        $reason = $curlErr !== ''
            ? $curlErr
            : ($httpCode >= 400
                ? "the records service returned HTTP $httpCode"
                : 'the records service returned an unreadable response');
        exit(json_encode([
            'success' => false,
            'error'   => 'Could not load care contexts — ' . $reason .
                         '. (Checked ' . $localUrl . ')',
            'patient' => $patient,
        ]));
    }

    $careContexts = $history['patient'] ?? [];

    // Annotate each group with its ABDM link status so the UI can show
    // Linked / Not-linked and avoid re-linking already-pushed records.
    foreach ($careContexts as &$grp) {
        $grp['linked'] = ccGroupLinked(
            $con,
            (string) ($grp['referenceNumber'] ?? ''),
            (string) ($grp['hiType'] ?? ''),
            is_array($grp['careContexts'] ?? null) ? $grp['careContexts'] : []
        );
    }
    unset($grp);

    exit(json_encode(['success' => true, 'patient' => $patient, 'careContexts' => $careContexts]));

// ── link_token (M2) ──────────────────────────────────────────────────────────
// Generate an ABDM link token for HIP-initiated care-context linking
} elseif ($page === 'link_token') {
    $accessToken = $input['accessToken'] ?? '';
    // ABDM requires the 14-digit ABHA number without dashes
    $abhaNumber  = str_replace('-', '', trim($input['abhaNumber'] ?? ''));
    $abhaAddress = trim($input['abhaAddress'] ?? '');
    $name        = trim($input['name']        ?? '');
    // ABDM only accepts M / F / O — never U
    $genderInit  = strtoupper(substr(trim($input['gender'] ?? ''), 0, 1));
    $gender      = in_array($genderInit, ['M', 'F', 'O'], true) ? $genderInit : 'O';
    // ABDM expects a 4-digit year; reject anything else before the call
    $yearOfBirth = (string) trim($input['yearOfBirth'] ?? '');

    if (!$accessToken || !$abhaNumber || !$abhaAddress) {
        exit(json_encode(['success' => false, 'error' => 'accessToken, abhaNumber, abhaAddress are required']));
    }
    if (!preg_match('/^\d{4}$/', $yearOfBirth)) {
        exit(json_encode(['success' => false, 'error' => 'yearOfBirth (4-digit) is required — patient date of birth is missing or invalid']));
    }

    $requestId = generateUuidV4();
    $timestamp = isoTimestamp();

    // Use a milestone-2 (HIP-scope) token — the m1 token from get_token is
    // rejected by the m2 link gateway with WSO2 900901 "Invalid Credentials".
    $m2Token   = getMilestone2Token($baseurl, $apiKey);
    $bearer    = $m2Token !== '' ? $m2Token : $accessToken;

    $ch = curl_init("$baseurl/api/milestone2/hip-link/token/generate");
    curl_setopt_array($ch, [
        CURLOPT_POST           => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER     => [
            "X-API-Key: $apiKey",
            "Authorization: Bearer $bearer",
            "REQUEST-ID: $requestId",
            "TIMESTAMP: $timestamp",
            "X-HIP-ID: " . ABDM_HIP_ID,
            "X-CM-ID: "  . ABDM_CM_ID,
            "Content-Type: application/json",
        ],
        CURLOPT_POSTFIELDS => json_encode([
            'abhaNumber'  => $abhaNumber,
            'abhaAddress' => $abhaAddress,
            'name'        => $name,
            'gender'      => $gender,
            'yearOfBirth' => $yearOfBirth,
        ]),
        CURLOPT_TIMEOUT => 40,
    ]);
    $response = curl_exec($ch);
    curl_close($ch);
    echo $response ?: json_encode(['success' => false, 'error' => 'link_token request failed']);
    exit;

// ── link_care_contexts (M2) ───────────────────────────────────────────────────
// Link selected care contexts to patient ABHA via ABDM
} elseif ($page === 'link_care_contexts') {
    $accessToken = $input['accessToken'] ?? '';
    $linkToken   = trim($input['linkToken']   ?? '');
    // ABDM requires the 14-digit ABHA number without dashes
    $abhaNumber  = str_replace('-', '', trim($input['abhaNumber'] ?? ''));
    $abhaAddress = trim($input['abhaAddress'] ?? '');
    $patient     = $input['patient'] ?? [];

    if (!$accessToken || !$linkToken || !$abhaNumber || empty($patient)) {
        exit(json_encode(['success' => false, 'error' => 'accessToken, linkToken, abhaNumber, patient[] are required']));
    }

    $requestId = generateUuidV4();
    $timestamp = isoTimestamp();

    $body = [[
        'abhaNumber'  => $abhaNumber,
        'abhaAddress' => $abhaAddress,
        'patient'     => $patient,
    ]];

    // Milestone-2 (HIP-scope) token — the m1 token is rejected by the m2 link
    // gateway with WSO2 900901 "Invalid Credentials".
    $m2Token = getMilestone2Token($baseurl, $apiKey);
    $bearer  = $m2Token !== '' ? $m2Token : $accessToken;

    $ch = curl_init("$baseurl/api/milestone2/hip-link/care-contexts/link");
    curl_setopt_array($ch, [
        CURLOPT_POST           => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER     => [
            "X-API-Key: $apiKey",
            "Authorization: Bearer $bearer",
            "REQUEST-ID: $requestId",
            "TIMESTAMP: $timestamp",
            "X-HIP-ID: "    . ABDM_HIP_ID,
            "X-LINK-TOKEN: $linkToken",
            "X-CM-ID: "     . ABDM_CM_ID,
            "Content-Type: application/json",
        ],
        CURLOPT_POSTFIELDS => json_encode($body),
        CURLOPT_TIMEOUT    => 30,
    ]);
    $response = curl_exec($ch);
    $httpCode = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    // Same success test careContextLink.php uses before it reports "submitted".
    $decoded = json_decode((string) $response, true);
    if ($httpCode >= 200 && $httpCode < 300 && !empty($decoded['success']) && is_array($patient)) {
        $markDb  = new userlogin();
        $markCon = $markDb->dbconnect();
        ccGroupsMarkLinked($markCon, $patient);
        $markCon->close();
    }

    echo $response ?: json_encode(['success' => false, 'error' => 'link_care_contexts request failed']);
    exit;

} else {
    echo json_encode(["error" => "Invalid page parameter"]);
    exit;
}

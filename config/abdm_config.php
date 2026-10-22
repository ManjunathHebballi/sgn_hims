<?php

/**
 * ABDM configuration — loads .env then defines all ABDM constants.
 * Include this file once at the top of any ABDM-related PHP file instead
 * of re-declaring define() blocks with hardcoded values.
 *
 * Usage (from api/ subdirectory):
 *   require_once __DIR__ . '/../config/abdm_config.php';
 *
 * Usage (from project root):
 *   require_once __DIR__ . '/config/abdm_config.php';
 */

require_once __DIR__ . '/env_loader.php';
// abdmSafeDisplay() / abdmSanitizeCareContextEntries(). Loaded here so every
// ABDM caller gets them without a second require — the auto-link model methods
// already pull this file in for the ABDM_AUTOLINK switch.
require_once __DIR__ . '/abdm_display.php';

// Load .env from project root (one level up from config/)
loadEnv(__DIR__ . '/../.env');

// ── ABDM Credentials ────────────────────────────────────────────────────────
if (!defined('ABDM_BASE_URL')) {
    define('ABDM_BASE_URL', getenv('ABDM_BASE_URL') ?: 'https://abdm-api.nexenstial.com');
}
if (!defined('ABDM_CLIENT_ID')) {
    define('ABDM_CLIENT_ID', getenv('ABDM_CLIENT_ID') ?: '');
}
if (!defined('ABDM_CLIENT_SECRET')) {
    define('ABDM_CLIENT_SECRET', getenv('ABDM_CLIENT_SECRET') ?: '');
}
if (!defined('ABDM_HIP_ID')) {
    define('ABDM_HIP_ID', getenv('ABDM_HIP_ID') ?: '');
}
if (!defined('ABDM_CM_ID')) {
    define('ABDM_CM_ID', getenv('ABDM_CM_ID') ?: 'abdm');
}
// ── Automatic care-context linking ───────────────────────────────────────────
// When 'off', clinical events (OPD treatment save, billing, discharge, IPD
// document upload, lab finalisation) STOP requesting a link token and pushing
// care contexts to ABDM. Manual linking via careContextLink.php is unaffected.
//
// Why this exists: every save asks ABDM for a link token for that patient's
// ABHA, and ABDM rejects a second request while one is live
// (ABDM-1092 "Duplicate Link token request") — which competes with, and blocks,
// a manual link attempt for the same patient.
//
// Default is 'on' so nothing changes for an install that does not set it.
// Set ABDM_AUTOLINK=off in .env to disable. Node has its own copy of this flag
// (it reads its own .env) — turn BOTH off to stop auto-linking end to end.
if (!defined('ABDM_AUTOLINK')) {
    define('ABDM_AUTOLINK', strtolower(trim((string) (getenv('ABDM_AUTOLINK') ?: 'on'))));
}
if (!function_exists('abdmAutoLinkEnabled')) {
    /**
     * Is automatic (clinical-event driven) care-context linking switched on?
     * Anything other than off/false/0/no counts as on, so a typo fails safe
     * towards the existing behaviour rather than silently disabling ABDM.
     */
    function abdmAutoLinkEnabled(): bool
    {
        return !in_array(ABDM_AUTOLINK, ['off', 'false', '0', 'no'], true);
    }
}
// ── HIU (Health Information User / Milestone 3) ──────────────────────────────
// Sent as X-HIU-ID + consent.hiu.id. Same facility as the HIP, so default to
// ABDM_HIP_ID when a dedicated HIU id isn't configured.
if (!defined('ABDM_HIU_ID')) {
    define('ABDM_HIU_ID', getenv('ABDM_HIU_ID') ?: (getenv('ABDM_HIP_ID') ?: ABDM_HIP_ID));
}
// Scope of an HIU consent request (fetchHealthRecords.php).
//
// ABDM reads a null `consent.hip` as "every HIP this patient has linked", so an
// unscoped request pulls back records other facilities created. Default is OFF:
// the consent names ABDM_HIP_ID, and a fetch returns only this hospital's records.
// Set ABDM_HIU_FETCH_ALL_HIPS=on to request the patient's full linked history.
if (!defined('ABDM_HIU_FETCH_ALL_HIPS')) {
    $fetchAllEnv = strtolower(trim((string) (getenv('ABDM_HIU_FETCH_ALL_HIPS') ?: 'off')));
    define('ABDM_HIU_FETCH_ALL_HIPS', in_array($fetchAllEnv, ['on', 'true', '1', 'yes'], true));
}
// Requester identifier used in the consent request (the ordering doctor).
if (!defined('ABDM_REQUESTER_SYSTEM')) {
    define('ABDM_REQUESTER_SYSTEM', getenv('ABDM_REQUESTER_SYSTEM') ?: 'https://www.nmc.org.in');
}
if (!defined('ABDM_REQUESTER_DEFAULT_REGNO')) {
    define('ABDM_REQUESTER_DEFAULT_REGNO', getenv('ABDM_REQUESTER_DEFAULT_REGNO') ?: '');
}

// ── ABDM Gateway Endpoint URLs ───────────────────────────────────────────────
// These callbacks now route THROUGH the Node gateway (abdm-api.nexenstial.com),
// which proxies them to ABDM — instead of hitting ABDM directly. Defaults are
// derived from ABDM_BASE_URL (the gateway) so a single env var controls it.
$abdmProxyBase = rtrim(ABDM_BASE_URL, '/') . '/api/milestone2/abdm-proxy';
if (!defined('ABDM_ON_NOTIFY_URL')) {
    define('ABDM_ON_NOTIFY_URL', getenv('ABDM_ON_NOTIFY_URL') ?: $abdmProxyBase . '/on-notify');
}
if (!defined('ABDM_ON_REQUEST_URL')) {
    define('ABDM_ON_REQUEST_URL', getenv('ABDM_ON_REQUEST_URL') ?: $abdmProxyBase . '/hip-on-request');
}
if (!defined('ABDM_HI_NOTIFY_URL')) {
    define('ABDM_HI_NOTIFY_URL', getenv('ABDM_HI_NOTIFY_URL') ?: $abdmProxyBase . '/hi-notify');
}
// Encrypted health-data push. The dataPushUrl is dynamic (ABDM supplies it per
// request), so this is the gateway endpoint that forwards the body to that URL —
// keeps the push off the shared-hosting IP that ABDM's CloudFront/WAF blocks.
if (!defined('ABDM_DATA_PUSH_URL')) {
    define('ABDM_DATA_PUSH_URL', getenv('ABDM_DATA_PUSH_URL') ?: $abdmProxyBase . '/data-push');
}
// Scan & Share (patient-share/v3) reply. Our on-share ack goes through the same
// abdm-proxy (the Node gateway forwards it to ABDM's
// /api/hiecm/patient-share/v3/on-share). MUST route via the proxy, not a raw
// ABDM_BASE_URL/api/hiecm/... path (the gateway does not route that).
if (!defined('ABDM_ON_SHARE_URL')) {
    define('ABDM_ON_SHARE_URL', getenv('ABDM_ON_SHARE_URL') ?: $abdmProxyBase . '/on-share');
}
// Running Token Status reply. ABDM asks the HIP for a counter's current running
// token at {callback_url}/api/v3/hip/patient/running-token/status; the answer goes
// back through the same abdm-proxy that carries on-share.
if (!defined('ABDM_ON_RUNNING_TOKEN_STATUS_URL')) {
    define(
        'ABDM_ON_RUNNING_TOKEN_STATUS_URL',
        getenv('ABDM_ON_RUNNING_TOKEN_STATUS_URL') ?: $abdmProxyBase . '/running-token-on-status'
    );
}
// Fallback average service time (minutes) reported with the running token when
// there is not enough history at a counter to measure one.
if (!defined('SCAN_SHARE_DEFAULT_SERVICE_MINUTES')) {
    define('SCAN_SHARE_DEFAULT_SERVICE_MINUTES', (int)(getenv('SCAN_SHARE_DEFAULT_SERVICE_MINUTES') ?: 5));
}
// Validity (seconds) of the scan-and-share counter token we hand back in on-share;
// also used to expire stale WAITING queue rows.
if (!defined('SCAN_SHARE_TOKEN_EXPIRY')) {
    define('SCAN_SHARE_TOKEN_EXPIRY', (int)(getenv('SCAN_SHARE_TOKEN_EXPIRY') ?: 1800));
}
// How to answer a Scan & Share from a SELF-DECLARED ABHA profile (one shared
// without a verified 14-digit ABHA number):
//   'token'  — queue the patient and hand back a token like any other share; the
//              queue row is flagged self_declared so the counter verifies ID.
//   'reject' — do not queue; reply with the documented ABDM error envelope instead
//              (the default, keeping the facility's verified-ABHA-only rule).
// Switch to 'token' if the facility decides to serve self-declared patients.
if (!defined('SCAN_SHARE_SELF_DECLARED_MODE')) {
    $ssMode = strtolower(trim((string) (getenv('SCAN_SHARE_SELF_DECLARED_MODE') ?: 'reject')));
    define('SCAN_SHARE_SELF_DECLARED_MODE', $ssMode === 'token' ? 'token' : 'reject');
}
// Base URL the facility QR encodes. Per ABDM the QR is a share-profile URL the
// patient's ABHA/PHR app opens (NOT arbitrary JSON):
//   {base}?hip-id={HFR_ID}&counter-id={counter_code}
// Sandbox: https://phrsbx.abdm.gov.in/share-profile
// Production: https://phr.abdm.gov.in/share-profile
if (!defined('ABDM_SHARE_PROFILE_URL')) {
    define('ABDM_SHARE_PROFILE_URL', getenv('ABDM_SHARE_PROFILE_URL') ?: 'https://phrsbx.abdm.gov.in/share-profile');
}

// ── Local Service URLs ────────────────────────────────────────────────────────
if (!defined('FIDELIUS_URL')) {
    define('FIDELIUS_URL', getenv('FIDELIUS_URL') ?: 'http://localhost:8090');
}
if (!defined('FHIR_ENDPOINT')) {
    define('FHIR_ENDPOINT', getenv('FHIR_ENDPOINT') ?: 'http://localhost/sgn_jalna_hospital/api/fhir_records.php');
}
// Optional server-internal address for the SAME fhir_records.php. When the HIP
// handler calls the record generator it is talking to its own machine, so there
// is no reason for that request to leave the box: with FHIR_ENDPOINT set to a
// public https:// URL the call goes out to DNS, back over TLS and through every
// reverse proxy in front of PHP — carrying PHI, and failing whenever any of
// those layers does (a bodyless nginx 502 with nothing in the PHP error log is
// the classic symptom, since PHP never runs).
//
// Set this to reach the origin directly, e.g. on cPanel where nginx proxies to
// Apache on 8080:
//   FHIR_ENDPOINT_INTERNAL=http://127.0.0.1:8080/hims_test/api/fhir_records.php
// The Host header is taken from FHIR_ENDPOINT so name-based vhost routing still
// selects the right site. Leave blank to keep using FHIR_ENDPOINT unchanged.
if (!defined('FHIR_ENDPOINT_INTERNAL')) {
    define('FHIR_ENDPOINT_INTERNAL', getenv('FHIR_ENDPOINT_INTERNAL') ?: '');
}
// Base URL of the HIMS api/ directory (…/HIMS/api/). Used for internal
// self-calls like patient_history.php. Falls back to deriving from FHIR_ENDPOINT.
if (!defined('HIMS_API_BASE_URL')) {
    $himsApiBaseEnv = getenv('HIMS_API_BASE_URL');
    define('HIMS_API_BASE_URL', $himsApiBaseEnv
        ? rtrim($himsApiBaseEnv, '/') . '/'
        : preg_replace('#fhir_records\.php$#', '', FHIR_ENDPOINT));
}

// ── Hospital / Facility ───────────────────────────────────────────────────────
if (!defined('FACILITY_NAME')) {
    define('FACILITY_NAME', getenv('HOSPITAL_NAME') ?: 'Hospital');
}
if (!defined('HOSPITAL_COLLEGE_NAME')) {
    define('HOSPITAL_COLLEGE_NAME', getenv('HOSPITAL_COLLEGE_NAME') ?: (getenv('HOSPITAL_NAME') ?: 'Hospital'));
}
if (!defined('HOSPITAL_COLLEGE_SOCIETY')) {
    define('HOSPITAL_COLLEGE_SOCIETY', getenv('HOSPITAL_COLLEGE_SOCIETY') ?: (getenv('HOSPITAL_SOCIETY') ?: ''));
}
if (!defined('HOSPITAL_COLLEGE_LOCATION')) {
    define('HOSPITAL_COLLEGE_LOCATION', getenv('HOSPITAL_COLLEGE_LOCATION') ?: (getenv('HOSPITAL_LOCATION') ?: ''));
}
if (!defined('HOSPITAL_COLLEGE_PHONE')) {
    define('HOSPITAL_COLLEGE_PHONE', getenv('HOSPITAL_COLLEGE_PHONE') ?: (getenv('HOSPITAL_PHONE') ?: ''));
}
if (!defined('HOSPITAL_COLLEGE_MAIL')) {
    define('HOSPITAL_COLLEGE_MAIL', getenv('HOSPITAL_COLLEGE_MAIL') ?: (getenv('HOSPITAL_MAIL') ?: ''));
}
if (!defined('HOSPITAL_LOGO')) {
    // Absolute path to the logo file (env override allowed); mPDF embeds it.
    $defaultLogo = realpath(__DIR__ . '/../billing/assets/logo.png');
    define('HOSPITAL_LOGO', getenv('HOSPITAL_LOGO') ?: ($defaultLogo ?: ''));
}

// ── App environment / security flags ─────────────────────────────────────────
if (!defined('APP_ENV')) {
    define('APP_ENV', strtolower(getenv('APP_ENV') ?: 'development')); // production | development
}
if (!defined('APP_DEBUG')) {
    // When false in production, dev/debug tools (dev_guard.php) return 404.
    define('APP_DEBUG', filter_var(getenv('APP_DEBUG') ?: 'false', FILTER_VALIDATE_BOOLEAN));
}
if (!defined('ABDM_VERIFY_SSL')) {
    // Verify TLS certs on outbound ABDM / gateway calls. Defaults ON in
    // production, OFF in development (sandbox certs are sometimes flaky).
    $verifyEnv = getenv('ABDM_VERIFY_SSL');
    define('ABDM_VERIFY_SSL', ($verifyEnv === false || $verifyEnv === '')
        ? (APP_ENV === 'production')
        : filter_var($verifyEnv, FILTER_VALIDATE_BOOLEAN));
}

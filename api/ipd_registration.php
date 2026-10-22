<?php
/**
 * IPD Registration (admission) API — admits an existing OPD patient (same as ipregistration.php).
 *
 * POST /api/ipd_registration.php
 * Headers: Content-Type: application/json, X-API-Key: <REGISTRATION_API_KEY>
 * Patient details default from the OPD visit (opd_no); any field sent in the body overrides it.
 * See api/REGISTRATION_API.md for the full field list. Logic: model/registrationService.php.
 */
require_once(__DIR__ . '/_registration_common.php');
require_once(__DIR__ . '/../model/registrationService.php');

api_require_post_and_key();
$in = api_input();

$con = api_db();
$r = reg_register_ipd($con, $in, api_created_by($in));
$con->close();

api_respond($r['code'], $r['ok'], $r['message'], $r['data']);

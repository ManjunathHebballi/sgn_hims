<?php
/**
 * OPD Registration API — registers a new OPD patient (same as opregistration.php).
 *
 * POST /api/opd_registration.php
 * Headers: Content-Type: application/json, X-API-Key: <REGISTRATION_API_KEY>
 * See api/REGISTRATION_API.md for the full field list. Logic: model/registrationService.php.
 */
require_once(__DIR__ . '/_registration_common.php');
require_once(__DIR__ . '/../model/registrationService.php');

api_require_post_and_key();
$in = api_input();

$con = api_db();
$r = reg_register_opd($con, $in, api_created_by($in));
$con->close();

api_respond($r['code'], $r['ok'], $r['message'], $r['data']);

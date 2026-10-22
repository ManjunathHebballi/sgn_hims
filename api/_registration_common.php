<?php
/**
 * Shared helpers for the OPD / IPD registration APIs.
 *
 * Auth: every request must send header  X-API-Key: <REGISTRATION_API_KEY from .env>.
 * If REGISTRATION_API_KEY is not set, the APIs refuse all requests.
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, X-API-Key');

if (($_SERVER['REQUEST_METHOD'] ?? '') === 'OPTIONS') {
    http_response_code(204);
    exit;
}

require_once(__DIR__ . '/../model/env.php');
require_once(__DIR__ . '/../model/dbconnect.php');
date_default_timezone_set("Asia/Kolkata");

function api_respond($httpCode, $success, $message, $data = null)
{
    http_response_code($httpCode);
    echo json_encode(['success' => $success, 'message' => $message, 'data' => $data]);
    exit;
}

function api_require_post_and_key()
{
    if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
        api_respond(405, false, 'Only POST is allowed');
    }
    $expected = (string) env('REGISTRATION_API_KEY', '');
    $given = $_SERVER['HTTP_X_API_KEY'] ?? '';
    if ($expected === '' || !hash_equals($expected, (string) $given)) {
        api_respond(401, false, 'Invalid or missing API key');
    }
}

/** Reads a JSON body, falling back to form fields. */
function api_input()
{
    $raw = file_get_contents('php://input');
    $json = json_decode($raw, true);
    $input = is_array($json) ? $json : $_POST;
    foreach ($input as $k => $v) {
        if (is_string($v)) {
            $input[$k] = trim($v);
        }
    }
    return $input;
}

function api_db()
{
    $login = new userlogin();
    $con = $login->dbconnect1();
    mysqli_set_charset($con, 'utf8mb4');
    return $con;
}

/** Value for central_opd/central_ipd.created_by. */
function api_created_by($input)
{
    $by = isset($input['created_by']) && $input['created_by'] !== '' ? $input['created_by'] : 'API';
    return substr((string) $by, 0, 100);
}

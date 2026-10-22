<?php
/**
 * Minimal .env file loader — no Composer required.
 *
 * This project already has one .env parser in model/env.php (it also exposes the
 * env() helper used by model/dbconnect.php and index.php). Rather than parse the
 * same file twice with two sets of rules, this file keeps the loadEnv($path) API
 * that config/abdm_config.php expects and delegates the actual work.
 *
 * After loadEnv() runs, every key is readable through getenv(), $_ENV and
 * $_SERVER — which is what the ABDM constants below it rely on.
 */

require_once __DIR__ . '/../model/env.php';

if (!function_exists('loadEnv')) {
    function loadEnv(string $path): void
    {
        load_env($path);
    }
}

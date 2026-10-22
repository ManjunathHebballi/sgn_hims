<?php
// Centralized session configuration for Docker environment
if (session_status() === PHP_SESSION_NONE) {
    $isLocal = in_array($_SERVER['HTTP_HOST'] ?? '', ['localhost', '127.0.0.1', '::1'], true);

    $currentPath = (string) ini_get('session.save_path');
    if (strpos($currentPath, ';') !== false) {
        $parts = explode(';', $currentPath);
        $currentPath = end($parts);
    }

    if ($isLocal) {
        $candidatePaths = array('C:/xampp/tmp', $currentPath, sys_get_temp_dir());
    } else {
        $candidatePaths = array($currentPath, '/var/lib/php/sessions', '/tmp/hospital_sessions', sys_get_temp_dir());
    }

    $sessionPathSet = false;
    foreach ($candidatePaths as $path) {
        $path = trim((string)$path);
        if ($path === '') {
            continue;
        }
        if (!is_dir($path)) {
            @mkdir($path, 0777, true);
        }
        if (is_dir($path) && is_writable($path)) {
            ini_set('session.save_path', $path);
            $sessionPathSet = true;
            break;
        }
    }

    if (!$sessionPathSet) {
        ini_set('session.save_path', sys_get_temp_dir());
    }

    $isHttps = (
        (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ||
        (isset($_SERVER['SERVER_PORT']) && (int)$_SERVER['SERVER_PORT'] === 443) ||
        (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && strtolower((string)$_SERVER['HTTP_X_FORWARDED_PROTO']) === 'https')
    );

    ini_set('session.cookie_domain', '');
    ini_set('session.cookie_secure', $isHttps ? '1' : '0');
    ini_set('session.cookie_samesite', 'Lax');
    ini_set('session.gc_probability', 1);

    // Cookie parameters - common settings
    ini_set('session.cookie_lifetime', 0);
    ini_set('session.cookie_path', '/');
    ini_set('session.cookie_httponly', 1);

    // Use cookies only (not URL parameters)
    ini_set('session.use_only_cookies', 1);
    ini_set('session.use_trans_sid', 0);

    if (!@session_start()) {
        ini_set('session.save_path', sys_get_temp_dir());
        @session_start();
    }
}
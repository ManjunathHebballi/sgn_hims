<?php
/**
 * Dev/debug guard. Include at the very top of any debug/test/PHI-dump endpoint:
 *   require_once __DIR__ . '/../config/dev_guard.php';
 *
 * In production (APP_ENV=production) these tools return 404 unless APP_DEBUG=true.
 * Keeps fhir_preview / log viewers / webhook test pages off the public internet
 * once live.
 */
require_once __DIR__ . '/abdm_config.php';

if (APP_ENV === 'production' && !APP_DEBUG) {
    http_response_code(404);
    header('Content-Type: text/plain');
    exit('Not found');
}

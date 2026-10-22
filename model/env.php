<?php
/**
 * Minimal .env loader (no external dependencies).
 *
 * Reads the project root .env file once per request and exposes the values
 * through getenv(), $_ENV, $_SERVER and the env() helper below.
 * Real environment variables (Docker, Apache SetEnv, etc.) always win over
 * values coming from the file.
 */

if (!function_exists('load_env')) {

    function load_env($path = null)
    {
        static $loaded = false;

        if ($loaded) {
            return;
        }
        $loaded = true;

        if ($path === null) {
            // model/ -> project root
            $path = dirname(__DIR__) . '/.env';
        }

        if (!is_readable($path)) {
            return;
        }

        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        if ($lines === false) {
            return;
        }

        foreach ($lines as $line) {
            $line = trim($line);

            // Skip blank lines and comments
            if ($line === '' || $line[0] === '#' || $line[0] === ';') {
                continue;
            }

            if (strpos($line, '=') === false) {
                continue;
            }

            list($name, $value) = explode('=', $line, 2);
            $name  = trim($name);
            $value = trim($value);

            if ($name === '') {
                continue;
            }

            // Strip surrounding quotes
            $len = strlen($value);
            if ($len >= 2) {
                $first = $value[0];
                $last  = $value[$len - 1];
                if (($first === '"' && $last === '"') || ($first === "'" && $last === "'")) {
                    $value = substr($value, 1, -1);
                    if ($first === '"') {
                        $value = str_replace(
                            array('\\n', '\\r', '\\"', '\\\\'),
                            array("\n", "\r", '"', '\\'),
                            $value
                        );
                    }
                }
            }

            // Do not override a real environment variable
            if (getenv($name) !== false) {
                continue;
            }

            putenv($name . '=' . $value);
            $_ENV[$name]    = $value;
            $_SERVER[$name] = $value;
        }
    }
}

if (!function_exists('env')) {

    /**
     * Fetch a configuration value, falling back to $default when unset.
     */
    function env($key, $default = null)
    {
        load_env();

        $value = getenv($key);

        if ($value === false) {
            if (array_key_exists($key, $_ENV)) {
                $value = $_ENV[$key];
            } else {
                return $default;
            }
        }

        switch (strtolower((string) $value)) {
            case 'true':
                return true;
            case 'false':
                return false;
            case 'null':
            case '':
                return $default;
        }

        return $value;
    }
}

load_env();

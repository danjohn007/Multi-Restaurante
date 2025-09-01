<?php
/**
 * Configuration file for Multi-Restaurant System
 */

// Database configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'ejercito_multirestaurante');
define('DB_USER', 'ejercito_multirestaurante');
define('DB_PASS', 'Danjohn007!');

// Auto-detect base URL
function getBaseUrl() {
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
    $host = $_SERVER['HTTP_HOST'];
    $script = $_SERVER['SCRIPT_NAME'];
    $path = str_replace(basename($script), '', $script);
    return $protocol . $host . $path;
}

define('BASE_URL', getBaseUrl());

// Application settings
define('APP_NAME', 'Multi-Restaurante');
define('APP_VERSION', '1.0.0');

// Session configuration
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_secure', 0); // Set to 1 for HTTPS only

// Timezone
date_default_timezone_set('America/Mexico_City');

// Error reporting (set to 0 in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>

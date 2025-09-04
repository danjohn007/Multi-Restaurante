<?php
/**
 * Configuration file for Multi-Restaurant System
 */

// Database configuration
define('DB_HOST', '127.0.0.1');
define('DB_NAME', 'multi_restaurante');
define('DB_USER', 'app_user');
define('DB_PASS', 'app_password');

// Auto-detect base URL
function getBaseUrl() {
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost:8000';
    $script = $_SERVER['SCRIPT_NAME'] ?? '/index.php';
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

<?php
/**
 * CMS Configuration File
 */

// Database Configuration (Supports environment variables for Docker, with local fallback)
define('DB_HOST', getenv('DB_HOST') ?: 'localhost');
define('DB_USER', getenv('DB_USER') ?: 'root');
define('DB_PASS', getenv('DB_PASS') !== false ? getenv('DB_PASS') : '');
define('DB_NAME', getenv('DB_NAME') ?: 'php_abonk_cms');

// Dynamic Base URL calculation
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
$scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
$dir = dirname($scriptName);

// Clean up trailing slash and directory structure
$dir = ($dir === '\\' || $dir === '/') ? '' : $dir;
$baseUrl = $protocol . $host . $dir;

define('BASE_URL', rtrim($baseUrl, '/'));

// Uploads Directory Configuration
define('UPLOAD_DIR', __DIR__ . '/../assets/uploads');

// Display errors for development (disable in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

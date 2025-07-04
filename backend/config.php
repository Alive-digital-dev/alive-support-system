<?php
// ALIVE Support System - Configuration

// Database Configuration
define('DB_HOST', getenv('MYSQL_HOST') ?: 'localhost');
define('DB_PORT', getenv('MYSQL_PORT') ?: '3306');
define('DB_USER', getenv('MYSQL_USER') ?: 'root');
define('DB_PASS', getenv('MYSQL_PASSWORD') ?: '');
define('DB_NAME', getenv('MYSQL_DATABASE') ?: 'alive_support');

// System Configuration
define('SYSTEM_NAME', 'ALIVE Support System');
define('SYSTEM_VERSION', '1.0.0');
define('TIMEZONE', 'Asia/Jerusalem');

// API Configuration
define('API_KEY', getenv('API_KEY') ?: 'your-secret-api-key');
define('GEMINI_API_KEY', getenv('GEMINI_API_KEY') ?: '');

// Security
define('JWT_SECRET', getenv('JWT_SECRET') ?: 'your-jwt-secret-key');
define('BCRYPT_ROUNDS', 12);

// File Upload
define('MAX_FILE_SIZE', 10 * 1024 * 1024); // 10MB
define('UPLOAD_DIR', 'uploads/');

// Session Configuration
ini_set('session.cookie_httponly', 1);
ini_set('session.use_strict_mode', 1);
ini_set('session.cookie_secure', 1);

// Set timezone
date_default_timezone_set(TIMEZONE);

// Error reporting (disable in production)
if (getenv('ENVIRONMENT') !== 'production') {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}
?>

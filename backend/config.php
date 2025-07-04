<?php
// ALIVE Support System Configuration
define('DB_HOST', $_ENV['MYSQLHOST'] ?? 'localhost');
define('DB_NAME', $_ENV['MYSQLDATABASE'] ?? 'railway');
define('DB_USER', $_ENV['MYSQLUSER'] ?? 'root');
define('DB_PASS', $_ENV['MYSQLPASSWORD'] ?? '');

echo "ALIVE Support System Backend Ready!";
?>
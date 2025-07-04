<?php
require_once 'config.php';

class Database {
    private $connection;
    
    public function getConnection() {
        $this->connection = null;
        
        try {
            $host = defined('DB_HOST') ? DB_HOST : 'localhost';
            $dbname = defined('DB_NAME') ? DB_NAME : 'support_system';
            $username = defined('DB_USER') ? DB_USER : 'root';
            $password = defined('DB_PASS') ? DB_PASS : '';
            
            $dsn = "mysql:host={$host};dbname={$dbname};charset=utf8mb4";
            
            $this->connection = new PDO($dsn, $username, $password);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
        } catch (PDOException $e) {
            echo "Database connection failed: " . $e->getMessage();
        }
        
        return $this->connection;
    }
}
?>
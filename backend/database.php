<?php
// ALIVE Support System - Database Connection

require_once 'config.php';

class Database {
    private $host;
    private $port;
    private $dbname;
    private $username;
    private $password;
    private $pdo;
    private $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci"
    ];

    public function __construct() {
        $this->host = DB_HOST;
        $this->port = DB_PORT;
        $this->dbname = DB_NAME;
        $this->username = DB_USER;
        $this->password = DB_PASS;
    }

    public function connect() {
        if ($this->pdo === null) {
            try {
                $dsn = "mysql:host={$this->host};port={$this->port};dbname={$this->dbname};charset=utf8mb4";
                $this->pdo = new PDO($dsn, $this->username, $this->password, $this->options);
                
                // Test connection
                $this->pdo->query("SELECT 1");
                
            } catch (PDOException $e) {
                throw new PDOException("Connection failed: " . $e->getMessage());
            }
        }
        return $this->pdo;
    }

    public function query($sql, $params = []) {
        try {
            $stmt = $this->connect()->prepare($sql);
            $stmt->execute($params);
            return $stmt;
        } catch (PDOException $e) {
            throw new PDOException("Query failed: " . $e->getMessage());
        }
    }

    public function fetch($sql, $params = []) {
        return $this->query($sql, $params)->fetch();
    }

    public function fetchAll($sql, $params = []) {
        return $this->query($sql, $params)->fetchAll();
    }

    public function insert($table, $data) {
        $columns = implode(', ', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));
        $sql = "INSERT INTO {$table} ({$columns}) VALUES ({$placeholders})";
        
        $this->query($sql, $data);
        return $this->connect()->lastInsertId();
    }

    public function update($table, $data, $conditions) {
        $set = [];
        foreach ($data as $key => $value) {
            $set[] = "{$key} = :{$key}";
        }
        $set = implode(', ', $set);
        
        $where = [];
        foreach ($conditions as $key => $value) {
            $where[] = "{$key} = :where_{$key}";
            $data["where_{$key}"] = $value;
        }
        $where = implode(' AND ', $where);
        
        $sql = "UPDATE {$table} SET {$set} WHERE {$where}";
        return $this->query($sql, $data);
    }

    public function delete($table, $conditions) {
        $where = [];
        foreach ($conditions as $key => $value) {
            $where[] = "{$key} = :{$key}";
        }
        $where = implode(' AND ', $where);
        
        $sql = "DELETE FROM {$table} WHERE {$where}";
        return $this->query($sql, $conditions);
    }

    public function beginTransaction() {
        return $this->connect()->beginTransaction();
    }

    public function commit() {
        return $this->connect()->commit();
    }

    public function rollback() {
        return $this->connect()->rollback();
    }

    public function getLastInsertId() {
        return $this->connect()->lastInsertId();
    }

    public function tableExists($table) {
        $sql = "SHOW TABLES LIKE :table";
        $result = $this->query($sql, ['table' => $table]);
        return $result->rowCount() > 0;
    }

    public function createTables() {
        $this->createUsersTable();
        $this->createAgentsTable();
        $this->createConversationsTable();
        $this->createMessagesTable();
        $this->createTicketsTable();
        $this->createSystemLogsTable();
        $this->createKnowledgeBaseTable();
        $this->createSettingsTable();
        $this->insertDefaultData();
    }

    private function createUsersTable() {
        $sql = "CREATE TABLE IF NOT EXISTS users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            email VARCHAR(255) UNIQUE NOT NULL,
            phone VARCHAR(20),
            password_hash VARCHAR(255),
            role ENUM('customer', 'agent', 'admin') DEFAULT 'customer',
            status ENUM('active', 'inactive', 'banned') DEFAULT 'active',
            last_login TIMESTAMP NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX idx_email (email),
            INDEX idx_role (role),
            INDEX idx_status (status)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
        
        $this->query($sql);
    }

    private function createAgentsTable() {
        $sql = "CREATE TABLE IF NOT EXISTS agents (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            department VARCHAR(100),
            specialization TEXT,
            status ENUM('online', 'offline', 'busy', 'away') DEFAULT 'offline',
            max_concurrent_chats INT DEFAULT 5,
            current_chats INT DEFAULT 0,
            total_chats INT DEFAULT 0,
            average_rating DECIMAL(3,2) DEFAULT 0.00,
            last_activity TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
            INDEX idx_user_id (user_id),
            INDEX idx_status (status),
            INDEX idx_department (department)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
        
        $this->query($sql);
    }

    private function createConversationsTable() {
        $sql = "CREATE TABLE IF NOT EXISTS conversations (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT,
            agent_id INT,
            type ENUM('bot', 'live', 'ticket') DEFAULT 'bot',
            status ENUM('active', 'waiting', 'resolved', 'closed') DEFAULT 'active',
            priority ENUM('low', 'medium', 'high', 'urgent') DEFAULT 'medium',
            subject VARCHAR(255),
            department VARCHAR(100),
            started_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            last_message_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            resolved_at TIMESTAMP NULL,
            closed_at TIMESTAMP NULL,
            rating INT CHECK (rating >= 1 AND rating <= 5),
            feedback TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
            FOREIGN KEY (agent_id) REFERENCES agents(id) ON DELETE SET NULL,
            INDEX idx_user_id (user_id),
            INDEX idx_agent_id (agent_id),
            INDEX idx_status (status),
            INDEX idx_type (type),
            INDEX idx_priority (priority)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
        
        $this->query($sql);
    }

    private function createMessagesTable() {
        $sql = "CREATE TABLE IF NOT EXISTS messages (
            id INT AUTO_INCREMENT PRIMARY KEY,
            conversation_id INT NOT NULL,
            sender_id INT,
            sender_type ENUM('user', 'agent', 'bot', 'system') NOT NULL,
            message_type ENUM('text', 'image', 'file', 'audio', 'video', 'system') DEFAULT 'text',
            content TEXT NOT NULL,
            file_url VARCHAR(500),
            file_name VARCHAR(255),
            file_size INT,
            is_read BOOLEAN DEFAULT FALSE,
            read_at TIMESTAMP NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (conversation_id) REFERENCES conversations(id) ON DELETE CASCADE,
            FOREIGN KEY (sender_id) REFERENCES users(id) ON DELETE SET NULL,
            INDEX idx_conversation_id (conversation_id),
            INDEX idx_sender_id (sender_id),
            INDEX idx_sender_type (sender_type),
            INDEX idx_created_at (created_at)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
        
        $this->query($sql);
    }

    private function createTicketsTable() {
        $sql = "CREATE TABLE IF NOT EXISTS tickets (
            id INT AUTO_INCREMENT PRIMARY KEY,
            ticket_number VARCHAR(50) UNIQUE NOT NULL,
            user_id INT,
            agent_id INT,
            category VARCHAR(100),
            subject VARCHAR(255) NOT NULL,
            description TEXT,
            status ENUM('new', 'open', 'pending', 'resolved', 'closed') DEFAULT 'new',
            priority ENUM('low', 'medium', 'high', 'urgent') DEFAULT 'medium',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            resolved_at TIMESTAMP NULL,
            closed_at TIMESTAMP NULL,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
            FOREIGN KEY (agent_id) REFERENCES agents(id) ON DELETE SET NULL,
            INDEX idx_ticket_number (ticket_number),
            INDEX idx_user_id (user_id),
            INDEX idx_agent_id (agent_id),
            INDEX idx_status (status),
            INDEX idx_priority (priority)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
        
        $this->query($sql);
    }

    private function createSystemLogsTable() {
        $sql = "CREATE TABLE IF NOT EXISTS system_logs (
            id INT AUTO_INCREMENT PRIMARY KEY,
            level ENUM('info', 'warning', 'error', 'debug') NOT NULL,
            message TEXT NOT NULL,
            context JSON,
            user_id INT,
            ip_address VARCHAR(45),
            user_agent TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
            INDEX idx_level (level),
            INDEX idx_user_id (user_id),
            INDEX idx_created_at (created_at)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
        
        $this->query($sql);
    }

    private function createKnowledgeBaseTable() {
        $sql = "CREATE TABLE IF NOT EXISTS knowledge_base (
            id INT AUTO_INCREMENT PRIMARY KEY,
            category VARCHAR(100),
            title VARCHAR(255) NOT NULL,
            content TEXT NOT NULL,
            keywords TEXT,
            status ENUM('active', 'inactive') DEFAULT 'active',
            views INT DEFAULT 0,
            helpful_votes INT DEFAULT 0,
            unhelpful_votes INT DEFAULT 0,
            created_by INT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL,
            INDEX idx_category (category),
            INDEX idx_status (status),
            INDEX idx_keywords (keywords(255))
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
        
        $this->query($sql);
    }

    private function createSettingsTable() {
        $sql = "CREATE TABLE IF NOT EXISTS settings (
            id INT AUTO_INCREMENT PRIMARY KEY,
            setting_key VARCHAR(100) UNIQUE NOT NULL,
            setting_value TEXT,
            setting_type ENUM('string', 'number', 'boolean', 'json') DEFAULT 'string',
            description TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX idx_setting_key (setting_key)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
        
        $this->query($sql);
    }

    private function insertDefaultData() {
        // Insert default admin user
        $adminExists = $this->fetch("SELECT id FROM users WHERE email = 'admin@alive.com'");
        if (!$adminExists) {
            $this->insert('users', [
                'name' => 'ALIVE Admin',
                'email' => 'admin@alive.com',
                'password_hash' => password_hash('admin123', PASSWORD_DEFAULT),
                'role' => 'admin',
                'status' => 'active'
            ]);
        }

        // Insert default settings
        $defaultSettings = [
            ['setting_key' => 'system_name', 'setting_value' => 'ALIVE Support System', 'description' => 'System name'],
            ['setting_key' => 'max_concurrent_chats', 'setting_value' => '5', 'setting_type' => 'number', 'description' => 'Maximum concurrent chats per agent'],
            ['setting_key' => 'auto_assign_tickets', 'setting_value' => 'true', 'setting_type' => 'boolean', 'description' => 'Auto assign tickets to agents'],
            ['setting_key' => 'bot_enabled', 'setting_value' => 'true', 'setting_type' => 'boolean', 'description' => 'Enable chatbot'],
            ['setting_key' => 'office_hours', 'setting_value' => '{"start": "09:00", "end": "17:00"}', 'setting_type' => 'json', 'description' => 'Office hours']
        ];

        foreach ($defaultSettings as $setting) {
            $exists = $this->fetch("SELECT id FROM settings WHERE setting_key = ?", [$setting['setting_key']]);
            if (!$exists) {
                $this->insert('settings', $setting);
            }
        }
    }
}

// Global database instance
$database = new Database();
?>

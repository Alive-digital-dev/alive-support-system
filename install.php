<?php
// ALIVE Support System - Installation Script

require_once 'config.php';
require_once 'database.php';

header('Content-Type: application/json');

try {
    // Test database connection
    $db = new Database();
    $connection = $db->connect();
    
    if ($connection) {
        // Create all tables
        $db->createTables();
        
        echo json_encode([
            'success' => true,
            'message' => 'Database installed successfully!',
            'tables_created' => [
                'users', 'agents', 'conversations', 'messages', 
                'tickets', 'system_logs', 'knowledge_base', 'settings'
            ],
            'timestamp' => date('Y-m-d H:i:s')
        ]);
    } else {
        throw new Exception('Failed to connect to database');
    }
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage(),
        'timestamp' => date('Y-m-d H:i:s')
    ]);
}
?>

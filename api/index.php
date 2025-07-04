<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

require_once '../backend/config.php';
require_once '../backend/database.php';

$method = $_SERVER['REQUEST_METHOD'];
$path = explode('/', trim($_SERVER['REQUEST_URI'], '/'));

// Health check
if ($path[1] === 'health') {
    echo json_encode([
        'status' => 'healthy',
        'timestamp' => date('c'),
        'version' => '1.0.0'
    ]);
    exit;
}

// Basic stats endpoint
if ($path[1] === 'stats') {
    echo json_encode([
        'success' => true,
        'data' => [
            'tickets' => ['total_tickets' => 0],
            'agents' => ['total' => 1, 'online' => 0]
        ]
    ]);
    exit;
}

echo json_encode(['message' => 'ALIVE Support API is running!']);
?>
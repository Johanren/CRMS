<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// Permitir preflight (CORS)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// API KEY válida (puedes moverla a DB o ENV)
define('API_KEY_VALIDA', 'RST-API-KEY-9f8d7a6c3b2a');

// Obtener headers
$headers = getallheaders();
$apiKey = $headers['X-API-KEY'] ?? $headers['x-api-key'] ?? null;

// Validar API Key
if (!$apiKey || $apiKey !== API_KEY_VALIDA) {
    http_response_code(401);
    echo json_encode([
        'error' => true,
        'message' => 'API Key inválida o no enviada'
    ]);
    exit;
}

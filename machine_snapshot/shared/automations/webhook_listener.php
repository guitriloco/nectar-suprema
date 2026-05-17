<?php
/**
 * webhook_listener.php
 * Receptor central de webhooks — Kiwify / Hotmart
 * @route POST /automations/webhook_listener.php?source=kiwify|hotmart
 */

require_once __DIR__ . '/automation_controller.php';

// Headers
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Preflight
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Validação básica
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Método não permitido']);
    exit;
}

$source = $_GET['source'] ?? '';
$payload = file_get_contents('php://input');
$data = json_decode($payload, true);

// Log cru do webhook
$logFile = __DIR__ . '/logs/webhook_received_' . date('Y-m-d') . '.json';
file_put_contents($logFile, json_encode([
    'timestamp' => date('Y-m-d H:i:s'),
    'source' => $source,
    'payload' => $data
], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE), FILE_APPEND);

if (!$data) {
    http_response_code(400);
    echo json_encode(['error' => 'Payload inválido']);
    exit;
}

try {
    $controller = new AutomationController();

    switch ($source) {
        case 'kiwify':
            $result = $controller->handleKiwifyWebhook($data);
            break;
        case 'hotmart':
            $result = $controller->handleHotmartWebhook($data);
            break;
        default:
            throw new Exception("Source desconhecido: $source");
    }

    echo json_encode(['status' => 'ok', 'result' => $result]);

} catch (Exception $e) {
    $controller->log('ERROR', $e->getMessage(), $data);
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
<?php
/**
 * process_lead_capture.php
 * Handler do formulário da landing page
 * Endpoint: POST /automations/process_lead_capture.php
 * 
 * Dados esperados (POST):
 * - name: string
 * - email: string
 * - utm_source: string (opcional)
 * - utm_medium: string (opcional)
 * - utm_campaign: string (opcional)
 * - utm_content: string (opcional)
 * - utm_term: string (opcional)
 */

require_once __DIR__ . '/automation_controller.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Método não permitido']);
    exit;
}

// Coletar dados do POST
$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$utmSource = trim($_POST['utm_source'] ?? '');
$utmMedium = trim($_POST['utm_medium'] ?? '');
$utmCampaign = trim($_POST['utm_campaign'] ?? '');
$utmContent = trim($_POST['utm_content'] ?? '');
$utmTerm = trim($_POST['utm_term'] ?? '');

// Validar campos obrigatórios
if (empty($name)) {
    http_response_code(400);
    echo json_encode(['error' => 'Nome é obrigatório']);
    exit;
}

if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['error' => 'Email válido é obrigatório']);
    exit;
}

// Validar UTM (evitar XSS)
$utmSource = preg_replace('/[^a-zA-Z0-9_\-]/', '', $utmSource);
$utmMedium = preg_replace('/[^a-zA-Z0-9_\-]/', '', $utmMedium);
$utmCampaign = preg_replace('/[^a-zA-Z0-9_\-]/', '', $utmCampaign);

// Log da requisição
$logFile = __DIR__ . '/logs/lead_capture_' . date('Y-m-d') . '.json';
file_put_contents($logFile, json_encode([
    'timestamp' => date('Y-m-d H:i:s'),
    'name' => $name,
    'email' => $email,
    'utm' => [
        'source' => $utmSource,
        'medium' => $utmMedium,
        'campaign' => $utmCampaign,
        'content' => $utmContent,
        'term' => $utmTerm
    ]
], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE), FILE_APPEND);

try {
    $controller = new AutomationController();
    
    $result = $controller->triggerLeadCapture(
        $email,
        $name,
        $utmSource,
        $utmMedium,
        $utmCampaign,
        $utmContent,
        $utmTerm
    );

    // Sucesso: retorna JSON para o JS da landing page
    echo json_encode([
        'status' => 'success',
        'message' => 'Lead capturado com sucesso! Verifique seu email.',
        'result' => $result
    ]);

} catch (Exception $e) {
    // Erro: log e responde
    $controller->log('LEAD_CAPTURE_ERROR', $e->getMessage(), [
        'name' => $name,
        'email' => $email
    ]);

    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Erro ao processar sua solicitação. Tente novamente.'
    ]);
}
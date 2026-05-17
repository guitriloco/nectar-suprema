<?php
/**
 * lead_capture_handler.php
 * Handler do formulário da landing page (isca digital)
 * @route POST /lead_capture_handler.php
 */

require_once __DIR__ . '/automation_controller.php';

header('Content-Type: application/json');

// Só aceita POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Método não permitido']);
    exit;
}

// Coletar dados do formulário
$name = trim($_POST['nome'] ?? $_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['telefone'] ?? $_POST['phone'] ?? '');

// UTM params
$utmParams = [
    'utm_source'   => $_POST['utm_source']   ?? $_GET['utm_source']   ?? '',
    'utm_medium'   => $_POST['utm_medium']   ?? $_GET['utm_medium']   ?? '',
    'utm_campaign' => $_POST['utm_campaign'] ?? $_GET['utm_campaign'] ?? '',
    'utm_content'  => $_POST['utm_content']  ?? $_GET['utm_content']  ?? '',
    'utm_term'     => $_POST['utm_term']     ?? $_GET['utm_term']     ?? '',
];

// Validação básica
if (!$name || !$email) {
    http_response_code(400);
    echo json_encode(['error' => 'Nome e email são obrigatórios']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['error' => 'Email inválido']);
    exit;
}

// Sanitização básica
$name = htmlspecialchars(strip_tags($name), ENT_QUOTES, 'UTF-8');
$email = filter_var($email, FILTER_SANITIZE_EMAIL);

try {
    $controller = new AutomationController();

    // Executar WF-01: Captura de Lead
    $result = $controller->wfLeadCaptured($name, $email, $utmParams);

    echo json_encode([
        'status' => 'ok',
        'message' => 'Lead registrado com sucesso! Verifique seu email.',
        'data' => $result
    ]);

} catch (Exception $e) {
    error_log("Lead capture error: " . $e->getMessage());

    // Não mostrar erro técnico ao usuário
    echo json_encode([
        'status' => 'error',
        'message' => 'Algo deu errado. Tente novamente em alguns minutos.'
    ]);
}
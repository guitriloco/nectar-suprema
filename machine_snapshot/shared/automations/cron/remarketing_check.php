#!/usr/bin/env php
<?php
/**
 * cron/remarketing_check.php
 * Verifica leads que passaram pela sequência sem comprar
 * e move para fluxo de remarketing
 *
 * Verificar a cada 1 hora:
 * 0 * * * * php /home/team/shared/automations/cron/remarketing_check.php >> /tmp/remarketing.log 2>&1
 */

require_once __DIR__ . '/../automations/automation_controller.php';

echo "[" . date('Y-m-d H:i:s') . "] Verificando leads para remarketing\n";

$controller = new AutomationController();
$logFile = __DIR__ . '/../logs/automation_log.json';

if (!file_exists($logFile)) {
    echo "Log não encontrado. Nada a fazer.\n";
    exit(0);
}

$logs = file($logFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$processedLeads = [];

foreach ($logs as $logLine) {
    $entry = json_decode($logLine, true);
    if (!$entry) continue;

    // Encontrar leads que completaram sequência (email_07_final enviado) mas não compraram
    if (
        $entry['level'] === 'INFO' &&
        strpos($entry['message'], 'WF-01 completo para') !== false &&
        !strpos($entry['message'], 'comprador') // não comprou
    ) {
        // Extrair email do contexto se disponível
        $email = $entry['context']['email'] ?? null;
        if ($email && !in_array($email, $processedLeads)) {
            $processedLeads[] = $email;
        }
    }
}

$count = count($processedLeads);
echo "Leads encontrados para remarketing: $count\n";

foreach ($processedLeads as $email) {
    try {
        // Em produção real: verificar no RD Station se o lead NÃO comprou
        // aqui apenas logamos como placeholder
        $controller->log('CRON', "Remarketing trigger para: $email");
    } catch (Exception $e) {
        $controller->log('ERROR', "Erro remarketing para $email: " . $e->getMessage());
    }
}

echo "[" . date('Y-m-d H:i:s') . "] Verificação concluída\n";
exit(0);
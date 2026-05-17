<?php
/**
 * Cron: Process Remarketing — detecta leads que passaram pela sequência sem comprar
 * e inicia fluxo de remarketing (WF-03)
 * 
 * Configuração crontab (a cada hora):
 * 0 * * * * php /home/team/shared/automations/cron/process_remarketing.php >> /var/log/remarketing.log 2>&1
 */

require_once __DIR__ . '/../automation_controller.php';

define('DAYS_AFTER_SEQUENCE', 8); // Quantos dias após o último email da sequência

$logFile = __DIR__ . '/../logs/remarketing_cron_' . date('Y-m-d') . '.json';
$log = function(string $msg) use ($logFile) {
    $entry = date('Y-m-d H:i:s') . " — $msg\n";
    file_put_contents($logFile, $entry, FILE_APPEND);
    echo "$entry\n";
};

$log("Iniciando processamento de remarketing...");

try {
    $controller = new AutomationController();

    $scheduleFile = __DIR__ . '/../logs/email_schedule.json';
    
    if (!file_exists($scheduleFile)) {
        $log("Arquivo de schedule não encontrado. Nada a processar.");
        exit(0);
    }

    $schedule = json_decode(file_get_contents($scheduleFile), true) ?? [];
    $now = new DateTime();
    $processed = 0;
    $emailsToProcess = [];

    foreach ($schedule as $index => $item) {
        // Apenas emails da sequência principal (WF-01) que já passaram do dia 8
        if ($item['template'] === 'email_07_final' && $item['status'] === 'sent') {
            $sendAt = new DateTime($item['send_at']);
            $diff = $now->diff($sendAt)->days;

            if ($diff >= DAYS_AFTER_SEQUENCE) {
                $emailsToProcess[$item['email']] = $item;
            }
        }
    }

    // Para cada lead que completou a sequência sem virar comprador
    foreach ($emailsToProcess as $email => $item) {
        // Verificar se não virou comprador (não tem tag comprador)
        $rdstation = new RDStationIntegration();
        $lead = $rdstation->getLead($email);

        $isBuyer = false;
        if ($lead && isset($lead['tags'])) {
            foreach ($lead['tags'] as $tag) {
                if ($tag === 'comprador') {
                    $isBuyer = true;
                    break;
                }
            }
        }

        if (!$isBuyer) {
            $log("Iniciando remarketing para: $email");
            $controller->triggerRemarketingSequence($email, $item['name']);
            $processed++;
        }

        // Remove do schedule original
        $key = array_search($email, array_column($schedule, 'email'));
        if ($key !== false) {
            unset($schedule[$key]);
        }
    }

    // Salva schedule atualizado
    file_put_contents($scheduleFile, json_encode(array_values($schedule), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

    $log("Processados: $processed leads para remarketing");

} catch (Exception $e) {
    $log("ERRO: " . $e->getMessage());
    exit(1);
}
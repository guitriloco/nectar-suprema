<?php
/**
 * cron_process_queue.php
 * Cron job para processar fila de emails agendados
 * 
 * Configuração crontab:
 * * * * * * php /path/to/cron_process_queue.php >> /var/log/email_queue.log 2>&1
 * 
 * Este script deve ser executado a cada minuto pelo cron.
 */

require_once __DIR__ . '/email_sender.php';

$logFile = __DIR__ . '/logs/cron_process_queue.log';

$startTime = microtime(true);

$log = function(string $msg) use ($logFile) {
    $entry = date('Y-m-d H:i:s') . " — $msg\n";
    file_put_contents($logFile, $entry, FILE_APPEND);
    echo $entry;
};

$log("Iniciando processamento da fila de emails...");

try {
    $sender = new EmailSender();
    $count = $sender->processQueue();

    $duration = round((microtime(true) - $startTime) * 1000, 2);

    $log("Processados: $count emails | Tempo: {$duration}ms");

    // Limpar logs antigos (> 30 dias)
    $logsDir = __DIR__ . '/logs';
    if (is_dir($logsDir)) {
        $files = glob("$logsDir/*.json");
        foreach ($files as $file) {
            if (filemtime($file) < time() - (30 * 86400)) {
                @unlink($file);
            }
        }
    }

} catch (Exception $e) {
    $log("ERRO: " . $e->getMessage());
    exit(1);
}
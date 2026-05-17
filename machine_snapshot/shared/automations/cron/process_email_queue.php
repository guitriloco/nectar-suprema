#!/usr/bin/env php
<?php
/**
 * cron/email_queue_worker.php
 * Processa a fila de emails agendados
 *
 * Para adicionar ao crontab:
 * * * * * * php /home/team/shared/automations/cron/email_queue_worker.php >> /tmp/email_cron.log 2>&1
 *
 * Ou a cada 5 minutos:
 * */5 * * * * php /home/team/shared/automations/cron/email_queue_worker.php
 */

require_once __DIR__ . '/../automations/email_sender.php';

echo "[" . date('Y-m-d H:i:s') . "] Iniciando processamento da fila de emails\n";

$emailer = new EmailSender();
$processed = $emailer->processQueue();

echo "[" . date('Y-m-d H:i:s') . "] Emails processados: $processed\n";

exit(0);
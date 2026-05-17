<?php
/**
 * EmailSender
 * Motor de envio de emails com suporte a templates e schedule
 */

class EmailSender {

    private array $smtpConfig;
    private string $templatesDir;

    public function __construct() {
        $this->smtpConfig = [
            'host'     => $_ENV['SMTP_HOST'] ?? 'smtp.seudominio.com',
            'port'     => (int) ($_ENV['SMTP_PORT'] ?? 587),
            'user'     => $_ENV['SMTP_USER'] ?? '',
            'pass'     => $_ENV['SMTP_PASS'] ?? '',
            'from'     => $_ENV['SMTP_FROM'] ?? 'nome@seudominio.com',
            'fromName' => $_ENV['SMTP_FROM_NAME'] ?? 'Lista Quente',
        ];

        $this->templatesDir = __DIR__ . '/templates';
    }

    /**
     * Construir email a partir de template
     */
    public function buildEmail(string $templateName, array $variables = []): string {
        $templateFile = "{$this->templatesDir}/{$templateName}.php";

        if (!file_exists($templateFile)) {
            // Fallback: template inline
            return $this->buildFallbackEmail($templateName, $variables);
        }

        // Extrai variáveis para o escopo do template
        extract($variables);

        // Captura output do template
        ob_start();
        include $templateFile;
        $content = ob_get_clean();

        return $content;
    }

    /**
     * Enviar email agora
     */
    public function send(string $to, string $subject, string $htmlBody): bool {
        // Usa PHPMailer em produção, ou mail() simples em dev
        // Aqui implementamos com mail() para simplicidade

        $headers = [
            'MIME-Version: 1.0',
            'Content-Type: text/html; charset=UTF-8',
            'From: ' . $this->smtpConfig['fromName'] . ' <' . $this->smtpConfig['from'] . '>',
            'Reply-To: ' . $this->smtpConfig['from'],
            'X-Mailer: PHP/' . phpversion(),
        ];

        $result = mail($to, $subject, $htmlBody, implode("\r\n", $headers));

        // Log
        $this->logEmail($to, $subject, $result ? 'sent' : 'failed');

        return $result;
    }

    /**
     * Agendar email para data futura
     * Em produção: usar fila (Redis/Beanstalkd/SQS) + cron worker
     * Para MVP: salvar em JSON e um cron job processa
     */
    public function scheduleEmail(string $to, string $subject, string $htmlBody, DateTime $sendAt): bool {
        $scheduleFile = __DIR__ . '/logs/scheduled_emails.json';

        $queue = [];
        if (file_exists($scheduleFile)) {
            $queue = json_decode(file_get_contents($scheduleFile), true);
        }

        $queue[] = [
            'id' => uniqid('email_'),
            'to' => $to,
            'subject' => $subject,
            'body' => $htmlBody,
            'send_at' => $sendAt->format('Y-m-d H:i:s'),
            'status' => 'pending',
            'created_at' => date('Y-m-d H:i:s'),
        ];

        return (bool) file_put_contents($scheduleFile, json_encode($queue, JSON_UNESCAPED_UNICODE));
    }

    /**
     * Cancelar emails agendados de umlead
     */
    public function cancelScheduledEmails(string $email, array $filters = []): int {
        $scheduleFile = __DIR__ . '/logs/scheduled_emails.json';

        if (!file_exists($scheduleFile)) return 0;

        $queue = json_decode(file_get_contents($scheduleFile), true);
        $originalCount = count($queue);

        $queue = array_filter($queue, function($item) use ($email, $filters) {
            // Manter se NÃO é do email especificado OU se não passa nos filtros
            if ($item['to'] !== $email) return true;

            // Se tem filtro de sequence, verificar
            if (isset($filters['sequence']) && strpos($item['subject'], $filters['sequence']) === false) {
                return true; // Manter se não bate com filtro
            }

            return false; // Remover
        });

        file_put_contents($scheduleFile, json_encode(array_values($queue), JSON_UNESCAPED_UNICODE));

        return $originalCount - count($queue);
    }

    /**
     * Processar fila de emails agendados (chamado por cron)
     * Usage: * * * * * php /path/to/email_sender.php process_queue
     */
    public function processQueue(): int {
        $scheduleFile = __DIR__ . '/logs/scheduled_emails.json';

        if (!file_exists($scheduleFile)) return 0;

        $queue = json_decode(file_get_contents($scheduleFile), true);
        $now = new DateTime();
        $processed = 0;

        $queue = array_map(function($item) use (&$processed, $now) {
            if ($item['status'] !== 'pending') return $item;

            $sendAt = new DateTime($item['send_at']);

            if ($sendAt <= $now) {
                $result = $this->send($item['to'], $item['subject'], $item['body']);
                $item['status'] = $result ? 'sent' : 'failed';
                $item['processed_at'] = $now->format('Y-m-d H:i:s');
                $processed++;
            }

            return $item;
        }, $queue);

        file_put_contents($scheduleFile, json_encode(array_values($queue), JSON_UNESCAPED_UNICODE));

        return $processed;
    }

    private function buildFallbackEmail(string $templateName, array $vars): string {
        $html = "<!DOCTYPE html><html><head><meta charset='UTF-8'><style>";
        $html .= "body{font-family:Arial,sans-serif;max-width:600px;margin:0 auto;padding:20px;}";
        $html .= ".header{background:#FF6B00;padding:20px;text-align:center;color:#fff;font-size:24px;}";
        $html .= ".content{padding:30px 20px;background:#f9f9f9;}";
        $html .= ".cta{background:#FF6B00;color:#fff;text-align:center;padding:20px;margin:20px 0;";
        $html .= "border-radius:8px;}";
        $html .= ".cta a{color:#fff;text-decoration:none;font-size:18px;}";
        $html .= ".footer{text-align:center;padding:20px;font-size:12px;color:#888;}";
        $html .= "</style></head><body>";

        $html .= "<div class='header'>🔥 Lista Quente de Fornecedores</div>";
        $html .= "<div class='content'>";

        $html .= match($templateName) {
            'email_01_boasvindas' => "<h2>Olá {$vars['nome']}, seja bem-vindo(a)! 🎉</h2>"
                . "<p>Você acaba de garantir acesso à <strong>LISTA QUENTE DE FORNECEDORES</strong>.</p>"
                . "<p>Dentro do material você vai encontrar:</p>"
                . "<ul>"
                . "<li>✅ +500 fornecedores verificados (com contato direto)</li>"
                . "<li>✅ 15 nichos mais lucrativos de 2025</li>"
                . "<li>✅ Scripts de vendas testados</li>"
                . "<li>✅ Método passo a passo</li>"
                . "</ul>"
                . "<p>Baixe agora. Aplique amanhã. Success!</p>",

            'email_02_problema' => "<h2>{$vars['nome']}, você se identifica com isso?</h2>"
                . "<p>❌ Comprei curso atrás de curso, mas nada funcionava...</p>"
                . "<p>❌ Eu tinha uma ideia de produto, mas não sabia onde encontrar fornecedores...</p>"
                . "<p>❌ Fiz anúncios no Instagram/Facebook, mandei mensagem pra todo mundo, e nada de venda...</p>"
                . "<p>Amanhã vou te mostrar exatamente como resolver isso.</p>",

            'email_03_solucao' => "<h2>O que você precisa pra fazer dropshipping funcionar</h2>"
                . "<p><strong>1️⃣ FORNECEDOR QUE ENTREGA</strong></p>"
                . "<p><strong>2️⃣ PRODUTO QUE VENDE</strong></p>"
                . "<p><strong>3️⃣ COPY QUE CONVERTE</strong></p>"
                . "<p>E é exatamente isso que está dentro do material que você recebeu.</p>",

            'email_04_provasocial' => "<h2>Olha o que está acontecendo com quem já usa 📣</h2>"
                . "<p><strong>⭐⭐⭐⭐⭐</strong> \"Vendi R\$ 2.300 na primeira semana. O material se pagou no primeiro dia.\" — Mariana C., Rio de Janeiro</p>"
                . "<p><strong>⭐⭐⭐⭐⭐</strong> \"Finalmente um material que funciona no Brasil. Fornecedores reais.\" — Pedro H., Belo Horizonte</p>"
                . "<p><strong>⭐⭐⭐⭐⭐</strong> \"Economizei 3 meses de tentativas e erros. Recomendo demais.\" — Juliana M., Curitiba</p>",

            'email_05_urgencia' => "<h2>⚠️ {$vars['nome']}, isso expira em breve</h2>"
                . "<p>O preço que você viu quando comprou o material? Ele não vai durar pra sempre.</p>"
                . "<p>Quando acabarem as vagas disponíveis, o preço volta pro valor cheio: <strong>R\$ 297,00</strong>.</p>"
                . "<p>Você garante acesso vitalício pelo preço de hoje: <strong>R\$ 97,00</strong>.</p>",

            'email_06_fechamento' => "<h2>🚨 {$vars['nome']}, última chamada</h2>"
                . "<p><strong>OPÇÃO 1:</strong> Ignorar esse email, continuar fazendo o que sempre fez, e obter os mesmos resultados de sempre.</p>"
                . "<p><strong>OPÇÃO 2:</strong> Abrir o material que você já tem, começar a aplicar, e finalmente construir o negócio que você quer.</p>"
                . "<p>A escolha é sua.</p>",

            'email_07_final' => "<h2>Última chance, {$vars['nome']} 🚨</h2>"
                . "<p>Essa é a última vez que você vai ver esse material pelo preço que pagou.</p>"
                . "<p>Amanhã, ou depois, a oferta expira. O preço volta pro normal.</p>"
                . "<p>Se for, <strong>agora é a hora.</strong></p>",

            'purchase_confirmation' => "<h2>🎉 Sua compra foi confirmada!</h2>"
                . "<p>Olá {$vars['nome']}!</p>"
                . "<p>Sua compra do produto <strong>{$vars['produto']}</strong> foi confirmada!</p>"
                . "<p>Você receberá os dados de acesso em instantes.</p>",

            default => "<p>Olá {$vars['nome']}, tudo bem?</p><p>Segue seu conteúdo.</p>"
        };

        $html .= "</div>";
        $html .= "<div class='footer'>© " . date('Y') . " Lista Quente — Todos os direitos reservados.</div>";
        $html .= "</body></html>";

        return $html;
    }

    private function logEmail(string $to, string $subject, string $status): void {
        $logFile = __DIR__ . '/logs/email_log.json';
        $dir = dirname($logFile);

        if (!is_dir($dir)) mkdir($dir, 0755, true);

        $entry = [
            'timestamp' => date('Y-m-d H:i:s'),
            'to' => $to,
            'subject' => $subject,
            'status' => $status,
        ];

        file_put_contents($logFile, json_encode($entry, JSON_UNESCAPED_UNICODE) . "\n", FILE_APPEND);
    }
}

// CLI para processar fila
if (php_sapi_name() === 'cli' && ($argv[1] ?? '') === 'process_queue') {
    $sender = new EmailSender();
    $count = $sender->processQueue();
    echo "Processados: $count emails\n";
}
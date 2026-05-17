<?php
/**
 * automation_controller.php
 * Lógica central de workflows de automação
 */

require_once __DIR__ . '/rdstation_integration.php';
require_once __DIR__ . '/email_sender.php';

class AutomationController {

    private $rdstation;
    private $emailSender;
    private $logFile;

    public function __construct() {
        $this->rdstation = new RDStationIntegration();
        $this->emailSender = new EmailSender();
        $this->logFile = __DIR__ . '/logs/automation_log.json';
    }

    /**
     * Handle Kiwify Webhook
     * Eventos: compra.aprovada, compra.reprovada, acesso.concedido
     */
    public function handleKiwifyWebhook(array $data): array {
        $this->log('KIWIFY_WEBHOOK', 'Recebido', $data);

        $event = $data['event'] ?? '';
        $email = $data['email'] ?? $data['customer']['email'] ?? '';
        $name = $data['customer']['name'] ?? '';
        $productId = $data['product_id'] ?? '';
        $productName = $data['product']['name'] ?? '';
        $price = $data['amount'] ?? $data['product']['price'] ?? 0;
        $paymentMethod = $data['payment_method'] ?? '';

        if (empty($email)) {
            throw new Exception('Email não encontrado no payload Kiwify');
        }

        switch ($event) {
            case 'compra.aprovada':
                return $this->triggerPurchaseConfirmed($email, $name, $productName, $price, 'kiwify');

            case 'compra.reprovada':
                return $this->triggerPurchaseRejected($email);

            case 'acesso.concedido':
                return $this->triggerAccessGranted($email, $productName);

            default:
                $this->log('KIWIFY', "Evento não mapeado: $event", $data);
                return ['event' => $event, 'status' => 'ignored'];
        }
    }

    /**
     * Handle Hotmart Webhook
     * Eventos: PURCHASE_APPROVED, PURCHASE_REFUNDED, MEMBER_ACCESS_PROVIDED
     */
    public function handleHotmartWebhook(array $data): array {
        $this->log('HOTMART_WEBHOOK', 'Recebido', $data);

        // Hotmart envia dados em estrutura específica
        $event = $data['event'] ?? $data['action'] ?? '';
        $email = $data['data']['email'] ?? $data['email'] ?? '';
        $name = $data['data']['name'] ?? $data['name'] ?? '';
        $productId = $data['data']['product']['id'] ?? $data['product_id'] ?? '';
        $productName = $data['data']['product']['name'] ?? $data['product_name'] ?? '';
        $price = $data['data']['price']['value'] ?? $data['amount'] ?? 0;
        $currency = $data['data']['price']['currency'] ?? 'BRL';

        if (empty($email)) {
            throw new Exception('Email não encontrado no payload Hotmart');
        }

        switch ($event) {
            case 'PURCHASE_APPROVED':
            case 'purchase.approved':
                return $this->triggerPurchaseConfirmed($email, $name, $productName, $price, 'hotmart');

            case 'PURCHASE_REFUNDED':
            case 'purchase.refunded':
                return $this->triggerPurchaseRejected($email);

            case 'MEMBER_ACCESS_PROVIDED':
            case 'access.provided':
                return $this->triggerAccessGranted($email, $productName);

            default:
                $this->log('HOTMART', "Evento não mapeado: $event", $data);
                return ['event' => $event, 'status' => 'ignored'];
        }
    }

    /**
     * WF-02: Compra Confirmada
     */
    private function triggerPurchaseConfirmed(string $email, string $name, string $productName, float $price, string $platform): array {
        $this->log('PURCHASE_CONFIRMED', "Email: $email | Plataforma: $platform", [
            'email' => $email,
            'name' => $name,
            'product' => $productName,
            'price' => $price,
            'platform' => $platform
        ]);

        // 1. Atualizar CRM
        $this->rdstation->updateLeadStatus($email, 'comprador');
        $this->rdstation->replaceTag($email, 'lead_quente', 'comprador');

        // 2. Enviar email de confirmação
        $this->emailSender->sendPurchaseConfirmation($email, $name, $productName, $platform);

        // 3. Cancelar sequências ativas (evitar spam de venda)
        $this->rdstation->addTag($email, 'sequencia_cancelada');

        // 4. Registrar conversão (para métricas)
        $this->logConversion($email, $price, $platform);

        return [
            'workflow' => 'WF-02',
            'status' => 'completed',
            'email' => $email
        ];
    }

    /**
     * WF-02: Compra Recusada/Reembolsada
     */
    private function triggerPurchaseRejected(string $email): array {
        $this->log('PURCHASE_REJECTED', "Email: $email", ['email' => $email]);

        $this->rdstation->updateLeadStatus($email, 'reembolsado');
        $this->rdstation->addTag($email, 'reembolsado');

        return [
            'workflow' => 'WF-02',
            'status' => 'rejected',
            'email' => $email
        ];
    }

    /**
     * WF-02: Acesso ao Produto Concedido
     */
    private function triggerAccessGranted(string $email, string $productName): array {
        $this->log('ACCESS_GRANTED', "Email: $email | Produto: $productName", [
            'email' => $email,
            'product' => $productName
        ]);

        // Enviar email de boas-vindas com dados de acesso
        $this->emailSender->sendAccessConfirmation($email, $productName);

        return [
            'workflow' => 'WF-02',
            'status' => 'access_granted',
            'email' => $email
        ];
    }

    /**
     * WF-01: Captura de Lead
     * Chamado pela landing page após submit do formulário
     */
    public function triggerLeadCapture(string $email, string $name, string $utmSource = '', string $utmMedium = '', string $utmCampaign = '', string $utmContent = ''): array {
        $this->log('LEAD_CAPTURE', "Email: $email", [
            'email' => $email,
            'name' => $name,
            'utm_source' => $utmSource,
            'utm_campaign' => $utmCampaign
        ]);

        // 1. Cadastrar lead no RD Station
        $this->rdstation->createOrUpdateLead($email, $name, [
            'utm_source' => $utmSource,
            'utm_medium' => $utmMedium,
            'utm_campaign' => $utmCampaign,
            'utm_content' => $utmContent,
            'origem' => 'landing_page'
        ]);

        // 2. Adicionar tags
        $this->rdstation->addTag($email, 'lead_quente');
        $this->rdstation->addTag($email, 'wf_01_ativo');

        // 3. Enviar Email-01 (Boas-vindas)
        $this->emailSender->sendEmailSequence($email, $name, 1);

        // 4. Agendar demais emails da sequência
        $this->scheduleEmailSequence($email, $name);

        return [
            'workflow' => 'WF-01',
            'status' => 'completed',
            'email' => $email
        ];
    }

    /**
     * Agendar sequência de emails 7 dias
     */
    private function scheduleEmailSequence(string $email, string $name): void {
        $scheduleFile = __DIR__ . '/logs/email_schedule.json';
        $schedule = [];

        if (file_exists($scheduleFile)) {
            $schedule = json_decode(file_get_contents($scheduleFile), true) ?? [];
        }

        $emails = [
            2 => ['subject' => 'Problema', 'template' => 'email_02_problema'],
            4 => ['subject' => 'Solução', 'template' => 'email_03_solucao'],
            5 => ['subject' => 'Prova Social', 'template' => 'email_04_provasocial'],
            6 => ['subject' => 'Urgência', 'template' => 'email_05_urgencia'],
            7 => ['subject' => 'Fechamento', 'template' => 'email_06_fechamento'],
            8 => ['subject' => 'Última chance', 'template' => 'email_07_final']
        ];

        foreach ($emails as $day => $info) {
            $schedule[] = [
                'email' => $email,
                'name' => $name,
                'send_at' => date('Y-m-d H:i:s', strtotime("+$day days")),
                'template' => $info['template'],
                'subject' => $info['subject'],
                'status' => 'scheduled'
            ];
        }

        file_put_contents($scheduleFile, json_encode($schedule, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }

    /**
     * WF-03: Remarketing — não comprou após sequência
     * Chamado via cron job (D+8 após lead não converter)
     */
    public function triggerRemarketingSequence(string $email, string $name): array {
        $this->log('REMARKETING_TRIGGER', "Email: $email", ['email' => $email]);

        // Mover para lista remarketing
        $this->rdstation->replaceTag($email, 'wf_01_ativo', 'remarketing_1');
        $this->rdstation->addTag($email, 'wf_03_ativo');

        // Agendar sequência remarketing
        $this->scheduleRemarketingSequence($email, $name);

        return [
            'workflow' => 'WF-03',
            'status' => 'scheduled',
            'email' => $email
        ];
    }

    /**
     * Agendar sequência remarketing (D+10, D+14, D+21)
     */
    private function scheduleRemarketingSequence(string $email, string $name): void {
        $scheduleFile = __DIR__ . '/logs/remarketing_schedule.json';
        $schedule = [];

        if (file_exists($scheduleFile)) {
            $schedule = json_decode(file_get_contents($scheduleFile), true) ?? [];
        }

        $emails = [
            10 => ['template' => 'email_remarketing_01'],
            14 => ['template' => 'email_remarketing_02'],
            21 => ['template' => 'email_remarketing_03']
        ];

        foreach ($emails as $day => $info) {
            $schedule[] = [
                'email' => $email,
                'name' => $name,
                'send_at' => date('Y-m-d H:i:s', strtotime("+$day days")),
                'template' => $info['template'],
                'workflow' => 'WF-03',
                'status' => 'scheduled'
            ];
        }

        file_put_contents($scheduleFile, json_encode($schedule, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }

    /**
     * WF-04: Carrinho Abandonado
     * Chamado quando pixel detecta visita à página de vendas sem compra
     */
    public function triggerCartAbandoned(string $email, string $name, float $cartValue): array {
        $this->log('CART_ABANDONED', "Email: $email | Valor: $cartValue", [
            'email' => $email,
            'cart_value' => $cartValue
        ]);

        $this->rdstation->addTag($email, 'carrinho_abandonado');
        $this->rdstation->updateLeadStatus($email, 'carrinho_abandonado');

        // Email de recuperação (1h)
        $this->emailSender->scheduleEmail($email, $name, 'email_carrinho_abandonado', strtotime('+1 hour'));

        // Email de urgência (24h)
        $this->emailSender->scheduleEmail($email, $name, 'email_carrinho_urgencia', strtotime('+24 hours'));

        return [
            'workflow' => 'WF-04',
            'status' => 'triggered',
            'email' => $email
        ];
    }

    /**
     * Log de conversão para métricas
     */
    private function logConversion(string $email, float $price, string $platform): void {
        $convFile = __DIR__ . '/logs/conversions_' . date('Y-m') . '.json';
        $conversions = [];

        if (file_exists($convFile)) {
            $conversions = json_decode(file_get_contents($convFile), true) ?? [];
        }

        $conversions[] = [
            'timestamp' => date('Y-m-d H:i:s'),
            'email' => $email,
            'price' => $price,
            'platform' => $platform
        ];

        file_put_contents($convFile, json_encode($conversions, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }

    /**
     * Log centralizado
     */
    public function log(string $type, string $message, array $context = []): void {
        $entry = [
            'timestamp' => date('Y-m-d H:i:s'),
            'type' => $type,
            'message' => $message,
            'context' => $context
        ];

        $logLine = json_encode($entry, JSON_UNESCAPED_UNICODE) . "\n";
        file_put_contents($this->logFile, $logLine, FILE_APPEND);
    }
}
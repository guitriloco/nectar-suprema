<?php
/**
 * rdstation_integration.php
 * Cliente RD Station API v3
 * Gerenciamento de leads, tags e automações
 */

require_once __DIR__ . '/config/rdstation_config.php';

class RDStationIntegration {

    private $apiKey;
    private $workspaceUuid;
    private $baseUrl = 'https://api.rdstation.com/v1';

    public function __construct() {
        $config = require __DIR__ . '/config/rdstation_config.php';
        $this->apiKey = $config['RD_STATION_API_KEY'];
        $this->workspaceUuid = $config['RD_STATION_WORKSPACE_UUID'];
    }

    /**
     * Criar ou atualizar lead
     */
    public function createOrUpdateLead(string $email, string $name, array $customFields = []): array {
        $url = $this->baseUrl . '/integrations/v1/contacts';

        $payload = [
            'email' => $email,
            'name' => $name,
            'custom_fields' => $customFields
        ];

        return $this->post($url, $payload);
    }

    /**
     * Buscar lead por email
     */
    public function getLead(string $email): ?array {
        $url = $this->baseUrl . '/integrations/v1/contacts/' . urlencode($email);
        $result = $this->get($url);

        return $result['contact'] ?? null;
    }

    /**
     * Atualizar status do lead (cf_status)
     */
    public function updateLeadStatus(string $email, string $status): array {
        $url = $this->baseUrl . '/integrations/v1/contacts/' . urlencode($email);

        $payload = [
            'custom_fields' => [
                'cf_status' => $status
            ]
        ];

        return $this->patch($url, $payload);
    }

    /**
     * Adicionar tag ao lead
     */
    public function addTag(string $email, string $tag): array {
        $url = $this->baseUrl . '/integrations/v1/contacts/' . urlencode($email) . '/tags';

        $payload = [
            'tag' => $tag
        ];

        return $this->post($url, $payload);
    }

    /**
     * Substituir uma tag por outra (replaceTag)
     */
    public function replaceTag(string $email, string $oldTag, string $newTag): array {
        // Primeiro.remove a tag antiga
        $this->removeTag($email, $oldTag);
        // Depois adiciona a nova
        return $this->addTag($email, $newTag);
    }

    /**
     * Remover tag do lead
     */
    public function removeTag(string $email, string $tag): array {
        $url = $this->baseUrl . '/integrations/v1/contacts/' . urlencode($email) . '/tags/' . urlencode($tag);

        return $this->delete($url);
    }

    /**
     * Criar oportunidade/cNegócio no pipeline
     */
    public function createOpportunity(string $email, string $productName, float $value): array {
        $url = $this->baseUrl . '/opportunities';

        $payload = [
            'contact_email' => $email,
            'title' => "Venda - $productName",
            'deal_value' => $value,
            'pipeline' => 'vendas_dropshipping',
            'stage' => 'qualified'
        ];

        return $this->post($url, $payload);
    }

    /**
     * Atualizar oportunidade para ganha
     */
    public function closeOpportunityWon(string $email, string $productName): array {
        $url = $this->baseUrl . '/opportunities';

        // Encontrar e atualizar
        $opportunities = $this->getOpportunities($email);
        foreach ($opportunities as $opp) {
            if ($opp['title'] === "Venda - $productName") {
                $patchUrl = $url . '/' . $opp['id'];
                return $this->patch($patchUrl, ['stage' => 'won']);
            }
        }

        return ['status' => 'not_found'];
    }

    /**
     * Buscar oportunidades de um lead
     */
    public function getOpportunities(string $email): array {
        $url = $this->baseUrl . '/opportunities?contact_email=' . urlencode($email);
        $result = $this->get($url);

        return $result['opportunities'] ?? [];
    }

    /**
     * Converter lead (mover no funil)
     */
    public function convertLead(string $email, string $funilName, string $stage): array {
        $url = $this->baseUrl . '/funnels/' . urlencode($funilName) . '/contacts';

        $payload = [
            'email' => $email,
            'stage' => $stage
        ];

        return $this->post($url, $payload);
    }

    /**
     * Executar automação pelo nome
     */
    public function triggerAutomation(string $automationUuid, array $payload = []): array {
        $url = $this->baseUrl . '/automations/' . $automationUuid . '/trigger';

        return $this->post($url, array_merge(['email' => $payload['email'] ?? ''], $payload));
    }

    // ─────────────────────────────────────────────────────────────────────
    // Métodos HTTP internos
    // ─────────────────────────────────────────────────────────────────────

    private function post(string $url, array $payload): array {
        $ch = curl_init($url);

        curl_setopt_array($ch, [
            CURLOPT_POST => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer ' . $this->apiKey,
                'Content-Type: application/json'
            ],
            CURLOPT_POSTFIELDS => json_encode($payload)
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $this->logApi($httpCode, 'POST', $url, $payload);

        return json_decode($response, true) ?? ['status' => $httpCode];
    }

    private function get(string $url): array {
        $ch = curl_init($url);

        curl_setopt_array($ch, [
            CURLOPT_GET => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer ' . $this->apiKey,
                'Content-Type: application/json'
            ]
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $this->logApi($httpCode, 'GET', $url, []);

        return json_decode($response, true) ?? ['status' => $httpCode];
    }

    private function patch(string $url, array $payload): array {
        $ch = curl_init($url);

        curl_setopt_array($ch, [
            CURLOPT_CUSTOMREQUEST => 'PATCH',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer ' . $this->apiKey,
                'Content-Type: application/json'
            ],
            CURLOPT_POSTFIELDS => json_encode($payload)
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $this->logApi($httpCode, 'PATCH', $url, $payload);

        return json_decode($response, true) ?? ['status' => $httpCode];
    }

    private function delete(string $url): array {
        $ch = curl_init($url);

        curl_setopt_array($ch, [
            CURLOPT_CUSTOMREQUEST => 'DELETE',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer ' . $this->apiKey,
                'Content-Type: application/json'
            ]
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $this->logApi($httpCode, 'DELETE', $url, []);

        return json_decode($response, true) ?? ['status' => $httpCode];
    }

    private function logApi(int $httpCode, string $method, string $url, array $payload): void {
        $logFile = __DIR__ . '/logs/rdstation_api_' . date('Y-m-d') . '.json';
        $entry = [
            'timestamp' => date('Y-m-d H:i:s'),
            'http_code' => $httpCode,
            'method' => $method,
            'url' => $url,
            'payload' => $payload
        ];
        file_put_contents($logFile, json_encode($entry, JSON_UNESCAPED_UNICODE) . "\n", FILE_APPEND);
    }
}
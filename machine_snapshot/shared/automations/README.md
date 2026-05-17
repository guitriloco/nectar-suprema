# Sistema de Automação — Dropshipping + Infoprodutos
## Agente: automation_specialist

---

## 1. Visão Geral da Arquitetura

```
┌──────────────────────────────────────────────────────────────────────┐
│                    KIWIFY / HOTMART (Pagamentos)                      │
│   webhook_compra.php → POST /automations/webhook_listener.php         │
└──────────────────────────────────────────────────────────────────────┘
                                    │
                                    ▼
┌──────────────────────────────────────────────────────────────────────┐
│              AUTOMATION CONTROLLER (webhook_listener.php)              │
│                                                                          │
│  • Recebe eventos: COMPRA, LEAD_CAPTURE, ACESSO_PRODUTO               │
│  • Valida payload                                                      │
│  • Dispara ações para cada serviço                                      │
│  • Loga tudo em automation_log.json                                    │
└──────────────────────────────────────────────────────────────────────┘
          │           │           │           │
          ▼           ▼           ▼           ▼
┌─────────────┐ ┌─────────────┐ ┌─────────────┐ ┌─────────────┐
│ RD Station  │ │   E-mail    │ │  Remarketing│ │   Entrega   │
│  (CRM/API)  │ │  (Sequência)│ │  (Pixels)   │ │ (Produto)   │
└─────────────┘ └─────────────┘ └─────────────┘ └─────────────┘
```

---

## 2. Workflows de Automação

### WF-01: Captura de Lead (Isca Digital)
```
TRIGGER: Lead preenche landing page (email entregue)
AÇÃO:
  1. Gravar lead no RD Station (nome, email, origem UTM)
  2. Adicionar tag "lead_quente" no RD Station
  3. Enviar Email-01 (Boas-vindas + entrega PDF)
  4. Agendar Email-02 para D+2
  5. Agendar Email-03 para D+4
  6. Agendar Email-04 para D+5
  7. Agendar Email-05 para D+6
  8. Agendar Email-06 para D+7 (OFERTA)
  9. Agendar Email-07 para D+8 (LAST CALL)
  10. Criar evento de remarketing (Facebook Pixel)
STATUS: Ativo
```

### WF-02: Compra Confirmada
```
TRIGGER: Kiwify/Hotmart POST webhook (status=pago)
AÇÃO:
  1. Baixar status lead para "comprador" no RD Station
  2. Trocar tag "lead_quente" → "comprador"
  3. Enviar email de confirmação + dados de acesso ao produto
  4. Removerlead dos sequenciais de venda (evitar spam)
  5. Trigger pixel de COMPRA no Facebook
  6. Criar entrada no log de entrega
STATUS: Ativo
```

### WF-03: Remarketing — Não Comprou (Pós-Sequência)
```
TRIGGER: Lead passou pela sequência (D+8) sem comprar
AÇÃO:
  1. Mover lead para lista "remarketing_1"
  2. Enviar sequência remarketing (D+10, D+14, D+21)
  3. Excluir de sequências ativas de venda
  4. Aplicaraudience custom no Facebook via pixel
STATUS: Ativo
```

### WF-04: Remarketing — Carrinho Abandonado
```
TRIGGER: Pixel detecta visita a página de vendas sem compra
AÇÃO:
  1. Adicionar lead à audience "carrinho_abandonado"
  2. Enviar email de recuperação em 1h
  3. Enviar email de urgência em 24h
  4. Trigger ads de remarketing no Facebook
STATUS: Ativo
```

### WF-05: Upsell / Cross-sell (Pós-Compra)
```
TRIGGER: Compra confirmada (D+0)
AÇÃO:
  1. Enviar email upsell no D+3
  2. Enviar email cross-sell no D+7
  3. Aplicar tag "interessado_upsell" se abriu emails
STATUS: Ativo
```

---

## 3. Plataforma de Email — Configuração

### Provedores Suportados
| Provedor | Endpoint | Status |
|---|---|---|
| RD Station | api.rdstation.com | ✅ Configurado |
| Mailchimp | api.mailchimp.com | ✅ Suportado |
| ActiveCampaign | api.activecampaign.com | ✅ Suportado |
| Envio Direto (SMTP) | localhost | ✅ Configurado |

### Configuração RD Station
```php
// rdstation_config.php
RD_STATION_API_KEY=seu_api_key_aqui
RD_STATION_WORKSPACE_UUID=seu_workspace_uuid

// Tags usadas:
lead_quente       → Lead captado via isca digital
comprador          → Completed purchase
remarketing_1      → Did not buy after sequence
carrinho_abandonado → Visited checkout, no purchase
```

---

## 4. Sequências de Email Configuradas

### Sequência 7 Dias — Principal (WF-01)
| Dia | Email | Assunto | Tag |
|---|---|---|---|
| D+0 | Boas-vindas | "Sua lista chegou! 📦 + algo inesperado..." | email_01_boasvindas |
| D+2 | Problema | "[NOME], você se identifica com isso?" | email_02_problema |
| D+4 | Solução | "O que você precisa pra fazer dropshipping funcionar" | email_03_solucao |
| D+5 | Prova Social | "Olha o que está acontecendo com quem já usa 📣" | email_04_provasocial |
| D+6 | Urgência | "⚠️ [NOME], isso expira em breve" | email_05_urgencia |
| D+7 | Fechamento | "🚨 [NOME], última chamada" | email_06_fechamento |
| D+8 | Final | "Última chance, [NOME] 🚨" | email_07_final |

### Sequência Remarketing (WF-03)
| Dia | Email | Assunto |
|---|---|---|
| D+10 | Desconto especial | "Uma oferta exclusiva pra você..." |
| D+14 | Estudo de caso | "Como o João fez R$5.000 em 30 dias" |
| D+21 | Urgência final | "É agora ou nunca" |

### Sequência Carrinho Abandonado (WF-04)
| Tempo | Email | Assunto |
|---|---|---|
| 1h | Lembrete | "Esqueceu algo? 🔥" |
| 24h | Urgência | "Ainda está lá... mas não por muito tempo" |

---

## 5. Webhooks — Kiwify & Hotmart

### Kiwify Webhook
```
URL: https://seudominio.com/automations/webhook_listener.php?source=kiwify
Eventos: compra.aprovada, compra.reprovada, acesso.concedido
Método: POST
Headers: Content-Type: application/json
```

### Hotmart Webhook
```
URL: https://seudominio.com/automations/webhook_listener.php?source=hotmart
Eventos: PURCHASE_APPROVED, PURCHASE_REFUNDED, MEMBER_ACCESS_PROVIDED
Método: POST
Headers: Authorization: Bearer {token}
```

---

## 6. Pixels de Rastreamento

### Facebook Pixel
| Evento | Trigger | Dado extra |
|---|---|---|
| PageView | landing_page.php | - |
| Lead | form_submit_quiz.php | content_category: lead_quente |
| ViewContent | checkout page | value: price |
| InitiateCheckout | checkout_open | value: price |
| Purchase | webhook purchase confirmed | value: price, currency: BRL |
| CustomEvent | email_opened | event: email_opened |

### UTM Parameters
```
utm_source=instagram|facebook|tiktok|whatsapp
utm_medium=social|cpc|status|dm
utm_campaign=lista_quente_2025|nicho_solar_2025
utm_content={ad_id}|{creative_id}
utm_term={keyword}
```

---

## 7. Arquivos do Sistema

```
automations/
├── webhook_listener.php       # Receptor principal de webhooks
├── automation_controller.php  # Lógica central de workflows
├── kiwify_webhook.php         # Handler específico Kiwify
├── hotmart_webhook.php        # Handler específico Hotmart
├── rdstation_integration.php  # Cliente RD Station API
├── email_sender.php           # Motor de envio de emails
├── templates/
│   ├── email_01_boasvindas.php
│   ├── email_02_problema.php
│   ├── email_03_solucao.php
│   ├── email_04_provasocial.php
│   ├── email_05_urgencia.php
│   ├── email_06_fechamento.php
│   ├── email_07_final.php
│   ├── email_remarketing_01.php
│   ├── email_remarketing_02.php
│   ├── email_carrinho_abandonado.php
│   └── email_upsell.php
├── config/
│   ├── rdstation_config.php
│   ├── kiwify_config.php
│   ├── hotmart_config.php
│   └── smtp_config.php
└── logs/
    └── automation_log.json
```

---

## 8. Checklist de Implementação

### Fase 1 — Infraestrutura
- [x] Estrutura de arquivos criada
- [ ] Configurar credenciais RD Station
- [ ] Configurar credenciais Kiwify (API key)
- [ ] Configurar credenciais Hotmart (Webhook token)
- [ ] Configurar SMTP ou provedores de email

### Fase 2 — Webhooks
- [ ] Testar webhook Kiwify (compra.aprovada)
- [ ] Testar webhook Hotmart (PURCHASE_APPROVED)
- [ ] Validar assinatura do webhook
- [ ] Configurar SSL (https://)

### Fase 3 — Sequências
- [ ] Implementar Email-01 a Email-07
- [ ] Implementar sequências remarketing
- [ ] Implementar sequência carrinho abandonado
- [ ] Testar personalização [NOME]

### Fase 4 — Integração CRM
- [ ] Integrar RD Station (tags, lead score)
- [ ] Configurar lead scoring
- [ ] Configurar CRM pipeline stages

### Fase 5 — Remarketing
- [ ] Configurar Facebook Pixel (eventos)
- [ ] Configurar audience自动 (não comprou)
- [ ] Configurar Google Analytics Goals

### Fase 6 — Entrega
- [ ] Configurar entrega automática de produto
- [ ] Testar fluxo completo (lead → compra)
- [ ] Testar email de entrega pós-compra

---

## 9. Variáveis de Ambiente

```bash
KIWIFY_API_KEY=seu_kiwify_api_key
KIWIFY_PRODUCT_ID=seu_product_id

HOTMART_WEBHOOK_TOKEN=seu_hotmart_token
HOTMART_PRODUCT_ID=seu_hotmart_product_id

RD_STATION_API_KEY=seu_rd_api_key
RD_STATION_WORKSPACE_UUID=seu_rd_workspace

SMTP_HOST=smtp.seudominio.com
SMTP_PORT=587
SMTP_USER=seu@email.com
SMTP_PASS=sua_senha
SMTP_FROM=nome@seudominio.com

FACEBOOK_PIXEL_ID=seu_pixel_id
FACEBOOK_ACCESS_TOKEN=seu_token

DB_HOST=localhost
DB_NAME=automations
DB_USER=automations_user
DB_PASS=sua_senha_db
```

---

*Sistema de automação — automation_specialist*
*Versão 1.0 — 2025*
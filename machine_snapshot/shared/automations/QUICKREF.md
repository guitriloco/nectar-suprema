# QUICK REF — Automação Lista Quente
## Referência rápida para operador

---

## Endpoints

| Endpoint | Método | Descrição |
|---|---|---|
| `/automations/webhook_listener.php?source=kiwify` | POST | Webhook Kiwify |
| `/automations/webhook_listener.php?source=hotmart` | POST | Webhook Hotmart |
| `/automations/lead_capture_handler.php` | POST | Captura landing page |
| `/automations/landing-page-quente.php` | GET | Página de captura (isca) |

---

## Workflows

| ID | Nome | Gatilho | Ações |
|---|---|---|---|
| WF-01 | Captura Lead | Form submission | CRM + Email-01 + sequenciar |
| WF-02 | Compra Confirmada | Webhook compra.aprovada | CRM + entrega + pixel |
| WF-02b | Compra Negada | Webhook compra.reprovada | Atualizar CRM |
| WF-02c | Acesso Concedido | Webhook acesso.concedido | Email de acesso |
| WF-03 | Remarketing | Após sequência sem compra | CRM + sequência remarketing |
| WF-04 | Carrinho Abandonado | Pixel abandono | Email 1h + 24h |
| WF-05 | Upsell | D+3 pós-compra | Email upsell |

---

## Tags RD Station

```
lead_quente          → Captado via isca digital
comprador            → Fez purchase
remarketing_1        → Não comprou após sequência
carrinho_abandonado  → Visitou checkout sem comprar
```

---

## Sequência de Emails (7 dias)

```
D+0  → email_01_boasvindas    (Boas-vindas + entrega)
D+2  → email_02_problema      (Agitar dor)
D+4  → email_03_solucao      (3 pilares)
D+5  → email_04_provasocial (Depoimentos)
D+6  → email_05_urgencia     (Preço expira)
D+7  → email_06_fechamento  (Última chamada AM)
D+8  → email_07_final       (Last call)
```

---

## Logs

```
/logs/automation_log.json         ← Todos os eventos de workflow
/logs/webhook_received_{date}.json ← Webhooks recebidos
/logs/email_log.json               ← Envios de email
/logs/scheduled_emails.json       ← Fila de emails programados
```

---

## UTM Params

```
utm_source   = instagram | facebook | tiktok | whatsapp
utm_medium   = social | cpc | status | dm
utm_campaign = lista_quente_2025 | nicho_solar_2025
```

---

## Crons

```bash
# Fila de emails (a cada minuto)
php /automations/cron/process_email_queue.php

# Remarketing check (a cada hora)
php /automations/cron/remarketing_check.php
```

---

## Pixel Events

```
Lead              → Form submission
PageView         → Landing page
ViewContent      → Página de vendas
InitiateCheckout → Checkout aberto
Purchase         → Compra confirmada (webhook)
CustomEvent      → Email aberto
```

---

## Nichos Prioritários

1. ☀️ Energia Solar (margem 70-150%)
2. 📱 Acessórios Celular (margem 40-80%)
3. 💡 Iluminação LED (margem 60-130%)
4. 🐶 Pets (margem 35-60%)
5. 🏠 Organização Doméstica (margem 50-120%)

---

*Versão 1.0 — 2025*
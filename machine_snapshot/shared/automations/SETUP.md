# Setup do Sistema de Automação — Lista Quente

## 1. Configuração Inicial

### 1.1 Credenciais

```bash
# Copiar arquivo de exemplo
cp /home/team/shared/automations/.env.example /home/team/shared/automations/.env

# Editar com suas credenciais reais
nano /home/team/shared/automations/.env
```

### 1.2 Permissões

```bash
chmod +x /home/team/shared/automations/cron/*.php
chmod 755 /home/team/shared/automations/logs
chmod 644 /home/team/shared/automations/logs/*.json
```

## 2. Configurar Webhooks nas Plataformas

### 2.1 Kiwify

1. Acesse: https://app.kiwify.com.br/area-do-produtor
2. Vá em: Configurações → Webhooks
3. Adicione endpoint:
   ```
   https://www.seudominio.com/automations/webhook_listener.php?source=kiwify
   ```
4. Selecione eventos:
   - `compra.aprovada`
   - `compra.reprovada`
   - `acesso.concedido`
5. Copie o **Webhook Secret** e cole no `.env`

### 2.2 Hotmart

1. Acesse: https://app.hotmart.com/products
2. Vá em: Configurações → Webhooks
3. Adicione endpoint:
   ```
   https://www.seudominio.com/automations/webhook_listener.php?source=hotmart
   ```
4. Selecione eventos:
   - `PURCHASE_APPROVED`
   - `PURCHASE_REFUNDED`
   - `MEMBER_ACCESS_PROVIDED`
5. Copie o **Webhook Token** e cole no `.env`

## 3. Configurar RD Station

1. Acesse: https://app.rdstation.com
2. Vá em: Configurações → API e Integrações
3. Gere uma **API Key**
4. Copie o **Workspace UUID** (encontrado nas configurações da conta)
5. Cole ambos no `.env`

## 4. Configurar Cron Jobs

Adicione ao crontab do servidor:

```bash
crontab -e
```

```cron
# Processar fila de emails a cada minuto
* * * * * php /home/team/shared/automations/cron/process_email_queue.php >> /tmp/email_cron.log 2>&1

# Verificar leads para remarketing a cada hora
0 * * * * php /home/team/shared/automations/cron/remarketing_check.php >> /tmp/remarketing.log 2>&1

# Limpar logs antigos semanalmente
0 3 * * 0 find /home/team/shared/automations/logs -name "*.json" -mtime +30 -delete
```

## 5. Configurar Facebook Pixel

1. Acesse: https://business.facebook.com
2. Vá em:pixel do Facebook
3. Anote o **ID do Pixel**
4. Cole no `.env` como `FACEBOOK_PIXEL_ID`
5. Configure os eventos desejados no `automation_controller.php`

## 6. Testar o Sistema

### Teste de Webhook Kiwify:

```bash
curl -X POST https://seudominio.com/automations/webhook_listener.php?source=kiwify \
  -H "Content-Type: application/json" \
  -d '{
    "event": "compra.aprovada",
    "customer": {"name": "Teste Silva", "email": "teste@email.com"},
    "product": {"name": "Lista Quente"},
    "transaction": {"id": "TEST123", "price": 97}
  }'
```

### Teste de Webhook Hotmart:

```bash
curl -X POST https://seudominio.com/automations/webhook_listener.php?source=hotmart \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer SEU_TOKEN" \
  -d '{
    "event": "PURCHASE_APPROVED",
    "data": {
      "buyer": {"name": "Teste Silva", "email": "teste@email.com"},
      "product": {"name": "Lista Quente"},
      "amount": {"value": 97},
      "purchase": {"id": "TEST123"}
    }
  }'
```

### Teste de Captura de Lead:

```bash
curl -X POST https://seudominio.com/automations/lead_capture_handler.php \
  -d "nome=Teste&email=teste@email.com&utm_source=instagram"
```

## 7. Verificar Logs

```bash
# Ver logs de automação
cat /home/team/shared/automations/logs/automation_log.json | jq

# Ver logs de webhooks
cat /home/team/shared/automations/logs/webhook_received_$(date +%Y-%m-%d).json | jq

# Ver logs de emails
cat /home/team/shared/automations/logs/email_log.json | jq

# Ver fila de emails agendados
cat /home/team/shared/automations/logs/scheduled_emails.json | jq
```

## 8. Checklist de Produção

- [ ] SSL configurado (HTTPS)
- [ ] Arquivo `.env` preenchido com credenciais reais
- [ ] Webhooks configurados no Kiwify
- [ ] Webhooks configurados no Hotmart
- [ ] RD Station API Key configurada
- [ ] Facebook Pixel ID configurado
- [ ] Cron jobs ativos
- [ ] SMTP configurado e testado
- [ ] Landing page publicada
- [ ] Página de vendas configurada
- [ ] Links de checkout funcionais
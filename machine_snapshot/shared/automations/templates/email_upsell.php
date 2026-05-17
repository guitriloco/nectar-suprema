<?php
/**
 * Email Upsell — Pós-Compra (D+3)
 */
$nome = $nome ?? 'Cliente';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sua próxima etapa 🚀</title>
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 0; background: #f4f4f4; }
        .header { background: linear-gradient(135deg, #FF6B00 0%, #ff8c00 100%); padding: 40px 30px; text-align: center; color: white; }
        .content { background: white; padding: 40px 30px; }
        .content h2 { font-size: 22px; color: #1a1a2e; margin-top: 0; }
        .content p { font-size: 16px; line-height: 1.7; color: #333; }
        .bonus { background: #fff8f2; border: 2px solid #FF6B00; border-radius: 12px; padding: 20px; margin: 20px 0; }
        .bonus h3 { margin-top: 0; font-size: 18px; color: #FF6B00; }
        .cta { background: linear-gradient(135deg, #FF6B00 0%, #ff8c00 100%); color: white; text-align: center; padding: 25px; border-radius: 12px; margin: 25px 0; }
        .cta a { color: white; text-decoration: none; font-size: 18px; font-weight: bold; }
        .footer { text-align: center; padding: 25px; font-size: 13px; color: #888; }
    </style>
</head>
<body>
    <div class="header">
        <h1>🚀 Olá <?= htmlspecialchars($nome) ?>!</h1>
    </div>
    <div class="content">
        <h2>Sua próxima etapa pra lucrar mais</h2>
        <p>Você já tem a Lista Quente de Fornecedores. Já conhece os melhores fornecedores. Já tem os scripts de venda.</p>
        <p>Mas você sabe qual é o próximo passo pra multiplicar seus resultados?</p>
        <p>É usar o <strong>Método Completo de Dropshipping</strong> — a formação que vai te mostrar passo a passo como transformar fornecedores em vendas.</p>
        <div class="bonus">
            <h3>🎁 Bônus exclusivo pra quem já é cliente:</h3>
            <p>+200 fornecedores extras (atualizado Janeiro 2025)</p>
            <p> checklist de produtos winners</p>
            <p>+Acesso ao grupo VIP do WhatsApp</p>
        </div>
        <p>Esse bônus é só pra você. Não encontra em outro lugar.</p>
        <div class="cta">
            <a href="https://www.seudominio.com/upsell">QUERO APRIMORAR MEU MÉTODO</a>
        </div>
        <p>Um abraço,<br>[Seu nome]</p>
    </div>
    <div class="footer">
        <p>© <?= date('Y') ?> Lista Quente</p>
    </div>
</body>
</html>
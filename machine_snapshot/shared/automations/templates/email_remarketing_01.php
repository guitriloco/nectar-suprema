<?php
/**
 * Email Remarketing 01 — Desconto especial (D+10)
 */
$nome = $nome ?? 'Cliente';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Uma oferta exclusiva pra você...</title>
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 0; background: #f4f4f4; }
        .header { background: linear-gradient(135deg, #FF6B00 0%, #ff8c00 100%); padding: 40px 30px; text-align: center; color: white; }
        .content { background: white; padding: 40px 30px; }
        .content h2 { font-size: 22px; color: #1a1a2e; margin-top: 0; }
        .content p { font-size: 16px; line-height: 1.7; color: #333; }
        .cta { background: linear-gradient(135deg, #FF6B00 0%, #ff8c00 100%); color: white; text-align: center; padding: 25px; border-radius: 12px; margin: 25px 0; }
        .cta a { color: white; text-decoration: none; font-size: 18px; font-weight: bold; }
        .footer { text-align: center; padding: 25px; font-size: 13px; color: #888; }
    </style>
</head>
<body>
    <div class="header">
        <h1>🎁 Uma oferta especial pra você</h1>
    </div>
    <div class="content">
        <h2>Olá <?= htmlspecialchars($nome) ?>!</h2>
        <p>Eu sei que você baixou a Lista Quente de Fornecedores mas ainda não começou a usar.</p>
        <p>Antes de mais nada: sem pressão.</p>
        <p>Mas preciso te contar uma coisa: eu abri uma exceção especial.</p>
        <p>Por tempo limitado, você pode garantir acesso completo ao material pelo preço que você já viu — sem aumentar.</p>
        <p>Isso é só pra você. Não compartilha com ninguém.</p>
        <div class="cta">
            <a href="https://www.seudominio.com/checkout">QUERO APROVEITAR ESTA OFERTA</a>
        </div>
        <p>Um abraço,<br>[Seu nome]</p>
    </div>
    <div class="footer">
        <p>© <?= date('Y') ?> Lista Quente</p>
    </div>
</body>
</html>
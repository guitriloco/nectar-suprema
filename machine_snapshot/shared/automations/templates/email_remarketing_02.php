<?php
/**
 * Email Remarketing 02 — Estudo de caso (D+14)
 */
$nome = $nome ?? 'Cliente';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Como o João fez R$5.000 em 30 dias</title>
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 0; background: #f4f4f4; }
        .header { background: linear-gradient(135deg, #FF6B00 0%, #ff8c00 100%); padding: 40px 30px; text-align: center; color: white; }
        .content { background: white; padding: 40px 30px; }
        .content h2 { font-size: 22px; color: #1a1a2e; margin-top: 0; }
        .content p { font-size: 16px; line-height: 1.7; color: #333; }
        .story { background: #f9f9f9; border-radius: 12px; padding: 25px; margin: 20px 0; }
        .story p { margin: 10px 0; }
        .cta { background: linear-gradient(135deg, #FF6B00 0%, #ff8c00 100%); color: white; text-align: center; padding: 25px; border-radius: 12px; margin: 25px 0; }
        .cta a { color: white; text-decoration: none; font-size: 18px; font-weight: bold; }
        .footer { text-align: center; padding: 25px; font-size: 13px; color: #888; }
    </style>
</head>
<body>
    <div class="header">
        <h1>💰 Como o João fez R$5.000 em 30 dias</h1>
    </div>
    <div class="content">
        <h2>Olá <?= htmlspecialchars($nome) ?>,</h2>
        <p>Vou te contar uma história.</p>
        <div class="story">
            <p>Há 6 meses, o João (nome fictício) me procurou desesperado. Tinha tentado dropshipping 3 vezes e nunca tinha vendido nada.</p>
            <p>Sabe o que ele tinha de diferente das outras pessoas?</p>
            <p><strong>Ele tinha uma LISTA DE FORNECEDORES QUALIFICADOS.</strong></p>
            <p>Com a lista que você recebeu, e aplicando o método correto, ele conseguiu:</p>
            <p>✅ Vender R$5.000 no primeiro mês</p>
            <p>✅ Sem investir em estoque</p>
            <p>✅ Usando fornecedores brasileiros</p>
        </div>
        <p>Se ele conseguiu, você também consegue.</p>
        <p>A diferença entre ele e você agora? Só uma coisa: <strong>ação.</strong></p>
        <div class="cta">
            <a href="https://www.seudominio.com/checkout">QUERO COMEÇAR HOJE</a>
        </div>
        <p>Um abraço,<br>[Seu nome]</p>
    </div>
    <div class="footer">
        <p>© <?= date('Y') ?> Lista Quente</p>
    </div>
</body>
</html>
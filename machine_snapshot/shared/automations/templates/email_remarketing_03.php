<?php
/**
 * Email Remarketing 03 — Urgência final (D+21)
 */
$nome = $nome ?? 'Cliente';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>É agora ou nunca ⏰</title>
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 0; background: #f4f4f4; }
        .header { background: linear-gradient(135deg, #c0392b 0%, #e74c3c 100%); padding: 40px 30px; text-align: center; color: white; }
        .content { background: white; padding: 40px 30px; }
        .content h2 { font-size: 22px; color: #1a1a2e; margin-top: 0; }
        .content p { font-size: 16px; line-height: 1.7; color: #333; }
        .highlight { background: #fff8f2; border: 2px solid #c0392b; border-radius: 12px; padding: 20px; margin: 25px 0; text-align: center; }
        .cta { background: linear-gradient(135deg, #FF6B00 0%, #ff8c00 100%); color: white; text-align: center; padding: 25px; border-radius: 12px; margin: 25px 0; }
        .cta a { color: white; text-decoration: none; font-size: 18px; font-weight: bold; }
        .footer { text-align: center; padding: 25px; font-size: 13px; color: #888; }
    </style>
</head>
<body>
    <div class="header">
        <h1>⏰ É agora ou nunca</h1>
    </div>
    <div class="content">
        <h2><?= htmlspecialchars($nome) ?>,</h2>
        <p>Esse é o último email que vou te enviar.</p>
        <p>Não estou dizendo isso pra te pressionar. Estou dizendo porque é verdade.</p>
        <div class="highlight">
            <p>Daqui a pouco este email não existirá mais. E com ele, a oportunidade de conseguir o material pelo preço que você viu.</p>
        </div>
        <p>Se você ainda não usou a lista que baixou, eu entendo. Talvez não seja o momento certo pra você.</p>
        <p>Mas se você está lendo isso e pensa "depois eu aproveito"...</p>
        <p>Eu te peço: não deixa pra depois.</p>
        <p>O momento é agora.</p>
        <div class="cta">
            <a href="https://www.seudominio.com/checkout">ÚLTIMA CHANCE — GARANTIR ACESSO</a>
        </div>
        <p>Sucesso!<br>[Seu nome]</p>
    </div>
    <div class="footer">
        <p>© <?= date('Y') ?> Lista Quente</p>
    </div>
</body>
</html>
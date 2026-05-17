<?php
/**
 * Email Carrinho Abandonado — Lembrete 1h
 */
$value = $value ?? 97;
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Esqueceu algo? 🔥</title>
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 0; background: #f4f4f4; }
        .header { background: linear-gradient(135deg, #FF6B00 0%, #ff8c00 100%); padding: 40px 30px; text-align: center; color: white; }
        .content { background: white; padding: 40px 30px; }
        .content h2 { font-size: 22px; color: #1a1a2e; margin-top: 0; }
        .content p { font-size: 16px; line-height: 1.7; color: #333; }
        .price-info { background: #fff8f2; border-radius: 10px; padding: 20px; margin: 20px 0; text-align: center; }
        .price-info p { font-size: 18px; margin: 5px 0; }
        .cta { background: linear-gradient(135deg, #FF6B00 0%, #ff8c00 100%); color: white; text-align: center; padding: 25px; border-radius: 12px; margin: 25px 0; }
        .cta a { color: white; text-decoration: none; font-size: 18px; font-weight: bold; }
        .footer { text-align: center; padding: 25px; font-size: 13px; color: #888; }
    </style>
</head>
<body>
    <div class="header">
        <h1>🔥 Esqueceu algo?</h1>
    </div>
    <div class="content">
        <h2>Sua lista ainda está esperando por você!</h2>
        <p>Vimos que você visitou nossa página de vendas mas ainda não garantiu seu acesso.</p>
        <p>Semanas de trabalho tentando encontrar fornecedores... tudo isso pode estar resolvido com a lista certa.</p>
        <div class="price-info">
            <p>Por apenas <strong>R$ <?= number_format($value, 2, ',', '.') ?></strong></p>
            <p>Acesso vitalício • Atualizações incluídas</p>
        </div>
        <p>Não deixa esse momento passar.</p>
        <div class="cta">
            <a href="https://www.seudominio.com/checkout">RESGATAR MINHA LISTA</a>
        </div>
        <p>Um abraço,<br>[Seu nome]</p>
    </div>
    <div class="footer">
        <p>© <?= date('Y') ?> Lista Quente</p>
    </div>
</body>
</html>
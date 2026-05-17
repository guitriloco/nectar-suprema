<?php
/**
 * Email 05 — Urgência (Dia 6)
 */
$nome = $nome ?? 'Cliente';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>⚠️ <?= htmlspecialchars($nome) ?>, isso expira em breve</title>
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 0; background: #f4f4f4; }
        .header { background: linear-gradient(135deg, #c0392b 0%, #e74c3c 100%); padding: 40px 30px; text-align: center; color: white; }
        .content { background: white; padding: 40px 30px; }
        .content h2 { font-size: 22px; color: #1a1a2e; margin-top: 0; }
        .content p { font-size: 16px; line-height: 1.7; color: #333; }
        .price-box { background: #fff8f2; border: 2px solid #FF6B00; border-radius: 12px; padding: 25px; text-align: center; margin: 25px 0; }
        .price-box .old-price { text-decoration: line-through; color: #999; font-size: 18px; }
        .price-box .new-price { font-size: 36px; font-weight: 800; color: #FF6B00; margin: 10px 0; }
        .price-box .installment { font-size: 16px; color: #666; }
        .cta { background: linear-gradient(135deg, #FF6B00 0%, #ff8c00 100%); color: white; text-align: center; padding: 25px; border-radius: 12px; margin: 25px 0; }
        .cta a { color: white; text-decoration: none; font-size: 18px; font-weight: bold; }
        .footer { text-align: center; padding: 25px; font-size: 13px; color: #888; }
    </style>
</head>
<body>
    <div class="header">
        <h1>⏰ Atenção!</h1>
    </div>

    <div class="content">
        <h2Ei <?= htmlspecialchars($nome) ?>, preciso te falar uma coisa importante.</h2>

        <p>O preço que você viu quando comprou o material? Ele não vai durar pra sempre.</p>

        <p>Isso é uma <strong>oferta de lançamento</strong>. E ofertas de lançamento têm prazo.</p>

        <div class="price-box">
            <p class="old-price">De: R$ 297,00</p>
            <p class="new-price">Por: R$ 97,00</p>
            <p class="installment">ou 12x de R$ 9,70 no cartão</p>
        </div>

        <p>Quando acabarem as vagas disponíveis, o preço volta pro valor cheio: <strong>R$ 297,00</strong>.</p>

        <p>Você garante acesso vitalício pelo preço de hoje: <strong>R$ 97,00</strong>.</p>

        <p>Se você ainda não começou a usar o material, agora é a melhor hora.</p>

        <p>É só voltar ao primeiro email e fazer o download. Leva 2 minutos.</p>

        <p><strong>O tempo está passando. Não perca essa oportunidade.</strong></p>
    </div>

    <div class="footer">
        <p>© <?= date('Y') ?> Lista Quente</p>
    </div>
</body>
</html>
<?php
/**
 * Email 07 — Final (Dia 8 — Last Call)
 */
$nome = $nome ?? 'Cliente';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Última chance, <?= htmlspecialchars($nome) ?> 🚨</title>
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 0; background: #f4f4f4; }
        .header { background: linear-gradient(135deg, #c0392b 0%, #e74c3c 100%); padding: 40px 30px; text-align: center; color: white; }
        .content { background: white; padding: 40px 30px; }
        .content h2 { font-size: 22px; color: #1a1a2e; margin-top: 0; }
        .content p { font-size: 16px; line-height: 1.7; color: #333; }
        .highlight { background: #fff8f2; border: 2px solid #FF6B00; border-radius: 12px; padding: 20px; margin: 25px 0; text-align: center; }
        .highlight p { font-size: 18px; margin: 0; }
        .cta { background: linear-gradient(135deg, #FF6B00 0%, #ff8c00 100%); color: white; text-align: center; padding: 25px; border-radius: 12px; margin: 25px 0; }
        .cta a { color: white; text-decoration: none; font-size: 18px; font-weight: bold; }
        .footer { text-align: center; padding: 25px; font-size: 13px; color: #888; }
    </style>
</head>
<body>
    <div class="header">
        <h1>🚨 Última chance</h1>
    </div>

    <div class="content">
        <h2><?= htmlspecialchars($nome) ?>, esse é o último email.</h2>

        <p>Eu não vou te pressionar. A decisão é sua.</p>

        <p>Mas preciso te lembrar:</p>

        <div class="highlight">
            <p><strong>Essa é a última vez que você vai ver esse material pelo preço que pagou.</strong></p>
        </div>

        <p>Amanhã, ou depois, a oferta expira. O preço volta pro normal.</p>

        <p>Se você ainda não abriu o material, eu entendo. Talvez não seja o momento certo.</p>

        <p>Mas se for, <strong>agora é a hora.</strong></p>

        <p>Volta ao primeiro email. Faz o download. Aplica o que está lá dentro.</p>

        <p><strong>Eu estou torcendo pelo seu sucesso.</strong></p>

        <div class="cta">
            🔴 <a href="https://www.seudominio.com/checkout">QUERO GARANTIR MINHA VAGA</a>
        </div>

        <p>Um abraço,<br><strong>[Seu nome]</strong></p>
    </div>

    <div class="footer">
        <p>© <?= date('Y') ?> Lista Quente</p>
    </div>
</body>
</html>
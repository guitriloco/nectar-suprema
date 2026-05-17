<?php
/**
 * Email 04 — Prova Social (Dia 5)
 */
$nome = $nome ?? 'Cliente';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Olha o que está acontecendo com quem já usa 📣</title>
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 0; background: #f4f4f4; }
        .header { background: linear-gradient(135deg, #FF6B00 0%, #ff8c00 100%); padding: 40px 30px; text-align: center; color: white; }
        .content { background: white; padding: 40px 30px; }
        .content h2 { font-size: 22px; color: #1a1a2e; margin-top: 0; }
        .content p { font-size: 16px; line-height: 1.7; color: #333; }
        .testimonial { background: #f9f9f9; border-radius: 12px; padding: 25px; margin: 20px 0; border-left: 4px solid #FF6B00; }
        .testimonial .stars { color: #ffc107; font-size: 20px; margin-bottom: 10px; }
        .testimonial p { font-size: 17px; font-style: italic; color: #333; line-height: 1.6; margin: 0 0 12px 0; }
        .testimonial .author { font-weight: 600; color: #1a1a2e; font-style: normal; }
        .cta { background: linear-gradient(135deg, #FF6B00 0%, #ff8c00 100%); color: white; text-align: center; padding: 25px; border-radius: 12px; margin: 25px 0; }
        .cta a { color: white; text-decoration: none; font-size: 18px; font-weight: bold; }
        .footer { text-align: center; padding: 25px; font-size: 13px; color: #888; }
    </style>
</head>
<body>
    <div class="header">
        <h1>📣 Resultados reais</h1>
    </div>

    <div class="content">
        <h2><?= htmlspecialchars($nome) ?>, você não precisa acreditar em mim.</h2>

        <p>Acredite em quem já usou.</p>

        <div class="testimonial">
            <div class="stars">⭐⭐⭐⭐⭐</div>
            <p>"Vendi R$ 2.300 na primeira semana. O material se pagou no primeiro dia. Não acredito que demorei tanto pra descobrir isso."</p>
            <p class="author">— Mariana C., Rio de Janeiro</p>
        </div>

        <div class="testimonial">
            <div class="stars">⭐⭐⭐⭐⭐</div>
            <p>"Finalmente um material que funciona no Brasil. Fornecedores reais, que respondem, que entregam. Reduzi minha taxa de retorno em 80%."</p>
            <p class="author">— Pedro H., Belo Horizonte</p>
        </div>

        <div class="testimonial">
            <div class="stars">⭐⭐⭐⭐⭐</div>
            <p>"Economizei 3 meses de tentativas e erros. A copy pronta me economizou ainda mais. Recomendo demais."</p>
            <p class="author">— Juliana M., Curitiba</p>
        </div>

        <p>Esses são pessoas comuns que decidiram agir.</p>

        <p><strong>Se eles conseguiram, você também consegue.</strong></p>
    </div>

    <div class="footer">
        <p>© <?= date('Y') ?> Lista Quente</p>
    </div>
</body>
</html>
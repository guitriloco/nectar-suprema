<?php
/**
 * Email 02 — Problema (Dia 2)
 */
$nome = $nome ?? 'Cliente';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($nome) ?>, você se identifica com isso?</title>
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 0; background: #f4f4f4; }
        .header { background: linear-gradient(135deg, #FF6B00 0%, #ff8c00 100%); padding: 40px 30px; text-align: center; color: white; }
        .content { background: white; padding: 40px 30px; }
        .content h2 { font-size: 22px; color: #1a1a2e; margin-top: 0; }
        .content p { font-size: 16px; line-height: 1.7; color: #333; }
        .pain-point { background: #fff8f2; padding: 20px; border-radius: 10px; margin: 15px 0; border-left: 4px solid #FF6B00; }
        .pain-point p { margin: 8px 0; font-size: 16px; }
        .footer { text-align: center; padding: 25px; font-size: 13px; color: #888; }
    </style>
</head>
<body>
    <div class="header">
        <h1>❓ Pergunta importante</h1>
    </div>

    <div class="content">
        <h2><?= htmlspecialchars($nome) ?>, você se identifica com isso?</h2>

        <p>Olá!</p>

        <p>Pergunta: você já se sentiu assim?</p>

        <div class="pain-point">
            <p>❌ "Comprei curso atrás de curso, mas nada funcionava..."</p>
            <p>❌ "Eu tinha uma ideia de produto, mas não sabia onde encontrar fornecedores..."</p>
            <p>❌ "Fiz anúncios no Instagram/Facebook, mandei mensagem pra todo mundo, e nada de venda..."</p>
            <p>❌ "A sensação de que eu estava fazendo tudo errado..."</p>
        </div>

        <p>Se você marcou mesmo que 1 desses, te digo uma coisa: <strong>você não está sozinho.</strong></p>

        <p>A verdade é que a maioria das pessoas que tenta dropshipping falha por um motivo muito simples:</p>

        <p><strong>Falta informação de qualidade.</strong></p>

        <p>Não é falta de vontade. Não é falta de inteligência. É falta de saber o que fazer e com quem fazer.</p>

        <p>Amanhã vou te mostrar exatamente como resolver isso.</p>
    </div>

    <div class="footer">
        <p>© <?= date('Y') ?> Lista Quente</p>
    </div>
</body>
</html>
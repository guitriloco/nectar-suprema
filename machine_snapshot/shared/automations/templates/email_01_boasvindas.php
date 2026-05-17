<?php
/**
 * Email 01 — Boas-vindas + Entrega da Isca
 */
$nome = $nome ?? 'Cliente';
$utm_source = $utm_source ?? 'direct';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sua lista chegou! 📦 + algo inesperado...</title>
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 0; background: #f4f4f4; }
        .header { background: linear-gradient(135deg, #FF6B00 0%, #ff8c00 100%); padding: 40px 30px; text-align: center; color: white; }
        .header h1 { font-size: 26px; margin: 0; }
        .content { background: white; padding: 40px 30px; }
        .content h2 { font-size: 22px; color: #1a1a2e; margin-top: 0; }
        .content p { font-size: 16px; line-height: 1.7; color: #333; margin: 15px 0; }
        .checklist { list-style: none; padding: 0; margin: 20px 0; }
        .checklist li { padding: 12px 0; border-bottom: 1px solid #eee; font-size: 16px; }
        .checklist li::before { content: "✅ "; margin-right: 8px; font-size: 18px; }
        .cta { background: linear-gradient(135deg, #FF6B00 0%, #ff8c00 100%); color: white; text-align: center; padding: 25px; border-radius: 12px; margin: 25px 0; }
        .cta a { color: white; text-decoration: none; font-size: 18px; font-weight: bold; }
        .footer { text-align: center; padding: 25px; font-size: 13px; color: #888; }
        .ps { background: #fff8f2; padding: 20px; border-radius: 10px; margin-top: 25px; font-size: 15px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>🔥 Sua lista chegou!</h1>
    </div>

    <div class="content">
        <h2>Olá <?= htmlspecialchars($nome) ?>, seja muito bem-vindo(a)! 🎉</h2>

        <p>Você acaba de garantir acesso à <strong>LISTA QUENTE DE FORNECEDORES</strong>.</p>

        <p>Antes de tudo, deixa eu te contar uma coisa...</p>

        <p>Eu criei esse material porque eu mesmo sofri bastante no início do meu negócio. Passei meses tentando encontrar fornecedores confiáveis. Gastei dinheiro em listas que não funcionavam. Quase desisti.</p>

        <p>Mas uma coisa eu aprendi: <strong>o sucesso no dropshipping começa com os fornecedores certos.</strong></p>

        <p>E é exatamente isso que você tem em mãos agora.</p>

        <h3>Dentro do material você vai encontrar:</h3>
        <ul class="checklist">
            <li>+500 fornecedores verificados (com contato direto)</li>
            <li>15 nichos mais lucrativos de 2025</li>
            <li>Scripts de vendas testados</li>
            <li>Método passo a passo</li>
        </ul>

        <p>Baixe agora. Aplique amanhã. Success!</p>

        <div class="cta">
            📥 <a href="https://www.seudominio.com/area-do-aluno">BAIXAR MINHA LISTA AGORA</a>
        </div>

        <div class="ps">
            <strong>P.S.</strong> Se tiver qualquer dúvida ao usar o material, me responde aqui que eu te ajudo pessoalmente.
        </div>
    </div>

    <div class="footer">
        <p>© <?= date('Y') ?> Lista Quente — Todos os direitos reservados.</p>
        <p>Você recebeu este email porque se cadastrou na nossa lista.</p>
    </div>
</body>
</html>
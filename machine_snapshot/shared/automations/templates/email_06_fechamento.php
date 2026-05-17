<?php
/**
 * Email 06 — Fechamento (Dia 7 — Manhã)
 */
$nome = $nome ?? 'Cliente';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🚨 <?= htmlspecialchars($nome) ?>, última chamada</title>
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 0; background: #f4f4f4; }
        .header { background: linear-gradient(135deg, #c0392b 0%, #e74c3c 100%); padding: 40px 30px; text-align: center; color: white; }
        .content { background: white; padding: 40px 30px; }
        .content h2 { font-size: 22px; color: #1a1a2e; margin-top: 0; }
        .content p { font-size: 16px; line-height: 1.7; color: #333; }
        .options { margin: 25px 0; }
        .option { display: flex; align-items: flex-start; margin: 15px 0; padding: 20px; border-radius: 10px; }
        .option-1 { background: #f9f9f9; border-left: 4px solid #999; }
        .option-2 { background: #fff8f2; border-left: 4px solid #FF6B00; }
        .option h3 { font-size: 17px; margin: 0 0 5px 0; }
        .option p { margin: 0; font-size: 15px; color: #555; }
        .cta { background: linear-gradient(135deg, #FF6B00 0%, #ff8c00 100%); color: white; text-align: center; padding: 25px; border-radius: 12px; margin: 25px 0; }
        .cta a { color: white; text-decoration: none; font-size: 18px; font-weight: bold; }
        .ps { background: #fff8f2; padding: 15px; border-radius: 8px; font-size: 15px; margin-top: 20px; }
        .footer { text-align: center; padding: 25px; font-size: 13px; color: #888; }
    </style>
</head>
<body>
    <div class="header">
        <h1>🚨 Última chamada</h1>
    </div>

    <div class="content">
        <h2><?= htmlspecialchars($nome) ?>, esse é o penúltimo email da sequência.</h2>

        <p>E eu vou ser bem direto com você:</p>

        <p><strong>Você tem duas opções agora:</strong></p>

        <div class="options">
            <div class="option option-1">
                <div>
                    <h3>OPÇÃO 1:</h3>
                    <p>Ignorar esse email, continuar fazendo o que sempre fez, e obter os mesmos resultados de sempre.</p>
                </div>
            </div>

            <div class="option option-2">
                <div>
                    <h3>OPÇÃO 2:</h3>
                    <p>Abrir o material que você já tem, começar a aplicar, e finalmente construir o negócio que você quer.</p>
                </div>
            </div>
        </div>

        <p>A escolha é sua.</p>

        <p>Eu já te dei toda a informação. Você tem os fornecedores, os produtos, as copies. Está tudo pronto.</p>

        <p><strong>O próximo passo é seu.</strong></p>

        <div class="cta">
            🔴 <a href="https://www.seudominio.com/checkout">GARANTIR MINHA VAGA AGORA</a>
        </div>

        <div class="ps">
            <strong>P.S.</strong> Amanhã vou mandar o último email. Depois disso, a oferta não vai estar mais disponível pelo preço atual.
        </div>
    </div>

    <div class="footer">
        <p>© <?= date('Y') ?> Lista Quente</p>
    </div>
</body>
</html>
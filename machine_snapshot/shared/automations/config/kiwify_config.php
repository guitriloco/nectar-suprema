<?php
/**
 * Kiwify Configuration
 * Credenciais e setup da API Kiwify
 */

$_ENV['KIWIFY_API_KEY'] = 'SEU_KIWIFY_API_KEY';
$_ENV['KIWIFY_PRODUCT_ID'] = 'SEU_PRODUCT_ID';
$_ENV['KIWIFY_WEBHOOK_SECRET'] = 'SEU_WEBHOOK_SECRET';

/**
 * URLs Kiwify
 */
define('KIWIFY_API_URL', 'https://api.kiwify.com.br/api');
define('KIWIFY_CHECKOUT_URL', 'https://app.kiwify.com.br/checkout');
define('KIWIFY_DASHBOARD_URL', 'https://app.kiwify.com.br/area-do-produtor');
define('KIWIFY_AREA_ALUNO_URL', 'https://app.kiwify.com.br/area-do-aluno');

/**
 * Endpoints de webhook Kiwify
 */
define('KIWIFY_WEBHOOK_EVENTS', [
    'compra.aprovada',
    'compra.reprovada',
    'acesso.concedido',
]);
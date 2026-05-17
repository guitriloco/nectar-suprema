<?php
/**
 * Hotmart Configuration
 * Credenciais e setup da API Hotmart
 */

$_ENV['HOTMART_WEBHOOK_TOKEN'] = 'SEU_HOTMART_WEBHOOK_TOKEN';
$_ENV['HOTMART_PRODUCT_ID'] = 'SEU_PRODUCT_ID_HOTMART';
$_ENV['HOTMART_API_KEY'] = 'SEU_HOTMART_API_KEY';

/**
 * URLs Hotmart
 */
define('HOTMART_API_URL', 'https://api-hotmart.com');
define('HOTMART_CHECKOUT_URL', 'https://pay.hotmart.com');
define('HOTMART_AREA_ALUNO_URL', 'https://app.hotmart.com/area-do-aluno');

/**
 * Eventos webhook Hotmart
 * https://dev.hotmart.com/docs/webhooks/
 */
define('HOTMART_WEBHOOK_EVENTS', [
    'PURCHASE_APPROVED',
    'PURCHASE_REFUNDED',
    'PURCHASE_CHARGEBACK',
    'MEMBER_ACCESS_PROVIDED',
    'SUBSCRIPTION_APPROVED',
    'SUBSCRIPTION_CANCELLED',
]);
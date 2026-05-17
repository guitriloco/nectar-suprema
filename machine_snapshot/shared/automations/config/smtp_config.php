<?php
/**
 * SMTP Configuration
 * Configurações do servidor de email
 */

$_ENV['SMTP_HOST'] = 'smtp.seudominio.com';
$_ENV['SMTP_PORT'] = 587;
$_ENV['SMTP_USER'] = 'seu@email.com';
$_ENV['SMTP_PASS'] = 'sua_senha';
$_ENV['SMTP_FROM'] = 'nome@seudominio.com';
$_ENV['SMTP_FROM_NAME'] = 'Lista Quente';

/**
 * Facebook Pixel
 */
$_ENV['FACEBOOK_PIXEL_ID'] = 'SEU_PIXEL_ID';
$_ENV['FACEBOOK_ACCESS_TOKEN'] = 'SEU_ACCESS_TOKEN';

/**
 * URL base do sistema
 */
define('SYSTEM_BASE_URL', 'https://www.seudominio.com');
define('LANDING_PAGE_URL', 'https://www.seudominio.com/landing');
define('CHECKOUT_KIWIFY_URL', 'https://app.kiwify.com.br/checkout/SEU_ID');
define('CHECKOUT_HOTMART_URL', 'https://pay.hotmart.com/SEU_ID');
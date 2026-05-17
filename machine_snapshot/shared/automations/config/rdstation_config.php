<?php
/**
 * RD Station Configuration
 * Credenciais da API RD Station
 */

$_ENV['RD_STATION_API_KEY'] = 'SEU_API_KEY_RD_STATION';
$_ENV['RD_STATION_WORKSPACE_UUID'] = 'SEU_WORKSPACE_UUID';

/**
 * Tags usadas no RD Station
 */
define('TAG_LEAD_QUENTE', 'lead_quente');
define('TAG_COMPRADOR', 'comprador');
define('TAG_REMARKETING', 'remarketing_1');
define('TAG_CARRINHO_ABANDONADO', 'carrinho_abandonado');

/**
 * Lifecycle stages
 */
define('LIFECYCLE_LEAD', 'lead');
define('LIFECYCLE_CUSTOMER', 'customer');
define('LIFECYCLE_SUBSCRIBER', 'subscriber');
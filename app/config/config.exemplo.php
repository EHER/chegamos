<?php

/**
 * Define o nível de erro a ser exibido.
 */
error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE | E_ALL);
ini_set('display_errors', '1');

/**
 * Define o timezone padrão 
 */
date_default_timezone_set("America/Sao_Paulo");

/**
 * Para usar a api do apontador basta fazer o cadastro no link
 * http://www.apontador.com.br/accounts/app/create.html
 * Você pode consultar esses dados a qualquer momento no seu perfil do Apontador
 * http://www.apontador.com.br/accounts/apps.html
 * Qualquer dúvida sobre a api do apontador, a documentação está em
 * http://api.apontador.com.br/pt/acesso.html
 */
define('ROOT_URL', 'http://localhost/');
define('APONTADOR_URL', 'api.apontador.com.br/v1/');
define('APONTADOR_PORT', 80);
define('APONTADOR_CONSUMER_KEY', '');
define('APONTADOR_CONSUMER_SECRET', '');
define('APONTADOR_TIMEOUT', 10);


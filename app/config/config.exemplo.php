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
 * Define charset padrão (recomendado: utf-8) 
 */
ini_set("default_charset", "utf-8");

/**
 * Para usar a api do apontador basta fazer o cadastro no link
 * http://www.apontador.com.br/accounts/app/create.html
 * Você pode consultar esses dados a qualquer momento no seu perfil do Apontador
 * http://www.apontador.com.br/accounts/apps.html
 * Qualquer dúvida sobre a api do apontador, a documentação está em
 * http://api.apontador.com.br/pt/acesso.html
 */
define('APONTADOR_CONSUMER_KEY', '');
define('APONTADOR_CONSUMER_SECRET', '');

/**
 * Configuração das URLs
 */
define('ROOT_URL', 'http://localhost/chegamos/');
define('STATIC_URL', 'http://localhost/chegamos/');

/**
 * Habilita a versão sem javascript/css
 */
define('LIGHT_VERSION', false);

/**
 * Chaves para fazer connect do Foursquare
 */
define('FOURSQUARE_CONSUMER_KEY', '');
define('FOURSQUARE_CONSUMER_SECRET', '');

/**
 * Chaves para fazer connect do Twitter
 */
define('TWITTER_CONSUMER_KEY', '');
define('TWITTER_CONSUMER_SECRET', '');

/**
 * Dados para fazer connect com Facebook
 */
define('FACEBOOK_AP_ID', '');
define('FACEBOOK_SECRET', '');

/**
 * Chaves para fazer connect com Orkut
 */
define('ORKUT_CONSUMER_KEY', '');
define('ORKUT_CONSUMER_SECRET', '');

/**
 * Chave para usar API do Google
 */
define('GOOGLE_APIS_KEY', '');

/**
 * Configuração da Api Apontador
 */
define('APONTADOR_URL', 'api.apontador.com.br/v1/');
define('APONTADOR_PORT', 80);
define('APONTADOR_TIMEOUT', 10);

/**
 * Habilita e configura o sevidor do Memcached
 */
define('USE_MEMCACHED', false);
define('MEMCACHED_SERVER', '127.0.0.1:11211');

/**
 * Contiguração de tema
 */
define('THEME_MAIN', 'b');
define('THEME_LIST', 'c');

/**
 * Faz login via POST no lugar de Oauth
 * @deprecated
 */
define('APONTADOR_POST_LOGIN', false);

<?php

namespace app\models;

use \app\models\oauth\OAuthConsumer;
use \app\models\oauth\OAuthSignatureMethod_HMAC_SHA1;
use \app\models\oauth\OAuthRequest;

class ApontadorApi {

	var $config = array(
		'apiUrl' => APONTADOR_URL,
		'port' => APONTADOR_PORT,
		'consumerKey' => APONTADOR_CONSUMER_KEY,
		'consumerSecret' => APONTADOR_CONSUMER_SECRET,
	);

	public function __construct() {
		if (empty($this->config['apiUrl'])) {
			throw new \Exception('URL da API Apontador deve ser configurada');
		}
		if (empty($this->config['port'])) {
			throw new \Exception('Porta da API Apontador deve ser configurada');
		}
		if (empty($this->config['consumerKey'])) {
			throw new \Exception('Consumer Key da API Apontador deve ser configurada');
		}
		if (empty($this->config['consumerSecret'])) {
			throw new \Exception('Consumer Secret da API Apontador deve ser configurada');
		}
	}

	// Não está conversando com a API :(
	public function checkin($param=array()) {
		if (empty($param['place_id'])) {
			return false;
		}
		$response = $this->request('users/self/visits', array(
					'place_id' => $param['place_id'],
					'oauth_token' => empty($param['oauth_token']) ? '' : $param['oauth_token'],
					'oauth_token_secret' => empty($param['oauth_token_secret']) ? '' : $param['oauth_token_secret']
				), 'PUT');
				
		return json_decode($response, false);
	}

	public function review($param=array()) {
		if (empty($param['place_id'])) {
			return false;
		}
		$response = $this->request('places/' . $param['place_id'] . '/reviews/new', array(
					'place_id' => $param['place_id'],
					'rating' => empty($param['rating']) ? '' : $param['rating'],
					'content' => empty($param['content']) ? '' : $param['content'],
					'oauth_token' => empty($param['oauth_token']) ? '' : $param['oauth_token'],
					'oauth_token_secret' => empty($param['oauth_token_secret']) ? '' : $param['oauth_token_secret'],
				), 'PUT');
		return $response;
		return json_decode($response, false);
	}

	public function getReviews($param=array()) {
		if (empty($param['place_id'])) {
			return false;
		}
		$response = $this->request('places/' . $param['place_id'] . '/reviews', array(
					'place_id' => $param['place_id'],
					'page' => empty($param['page']) ? '' : $param['page'],
					'limit' => empty($param['limit']) ? '' : $param['limit'],
				));
		return json_decode($response, false);
	}

	public function getCategories($param=array()) {
		$response = $this->request('categories', array(
					'term' => isset($param['term']) ? $this->removeAccents($param['term']) : '',
				));
		return json_decode($response, false);
	}

	public function getCategoriesTop() {
		$response = $this->request('categories/top');
		return json_decode($response, false);
	}

	public function getSubcategories($param=array()) {
		if (empty($param['categoryid'])) {
			return false;
		}
		$response = $this->request('categories/' . $param['categoryid'] . '/subcategories', array(
					'categoryid' => $param['categoryid'],
					'term' => isset($param['term']) ? $this->removeAccents($param['term']) : '',
				));
		return json_decode($response, false);
	}

	public function searchByPoint($param=array()) {
		if (empty($param['lat']) and empty($param['lng'])) {
			return false;
		}
		$response = $this->request('search/places/bypoint', array(
					'lat' => $param['lat'],
					'lng' => $param['lng'],
					'radius_mt' => isset($param['radius_mt']) ? $param['radius_mt'] : '',
					'term' => isset($param['term']) ? $this->removeAccents($param['term']) : '',
					'category_id' => isset($param['category_id']) ? $param['category_id'] : '',
					'sort_by' => isset($param['sort_by']) ? $param['sort_by'] : '',
					'order' => isset($param['order']) ? $param['order'] : '',
					'rating' => isset($param['rating']) ? $param['rating'] : '',
					'page' => isset($param['page']) ? $param['page'] : '',
					'limit' => isset($param['limit']) ? $param['limit'] : '',
					'user_id' => isset($param['user_id']) ? $param['user_id'] : '',
				));
		
		return json_decode($response, false);
	}

	public function searchRecursive($param, $type = 'searchByPoint') {
		$numFound = 0;
		$radiusLimit = 10000000;
		$param['limit'] = !empty($param['limit']) ? $param['limit'] : 20;
		$param['radius_mt'] = !empty($param['radius_mt']) ? $param['radius_mt'] : 10;

		do {
			$param['radius_mt'] . " ";
			$result = $this->$type($param);
			$numFound = $result->search->result_count ? $result->search->result_count : 0;
			$param['radius_mt'] = $param['radius_mt'] * 10;
		} while ($numFound < $param['limit'] || $param['radius_mt'] > $radiusLimit);

		//var_dump($result);
		//exit;
		
		return $result;
	}

	public function searchByAddress($param=array()) {
		if (empty($param['state']) and empty($param['city'])) {
			return false;
		}
		$response = $this->request('search/places/byaddress', array(
					'country' => $param['country'],
					'state' => $param['state'],
					'city' => $this->removeAccents($param['city']),
					'street' => isset($param['street']) ? $param['street'] : '',
					'number' => isset($param['number']) ? $param['number'] : '',
					'district' => isset($param['district']) ? $param['district'] : '',
					'radius_mt' => isset($param['radius_mt']) ? $param['radius_mt'] : '',
					'term' => isset($param['term']) ? $param['term'] : '',
					'category_id' => isset($param['category_id']) ? $param['category_id'] : '',
				));

		return json_decode($response, false);
	}
	
	public function geocode($lat, $lng) {
		if ($lat && $lng) {
			$search = $this->searchRecursive(array(
							'lat' => $lat,
							'lng' => $lng,
							'limit' => 1
								), 'searchByPoint');
			return $search->search->places[0]->place->address;
		}
		return false;
	}

	public function searchByZipcode($param=array()) {
		if (empty($param['zipcode'])) {
			return false;
		}

		$response = $this->request('search/places/byzipcode', array(
					'zipcode' => $param['zipcode'],
					'radius_mt' => isset($param['radius_mt']) ? $param['radius_mt'] : '',
					'term' => isset($param['term']) ? $this->removeAccents($param['term']) : '',
					'category_id' => isset($param['category_id']) ? $param['category_id'] : '',
					'sort_by' => isset($param['sort_by']) ? $param['sort_by'] : '',
					'order' => isset($param['order']) ? $param['order'] : '',
					'rating' => isset($param['rating']) ? $param['rating'] : '',
					'page' => isset($param['page']) ? $param['page'] : '',
					'limit' => isset($param['limit']) ? $param['limit'] : '',
					'user_id' => isset($param['user_id']) ? $param['user_id'] : '',
				));

		return json_decode($response, false);
	}

	public function getPlace($param=array()) {
		if (empty($param['placeid'])) {
			return false;
		}
		$response = $this->request('places/' . $param['placeid']);
		return json_decode($response, false);
	}

	public function getVisitors($param=array()) {
		if (empty($param['placeid'])) {
			return false;
		}
		$response = $this->request('places/' . $param['placeid'] . '/visitors');

		// resolvendo bug da api que está trazendo o visitors com uma vírgula no final
		$response = str_replace(',]}', ']}', $response);
		$visitors = json_decode($response, false);
		if (!is_object($visitors)) {
			return false;
		}
		return $visitors->visitors;
	}

	private function request($method, $params=array(), $verb='GET') {
		// Workaround para funcionar o PUT
		if ($verb == 'PUT') {
			$oauth_token = $params['oauth_token'];
			$oauth_token_secret = $params['oauth_token_secret'];
			unset($params['oauth_token']);
			unset($params['oauth_token_secret']);
			return $this->apontadorChamaApi($verb, $method, $params, $oauth_token, $oauth_token_secret);
		}

		$default = array('type' => 'json');

		$queryString = \http_build_query($params + $default);

		$url = $this->config['apiUrl'] . $method . '?' . $queryString;

		$curl = curl_init($url);
		curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		curl_setopt($curl, CURLOPT_USERPWD, $this->config['consumerKey'] . ':' . $this->config['consumerSecret']);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_PORT, $this->config['port']);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $verb);
		curl_setopt($curl, CURLOPT_TIMEOUT, 10);
		curl_setopt($curl, CURLOPT_FAILONERROR, false);
		$curl_response = curl_exec($curl);
		curl_close($curl);
		
		//echo $this->config['consumerKey'] . ':' . $this->config['consumerSecret'];
		//echo $url . '<br />';
		//exit;
		//$curl_response = utf8_encode($curl_response);
		//exit;
		//json_decode($curl_response, false);
		//exit;
		return $curl_response;
	}

	private function requestNative($method, $params=array()) {
		$default = array('type' => 'json');

		$queryString = \http_build_query($params + $default);

		$url = $this->config['apiUrl'] . $method . '?' . $queryString;

		$config = array(
			'host' => $url,
			'port' => $this->config['port'],
			'auth' => 'Basic',
			'username' => $this->config['consumerKey'],
			'password' => $this->config['consumerSecret'],
			'timeout' => 10,
			'socket' => 'Curl',
			'encoding' => 'UTF-8',
		);
		$request = new \lithium\net\http\Service($config);
		$response = $request->post($method, $params);

		return $curl_response;
	}

	private function removeAccents($var) {
		$var = strtolower($var);

		$var = str_replace(array("á", "à", "â", "ã", "ª"), "a", $var);
		$var = str_replace(array("é", "è", "ê"), "e", $var);
		$var = str_replace(array("ó", "ò", "ô", "õ", "º"), "o", $var);
		$var = str_replace(array("ú", "ù", "û"), "u", $var);
		$var = str_replace("ç", "c", $var);

		return $var;
	}

	/**
	 * Leva o usuário  para o site Apontador, para que ele autorize a aplicação. Deve ser
	 * a última coisa chamada na página (vai mandar um redirect).
	 */
	function apontadorRedirectAutorizacao($callbackurl) {

		$consumer = new \app\models\oauth\OAuthConsumer($this->config['consumerKey'], $this->config['consumerSecret'], NULL);
		$signature_method = new \app\models\oauth\OAuthSignatureMethod_HMAC_SHA1();

		// Passo 1: Pedir o par de tokens inicial (oauth_token e oauth_token_secret) para o Apontador
		$endpoint = "http://api.apontador.com.br/v1/oauth/request_token";
		$req_req = \app\models\oauth\OAuthRequest::from_consumer_and_token($consumer, NULL, "GET", $endpoint, array());
		$req_req->sign_request($signature_method, $consumer, NULL);
		parse_str(file_get_contents($req_req));

		// Passo 2: Redirecionar o usuário para o Apontador, para que ele autorize o uso dos seus dados.
		$endpoint = "http://api.apontador.com.br/v1/oauth/authorize";
		$oauth_callback = "$callbackurl?&key=" . $this->config['consumerKey'] . "&secret=" . $this->config['consumerSecret'] . "&token=$oauth_token&token_secret=$oauth_token_secret&endpoint=" . urlencode($endpoint);
		$auth_url = $endpoint . "?oauth_token=$oauth_token&oauth_callback=" . urlencode($oauth_callback) . "";
		header("Location: $auth_url");
		die();
	}

	/**
	 * Processa o retorno (callback) de uma autorização, obtendo os dados de acesso definitivos
	 * (token+secret) e o ID do usuário.
	 *
	 * A função acessa diretamente o request ($_REQUEST) para obter os dados.
	 *
	 * @return mixed dados de acesso (oauth_token e oauth_token_secret) e user_id do Apontador.
	 */
	function apontadorProcessaAutorizacao() {

		$consumer = new \app\models\oauth\OAuthConsumer($this->config['consumerKey'], $this->config['consumerSecret'], NULL);
		$signature_method = new \app\models\oauth\OAuthSignatureMethod_HMAC_SHA1();

		$token = $_REQUEST["oauth_token"];
		$verifier = $_REQUEST["oauth_verifier"];
		if ((!$token) || (!$verifier)) {
			return null;
		}

		// Passo 3: Passa o token e verificador para o Apontador, que vai validar o callback
		//          e devolver o token de acesso definitivo
		$endpoint = "http://api.apontador.com.br/v1/oauth/access_token?oauth_verifier=$verifier";
		$parsed = parse_url($endpoint);
		$params = array();
		parse_str($parsed['query'], $params);
		$acc_req = \app\models\oauth\OAuthRequest::from_consumer_and_token($consumer, NULL, "GET", $endpoint, $params);
		$acc_req->sign_request($signature_method, $consumer, NULL);
		parse_str(file_get_contents($acc_req), $access_token);
		return $access_token;
	}

	/**
	 * Efetua uma chamada a um método API
	 *
	 * @param verbo string GET, POST, PUT ou DELETE, conforme o método/intenção
	 * @param metodo string path do métdodo, sem "/" no começo (ex.: "users/self")
	 * @param params mixed parâmetros da chamada (array associativo)
	 * @param oauth_token string token de autorização do usuário. Se omitido, a chamada usa HTTP Basic Auth
	 * @param oauth_token_secret string secret do token de autorização do usuário (ignorado se oauth_token não for passado)
	 * @return resultado da chamada.
	 */
	function apontadorChamaApi($verbo="GET", $metodo, $params=array(), $oauth_token="", $oauth_token_secret="") {
	
		extract($this->config);

		$params['type'] = 'json';
		
		$key = $consumerKey;
		$secret = $consumerSecret;

		$endpoint = APONTADOR_URL . $metodo;
		if (!$oauth_token) {
			$queryparams = http_build_query($params);
			$auth_hash = base64_encode("$email:$key");
			return $this->_post("$endpoint?$queryparams", "GET", null, "Authorization: $auth_hash");
		} else {
			// OAuth
			$consumer = new OAuthConsumer($key, $secret, NULL);
			$token = new OAuthConsumer($oauth_token, $oauth_token_secret);
			$signature_method = new OAuthSignatureMethod_HMAC_SHA1();
			$req_req = OAuthRequest::from_consumer_and_token($consumer, $token, $verbo, $endpoint, $params);
			$req_req->sign_request($signature_method, $consumer, $token);

			if ($verbo == "GET") {
				return $this->_post($req_req, $verbo);
			} else {
				return $this->_post($endpoint, $verbo, $req_req->to_postdata());
			}
		}
	}

	function _post($url, $method, $data = null, $optional_headers = null) {
		
		$params = array('http' => array(
				'method' => $method,
				'ignore_errors' => true
				));
		if ($optional_headers !== null) {
			$params['http']['header'] = $optional_headers;
		}
		if ($data !== null) {
			$params['http']['content'] = $data;
		}
		$ctx = stream_context_create($params);
		$fp = @fopen($url, 'rb', false, $ctx);
		$response = @stream_get_contents($fp);
		
		return $response;
	}

}

<?php

namespace app\models;

use \app\models\oauth\OAuthConsumer;
use \app\models\oauth\OAuthSignatureMethod_HMAC_SHA1;
use \app\models\oauth\OAuthRequest;
use lithium\net\http\Service;

class ApontadorException extends \Exception {

}

class ApontadorApi {

	var $config = array(
		'apiUrl' => APONTADOR_URL,
		'port' => APONTADOR_PORT,
		'consumerKey' => APONTADOR_CONSUMER_KEY,
		'consumerSecret' => APONTADOR_CONSUMER_SECRET,
		'timeout' => APONTADOR_TIMEOUT,
	);

	public function __construct() {
		if (empty($this->config['apiUrl'])) {
			throw new ApontadorException('URL da API Apontador deve ser configurada');
		}
		if (empty($this->config['port'])) {
			throw new ApontadorException('Porta da API Apontador deve ser configurada');
		}
		if (empty($this->config['consumerKey'])) {
			throw new ApontadorException('Consumer Key da API Apontador deve ser configurada');
		}
		if (empty($this->config['consumerSecret'])) {
			throw new ApontadorException('Consumer Secret da API Apontador deve ser configurada');
		}
	}

	public function getUser($param=array()) {
		if (empty($param['userid'])) {
			return false;
		}
		$response = $this->request('users/' . $param['userid'], array());

		return new User($response->user);
	}

	public function checkin($param=array()) {
		if (empty($param['place_id'])) {
			return false;
		}
		$response = $this->request('users/self/visits', array(
					'place_id' => $param['place_id'],
					'oauth_token' => empty($param['oauth_token']) ? '' : $param['oauth_token'],
					'oauth_token_secret' => empty($param['oauth_token_secret']) ? '' : $param['oauth_token_secret']
						), 'PUT');
		return $response;
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
	}

	public function getPhotos($param=array()) {
		if (empty($param['placeId'])) {
			return false;
		}
		$response = $this->request('places/' . $param['placeId'] . '/photos');

		return New PhotoList($response);
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
		return $response;
	}

	public function getCategories($param=array()) {
		$response = $this->request('categories', array(
					'term' => isset($param['term']) ? $this->removeAccents($param['term']) : '',
				));


		return new CategoryList($response);
	}

	public function getCategoriesTop() {
		$response = $this->request('categories/top');


		return new CategoryList($response);
	}

	public function getSubcategories($param=array()) {
		if (empty($param['categoryid'])) {
			return false;
		}
		$response = $this->request('categories/' . $param['categoryid'] . '/subcategories', array(
					'categoryid' => $param['categoryid'],
					'term' => isset($param['term']) ? $this->removeAccents($param['term']) : '',
				));
		return $response;
	}

	public function searchByPoint($param=array()) {
		if (empty($param['lat']) && empty($param['lng'])) {
			return false;
		}
		$response = $this->request('search/places/bypoint', array(
					'term' => isset($param['term']) ? $this->removeAccents($param['term']) : '',
					'lat' => $param['lat'],
					'lng' => $param['lng'],
					'radius_mt' => isset($param['radius_mt']) ? $param['radius_mt'] : '',
					'category_id' => isset($param['category_id']) ? $param['category_id'] : '',
					'subcategory_id' => isset($param['subcategory_id']) ? $param['subcategory_id'] : '',
					'sort_by' => isset($param['sort_by']) ? $param['sort_by'] : 'relevance',
					'order' => isset($param['order']) ? $param['order'] : '',
					'rating' => isset($param['rating']) ? $param['rating'] : '',
					'page' => isset($param['page']) ? $param['page'] : '1',
					'limit' => isset($param['limit']) ? $param['limit'] : '',
					'user_id' => isset($param['user_id']) ? $param['user_id'] : '',
				));

		if (!empty($response->search)) {
			return new PlaceList($response->search);
		}
		return false;
	}

	public function searchRecursive($param, $type = 'searchByPoint') {
		$numFound = 0;
		$maxQueries = 5;
		$numQueries = 0;
		$radiusLimit = 1000000;
		$param['limit'] = !empty($param['limit']) ? $param['limit'] : 20;
		$param['radius_mt'] = !empty($param['radius_mt']) ? $param['radius_mt'] : 10;
		$param['page'] = !empty($param['page']) ? $param['page'] : '1';

		do {
			$numQueries++;
			$param['radius_mt'] . " ";
			$placeList = $this->$type($param);
			$numFound = $placeList instanceof PlaceList ? $placeList->getNumFound() : 0;
			$param['radius_mt'] = $param['radius_mt'] * 10;
			if ($numQueries > $maxQueries) {
				break;
			}
		} while ($numFound < $param['limit'] && $param['radius_mt'] < $radiusLimit);
		return $placeList;
	}

	public function searchGasStations($param, $type = 'searchByPoint') {
		$pageLimit = 10;
		$radiusLimit = 10000;
		$placesLimit = 5;
		$param['limit'] = 20;
		$param['radius_mt'] = 500;
		$param['category_id'] = '065';

		$gasStationList = new PlaceList();

		do {

			$param['page'] = 1;
			$param['radius_mt'] = round($param['radius_mt'] * 1.5);

			do {
				$placeList = $this->$type($param);

				if ($placeList instanceof PlaceList) {
					//echo ' -- ' . $placeList->getNumFound() . ' -- <br />';
					foreach ($placeList->getItems() as $place) {
						if ($place->getPlaceInfo() instanceof PlaceInfo && $place->getPlaceInfo()->getGasStation() instanceof GasStation) {
							$gasStationList->addUnique($place);
						}
					}
				}

				$param['page']++;
			} while ($gasStationList->getNumFound() < $placesLimit && $param['page'] < $pageLimit);


			$param['order'] = 'descending';
			//echo $param['radius_mt'] . ', ' . $param['page'] . ', ' . $gasStationList->getNumFound() . '<br />';
			//if (!($gasStationList->getNumFound() < $placesLimit && $param['radius_mt'] < $radiusLimit)) {exit;}
		} while ($gasStationList->getNumFound() < $placesLimit && $param['radius_mt'] < $radiusLimit);

		$gasStationList->setRadius($param['radius_mt']);
		return $gasStationList;
	}

	public function searchByAddress($param=array()) {
		if (empty($param['state']) || empty($param['city'])) {
			return false;
		}
		$response = $this->request('search/places/byaddress', array(
					'term' => isset($param['term']) ? $param['term'] : '',
					'country' => $param['country'],
					'state' => $param['state'],
					'city' => $this->removeAccents($param['city']),
					'street' => isset($param['street']) ? $param['street'] : '',
					'number' => isset($param['number']) ? $param['number'] : '',
					'district' => isset($param['district']) ? $param['district'] : '',
					'radius_mt' => isset($param['radius_mt']) ? $param['radius_mt'] : '',
					'category_id' => isset($param['category_id']) ? $param['category_id'] : '',
					'subcategory_id' => isset($param['subcategory_id']) ? $param['subcategory_id'] : '',
					'sort_by' => isset($param['sort_by']) ? $param['sort_by'] : 'relevance',
					'order' => isset($param['order']) ? $param['order'] : '',
					'rating' => isset($param['rating']) ? $param['rating'] : '',
					'page' => isset($param['page']) ? $param['page'] : '',
					'limit' => isset($param['limit']) ? $param['limit'] : '',
					'user_id' => isset($param['user_id']) ? $param['user_id'] : '',
				));


		if (is_object($response->search)) {
			return new PlaceList($response->search);
		}
		return false;
	}

	public function geocode($lat, $lng) {
		if ($lat && $lng) {
			$search = $this->searchRecursive(array(
						'lat' => $lat,
						'lng' => $lng,
						'limit' => 1
							), 'searchByPoint');
			if ($search) {
				return $search->getItem(0)->getAddress();
			}
		}
		return false;
	}

	public function getUserPlaces($param=array()) {
		if (empty($param['userId'])) {
			return false;
		}

		$response = $this->request('users/' . $param['userId'] . '/places', array(
					'page' => isset($param['page']) ? $param['page'] : '',
					'limit' => isset($param['limit']) ? $param['limit'] : '',
				));



		if (is_object($response->user)) {
			return new User($response->user);
		}
		return false;
	}

	public function getUserFollowing($param=array()) {
		if (empty($param['userId'])) {
			return false;
		}

		$response = $this->request('users/' . $param['userId'] . '/following', array(
					'nearby' => isset($param['nearby']) ? $param['nearby'] : '',
					'lat' => isset($param['lat']) ? $param['lat'] : '',
					'lng' => isset($param['lng']) ? $param['lng'] : '',
					'page' => isset($param['page']) ? $param['page'] : '',
					'limit' => isset($param['limit']) ? $param['limit'] : '',
				));



		if (is_object($response->following)) {
			return new FollowingList($response->following);
		}

		return false;
	}

	public function getUserFollowers($param=array()) {
		if (empty($param['userId'])) {
			return false;
		}

		$response = $this->request('users/' . $param['userId'] . '/followers', array(
					/**
					 * Não foi feito na API
					 * 'nearby' => isset($param['nearby']) ? $param['nearby'] : '',
					 * 'lat' => isset($param['lat']) ? $param['lat'] : '',
					 * 'lng' => isset($param['lng']) ? $param['lng'] : '',
					 */
					'page' => isset($param['page']) ? $param['page'] : '',
					'limit' => isset($param['limit']) ? $param['limit'] : '',
				));


		if (is_object($response) && isset($response->followers)) {
			return new FollowingList($response->followers);
		}

		return false;
	}

	public function searchByZipcode($param=array()) {
		if (empty($param['zipcode'])) {
			return false;
		}

		$response = $this->request('search/places/byzipcode', array(
					'term' => isset($param['term']) ? $this->removeAccents($param['term']) : '',
					'zipcode' => $param['zipcode'],
					'radius_mt' => isset($param['radius_mt']) ? $param['radius_mt'] : '',
					'category_id' => isset($param['category_id']) ? $param['category_id'] : '',
					'subcategory_id' => isset($param['subcategory_id']) ? $param['subcategory_id'] : '',
					'sort_by' => isset($param['sort_by']) ? $param['sort_by'] : 'relevance',
					'order' => isset($param['order']) ? $param['order'] : '',
					'rating' => isset($param['rating']) ? $param['rating'] : '',
					'page' => isset($param['page']) ? $param['page'] : '',
					'limit' => isset($param['limit']) ? $param['limit'] : '',
					'user_id' => isset($param['user_id']) ? $param['user_id'] : '',
				));

		if (is_object($response->search)) {
			return new PlaceList($response->search);
		}
		return false;
	}

	public function searchDeals($param=array()) {
		if (empty($param['lat']) || empty($param['lng'])) {
			return false;
		}

		var_dump($param);
		$response = $this->request('search/deals', array(
					'lat' => isset($param['lat']) ? $param['lat'] : '',
					'lng' => isset($param['lng']) ? $param['lng'] : '',
					'limit' => isset($param['limit']) ? $param['limit'] : '',
				));
		var_dump('resp: ' . $response);
		exit;

		if (is_object($response->search)) {
			return new DealList($response->search);
		}
		return false;
	}

	public function getPlace($param=array()) {
		if (empty($param['placeid'])) {
			return false;
		}
		$response = $this->request('places/' . $param['placeid']);


		return new Place($response->place);
	}

	public function getVisitors($param=array()) {
		if (empty($param['placeid'])) {
			return false;
		}
		$visitors = $this->request('places/' . $param['placeid'] . '/visitors');

		if (!is_object($visitors)) {
			return false;
		}
		$visitorList = new VisitorList($visitors->visitors);
		$visitorList->setPlaceId($param['placeid']);
		return $visitorList;
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

		$response = $this->send(array(
					'url' => $url,
					'port' => $this->config['port'],
					'basicAuth' => true,
					'username' => $this->config['consumerKey'],
					'password' => $this->config['consumerSecret'],
					'timeout' => $this->config['timeout'],
				));

		$response = json_decode($response, false);

		if (isset($response->error)) {
			throw new ApontadorException($response->error->httpstatus . ': ' . $response->error->message, $response->error->code);
		}

		return $response;
	}

	private function send(array $params = array()) {
		$defaults = array(
			'url' => 'localhost',
			'port' => 80,
			'basicAuth' => false,
			'username' => '',
			'password' => '',
			'method' => 'GET',
			'header' => '',
			'fields' => '',
			'timeout' => 5,
		);

		$config = $params + $defaults;

		$curl = curl_init($config['url']);
		if (!empty($config['basicAuth'])) {
			curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
			curl_setopt($curl, CURLOPT_USERPWD, $config['username'] . ':' . $config['password']);
		}
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_PORT, $config['port']);
		if (!empty($config['header'])) {
			curl_setopt($curl, CURLOPT_HEADER, $config['header']);
		}
		if (!empty($config['fields'])) {
			curl_setopt($curl, CURLOPT_POSTFIELDS, $config['fields']);
		}
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, \strtoupper($config['method']));
		curl_setopt($curl, CURLOPT_TIMEOUT, $config['timeout']);
		curl_setopt($curl, CURLOPT_FAILONERROR, false);
		$response = curl_exec($curl);
		curl_close($curl);

		return $response;
	}

	private function removeAccents($var) {
		$var = strtolower($var);

		$var = str_replace(array("á", "à", "â", "ã", "ª"), "a", $var);
		$var = str_replace(array("é", "è", "ê"), "e", $var);
		$var = str_replace(array("í", "ì", "î"), "i", $var);
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
		$step1 = $this->send(array('url' => $req_req->__toString()));
		parse_str($step1);

		// Passo 2: Redirecionar o usuário para o Apontador, para que ele autorize o uso dos seus dados.
		$endpoint = "http://api.apontador.com.br/v1/oauth/authorize";
		$oauth_callback = "$callbackurl?&key=" . $this->config['consumerKey'] . "&secret=" . $this->config['consumerSecret'] . "&token=$oauth_token&token_secret=$oauth_token_secret&endpoint=" . urlencode($endpoint);
		$auth_url = $endpoint . "?oauth_token=$oauth_token&oauth_callback=" . urlencode($oauth_callback) . "";
		return $auth_url;
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
		parse_str($this->send(array('url' => $acc_req)), $access_token);
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
	function apontadorChamaApi($verbo="GET", $metodo="", $params=array(), $oauth_token="", $oauth_token_secret="") {

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

		$response = $this->send(array(
					'url' => $url,
					'basicAuth' => true,
					'username' => $this->config['consumerKey'],
					'password' => $this->config['consumerSecret'],
					'method' => $method,
					'fields' => $data,
					'header' => $optional_headers,
				));
		return $response;
	}

	public static function encurtaUrl($url) {
		$urlEncurtada = self::send(array('url' => 'http://aponta.me/add?wt=text&url=' . $url));

		$novaUrl = empty($urlEncurtada) ? $url : $urlEncurtada;

		return $novaUrl;
	}

}

<?php

namespace app\models;

class ApontadorApi {

	var $config = array(
		'apiUrl' => 'http://api.apontador.com.br/v1/',
		'port' => 80,
		'consumerKey' => 'ImpfX7kZ3mOQO7vIIR5pJghNMS0Za5RYqKfBf5mnfds~',
		'consumerSecret' => 'CxhEUWv-D9LKVKiaYhrfWmoyAP0~',
	);

	public function searchByPoint($param) {
		if(empty($param['lat']) and empty($param['lng'])) {
			return false;
		}
		return $this->request('search/places/bypoint', array(
			'lat' => $param['lat'],
			'lng' => $param['lng'],
		));
	}

	public function searchByAddress($param) {
		if(empty($param['state']) and empty($param['city'])) {
			return false;
		}
		return $this->request('search/places/bypoint', array(
			'country' => $param['country'],
			'state' => $param['state'],
			'city' => $param['city'],
			'street' => $param['street'],
			'number' => $param['number'],
			'district' => $param['district'],
			'radius_mt' => $param['radius_mt'],
			'term' => $param['term'],
			'category_id' => $param['category_id'],
		));
	}

	public function searchByZipcode($param) {
		if(empty($param['zipcode'])) {
			return false;
		}
		return $this->request('search/places/byzipcode', array(
			'zipcode' => $param['zipcode']
		));
	}

	private function request($method, $params=array()) {
		$default = array('type'=>'json');

		$queryString = \http_build_query($params + $default);

		$curl = curl_init($this->config['apiUrl'] . $method . '?' . $queryString);
		curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		curl_setopt($curl, CURLOPT_USERPWD, $this->config['consumerKey'] . ':' . $this->config['consumerSecret']);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_PORT, $this->config['port']);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
		curl_setopt($curl, CURLOPT_TIMEOUT, 10);
		curl_setopt($curl, CURLOPT_FAILONERROR, false);
		$curl_response = curl_exec($curl);
		curl_close($curl);

		return $curl_response;
	}

}

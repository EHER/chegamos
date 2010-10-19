<?php

namespace app\models;

class Place {

    var $id = "";
    var $name = "";
    var $average_rating = 0;
    var $review_count = 0;
    var $category = null;
    var $subcategory = null;
    var $address = null;
    var $point = null;
    var $main_url = "";
    var $icon_url = "";
    var $other_url = "";

    public function get($placeId) {
        return $this->request('places/'.$placeId);
    }

    public function searchByZipcode($zipcode) {
		return $this->request('search/places/byzipcode', array('zipcode'=>$zipcode));
    }

    private function request($method, $params=array()) {
        $config = array(
            'host' => 'http://api.apontador.com.br/v1/',
            'port' => 80,
            'username' => 'ImpfX7kZ3mOQO7vIIR5pJghNMS0Za5RYqKfBf5mnfds~',
            'password' => 'CxhEUWv-D9LKVKiaYhrfWmoyAP0~',
        );

		$curl = curl_init($config['host'] . $method);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
		curl_setopt($curl, CURLOPT_USERPWD, $config['username'].':'.$config['password']);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_PORT, $config['port']);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
		curl_setopt($curl, CURLOPT_TIMEOUT, 10);
		curl_setopt($curl, CURLOPT_FAILONERROR, false);
		$curl_response = curl_exec($curl);
		curl_close($curl);

		return $curl_response;
    }

	private function request_fopen($method, $params) {
        $config = array(
            'host' => 'http://api.apontador.com.br/v1/',
            'auth' => 'Basic',
            'username' => 'ImpfX7kZ3mOQO7vIIR5pJghNMS0Za5RYqKfBf5mnfds~',
            'password' => 'CxhEUWv-D9LKVKiaYhrfWmoyAP0~',
        );
        $request = new \lithium\net\http\Service($config);
		$response = $request->get($method, $params);

		return $response;
    }

}
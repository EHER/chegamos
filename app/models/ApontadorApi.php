<?php

namespace app\models;

class ApontadorApi {

    var $config = array(
        'apiUrl' => APONTADOR_URL,
        'port' => APONTADOR_PORT,
        'consumerKey' => APONTADOR_COMSUMER_KEY,
        'consumerSecret' => APONTADOR_COMSUMER_SECRET,
    );

    public function __construct() {
        if (empty($this->config['apiUrl'])) {
            throw new \Exception('URL da API Apontador deve ser configurada em app/config/bootstrap.php');
        }
        if (empty($this->config['port'])) {
            throw new \Exception('Porta da API Apontador deve ser configurada em app/config/bootstrap.php');
        }
        if (empty($this->config['consumerKey'])) {
            throw new \Exception('Consumer Key da API Apontador deve ser configurada em app/config/bootstrap.php');
        }
        if (empty($this->config['consumerSecret'])) {
            throw new \Exception('Consumer Secret da API Apontador deve ser configurada em app/config/bootstrap.php');
        }
    }

    public function getCategories($param) {
        return $this->request('categories', array(
            'term' => $this->removeAccents($param['term'])
        ));
    }

    public function getCategoriesTop() {
        return $this->request('categories/top');
    }

    public function getSubcategories($param) {
        return $this->request('categories', array(
            'categoryid' => $param['categoryid']
        ));
    }


    public function searchByPoint($param) {
        if (empty($param['lat']) and empty($param['lng'])) {
            return false;
        }
        return $this->request('search/places/bypoint', array(
            'lat' => $param['lat'],
            'lng' => $param['lng'],
        ));
    }

    public function searchByAddress($param) {
        if (empty($param['state']) and empty($param['city'])) {
            return false;
        }
        return $this->request('search/places/byaddress', array(
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
    }

    public function searchByZipcode($param) {
        if (empty($param['zipcode'])) {
            return false;
        }
        return $this->request('search/places/byzipcode', array(
            'zipcode' => $param['zipcode']
        ));
    }

    public function getPlace($param) {
        if (empty($param['placeid'])) {
            return false;
        }
        return $this->request('places/' . $param['placeid']);
    }

    private function request($method, $params=array()) {
        $default = array('type' => 'json');

        $queryString = \http_build_query($params + $default);

        $url = $this->config['apiUrl'] . $method . '?' . $queryString;

        $curl = curl_init($url);
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

}

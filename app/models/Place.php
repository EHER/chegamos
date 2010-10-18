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
        return $this->request('search/places/byzipcode');
    }

    private function request($method='', array $params = array()) {
        $config = array(
            'host' => 'http://api.apontador.com.br/v1/' . $method,
            'auth' => 'Basic',
            'username' => 'ImpfX7kZ3mOQO7vIIR5pJghNMS0Za5RYqKfBf5mnfds~',
            'password' => 'CxhEUWv-D9LKVKiaYhrfWmoyAP0~',
        );
        $request = new \lithium\net\http\Request($config);

        echo $request->to('string');
        exit;
        return $request;
    }

}
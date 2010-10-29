<?php

namespace app\models;

class Geocode {

    private $ip;
    private $country;
    private $countryAbbrev;
    private $city;
    private $latitude;
    private $longitude;

    public function getByIp($ip=null) {
        $ip = empty($ip) ? $_SERVER['REMOTE_ADDR'] : $ip;
        $this->setIp($ip);

        $url = 'http://api.hostip.info/get_html.php?ip=' . $this->getIp() . '&position=true';

        $response = $this->_get($url);

        $geo = explode("\n", $response);

        list($nothing, $country, $countryAbbrev) = explode(" ", $geo[0]);
        list($nothing, $city) = explode(" ", $geo[1]);
        list($nothing, $latitude) = explode(" ", $geo[3]);
        list($nothing, $longitude) = explode(" ", $geo[4]);

        $this->setCountry($country);
        $this->setCountryAbbrev(\str_replace(array("(", ")"), "", $countryAbbrev));
        $this->setCity($city);
        $this->setLatitude($latitude);
        $this->setLongitude($longitude);
    }

    public function setIp($ip) {
        $this->ip = $ip;
    }

    public function getIp() {
        return $this->ip;
    }

    public function setCity($city) {
        $this->city = $city;
    }

    public function getCity() {
        return $this->city;
    }

    public function setCountry($country) {
        $this->country = $country;
    }

    public function getCountry() {
        return $this->country;
    }

    public function setCountryAbbrev($countryAbbrev) {
        $this->countryAbbrev = $countryAbbrev;
    }

    public function getCountryAbbrev() {
        return $this->countryAbbrev;
    }

    public function setLatitude($latitude) {
        $this->latitude = $latitude;
    }

    public function getLatitude() {
        return $this->latitude;
    }

    public function setLongitude($longitude) {
        $this->longitude = $longitude;
    }

    public function getLongitude() {
        return $this->longitude;
    }

    private function _get($url) {
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($curl, CURLOPT_TIMEOUT, 10);
        curl_setopt($curl, CURLOPT_FAILONERROR, false);
        $curl_response = curl_exec($curl);
        curl_close($curl);

        return $curl_response;
    }

}
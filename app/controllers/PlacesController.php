<?php

namespace app\controllers;

use app\models\Place,
 lithium\storage;

class PlacesController extends \lithium\action\Controller {

    public function index() {
        $api = new \app\models\ApontadorApi();

        $placeId = \lithium\storage\Session::read('placeId');
        $placeName = \lithium\storage\Session::read('placeName');
        $zipcode = \lithium\storage\Session::read('zipcode');
        $cityState = \lithium\storage\Session::read('cityState');
        $lat = \lithium\storage\Session::read('lat');
        $lng = \lithium\storage\Session::read('lng');

        if (!empty($placeId)) {
            $placeJson = $api->getPlace(array('placeid' => $placeId));
            $place = json_decode($placeJson, false);
            $lat = $place->place->point->lat;
            $lng = $place->place->point->lng;
            $searchJson = $api->searchByPoint(array(
                        'lat' => $lat,
                        'lng' => $lng
                    ));
            $clear = array('zipcode', 'cityState', 'lat', 'lng');
        } elseif (!empty($zipcode)) {
            $searchJson = $api->searchByZipcode(array(
                        'zipcode' => $zipcode
                    ));
            $clear = array('placeId', 'cityState', 'lat', 'lng');
        } elseif (!empty($cityState) and strstr($cityState, ',')) {
            list($city, $state) = \explode(',', $cityState);
            $searchJson = $api->searchByAddress(array(
                        'city' => trim($city),
                        'state' => trim($state),
                        'country' => 'BR'
                    ));
            $clear = array('placeId', 'zipcode', 'lat', 'lng');
        } elseif (!empty($lat) and !empty($lng)) {
            $searchJson = $api->searchByPoint(array(
                        'lat' => $lat,
                        'lng' => $lng
                    ));
            $clear = array('placeId', 'zipcode', 'cityState');
        } else {
            $this->redirect('/places/checkin');
        }

        $search = json_decode($searchJson, false);

        return compact('search', 'placeId', 'placeName', 'zipcode', 'cityState', 'lat', 'lng');
    }

    public function checkin() {
        $title = "Estou aqui";
        $api = new \app\models\ApontadorApi();

        $placeId = null;
        $placeName = null;
        $zipcode = null;
        $cityState = null;
        $lat = null;
        $lng = null;

        if (!empty($_GET)) {
            $placeId = !empty($_GET['placeId']) ? $_GET['placeId'] : $placeId;
            if ($placeId) {
                $placeJson = $api->getPlace(array('placeid' => $placeId));
                $place = json_decode($placeJson, false);
                $placeName = $place->place->name;
                \lithium\storage\Session::write('placeName', $placeName);
            }
            \lithium\storage\Session::write('placeId', $placeId);

            $zipcode = !empty($_GET['cep']) ? $_GET['cep'] : $zipcode;
            \lithium\storage\Session::write('zipcode', $zipcode);

            $cityState = !empty($_GET['cityState']) ? $_GET['cityState'] : $cityState;
            \lithium\storage\Session::write('cityState', $cityState);

            $lat = !empty($_GET['lat']) ? $_GET['lat'] : $lat;
            $lng = !empty($_GET['lng']) ? $_GET['lng'] : $lng;
            \lithium\storage\Session::write('lat', $lat);
            \lithium\storage\Session::write('lng', $lng);

            if (!empty($placeId)) {
                $clear = array('zipcode', 'cityState', 'lat', 'lng');
            } elseif (!empty($zipcode)) {
                $clear = array('placeId', 'placeName', 'cityState', 'lat', 'lng');
            } elseif (!empty($cityState)) {
                $clear = array('placeId', 'placeName', 'zipcode', 'lat', 'lng');
            } elseif (!empty($lat) and !empty($lng)) {
                $clear = array('placeId', 'placeName', 'zipcode', 'cityState');
            } else {
                $clear = array();
            }

            foreach ($clear as $cookie) {
                \lithium\storage\Session::write($cookie);
            }

            $this->redirect('/');
        }

        return compact('placeId', 'placeName', 'zipcode', 'cityState', 'lat', 'lng');
    }

    public function show($placeid = null) {
        $api = new \app\models\ApontadorApi();
        $placeJson = $api->getPlace(array('placeid' => $placeid));

        if ($placeJson) {
            $place = json_decode($placeJson, false);

            switch ($place->place->average_rating) {
                case 1:
                    $place->place->average_rating = "Péssimo";
                    break;
                case 2:
                    $place->place->average_rating = "Ruim";
                    break;
                case 3:
                    $place->place->average_rating = "Regular";
                    break;
                case 4:
                    $place->place->average_rating = "Bom";
                    break;
                case 5:
                    $place->place->average_rating = "Excelente";
                    break;
            }
            return compact('place');
        } else {
            $this->redirect('/');
        }

    }

}

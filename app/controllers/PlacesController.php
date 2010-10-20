<?php

namespace app\controllers;
use app\models\Place;

class PlacesController extends \lithium\action\Controller {

    public function index() {
        $api = new \app\models\ApontadorApi();
        $zipcode = $_GET['cep'];
        $search = $api->searchByZipcode(array('zipcode'=>$zipcode));
		//$lat = "-23.593873718812";
		//$lng = "-46.688480447148";
        //$search = $place->searchByPoint($lat, $lng);
        return compact('search');
    }

    public function add() {
        $success = false;

        if ($this->request->data) {
            $place = Place::create($this->request->data);
            $success = $place->save();
        }

        return compact('success');
    }

}

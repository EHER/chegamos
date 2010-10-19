<?php

namespace app\controllers;
use app\models\Place;

class PlacesController extends \lithium\action\Controller {

    public function index() {
        $place = new \app\models\Place();
//        $zipcode = '02913-000';
//        $search = $place->searchByZipcode($zipcode);
		$lat = "-46.688480447148";
		$lng = "-23.593873718812";
        $search = $place->searchByPoint($lat, $lng);
		var_dump($search);
		exit;
        return compact('place');
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

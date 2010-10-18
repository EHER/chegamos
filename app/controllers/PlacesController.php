<?php

namespace app\controllers;
use app\models\Place;

class PlacesController extends \lithium\action\Controller {

    public function index() {
        $place = new \app\models\Place();
        $zipcode = '02913000';
        $place->searchByZipcode($zipcode);
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

<?php

namespace app\controllers;
use app\models\Place;

class PlacesController extends \lithium\action\Controller {

    public function index() {
        $place = new \app\models\Place($config);
        $place_name = $place->name;
        return compact('place', 'place_name');
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

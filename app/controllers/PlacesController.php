<?php

namespace app\controllers;

use app\models\Place,
	lithium\storage;

class PlacesController extends \lithium\action\Controller {

	public function index() {
		$api = new \app\models\ApontadorApi();
		$zipcode = isset($_GET['cep']) ? $_GET['cep'] : null;
		$zipcode = isset($_GET['cep']) ? $_GET['cep'] : \lithium\storage\Session::read('zipcode');
		$search = $api->searchByZipcode(array('zipcode' => $zipcode));
		$search = json_decode($search, false);
		//$lat = "-23.593873718812";
		//$lng = "-46.688480447148";
		//$search = $place->searchByPoint($lat, $lng);
		return compact('search', 'zipcode');
	}

	public function show($placeid = null) {
		$api = new \app\models\ApontadorApi();
		//$placeid = $_GET['id'];
		$place = $api->getPlace(array('placeid' => $placeid));
		$place = json_decode($place, false);

		return compact('place');
	}

	public function checkin($placeid = null) {
		$api = new \app\models\ApontadorApi();
		$title = "Estou aqui";

		$place = $api->getPlace(array('placeid' => $placeid));
		$place = json_decode($place, false);

		if ($this->request->data) {
			//$place = $api->checkin(array('placeid' => $placeid));
			\lithium\storage\Session::write('zipcode',$place->place->address->zipcode);
			$this->redirect('/');
		}

		return compact('place','title');
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

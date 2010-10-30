<?php

namespace app\controllers;

use app\models\Place;
use lithium\storage;

class PlacesController extends \lithium\action\Controller {

	public function index() {
		$placeId = \lithium\storage\Session::read('placeId');
		$placeName = \lithium\storage\Session::read('placeName');
		$zipcode = \lithium\storage\Session::read('zipcode');
		$cityState = \lithium\storage\Session::read('cityState');
		$lat = \lithium\storage\Session::read('lat');
		$lng = \lithium\storage\Session::read('lng');

		if (empty($placeId) && empty($placeName) && empty($zipcode) && empty($cityState) && (empty($lat) or empty($lng))) {
			$this->redirect('/places/checkin');
		}

		return compact('placeId', 'placeName', 'zipcode', 'cityState', 'lat', 'lng');
	}

	public function search() {
		$api = new \app\models\ApontadorApi();

		$placeId = \lithium\storage\Session::read('placeId');
		$placeName = \lithium\storage\Session::read('placeName');
		$zipcode = \lithium\storage\Session::read('zipcode');
		$cityState = \lithium\storage\Session::read('cityState');
		$lat = \lithium\storage\Session::read('lat');
		$lng = \lithium\storage\Session::read('lng');

		$searchName = '';

		if (isset($_GET['name'])) {
			$searchName = $_GET['name'];

			if (!empty($placeId)) {
				$place = $api->getPlace(array('placeid' => $placeId));
				$lat = $place->place->point->lat;
				$lng = $place->place->point->lng;
				$search = $api->searchByPoint(array(
							'term' => $searchName,
							'radius_mt' => 10000,
							'lat' => $lat,
							'lng' => $lng
								));
			} elseif (!empty($zipcode)) {
				$search = $api->searchByZipcode(array(
							'term' => $searchName,
							'radius_mt' => 10000,
							'zipcode' => $zipcode
								));
			} elseif (!empty($cityState) and strstr($cityState, ',')) {
				list($city, $state) = \explode(',', $cityState);
				$search = $api->searchByAddress(array(
							'term' => $searchName,
							'radius_mt' => 10000,
							'city' => trim($city),
							'state' => trim($state),
							'country' => 'BR'
								));
			} elseif (!empty($lat) and !empty($lng)) {
				$search = $api->searchByPoint(array(
							'term' => $searchName,
							'radius_mt' => 10000,
							'lat' => $lat,
							'lng' => $lng
								));
			} else {
				$this->redirect('/places/checkin');
			}
		}
		return compact('search', 'searchName', 'placeId', 'placeName', 'zipcode', 'cityState', 'lat', 'lng');
	}

	public function near() {
		$api = new \app\models\ApontadorApi();

		$placeId = \lithium\storage\Session::read('placeId');
		$placeName = \lithium\storage\Session::read('placeName');
		$zipcode = \lithium\storage\Session::read('zipcode');
		$cityState = \lithium\storage\Session::read('cityState');
		$lat = \lithium\storage\Session::read('lat');
		$lng = \lithium\storage\Session::read('lng');

		if (!empty($placeId)) {
			$place = $api->getPlace(array('placeid' => $placeId));
			$lat = $place->place->point->lat;
			$lng = $place->place->point->lng;
			$search = $api->searchRecursive(array(
						'lat' => $lat,
						'lng' => $lng
							), 'searchByPoint');
		} elseif (!empty($zipcode)) {
			$search = $api->searchRecursive(array(
						'zipcode' => $zipcode
							), 'searchByZipcode');
		} elseif (!empty($cityState) and strstr($cityState, ',')) {
			list($city, $state) = \explode(',', $cityState);
			$search = $api->searchRecursive(array(
						'city' => trim($city),
						'state' => trim($state),
						'country' => 'BR'
							), 'searchByAddress');
		} elseif (!empty($lat) and !empty($lng)) {
			$search = $api->searchRecursive(array(
						'lat' => $lat,
						'lng' => $lng
							), 'searchByPoint');
		} else {
			$this->redirect('/places/checkin');
		}

		return compact('search', 'placeId', 'placeName', 'zipcode', 'cityState', 'lat', 'lng');
	}

	public function categories() {
		$api = new \app\models\ApontadorApi();

		$placeId = \lithium\storage\Session::read('placeId');
		$placeName = \lithium\storage\Session::read('placeName');
		$zipcode = \lithium\storage\Session::read('zipcode');
		$cityState = \lithium\storage\Session::read('cityState');
		$lat = \lithium\storage\Session::read('lat');
		$lng = \lithium\storage\Session::read('lng');

		if (isset($_GET['all'])) {
			$categories = $api->getCategories();
		} else {
			$categories = $api->getCategoriesTop();
		}
		return compact('categories', 'placeId', 'placeName', 'zipcode', 'cityState', 'lat', 'lng');
	}

	public function category($categoryId) {
		if (empty($categoryId)) {
			$this->redirect('/places/categories');
		}
		$api = new \app\models\ApontadorApi();

		$placeId = \lithium\storage\Session::read('placeId');
		$placeName = \lithium\storage\Session::read('placeName');
		$zipcode = \lithium\storage\Session::read('zipcode');
		$cityState = \lithium\storage\Session::read('cityState');
		$lat = \lithium\storage\Session::read('lat');
		$lng = \lithium\storage\Session::read('lng');

		$category = $api->getSubcategories(array('categoryid' => $categoryId));

		$categoryName = $category->category->name;

		if (!empty($placeId)) {
			$place = $api->getPlace(array('placeid' => $placeId));
			$lat = $place->place->point->lat;
			$lng = $place->place->point->lng;
			$search = $api->searchRecursive(array(
						'category_id' => $categoryId,
						'lat' => $lat,
						'lng' => $lng
							), 'searchByPoint');
		} elseif (!empty($zipcode)) {
			$search = $api->searchRecursive(array(
						'category_id' => $categoryId,
						'zipcode' => $zipcode
							), 'searchByZipcode');
		} elseif (!empty($cityState) and strstr($cityState, ',')) {
			list($city, $state) = \explode(',', $cityState);
			$search = $api->searchRecursive(array(
						'category_id' => $categoryId,
						'city' => trim($city),
						'state' => trim($state),
						'country' => 'BR'
							), 'searchByAddress');
		} elseif (!empty($lat) and !empty($lng)) {
			$search = $api->searchRecursive(array(
						'category_id' => $categoryId,
						'lat' => $lat,
						'lng' => $lng
							), 'searchByPoint');
		} else {
			$this->redirect('/places/checkin');
		}

		return compact('search', 'categoryName', 'placeId', 'placeName', 'zipcode', 'cityState', 'lat', 'lng');
	}

	public function checkin() {
		$api = new \app\models\ApontadorApi();

		if (!empty($_GET)) {
			if (!empty($_GET['placeId'])) {
				$place = $api->getPlace(array('placeid' => $_GET['placeId']));
				$checkinData = array(
										'placeId' => $_GET['placeId'], 
										'placeName' => $place->place->name,
										'lat' => $place->place->point->lat,
										'lng' => $place->place->point->lng,
									);
			} elseif (!empty($_GET['lat']) and !empty($_GET['lng'])) {
				$checkinData = array('lat' => $_GET['lat'], 'lng' => $_GET['lng']);
			} elseif (!empty($_GET['cep'])) {
				$checkinData = array('zipcode' => $_GET['cep']);
			} elseif (!empty($_GET['cityState'])) {
				$checkinData = array('cityState' => $_GET['cityState']);
			} else {
				$checkinData = array();
			}

			$this->doCheckin($checkinData);

			$this->redirect('/');
		}
	}

	private function doCheckin(Array $checkinData = array()) {
		$checkinVars = array('zipcode', 'cityState', 'lat', 'lng', 'placeId', 'placeName');

		foreach ($checkinVars as $method) {
			\lithium\storage\Session::write($method);
		}

		foreach ($checkinData as $method => $value) {
			\lithium\storage\Session::write($method, $value);
		}
	}

	public function show($placeId = null) {
		if (empty($placeId)) {
			$this->redirect('/');
		}

		$api = new \app\models\ApontadorApi();
		$place = $api->getPlace(array('placeid' => $placeId));

		if ($place) {
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

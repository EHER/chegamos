<?php

namespace app\controllers;

use app\models\ApontadorApi;
use lithium\storage\Session;

class DealsController extends \lithium\action\Controller {

	var $api;

	public function __construct(array $config = array()) {
		$this->api = new ApontadorApi();
		parent::__construct($config);
	}

	public function near($page = 'page1') {
		extract($this->whereAmI());

		$page = str_replace('page', '', $page);

		if (!empty($placeId)) {
			$place = $this->api->getPlace(array('placeid' => $placeId));
			$lat = $place->getPoint()->lat;
			$lng = $place->getPoint()->lng;
			$dealsList = $this->api->searchDeals(array(
						'lat' => $lat,
						'lng' => $lng,
						'page' => $page
							));
		} elseif (!empty($zipcode)) {
			$dealsList = $this->api->searchDeals(array(
						'zipcode' => $zipcode,
						'page' => $page
							), 'searchByZipcode');
		} elseif (!empty($cityState) and strstr($cityState, ',')) {
			list($city, $state) = \explode(',', $cityState);
			$dealsList = $this->api->searchDeals(array(
						'city' => trim($city),
						'state' => trim($state),
						'country' => 'BR',
						'page' => $page
							));
		} elseif (!empty($lat) and !empty($lng)) {
			$dealsList = $this->api->searchDeals(array(
						'lat' => $lat,
						'lng' => $lng,
						'page' => $page
							));
		} else {
			$this->redirect('/places/checkin');
		}
		var_dump($dealsList);
		$title = "Ofertas por perto";
		return compact('title', 'page', 'geocode', 'dealsList', 'placeId', 'placeName', 'zipcode', 'cityState', 'lat', 'lng');
	}

	public function whereAmI() {
		$placeId = Session::read('placeId');
		$placeName = Session::read('placeName');
		$zipcode = Session::read('zipcode');
		$cityState = Session::read('cityState');
		$lat = Session::read('lat');
		$lng = Session::read('lng');
		$geocode = $this->api->geocode($lat, $lng);

		return compact('geocode', 'placeId', 'placeName', 'zipcode', 'cityState', 'lat', 'lng');
	}
}

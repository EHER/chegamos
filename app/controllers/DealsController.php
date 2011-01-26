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

	public function near() {
		extract(OauthController::whereAmI());

		if (!empty($lat) and !empty($lng)) {
			$dealsList = $this->api->searchDeals(array(
						'lat' => $lat,
						'lng' => $lng,
							));
		} else {
			$this->redirect('/places/checkin');
		}
		$title = "Ofertas por perto";
		return compact('title', 'page', 'geocode', 'dealsList', 'placeId', 'placeName', 'zipcode', 'cityState', 'lat', 'lng');
	}
}

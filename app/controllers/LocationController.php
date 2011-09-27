<?php

namespace app\controllers;

use app\models\Point;
use app\models\Location;
use app\models\ApontadorApi;
use lithium\action\Controller;
use lithium\storage\Session;

class LocationController extends Controller {
	private $api = null;
	private $location = null;
	private $isSessionEnabled = true;

	public function __construct($config = null) {
		$this->api = new ApontadorApi();
		$this->location = new Location();
		if(!empty($config)) {
			parent::__construct($config);
		}
	}

	public function setApi($api) {
		$this->api = $api;
	}

	public function disableSession() {
		$this->isSessionEnabled = false;
	}

	public function update($latitude = null, $longitude = null) {
		
		$latitude = empty($_GET['lat']) ? $latitude : $_GET['lat'];
		$longitude = empty($_GET['lng']) ? $longitude : $_GET['lng'];

		if(!empty($latitude) && !empty($longitude)) {

			$newLocation = $this->api->revgeocode($latitude, $longitude);

			$point = new Point();
			$point->setLat($latitude);
			$point->setLng($longitude);

			$this->location->setPoint($point);

			if(!empty($newLocation)) {
				$this->location->setAddress($newLocation);
			}
			if($this->isSessionEnabled) {
				$this->location->save();
			}
		}
		
		$success = (bool) $this->location->getPoint();
		$location = $this->location->toJson();
		
		return compact("success", "location");
	}

	public function current() {
		if($this->isSessionEnabled) {
			$this->location->load();
		}
		$location = $this->location->toJson();
		return compact('location');
	}
}



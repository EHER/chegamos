<?php

namespace app\models;

class Point {

	private $lat;
	private $lng;

	function __construct($data=null) {
		$this->populate($data);
	}

	public function populate($data) {
		if (isset($data->lat)) {
			$this->setLat($data->lat);
		}
		if (isset($data->lng)) {
			$this->setLng($data->lng);
		}
	}

	public function __toString() {
		if ($this->getLat() && $this->getLng()) {
			return $this->getLat() . ',' . $this->getLng();
		}
		return '';
	}

	public function getLat() {
		return $this->lat;
	}

	public function setLat($lat) {
		$this->lat = $lat;
	}

	public function getLng() {
		return $this->lng;
	}

	public function setLng($lng) {
		$this->lng = $lng;
	}

}
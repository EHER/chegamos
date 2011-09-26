<?php

namespace app\models;

use lithium\storage\Session;

class Location {
	private $point;
	private $address;

	public function __construct($data = null) {
		$this->populate($data);
	}
	
	public function populate($data) {
		$this->point = new Point();
		if(!empty($data->point->lat) && !empty($data->point->lng)) {
			$this->point->setLat($data->point->lat);
			$this->point->setLng($data->point->lng);
		}

		$this->address = new Address();
		if(!empty($data->address)) {
			$this->address->populate($data->address);
		}
	}

	public function save() {
		Session::write('location',serialize($this->toJson()));
	}

	public function load() {
		$this->populate(unserialize(Session::read('location')));
	}

	public function setPoint(Point $point) {
		$this->point = $point;
	}

	public function getPoint() {
		return $this->point;
	}

	public function setAddress(Address $address) {
		$this->address = $address;
	}

	public function getAddress() {
		return $this->address;
	}

	public function toJson() {
		$json = new \stdClass();
		if($this->getPoint()) {
			$json->point = $this->getPoint()->toJson();
		}
		if($this->getAddress()) {
			$json->address = $this->getAddress()->toJson();
		}

		return $json;
	}
}
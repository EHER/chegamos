<?php

namespace app\models;

class Visit {

	private $place = null;
	private $date = "";

	public function __construct($data=null) {
		$this->populate($data);
	}

	public function populate($data) {
		$this->setDate($data->date);
		$this->setPlace(new Place($data->place));
	}

	public function getPlace() {
		return $this->place;
	}

	public function setPlace($place) {
		$this->place = $place;
	}

	public function setDate($date) {
		$this->date = $date;
	}

	public function getDate() {
		return date("d/m/y H:i", strtotime($this->date));
	}

}

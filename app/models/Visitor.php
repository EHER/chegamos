<?php

namespace app\models;

class Visitor extends User {

	var $visits = "";
	var $lastVisit = "";

	public function __construct($data) {
		$this->populate($data);
	}

	public function populate($data) {
		parent::populate($data->user);
		$this->setVisits($data->visits);
		$this->setLastVisit($data->last_visit);
	}

	public function setVisits($visits) {
		$this->visits = $visits;
	}
	
	public function getVisits() {
		return $this->visits;
	}
	
	public function setLastVisit($lastVisit) {
		$this->lastVisit = $lastVisit;
	}
	
	public function getLastVisit() {
		return date("d/m/y H:i", strtotime($this->lastVisit));
	}
}

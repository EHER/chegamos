<?php

namespace app\models;

class Visitor extends User {

	var $id = "";
	var $name = "";

	public function __construct($data) {
		$this->populate($data);
	}

	public function populate($data) {
		parent::populate($data->user);
		var_dump($data);exit;
		$this->setId($data->id);
		$this->setName($data->name);
		$this->setBirthday($data->birthday);
	}

	public function setStats($stats) {
		$this->stats = $stats;
	}
	
	public function getStats() {
		return $this->stats;
	}
}

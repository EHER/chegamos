<?php

namespace app\models;

class Subcategory {

	var $id = "";
	var $name = "";

	public function __construct($data) {
		$this->populate($data);
	}

	public function populate($data) {
		$this->setId($data->id);
		$this->setName($data->name);
	}

	public function setId($id) {
		$this->id = $id;
	}

	public function getId() {
		return $this->id;
	}

	public function setName($name) {
		$this->name = $name;
	}

	public function getName() {
		return $this->name;
	}
}

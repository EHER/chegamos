<?php

namespace app\models;

class Category {

	var $id = "";
	var $name = "";
	var $subcategory = "";

	public function __construct($data) {
		$this->populate($data);
	}

	public function populate($data) {
		$this->setId($data->id);
		$this->setName($data->name);
		$this->setSubcategory(new Subcategory($data->subcategory));
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

	public function setSubcategory($subcategory) {
		$this->subcategory = $subcategory;
	}

	public function getSubcategory() {
		return $this->subcategory;
	}
}

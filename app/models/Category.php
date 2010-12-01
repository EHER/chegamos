<?php

namespace app\models;

use lithium\util\Inflector;

class Category {

	var $id = "";
	var $name = "";
	var $subcategory = "";

	public function __construct($data=null) {
		$this->populate($data);
	}

	public function populate($data) {
		if (isset($data->id)) {
			$this->setId($data->id);
		}
		if (isset($data->name)) {
			$this->setName($data->name);
		}
		if (isset($data->subcategory)) {
			$this->setSubcategory(new Subcategory($data->subcategory));
		}
	}

	public function __toString() {
		$category = $this->getName();
		$category .= $this->getSubcategory() ? ' - ' . $this->getSubcategory() : '';
		return $category;
	}

	public function setId($id) {
		$this->id = $id;
	}

	public function getId() {
		return $this->id;
	}

	public function setName($name) {
		$this->name = Inflector::formatTitle($name);
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

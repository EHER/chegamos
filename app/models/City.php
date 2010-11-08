<?php

namespace app\models;
use lithium\util\Inflector;

class City {

	private $country = "";
	private $state = "";
	private $name = "";

	public function __construct($data) {
		$this->populate($data);
	}
	
	public function populate($data) {
		$this->setCountry($data->country);
		$this->setState($data->state);
		$this->setName($data->name);
	}
	
	public function __toString() {
		return $this->getName() . ' - ' . $this->getState(); 
	}
	
	public function setCountry($country) {
		$this->country = $country;
	}
	
	public function getCountry() {
		return $this->country;
	}
	
	public function setState($state) {
		$this->state = $state;
	}
	
	public function getState() {
		return $this->state;
	}
	
	public function setName($name) {
		$this->name = Inflector::formatTitle($name);
	}
	
	public function getName() {
		return $this->name;
	}
}

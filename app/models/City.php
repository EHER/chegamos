<?php

namespace app\models;
use lithium\util\Inflector;

class City {

	private $country = "";
	private $state = "";
	private $name = "";
	private $formatter = null;

	public function __construct($data=null) {
		$this->populate($data);
	}
	
	public function populate($data) {
		if(!empty($data->country)) {
			$this->setCountry($data->country);
		}
		if(!empty($data->state)) {
			$this->setState($data->state);
		}
		if(!empty($data->name)) {
			$this->setName($data->name);
		}
	}
	
	public function __toString() {
		return $this->format($this->getName()) . ($this->getState() ? ' - ' . $this->getState() : '');

	}

	public function setFormatter($formater) {
		$this->formatter = $formater;
	}

	public function format($string = '', $type = 'formatTitle') {
		if(empty($this->formatter)) {
			return $string;
		} else {
			return $this->formatter->$type($string);
		}
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
		$this->name = $name;
	}
	
	public function getName() {
		return $this->name;
	}
}

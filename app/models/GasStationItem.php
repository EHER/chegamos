<?php

namespace app\models;

class GasStationItem {

	var $label;
	var $value;
	var $averageValue;
	var $collectDate;

	public function __toString() {
		return $this->getLabel() . ': R$ ' . $this->getValue() . 
			  ' (média R$ ' . $this->getAverageValue() . ')';
	}

	public function setLabel($label) {
		$this->label = $label;
	}
	
	public function getLabel() {
		return $this->label;
	}
	
	public function setValue($value) {
		$this->value = $value;
	}
	
	public function getValue() {
		return $this->value;
	}
	
	public function setAverageValue($averageValue) {
		$this->averageValue = $averageValue;
	}
	
	public function getAverageValue() {
		return $this->averageValue;
	}
	
	public function setCollectDate($collectDate) {
		$this->collectDate = $collectDate;
	}
	
	public function getCollectDate() {
		return $this->collectDate;
	}

}

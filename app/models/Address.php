<?php

namespace app\models;
use lithium\util\Inflector;

class Address {

	var $street = "";
	var $number = 0;
	var $complement = "";
	var $district = "";
	var $zipcode = "";
	var $city = null;

	public function __construct($data) {
		$this->populate($data);
	}
	
	public function populate($data) {
		$this->setStreet($data->street);
		$this->setNumber($data->number);
		$this->setComplement($data->complement);
		$this->setDistrict($data->district);
		$this->setZipcode($data->zipcode);
		$this->setCity(new City($data->city));
	}
	
	public function __toString() {
		$data = $this->getStreet();
		$data .= $this->getNumber() ? ', ' . $this->getNumber() : '';
		$data .= $this->getDistrict() ? ' - ' . $this->getDistrict() : '';
		$data .= $this->getCity() ? '<br />' . $this->getCity() : '';
		return $data;
	}
	
	public function getStreetCity() {
		$data = $this->getStreet();
		$data .= $this->getCity() ? ' em ' . $this->getCity() . '' : '';
		return $data;
	}

	public function getDistrictCity() {
		$data = $this->getDistrict() ? $this->getDistrict() : '';
		$data .= $this->getCity() ? ' em ' . $this->getCity() . '' : '';
		return $data;
	}

	public function setStreet($street) {
		$this->street = Inflector::formatTitle($street);
	}
	
	public function getRouteAddress() {
		$data = $this->getStreet();
		$data .= $this->getNumber() ? ', ' . $this->getNumber() : '';
		$data .= $this->getCity() ? ', ' . $this->getCity() : '';
		$data .= $this->getCity()->getState() ? ' - ' . $this->getCity()->getState() : '';
		return $data;
	}
	
	public function getStreet() {
		return $this->street;
	}
	
	public function setNumber($number) {
		$this->number = $number;
	}
	
	public function getNumber() {
		return $this->number;
	}
	
	public function setComplement($complement) {
		$this->complement = Inflector::formatTitle($complement);
	}
	
	public function getComplement() {
		return $this->complement;
	}
	
	public function setDistrict($district) {
		$this->district = Inflector::formatTitle($district);
	}
	
	public function getDistrict() {
		return $this->district;
	}
	
	public function setZipcode($zipcode) {
		$this->zipcode = $zipcode;
	}
	
	public function getZipcode() {
		return $this->zipcode;
	}
	
	public function setCity($city) {
		$this->city = $city;
	}
	
	public function getCity() {
		return $this->city;
	}

}

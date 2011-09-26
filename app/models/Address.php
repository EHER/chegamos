<?php

namespace app\models;
use lithium\util\Inflector;

class Address {

	private $street = "";
	private $number = 0;
	private $complement = "";
	private $district = "";
	private $zipcode = "";
	private $city = null;

	public function __construct($data=null) {
		$this->populate($data);
	}

	public function populate($data) {
		if(isset($data->street)) {
			$this->setStreet($data->street);
		}
		if(isset($data->number)) {
			$this->setNumber($data->number);
		}
		if(isset($data->complement)) {
			$this->setComplement($data->complement);
		}
		if(isset($data->district)) {
			$this->setDistrict($data->district);
		}
		if(isset($data->zipcode)) {
			$this->setZipcode($data->zipcode);
		}
		if(isset($data->city)) {
			$this->setCity(new City($data->city));
		}
	}

	public function __toString() {
		$data = $this->getStreet();
		$data .= $this->getNumber() ? ', ' . $this->getNumber() : '';
		$data .= $this->getDistrict() ? ' - ' . $this->getDistrict() : '';
		$data .= $this->getCity() ? '<br/>' . $this->getCity() : '';
		return $data;
	}

	public function toOneLine() {
		$addressArray = array_filter($this->toArray());
		return implode(', ', $addressArray);
	}
	
	public function toArray() {
		return array(
			'street' => $this->getStreet(),
			'district' => $this->getDistrict(),
			'city' => $this->getCity() ? $this->getCity()->getName() : null,
			'state' => $this->getCity() ? $this->getCity()->getState() : null
		);
	}

	public function toJson() {
		$json = new \stdClass();
		if($this->getStreet()){
			$json->street = $this->getStreet();
		}
		if($this->getDistrict()){
			$json->district = $this->getDistrict();
		}
		if($this->getZipcode()){
			$json->zipcode = $this->getZipcode();
		}
		if($this->getCity() instanceof City) {
			$json->city->name = $this->getCity()->getName();
			$json->city->state = $this->getCity()->getState();
		}

		return $json;
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

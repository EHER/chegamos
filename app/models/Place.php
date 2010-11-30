<?php

namespace app\models;
use lithium\util\Inflector;

class Place {

	var $id = "";
	var $name = "";
	var $averageRating = 0;
	var $reviewCount = 0;
	var $category = null;
	var $subcategory = null;
	var $address = null;
	var $point = null;
	var $mainUrl = "";
	var $iconUrl = "";
	var $otherUrl = "";
	var $description = "";
	var $created = null;
	var $phone = null;
	var $visitors = null;
	var $placeInfo = null;
	var $numVisitors = 0;
	var $numPhotos = 0;

	public function __construct($data=null) {
		$this->populate($data);
	}
	
	public function populate($data) {	
		$this->setId($data->id);
		$this->setName($data->name);
		$this->setAverageRating($data->average_rating);
		$this->setReviewCount($data->review_count);
		$this->setCategory(new Category($data->category));
		$this->setAddress(new Address($data->address));
		$this->setPoint($data->point);
		$this->setMainUrl($data->main_url);
		$this->setOtherUrl($data->other_url);
		$this->setIconUrl($data->icon_url);
		
		if (isset($data->description)) {
			$this->setDescription($data->description);
		}
		
		if (isset($data->created)) {
			$this->setCreated($data->created);
		}
		
		if (isset($data->phone)) {
			$this->setPhone($data->phone);
		}
		
		if (isset($data->extended)) {
			$this->setPlaceInfo(new PlaceInfo($data->extended));
		}
	}
	
	public function setId($id) {
		$this->id = $id;
	}
	
	public function getId() {
		return $this->id;
	}
	
	public function setNumVisitors($numVisitors) {
		$this->numVisitors = $numVisitors;
	}
	
	public function getNumVisitors() {
		return $this->numVisitors;
	}
	
	public function setNumPhotos($numPhotos) {
		$this->numPhotos = $numPhotos;
	}
	
	public function getNumPhotos() {
		return $this->numPhotos;
	}
	
	public function setName($name) {
		$this->name = Inflector::formatTitle($name);
	}
	
	public function getName() {
		return $this->name;
	}
	
	public function setCreated($created) {
		$this->created = $created;
	}
	
	public function getCreated() {
		return $this->created;
	}
	
	public function setPlaceInfo($placeInfo) {
		$this->placeInfo = $placeInfo;
	}
	
	public function getPlaceInfo() {
		return $this->placeInfo;
	}

	public function setPhone($phone) {
		$this->phone = $phone;
	}
	
	public function getPhone() {
		return $this->phone;
	}
	public function setDescription($description) {
		$this->description = $description;
	}
	
	public function getDescription() {
		return $this->description;
	}
	
	public function setAverageRating($averageRating) {
		$this->averageRating = $averageRating;
	}
	
	public function getAverageRating() {
		return $this->averageRating;
	}
	
	public function getAverageRatingString() {
		switch ($this->getAverageRating()) {
			case 1:
				return "Péssimo";
			case 2:
				return "Ruim";
			case 3:
				return "Regular";
			case 4:
				return "Bom";
			case 5:
				return "Excelente";
			default:
				return '';
				break;
		}
	}
	
	public function setReviewCount($reviewCount) {
		$this->reviewCount = $reviewCount;
	}
	
	public function getReviewCount() {
		return $this->reviewCount;
	}
	
	public function setCategory($category) {
		$this->category = $category;
	}
	
	public function getCategory() {
		return $this->category;
	}
	
	public function setAddress($address) {
		$this->address = $address;
	}
	
	public function getAddress() {
		return $this->address;
	}
	
	public function setPoint($point) {
		$this->point = $point;
	}
	
	public function getPoint() {
		return $this->point;
	}
	
	public function setMainUrl($mainUrl) {
		$this->mainUrl = $mainUrl;
	}
	
	public function getMainUrl() {
		return $this->mainUrl;
	}
	
	public function setOtherUrl($otherUrl) {
		$this->otherUrl = $otherUrl;
	}
	
	public function getOtherUrl() {
		return $this->otherUrl;
	}
	
	public function setIconUrl($iconUrl) {
		$this->iconUrl = $iconUrl;
	}
	
	public function getIconUrl() {
		return $this->iconUrl;
	}
	
	public function getRouteUrl($userAddress, $lat, $lng) {
		$routeUrl = "http://maps.google.com.br/m/directions";
		
		$params = array();

		$params['dirflg'] = 'd';
		
		$params['daddr'] = $this->address->getRouteAddress();

		if ($userAddress instanceof Address) {
			$params['saddr'] = $userAddress->getRouteAddress();
		}
		
		return $routeUrl . '?' . http_build_query($params);
	}
}

<?php

namespace app\models;

use lithium\util\Inflector;

class Place {

	private $id = "";
	private $name = "";
	private $averageRating = 0;
	private $reviewCount = 0;
	private $category = null;
	private $subcategory = null;
	private $address = null;
	private $point = null;
	private $mainUrl = "";
	private $iconUrl = "";
	private $otherUrl = "";
	private $description = "";
	private $created = null;
	private $phone = null;
	private $placeInfo = null;
	private $numVisitors = 0;
	private $numPhotos = 0;

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

		if (isset($data->average_rating)) {
			$this->setAverageRating($data->average_rating);
		}

		if (isset($data->review_count)) {
			$this->setReviewCount($data->review_count);
		}

		if (!empty($data->category)) {
			$this->setCategory(new Category($data->category));
		}

		if (!empty($data->subcategory)) {
			$this->setSubcategory(new Category($data->subcategory));
		}

		if (isset($data->address)) {
			$this->setAddress(new Address($data->address));
		}

		if (isset($data->point)) {
			$this->setPoint(new Point($data->point));
		}

		if (isset($data->main_url)) {
			$this->setMainUrl($data->main_url);
		}

		if (isset($data->other_url)) {
			$this->setOtherUrl($data->other_url);
		}

		if (isset($data->icon_url)) {
			$this->setIconUrl($data->icon_url);
		}

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

		if (isset($data->num_visitors)) {
			$this->setNumVisitors($data->num_visitors);
		}

		if (isset($data->num_photos)) {
			$this->setNumPhotos($data->num_photos);
		}
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

	public function getSubcategory() {
		return $this->subcategory;
	}

	public function setSubcategory($subcategory) {
		$this->subcategory = $subcategory;
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

	public function getShortPlaceUrl() {
		return ROOT_URL . 'places/show/' . $this->getId();
	}

	public function getPlaceUrl() {
		return ROOT_URL .
			\strtolower(Inflector::slug($this->getAddress()->getCity()->getState())) .
			'/' . \strtolower(Inflector::slug($this->getAddress()->getCity()->getName())) .
					'/' . \strtolower(Inflector::slug($this->getCategory())) .
					'/' . \strtolower(Inflector::slug($this->getName())) .
			'/' . $this->getId() .
			'.html';
	}

	public function getMapUrl() {
		if ($this->getPoint()->getLat() && $this->getPoint()->getLng()) {
			$mapUrl = "http://maplink.com.br/widget";

			$params = array();

			$params['v'] = '4.1';
			$params['lat'] = $this->getPoint()->getLat();
			$params['lng'] = $this->getPoint()->getLng();

			return $mapUrl . '?' . http_build_query($params);
		}
		return false;
	}

	public function getRouteUrl($location) {
		$routeUrl = "http://maps.google.com.br/m/directions";

		$params = array();

		$params['dirflg'] = 'd';

		$params['daddr'] = $this->address->getRouteAddress();

		if ($location->getAddress() instanceof Address) {
			$params['saddr'] = $location->getAddress()->getRouteAddress();
		}

		return $routeUrl . '?' . http_build_query($params);
	}

}

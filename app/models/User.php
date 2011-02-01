<?php

namespace app\models;

class User {

	private $id = "";
	private $name = "";
	private $birthday = null;
	private $gender = null;
	private $photoUrl = null;
	private $photoMediumUrl = null;
	private $photoSmallUrl = null;
	private $stats = null;
	private $places = null;
	private $reviews = null;
	private $photos = null;
	private $lastVisit = null;

	public function __construct($data) {
		$this->populate($data);
	}

	public function populate($data) {
		$this->setId($data->id);
		$this->setName($data->name);
		$this->setBirthday(isset($data->birthday) ? $data->birthday : '');
		$this->setGender(isset($data->gender) ? $data->gender : '');

		if (isset($data->photo_medium_url)) {
			$this->setPhotoMediumUrl($data->photo_medium_url);
		} else if (isset($data->photo_medium)) {
			$this->setPhotoMediumUrl($data->photo_medium);
		}

		if (isset($data->photo_url)) {
			$this->setPhotoUrl($data->photo_url);
		} else if (isset($data->photo)) {
			$this->setPhotoUrl($data->photo);
		}

		if (isset($data->photo_small_url)) {
			$this->setPhotoSmallUrl($data->photo_small_url);
		} else if (isset($data->photo_small)) {
			$this->setPhotoSmallUrl($data->photo_small);
		}

		if (isset($data->places)) {
			$this->setPlaces(new PlaceList($data));
		}

		if (isset($data->reviews)) {
			$this->setReviews(new ReviewList($data));
		}
		
		if (isset($data->photos)) {
			$this->setPhotos(new PhotoList($data));
		}

		if (isset($data->last_visit->place)) {
			$this->setLastVisit(new Place($data->last_visit->place));
		} else {
			$this->setLastVisit(new Place());
		}

		$this->setStats(isset($data->stats) ? new UserStats($data->stats) : null);
	}

	public function getUserInfo() {
		$userInfo = array();
		if ($this->getGender()) {
			$userInfo[] = $this->getGender();
		}

		if ($this->getAge()) {
			$userInfo[] = $this->getAge();
		}

		return implode(", ", $userInfo);
	}

	public function getLastVisitInfo($returnLink = false) {
		$lastVisitInfo = '';

		if ($this->getLastVisit()->getName()) {
			$lastVisitInfo .= 'Último check-in: ';
			$lastVisitInfo .= $returnLink ? '<a href="' . $this->getLastVisit()->getPlaceUrl() . '">' : '';
			$lastVisitInfo .= $this->getLastVisit()->getName();
			$lastVisitInfo .= $returnLink ? '</a>' : '';

			return $lastVisitInfo;
		}
		return false;
	}

	public function setPlaces($places) {
		$this->places = $places;
	}

	public function getPlaces() {
		return $this->places;
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

	public function getAge() {
		if ($this->getBirthday()) {
			list($day, $month, $year) = explode("/", $this->getBirthday());

			$year = $year < 1900 ? $year + 1900 : $year;

			$year_diff = date("Y") - $year;
			$month_diff = date("m") - $month;
			$day_diff = date("d") - $day;
			if ($month_diff < 0) {
				$year_diff--;
			} elseif (($month_diff == 0) && ($day_diff < 0)) {
				$year_diff--;
			}
			return $year_diff . ' anos';
		}
		return false;
	}

	public function setBirthday($birthday) {
		$this->birthday = $birthday;
	}

	public function getBirthday() {
		if ($this->birthday != null) {
			return date("d/m/y", strtotime($this->birthday));
		}
		return false;
	}

	public function setGender($gender) {
		$this->gender = $gender;
	}

	public function getGender() {
		switch ($this->gender) {
			case 'M':
				return 'Masculino';
			case 'F':
				return 'Feminino';
			default:
				return false;
		}
	}

	public function setPhotoUrl($photoUrl) {
		$this->photoUrl = $photoUrl;
	}

	public function getPhotoUrl() {
		return $this->photoUrl;
	}

	public function setPhotoSmallUrl($photoSmallUrl) {
		$this->photoSmallUrl = $photoSmallUrl;
	}

	public function getPhotoSmallUrl() {
		return $this->photoSmallUrl;
	}

	public function setPhotoMediumUrl($photoMediumUrl) {
		$this->photoMediumUrl = $photoMediumUrl;
	}

	public function getPhotoMediumUrl() {
		return $this->photoMediumUrl;
	}

	public function setStats($stats) {
		$this->stats = $stats;
	}

	public function getStats() {
		return $this->stats;
	}

	public function getLastVisit() {
		return $this->lastVisit;
	}

	public function setLastVisit($lastVisit) {
		$this->lastVisit = $lastVisit;
	}

	public function getReviews() {
		return $this->reviews;
	}

	public function setReviews($reviews) {
		$this->reviews = $reviews;
	}

	public function getPhotos() {
		return $this->photos;
	}

	public function setPhotos($photos) {
		$this->photos = $photos;
	}

	public function getProfileUrl() {
		if ($this->getId()) {
			return ROOT_URL . 'profile/show/' . $this->getId();
		}
		return false;
	}

}

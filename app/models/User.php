<?php

namespace app\models;

class User {

	var $id = "";
	var $name = "";
	var $birthday = null;
	var $gender = null;
	var $photoUrl = null;
	var $photoMediumUrl = null;
	var $photoSmallUrl = null;
	var $stats = null;

	public function __construct($data) {
		$this->populate($data);
	}

	public function populate($data) {	
		$this->setId($data->id);
		$this->setName($data->name);
		$this->setBirthday($data->birthday);
		$this->setGender($data->gender);
		$this->setPhotoUrl($data->photo_url);
		$this->setPhotoMediumUrl($data->photo_medium_url);
		$this->setPhotoSmallUrl($data->photo_small_url);
		$this->setStats(new UserStats($data->stats));
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
			
			$year_diff  = date("Y") - $year;
			$month_diff = date("m") - $month;
			$day_diff   = date("d") - $day;
			if ($month_diff < 0) {
				$year_diff--;
			} elseif (($month_diff==0) && ($day_diff < 0)) {
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
		return date("d/m/y", strtotime($this->birthday));
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
}

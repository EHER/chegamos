<?php

namespace app\models;

class UserStats {

	var $places = 0;
	var $photos = 0;
	var $reviews = 0;

	public function __construct($data) {
		$this->populate($data);
	}

	public function populate($data) {
		$this->setPlaces($data->places);
		$this->setPhotos($data->photos);
		$this->setReviews($data->reviews);
	}
	
	public function setPlaces($places) {
		$this->places = $places;
	}
	
	public function getPlaces() {
		return $this->places;
	}
	
	public function setPhotos($photos) {
		$this->photos = $photos;
	}
	
	public function getPhotos() {
		return $this->photos;
	}
	
	public function setReviews($reviews) {
		$this->reviews = $reviews;
	}
	
	public function getReviews() {
		return $this->reviews;
	}
}

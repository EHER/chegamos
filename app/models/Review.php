<?php

namespace app\models;

class Review {

	private $id = "";
	private $place = null;
	private $rating = 0;
	private $content = "";
	private $created = null;

	public function __construct($data=null) {
		$this->populate($data);
	}

	public function populate($data) {
		if (isset($data->id)) {
			$this->setId($data->id);
		}

		if (isset($data->place)) {
			$this->setPlace(New Place($data->place));
		}

		if (isset($data->rating)) {
			$this->setRating($data->rating);
		}

		if (isset($data->content)) {
			$this->setContent($data->content);
		}

		if (isset($data->created)) {
			$this->setCreated($data->created);
		}
	}

	public function getId() {
		return $this->id;
	}

	public function setId($id) {
		$this->id = $id;
	}

	public function getPlace() {
		return $this->place;
	}

	public function setPlace($place) {
		$this->place = $place;
	}

	public function getRating() {
		return $this->rating;
	}

	public function setRating($rating) {
		$this->rating = $rating;
	}

	public function getContent() {
		return $this->content;
	}

	public function setContent($content) {
		$this->content = $content;
	}

	public function getCreated() {
		return $this->created;
	}

	public function setCreated($created) {
		$this->created = $created;
	}

}

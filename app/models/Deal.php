<?php

namespace app\models;

class Deal {

	private $id = "";
	private $place = null;
	private $url = "";
	private $title = "";
	private $imageUrl = "";

	public function __construct($data) {
		$this->populate($data);
	}

	public function populate($data) {
		$this->setId(isset($data->id) ? $data->id : '');
		$this->setPlace(isset($data->place) ? new Place($data->place) : null);
		$this->setUrl($data->url);
		$this->setTitle($data->title);
		$this->setImageUrl($data->image_url);
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

	public function getUrl() {
		return $this->url;
	}

	public function setUrl($url) {
		$this->url = $url;
	}

	public function getTitle() {
		return $this->title;
	}

	public function setTitle($title) {
		$this->title = $title;
	}

	public function getImageUrl() {
	 return $this->imageUrl;
	}

	public function setImageUrl($imageUrl) {
	 $this->imageUrl = $imageUrl;
	}
}

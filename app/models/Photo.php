<?php

namespace app\models;
use lithium\util\Inflector;

class Photo {
	var $photoUrl = '';

	public function __construct($data) {
		$this->populate($data);
	}

	public function populate($data) {
		$this->setPhotoUrl($data);
	}

	public function getPhotoUrl() {
		return $this->photoUrl;
	}

	public function setPhotoUrl($photoUrl) {
		$this->photoUrl = $photoUrl;
	}
}
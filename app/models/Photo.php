<?php

namespace app\models;
use lithium\util\Inflector;

class Photo {
	var $url = '';

	public function __construct($data) {
		$this->populate($data);
	}

	public function populate($data) {
		$this->setUrl($data);
	}

	public function getUrl() {
		return $this->url;
	}

	public function setUrl($url) {
		$this->url = $url;
	}
}
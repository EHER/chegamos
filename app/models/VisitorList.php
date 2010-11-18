<?php

namespace app\models;

class VisitorList extends ItemsList {
	var $placeId = '';

	public function __construct($data = null) {
		if (!empty($data)) {
			$this->setNumFound(count($data));
			foreach ($data as $visitor) {
				$this->add(New Visitor($visitor->visitor));
			}
		}
	}

	public function getPlaceId() {
		return $this->placeId;
	}

	public function setPlaceId($placeId) {
		$this->placeId = $placeId;
	}
}
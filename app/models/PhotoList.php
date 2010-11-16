<?php

namespace app\models;

class PhotoList extends ItemsList {
	var $placeId = '';

	public function __construct($data = null) {
		if (!empty($data)) {
			$this->setNumFound(count($data->place->photos));
			$this->setPlaceId($data->place->id);
			foreach ($data->place->photos as $photo) {
				$this->add(New Photo($photo));
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
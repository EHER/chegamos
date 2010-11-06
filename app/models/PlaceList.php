<?php

namespace app\models;

class PlaceList extends ItemsList {

	public function __construct($data) {
		$this->setNumFound($data->result_count);
		
		foreach ($data->places as $place) {
			$this->add(new Place($place->place));
		}
	}
}

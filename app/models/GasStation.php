<?php

namespace app\models;

class GasStation extends ItemsList {

	public function __construct($data) {
		$this->populate($data);
	}
	
	public function populate($data) {
		foreach ($data as $itemName => $value) {
			if (strstr($itemName, 'price_') !== false) {
				$this->add(GasStationItemFactory::generate($itemName, $data));
			}
		}
	}
}

<?php

namespace app\models;

class PlaceInfo {

	var $gasStation = "";

	public function __construct($data) {
		$this->populate($data);
	}
	
	public function populate($data) {
		$this->setGasStation(new GasStation($data->gas_station));
	}
	
	public function setGasStation($gasStation) {
		$this->gasStation = $gasStation;
	}
	
	public function getGasStation() {
		return $this->gasStation;
	}

}

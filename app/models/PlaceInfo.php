<?php

namespace app\models;

class PlaceInfo {

	var $gasStation = "";

	public function __construct($data=null) {
		$this->populate($data);
	}
	
	public function populate($data) {
		if(isset($data->gas_station)) {
			$this->setGasStation(new GasStation($data->gas_station));
		}
	}
	
	public function setGasStation($gasStation) {
		$this->gasStation = $gasStation;
	}
	
	public function getGasStation() {
		return $this->gasStation;
	}

}

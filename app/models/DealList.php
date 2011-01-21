<?php

namespace app\models;

class DealList extends ItemsList {

	var $lat = '';
	var $lng = '';
	var $currentPage = 0;

	public function __construct($data = null) {
		if (!empty($data)) {
			var_dump($data);
//			$this->setNumFound($data->result_count);
//			$this->setCurrentPage($data->current_page);
//			$this->setLat($data->point->lat);
//			$this->setLng($data->point->lng);
			foreach ($data->deals as $deal) {
				$this->add(New Deal($deal->deal));
			}
		}
	}

	public function getLat() {
		return $this->lat;
	}

	public function setLat($lat) {
		$this->lat = $lat;
	}

	public function getLng() {
		return $this->lng;
	}

	public function setLng($lng) {
		$this->lng = $lng;
	}

	public function setCurrentPage($currentPage) {
		$this->currentPage = $currentPage;
	}

	public function getCurrentPage() {
		return $this->currentPage;
	}

}
<?php

namespace app\models;

class PlaceList {

	var $items = array();
	var $numFound = 0;

	public function __construct($data) {
		$this->setNumFound($data->result_count);
		
		foreach ($data->places as $place) {
			$this->add(new Place($place->place));
		}
	}
	
	public function getItem($index = 0) {
		$items = $this->getItems();
		return $items[$index];
	}
	
	public function getNumFound() {
		return $this->numFound;
	}
	
	public function setNumFound($numFound = 0) {
		$this->numFound = $numFound;
	}
	
	public function add($item) {
		$this->items[] = $item;
	}
	
	public function getItems() {
		return $this->items;
	}

	public function setItems($items) {
		$this->items = $items;
	}
}

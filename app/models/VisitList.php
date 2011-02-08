<?php

namespace app\models;

class VisitList extends ItemsList {

	private $currentPage;

	public function __construct($data) {
		$this->populate($data);
	}

	public function populate($data) {
		if (isset($data->result_count)) {
			$this->setNumFound($data->result_count);
		}
		if (isset($data->current_page)) {
			$this->setCurrentPage($data->current_page);
		}
		if (isset($data->visits)) {
			foreach ($data->visits as $place) {
				$this->add(new Visit($place->visit));
			}
		}
	}

	public function setRadius($radius) {
		$this->radius = $radius;
	}

	public function getRadius() {
		return $this->radius;
	}

	public function setCurrentPage($currentPage) {
		$this->currentPage = $currentPage;
	}

	public function getCurrentPage() {
		return $this->currentPage;
	}

	public function addUnique($newItem) {
		$isUnique = true;

		foreach ($this->getItems() as $item) {
			if ($newItem->getId() == $item->getId()) {
				$isUnique = false;
				break;
			}
		}

		if ($isUnique) {
			$this->add($newItem);
			$this->setNumFound($this->getNumFound() + 1);
		}
	}

}

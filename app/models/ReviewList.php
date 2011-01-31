<?php

namespace app\models;

class ReviewList extends ItemsList {

	private $id = "";
	private $name = "";
	private $photoLargeUrl = "";
	private $photoUrl = "";
	private $photoMediumUrl = "";
	private $photoSmallUrl = "";
	private $currentPage;

	public function __construct($data = null) {
		if ($data != null) {
			$this->setId($data->id);
			$this->setName($data->name);
			$this->setPhotoLargeUrl($data->photo_large_url);
			$this->setPhotoUrl($data->photo_url);
			$this->setPhotoMediumUrl($data->photo_medium_url);
			$this->setPhotoSmallUrl($data->photo_small_url);
			$this->setNumFound($data->result_count);
			$this->setCurrentPage($data->current_page);

			foreach ($data->reviews as $place) {
				$this->add(new Review($place->review));
			}
		}
	}

	public function setCurrentPage($currentPage) {
		$this->currentPage = $currentPage;
	}

	public function getCurrentPage() {
		return $this->currentPage;
	}

	public function getId() {
		return $this->id;
	}

	public function setId($id) {
		$this->id = $id;
	}

	public function getName() {
		return $this->name;
	}

	public function setName($name) {
		$this->name = $name;
	}

	public function getPhotoLargeUrl() {
		return $this->photoLargeUrl;
	}

	public function setPhotoLargeUrl($photoLargeUrl) {
		$this->photoLargeUrl = $photoLargeUrl;
	}

	public function getPhotoUrl() {
		return $this->photoUrl;
	}

	public function setPhotoUrl($photoUrl) {
		$this->photoUrl = $photoUrl;
	}

	public function getPhotoMediumUrl() {
		return $this->photoMediumUrl;
	}

	public function setPhotoMediumUrl($photoMediumUrl) {
		$this->photoMediumUrl = $photoMediumUrl;
	}

	public function getPhotoSmallUrl() {
		return $this->photoSmallUrl;
	}

	public function setPhotoSmallUrl($photoSmallUrl) {
		$this->photoSmallUrl = $photoSmallUrl;
	}

}

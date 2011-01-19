<?php

namespace app\models;

class FollowersList extends ItemsList {

	var $userId = '';
	var $currentPage = 0;

	public function __construct($data = null) {
		if (!empty($data)) {
			$this->setNumFound($data->result_count);
			$this->setCurrentPage($data->current_page);
			$this->setUserId($data->id);
			foreach ($data->users as $user) {
				$this->add(New User($user->user));
			}
		}
	}

	public function setUserId($userId) {
		$this->userId = $userId;
	}

	public function getUserId() {
		return $this->userId;
	}

	public function setCurrentPage($currentPage) {
		$this->currentPage = $currentPage;
	}

	public function getCurrentPage() {
		return $this->currentPage;
	}


}
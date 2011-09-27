<?php

namespace app\models;

class UserList extends ItemsList {

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
		if (isset($data->users)) {
			foreach ($data->users as $user) {
				$this->add(new User($user->user));
			}
		}
	}
	
	public function setCurrentPage($currentPage) {
		$this->currentPage = $currentPage;
	}

	public function getCurrentPage() {
		return $this->currentPage;
	}
}

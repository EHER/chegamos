<?php

namespace app\controllers;

use app\models\ApontadorApi;
use app\models\oauth;
use lithium\storage\Session;

class ProfileController extends \lithium\action\Controller {

	var $api;

	public function __construct(array $config = array()) {
		$this->api = new ApontadorApi();
		parent::__construct($config);
	}

	public function places($userId, $page='page1') {
		if (empty($userId)) {
			$this->redirect('/');
		}

		$page = str_replace('page', '', $page);

		$user = $this->api->getUserPlaces(array('userId' => $userId, 'page' => $page));

		return compact('user', 'geocode', 'placeId', 'placeName', 'zipcode', 'cityState', 'lat', 'lng');
	}

	public function following($userId=null, $page='page1') {
		OauthController::verifyLogged('apontador');

		if (empty($userId)) {
			$userId = Session::read('apontadorId');
		}

		$page = str_replace('page', '', $page);

		\extract(OauthController::whereAmI());

		$following = $this->api->getUserFollowing(array(
					'userId' => $userId,
					'nearby' => false,
					'lat' => $lat,
					'lng' => $lng,
					'page' => $page
				));

		return compact('following', 'geocode', 'placeId', 'placeName', 'zipcode', 'cityState', 'lat', 'lng');
	}

	public function followers($userId=null, $page='page1') {
		OauthController::verifyLogged('apontador');

		if (empty($userId)) {
			$userId = Session::read('apontadorId');
		}

		$page = str_replace('page', '', $page);

		\extract(OauthController::whereAmI());

		$following = $this->api->getUserFollowers(array(
					'userId' => $userId,
					'nearby' => true,
					'lat' => $lat,
					'lng' => $lng,
					'page' => $page
				));

		return compact('following', 'geocode', 'placeId', 'placeName', 'zipcode', 'cityState', 'lat', 'lng');
	}

	public function show($userId) {
		$user = $this->api->getUser(array('userid' => $userId));

		\extract(OauthController::whereAmI());

		return compact('user', 'geocode', 'placeId', 'placeName', 'zipcode', 'cityState', 'lat', 'lng');
	}

}

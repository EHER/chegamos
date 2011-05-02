<?php

namespace app\controllers;

use app\models\ApontadorApi;
use app\models\Address;
use app\models\City;
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

		$title = 'Locais cadastrados por ' . $user->getName();
		return compact('title', 'user', 'geocode', 'placeId', 'placeName', 'zipcode', 'cityState', 'lat', 'lng');
	}

	public function following($userId=null, $page='page1') {
		if (empty($userId)) {
			OauthController::verifyLogged('apontador');
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

		$user = $this->api->getUser(array('userid' => $userId));

		$title = 'Quem ' . $user->getName() . ' segue';
		return compact('title', 'following', 'geocode', 'placeId', 'placeName', 'zipcode', 'cityState', 'lat', 'lng');
	}

	public function followers($userId=null, $page='page1') {
		if (empty($userId)) {
			OauthController::verifyLogged('apontador');
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
		$user = $this->api->getUser(array('userid' => $userId));

		$title = 'Quem segue ' . $user->getName();
		return compact('title', 'following', 'geocode', 'placeId', 'placeName', 'zipcode', 'cityState', 'lat', 'lng');
	}

	public function reviews($userId=null, $page='page1') {
		if (empty($userId)) {
			OauthController::verifyLogged('apontador');
			$userId = Session::read('apontadorId');
		}

		$page = str_replace('page', '', $page);

		\extract(OauthController::whereAmI());

		$user = $this->api->getUserReviews(array(
					'userId' => $userId,
					'nearby' => true,
					'lat' => $lat,
					'lng' => $lng,
					'page' => $page
				));

		$title = 'Avaliações de ' . $user->getName();
		return compact('title', 'user', 'geocode', 'placeId', 'placeName', 'zipcode', 'cityState', 'lat', 'lng');
	}

	public function visits($userId=null, $page='page1') {
		if (empty($userId)) {
			OauthController::verifyLogged('apontador');
			$userId = Session::read('apontadorId');
		}

		$page = str_replace('page', '', $page);

		\extract(OauthController::whereAmI());

		$visits = $this->api->getUserVisits(array(
					'userid' => $userId,
					'page' => $page
				));
		$user = $this->api->getUser(array('userid' => $userId));

		$title = 'Últimas visitas de ' . $user->getName();
		return compact('title', 'visits','user', 'geocode', 'placeId', 'placeName', 'zipcode', 'cityState', 'lat', 'lng');
	}

	public function show($userId = null) {
		if (empty($userId)) {
			OauthController::verifyLogged('apontador');
			$userId = Session::read('apontadorId');
		}
		
		$user = $this->api->getUser(array('userid' => $userId));

		\extract(OauthController::whereAmI());

		$title = 'Perfil de ' . $user->getName();
		return compact('title', 'user', 'geocode', 'placeId', 'placeName', 'zipcode', 'cityState', 'lat', 'lng');
	}
	public function location() {
		$hideWhereAmI = true;
		if (!empty($_GET)) {
			if (!empty($_GET['lat']) and !empty($_GET['lng'])) {
				$checkinData = array('lat' => $_GET['lat'], 'lng' => $_GET['lng']);
			} elseif (!empty($_GET['cep'])) {
				$address = new Address();
				$address->setZipcode($_GET['cep']);
				$geocode = $this->api->geocode($address);
				$checkinData = array('zipcode' => $_GET['cep'], 'lat' => $geocode->getLat(), 'lng' => $geocode->getLng());
			} elseif (!empty($_GET['cityState'])) {
				$cityState = \explode(',', $_GET['cityState']);

				$city = new City();
				$city->setName(trim($cityState[0]));
				$city->setState(trim($cityState[1]));

				$address = new Address();
				$address->setCity(new City($city));
				$geocode = $this->api->geocode($address);

				$checkinData = array('cityState' => $_GET['cityState'], 'lat' => $geocode->getLat(), 'lng' => $geocode->getLng());
			} else {
				$checkinData = array();
			}

			$this->updateLocation($checkinData);
		}

		extract(OauthController::whereAmI());

		$title = 'Onde estou';
		return compact('title', 'geocode', 'hideWhereAmI', 'checkinData', 'zipcode', 'cityState', 'lat', 'lng', 'placeId', 'placeName');
	}

	public function updateLocation(Array $checkinData = array()) {
		$checkinVars = array('zipcode', 'cityState', 'lat', 'lng', 'placeId', 'placeName');

		foreach ($checkinVars as $method) {
			Session::write($method);
		}

		foreach ($checkinData as $method => $value) {
			Session::write($method, $value);
		}

		if (isset($_GET['type']) && $_GET['type'] == 'json') {
			header('Cache-Control: no-cache, must-revalidate');
			header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
			header('Content-type: application/json');
			
			if(empty($checkinData['lat']) || empty($checkinData['lng'])) {
				echo json_encode(array('success' => false, 'error' => 'lat/lng nao informado'));
				exit;
			}
			
			$geocode = $this->api->revgeocode($checkinData['lat'], $checkinData['lng']);
			if ($geocode instanceof Address) {
				echo json_encode(array('success' => true, 'checkinData' => $geocode->toArray()));
			} else {
				echo json_encode(array('success' => false, 'error' => 'Desculpe! Nao consegui fazer o checkin :('));
			}
			exit;
		}
		$this->redirect('/');
	}
}

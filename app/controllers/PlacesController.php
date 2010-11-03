<?php

namespace app\controllers;

use app\models\ApontadorApi;
use app\models\oauth;
use lithium\storage\Session;

class PlacesController extends \lithium\action\Controller {

	var $api;

	public function __construct(array $config = array()) {
		$this->api = new ApontadorApi();
		parent::__construct($config);
	}

	public function index() {
		$placeId = Session::read('placeId');
		$placeName = Session::read('placeName');
		$zipcode = Session::read('zipcode');
		$cityState = Session::read('cityState');
		$lat = Session::read('lat');
		$lng = Session::read('lng');

		if (empty($placeId) && empty($placeName) && empty($zipcode) && empty($cityState) && (empty($lat) or empty($lng))) {
			$this->redirect('/places/checkin');
		}

		return compact('placeId', 'placeName', 'zipcode', 'cityState', 'lat', 'lng');
	}

	public function search() {
		$placeId = Session::read('placeId');
		$placeName = Session::read('placeName');
		$zipcode = Session::read('zipcode');
		$cityState = Session::read('cityState');
		$lat = Session::read('lat');
		$lng = Session::read('lng');

		$searchName = '';

		if (isset($_GET['name'])) {
			$searchName = $_GET['name'];

			if (!empty($placeId)) {
				$place = $this->api->getPlace(array('placeid' => $placeId));
				$lat = $place->place->point->lat;
				$lng = $place->place->point->lng;
				$search = $this->api->searchByPoint(array(
							'term' => $searchName,
							'radius_mt' => 10000,
							'lat' => $lat,
							'lng' => $lng
						));
			} elseif (!empty($zipcode)) {
				$search = $this->api->searchByZipcode(array(
							'term' => $searchName,
							'radius_mt' => 10000,
							'zipcode' => $zipcode
						));
			} elseif (!empty($cityState) and strstr($cityState, ',')) {
				list($city, $state) = \explode(',', $cityState);
				$search = $this->api->searchByAddress(array(
							'term' => $searchName,
							'radius_mt' => 10000,
							'city' => trim($city),
							'state' => trim($state),
							'country' => 'BR'
						));
			} elseif (!empty($lat) and !empty($lng)) {
				$search = $this->api->searchByPoint(array(
							'term' => $searchName,
							'radius_mt' => 10000,
							'lat' => $lat,
							'lng' => $lng
						));
			} else {
				$this->redirect('/places/checkin');
			}
		}
		return compact('search', 'searchName', 'placeId', 'placeName', 'zipcode', 'cityState', 'lat', 'lng');
	}

	public function near() {
		$placeId = Session::read('placeId');
		$placeName = Session::read('placeName');
		$zipcode = Session::read('zipcode');
		$cityState = Session::read('cityState');
		$lat = Session::read('lat');
		$lng = Session::read('lng');

		if (!empty($placeId)) {
			$place = $this->api->getPlace(array('placeid' => $placeId));
			$lat = $place->place->point->lat;
			$lng = $place->place->point->lng;
			$search = $this->api->searchRecursive(array(
						'lat' => $lat,
						'lng' => $lng
							), 'searchByPoint');
		} elseif (!empty($zipcode)) {
			$search = $this->api->searchRecursive(array(
						'zipcode' => $zipcode
							), 'searchByZipcode');
		} elseif (!empty($cityState) and strstr($cityState, ',')) {
			list($city, $state) = \explode(',', $cityState);
			$search = $this->api->searchRecursive(array(
						'city' => trim($city),
						'state' => trim($state),
						'country' => 'BR'
							), 'searchByAddress');
		} elseif (!empty($lat) and !empty($lng)) {
			$search = $this->api->searchRecursive(array(
						'lat' => $lat,
						'lng' => $lng
							), 'searchByPoint');
		} else {
			$this->redirect('/places/checkin');
		}

		return compact('search', 'placeId', 'placeName', 'zipcode', 'cityState', 'lat', 'lng');
	}

	public function categories() {
		$placeId = Session::read('placeId');
		$placeName = Session::read('placeName');
		$zipcode = Session::read('zipcode');
		$cityState = Session::read('cityState');
		$lat = Session::read('lat');
		$lng = Session::read('lng');

		if (isset($_GET['all'])) {
			$categories = $this->api->getCategories();
		} else {
			$categories = $this->api->getCategoriesTop();
		}
		return compact('categories', 'placeId', 'placeName', 'zipcode', 'cityState', 'lat', 'lng');
	}

	public function category($categoryId) {
		if (empty($categoryId)) {
			$this->redirect('/places/categories');
		}


		$placeId = Session::read('placeId');
		$placeName = Session::read('placeName');
		$zipcode = Session::read('zipcode');
		$cityState = Session::read('cityState');
		$lat = Session::read('lat');
		$lng = Session::read('lng');

		$category = $this->api->getSubcategories(array('categoryid' => $categoryId));

		$categoryName = $category->category->name;

		if (!empty($placeId)) {
			$place = $this->api->getPlace(array('placeid' => $placeId));
			$lat = $place->place->point->lat;
			$lng = $place->place->point->lng;
			$search = $this->api->searchRecursive(array(
						'category_id' => $categoryId,
						'lat' => $lat,
						'lng' => $lng
							), 'searchByPoint');
		} elseif (!empty($zipcode)) {
			$search = $this->api->searchRecursive(array(
						'category_id' => $categoryId,
						'zipcode' => $zipcode
							), 'searchByZipcode');
		} elseif (!empty($cityState) and strstr($cityState, ',')) {
			list($city, $state) = \explode(',', $cityState);
			$search = $this->api->searchRecursive(array(
						'category_id' => $categoryId,
						'city' => trim($city),
						'state' => trim($state),
						'country' => 'BR'
							), 'searchByAddress');
		} elseif (!empty($lat) and !empty($lng)) {
			$search = $this->api->searchRecursive(array(
						'category_id' => $categoryId,
						'lat' => $lat,
						'lng' => $lng
							), 'searchByPoint');
		} else {
			$this->redirect('/places/checkin');
		}

		return compact('search', 'categoryName', 'placeId', 'placeName', 'zipcode', 'cityState', 'lat', 'lng');
	}

	public function checkin() {
		if (!empty($_GET)) {
			if (!empty($_GET['placeId'])) {
				$place = $this->api->getPlace(array('placeid' => $_GET['placeId']));
				$checkinData = array(
					'placeId' => $_GET['placeId'],
					'placeName' => $place->place->name,
					'lat' => $place->place->point->lat,
					'lng' => $place->place->point->lng,
				);
			} elseif (!empty($_GET['lat']) and !empty($_GET['lng'])) {
				$checkinData = array('lat' => $_GET['lat'], 'lng' => $_GET['lng']);
			} elseif (!empty($_GET['cep'])) {
				$checkinData = array('zipcode' => $_GET['cep']);
			} elseif (!empty($_GET['cityState'])) {
				$checkinData = array('cityState' => $_GET['cityState']);
			} else {
				$checkinData = array();
			}

			$this->doCheckin($checkinData);

			$this->redirect('/');
		}
	}

	private function doCheckin(Array $checkinData = array()) {
		$checkinVars = array('zipcode', 'cityState', 'lat', 'lng', 'placeId', 'placeName');

		foreach ($checkinVars as $method) {
			Session::write($method);
		}

		foreach ($checkinData as $method => $value) {
			Session::write($method, $value);
		}

		$placeId = Session::read('placeId');
		$oauthToken = Session::read('oauthToken');
		$oauthTokenSecret = Session::read('oauthTokenSecret');

		if ($placeId) {
			if (!empty ($oauthToken)) {
				$response = $this->api->checkin(array(
							'place_id' => $placeId,
							'oauth_token' => $oauthToken,
							'oauth_token_secret' => $oauthTokenSecret,
						));
			} else {
				$this->redirect('/oauth');
			}
		}
	}

	public function show($placeId = null) {
		if (empty($placeId)) {
			$this->redirect('/');
		}

		$place = $this->api->getPlace(array('placeid' => $placeId));

		if ($place) {
			switch ($place->place->average_rating) {
				case 1:
					$place->place->average_rating = "Péssimo";
					break;
				case 2:
					$place->place->average_rating = "Ruim";
					break;
				case 3:
					$place->place->average_rating = "Regular";
					break;
				case 4:
					$place->place->average_rating = "Bom";
					break;
				case 5:
					$place->place->average_rating = "Excelente";
					break;
			}
			return compact('place');
		} else {
			$this->redirect('/');
		}
	}

	public function checkins($placeId = null) {
		if (empty($placeId)) {
			$this->redirect('/');
		}

		$visitors = $this->api->getVisitors(array('placeid' => $placeId));
		$visitors = array_reverse($visitors);

		return compact('placeId','visitors');
	}

	public function review($placeId = null) {
		if (empty($placeId)) {
			$this->redirect('/');
		}

		if (!empty($_GET)) {

			$reviewData = array(
				'place_id' => $placeId,
				'rating' => $_GET['rating'],
				'content' => $_GET['content'],
			);
			$this->doReview($reviewData);

			$this->redirect(Session::read('redir'));
		}
		$reviews = $this->api->getReviews(array(
					'place_id' => $placeId,
					'limit' => 100,
				));

		return compact('placeId', 'reviews');
	}

	private function doReview(Array $reviewData = array()) {

		$oauthToken = Session::read('oauthToken');
		$oauthTokenSecret = Session::read('oauthTokenSecret');

		if ($reviewData['place_id']) {
			if (!empty ($oauthToken)) {
				$response = $this->api->review(array(
							'place_id' => $reviewData['place_id'],
							'rating' => $reviewData['rating'],
							'content' => $reviewData['content'],
							'oauth_token' => $oauthToken,
							'oauth_token_secret' => $oauthTokenSecret,
						));
				return $response;
			} else {
				$this->redirect('/oauth');
			}
		}
	}

}

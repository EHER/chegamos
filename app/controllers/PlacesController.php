<?php

namespace app\controllers;

use app\models\ApontadorApi;
use app\models\Place;
use app\models\PlaceList;
use app\models\oauth;
use lithium\storage\Session;

class PlacesController extends \lithium\action\Controller {

	var $api;

	public function __construct(array $config = array()) {
		$this->api = new ApontadorApi();
		parent::__construct($config);
	}

	public function index() {
		extract($this->whereAmI());

		if (empty($placeId) && empty($placeName) && empty($zipcode) && empty($cityState) && (empty($lat) or empty($lng))) {
			$checkinData = array('cityState' => 'São Paulo, SP');
			$this->doCheckin($checkinData);
			return $checkinData;
		}
		$title = "Página principal";
		return \array_merge(compact('title'),$this->whereAmI());

	}

	public function search() {
		extract($this->whereAmI());

		$searchName = '';

		if (isset($_GET['name'])) {
			$searchName = $_GET['name'];

			if (!empty($placeId)) {
				$place = $this->api->getPlace(array('placeid' => $placeId));
				$lat = $place->getPoint()->lat;
				$lng = $place->getPoint()->lng;
				$placeList = $this->api->searchByPoint(array(
							'term' => $searchName,
							'radius_mt' => 10000,
							'lat' => $lat,
							'lng' => $lng
						));
			} elseif (!empty($zipcode)) {
				$placeList = $this->api->searchByZipcode(array(
							'term' => $searchName,
							'radius_mt' => 10000,
							'zipcode' => $zipcode
						));
			} elseif (!empty($cityState) and strstr($cityState, ',')) {
				list($city, $state) = \explode(',', $cityState);
				$placeList = $this->api->searchByAddress(array(
							'term' => $searchName,
							'radius_mt' => 10000,
							'city' => trim($city),
							'state' => trim($state),
							'country' => 'BR'
						));
			} elseif (!empty($lat) and !empty($lng)) {
				$placeList = $this->api->searchByPoint(array(
							'term' => $searchName,
							'radius_mt' => 10000,
							'lat' => $lat,
							'lng' => $lng
						));
			} else {
				$this->redirect('/places/checkin');
			}
		}

		$title = "Locais por nome";
		$title = empty($searchName) ? $title : $title . ": " . $searchName ;

		return compact('title', 'geocode', 'placeList', 'searchName', 'placeId', 'placeName', 'zipcode', 'cityState', 'lat', 'lng');
	}

	public function near($page = 'page1') {
		extract($this->whereAmI());

		$page = str_replace('page', '', $page);

		if (!empty($placeId)) {
			$place = $this->api->getPlace(array('placeid' => $placeId));
			$lat = $place->getPoint()->lat;
			$lng = $place->getPoint()->lng;
			$placeList = $this->api->searchRecursive(array(
						'lat' => $lat,
						'lng' => $lng,
						'page' => $page
							), 'searchByPoint');
		} elseif (!empty($zipcode)) {
			$placeList = $this->api->searchRecursive(array(
						'zipcode' => $zipcode,
						'page' => $page
							), 'searchByZipcode');
		} elseif (!empty($cityState) and strstr($cityState, ',')) {
			list($city, $state) = \explode(',', $cityState);
			$placeList = $this->api->searchRecursive(array(
						'city' => trim($city),
						'state' => trim($state),
						'country' => 'BR',
						'page' => $page
							), 'searchByAddress');
		} elseif (!empty($lat) and !empty($lng)) {
			$placeList = $this->api->searchRecursive(array(
						'lat' => $lat,
						'lng' => $lng,
						'page' => $page
							), 'searchByPoint');
		} else {
			$this->redirect('/places/checkin');
		}

		$title = "Locais Próximos";
		return compact('title', 'page', 'geocode', 'placeList', 'placeId', 'placeName', 'zipcode', 'cityState', 'lat', 'lng');
	}

	public function gasstations() {
		extract($this->whereAmI());

		if (!empty($placeId)) {
			$place = $this->api->getPlace(array('placeid' => $placeId));
			$lat = $place->getPoint()->lat;
			$lng = $place->getPoint()->lng;
			$placeList = $this->api->searchGasStations(array(
						'lat' => $lat,
						'lng' => $lng
							), 'searchByPoint');
		} elseif (!empty($zipcode)) {
			$placeList = $this->api->searchGasStations(array(
						'zipcode' => $zipcode
							), 'searchByZipcode');
		} elseif (!empty($cityState) and strstr($cityState, ',')) {
			list($city, $state) = \explode(',', $cityState);
			$placeList = $this->api->searchGasStations(array(
						'city' => trim($city),
						'state' => trim($state),
						'country' => 'BR'
							), 'searchByAddress');
		} elseif (!empty($lat) and !empty($lng)) {
			$placeList = $this->api->searchGasStations(array(
						'lat' => $lat,
						'lng' => $lng
							), 'searchByPoint');
		} else {
			$this->redirect('/places/checkin');
		}

		return compact('title', 'geocode', 'placeList', 'placeId', 'placeName', 'zipcode', 'cityState', 'lat', 'lng');
	}

	public function categories($all = null) {
		extract($this->whereAmI());

		if (!empty($all)) {
			$categories = $this->api->getCategories();
			$title = "Todas as categorias";
		} else {
			$categories = $this->api->getCategoriesTop();
			$title = "Principais categorias";
		}

		return compact('title', 'all', 'geocode', 'categories', 'placeId', 'placeName', 'zipcode', 'cityState', 'lat', 'lng');
	}

	public function category($categoryId, $page='page1') {
		extract($this->whereAmI());

		$page = str_replace('page', '', $page);

		if (empty($categoryId)) {
			$this->redirect('/places/categories');
		}

		$category = $this->api->getSubcategories(array('categoryid' => $categoryId));

		$categoryName = $category->category->name;

		if (!empty($placeId)) {
			$place = $this->api->getPlace(array('placeid' => $placeId));
			$lat = $place->getPoint()->lat;
			$lng = $place->getPoint()->lng;
			$placeList = $this->api->searchRecursive(array(
						'category_id' => $categoryId,
						'lat' => $lat,
						'lng' => $lng,
						'page' => $page
							), 'searchByPoint');
		} elseif (!empty($zipcode)) {
			$placeList = $this->api->searchRecursive(array(
						'category_id' => $categoryId,
						'zipcode' => $zipcode,
						'page' => $page
							), 'searchByZipcode');
		} elseif (!empty($cityState) and strstr($cityState, ',')) {
			list($city, $state) = \explode(',', $cityState);
			$placeList = $this->api->searchRecursive(array(
						'category_id' => $categoryId,
						'city' => trim($city),
						'state' => trim($state),
						'country' => 'BR',
						'page' => $page
							), 'searchByAddress');
		} elseif (!empty($lat) and !empty($lng)) {
			$placeList = $this->api->searchRecursive(array(
						'category_id' => $categoryId,
						'lat' => $lat,
						'lng' => $lng,
						'page' => $page
							), 'searchByPoint');
		} else {
			$this->redirect('/places/checkin');
		}

		extract($this->whereAmI());

		$title = $categoryName;

		return compact('title', 'page', 'categoryId', 'geocode', 'placeList', 'categoryName', 'zipcode', 'cityState', 'lat', 'lng', 'placeId', 'placeName');
	}

	public function whereAmI() {
		$placeId = Session::read('placeId');
		$placeName = Session::read('placeName');
		$zipcode = Session::read('zipcode');
		$cityState = Session::read('cityState');
		$lat = Session::read('lat');
		$lng = Session::read('lng');
		$geocode = $this->api->geocode($lat, $lng);

		return compact('geocode', 'placeId', 'placeName', 'zipcode', 'cityState', 'lat', 'lng');
	}

	public function checkin() {
		$hideWhereAmI = true;
		if (!empty($_GET)) {
			if (!empty($_GET['placeId'])) {
				$place = $this->api->getPlace(array('placeid' => $_GET['placeId']));
				$checkinData = array(
					'placeId' => $_GET['placeId'],
					'placeName' => $place->getName(),
					'lat' => $place->getPoint()->lat,
					'lng' => $place->getPoint()->lng,
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

		extract($this->whereAmI());

		$title = 'Onde estou';
		return compact('title', 'geocode', 'hideWhereAmI', 'checkinData', 'zipcode', 'cityState', 'lat', 'lng', 'placeId', 'placeName');
	}

	private function doCheckin(Array $checkinData = array()) {
		$checkinVars = array('zipcode', 'cityState', 'lat', 'lng', 'placeId', 'placeName');

		foreach ($checkinVars as $method) {
			Session::write($method);
		}

		foreach ($checkinData as $method => $value) {
			Session::write($method, $value);
		}

		$placeId = isset($checkinData['placeId']) ? $checkinData['placeId'] : Session::read('placeId');
		$oauthToken = Session::read('oauthToken');
		$oauthTokenSecret = Session::read('oauthTokenSecret');

		if (!empty($placeId)) {
			if (!empty($oauthToken)) {
				$response = $this->api->checkin(array(
							'place_id' => $placeId,
							'oauth_token' => $oauthToken,
							'oauth_token_secret' => $oauthTokenSecret,
						));

				$this->redirect('/places/show/' . $placeId);
			} else {
				Session::Write('redir', ROOT_URL . 'places/checkin?placeId=' . $placeId);
				$this->redirect('/oauth');
			}
		}
	}

	public function show($placeId = null) {

		if (empty($placeId)) {
			$this->redirect('/');
		}

		$place = $this->api->getPlace(array('placeid' => $placeId));

		if ($place instanceof Place) {

			$thePlaceId = $placeId;

			extract($this->whereAmI());

			$placeId = $thePlaceId;

			$visitors = $this->api->getVisitors(array('placeid' => $placeId));
			$place->setNumVisitors($visitors->getNumFound());

			$photos = $this->api->getPhotos(array('placeId' => $placeId));
			$place->setNumPhotos(count($photos->getItems()));

			$showCheckin = true;

			$title = $place->getName();
			return compact('title', 'numVisitors', 'geocode', 'showCheckin', 'place', 'zipcode', 'cityState', 'lat', 'lng', 'placeId', 'placeName');
		} else {
			$this->redirect('/');
		}
	}

	public function checkins($placeId = null) {
		if (empty($placeId)) {
			$this->redirect('/');
		}

		$visitors = $this->api->getVisitors(array('placeid' => $placeId));

		$thePlaceId = $placeId;

		extract($this->whereAmI());

		$placeId = $thePlaceId;

		$place = $this->api->getPlace(array('placeid' => $placeId));

		$title = $place->getName() . ' - Quem esteve aqui';
		return compact('title', 'placeId', 'visitors', 'place', 'zipcode', 'cityState', 'lat', 'lng', 'placeId', 'placeName');
	}

	public function photos($placeId = null, $photoId = 0) {
		if (empty($placeId)) {
			$this->redirect('/');
		}

		$photos = $this->api->getPhotos(array('placeId' => $placeId));

		$thePlaceId = $placeId;

		extract($this->whereAmI());

		$placeId = $thePlaceId;

		$place = $this->api->getPlace(array('placeid' => $placeId));

		$title = $place->getName() . ' - Fotos';
		return compact('title', 'photoId', 'placeId', 'photos', 'place', 'zipcode', 'cityState', 'lat', 'lng', 'placeId', 'placeName');
	}

	public function review($placeId = null, $reviewId = null) {
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
		}

		$reviews = $this->api->getReviews(array(
					'place_id' => $placeId,
					'limit' => 100,
				));

		if ($reviewId != null) {
			foreach ($reviews->place->reviews as $k => $review) {
				if ($review->review->id != $reviewId) {
					unset($reviews->place->reviews[$k]);
				}
			}
		}

		$thePlaceId = $placeId;

		extract($this->whereAmI());

		$placeId = $thePlaceId;

		$place = $this->api->getPlace(array('placeid' => $placeId));

		$title = $place->getName() . ' - Avaliações';
		return compact('title', 'geocode', 'reviewId', 'reviews', 'place', 'zipcode', 'cityState', 'lat', 'lng', 'placeId', 'placeName');
	}

	private function doReview(Array $reviewData = array()) {

		$oauthToken = Session::read('oauthToken');
		$oauthTokenSecret = Session::read('oauthTokenSecret');

		if ($reviewData['place_id']) {
			if (!empty($oauthToken)) {
				$response = $this->api->review(array(
							'place_id' => $reviewData['place_id'],
							'rating' => $reviewData['rating'],
							'content' => $reviewData['content'],
							'oauth_token' => $oauthToken,
							'oauth_token_secret' => $oauthTokenSecret,
						));
				return $response;
			} else {
				Session::write('redir', ROOT_URL . 'places/review/' . $reviewData['place_id'] .
						'?rating=' . $reviewData['rating'] .
						'&content=' . $reviewData['content']
						);
				$this->redirect('/oauth');
			}
		}
	}

}

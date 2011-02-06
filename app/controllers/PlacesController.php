<?php

namespace app\controllers;

use app\models\ApontadorApi;
use app\models\Place;
use app\models\Address;
use app\models\City;
use app\models\PlaceList;
use app\models\FourSquareApiV2;
use app\models\TwitterOAuth;
use app\models\Facebook;
use app\models\OrkutOAuth;
use app\models\oauth;
use app\models\OpenGraph;
use lithium\storage\Session;

class PlacesController extends \lithium\action\Controller {

	public $api;

	public function __construct(array $config = array()) {
		$this->api = new ApontadorApi();
		parent::__construct($config);
	}

	public function index() {
		extract(OauthController::whereAmI());

		if (empty($placeId) && empty($placeName) && empty($zipcode) && empty($cityState) && (empty($lat) or empty($lng))) {
			$checkinData = array('cityState' => 'São Paulo, SP', 'lat' => '-23.48033', 'lng' => '-46.63459');
			$this->doCheckin($checkinData);
			return $checkinData;
		}
		$title = "";
		return \array_merge(compact('title'), OauthController::whereAmI());
	}

	public function search() {
		extract(OauthController::whereAmI());

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
		$title = empty($searchName) ? $title : $title . ": " . $searchName;

		return compact('title', 'geocode', 'placeList', 'searchName', 'placeId', 'placeName', 'zipcode', 'cityState', 'lat', 'lng');
	}

	public function near($page = 'page1') {
		extract(OauthController::whereAmI());

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
		extract(OauthController::whereAmI());

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
		extract(OauthController::whereAmI());

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
		extract(OauthController::whereAmI());

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

		extract(OauthController::whereAmI());

		$title = $categoryName;

		return compact('title', 'page', 'categoryId', 'geocode', 'placeList', 'categoryName', 'zipcode', 'cityState', 'lat', 'lng', 'placeId', 'placeName');
	}

	public function checkin() {
		$hideWhereAmI = true;
		if (!empty($_GET)) {
			if (!empty($_GET['placeId'])) {
				$place = $this->api->getPlace(array('placeid' => $_GET['placeId']));
				$placeName = $place->getName();
				$checkinData = array(
					'placeId' => $_GET['placeId'],
					'placeName' => $place->getName(),
					'term' => $place->getName(),
					'lat' => $place->getPoint()->lat,
					'lng' => $place->getPoint()->lng,
				);
			} elseif (!empty($_GET['lat']) and !empty($_GET['lng'])) {
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
			$this->doCheckin($checkinData);

			$this->redirect('/');
		}

		extract(OauthController::whereAmI());

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

		$placeId = empty($checkinData['placeId']) ? null : $checkinData['placeId'];
		$apontadorToken = Session::read('apontadorToken');
		$apontadorTokenSecret = Session::read('apontadorTokenSecret');
		$foursquareAccessToken = Session::read('foursquareAccessToken');
		$twitterAccessToken = array('oauth_token' => Session::read('twitterToken'),
			'oauth_token_secret' => Session::read('twitterTokenSecret'));
		$facebookAccessToken = Session::read('facebookToken');
		$orkutAccessToken = array('oauth_token' => Session::read('orkutToken'),
			'oauth_token_secret' => Session::read('orkutTokenSecret'));
		$checkedin = false;

		if (!empty($placeId)) {
			OauthController::verifyLogged('apontador');

			if (!empty($apontadorToken)) {
				$this->api->checkin(array(
					'place_id' => $placeId,
					'oauth_token' => $apontadorToken,
					'oauth_token_secret' => $apontadorTokenSecret,
				));
			}
			if (!empty($foursquareAccessToken)) {
				$this->doFoursquareCheckin($foursquareAccessToken, $checkinData);
			}

			if (!empty($twitterAccessToken['oauth_token'])) {
				$this->doTwitterCheckin($twitterAccessToken, $checkinData);
			}

			if (!empty($facebookAccessToken)) {
				$this->doFacebookCheckin($facebookAccessToken, $checkinData);
				$checkedin = true;
			}

			if (!empty($orkutAccessToken['oauth_token'])) {
				$this->doOrkutCheckin($orkutAccessToken, $checkinData);
			}

			$this->redirect('/places/checkins/' . $placeId);
		}
		$this->redirect('/');
	}

	private function doFacebookCheckin($facebookAccessToken = '', $checkinData = '') {
		$api = new Facebook(array(
					'appId' => \FACEBOOK_AP_ID,
					'secret' => \FACEBOOK_SECRET,
					'cookie' => true,
				));

		$session = array(
			'access_token' => Session::read('facebookToken'),
			'uid' => Session::read('facebookUid'),
			'sig' => Session::read('facebookSig'),
		);
		$api->setSession($session);

		$urlChegamos = ROOT_URL . "places/show/" . $checkinData['placeId'];
		$urlChegamos = ApontadorApi::encurtaUrl($urlChegamos);
		$shout = "Eu estou em " . $checkinData['placeName'] . ". " . $urlChegamos . " #checkin";

		try {
			$postStatus = $api->api('/me/feed', 'POST', array('message' => $shout, 'access_token' => $facebookAccessToken));
		} catch (\Exception $e) {
			$postStatus = $e;
		}
	}

	private function doTwitterCheckin($twitterAccessToken = '', $checkinData = '') {
		$api = new TwitterOAuth(TWITTER_CONSUMER_KEY, TWITTER_CONSUMER_SECRET, $twitterAccessToken['oauth_token'], $twitterAccessToken['oauth_token_secret']);

		$searchParams = array();
		$searchParams['lat'] = empty($checkinData['lat']) ? '' : $checkinData['lat'];
		$searchParams['long'] = empty($checkinData['lng']) ? '' : $checkinData['lng'];
		$searchParams['name'] = empty($checkinData['term']) ? '' : $checkinData['term'];
		$searchPlaces = $api->get("geo/similar_places", $searchParams);

		$place_id = empty($searchPlaces->result->places[0]->id) ? null : $searchPlaces->result->places[0]->id;

		$urlChegamos = ROOT_URL . "places/show/" . $checkinData['placeId'];
		$urlChegamos = ApontadorApi::encurtaUrl($urlChegamos);

		$status = "Eu estou em " . $checkinData['placeName'] . ". " . $urlChegamos . " #checkin via @sitechegamos";
		$api->post("statuses/update", array('status' => $status, 'place_id' => $place_id));
	}

	private function doOrkutCheckin($orkutAccessToken = '', $checkinData = '') {
		$api = new OrkutOAuth(ORKUT_CONSUMER_KEY, ORKUT_CONSUMER_SECRET, $orkutAccessToken['oauth_token'], $orkutAccessToken['oauth_token_secret']);

		$urlChegamos = ROOT_URL . "places/show/" . $checkinData['placeId'];
		$urlChegamos = ApontadorApi::encurtaUrl($urlChegamos);

		$status = "Eu estou em " . $checkinData['placeName'] . ". " . $urlChegamos . " #checkin via @sitechegamos";
		$checkResult = $api->post("http://www.orkut.com/social/rest/activities/@me/@self", array('body' => $status, 'title' => $checkinData['placeName']));
//		$checkResult = $api->get("https://www.googleapis.com/latitude/v1/currentLocation", array('key' => GOOGLE_APIS_KEY, 'latitude' => $checkinData['lat'], 'longitude' => $checkinData['lng']));
//		$checkResult = $api->post("https://www.googleapis.com/latitude/v1/currentLocation?key=" . GOOGLE_APIS_KEY . '&latitude=' . $checkinData['lat'] . '&longitude=' . $checkinData['lng']);
//		var_dump($checkResult);
//		exit;
	}

	private function doFoursquareCheckin($foursquareAccessToken = '', $checkinData = '') {

		$callbackurl = ROOT_URL . "oauth/callback/foursquare";
		$foursquareApi = new FourSquareApiV2(\FOURSQUARE_CONSUMER_KEY, \FOURSQUARE_CONSUMER_SECRET, $callbackurl);
		$foursquareApi->setOAuth2Token($foursquareAccessToken);

		if (!empty($checkinData['radius_mt'])) {
			$radius_mt = $checkinData['radius_mt'];
		} else {
			$radius_mt = 1000;
		}
		if (!empty($checkinData['lat'])) {
			$lat = $checkinData['lat'];
		} else {
			$lat = '-23.5934';
		}
		if (!empty($checkinData['lng'])) {
			$lng = $checkinData['lng'];
		} else {
			$lng = '-46.6876';
		}
		if (!empty($checkinData['term'])) {
			$term = $checkinData['term'];
		}
		$limit = 5;
		$intent = 'checkin'; // checkin ou match

		$venues = $foursquareApi->searchVenues($lat, $lng, $radius_mt, $term, $limit, $intent);

		if (!empty($venues)) {
			$shout = "Eu estou em " . $venues[0]['name'] . ". #checkin via @sitechegamos";
			$venueId = $venues[0]['id'];
			$broadcast = "public";
			$checkin = $foursquareApi->checkinVenue($venueId, $shout, $broadcast);
		}
	}

	public function show($placeId = null) {

		if (empty($placeId)) {
			$this->redirect('/');
		}

		$place = $this->api->getPlace(array('placeid' => $placeId));

		if ($place instanceof Place) {

			$thePlaceId = $placeId;

			extract(OauthController::whereAmI());

			$placeId = $thePlaceId;

			$visitors = $this->api->getVisitors(array('placeid' => $placeId));
			$place->setNumVisitors($visitors->getNumFound());

			$photos = $this->api->getPhotos(array('placeId' => $placeId));
			$place->setNumPhotos(count($photos->getItems()));

			$showCheckin = true;

			$og = new OpenGraph();
			$og->populate($place);

			$meta = $og->getMeta();
			$title = $place->getName();
			return compact('meta', 'title', 'numVisitors', 'geocode', 'showCheckin', 'place', 'zipcode', 'cityState', 'lat', 'lng', 'placeId', 'placeName');
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

		extract(OauthController::whereAmI());

		$placeId = $thePlaceId;

		$place = $this->api->getPlace(array('placeid' => $placeId));

		$title = $place->getName() . ' - Quem esteve aqui';
		return compact('title', 'placeId', 'geocode', 'visitors', 'place', 'zipcode', 'cityState', 'lat', 'lng', 'placeId', 'placeName');
	}

	public function photos($placeId = null, $photoId = 0) {
		if (empty($placeId)) {
			$this->redirect('/');
		}

		$photos = $this->api->getPhotos(array('placeId' => $placeId));

		$thePlaceId = $placeId;

		extract(OauthController::whereAmI());

		$placeId = $thePlaceId;

		$place = $this->api->getPlace(array('placeid' => $placeId));

		$title = $place->getName() . ' - Fotos';
		return compact('title', 'photoId', 'geocode', 'placeId', 'photos', 'place', 'zipcode', 'cityState', 'lat', 'lng', 'placeId', 'placeName');
	}

	public function review($placeId = null, $reviewId = null) {

		if (empty($placeId)) {
			$this->redirect('/');
		}

		if (!empty($_GET)) {
			OauthController::verifyLogged('apontador');

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


		$thePlaceId = $placeId;

		extract(OauthController::whereAmI());

		$placeId = $thePlaceId;

		$place = $this->api->getPlace(array('placeid' => $placeId));

		if ($reviewId != null) {
			foreach ($reviews->place->reviews as $k => $review) {
				if ($review->review->id != $reviewId) {
					unset($reviews->place->reviews[$k]);
				} else {
					$keyReview = $k;
				}
			}
			$title = $place->getName() . ' - Avaliação de '
					. $reviews->place->reviews[$keyReview]->review->created->user->name
					. ' (' . $reviews->place->reviews[$keyReview]->review->id . ')';
		} else {


			$title = $place->getName() . ' - Avaliações';
		}
		return compact('title', 'geocode', 'reviewId', 'reviews', 'place', 'zipcode', 'cityState', 'lat', 'lng', 'placeId', 'placeName');
	}

	private function doReview(Array $reviewData = array()) {

		$apontadorToken = Session::read('apontadorToken');
		$apontadorTokenSecret = Session::read('apontadorTokenSecret');

		if ($reviewData['place_id']) {
			if (!empty($apontadorToken)) {
				$response = $this->api->review(array(
							'place_id' => $reviewData['place_id'],
							'rating' => $reviewData['rating'],
							'content' => $reviewData['content'],
							'oauth_token' => $apontadorToken,
							'oauth_token_secret' => $apontadorTokenSecret,
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

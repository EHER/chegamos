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
			ProfileController::updateLocation($checkinData);
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
				$lat = $place->getPoint()->getLat();
				$lng = $place->getPoint()->getLng();
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

		if ($placeList->getNumFound() == 0) {
			$placeList = $this->api->search(array(
						'term' => $searchName,
					));
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
			$lat = $place->getPoint()->getLat();
			$lng = $place->getPoint()->getLng();
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
			$lat = $place->getPoint()->getLat();
			$lng = $place->getPoint()->getLng();
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
			$lat = $place->getPoint()->getLat();
			$lng = $place->getPoint()->getLng();
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

	public function checkin($placeId = null) {
		if (empty($placeId)) {
			$this->redirect('/');
		}

		$place = $this->api->getPlace(array('placeid' => $placeId));
		$placeName = $place->getName();

		$url = ApontadorApi::encurtaUrl(ROOT_URL . "places/show/" . $place->getId());
		$status = "Eu estou em " . $place->getName() . ". " . $url . " #checkin via @sitechegamos";

		if (!empty($_POST) && $place instanceof Place) {
			$checkinData = array(
				'placeId' => $place->getId(),
				'placeName' => $place->getName(),
				'term' => $place->getName(),
				'lat' => $place->getPoint()->getLat(),
				'lng' => $place->getPoint()->getLng(),
			);

			$checkinData['url'] = isset($_POST['url']) ? $_POST['url'] : $url;
			$checkinData['status'] = isset($_POST['status']) ? str_replace("\n", " ", $_POST['status']) : $status;
			$checkinData['providers'] = isset($_POST['providers']) ? $_POST['providers'] : array();

			$checkedin = $this->doCheckin($checkinData);

			if ($checkedin) {
				$this->redirect('/places/checkins/' . $placeId);
			} else {
				$this->redirect('/places/show/' . $placeId);
			}
		}

		$providers = array();

		if (OauthController::isLogged('apontador')) {
			$providers['apontador'] = "Apontador";
		}
		if (OauthController::isLogged('foursquare')) {
			$providers['foursquare'] = "Foursquare";
		}
		if (OauthController::isLogged('twitter')) {
			$providers['twitter'] = "Twitter";
		}
		if (OauthController::isLogged('facebook')) {
			$providers['facebook'] = "Facebook";
		}
		if (OauthController::isLogged('orkut')) {
			$providers['orkut'] = "Orkut";
		}

		if (count($providers) == 0) {
			OauthController::verifyLogged('apontador');
		}

		extract(OauthController::whereAmI());

		$title = 'Check-in em ' . $placeName;
		return compact('title', 'providers', 'status', 'geocode', 'zipcode', 'cityState', 'lat', 'lng', 'placeId', 'placeName');
	}

	private function doCheckin(Array $checkinData = array()) {

		if (!empty($checkinData['placeId'])) {
			$checkedin = false;

			if (isset($checkinData['providers']['apontador'])) {
				$this->doApontadorCheckin($checkinData);
				$checkedin = true;
			}

			if (isset($checkinData['providers']['foursquare'])) {
				$this->doFoursquareCheckin($checkinData);
				$checkedin = true;
			}

			if (isset($checkinData['providers']['twitter'])) {
				$this->doTwitterCheckin($checkinData);
				$checkedin = true;
			}

			if (isset($checkinData['providers']['facebook'])) {
				$this->doFacebookCheckin($checkinData);
				$checkedin = true;
			}

			if (isset($checkinData['providers']['orkut'])) {
				$this->doOrkutCheckin($checkinData);
				$checkedin = true;
			}

			return $checkedin;
		}
	}

	private function doApontadorCheckin($checkinData = '') {
		$api = new ApontadorApi();
		$api->checkin(array(
			'place_id' => $checkinData['placeId'],
			'oauth_token' => Session::read('apontadorToken'),
			'oauth_token_secret' => Session::read('apontadorTokenSecret'),
		));
	}

	private function doFacebookCheckin($checkinData = '') {
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

		try {
			$postStatus = $api->api('/me/feed', 'POST', array('message' => $checkinData['status'], 'access_token' => $facebookAccessToken));
		} catch (\Exception $e) {
			$postStatus = $e;
		}
	}

	private function doTwitterCheckin($checkinData = '') {
		$api = new TwitterOAuth(TWITTER_CONSUMER_KEY, TWITTER_CONSUMER_SECRET, Session::read('twitterToken'), Session::read('twitterTokenSecret'));

		$searchParams = array();
		$searchParams['lat'] = empty($checkinData['lat']) ? '' : $checkinData['lat'];
		$searchParams['long'] = empty($checkinData['lng']) ? '' : $checkinData['lng'];
		$searchParams['name'] = empty($checkinData['term']) ? '' : $checkinData['term'];
		$searchPlaces = $api->get("geo/similar_places", $searchParams);

		$place_id = empty($searchPlaces->result->places[0]->id) ? null : $searchPlaces->result->places[0]->id;

		$api->post("statuses/update", array('status' => $checkinData['status'], 'place_id' => $place_id));
	}

	private function doOrkutCheckin($checkinData = '') {
		$api = new OrkutOAuth(ORKUT_CONSUMER_KEY, ORKUT_CONSUMER_SECRET, Session::read('orkutToken'), Session::read('orkutTokenSecret'));

		$checkResult = $api->post("http://www.orkut.com/social/rest/activities/@me/@self", array('body' => $checkinData['status'], 'title' => $checkinData['placeName']));
//		$checkResult = $api->get("https://www.googleapis.com/latitude/v1/currentLocation", array('key' => GOOGLE_APIS_KEY, 'latitude' => $checkinData['lat'], 'longitude' => $checkinData['lng']));
//		$checkResult = $api->post("https://www.googleapis.com/latitude/v1/currentLocation?key=" . GOOGLE_APIS_KEY . '&latitude=' . $checkinData['lat'] . '&longitude=' . $checkinData['lng']);
//		var_dump($checkResult);
//		exit;
	}

	private function doFoursquareCheckin($checkinData = '') {
		$callbackurl = ROOT_URL . "oauth/callback/foursquare";
		$foursquareApi = new FourSquareApiV2(\FOURSQUARE_CONSUMER_KEY, \FOURSQUARE_CONSUMER_SECRET, $callbackurl);
		$foursquareApi->setOAuth2Token(Session::read('foursquareToken'));

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
		if (!empty($checkinData['placeName'])) {
			$term = $checkinData['placeName'];
		} else {
			$term = '';
		}
		$limit = 5;
		$intent = 'checkin'; // checkin ou match

		$venues = $foursquareApi->searchVenues($lat, $lng, $radius_mt, $term, $limit, $intent);

		if (!empty($venues)) {
			$checkinData['placeId'] = $venues[0]['id'];
			$checkinData['placeName'] = $venues[0]['name'];

			$checkin = $foursquareApi->checkinVenue($checkinData['placeId'], $checkinData['status'], 'public');
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

		$title = $place->getName() . ' - Foto ' . ($photoId + 1);
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

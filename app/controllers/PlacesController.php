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
use app\models\ABMeta;
use app\models\Location;
use lithium\action\Controller;
use lithium\storage\Session;
use lithium\storage\Cache;

class PlacesController extends Controller {

	public $api;

	public function __construct(array $config = array()) {
		$this->api = new ApontadorApi();
		parent::__construct($config);
	}

	public function index() {
		$location = new Location();
		$location->load();

		$title = "";
		return compact('title', 'location');
	}

	public function search() {

		$searchName = '';
		$suggestions = null;
		$placeList = array();
		$location = new Location();
		$location->load();
		$lat = $location->getPoint()->getLat();
		$lng = $location->getPoint()->getLng();

		if (isset($_GET['q'])) {
			$searchName = $_GET['q'];

			if (!empty($lat) and !empty($lng)) {
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
		$suggestions = $this->api->getSuggestions(array('q' => $searchName));

		$title = "Busca de Locais";
		$title = empty($searchName) ? $title : $title . ": " . $searchName;

		return compact('title', 'location', 'searchName', 'suggestions', 'placeList');
	}

	public function near($page = 'page1') {

		$location = new Location();
		$location->load();
		$lat = $location->getPoint()->getLat();
		$lng = $location->getPoint()->getLng();
		$page = str_replace('page', '', $page);

		if (!empty($lat) and !empty($lng)) {
			$placeList = $this->api->searchByPoint(array(
						'lat' => $lat,
						'lng' => $lng,
						'sort_by' => 'distance',
						'page' => $page
			));
		} else {
			$this->redirect('/places/checkin');
		}

		$title = "Locais por perto";
		return compact('title', 'location', 'placeList', 'page');
	}

	public function gasstations() {

		$location = new Location();
		$location->load();
		$lat = $location->getPoint()->getLat();
		$lng = $location->getPoint()->getLng();

		if (!empty($lat) and !empty($lng)) {
			$placeList = $this->api->searchGasStations(array(
						'lat' => $lat,
						'lng' => $lng
			), 'searchByPoint');
		} else {
			$this->redirect('/places/checkin');
		}

		return compact('title', 'location', 'placeList');
	}

	public function categories($all = null) {

		$location = new Location();
		$location->load();

		if (!empty($all)) {
			$categories = $this->api->getCategories();
			$title = "Todas as categorias";
		} else {
			$categories = $this->api->getCategoriesTop();
			$title = "Principais categorias";
		}

		return compact('title', 'all', 'location', 'categories');
	}

	public function category($categoryId, $page='page1') {

		$location = new Location();
		$location->load();
		$lat = $location->getPoint()->getLat();
		$lng = $location->getPoint()->getLng();
		$page = str_replace('page', '', $page);

		if (empty($categoryId)) {
			$this->redirect('/places/categories');
		}

		$category = $this->api->getSubcategories(array('categoryid' => $categoryId));

		$categoryName = $category->category->name;

		if (!empty($lat) and !empty($lng)) {
			$placeList = $this->api->searchRecursive(array(
						'category_id' => $categoryId,
						'lat' => $lat,
						'lng' => $lng,
						'page' => $page
			), 'searchByPoint');
		} else {
			$this->redirect('/places/checkin');
		}

		$title = $categoryName;

		return compact('title','location', 'page', 'categoryId', 'categoryName', 'placeList');
	}

	public function checkin($placeId = null) {
		if (empty($placeId)) {
			$this->redirect('/');
		}

		$place = $this->api->getPlace(array('placeid' => $placeId));
		$placeName = $place->getName();

		$url = ApontadorApi::encurtaUrl($place->getPlaceUrl());
		$status = "Eu estou em " . $place->getName() . ". " . $url . " #checkin via @sitechegamos";

		if (!empty($_POST) && $place instanceof Place) {
			$location = new Location();
			$location->setPoint($place->getPoint());
			$location->setAddress($place->getAddress());
			$location->save();

			$checkinData['url'] = isset($_POST['url']) ? $_POST['url'] : $url;
			$checkinData['status'] = isset($_POST['status']) ? str_replace("\n", " ", $_POST['status']) : $status;
			$checkinData['providers'] = isset($_POST['providers']) ? $_POST['providers'] : array();

			$checkedin = $this->doCheckin($checkinData);

			if ($checkedin) {
				$this->redirect('/places/checkins/' . $placeId);
			} else {
				$this->redirect($place->getPlaceUrl());
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

		$location = new Location();
		$location->load();

		$title = 'Check-in em ' . $placeName;
		return compact('title', 'providers', 'status', 'location');
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
		$placeId = isset($this->request->params['placeId']) ? $this->request->params['placeId'] : $placeId;

		if (empty($placeId)) {
			$this->redirect('/');
		}

		$place = unserialize(Cache::read('default', $placeId));
		if(empty($place)) {
			$place = $this->api->getPlace(array('placeid' => $placeId));
			if(!empty($place)) {
				Cache::write("default", $placeId, serialize($place),"+1 day");
			}
		}

		if ($place instanceof Place) {

			$thePlaceId = $placeId;

			$location = new Location();
			$location->load();
				
			$placeId = $thePlaceId;

			$visitors = $this->api->getVisitors(array('placeid' => $placeId));
			$place->setNumVisitors($visitors->getNumFound());

			$photos = $this->api->getPhotos(array('placeId' => $placeId));
			$place->setNumPhotos(count($photos->getItems()));

			$showCheckin = true;

			$og = new OpenGraph();
			$og->populate($place);

			$abm = new ABMeta();
			$abm->populate($place);

			$meta = implode('', array(
			$og->getMeta(),
			$abm->getMeta(),
					"\t<link rel=\"canonical\" href=\"".$place->getPlaceUrl()."\" />\n",
			));
			$abmType = $abm->get('type');

			$title = $place->getName();
			return compact('meta', 'title', 'location', 'abmType', 'numVisitors', 'showCheckin', 'place');
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

		$location = new Location();
		$location->load();

		$placeId = $thePlaceId;

		$place = $this->api->getPlace(array('placeid' => $placeId));

		$title = $place->getName() . ' - Quem esteve aqui';
		return compact('title', 'location', 'visitors', 'place');
	}

	public function photos($placeId = null, $photoId = 0) {
		if (empty($placeId)) {
			$this->redirect('/');
		}

		$photos = $this->api->getPhotos(array('placeId' => $placeId));

		$thePlaceId = $placeId;

		$location = new Location();
		$location->load();

		$placeId = $thePlaceId;

		$place = $this->api->getPlace(array('placeid' => $placeId));

		$title = $place->getName() . ' - Foto ' . ($photoId + 1);
		return compact('title', 'location', 'photoId', 'photos', 'place');
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

		$location = new Location();
		$location->load();

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
		return compact('title', 'location', 'reviewId', 'reviews', 'place');
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

	public function buy($placeId = null) {

		if (empty($placeId)) {
			$this->redirect('/');
		}

		$place = $this->api->getPlace(array('placeid' => $placeId));

		$location = new Location();
		$location->load();

		$title = $place->getName() . ' - Quero Destaque';
		return compact('title', 'location', 'place');
	}

}

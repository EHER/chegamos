<?php

namespace app\controllers;

use app\models\Location;

use app\models\ApontadorApi;
use app\models\oauth;
use app\models\ApontadorExtras;
use lithium\storage\Session;
use lithium\storage\Cache;

use chegamos\rest\client\Curl as RestClient;
use chegamos\rest\auth\BasicAuth;
use chegamos\entity\Config;
use chegamos\entity\repository\UserRepository;

use chegamos\entity\City;
use chegamos\entity\Address;
use chegamos\entity\Point;


class ProfileController extends \lithium\action\Controller
{
    private $api;
    private $userRepository;

    public function __construct(array $config = array())
    {
        $this->api = new ApontadorApi();

        $apiConfig = new Config();
        $apiConfig->setBaseUrl(APONTADOR_URL);
        $apiConfig->setBasicAuth(
            new BasicAuth(APONTADOR_CONSUMER_KEY, APONTADOR_CONSUMER_SECRET)
        );
        $apiConfig->setRestClient(
            new RestClient()
        );
        $this->userRepository = new UserRepository($apiConfig);

        parent::__construct($config);
    }

	public function places($userId, $page='page1')
	{
		if (empty($userId)) {
			$this->redirect('/');
		}

		$page = str_replace('page', '', $page);

		$user = $this->api->getUserPlaces(array('userId' => $userId, 'page' => $page));

		$title = 'Locais cadastrados por ' . $user->getName();
		return compact('title', 'user', 'geocode', 'placeId', 'placeName', 'zipcode', 'cityState', 'lat', 'lng');
	}

	public function followingNearby($userId=null, $page='page1')
	{
		if (empty($userId)) {
			OauthController::verifyLogged('apontador');
			$userId = Session::read('apontadorId');
		}

		$page = str_replace('page', '', $page);

		$location = new Location();
		$location->load();
		$lat = $location->getPoint()->getLat();
		$lng = $location->getPoint()->getLng();

		$following = $this->api->getUserFollowing(array(
                    'userId' => $userId,
                    'nearby' => true,
                    'lat' => $lat,
                    'lng' => $lng,
                    'page' => $page
		));

		$user = $this->api->getUser(array('userid' => $userId));

		$title = 'Quem ' . $user->getName() . ' segue e está por perto';
		return compact('title', 'location', 'following');
	}
	
	
	public function following($userId=null, $page='page1')
	{
		if (empty($userId)) {
			OauthController::verifyLogged('apontador');
			$userId = Session::read('apontadorId');
		}

		$page = str_replace('page', '', $page);

		$location = new Location();
		$location->load();
		$lat = $location->getPoint()->getLat();
		$lng = $location->getPoint()->getLng();

		$following = $this->api->getUserFollowing(array(
                    'userId' => $userId,
                    'nearby' => false,
                    'lat' => $lat,
                    'lng' => $lng,
                    'page' => $page
		));

		$user = $this->api->getUser(array('userid' => $userId));

		$title = 'Quem ' . $user->getName() . ' segue';
		return compact('title', 'location', 'following');
	}

	public function followers($userId=null, $page='page1')
	{
		if (empty($userId)) {
			OauthController::verifyLogged('apontador');
			$userId = Session::read('apontadorId');
		}

		$page = str_replace('page', '', $page);

		$location = new Location();
		$location->load();

		$following = $this->api->getUserFollowers(array(
                    'userId' => $userId,
                    'page' => $page
		));
		$user = $this->api->getUser(array('userid' => $userId));

		$title = 'Quem segue ' . $user->getName();
		return compact('title', 'location', 'following');
	}

	public function reviews($userId=null, $page='page1')
	{
		if (empty($userId)) {
			OauthController::verifyLogged('apontador');
			$userId = Session::read('apontadorId');
		}

		$page = str_replace('page', '', $page);

		$location = new Location();
		$location->load();

		$user = $this->api->getUserReviews(array(
                    'userId' => $userId,
                    'page' => $page
		));

		$title = 'Avaliações de ' . $user->getName();
		return compact('title', 'location', 'user');
	}

	public function visits($userId=null, $page='page1')
	{
		if (empty($userId)) {
			OauthController::verifyLogged('apontador');
			$userId = Session::read('apontadorId');
		}

		$page = str_replace('page', '', $page);

		$location = new Location();
		$location->load();

		$visits = $this->api->getUserVisits(array(
                    'userid' => $userId,
                    'page' => $page
		));
		$user = $this->api->getUser(array('userid' => $userId));

		$title = 'Últimas visitas de ' . $user->getName();
		return compact('title', 'location', 'visits', 'user');
	}

	public function show($userId = null)
	{
		if (empty($userId)) {
			OauthController::verifyLogged('apontador');
			$userId = Session::read('apontadorId');
		}

		$user = unserialize(Cache::read('default', $userId));
		if(empty($user)) {
                        $user = $this->userRepository->get($userId);
			if(!empty($user)) {
				Cache::write("default", $userId, serialize($user),"+1 day");
			}
		}

		$location = new Location();
		$location->load();

		$myUserId = Session::read('apontadorId');

		$iFollow = $this->api->isUserFollowedByMe($myUserId, $userId);
		
		$title = 'Perfil de ' . $user->getName();
		return compact('title', 'location', 'user', 'iFollow');
	}

	public function follow($passiveUserId = null)
	{
		if (empty($userId)) {
			OauthController::verifyLogged('apontador');
			$activeUserId = Session::read('apontadorId');
		}

		$response = $this->api->follow(array(
			'activeUserId' => $activeUserId, 
			'passiveUserId' => $passiveUserId,
			'oauth_token' => Session::read('apontadorToken'),
			'oauth_token_secret' => Session::read('apontadorTokenSecret'),
		));
		//echo $response;

		$this->redirect('profile/show/' . $passiveUserId);
		exit;
	}

	public function unfollow($passiveUserId = null)
	{
		if (empty($userId)) {
			OauthController::verifyLogged('apontador');
			$activeUserId = Session::read('apontadorId');
		}

		$response = $this->api->unfollow(array(
			'activeUserId' => $activeUserId, 
			'passiveUserId' => $passiveUserId,
			'oauth_token' => Session::read('apontadorToken'),
			'oauth_token_secret' => Session::read('apontadorTokenSecret'),
		));
		echo $response;

		//$this->redirect('profile/show/' . $passiveUserId);
		exit;
	}

	public function achievements($userId = null)
	{
		if (empty($userId)) {
			OauthController::verifyLogged('apontador');
			$userId = Session::read('apontadorId');
		}
		$user = $this->api->getUser(array('userid' => $userId));

		$location = new Location();
		$location->load();

		$apontadorExtras = new ApontadorExtras();
		$playerProfile = $apontadorExtras->getPlayerProfile($userId);

		$title = 'Conquistas de ' . $user->getName();
		return compact('title', 'location', 'playerProfile', 'user');
	}

	public function near($page = 'page1')
	{
		$page = str_replace('page', '', $page);

		$location = new Location();
		$location->load();
		$lat = $location->getPoint()->getLat();
		$lng = $location->getPoint()->getLng();

		$users = $this->api->searchUsersByPoint(array(
                    'lat' => $lat,
                    'lng' => $lng,
                    'page' => $page
		));

		$title = 'Pessoas por perto';
		return compact('title', 'location', 'users');
	}

	public function location()
	{
		$hideWhereAmI = true;
		$location = new Location();
		$location->load();

		if (!empty($_GET)) {
			$location = new Location();
			if (!empty($_GET['lat']) and !empty($_GET['lng'])) {
				$location->getPoint()->setLat($_GET['lat']);
				$location->getPoint()->setLng($_GET['lng']);
				$location->save();
			} elseif (!empty($_GET['cep'])) {
				$address = new Address();
				$address->setZipcode($_GET['cep']);
				$geocode = $this->api->geocode($address);
				if(!empty($geocode)) {
					$location->getPoint()->setLat($geocode->getLat());
					$location->getPoint()->setLng($geocode->getLng());
					$revgeocode = $this->api->revgeocode($geocode->getLat(), $geocode->getLng());
					$location->setAddress($revgeocode);
					$location->save();
				} else {
					$this->redirect("profile/location");
				}
			} elseif (!empty($_GET['cityState'])) {
				if(!strstr($_GET['cityState'],',')){
					$this->redirect("profile/location");
				}
				$cityStateToUpper = strtoupper($_GET['cityState']);
				list($cityField, $stateField) = \explode(',', $cityStateToUpper);

				$city = new City();
				$city->setName(trim($cityField));
				$city->setState(trim($stateField));

				$address = new Address();
				$address->setCity(new City($city));
				$geocode = $this->api->geocode($address);

				if(!empty($geocode)) {
					$location->getPoint()->setLat($geocode->getLat());
					$location->getPoint()->setLng($geocode->getLng());
					$location->getAddress()->setCity($city);
					$location->save();
				} else {
					$this->redirect("profile/location");
				}
			}
			$this->redirect("/");
		}

		$title = 'Onde estou';
		return compact('title', 'geocode', 'hideWhereAmI', 'location');
	}
}

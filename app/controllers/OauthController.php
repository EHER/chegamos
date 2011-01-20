<?php

namespace app\controllers;

use app\models\ApontadorApi;
use app\models\FourSquareApiV2;
use app\models\TwitterOAuth;
use app\models\OrkutOAuth;
use app\models\Facebook;
use app\models\oauth;
use lithium\storage\Session;

class OauthController extends \lithium\action\Controller {

	public function index($provider = 'apontador') {
		$this->authorize($provider);
	}

	public function verifyLogged($provider = 'apontador') {
		if(!self::isLogged($provider)){
			Session::write('redir', 'http://'.$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"]);
			$this->redirect('/oauth/authorize/'.$provider);
		}
	}

	public static function isLogged($provider = 'apontador') {
		return (bool) Session::read($provider . 'Token');
	}

	public function authorize($provider = 'apontador') {

		if ($provider == 'foursquare') {
			$callbackurl = ROOT_URL . "oauth/callback/foursquare";
			$api = new FourSquareApiV2(\FOURSQUARE_CONSUMER_KEY, \FOURSQUARE_CONSUMER_SECRET, $callbackurl);
			$api->authenticate();
			exit;
		} elseif ($provider == 'twitter') {
			$callbackurl = ROOT_URL . "oauth/callback/twitter";
			$api = new TwitterOAuth(\TWITTER_CONSUMER_KEY, \TWITTER_CONSUMER_SECRET);
			$request_token = $api->getRequestToken($callbackurl);
			$token = $request_token['oauth_token'];
			Session::write('twitterToken', $request_token['oauth_token']);
			Session::write('twitterTokenSecret', $request_token['oauth_token_secret']);

			switch ($api->http_code) {
				case 200:
					$oauthCallbackUrl = $api->getAuthorizeURL($token);
					break;
				default:
					echo 'Could not connect to Twitter. Refresh the page or try again later.';
			}
			$this->redirect($oauthCallbackUrl);
		} elseif ($provider == 'facebook') {
			$api = new Facebook(array(
						'appId' => \FACEBOOK_AP_ID,
						'secret' => \FACEBOOK_SECRET,
						'cookie' => true,
					));
			$callbackurl = ROOT_URL . "oauth/callback/facebook";
			$oauthCallbackUrl = $api->getLoginUrl(array('next' => $callbackurl, 'req_perms' => 'publish_stream'));
			$this->redirect($oauthCallbackUrl);
		} elseif ($provider == 'orkut') {
			$callbackurl = ROOT_URL . "oauth/callback/orkut";
			$scope = 'https://orkut.gmodules.com/social/rest';
			$api = new OrkutOAuth(\ORKUT_CONSUMER_KEY, \ORKUT_CONSUMER_SECRET);
			$request_token = $api->getRequestToken($callbackurl, $scope);
			$token = $request_token['oauth_token'];
			Session::write('orkutToken', $request_token['oauth_token']);
			Session::write('orkutTokenSecret', $request_token['oauth_token_secret']);

			switch ($api->http_code) {
				case 200:
					$oauthCallbackUrl = $api->getAuthorizeURL($token);
					break;
				default:
					echo 'Could not connect to Orkut. Refresh the page or try again later.';
			}
			$this->redirect($oauthCallbackUrl);
		} elseif ($provider == 'apontador') {
			$login = Session::read('login');
			if (empty($login) && APONTADOR_POST_LOGIN == true) {
				$this->redirect('/oauth/login');
			}
			$api = new ApontadorApi();
			$callbackurl = ROOT_URL . "oauth/callback/apontador";
			$oauthCallbackUrl = $api->apontadorRedirectAutorizacao($callbackurl);
			$this->redirect($oauthCallbackUrl);
		}
	}

	public function callback($provider = 'apontador') {
		$redir = Session::read('redir');
		$redir = empty($redir) ? '/settings' : $redir;
		if ($provider == 'foursquare') {
			$code = $_GET["code"];

			$callbackurl = ROOT_URL . "oauth/callback/foursquare";
			$api = new FourSquareApiV2(\FOURSQUARE_CONSUMER_KEY, \FOURSQUARE_CONSUMER_SECRET, $callbackurl);

			$access_token = $api->accessToken($code);
			$user_info = $api->getUser();

			Session::write('foursquareAccessToken', $access_token);
			Session::write('foursquareName', $user_info['name']);
			Session::write('foursquarePhoto', $user_info['photo']);
			Session::write('foursquareEmail', $user_info['email']);
		} elseif ($provider == 'twitter') {
			$verifier = $_GET['oauth_verifier'];

			$twitterToken = Session::read('twitterToken');
			$twitterTokenSecret = Session::read('twitterTokenSecret');

			$api = new TwitterOAuth(TWITTER_CONSUMER_KEY, TWITTER_CONSUMER_SECRET, $twitterToken, $twitterTokenSecret);

			$access_token = $api->getAccessToken($verifier);
			Session::write('twitterUserId', $access_token['user_id']);
			Session::write('twitterName', $access_token['screen_name']);
			Session::write('twitterToken', $access_token['oauth_token']);
			Session::write('twitterTokenSecret', $access_token['oauth_token_secret']);
		} elseif ($provider == 'facebook') {
			$api = new Facebook(array(
						'appId' => \FACEBOOK_AP_ID,
						'secret' => \FACEBOOK_SECRET,
						'cookie' => true,
					));

			$session = $api->getSession();
			$userInfo = $api->api('/me');

			Session::write('facebookToken', $session['access_token']);
			Session::write('facebookSig', $session['sig']);
			Session::write('facebookUid', $session['uid']);
			Session::write('facebookName', $userInfo['name']);
		} elseif ($provider == 'orkut') {
			$verifier = $_GET['oauth_verifier'];
			$oauthToken = $_GET['oauth_token'];

			$orkutToken = Session::read('orkutToken');
			$orkutTokenSecret = Session::read('orkutTokenSecret');

			$api = new OrkutOAuth(\ORKUT_CONSUMER_KEY, \ORKUT_CONSUMER_SECRET, $orkutToken, $orkutTokenSecret);

			$access_token = $api->getAccessToken($verifier, $oauthToken);

			$userInfo = $api->get("http://www.orkut.com/social/rest/people/@me/@self");
			$userName = $userInfo->entry->name->givenName . ' ' . $userInfo->entry->name->familyName;

			Session::write('orkutUserId', $userInfo->entry->id);
			Session::write('orkutName', $userName);
			Session::write('orkutToken', $access_token['oauth_token']);
			Session::write('orkutTokenSecret', $access_token['oauth_token_secret']);
		} elseif ($provider == 'apontador') {
			$api = new ApontadorApi();

			$token = $api->apontadorProcessaAutorizacao();
			$userInfo = $api->getUser(array('userid' => $token['user_id']));

			Session::write('apontadorToken', $token['oauth_token']);
			Session::write('apontadorTokenSecret', $token['oauth_token_secret']);
			Session::write('apontadorId', $token['user_id']);
			Session::write('apontadorName', $userInfo->getName());
		}
		Session::delete('redir');
		$this->redirect($redir);
	}

	public function logout($provider = 'apontador') {

		if ($provider == 'foursquare') {
			Session::delete('foursquareAccessToken');
			Session::delete('foursquareName');
			Session::delete('foursquarePhoto');
			Session::delete('foursquareEmail');
		} elseif ($provider == 'twitter') {
			Session::delete('twitterUserId');
			Session::delete('twitterScreenName');
			Session::delete('twitterToken');
			Session::delete('twitterTokenSecret');
		} elseif ($provider == 'facebook') {
			Session::delete('facebookToken');
			Session::delete('facebookId');
			Session::delete('facebookName');
		} elseif ($provider == 'orkut') {
			Session::delete('orkutUserId');
			Session::delete('orkutScreenName');
			Session::delete('orkutToken');
			Session::delete('orkutTokenSecret');
		} elseif ($provider == 'apontador') {
			Session::delete('apontadorToken');
			Session::delete('apontadorTokenSecret');
			Session::delete('apontadorId');
			Session::delete('apontadorName');
		}
		$this->redirect('/settings');
	}

	public function login() {
		if (!empty($_GET['login'])) {
			Session::write('login', 1);
			$this->redirect(ROOT_URL . 'oauth');
		}
		$callbackUrl = ROOT_URL . 'oauth/login';
		return compact('callbackUrl');
	}

}

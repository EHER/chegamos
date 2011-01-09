<?php

namespace app\controllers;

use app\models\ApontadorApi;
use app\models\FourSquareApiV2;
use app\models\TwitterOAuth;
use app\models\oauth;
use lithium\storage\Session;

class OauthController extends \lithium\action\Controller {

	public function index($provider = 'apontador') {
		$this->authorize($provider);
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
			$this->redirect('/');
		} elseif ($provider == 'apontador') {
			$login = Session::read('login');
			if (empty($login) && APONTADOR_POST_LOGIN == true) {
				$this->redirect('/oauth/login');
			}
			$api = new ApontadorApi();
			$callbackurl = ROOT_URL . "oauth/callback";
			$oauthCallbackUrl = $api->apontadorRedirectAutorizacao($callbackurl);
			$this->redirect($oauthCallbackUrl);
		}
	}

	public function callback($provider = 'apontador') {
		$redir = Session::read('redir');
		$redir = empty($redir) ? ROOT_URL : $redir;
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


			Session::write('twitterToken', $access_token['oauth_token']);
			Session::write('twitterTokenSecret', $access_token['oauth_token_secret']);
		} elseif ($provider == 'facebook') {

		} elseif ($provider == 'apontador') {
			$api = new ApontadorApi();

			$token = $api->apontadorProcessaAutorizacao();

			Session::write('oauthToken', $token['oauth_token']);
			Session::write('oauthTokenSecret', $token['oauth_token_secret']);
			Session::write('userId', $token['user_id']);
		}
		$this->redirect($redir);
	}

	public function logout($provider = 'apontador') {
		if ($provider == 'foursquare') {
			Session::delete('foursquareAccessToken');
			Session::delete('foursquareName');
			Session::delete('foursquarePhoto');
			Session::delete('foursquareEmail');
		} elseif ($provider == 'twitter') {
			Session::delete('twitterToken');
			Session::delete('twitterTokenSecret');
			Session::delete('twitterUserId');
			Session::delete('twitterScreenName');
		} elseif ($provider == 'facebook') {

		} elseif ($provider == 'apontador') {
			Session::delete('oauthToken', array());
			Session::delete('oauthTokenSecret');
			Session::delete('userId');
		}
		$this->redirect('/');
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

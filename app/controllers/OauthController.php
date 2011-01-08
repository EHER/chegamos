<?php

namespace app\controllers;

use app\models\ApontadorApi;
use app\models\FourSquareApiV2;
use app\models\oauth;
use lithium\storage\Session;

class OauthController extends \lithium\action\Controller {

	public function index($provider = '') {
		$this->auth($provider);
	}

	public function auth($provider = '') {

		if ($provider == 'foursquare') {
			$callbackurl = ROOT_URL . "oauth/callback/foursquare";
			$api = new FourSquareApiV2(\FOURSQUARE_CONSUMER_KEY, \FOURSQUARE_CONSUMER_SECRET, $callbackurl);
			$api->authenticate();
			exit;
		} else { // Apontador
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

	public function callback($provider = '') {
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
		} else { // Apontador
			$api = new ApontadorApi();

			$token = $api->apontadorProcessaAutorizacao();

			Session::write('oauthToken', $token['oauth_token']);
			Session::write('oauthTokenSecret', $token['oauth_token_secret']);
			Session::write('userId', $token['user_id']);
		}
		$this->redirect($redir);
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

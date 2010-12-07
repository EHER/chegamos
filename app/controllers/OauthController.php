<?php

namespace app\controllers;

use app\models\ApontadorApi;
use app\models\oauth;
use lithium\storage\Session;

class OauthController extends \lithium\action\Controller {

	public function index() {
		$login = Session::read('login');
		if(empty($login)){
			$this->redirect('/oauth/login');
		}
		$api = new ApontadorApi();
		$callbackurl = ROOT_URL . "oauth/callback";
		$oauthCallbackUrl = $api->apontadorRedirectAutorizacao($callbackurl);
		$this->redirect($oauthCallbackUrl);
	}

	public function login() {
		if(!empty($_GET['login'])) {
			Session::write('login', 1);
			$this->redirect(ROOT_URL . 'oauth');
		}
		$callbackUrl = ROOT_URL . 'oauth/login';
		return compact('callbackUrl');
	}

	public function callback() {
		$api = new ApontadorApi();

		$redir = Session::read('redir');
		$redir = empty($redir) ? ROOT_URL : $redir;

		$token = $api->apontadorProcessaAutorizacao();

		Session::write('oauthToken', $token['oauth_token']);
		Session::write('oauthTokenSecret', $token['oauth_token_secret']);
		Session::write('userId', $token['user_id']);
		$this->redirect($redir);
	}

}

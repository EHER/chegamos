<?php

namespace app\controllers;

use app\models\ApontadorApi;
use app\models\oauth;
use lithium\storage\Session;

class OauthController extends \lithium\action\Controller {

	public function index() {
		$api = new ApontadorApi();
		$callbackurl = ROOT_URL . "oauth/callback";
		$oauthCallbackUrl = $api->apontadorRedirectAutorizacao($callbackurl);
		return compact('oauthCallbackUrl');
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

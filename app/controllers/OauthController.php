<?php

namespace app\controllers;

use app\models\ApontadorApi;
use app\models\oauth;
use lithium\storage\Session;

class OauthController extends \lithium\action\Controller {

	public function redirect() {
		$api = new ApontadorApi();
		$callbackurl = ROOT_URL . "oauth/callback";
		$oauthCallbackUrl = $api->apontadorRedirectAutorizacao($callbackurl);
		$this->redirect($oauthCallbackUrl);
	}

	public function index() {
	    if(!empty($_GET['login']) && $_GET['login']==1) {
	        $this->redirect(ROOT_URL . '/oauth/redirect');
        }
		$callbackUrl = ROOT_URL . 'oauth';
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

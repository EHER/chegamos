<?php

namespace app\controllers;

use lithium\storage\Session;

class SettingsController extends \lithium\action\Controller {

	public function index() {
		$this->_render['layout'] ='dialog';

		$name = Session::read('oauthToken');
		$oauthToken = Session::read('oauthToken');
		$foursquareToken = Session::read('foursquareAccessToken');
		$twitterToken = Session::read('twitterToken');

		$apontadorLogged = !empty($oauthToken);
		$foursquareLogged = !empty($foursquareToken);
		$twitterLogged = !empty($twitterToken);
		$facebookLogged = !empty($facebookAccessToken);

		$title = "";
		return \array_merge(compact('title', 'apontadorLogged', 'foursquareLogged', 'twitterLogged', 'facebookLogged'));
	}
}

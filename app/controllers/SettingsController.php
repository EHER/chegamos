<?php

namespace app\controllers;

use lithium\storage\Session;

class SettingsController extends \lithium\action\Controller {

	public function index() {
		$this->_render['layout'] ='dialog';

		$oauthToken = Session::read('oauthToken');
		$foursquareAccessToken = Session::read('foursquareAccessToken');
		$name = Session::read('oauthToken');

		$apontadorLogged = !empty($oauthToken);
		$foursquareLogged = !empty($foursquareAccessToken);
		$twitterLogged = !empty($twitterAccessToken);
		$facebookLogged = !empty($facebookAccessToken);

		$title = "";
		return \array_merge(compact('title', 'apontadorLogged', 'foursquareLogged', 'twitterLogged', 'facebookLogged'));
	}
}

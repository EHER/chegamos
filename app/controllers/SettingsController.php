<?php

namespace app\controllers;

use lithium\storage\Session;

class SettingsController extends \lithium\action\Controller {

	public function index() {

		Header('Cache-Control: no-cache');
		Header('Pragma: no-cache');
		
		$this->_render['layout'] = 'dialog';

		$apontador['token'] = Session::read('oauthToken');
		$apontador['name'] = Session::read('apontadorName');
		$apontador['email'] = Session::read('apontadorEmail');
		$apontador['logged'] = !empty($apontador['token']);

		$foursquare['token'] = Session::read('foursquareAccessToken');
		$foursquare['name'] = Session::read('foursquareName');
		$foursquare['email'] = Session::read('foursquareEmail');
		$foursquare['logged'] = !empty($foursquare['token']);

		$twitter['token'] = Session::read('twitterToken');
		$twitter['name'] = Session::read('twitterName');
		$twitter['logged'] = !empty($twitter['token']);

		$facebook['token'] = Session::read('facebookToken');
		$facebook['name'] = Session::read('facebookName');
		$facebook['email'] = Session::read('facebookEmail');
		$facebook['logged'] = !empty($facebook['token']);

		$orkut['token'] = Session::read('orkutToken');
		$orkut['name'] = Session::read('orkutName');
		$orkut['email'] = Session::read('orkutEmail');
		$orkut['logged'] = !empty($orkut['token']);

		$title = "Configurações de conta";
		return \array_merge(compact('title', 'apontador', 'foursquare', 'twitter', 'facebook', 'orkut'));
	}

}

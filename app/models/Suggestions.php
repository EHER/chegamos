<?php

namespace app\models;
use lithium\util\Inflector;

class Suggestions {
	private $suggestions = array();

	public function __construct($data=null) {
		$this->setSuggestions($data);
	}
	
	public function __toString() {
		$suggestions = "";
		if(is_array($this->getSuggestions())) {
			foreach($this->getSuggestions() as $suggestion) {
				$suggestions .= '<a href="' . ROOT_URL . 'places/search?q=' . $suggestion . '" rel="external">' . $suggestion . '</a> ';
			}
		}
		return $suggestions;

	}

	public function setSuggestions($suggestions) {
		$this->suggestions = $suggestions;
	}
	
	public function getSuggestions() {
		return $this->suggestions;
	}
}

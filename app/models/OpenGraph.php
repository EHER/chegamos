<?php

namespace app\models;

class OpenGraph {

	private $_values = array();

	public function populate($object) {
		if ($object instanceof Address) {
			if ($object->getStreet() && $object->getNumber()) {
				$this->add('street_address', $object->getStreet() . ', ' . $object->getNumber());
			}
			if ($object->getCity()) {
				$this->add('locality', $object->getCity()->getName());
				$this->add('region', $object->getCity()->getState());
				$this->add('country-name', $object->getCity()->getCountry());
			}

		}
	}

	public function getMeta() {
		$meta = '';

		foreach ($this->getArray() as $property => $content) {
			$meta .= "\t<meta content=\"og:" . $property . '" property="' . $content . '"/>'.PHP_EOL;
		}

		return $meta;
	}

	public function getArray() {
		return $this->_values;
	}

	public function add($index = null, $content) {
		$this->_values[$index] = $content;
	}

}


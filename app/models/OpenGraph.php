<?php

namespace app\models;

class OpenGraph {

	private $_values = array();

	public function populate($object) {
		if ($object instanceof Address) {
			if ($object->getStreet() && $object->getNumber()) {
				$this->add('street_address', $object->getStreet() . ', ' . $object->getNumber());
			}
		}
	}

	public function getMeta() {
		$meta = '';

		foreach ($this->getArray() as $property => $content) {
			$meta .= '<meta content="' . $property . '" property="' . $content . '"/>';
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


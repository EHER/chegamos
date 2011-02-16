<?php

namespace app\models;

class ABMeta {

	private $_values = array();

	public function populate($object) {
		if ($object instanceof Address) {
			if ($object->getStreet() && $object->getNumber()) {
				$this->add('address', $object->getStreet() . ', ' . $object->getNumber());
			}
			if ($object->getCity()) {
				$this->add('city', $object->getCity()->getName());
				$this->add('state', $object->getCity()->getState());
			}
		}

		if ($object instanceof Place) {
			if ($object->getName()) {
				$this->add('title', $object->getName());
			}
			if ($object->getDescription()) {
				$this->add('description', $object->getDescription());
			}
			if ($object->getMapUrl()) {
				$this->add('image', $object->getMapUrl());
			} else {
				$this->add('image', \ROOT_URL . 'img/chegamos.png');
			}
			if ($object->getPlaceUrl()) {
				$this->add('url', $object->getPlaceUrl());
			}
			if ($object->getAddress()->getStreet() && $object->getAddress()->getNumber()) {
				$this->add('address', $object->getAddress()->getStreet() . ', ' . $object->getAddress()->getNumber());
			}
			if ($object->getAddress()->getCity()) {
				$this->add('city', $object->getAddress()->getCity()->getName());
				$this->add('state', $object->getAddress()->getCity()->getState());
				$this->add('country-name', 'Brasil');
			}
			if ($object->getCategory()) {
				switch ($object->getCategory()->getId()){
					case '045':
						$type = 'bar';
						break;
					case '03':
					case '063':
						$type = 'cafe';
						break;
					case '022':
						$type = 'hotel';
						break;
					case '067':
						$type = 'restaurant';
						break;
					default:
						$type = 'company';
				}
				$this->add('type', $type);
			}
		}
	}

	public function getMeta() {
		$meta = '';

		foreach ($this->getArray() as $property => $content) {
			$meta .= "\t<meta property=\"" . $this->get('type') . ":" . $property . '" content="' . $content . '"/>' . PHP_EOL;
		}

		return $meta;
	}

	public function getArray() {
		return $this->_values;
	}

	public function add($index = null, $content) {
		$this->_values[$index] = $content;
	}

	public function get($index = null) {
		return empty($index) ? null : (string) $this->_values[$index];
	}
}


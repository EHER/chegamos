<?php

namespace app\models;

class OpenGraph {

	private $_values = array();

	public function populate($object) {
		if ($object instanceof Address) {
			if ($object->getStreet() && $object->getNumber()) {
				$this->add('street-address', $object->getStreet() . ', ' . $object->getNumber());
			}
			if ($object->getCity()) {
				$this->add('locality', $object->getCity()->getName());
				$this->add('region', $object->getCity()->getState());
				$this->add('country-name', 'Brasil');
			}
		}

		if ($object instanceof Place) {
			if ($object->getName()) {
				$this->add('title', $object->getName());
			}
			if ($object->getIconUrl()) {
				$this->add('image', $object->getIconUrl());
			}
			if ($object->getPlaceUrl()) {
				$this->add('url', $object->getPlaceUrl());
			}
			if ($object->getAddress()->getStreet() && $object->getAddress()->getNumber()) {
				$this->add('street-address', $object->getAddress()->getStreet() . ', ' . $object->getAddress()->getNumber());
			}
			if ($object->getAddress()->getCity()) {
				$this->add('locality', $object->getAddress()->getCity()->getName());
				$this->add('region', $object->getAddress()->getCity()->getState());
				$this->add('country-name', 'Brasil');
			}
			if ($object->getPoint() && $object->getPoint()->getLat() && $object->getPoint()->getLng()) {
				$this->add('latitude', $object->getPoint()->getLat());
				$this->add('longitude', $object->getPoint()->getLng());
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
			$meta .= "\t<meta property=\"og:" . $property . '" content="' . $content . '"/>' . PHP_EOL;
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


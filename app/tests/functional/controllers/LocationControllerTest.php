<?php

namespace app\controllers;

use app\models\Location;

use app\models\City;

use app\models\Address;

class LocationControllerTest extends \PHPUnit_Framework_TestCase {

	public function testShouldUpdateCurrentLocation() {
		$currentLocation = null;
		$latitude = -23.48033;
		$longitude = -46.63459;

		$city = new City();
		$city->setName("São Paulo");
		$city->setState("SP");

		$address = new Address();
		$address->setCity($city);
		$address->setStreet("Rua Funchal");
		$address->setDistrict("Vila Olímpia");

		$api = $this->getMock("ApontadorApi", array("revgeocode"));
		$api->expects($this->once())
		->method("revgeocode")
		->with($latitude, $longitude)
		->will($this->returnValue($address));

		$locationController = new LocationController();
		$locationController->setApi($api);
		$locationController->disableSession();
		$locationController->update($latitude, $longitude);
		$current = $locationController->current();
		$currentLocation = new Location($current['location']);

		$this->assertNotNull($currentLocation);
		$this->assertSame("SP", $currentLocation->getAddress()->getCity()->getState());
		$this->assertSame("São Paulo", $currentLocation->getAddress()->getCity()->getName());
		$this->assertSame("Vila Olímpia", $currentLocation->getAddress()->getDistrict());
		$this->assertSame("Rua Funchal", $currentLocation->getAddress()->getStreet());
		$this->assertSame($latitude, $currentLocation->getPoint()->getLat());
		$this->assertSame($longitude, $currentLocation->getPoint()->getLng());
	}

	public function testShouldUpdateCurrentLocationWhenRevgeoIsDown() {
		$currentLocation = null;
		$latitude = -23.48033;
		$longitude = -46.63459;

		$api = $this->getMock("ApontadorApi", array("revgeocode"));
		$api->expects($this->once())
		->method("revgeocode")
		->with($latitude, $longitude)
		->will($this->returnValue(null));

		$locationController = new LocationController();
		$locationController->setApi($api);
		$locationController->disableSession();
		$locationController->update($latitude, $longitude);
		$current = $locationController->current();
		$currentLocation = new Location($current['location']);

		$this->assertNotNull($currentLocation);
		$this->assertSame($latitude, $currentLocation->getPoint()->getLat());
		$this->assertSame($longitude, $currentLocation->getPoint()->getLng());
	}
}
<?php

namespace app\models;

class OpenGraphTest extends \PHPUnit_Framework_TestCase {

	/**
	 * @var OpenGraph
	 */
	protected $og;

	protected function setUp() {
		$this->og = new OpenGraph;
	}

	protected function tearDown() {
		unset($this->og);
	}

	public function testPopulateWithStreetAddress() {
		$address = new Address();
		$address->setStreet("Rua Funchal");
		$address->setNumber(129);

		$this->og->populate($address);

		$testMeta = <<<META
	<meta content="og:street-address" property="Rua Funchal, 129"/>
META;
		$testArray = array(
			'street-address' => 'Rua Funchal, 129',
		);
		$this->assertEquals($testArray, $this->og->getArray());
	}

	public function testPopulateWithCompleteAddress() {
		$city = new City();
		$city->setCountry('BR');
		$city->setState('SP');
		$city->setName('São Paulo');

		$address = new Address();
		$address->setStreet("Rua Funchal");
		$address->setNumber(129);
		$address->setComplement('6o andar');
		$address->setCity($city);

		$this->og->populate($address);

		$testMeta = <<<META
	<meta content="og:street-address" property="Rua Funchal, 129"/>
	<meta content="og:locality" property="São Paulo"/>
	<meta content="og:region" property="SP"/>
	<meta content="og:country-name" property="Brasil"/>

META;
		$this->assertEquals($testMeta, $this->og->getMeta());

		$testArray = array(
			'street-address' => 'Rua Funchal, 129',
			'locality' => 'São Paulo',
			'region' => 'SP',
			'country-name' => 'Brasil',
		);
		$this->assertEquals($testArray, $this->og->getArray());
	}

	public function testPopulateWithPlace() {
		$point = new Point();
		$point->setLat('-23.59243454');
		$point->setLng('-46.68677054');


		$city = new City();
		$city->setCountry('BR');
		$city->setState('SP');
		$city->setName('São Paulo');

		$address = new Address();
		$address->setStreet("Rua Funchal");
		$address->setNumber(129);
		$address->setComplement('6o andar');
		$address->setCity($city);

		$place = new Place();
		$place->setId("M25GJ288");
		$place->setName("Apontador.com - São Paulo");
		$place->setIconUrl("http://localphoto.s3.amazonaws.com/C40372534F143O1437_9896391605729015_l.jpg");
		$place->setPoint($point);
		$place->setAddress($address);

		$this->og->populate($place);

		$testMeta = <<<META
	<meta content="og:title" property="Apontador.com - São Paulo"/>
	<meta content="og:street-address" property="Rua Funchal, 129"/>
	<meta content="og:locality" property="São Paulo"/>
	<meta content="og:region" property="SP"/>
	<meta content="og:country-name" property="Brasil"/>
	<meta content="og:latitude" property=""/>
	<meta content="og:longitude" property=""/>

META;
		$testArray = array(
			'title' => 'Apontador.com - São Paulo',
			'image' => 'http://localphoto.s3.amazonaws.com/C40372534F143O1437_9896391605729015_l.jpg',
			'url' => ROOT_URL . 'places/show/M25GJ288',
			'street-address' => 'Rua Funchal, 129',
			'locality' => 'São Paulo',
			'region' => 'SP',
			'country-name' => 'Brasil',
			'latitude' => '-23.59243454',
			'longitude' => '-46.68677054',
		);
		$this->assertEquals($testArray, $this->og->getArray());
	}

}
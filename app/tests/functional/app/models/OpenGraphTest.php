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
	<meta property="og:street-address" content="Rua Funchal, 129"/>
	<meta property="og:locality" content="São Paulo"/>
	<meta property="og:region" content="SP"/>
	<meta property="og:country-name" content="Brasil"/>

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
	<meta property="og:title" content="Apontador.com - São Paulo"/>
	<meta property="og:image" content="http://localphoto.s3.amazonaws.com/C40372534F143O1437_9896391605729015_l.jpg"/>
	<meta property="og:url" content="http://192.168.2.103/chegamos/places/show/M25GJ288"/>
	<meta property="og:street-address" content="Rua Funchal, 129"/>
	<meta property="og:locality" content="São Paulo"/>
	<meta property="og:region" content="SP"/>
	<meta property="og:country-name" content="Brasil"/>
	<meta property="og:latitude" content="-23.59243454"/>
	<meta property="og:longitude" content="-46.68677054"/>

META;
		$this->assertEquals($testMeta, $this->og->getMeta());

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
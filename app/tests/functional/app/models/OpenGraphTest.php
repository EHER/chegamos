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

		$this->assertEquals('<meta content="og:street_address" property="Rua Funchal, 129"/>'.PHP_EOL, $this->og->getMeta());
		$this->assertEquals(array('street_address' => 'Rua Funchal, 129'), $this->og->getArray());
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
	<meta content="og:locatity" property="São Paulo"/>
	<meta content="og:region" property="SP"/>
	<meta content="og:country-name" property="BR"/>
META;
		$this->assertEquals($testMeta, $this->og->getMeta());
		
		$testArray = array(
							'street-address' => 'Rua Funchal, 129',
							'locality' => 'São Paulo',
							'region' => 'SP',
							'country-name' => 'BR',
							);
		$this->assertEquals($testArray, $this->og->getArray());
	}

}
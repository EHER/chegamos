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

	public function testPopulateWithAddress() {
		$address = new Address();
		$address->setStreet("Rua Funchal");
		$address->setNumber(129);

		$this->og->populate($address);
		
		$this->assertEquals('<meta content="street_address" property="Rua Funchal, 129"/>', $this->og->getMeta());
		$this->assertEquals(array('street_address' => 'Rua Funchal, 129'), $this->og->getArray());
	}

}
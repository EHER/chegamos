<?php
/**
 * Lithium: the most rad php framework
 *
 * @copyright	 Copyright 2010, Union of RAD (http://union-of-rad.org)
 * @license	   http://opensource.org/licenses/bsd-license.php The BSD License
 */

namespace lithium\tests\cases\console\command\g11n;

use \lithium\console\command\g11n\Extract;
use \lithium\console\Request;

class ExtractTest extends \lithium\test\Unit {

	protected $_path;

	public $command;

	public function skip() {
		$this->_path = LITHIUM_APP_PATH . '/resources/tmp/tests';
		$this->skipIf(!is_writable($this->_path), "{$this->_path} is not writable.");
	}

	public function setUp() {
		$this->command = new Extract(array(
			'request' => new Request(array('input' => fopen('php://temp', 'w+'))),
			'classes' => array('response' => '\lithium\tests\mocks\console\MockResponse')
		));
		mkdir($this->command->source = "{$this->_path}/source");
		mkdir($this->command->destination = "{$this->_path}/destination");
	}

	public function tearDown() {
		$this->_cleanUp();
	}

	public function testFailRead() {
		$result = $this->command->run();
		$expected = 1;
		$this->assertIdentical($expected, $result);

		$expected = "Yielded no items.\n";
		$result = $this->command->response->error;
		$this->assertEqual($expected, $result);
	}

	public function testFailWrite() {
		rmdir($this->command->destination);

		$file = "{$this->_path}/source/a.html.php";
		$data = <<<EOD
<h2>Flowers</h2>
<?=\$t('Apples are green.'); ?>
EOD;
		file_put_contents($file, $data);

		$result = $this->command->run();
		$expected = 1;
		$this->assertIdentical($expected, $result);

		$expected = "Failed to write template.\n";
		$result = $this->command->response->error;
		$this->assertEqual($expected, $result);
	}


	public function testDefaultConfiguration() {
		$file = "{$this->_path}/source/a.html.php";
		$data = <<<EOD
<h2>Flowers</h2>
<?=\$t('Apples are green.'); ?>
EOD;
		file_put_contents($file, $data);

		$result = $this->command->run();
		$expected = 0;
		$this->assertIdentical($expected, $result);

		$expected = '/.*Yielded 1 item.*/';
		$result = $this->command->response->output;
		$this->assertPattern($expected, $result);

		$file = "{$this->_path}/destination/message_default.pot";
		$result = file_exists($file);
		$this->assertTrue($result);

		$result = file_get_contents($file);
		$expected = '/msgid "Apples are green\."/';
		$this->assertPattern($expected, $result);

		$expected = '#/resources/tmp/tests/source/a.html.php:2#';
		$this->assertPattern($expected, $result);

		$result = $this->command->response->error;
		$this->assertFalse($result);
	}
}

?>
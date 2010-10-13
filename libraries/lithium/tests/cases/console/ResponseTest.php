<?php
/**
 * Lithium: the most rad php framework
 *
 * @copyright     Copyright 2010, Union of RAD (http://union-of-rad.org)
 * @license       http://opensource.org/licenses/bsd-license.php The BSD License
 */

namespace lithium\tests\cases\console;

use \lithium\console\Response;
use \lithium\console\Request;

class ResponseTest extends \lithium\test\Unit {

	public $streams;

	public function setUp() {
		$this->streams = array(
			'output' => LITHIUM_APP_PATH . '/resources/tmp/tests/output.txt',
			'error' => LITHIUM_APP_PATH . '/resources/tmp/tests/error.txt'
		);
	}

	public function tearDown() {
		foreach ($this->streams as $path) {
			if (file_exists($path)) {
				unlink($path);
			}
		}
	}

	public function testConstructWithoutConfig() {
		$response = new Response();
		$this->assertTrue(is_resource($response->output));

		$this->assertTrue(is_resource($response->error));
	}

	public function testConstructWithConfigOutput() {
		$base = LITHIUM_APP_PATH . '/resources/tmp/tests';
		$this->skipIf(!is_writable($base), "{$base} is not writable.");

		$stream = fopen($this->streams['output'], 'w');
		$response = new Response(array(
			'output' => $stream
		));
		$this->assertTrue(is_resource($response->output));
		$this->assertEqual($stream, $response->output);

	}

	public function testConstructWithConfigErrror() {
		$base = LITHIUM_APP_PATH . '/resources/tmp/tests';
		$this->skipIf(!is_writable($base), "{$base} is not writable.");

		$stream = fopen($this->streams['error'], 'w');
		$response = new Response(array(
			'error' => $stream
		));
		$this->assertTrue(is_resource($response->error));
		$this->assertEqual($stream, $response->error);

	}

	public function testOutput() {
		$base = LITHIUM_APP_PATH . '/resources/tmp/tests';
		$this->skipIf(!is_writable($base), "{$base} is not writable.");

		$response = new Response(array(
			'output' => fopen($this->streams['output'], 'w+')
		));
		$this->assertTrue(is_resource($response->output));

		$expected = 2;
		$result = $response->output('ok');
		$this->assertEqual($expected, $result);

		$expected = 'ok';
		$result = file_get_contents($this->streams['output']);
		$this->assertEqual($expected, $result);
	}

	public function testError() {
		$base = LITHIUM_APP_PATH . '/resources/tmp/tests';
		$this->skipIf(!is_writable($base), "{$base} is not writable.");

		$response = new Response(array(
			'error' => fopen($this->streams['error'], 'w+')
		));
		$this->assertTrue(is_resource($response->error));

		$expected = 2;
		$result = $response->error('ok');
		$this->assertEqual($expected, $result);

		$expected = 'ok';
		$result = file_get_contents($this->streams['error']);
		$this->assertEqual($expected, $result);
	}
}

?>
<?php
/**
 * Lithium: the most rad php framework
 *
 * @copyright     Copyright 2010, Union of RAD (http://union-of-rad.org)
 * @license       http://opensource.org/licenses/bsd-license.php The BSD License
 */

namespace lithium\tests\cases\console\command\create;

use \lithium\console\command\Create;
use \lithium\console\command\create\Test;
use \lithium\console\Request;
use \lithium\core\Libraries;

class TestTest extends \lithium\test\Unit {

	public $request;

	protected $_backup = array();

	protected $_testPath = null;

	public function skip() {
		$this->_testPath = LITHIUM_APP_PATH . '/resources/tmp/tests';
		$this->skipIf(!is_writable($this->_testPath), "{$this->_testPath} is not writable.");
	}

	public function setUp() {
		Libraries::cache(false);
		$this->classes = array('response' => '\lithium\tests\mocks\console\MockResponse');
		$this->_backup['cwd'] = getcwd();
		$this->_backup['_SERVER'] = $_SERVER;
		$_SERVER['argv'] = array();

		Libraries::add('create_test', array('path' => $this->_testPath . '/create_test'));
		$this->request = new Request(array('input' => fopen('php://temp', 'w+')));
		$this->request->params = array('library' => 'create_test');
	}

	public function tearDown() {
		$_SERVER = $this->_backup['_SERVER'];
		chdir($this->_backup['cwd']);
		$this->_cleanUp();
	}

	public function testTestModel() {
		$this->request->params += array(
			'command' => 'create', 'action' => 'run',
			'args' => array('test', 'model', 'Post')
		);
		$test = new Test(array(
			'request' => $this->request, 'classes' => $this->classes
		));
		$test->path = $this->_testPath;
		$test->run('test');
		$expected = "PostTest created in create_test\\tests\\cases\\models.\n";
		$result = $test->response->output;
		$this->assertEqual($expected, $result);

		$expected = <<<'test'


namespace create_test\tests\cases\models;

use \create_test\models\Post;

class PostTest extends \lithium\test\Unit {

	public function setUp() {}

	public function tearDown() {}


}


test;
		$replace = array("<?php", "?>");
		$result = str_replace($replace, '',
			file_get_contents($this->_testPath . '/create_test/tests/cases/models/PostTest.php')
		);
		$this->assertEqual($expected, $result);
	}

	public function testTestModelWithMethods() {
		mkdir($this->_testPath . '/create_test/models/', 0755, true);
		file_put_contents($this->_testPath . '/create_test/models/Post.php',
"<?php
namespace create_test\models;

class Post {
	public function someMethod() {}
}"
);

		$this->request->params += array(
			'command' => 'create', 'action' => 'run',
			'args' => array('test', 'model', 'Post')
		);
		$test = new Test(array(
			'request' => $this->request, 'classes' => $this->classes
		));
		$test->path = $this->_testPath;
		$test->run('test');
		$expected = "PostTest created in create_test\\tests\\cases\\models.\n";
		$result = $test->response->output;
		$this->assertEqual($expected, $result);

		$expected = <<<'test'


namespace create_test\tests\cases\models;

use \create_test\models\Post;

class PostTest extends \lithium\test\Unit {

	public function setUp() {}

	public function tearDown() {}

	public function testSomeMethod() {}
}


test;
		$replace = array("<?php", "?>");
		$result = str_replace($replace, '',
			file_get_contents($this->_testPath . '/create_test/tests/cases/models/PostTest.php')
		);
		$this->assertEqual($expected, $result);
	}
}

?>
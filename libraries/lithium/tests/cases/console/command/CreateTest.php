<?php
/**
 * Lithium: the most rad php framework
 *
 * @copyright     Copyright 2010, Union of RAD (http://union-of-rad.org)
 * @license       http://opensource.org/licenses/bsd-license.php The BSD License
 */

namespace lithium\tests\cases\console\command;

use \lithium\tests\mocks\console\command\MockCreate;
use \lithium\console\Request;
use \lithium\core\Libraries;
use \lithium\data\Connections;

class CreateTest extends \lithium\test\Unit {

	public $request;

	protected $_backup = array();

	protected $_testPath = null;

	public function skip() {
		$this->_testPath = LITHIUM_APP_PATH . '/resources/tmp/tests';
		$this->skipIf(!is_writable($this->_testPath), "{$this->_testPath} is not writable.");
	}

	public function setUp() {
		$this->_backup['cwd'] = getcwd();
		$this->_backup['_SERVER'] = $_SERVER;
		$this->_backup['app'] = Libraries::get('app');

		$_SERVER['argv'] = array();

		Libraries::add('app', array('path' => $this->_testPath . '/new', 'bootstrap' => false));
		Libraries::add('create_test', array('path' => $this->_testPath . '/create_test'));
		$this->request = new Request(array('input' => fopen('php://temp', 'w+')));
		$this->request->params = array('library' => 'create_test', 'action' => null);

		Connections::add('default', array(
			'type' => null,
			'adapter' => '\lithium\tests\mocks\data\model\MockDatabase'
		));
	}

	public function tearDown() {
		$_SERVER = $this->_backup['_SERVER'];
		chdir($this->_backup['cwd']);
		Libraries::add('app', $this->_backup['app']);
		$this->_cleanUp();
	}

	public function testConstruct() {
		$create = new MockCreate(array('request' => $this->request));

		$expected = 'create_test';
		$result = $create->library;
		$this->assertEqual($expected, $result);
	}

	public function testNonExistentCommand() {
		$this->request->params['args'] = array('does_not_exist', 'anywhere');
		$create = new MockCreate(array('request' => $this->request));

		$expected = false;
		$result = $create->run('does_not_exist');
		$this->assertEqual($expected, $result);

		$expected = "does_not_exist could not be created.\n";
		$result = $create->response->error;
		$this->assertEqual($expected, $result);
	}

	public function testNamespace() {
		$create = new MockCreate(array('request' => $this->request));
        $this->request->command = 'one';

		$expected = 'create_test\\two';
		$result = $create->invokeMethod('_namespace', array(
			$this->request, array(
				'spaces' => array('one' => 'two')
			)
		));
		$this->assertEqual($expected, $result);
	}

	public function testSave() {
		chdir($this->_testPath);
		$this->request->params = array('library' => 'create_test', 'template' => 'test');
		$create = new MockCreate(array('request' => $this->request));
		$result = $create->save(array(
			'namespace' => 'create_test\tests\cases\models',
			'use' => 'create_test\models\Post',
			'class' => 'PostTest',
			'methods' => "\tpublic function testCreate() {\n\n\t}\n",
		));
		$this->assertTrue($result);

		$result = $this->_testPath . '/create_test/tests/cases/models/PostTest.php';
		$this->assertTrue(file_exists($result));

		$this->_cleanUp();
	}

	public function testRunWithoutCommand() {
		$create = new MockCreate(array('request' => $this->request));

		$expected = false;
		$result = $create->run();
		$this->assertEqual($expected, $result);

		$expected = "What would you like to create? (model/view/controller/test/mock) \n > ";
		$result = $create->response->output;
		$this->assertEqual($expected, $result);
	}

	public function testRunNotSaved() {
		$this->request->params['library'] = 'not_here';
		$this->request->params['args'] = array('model', 'Post');
		$create = new MockCreate(array('request' => $this->request));

		$expected = false;
		$result = $create->run('model');
		$this->assertEqual($expected, $result);

		$expected = "model could not be created.\n";
		$result = $create->response->error;
		$this->assertEqual($expected, $result);
	}

	public function testRunWithModelCommand() {
		$this->request->params = array(
			'library' => 'create_test', 'command' => 'create', 'action' => 'model',
			'args' => array('model', 'Post')
		);

		$create = new MockCreate(array('request' => $this->request));

		$create->run('model');

		$expected = 'model';
		$result = $create->request->params['command'];
		$this->assertEqual($expected, $result);

		$result = $this->_testPath . '/create_test/models/Post.php';
		$this->assertTrue(file_exists($result));
	}

	public function testRunWithTestModelCommand() {
		$this->request->params = array(
			'library' => 'create_test', 'command' => 'create', 'action' => 'test',
			'args' => array('test', 'model', 'Post'),
		);

		$create = new MockCreate(array('request' => $this->request));

		$create->run('test');

		$expected = 'model';
		$result = $create->request->command;
		$this->assertEqual($expected, $result);

		$result = $this->_testPath . '/create_test/tests/cases/models/PostTest.php';
		$this->assertTrue(file_exists($result));
	}

	public function testRunWithTestControllerCommand() {
		$this->request->params = array(
			'library' => 'create_test', 'command' => 'create', 'action' => 'test',
			'args' => array('test', 'controller', 'Post'),
		);

		$create = new MockCreate(array('request' => $this->request));

		$create->run('test');

		$expected = 'controller';
		$result = $create->request->command;
		$this->assertEqual($expected, $result);

		$result = $this->_testPath . '/create_test/tests/cases/controllers/PostsControllerTest.php';
		$this->assertTrue(file_exists($result));
	}

	public function testRunWithTestOtherCommand() {
		$this->request->params = array(
			'library' => 'create_test', 'command' => 'create', 'action' => 'test',
			'args' => array('test', 'something', 'Post'),
		);

		$create = new MockCreate(array('request' => $this->request));
		$create->run('test');

		$expected = 'something';
		$result = $create->request->command;
		$this->assertEqual($expected, $result);

		$result = $this->_testPath . '/create_test/tests/cases/something/PostTest.php';
		$this->assertTrue(file_exists($result));
	}

	public function testRunAll() {
		$this->request->params = array(
			'library' => 'create_test', 'command' => 'create', 'action' => 'Post',
			'args' => array('Post'),
		);

		$create = new MockCreate(array('request' => $this->request));
		$create->run('Post');

		$result = $this->_testPath . '/create_test/models/Post.php';
		$this->assertTrue(file_exists($result));

		$result = $this->_testPath . '/create_test/controllers/PostsController.php';
		$this->assertTrue(file_exists($result));

		$result = $this->_testPath . '/create_test/tests/cases/models/PostTest.php';
		$this->assertTrue(file_exists($result));

		$result = $this->_testPath . '/create_test/tests/cases/controllers/PostsControllerTest.php';
		$this->assertTrue(file_exists($result));
	}
}

?>
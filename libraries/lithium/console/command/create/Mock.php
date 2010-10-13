<?php
/**
 * Lithium: the most rad php framework
 *
 * @copyright     Copyright 2010, Union of RAD (http://union-of-rad.org)
 * @license       http://opensource.org/licenses/bsd-license.php The BSD License
 */

namespace lithium\console\command\create;

use \lithium\util\Inflector;

/**
 * Generate a Mock that extends the name of the given class in the `--library` namespace.
 *
 * `li3 create mock model Post`
 * `li3 create --library=li3_plugin mock model Post`
 *
 */
class Mock extends \lithium\console\command\Create {

    /**
     * Get the namespace for the mock.
     *
     * @param string $request
     * @param string $options
     * @return string
     */
	protected function _namespace($request, $options = array()) {
		$request->shift();
		return parent::_namespace($request, array('prepend' => 'tests.mocks.'));
	}

    /**
     * Get the parent for the mock.
     *
     * @param string $request
     * @return string
     */
	protected function _parent($request) {
		$namespace = parent::_namespace($request);
		$class = $request->action;
		return "\\{$namespace}\\{$class}";
	}

    /**
     * Get the class name for the mock.
     *
     * @param string $request
     * @return string
     */
	protected function _class($request) {
		$name = $request->action;
		$type = $request->command;

		if ($command = $this->_instance($type)) {
			$request->params['action'] = $name;
			$name = $command->invokeMethod('_class', array($request));
		}
		return  Inflector::classify("Mock{$name}");
	}

    /**
     * Get the methods for the mock to override
     *
     * @param string $request
     * @return string
     */
	protected function _methods($request) {
		return null;
	}
}

?>
<?php
/**
 * Lithium: the most rad php framework
 *
 * @copyright     Copyright 2010, Union of RAD (http://union-of-rad.org)
 * @license       http://opensource.org/licenses/bsd-license.php The BSD License
 */

namespace lithium\console\command;

use \lithium\core\Libraries;
use \lithium\util\Inflector;
use \lithium\util\String;

/**
 * The `create` command allows you to rapidly develop your models, views, controllers, and tests
 * by generating the minimum code necessary to test and run your application.
 *
 * `li3 create --template=controller Posts`
 * `li3 create --template=model Post`
 *
 */
class Create extends \lithium\console\Command {

	/**
	 * Controls the interactive nature of the command.
	 * When true, the command will ask questions and expect answers to generate the result.
	 * When false, the command will do its best to determine the result to generate.
	 *
	 * @var boolean
	 */
	public $i = false;

	/**
	 * Name of library to use
	 *
	 * @var string
	 */
	public $library = null;

	/**
	 * The name of the template to use to generate the file. This allows you to add a custom
	 * template to be used in place of the core template for each command. Place templates in
	 * `<library>\extensions\command\create\template`.
	 *
	 * @var string
	 */
	public $template = null;

	/**
	 * Holds library data from `\lithium\core\Libraries::get()`
	 *
	 * @var array
	 */
	protected $_library = array();

	/**
	 * Class initializer. Parses template and sets up params that need to be filled.
	 *
	 * @return void
	 */
	protected function _init() {
		parent::_init();
		$this->library = $this->library ?: true;
		$defaults = array('prefix' => null, 'path' => null);
		$this->_library = (array) Libraries::get($this->library) + $defaults;
	}

	/**
	 * Run the create command. Takes `$command` and delegates to `$command::$method`
	 *
	 * @param string $command
	 * @return boolean
	 */
	public function run($command = null) {
		$this->template = $this->template ?: $command;

		if ($command && !$this->request->args(1)) {
			return $this->_default($command);
		}
		if (!$command) {
			$command = $this->in('What would you like to create?', array(
				'choices' => array('model', 'view', 'controller', 'test', 'mock')
			));
		}
		if (!$command) {
			return false;
		}
		if ($this->_execute($command)) {
			return true;
		}
		$this->error("{$command} could not be created.");
		return false;
	}

	/**
	 * [-i] Ask questions and use answers to create.
	 *
	 * @return boolean
	 */
	public function interactive() {
		$this->i = true;
		return $this->run();
	}

	/**
	 * Execute the given sub-command for the current request.
	 *
	 * @param string $command The sub-command name. example: Model, Controller, Test
	 * @param string $params
	 * @return void
	 */
	protected function _execute($command) {
		$this->request->shift(2);

		if (!$class = $this->_instance($command)) {
			return false;
		}
		$data = array();
		$params = $class->invokeMethod('_params');

		foreach ($params as $i => $param) {
			if (!$data[$param] = $class->invokeMethod("_{$param}", array($this->request))) {
				$data[$param] = $this->request->args($i);
			}
		}
		if ($message = $class->invokeMethod('_save', array($data))) {
			$this->out($message);
			return true;
		}
		return false;
	}

	/**
	 * Run through the default set. model, controller, test model, test controller
	 *
	 * @param string $name class name to create
	 * @return boolean
	 */
	protected function _default($name) {
		$commands = array(
			array('model', $name),
			array('controller', $name),
			array('test', 'model', $name),
			array('test', 'controller', Inflector::pluralize($name))
		);
		foreach ($commands as $command) {
			$this->template = $command[0];
			$this->request->params['args'] = $command;

			if (!$this->_execute($command[0])) {
				return false;
			}
		}
		return true;
	}

	/**
	 * Get the namespace.
	 *
	 * @param string $request
	 * @param array $options
	 * @return string
	 */
	protected function _namespace($request, $options  = array()) {
		$name = $request->command;
		$defaults = array(
			'prefix' => $this->_library['prefix'],
			'prepend' => null,
			'spaces' => array(
				'model' => 'models', 'view' => 'views', 'controller' => 'controllers',
				'command' => 'extensions.command', 'adapter' => 'extensions.adapter',
				'helper' => 'extensions.helper'
			)
		);
		$options += $defaults;

		if (isset($options['spaces'][$name])) {
			$name = $options['spaces'][$name];
		}
		return str_replace('.', '\\', $options['prefix'] . $options['prepend'] . $name);
	}

	/**
	 * Parse a template to find available variables specified in `{:name}` format. Each variable
	 * corresponds to a method in the sub command. For example, a `{:namespace}` variable will
	 * call the namespace method in the model command when `li3 create model Post` is called.
	 *
	 * @return array
	 */
	protected function _params() {
		$contents = $this->_template();

		if (empty($contents)) {
			return array();
		}
		preg_match_all('/(?:\{:(?P<params>[^}]+)\})/', $contents, $keys);

		if (!empty($keys['params'])) {
			return array_values(array_unique($keys['params']));
		}
		return array();
	}

	/**
	 * Returns the contents of the template.
	 *
	 * @return string
	 */
	protected function _template() {
		$file = Libraries::locate('command.create.template', $this->template, array(
			'filter' => false, 'type' => 'file', 'suffix' => '.txt.php',
		));
		if (!$file || is_array($file)) {
			return false;
		}
		return file_get_contents($file);
	}

	/**
	 * Get an instance of a sub-command
	 *
	 * @param string $name the name of the sub-command to instantiate
	 * @param array $config
	 * @return object;
	 */
	protected function _instance($name, array $config = array()) {
		if ($class = Libraries::locate('command.create', Inflector::camelize($name))) {
			$this->request->params['i'] = $this->i;
			$this->request->params['template'] = $this->template;

			return new $class(array(
				'request' => $this->request,
				'classes'=> $this->_classes,
			));
		}
		return parent::_instance($name, $config);
	}


	/**
	 * Save a template with the current params. Writes file to `Create::$path`.
	 *
	 * @param string $params
	 * @return boolean
	 */
	protected function _save(array $params = array()) {
		$defaults = array('namespace' => null, 'class' => null);
		$params += $defaults;

		if (empty($params['class']) || empty($this->_library['path'])) {
			return false;
		}
		$contents = $this->_template();
		$result = String::insert($contents, $params);

		$path = str_replace('\\', '/', "{$params['namespace']}\\{$params['class']}");
		$path = $this->_library['path'] . stristr($path, '/');
		$file = str_replace('//', '/', "{$path}.php");
		$directory = dirname($file);

		if ((!is_dir($directory)) && !mkdir($directory, 0755, true)) {
			return false;
		}
		if (file_put_contents($file, "<?php\n\n{$result}\n\n?>")) {
			return "{$params['class']} created in {$params['namespace']}.";
		}
		return false;
	}
}

?>
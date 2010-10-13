<?php
/**
 * Lithium: the most rad php framework
 *
 * @copyright     Copyright 2010, Union of RAD (http://union-of-rad.org)
 * @license       http://opensource.org/licenses/bsd-license.php The BSD License
 */

namespace lithium\net\http;

use lithium\util\String;
use lithium\core\Libraries;
use lithium\net\http\MediaException;

/**
 * The `Media` class facilitates content-type mapping (mapping between content-types and file
 * extensions), handling static assets and globally configuring how the framework handles output in
 * different formats.
 *
 * Using the `Media` class, you can globally configure input and output of different types of
 * content, i.e.:
 * {{{ embed:lithium\tests\cases\net\http\MediaTest::testCustomEncodeHandler(4-12) }}}
 *
 * You may then render CSV content from anywhere in your application. For example, in a controller
 * you may do the following:
 *
 * {{{
 * 	$this->render(array('csv' => Post::find('all')));
 * }}}
 */
class Media extends \lithium\core\StaticObject {

	/**
	 * Maps file extensions to content-types.  Used to set response types and determine request
	 * types.  Can be modified with Media::type().
	 *
	 * @var array
	 * @see lithium\net\http\Media::type()
	 */
	protected static $_types = array();

	/**
	 * A map of media handler objects or callbacks, mapped to media types.
	 *
	 * @var array
	 */
	protected static $_handlers = array();

	/**
	 * Contains default path settings for various asset types.
	 *
	 * For each type, the corresponding array key maps to the general type name, i.e. `'js'` or
	 * `'image'`. Each type contains a set of keys which define their locations and default
	 * behavior. For more information how each key works, see `Media::assets()`.
	 *
	 * @var array
	 * @see lithium\net\http\Media::assets()
	 */
	protected static $_assets = array();

	/**
	 * Returns the list of registered media types.  New types can be set with the `type()` method.
	 *
	 * @return array Returns an array of media type extensions or short-names, which comprise the
	 *         list of types handled.
	 */
	public static function types() {
		return array_keys(static::_types());
	}

	/**
	 * Alias for `types()`; included for interface compatibility with
	 * `lithium\util\Collection::to()`, which allows a collection object to be exported to any
	 * format supported by a `Media` handler. See the documentation for `Collection::to()` for more
	 * information.
	 *
	 * @see lithium\net\http\Media
	 * @return array Returns the value of `Media::types()`.
	 */
	public static function formats() {
		return static::types();
	}

	/**
	 * Alias for `encode()`; included for interface compatibility with
	 * `lithium\util\Collection::to()`, which allows a collection object to be exported to any
	 * format supported by a `Media` handler. See the documentation for `Collection::to()` for more
	 * information.
	 *
	 * @param mixed $format Format into which data will be converted, i.e. `'json'`.
	 * @param mixed $data Either an array or object (usually an instance of `Collection`) which will
	 *              be converted into the specified format.
	 * @param array $options Additional handler-specific options to pass to the content handler.
	 * @return mixed
	 */
	public static function to($format, $data, array $options = array()) {
		return static::encode($format, $data, $options);
	}

	/**
	 * Map an extension to a particular content-type (or types) with a set of options.
	 *
	 * Examples:
	 * {{{// Get a list of all available media types:
	 * Media::types(); // returns array('ai', 'amf', 'atom', ...);
	 * }}}
	 *
	 * {{{// Add a custom media type:
	 * Media::type('my', 'text/x-my', array('view' => '\my\custom\View', 'layout' => false));
	 * }}}
	 *
	 * {{{// Remove a custom media type:
	 * Media::type('my', false);
	 * }}}
	 *
	 * Alternatively, can be used to detect the type name of a registered content type:
	 * {{{
	 * Media::type('application/json'); // returns 'json'
	 * Media::type('application/javascript'); // returns 'javascript'
	 * Media::type('text/javascript'); // also returns 'javascript'
	 * }}}
	 *
	 * @see lithium\net\http\Media::$_types
	 * @see lithium\net\http\Media::$_handlers
	 * @see lithium\util\String::insert()
	 * @param string $type A file extension for the type, i.e. `'txt'`, `'js'`, or `'atom'`.
	 *               Alternatively, may be a content type, i.e. `'text/html'`,
	 *               `'application/atom+xml'`, etc.; in which case, the type name (i.e. '`html'` or
	 *               `'atom'`) will be returned.
	 * @param mixed $content Optional. A string or array containing the content-type(s) that
	 *        `$type` should map to.  If `$type` is an array of content-types, the first one listed
	 *        should be the "primary" type.
	 * @param array $options Optional.  The handling options for this media type. Possible keys are:
	 *        - `'decode'`: A (string) function name or (object) closure that handles
	 *          decoding or unserializing content from this format.
	 *        - `'encode'`: A (string) function name or (object) closure that handles encoding or
	 *          serializing content into this format.
	 *        - `'cast'`: Used with `'encode'`. If `true`, all data passed into the specified encode
	 *           function is first cast to array structures.
	 *        - `'layout'`: Specifies a `String::insert()`-style path to use when searching for
	 *          layout files.
	 *        - `'template'`: Specifies a `String::insert()`-style path to use when searching for
	 *          template files.
	 *        - `'view'`: Specifies the view class to use when rendering this content.
	 * @return mixed If `$content` and `$options` are empty, returns an array with `'content'` and
	 *         `'options'` keys, where `'content'` is the content-type(s) that correspond to
	 *         `$type` (can be a string or array, if multiple content-types are available), and
	 *         `'options'` is the array of options which define how this content-type should be
	 *         handled.  If `$content` or `$options` are non-empty, returns `null`.
	 */
	public static function type($type, $content = null, array $options = array()) {
		$defaults = array(
			'view' => false,
			'template' => false,
			'layout' => false,
			'encode' => false,
			'decode' => false,
			'cast'   => true,
		);

		if ($content === false) {
			unset(static::$_types[$type], static::$_handlers[$type]);
		}
		if (!$content && !$options) {
			if (!$content = static::_types($type)) {
				return;
			}
			if (is_array($content) && isset($content['alias'])) {
				$type = $content['alias'];
				$content = static::_types($type);
			}
			return compact('content') + array('options' => static::_handlers($type));
		}
		if ($content) {
			static::$_types[$type] = $content;
		}
		static::$_handlers[$type] = $options ? ($options + $defaults) : array();
	}

	/**
	 * Gets or sets options for various asset types.
	 *
	 * @see lithium\util\String::insert()
	 * @param string $type The name of the asset type, i.e. `'js'` or `'css'`.
	 * @param array $options If registering a new asset type or modifying an existing asset type,
	 *        contains settings for the asset type, where the available keys are as follows:
	 *        - `'suffix'`: The standard suffix for this content type, with leading dot ('.') if
	 *          applicable.
	 *        - `'filter'`: An array of key/value pairs representing simple string replacements to
	 *          be done on a path once it is generated.
	 *        - `'path'`: An array of key/value pairs where the keys are `String::insert()`
	 *          compatible paths, and the values are array lists of keys to be inserted into the
	 *          path string.
	 * @return array If `$type` is empty, an associative array of all registered types and all
	 *         associated options is returned. If `$type` is a string and `$options` is empty,
	 *         returns an associative array with the options for `$type`. If `$type` and `$options`
	 *         are both non-empty, returns `null`.
	 */
	public static function assets($type = null, $options = array()) {
		$defaults = array('suffix' => null, 'filter' => null, 'path' => array());

		if (!$type) {
			return static::_assets();
		}
		if ($options === false) {
			unset(static::$_assets[$type]);
		}
		if (!$options) {
			return static::_assets($type);
		}
		$options = (array) $options + $defaults;

		if ($base = static::_assets($type)) {
			$options = array_merge($base, array_filter($options));
		}
		static::$_assets[$type] = $options;
	}

	/**
	 * Calculates the web-accessible path to a static asset, usually a JavaScript, CSS or image
	 * file.
	 *
	 * @see lithium\net\http\Media::$_assets
	 * @see lithium\action\Request::env()
	 * @param string $path The path to the asset, relative to the given `$type`s path and without a
	 *        suffix. If the path contains a URI Scheme (eg. `http://`), no path munging will occur.
	 * @param string $type The asset type. See `Media::$_assets` or `Media::assets()`.
	 * @param array $options Contains setting for finding and handling the path, where the keys are
	 *        the following:
	 *        - `'base'`: The base URL of your application. Defaults to `null` for no base path.
	 *          This is usually set with the return value of a call to `env('base')` on an instance
	 *          of `lithium\action\Request`.
	 *        - `'check'`: Check for the existence of the file before returning. Defaults to
	 *          `false`.
	 *        - `'filter'`: An array of key/value pairs representing simple string replacements to
	 *          be done on a path once it is generated.
	 *        - `'path'`: An array of paths to search for the asset in. The paths should use
	 *          `String::insert()` formatting. See `Media::$_assets` for more.
	 *        - `suffix`: The suffix to attach to the path, generally a file extension.
	 *        - `'timestamp'`: Appends the last modified time of the file to the path if `true`.
	 *          Defaults to `false`.
	 *        - `'library'`: The name of the library from which to load the asset. Defaults to
	 *           `true`, for the default library.
	 * @return string Returns the publicly-accessible absolute path to the static asset. If checking
	 *         for the asset's existence (`$options['check']`), returns `false` if it does not exist
	 *         in your `/webroot` directory, or the `/webroot` directories of one of your included
	 *         plugins.
	 * @filter
	 */
	public static function asset($path, $type, array $options = array()) {
		$defaults = array(
			'base' => null,
			'timestamp' => false,
			'filter' => null,
			'path' => array(),
			'suffix' => null,
			'check' => false,
			'library' => true,
		);
		if (!$base = static::_assets($type)) {
			$type = 'generic';
			$base = static::_assets('generic');
		}
		$options += ($base + $defaults);
		$params = compact('path', 'type', 'options');

		return static::_filter(__FUNCTION__, $params, function($self, $params, $chain) {
			$path = $params['path'];
			$type = $params['type'];
			$options = $params['options'];
			$library = $options['library'];

			if (preg_match('/^[a-z0-9-]+:\/\//i', $path)) {
				return $path;
			}
			$config = Libraries::get($library);
			$paths = $options['path'];

			$config['default'] ? end($paths) : reset($paths);
			$options['library'] = basename($config['path']);

			if ($options['suffix'] && strpos($path, $options['suffix']) === false) {
				$path .= $options['suffix'];
			}

			if ($options['check'] || $options['timestamp']) {
				$file = $self::path($path, $type, $options);
			}

			if ($path[0] === '/') {
				$path = "{$options['base']}{$path}";
			} else {
				$path = String::insert(key($paths), compact('path') + $options);
			}

			if ($options['check'] && !is_file($file)) {
				return false;
			}

			if (is_array($options['filter']) && !empty($options['filter'])) {
				$keys = array_keys($options['filter']);
				$values = array_values($options['filter']);
				$path = str_replace($keys, $values, $path);
			}

			if ($options['timestamp'] && is_file($file)) {
				$separator = (strpos($path, '?') !== false) ? '&' : '?';
				$path .= $separator . filemtime($file);
			}
			return $path;
		});
	}

	/**
	 * Gets the physical path to the web assets (i.e. `/webroot`) directory of a library.
	 *
	 * @param string $library The name of the library for which to find the path, or `true` for the
	 *               default library.
	 * @return string Returns the physical path to the web assets directory for a library. For
	 *         example, the `/webroot` directory of the default library would be
	 *         `LITHIUM_APP_PATH . '/webroot'`.
	 */
	public static function webroot($library) {
		if (!$config = Libraries::get($library)) {
			return null;
		}
		if (isset($config['webroot'])) {
			return $config['webroot'];
		}
		if (isset($config['path'])) {
			return $config['path'] . '/webroot';
		}
	}

	/**
	 * Returns the physical path to an asset in the `/webroot` directory of an application or
	 * plugin.
	 *
	 * @param string $path The path to a web asset, relative to the root path for its type. For
	 *               example, for a JavaScript file in `/webroot/js/subpath/file.js`, the correct
	 *               value for `$path` would be `'subpath/file.js'`.
	 * @param string $type A valid asset type, i.e. `'js'`, `'cs'`, `'image'`, or another type
	 *               registered with `Media::assets()`, or `'generic'`.
	 * @param array $options The options used to calculate the path to the file.
	 * @return string Returns the physical filesystem path to an asset in the `/webroot` directory.
	 */
	public static function path($path, $type, array $options = array()) {
		$defaults = array(
			'base' => null,
			'path' => array(),
			'suffix' => null,
			'library' => true,
		);
		if (!$base = static::_assets($type)) {
			$type = 'generic';
			$base = static::_assets('generic');
		}
		$options += ($base + $defaults);
		$config = Libraries::get($options['library']);
		$root = static::webroot($options['library']);
		$paths = $options['path'];

		$config['default'] ? end($paths) : reset($paths);
		$options['library'] = basename($config['path']);

		if ($qOffset = strpos($path, '?')) {
			$path = substr($path, 0, $qOffset);
		}

		if ($path[0] === '/') {
			$file = $root . $path;
		} else {
			$template = str_replace('{:library}/', '', key($paths));
			$insert = array('base' => $root) + compact('path');
			$file = String::insert($template, $insert);
		}
		return realpath($file);
	}

	/**
	 * Renders data (usually the result of a controller action) and generates a string
	 * representation of it, based on the type of expected output.
	 *
	 * @param object $response A reference to a Response object into which the operation will be
	 *        rendered. The content of the render operation will be assigned to the `$body`
	 *        property of the object, and the `'Content-type'` header will be set accordingly.
	 * @param mixed $data
	 * @param array $options
	 * @return void
	 * @filter
	 * @todo Implement proper exception handling
	 */
	public static function render(&$response, $data = null, array $options = array()) {
		$params = array('response' => &$response) + compact('data', 'options');
		$types = static::_types();
		$handlers = static::_handlers();

		static::_filter(__FUNCTION__, $params, function($self, $params) use ($types, $handlers) {
			$defaults = array('encode' => null, 'template' => null, 'layout' => '', 'view' => null);
			$response =& $params['response'];
			$data = $params['data'];
			$options = $params['options'] + array('type' => $response->type());

			$result = null;
			$type = $options['type'];
			$hasHandler = isset($handlers[$type]);
			$handler = $options + ($hasHandler ? $handlers[$type] : array()) + $defaults;

			$filter = function($v) { return $v !== null; };
			$handler = array_filter($handler, $filter) + $handlers['default'] + $defaults;

			if (!$hasHandler) {
				throw new MediaException("Unhandled media type '{$type}'");
			}

			if (isset($types[$type])) {
				$response->headers('Content-type', current((array) $types[$type]));
			}
			$response->body($self::invokeMethod('_handle', array($handler, $data, $response)));
		});
	}

	/**
	 * For media types registered in `$_handlers` which include an `'encode'` setting, encodes data
	 * according to the specified media type.
	 *
	 * @param string $type Specifies the media type into which `$data` will be encoded. This media
	 *        type must have an `'encode'` setting specified in `Media::$_handlers`.
	 * @param mixed $data Arbitrary data you wish to encode. Note that some encoders can only handle
	 *        arrays or objects.
	 * @param array $options Handler-specific options.
	 * @return mixed
	 */
	public static function encode($type, $data, &$response = null) {
		$handler = is_array($type) ? $type : static::_handlers($type);

		if (!$handler || !isset($handler['encode'])) {
			return null;
		}

		$cast = function($data) {
			if (is_object($data)) {
				return method_exists($data, 'to') ? $data->to('array') : get_object_vars($data);
			}
			return $data;
		};

		if (!isset($handler['cast']) || $handler['cast']) {
			$data = is_object($data) ? $cast($data) : $data;
			$data = is_array($data) ? array_map($cast, $data) : $data;
		}
		$method = $handler['encode'];
		return is_string($method) ? $method($data) : $method($data, $handler, $response);
	}

	/**
	 * For media types registered in `$_handlers` which include an `'decode'` setting, decodes data
	 * according to the specified media type.
	 *
	 * @param string $type Specifies the media type into which `$data` will be encoded. This media
	 *        type must have an `'encode'` setting specified in `Media::$_handlers`.
	 * @param mixed $data Arbitrary data you wish to encode. Note that some encoders can only handle
	 *        arrays or objects.
	 * @param array $options Handler-specific options.
	 * @return mixed
	 */
	public static function decode($type, $data, array $options = array()) {
		if ((!$handler = static::_handlers($type)) || !isset($handler['decode'])) {
			return null;
		}
		$method = $handler['decode'];
		return is_string($method) ? $method($data) : $method($data, $handler + $options);
	}

	/**
	 * Resets the `Media` class to its default state. Mainly used for ensuring a consistent state
	 * during testing.
	 *
	 * @return void
	 */
	public static function reset() {
		foreach (get_class_vars(__CLASS__) as $name => $value) {
			static::${$name} = array();
		}
	}

	/**
	 * Called by `Media::render()` to render response content. Given a content handler and data,
	 * calls the content handler and passes in the data, receiving back a rendered content string.
	 *
	 * @see lithium\action\Response
	 * @param array $handler
	 * @param array $data
	 * @param object $response A reference to the `Response` object for this dispatch cycle.
	 * @return string
	 * @filter
	 */
	protected static function _handle($handler, $data, &$response) {
		$params = array('response' => &$response) + compact('handler', 'data');
		return static::_filter(__FUNCTION__, $params, function($self, $params) {
			$response = $params['response'];
			$handler = $params['handler'];
			$data = $params['data'];

			switch (true) {
				case $handler['encode']:
					return $self::encode($handler, $data, $response);
				case class_exists($handler['view']):
					$view = new $handler['view']($handler + array('response' => &$response));
					return $view->render('all', $data, $handler);
				case ($handler['template'] === false) && is_string($data):
					return $data;
				default:
					throw new MediaException("Could not interpret type settings for handler.");
			}
			return $result;
		});
	}

	/**
	 * Helper method for listing registered media types. Returns all types, or a single
	 * content type if a specific type is specified.
	 *
	 * @param string $type Type to return.
	 * @return mixed Array of types, or single type requested.
	 */
	protected static function _types($type = null) {
		$types = static::$_types + array(
			'atom'         => 'application/atom+xml',
			'css'          => 'text/css',
			'form'         => 'application/x-www-form-urlencoded',
			'htm'          => array('alias' => 'html'),
			'html'         => array('text/html', 'application/xhtml+xml', '*/*'),
			'js'           => array('application/javascript', 'text/javascript'),
			'json'         => 'application/json',
			'rss'          => 'application/rss+xml',
			'text'         => 'text/plain',
			'txt'          => array('alias' => 'text'),
			'xml'          => array('application/xml', 'text/xml'),
		);

		if (!$type) {
			return $types;
		}
		if (strpos($type, '/') === false) {
			return isset($types[$type]) ? $types[$type] : null;
		}
		if (strpos($type, ';')) {
			list($type) = explode(';', $type);
		}
		foreach ($types as $name => $cTypes) {
			if ($type == $cTypes || (is_array($cTypes) && in_array($type, $cTypes))) {
				return $name;
			}
		}
	}

	/**
	 * Helper method for listing registered type handlers. Returns all handlers, or the
	 * handler for a specific media type, if requested.
	 *
	 * @param string $type The type of handler to return.
	 * @return mixed Array of all handlers, or the handler for a specific type.
	 */
	protected static function _handlers($type = null) {
		$format = array('view' => false, 'layout' => false);
		$json   = array('encode' => 'json_encode', 'decode' => function($data) {
			return json_decode($data, true);
		});
		$paths  = array(
			'template' => '{:library}/views/{:controller}/{:template}.{:type}.php',
			'layout'   => '{:library}/views/layouts/{:layout}.{:type}.php',
		);

		$handlers = static::$_handlers + array(
			'default' => compact('paths') + array(
				'view'     => 'lithium\template\View',
				'encode'   => false,
				'decode'   => false,
				'cast'     => false,
			),
			'html' => array(),
			'json' => $format + $json + array('cast' => true),
			'text' => $format + array('cast' => false, 'encode' => function($s) { return $s; }),
			'form' => $format + array('cast' => true, 'encode' => 'http_build_query'),
		);

		if ($type) {
			return isset($handlers[$type]) ? $handlers[$type] : null;
		}
		return $handlers;
	}

	/**
	 * Helper method to list all asset paths, or the path for a single type.
	 *
	 * @param string $type The type you wish to get paths for.
	 * @return mixed An array of all paths, or a single array of paths for the
	 *               given type.
	 */
	protected static function _assets($type = null) {
		$assets = static::$_assets + array(
			'js' => array('suffix' => '.js', 'filter' => null, 'path' => array(
				'{:base}/{:library}/js/{:path}' => array('base', 'library', 'path'),
				'{:base}/js/{:path}' => array('base', 'path')
			)),
			'css' => array('suffix' => '.css', 'filter' => null, 'path' => array(
				'{:base}/{:library}/css/{:path}' => array('base', 'library', 'path'),
				'{:base}/css/{:path}' => array('base', 'path')
			)),
			'image' => array('suffix' => null, 'filter' => null, 'path' => array(
				'{:base}/{:library}/img/{:path}' => array('base', 'library', 'path'),
				'{:base}/img/{:path}' => array('base', 'path')
			)),
			'generic' => array('suffix' => null, 'filter' => null, 'path' => array(
				'{:base}/{:library}/{:path}' => array('base', 'library', 'path'),
				'{:base}/{:path}' => array('base', 'path')
			))
		);
		if ($type) {
			return isset($assets[$type]) ? $assets[$type] : null;
		}
		return $assets;
	}
}

?>
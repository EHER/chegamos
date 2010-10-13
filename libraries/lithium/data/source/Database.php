<?php
/**
 * Lithium: the most rad php framework
 *
 * @copyright     Copyright 2010, Union of RAD (http://union-of-rad.org)
 * @license       http://opensource.org/licenses/bsd-license.php The BSD License
 */

namespace lithium\data\source;

use lithium\util\String;
use lithium\core\Libraries;
use lithium\util\Inflector;
use InvalidArgumentException;

/**
 * The `Database` class provides the base-level abstraction for SQL-oriented relational databases.
 * It handles all aspects of abstraction, including formatting for basic query types and SQL
 * fragments (i.e. for joins), converting `Query` objects to SQL, and various other functionality
 * which is shared across multiple relational databases.
 *
 * @see lithium\data\model\Query
 */
abstract class Database extends \lithium\data\Source {

	/**
	 * The supported column types and their default values
	 *
	 * @var array
	 */
	protected $_columns = array(
		'string' => array('length' => 255)
	);

	/**
	 * Strings used to render the given statement
	 *
	 * @see lithium\data\source\Database::renderCommand()
	 * @var string
	 */
	protected $_strings = array(
		'create' => "INSERT INTO {:source} ({:fields}) VALUES ({:values});{:comment}",
		'update' => "UPDATE {:source} SET {:fields} {:conditions};{:comment}",
		'delete' => "DELETE {:flags} From {:source} {:aliases} {:conditions};{:comment}",
		'schema' => "CREATE TABLE {:source} (\n{:columns}{:indexes});{:comment}",
		'join'   => "{:type} JOIN {:source} ON {:constraint}"
	);

	/**
	 * Classes used by `Database`.
	 *
	 * @var array
	 */
	protected $_classes = array(
		'entity' => '\lithium\data\entity\Record',
		'set' => '\lithium\data\collection\RecordSet',
		'relationship' => '\lithium\data\model\Relationship'
	);

	/**
	 * List of SQL operators, paired with handling options.
	 *
	 * @var array
	 */
	protected $_operators = array(
		'='  => array('multiple' => 'IN'),
		'<'  => array(),
		'>'  => array(),
		'<=' => array(),
		'>=' => array(),
		'!=' => array('multiple' => 'NOT IN'),
		'<>' => array('multiple' => 'NOT IN'),
		'between' => array('format' => 'BETWEEN ? AND ?'),
		'BETWEEN' => array('format' => 'BETWEEN ? AND ?')
	);

	/**
	 * A pair of opening/closing quote characters used for quoting identifiers in SQL queries.
	 *
	 * @var array
	 */
	protected $_quotes = array();

	/**
	 * Getter/Setter for the connection's encoding
	 * Abstract. Must be defined by child class.
	 *
	 * @param mixed $encoding
	 * @return mixed.
	 */
	abstract public function encoding($encoding = null);

	/**
	 * Handle the result return from the
	 * Abstract. Must be defined by child class.
	 *
	 * @param string $type next|close The current step in the iteration.
	 * @param mixed $resource The result resource returned from the database.
	 * @param \lithium\data\model\Query $context The given query.
	 * @return void
	 */
	abstract public function result($type, $resource, $context);

	/**
	 * Return the last errors produced by a the execution of a query.
 	 * Abstract. Must be defined by child class.
 	 *
	 */
	abstract public function error();

	/**
	 * Execute a given query
 	 * Abstract. Must be defined by child class.
 	 *
 	 * @see lithium\data\source\Database::renderCommand()
	 * @param string $sql The sql string to execute
	 * @return resource
	 */
	abstract protected function _execute($sql);

	/**
	 * Get the last insert id from the database.
	 * Abstract. Must be defined by child class.
	 *
	 * @param \lithium\data\model\Query $context The given query.
	 * @return void
	 */
	abstract protected function _insertId($query);

	/**
	 * Creates the database object and set default values for it.
	 *
	 * Options defined:
	 *  - 'database' _string_ Name of the database to use. Defaults to 'lithium'.
	 *  - 'host' _string_ Name/address of server to connect to. Defaults to 'localhost'.
	 *  - 'login' _string_ Username to use when connecting to server. Defaults to 'root'.
	 *  - 'password' _string_ Password to use when connecting to server. Defaults to none.
	 *  - 'persistent' _boolean_ If true a persistent connection will be attempted, provided the
	 *    adapter supports it. Defaults to `true`.
	 *
	 * @param $config array Array of configuration options.
	 * @return Database object.
	 */
	public function __construct(array $config = array()) {
		$defaults = array(
			'persistent' => true,
			'host'       => 'localhost',
			'login'      => 'root',
			'password'   => '',
			'database'   => null,
		);
		$this->_strings += array(
			'read' => 'SELECT {:fields} From {:source} {:alias} {:joins} {:conditions} {:group} ' .
			          '{:order} {:limit};{:comment}'
		);
		parent::__construct($config + $defaults);
	}

	/**
	 * Field name handler to ensure proper escaping.
	 *
	 * @param string $name
	 * @return string
	 */
	public function name($name) {
		$open  = reset($this->_quotes);
		$close = next($this->_quotes);
		return preg_match('/^[a-z0-9_-]+$/i', $name) ? "{$open}{$name}{$close}" : $name;
	}

	/**
	 * Converts a given value into the proper type based on a given schema definition.
	 *
	 * @see lithium\data\source\Database::schema()
	 * @param mixed $value The value to be converted. Arrays will be recursively converted.
	 * @param array $schema Formatted array from `\lithium\data\source\Database::schema()`
	 * @return mixed value with converted type
	 */
	public function value($value, array $schema = array()) {
		if (is_array($value)) {
			foreach ($value as $key => $val) {
				$value[$key] = $this->value($val, isset($schema[$key]) ? $schema[$key] : $schema);
			}
			return $value;
		}
		if ($value === null) {
			return 'NULL';
		}
		switch ($type = isset($schema['type']) ? $schema['type'] : $this->_introspectType($value)) {
			case 'boolean':
				return $this->_toNativeBoolean($value);
			case 'float':
				return floatval($value);
			case 'integer':
				return intval($value);
		}
	}

	/**
	 * Inserts a new record into the database based on a the `Query`. The record is updated
	 * with the id of the insert.
	 *
	 * @see lithium\util\String::insert()
	 * @param object $query An SQL query string, or `lithium\data\model\Query` object instance.
	 * @param array $options If $query is a string, $options contains an array of bind values to be
	 *              escaped, quoted, and inserted into `$query` using `String::insert()`.
	 * @return boolean Returns `true` if the query succeeded, otherwise `false`.
	 * @filter
	 */
	public function create($query, array $options = array()) {
		return $this->_filter(__METHOD__, compact('query', 'options'), function($self, $params) {
			$query = $params['query'];
			$model = $entity = $object = $id = null;

			if (is_object($query)) {
				$object = $query;
				$model = $query->model();
				$params = $query->export($self);
				$entity =& $query->entity();
				$query = $self->renderCommand('create', $params, $query);
			} else {
				$query = String::insert($query, $self->value($params['options']));
			}

			if ($self->invokeMethod('_execute', array($query))) {
				if ($entity) {
					if (($model) && !$model::key($entity)) {
						$id = $self->invokeMethod('_insertId', array($object));
					}
					$entity->update($id);
				}
				return true;
			}
			return false;
		});
	}

	/**
	 * Reads records from a database using a `lithium\data\model\Query` object or raw SQL string.
	 *
	 * @param string|object $query `lithium\data\model\Query` object or SQL string.
	 * @param string $options If `$query` is a raw string, contains the values that will be escaped
	 *               and quoted. Other options:
	 *               - `'return'` _string_: switch return between `'array'`, `'item'`, or
	 *                 `'resource'`; defaults to `'item'`.
	 * @return mixed Determined by `$options['return']`.
	 * @filter
	 */
	public function read($query, array $options = array()) {
		$defaults = array('return' => 'item');
		$options += $defaults;

		return $this->_filter(__METHOD__, compact('query', 'options'), function($self, $params) {
			$query = $params['query'];
			$args = $params['options'];
			$return = $args['return'];
			unset($args['return']);

			if (is_string($query)) {
				$sql = String::insert($query, $self->value($args));
			} else {
				$sql = $self->renderCommand($query);
			}
			$result = $self->invokeMethod('_execute', array($sql));

			switch ($return) {
				case 'resource':
					return $result;
				case 'array':
					$columns = $self->schema($query, $result);
					$records = array();

					while ($data = $self->result('next', $result, null)) {
						$records[] = array_combine($columns, $data);
					}
					$self->result('close', $result, null);
					return $records;
				case 'item':
					return $self->item($query->model(), array(), compact('query', 'result') + array(
						'class' => 'set',
						'handle' => $self,
					));
			}
		});
	}

	/**
	 * Updates a record in the database based on the given `Query`.
	 *
	 * @param object $query A `\lithium\data\model\Query` object
	 * @param array $options none
	 * @return boolean
	 */
	public function update($query, array $options = array()) {
		return $this->_filter(__METHOD__, compact('query', 'options'), function($self, $params) {
			$query = $params['query'];
			$params = $query->export($self);
			$sql = $self->renderCommand('update', $params, $query);

			if ($self->invokeMethod('_execute', array($sql))) {
				if ($query->entity()) {
					$query->entity()->update();
				}
				return true;
			}
			return false;
		});
	}

	/**
	 * Deletes a record in the database based on the given `Query`.
	 *
	 * @param object $query An SQL string, or `lithium\data\model\Query` object instance.
	 * @param array $options If `$query` is a string, `$options` is the array of quoted/escaped
	 *              parameter values to be inserted into the query.
	 * @return boolean Returns `true` on successful query execution (not necessarily if records are
	 *         deleted), otherwise `false`.
	 */
	public function delete($query, array $options = array()) {
		return $this->_filter(__METHOD__, compact('query', 'options'), function($self, $params) {
			$query = $params['query'];

			if (is_object($query)) {
				$data = $query->export($self);

				if (!$data['conditions']) {
					return false;
				}
				$sql = $self->renderCommand('delete', $data, $query);
			} else {
				$sql = String::insert($query, $self->value($params['options']));
			}
			return (boolean) $self->invokeMethod('_execute', array($sql));
		});
	}

	/**
	 * Executes calculation-related queries, such as those required for `count` and other
	 * aggregates.
	 *
	 * @param string $type Only accepts `count`.
	 * @param mixed $query The query to be executed.
	 * @param array $options Optional arguments for the `read()` query that will be executed
	 *        to obtain the calculation result.
	 * @return integer Result of the calculation.
	 */
	public function calculation($type, $query, array $options = array()) {
		$query->calculate($type);

		switch ($type) {
			case 'count':
				$fields = $this->fields($query->fields(), $query);
				$query->fields("COUNT({$fields}) as count", true);
				$query->map(array($query->model() => array('count')));
				list($record) = $this->read($query, $options)->data();
				return isset($record['count']) ? intval($record['count']) : null;
		}
	}

	/**
	 * Defines or modifies the default settings of a relationship between two models.
	 *
	 * @param string $class
	 * @param string $type
	 * @param string $name
	 * @param array $config
	 * @return array Returns an array containing the configuration for a model relationship.
	 */
	public function relationship($class, $type, $name, array $config = array()) {
		$singularName = ($type == 'hasMany') ? Inflector::singularize($name) : $name;
		$keys = $type == 'belongsTo' ? $class::meta('name') : $singularName;
		$keys = Inflector::underscore($keys) . '_id';
		$from = $class;
		return $this->_instance('relationship', $config + compact('type', 'name', 'keys', 'from'));
	}

	/**
	 * Returns a newly-created `Record` object, bound to a model and populated with default data
	 * and options.
	 *
	 * @param string $model A fully-namespaced class name representing the model class to which the
	 *               `Record` object will be bound.
	 * @param array $data The default data with which the new `Record` should be populated.
	 * @param array $options Any additional options to pass to the `Record`'s constructor.
	 * @return object Returns a new, un-saved `Record` object bound to the model class specified in
	 *         `$model`.
	 */
	public function item($model, array $data = array(), array $options = array()) {
		return parent::item($model, $data, array('handle' => $this) + $options);
	}

	/**
	 * Returns a given `type` statement for the given data, rendered from `Database::$_strings`.
	 *
	 * @param string $type One of `'create'`, `'read'`, `'update'`, `'delete'` or `'join'`.
	 * @param string $data The data to replace in the string.
	 * @param string $context
	 * @return string
	 */
	public function renderCommand($type, $data = null, $context = null) {
		if (is_object($type)) {
			$context = $type;
			$data = $context->export($this);
			$type = $context->type();

			if (!isset($data['alias'])) {
				$data['alias'] = "AS {$data['name']}";
			}
		}
		if (!isset($this->_strings[$type])) {
			throw new InvalidArgumentException("Invalid query type '{$type}'");
		}
		$data = array_filter($data);
		return trim(String::insert($this->_strings[$type], $data, array('clean' => true)));
	}

	/**
	 * Builds an array of keyed on the fully-namespaced `Model` with array of fields as values
	 * for the given `Query`
	 *
	 * @param object $query A `\lithium\data\model\Query` object
	 * @param string $resource
	 * @param string $context
	 * @return void
	 */
	public function schema($query, $resource = null, $context = null) {
		$model = $query->model();
		$fields = $query->fields();
		$relations = $model::relations();
		$result = array();

		$ns = function($class) use ($model) {
			static $namespace;
			$namespace = $namespace ?: preg_replace('/\w+$/', '', $model);
			return "{$namespace}{$class}";
		};

		if (!$fields) {
			return array($model => array_keys($model::schema()));
		}

		foreach ($fields as $scope => $field) {
			switch (true) {
				case (is_numeric($scope) && $field == '*'):
					$result[$model] = array_keys($model::schema());
				break;
				case (is_numeric($scope) && isset($relations[$field])):
					$scope = $field;
				case (in_array($scope, $relations, true) && $field == '*'):
					$scope = $ns($scope);
					$result[$scope] = array_keys($scope::schema());
				break;
				case (in_array($scope, $relations)):
					$result[$scope] = $fields;
				break;
			}
		}
		return $result;
	}

	/**
	 * Returns a string of formatted conditions to be inserted into the query statement. If the
	 * query conditions are defined as an array, key pairs are converted to SQL strings.
	 *
	 * Conversion rules are as follows:
	 *
	 * - If `$key` is numeric and `$value` is a string, `$value` is treated as a literal SQL
	 *   fragment and returned.
	 *
	 * @param string|array $conditions The conditions for this query.
	 * @param object $context The current `lithium\data\model\Query` instance.
	 * @param array $options
	 *               - `prepend` _boolean_: Whether the return string should be prepended with the
	 *                 `WHERE` keyword.
	 * @return string Returns the `WHERE` clause of an SQL query.
	 */
	public function conditions($conditions, $context, array $options = array()) {
		$defaults = array('prepend' => true);
		$ops = $this->_operators;
		$options += $defaults;
		$model = $context->model();
		$schema = $model ? $model::schema() : array();

		switch (true) {
			case empty($conditions):
				return '';
			case is_string($conditions):
				return ($options['prepend']) ? "WHERE {$conditions}" : $conditions;
			case !is_array($conditions):
				return '';
		}
		$result = array();

		foreach ($conditions as $key => $value) {
			$schema[$key] = isset($schema[$key]) ? $schema[$key] : array();

			switch (true) {
				case (is_numeric($key) && is_string($value)):
					$result[] = $value;
				break;
				case (is_string($key) && is_object($value)):
					$value = trim(rtrim($this->renderCommand($value), ';'));
					$result[] = "{$key} IN ({$value})";
				break;
				case (is_string($key) && is_array($value) && isset($ops[key($value)])):
					foreach ($value as $op => $val) {
						$result[] = $this->_operator($key, array($op => $val), $schema[$key]);
					}
				break;
				case (is_string($key) && is_array($value)):
					$value = join(', ', $this->value($value, $schema));
					$result[] = "{$key} IN ({$value})";
				break;
				default:
					$value = $this->value($value, $schema);
					$result[] = "{$key} = {$value}";
				break;
			}
		}
		$result = join(" AND ", $result);
		return ($options['prepend'] && !empty($result)) ? "WHERE {$result}" : $result;
	}

	/**
	 * Returns either a formatted string for a select query, or an array of key/value pairs for a
	 * create or update query.
	 *
	 * @param array $fields Either an array of field names for a select, or key/value pairs for
	 *              a create or update query.
	 * @param string $context An instance of `Query`, containing the details of the query to be run.
	 * @return mixed Returns a string or array, depending on the query type to be performed (as
	 *         determined by `$context->type()`).
	 */
	public function fields($fields, $context) {
		$type = $context->type();
		$model = $context->model();
		$schema = $model ? (array) $model::schema() : array();

		if ($type == 'create' || $type == 'update') {
			$data = $context->data();

			if ($fields && is_array($fields) && is_int(key($fields))) {
				$data = array_intersect_key($data, array_combine($fields, $fields));
			}
			$method = "_{$type}Fields";
			return $this->{$method}($data, $schema, $context);
		}
		return empty($fields) ? '*' : join(', ', $fields);
	}

	/**
	 * Returns a LIMIT statement from the given limit and the offset of the context object.
	 *
	 * @param integer $limit An
	 * @param object $context The `\lithium\data\model\Query` object
	 * @return string
	 */
	public function limit($limit, $context) {
		if (!$limit) {
			return;
		}
		if ($offset = $context->offset() ?: '') {
			$offset .= ', ';
		}
		return "LIMIT {$offset}{$limit}";
	}

	/**
	 * Returns a join statement for given array of query objects
	 *
	 * @param object|array $joins A single or array of `\lithium\data\model\Query` objects
	 * @param object $context The parent `\lithium\data\model\Query` object
	 * @return string
	 */
	public function joins(array $joins, $context) {
		$result = null;
		foreach ($joins as $join) {
			$result .= $this->renderCommand('join', $join->export($this));
		}
		return $result;
	}

	/**
	 * Return formatted clause for order.
	 *
	 * @param mixed $order The `order` clause to be formatted
	 * @param object $context
	 * @return mixed Formatted `order` clause.
	 */
	public function order($order, $context) {
		$direction = 'ASC';
		$model = $context->model();

		if (is_string($order)) {
			if (!$model::schema($order)) {
				$match = '/\s+(A|DE)SC/i';
				return "ORDER BY {$order}" . (preg_match($match, $order) ? '' : " {$direction}");
			}
			$order = array($order => $direction);
		}

		if (is_array($order)) {
			$result = array();

			foreach ($order as $column => $dir) {
				if (is_int($column)) {
					$column = $dir;
					$dir = $direction;
				}
				if (!in_array($dir, array('ASC', 'asc', 'DESC', 'desc'))) {
					$dir = $direction;
				}
				if ($field = $model::schema($column)) {
					$name = $this->name($model::meta('name')) . '.' . $this->name($column);
					$result[] = "{$name} {$dir}";
				}
			}
			$order = join(', ', $result);
			return "ORDER BY {$order}";
		}
	}

	/**
	 * Adds formatting to SQL comments before they're embedded in queries.
	 *
	 * @param string $comment
	 * @return string
	 */
	public function comment($comment) {
		return $comment ? "/* {$comment} */" : null;
	}

	protected function _createFields($data, $schema, $context) {
		$fields = $values = array();

		while (list($field, $value) = each($data)) {
			$fields[] = $this->name($field);
			$values[] = $this->value($value, isset($schema[$field]) ? $schema[$field] : array());
		}
		$fields = join(', ', $fields);
		$values = join(', ', $values);
		return compact('fields', 'values');
	}

	protected function _updateFields($data, $schema, $context) {
		$fields = array();

		while (list($field, $value) = each($data)) {
			$schema += array($field => array('default' => null));

			if ($value === null && $schema[$field]['default'] === null) {
				continue;
			}
			$fields[] = $this->name($field) . ' = ' . $this->value($value, $schema[$field]);
		}
		return join(', ', $fields);
	}

	/**
	 * Handles conversion of SQL operator keys to SQL statements.
	 *
	 * @param string $key Key in a conditions array. Usually a field name.
	 * @param mixed $value An SQL operator or comparison value.
	 * @param array $schema An array defining the schema of the field used in the criteria.
	 * @param array $options
	 * @return string Returns an SQL string representing part of a `WHERE` clause of a query.
	 */
	protected function _operator($key, $value, array $schema = array(), array $options = array()) {
		$defaults = array('boolean' => 'AND');
		$options += $defaults;

		list($op, $value) = each($value);
		$config = $this->_operators[$op];
		$key = $this->name($key);
		$values = array();

		foreach ((array) $value as $val) {
			$values[] = $this->value($val, $schema);
		}

		switch (true) {
			case (isset($config['format'])):
				return $key . ' ' . String::insert($config['format'], $values);
			case (count($values) > 1 && isset($config['multiple'])):
				$op = $config['multiple'];
				$values = join(', ', $values);
				return "{$key} {$op} ({$values})";
			case (count($values) > 1):
				return join(" {$options['boolean']} ", array_map(
					function($v) use ($key, $op) { return "{$key} {$op} {$v}"; }, $values
				));
		}
		return "{$key} {$op} {$values[0]}";
	}

	/**
	 * Returns a fully-qualified table name (i.e. with prefix), quoted.
	 *
	 * @param string $entity
	 * @return string
	 */
	protected function _entityName($entity) {
		return $this->name($entity);
	}

	/**
	 * Attempts to automatically determine the column type of a value. Used by the `value()` method
	 * of various database adapters to determine how to prepare a value if the schema is not
	 * specified.
	 *
	 * @param mixed $value The value to be prepared for an SQL query.
	 * @return string Returns the name of the column type which `$value` most likely belongs to.
	 */
	protected function _introspectType($value) {
		switch (true) {
			case (is_bool($value)):
				return 'boolean';
			case (is_float($value) || preg_match('/^\d+\.\d+$/', $value)):
				return 'float';
			case (is_int($value) || preg_match('/^\d+$/', $value)):
				return 'integer';
			case (is_string($value) && strlen($value) <= $this->_columns['string']['length']):
				return 'string';
			default:
				return 'text';
		}
	}

	/**
	 * Casts a value which is being written or compared to a boolean-type database column.
	 *
	 * @param mixed $value A value of unknown type to be cast to boolean. Numeric values not equal
	 *              to zero evaluate to `true`, otherwise `false`. String values equal to `'true'`,
	 *              `'t'` or `'T'` evaluate to `true`, all others to `false`. In all other cases,
	 *               uses PHP's default casting.
	 * @return boolean Returns a boolean representation of `$value`, based on the comparison rules
	 *         specified above. Database adapters may override this method if boolean type coercion
	 *         is required and falls outside the rules defined.
	 */
	protected function _toBoolean($value) {
		if (is_bool($value)) {
			return $value;
		}
		if (is_int($value) || is_float($value)) {
			return ($value !== 0);
		}
		if (is_string($value)) {
			return ($value == 't' || $value == 'T' || $value == 'true');
		}
		return (boolean) $value;
	}

	protected function _toNativeBoolean($value) {
		return $value ? 1 : 0;
	}
}

?>
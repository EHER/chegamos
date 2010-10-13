<?php
/**
 * Lithium: the most rad php framework
 *
 * @copyright     Copyright 2010, Union of RAD (http://union-of-rad.org)
 * @license       http://opensource.org/licenses/bsd-license.php The BSD License
 */

namespace lithium\tests\cases\data\collection;

use \lithium\tests\mocks\data\collection\MockRecordSet;
use \lithium\tests\mocks\data\source\database\adapter\MockAdapter;
use \lithium\tests\mocks\data\MockPostObject;
use \lithium\tests\mocks\data\MockModel;
use \lithium\data\Connections;
use \lithium\util\Collection;

/**
 * RecordSet tests
 */
class RecordSetTest extends \lithium\test\Unit {
	/**
	 * RecordSet object to test
	 *
	 * @var object
	 */
	protected $_recordSet = null;

	/**
	 * Object based RecordSet object to test
	 *
	 * @var object
	 */
	protected $_objectRecordSet = null;

	/**
	 * Array of records for testing
	 *
	 * @var array
	 */
	protected $_records = array(
		array('id' => 1, 'data' => 'data1'),
		array('id' => 2, 'data' => 'data2'),
		array('id' => 3, 'data' => 'data3'),
		array('id' => 4, 'data' => 'data4')
	);

	/**
	 * Array of object records for testing
	 *
	 * @var array
	 */
	protected $_objectRecords = array();

	public function setUp() {
		foreach($this->_records as $i => $record) {
			$this->_objectRecords[$i] = new MockPostObject($record);
		}

		$connection = new MockAdapter(array(
			'records' => $this->_records,
			'columns' => array('lithium\tests\mocks\data\MockModel' => array('id', 'data')),
			'autoConnect' => false
		));

		$this->_recordSet = new MockRecordSet(array(
			'model'  => 'lithium\tests\mocks\data\MockModel',
			'handle' => &$connection,
			'result' => true,
			'exists' => true,
		));

		$objectConnection = new MockAdapter(array(
			'records' => $this->_objectRecords,
			'columns' => array('lithium\tests\mocks\data\MockPostObject' => array('id', 'data')),
			'autoConnect' => false
		));

		$this->_objectRecordSet = new MockRecordSet(array(
			'model'  => 'lithium\tests\mocks\data\MockModel',
			'handle' => &$objectConnection,
			'result' => true,
			'exists' => true,
		));
	}

	public function testInit() {
		$recordSet = new MockRecordSet();

		$this->assertTrue(is_a($recordSet, '\lithium\data\collection\RecordSet'));

		$recordSet = new MockRecordSet(array(
			'model'  => 'lithium\tests\mocks\data\MockModel',
			'handle' => new MockAdapter(),
			'result' => true,
			'exists' => true,
		));

		$this->assertEqual('lithium\tests\mocks\data\MockModel', $recordSet->get('_model'));
		$this->assertTrue($recordSet->get('_result'));
	}


	public function testOffsetExists() {
		$this->assertFalse($this->_recordSet->offsetExists(0));
		$this->assertTrue($this->_recordSet->offsetExists(1));
		$this->assertTrue($this->_recordSet->offsetExists(2));
		$this->assertTrue($this->_recordSet->offsetExists(3));
		$this->assertTrue($this->_recordSet->offsetExists(4));

		$this->assertTrue(isset($this->_recordSet[3]));

		$this->assertFalse($this->_objectRecordSet->offsetExists(0));
		$this->assertTrue($this->_objectRecordSet->offsetExists(1));
		$this->assertTrue($this->_objectRecordSet->offsetExists(2));
		$this->assertTrue($this->_objectRecordSet->offsetExists(3));
		$this->assertTrue($this->_objectRecordSet->offsetExists(4));
		$this->assertTrue(isset($this->_objectRecordSet[3]));
	}

	public function testOffsetGet() {
		$expected = array('id' => 1, 'data' => 'data1');
		$this->assertEqual($expected, $this->_recordSet[1]->to('array'));

		$expected = array('id' => 2, 'data' => 'data2');
		$this->assertEqual($expected, $this->_recordSet[2]->to('array'));

		$expected = array('id' => 3, 'data' => 'data3');
		$this->assertEqual($expected, $this->_recordSet[3]->to('array'));

		$expected = array('id' => 4, 'data' => 'data4');
		$this->assertEqual($expected, $this->_recordSet[4]->to('array'));

		$expected = array('id' => 3, 'data' => 'data3');
		$this->assertEqual($this->_records[2], $this->_recordSet[3]->to('array'));

		$recordSet = new MockRecordSet();
		$this->assertEqual(array(), $recordSet->data());

		$this->expectException();
		$this->_recordSet[5];
	}

	public function testOffsetGetObject() {
		$result = $this->_objectRecordSet[1];
		$this->assertEqual(1, $result->id);
		$this->assertEqual('data1', $result->data);

		$result = $this->_objectRecordSet[2];
		$this->assertEqual(2, $result->id);
		$this->assertEqual('data2', $result->data);

		$result = $this->_objectRecordSet[3];
		$this->assertEqual(3, $result->id);
		$this->assertEqual('data3', $result->data);

		$result = $this->_objectRecordSet[4];
		$this->assertEqual(4, $result->id);
		$this->assertEqual('data4', $result->data);

		$this->expectException();
		$this->_objectRecordSet[5];
	}

	public function testOffsetGetBackwards() {
		$expected = array('id' => 4, 'data' => 'data4');
		$this->assertEqual($expected, $this->_recordSet[4]->to('array'));

		$expected = array('id' => 3, 'data' => 'data3');
		$this->assertEqual($expected, $this->_recordSet[3]->to('array'));

		$expected = array('id' => 2, 'data' => 'data2');
		$this->assertEqual($expected, $this->_recordSet[2]->to('array'));

		$expected = array('id' => 1, 'data' => 'data1');
		$this->assertEqual($expected, $this->_recordSet[1]->to('array'));

		$result = $this->_objectRecordSet[4];
		$this->assertEqual(4, $result->id);
		$this->assertEqual('data4', $result->data);

		$result = $this->_objectRecordSet[3];
		$this->assertEqual(3, $result->id);
		$this->assertEqual('data3', $result->data);

		$result = $this->_objectRecordSet[2];
		$this->assertEqual(2, $result->id);
		$this->assertEqual('data2', $result->data);

		$result = $this->_objectRecordSet[1];
		$this->assertEqual(1, $result->id);
		$this->assertEqual('data1', $result->data);
	}

	public function testOffsetSet() {
		$this->_recordSet[5] = $expected = array('id' => 5, 'data' => 'data5');
		$items = $this->_recordSet->get('_data');
		$this->assertEqual($expected, $items[0]->to('array'));

		$this->_recordSet[] = $expected = array('id' => 6, 'data' => 'data6');
		$items = $this->_recordSet->get('_data');
		$this->assertEqual($expected, $items[1]->to('array'));

		$this->_objectRecordSet[5] = $expected = new MockPostObject(array(
			'id' => 5, 'data' => 'data5'
		));
		$items = $this->_recordSet->get('_data');
		$this->assertEqual($expected->id, $items[0]->id);
		$this->assertEqual($expected->data, $items[0]->data);

		$this->_recordSet[] = $expected = new MockPostObject(array('id' => 6, 'data' => 'data6'));
		$items = $this->_recordSet->get('_data');
		$this->assertEqual($expected->id, $items[1]->id);
		$this->assertEqual($expected->data, $items[1]->data);
	}

	public function testOffsetSetWithLoadedData() {
		$this->_recordSet[0];
		$this->_recordSet[1] = array('id' => 1, 'data' => 'new data1');

		$expected = array(
			1 => array('id' => 1, 'data' => 'new data1'),
			2 => array('id' => 2, 'data' => 'data2'),
			3 => array('id' => 3, 'data' => 'data3'),
			4 => array('id' => 4, 'data' => 'data4'),
		);
		$this->assertEqual($expected, $this->_recordSet->to('array'));

		$this->_objectRecordSet[0];
		$this->_objectRecordSet[1] = new MockPostObject(array('id' => 1, 'data' => 'new data1'));

		$result = $this->_objectRecordSet[1];
		$this->assertEqual(1, $result->id);
		$this->assertEqual('new data1', $result->data);

		$result = $this->_objectRecordSet[2];
		$this->assertEqual(2, $result->id);
		$this->assertEqual('data2', $result->data);

		$result = $this->_objectRecordSet[3];
		$this->assertEqual(3, $result->id);
		$this->assertEqual('data3', $result->data);

		$result = $this->_objectRecordSet[4];
		$this->assertEqual(4, $result->id);
		$this->assertEqual('data4', $result->data);
	}

	public function testOffsetUnset() {
		$this->_recordSet[0];
		unset($this->_recordSet[1]);

		$expected = array(
			2 => array('id' => 2, 'data' => 'data2'),
			3 => array('id' => 3, 'data' => 'data3'),
			4 => array('id' => 4, 'data' => 'data4')
		);
		$this->assertEqual($expected, $this->_recordSet->to('array'));

		$this->_objectRecordSet[0];
		unset($this->_objectRecordSet[1]);

		$this->expectException();
		$this->_objectRecordSet[1];

		$result = $this->_objectRecordSet[2];
		$this->assertEqual(2, $result->id);
		$this->assertEqual('data2', $result->data);

		$result = $this->_objectRecordSet[3];
		$this->assertEqual(3, $result->id);
		$this->assertEqual('data3', $result->data);

		$result = $this->_objectRecordSet[4];
		$this->assertEqual(4, $result->id);
		$this->assertEqual('data4', $result->data);
	}

	public function testRewind() {
		$this->_recordSet[0];
		$this->_recordSet->rewind();

		$expected = array('id' => 1, 'data' => 'data1');
		$this->assertEqual($expected, $this->_recordSet->current()->to('array'));

		$this->_objectRecordSet[0];
		$this->_objectRecordSet->rewind();

		$result = $this->_objectRecordSet->current();
		$this->assertEqual(1, $result->id);
		$this->assertEqual('data1', $result->data);
	}

	public function testCurrent() {
		$this->assertFalse(isset($this->_recordSet[0]));

		$this->_recordSet->set('_pointer', 1);
		$this->assertEqual($this->_records[1], $this->_recordSet->current()->to('array'));

		$this->_recordSet->set('_pointer', 2);
		$this->assertEqual($this->_records[2], $this->_recordSet->current()->to('array'));

		$this->assertFalse(isset($this->_objectRecordSet[0]));

		$this->_recordSet->set('_pointer', 1);
		$result = $this->_recordSet->current();
		$this->assertEqual($this->_objectRecords[1]->id, $result->id);
		$this->assertEqual($this->_objectRecords[1]->data, $result->data);

		$this->_recordSet->set('_pointer', 2);
		$result = $this->_recordSet->current();
		$this->assertEqual($this->_objectRecords[2]->id, $result->id);
		$this->assertEqual($this->_objectRecords[2]->data, $result->data);
	}

	public function testKey() {
		$this->assertFalse(isset($this->_recordSet[0]));

		$this->_recordSet->set('_pointer', 1);
		$this->assertEqual(2, $this->_recordSet->key());

		$this->_recordSet->set('_pointer', 2);
		$this->assertEqual(3, $this->_recordSet->key());

		$this->assertFalse(isset($this->_objectRecordSet[0]));

		$this->_objectRecordSet->set('_pointer', 1);
		$this->assertEqual(2, $this->_objectRecordSet->key());

		$this->_objectRecordSet->set('_pointer', 2);
		$this->assertEqual(3, $this->_objectRecordSet->key());
	}

	public function testNextWithForEach() {
		$counter = 0;
		foreach($this->_recordSet as $record) {
			$this->assertEqual($this->_records[$counter], $record->to('array'));
			$counter++;
		}

		$counter = 0;
		foreach($this->_objectRecordSet as $record) {
			$this->assertEqual($this->_objectRecords[$counter]->id, $record->id);
			$this->assertEqual($this->_objectRecords[$counter]->data, $record->data);
			$counter++;
		}
	}

	public function testNextWithWhile() {
		$counter = 0;
		while($record = $this->_recordSet->next()) {
			$this->assertEqual($this->_records[$counter], $record->to('array'));
			$counter++;
		}


		$counter = 0;
		while($record = $this->_objectRecordSet->next()) {
			$this->assertEqual($this->_objectRecords[$counter]->id, $record->id);
			$this->assertEqual($this->_objectRecords[$counter]->data, $record->data);
			$counter++;
		}
	}

	public function testMeta() {
		$expected = array('model' => 'lithium\tests\mocks\data\MockModel');
		$this->assertEqual($expected, $this->_recordSet->meta());

		$expected = array('model' => 'lithium\tests\mocks\data\MockModel');
		$this->assertEqual($expected, $this->_objectRecordSet->meta());
	}

	public function testTo() {
		Collection::formats('\lithium\net\http\Media');

		$this->assertFalse(isset($this->_recordSet[0]));
		$expected = array(
			1 => array('id' => 1, 'data' => 'data1'),
			2 => array('id' => 2, 'data' => 'data2'),
			3 => array('id' => 3, 'data' => 'data3'),
			4 => array('id' => 4, 'data' => 'data4')
		);
		$this->assertEqual($expected, $this->_recordSet->to('array'));

		$expected = '{"1":{"id":1,"data":"data1"},"2":{"id":2,"data":"data2"},'
			. '"3":{"id":3,"data":"data3"},"4":{"id":4,"data":"data4"}}';
		$this->assertEqual($expected, $this->_recordSet->to('json'));
	}

	public function testEach() {
		$filter = function($rec) {
			$rec->more_data = "More Data{$rec->id}";
			return $rec;
		};
		$expected = array(
			1 => array('id' => 1, 'data' => 'data1', 'more_data' => 'More Data1'),
			2 => array('id' => 2, 'data' => 'data2', 'more_data' => 'More Data2'),
			3 => array('id' => 3, 'data' => 'data3', 'more_data' => 'More Data3'),
			4 => array('id' => 4, 'data' => 'data4', 'more_data' => 'More Data4')
		);
		$result = $this->_recordSet->each($filter)->to('array');
		$this->assertEqual($expected, $result);

		$result = $this->_objectRecordSet->each($filter);
		foreach($result as $key => $record) {
			$this->assertEqual($expected[$key]['id'], $record->id);
			$this->assertEqual($expected[$key]['data'], $record->data);
			$this->assertEqual($expected[$key]['more_data'], $record->more_data);
		}
	}

	public function testMap() {
		$filter = function($rec) {
			return $rec->id . $rec->data;
		};
		$expected = array('1data1', '2data2', '3data3', '4data4');

		$result = $this->_recordSet->map($filter, array('collect' => false));
		$this->assertEqual($expected, $result);

		$result = $this->_recordSet->map($filter);

		$this->assertEqual($expected, $result->get('_data'));

		$result = $this->_objectRecordSet->map($filter, array('collect' => false));
		$this->assertEqual($expected, $result);

		$result = $this->_objectRecordSet->map($filter);
		$this->assertEqual($expected, $result->get('_data'));
	}
}

?>
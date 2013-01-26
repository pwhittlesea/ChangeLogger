<?php
/**
 * ChangeLogFixture
 *
 */
class ChangeLogFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'parent_id' => array('type' => 'integer', 'null' => true, 'default' => null),
		'model' => array('type' => 'string', 'null' => false, 'length' => 24, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'model_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'state' => array('type' => 'integer', 'null' => false, 'default' => null),
		'change' => array('type' => 'binary', 'null' => false, 'default' => null),
		'metadata' => array('type' => 'binary', 'null' => true, 'default' => null),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'MyISAM')
	);

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'id' => '12',
			'parent_id' => '3',
			'model' => 'LoggableTime',
			'model_id' => '6',
			'state' => '1',
			'change' => '[]',
			'metadata' => '{"infoA":"valueA","infoB":"2"}',
			'created' => '2013-01-20 18:18:54',
			'modified' => '2013-01-20 18:18:54'
		),
		array(
			'id' => '13',
			'parent_id' => '3',
			'model' => 'LoggableTime',
			'model_id' => '6',
			'state' => '3',
			'change' => '[]',
			'metadata' => '{"infoA":"valueA","infoB":"2"}',
			'created' => '2013-01-20 18:18:54',
			'modified' => '2013-01-20 18:18:54'
		),
		array(
			'id' => '11',
			'parent_id' => '3',
			'model' => 'modelA',
			'model_id' => '5',
			'state' => '3',
			'change' => '[]',
			'metadata' => '{"infoA":"valueB","infoB":"1"}',
			'created' => '2013-01-20 17:47:43',
			'modified' => '2013-01-20 17:47:43'
		),
		array(
			'id' => '9',
			'parent_id' => '3',
			'model' => 'modelA',
			'model_id' => '5',
			'state' => '1',
			'change' => '[]',
			'metadata' => '{"infoA":"valueC","infoB":"0"}',
			'created' => '2013-01-20 17:47:26',
			'modified' => '2013-01-20 17:47:26'
		),
		array(
			'id' => '10',
			'parent_id' => '3',
			'model' => 'modelA',
			'model_id' => '5',
			'state' => '2',
			'change' => '{"description":["old value","new value"],"title":["old value","new value"]}',
			'metadata' => '{"infoA":"valueD","infoB":"-1"}',
			'created' => '2013-01-20 17:47:40',
			'modified' => '2013-01-20 17:47:40'
		),
	);
}

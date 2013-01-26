<?php
/**
 * ChangeLogFixture
 *
 */
class LoggableTimeFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'field1' => array('type' => 'integer', 'null' => true, 'default' => null),
		'field2' => array('type' => 'string', 'null' => false, 'length' => 24, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
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
			'field1' => '3',
			'field2' => 'LoggableTime',
			'created' => '2013-01-20 18:18:54',
			'modified' => '2013-01-20 18:18:54'
		),
	);
}

<?php

class ChangeLoggingBehaviourTest extends CakeTestCase {

	private $__expectedDataA = array(
		array(
			'ChangeLog' => array(
				'id' => '12',
				'parent_id' => '3',
				'model' => 'LoggableTime',
				'model_id' => '6',
				'state' => '1',
				'change' => array(),
				'metadata' => array(
					"infoA" => "valueA",
					"infoB" => "2",
				),
				'created' => '2013-01-20 18:18:54',
				'modified' => '2013-01-20 18:18:54'
			)
		),
		array(
			'ChangeLog' => array(
				'id' => '13',
				'parent_id' => '3',
				'model' => 'LoggableTime',
				'model_id' => '6',
				'state' => '3',
				'change' => array(),
				'metadata' => array(
					"infoA" => "valueA",
					"infoB" => "2",
				),
				'created' => '2013-01-20 18:18:54',
				'modified' => '2013-01-20 18:18:54'
			)
		)
	);

	private $__expectedDataB = array(
		array(
			'ChangeLog' => array(
				'id' => '9',
				'parent_id' => '3',
				'model' => 'modelA',
				'model_id' => '5',
				'state' => '1',
				'change' => array(),
				'metadata' => array(
					"infoA" => "valueC",
					"infoB" => "0"
				),
				'created' => '2013-01-20 17:47:26',
				'modified' => '2013-01-20 17:47:26'
			),
		),
		array(
			'ChangeLog' => array(
				'id' => '10',
				'parent_id' => '3',
				'model' => 'modelA',
				'model_id' => '5',
				'state' => '2',
				'change' => array(
					"description" => array(
						"old value",
						"new value"
					),
					"title" => array(
						"old value",
						"new value"
					)
				),
				'metadata' => array(
					"infoA" => "valueD",
					"infoB" => "-1"
				),
				'created' => '2013-01-20 17:47:40',
				'modified' => '2013-01-20 17:47:40'
			),
		),
		array(
			'ChangeLog' => array(
				'id' => '11',
				'parent_id' => '3',
				'model' => 'modelA',
				'model_id' => '5',
				'state' => '3',
				'change' => array(),
				'metadata' => array(
					"infoA" => "valueB",
					"infoB" => "1"
				),
				'created' => '2013-01-20 17:47:43',
				'modified' => '2013-01-20 17:47:43'
			),
		),
	);

	public $fixtures = array(
		'plugin.change_logger.change_log',
		'plugin.change_logger.loggable_time',
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->LoggableTime = ClassRegistry::init('LoggableTime');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->__testModel);
		parent::tearDown();
	}

	public function testChangeLogById1() {
		$actualData = $this->LoggableTime->changeLogById(6, 'LoggableTime');
		$this->assertEquals($this->__expectedDataA, $actualData, "Output was not as expected");
	}

	public function testChangeLogById2() {
		$actualData = $this->LoggableTime->changeLogById(6);
		$this->assertEquals($this->__expectedDataA, $actualData, "Output was not as expected");
	}

	public function testChangeLogById3() {
		$this->LoggableTime->id = 6;
		$actualData = $this->LoggableTime->changeLogById();
		$this->assertEquals($this->__expectedDataA, $actualData, "Output was not as expected");
	}

	public function testChangeLogById4() {
		$this->LoggableTime->id = 3;
		$actualData = $this->LoggableTime->changeLogById(6);
		$this->assertEquals($this->__expectedDataA, $actualData, "Output was not as expected");
	}

	public function testChangeLogById5() {
		$actualData = $this->LoggableTime->changeLogById(6, null, 1);
		$this->assertEquals(array($this->__expectedDataA[0]), $actualData, "Output was not as expected");
	}

	public function testChangeLogById6() {
		$actualData = $this->LoggableTime->changeLogById(6, null, 1, 1);
		$this->assertEquals(array($this->__expectedDataA[1]), $actualData, "Output was not as expected");
	}

	public function testChangeLogByParentId1() {
		$actualData = $this->LoggableTime->changeLogByParentId(3, 'modelA');
		$this->assertEquals($this->__expectedDataB, $actualData, "Output was not as expected");
	}

	public function testChangeLogByParentId2() {
		$this->LoggableTime->id = 3;
		$actualData = $this->LoggableTime->changeLogByParentId(null, 'modelA');
		$this->assertEquals($this->__expectedDataB, $actualData, "Output was not as expected");
	}

	public function testCreate() {
		$this->LoggableTime->create();
		$loggableTime = array(
			'field1' => 1,
			'field2' => 'two'
		);
		$this->LoggableTime->save($loggableTime);

		$expectedLogs = array(
			array(
				'ChangeLog' => array(
					'id' => '14',
					'parent_id' => 1,
					'model' => 'loggabletime',
					'model_id' => '13',
					'state' => '1',
					'change' => array(),
					'metadata' => array()
				)
			)
		);
		$actualData = $this->LoggableTime->changeLogById();
		unset($actualData[0]['ChangeLog']['created']);
		unset($actualData[0]['ChangeLog']['modified']);
		$this->assertEquals($expectedLogs, $actualData, "Log was not created as expected");
	}

	public function testUpdate() {
		$this->LoggableTime->recursive = -1;
		$time = $this->LoggableTime->findById(12);
		$time['LoggableTime']['field1'] = 5;
		$this->LoggableTime->save($time);

		$expectedLogs = array(
			array(
				'ChangeLog' => array(
					'id' => '14',
					'parent_id' => 1,
					'model' => 'loggabletime',
					'model_id' => '12',
					'state' => '2',
					'change' => array(
						'field1' => array(
							'3',
							'5'
						)
					),
					'metadata' => array(),
				)
			)
		);
		$actualData = $this->LoggableTime->changeLogById(12);
		unset($actualData[0]['ChangeLog']['created']);
		unset($actualData[0]['ChangeLog']['modified']);
		$this->assertEquals($expectedLogs, $actualData, "Log was not created as expected");
	}

	public function testDelete() {
		$this->LoggableTime->delete(12);

		$expectedLogs = array(
			array(
				'ChangeLog' => array(
					'id' => '14',
					'parent_id' => 1,
					'model' => 'loggabletime',
					'model_id' => '12',
					'state' => '3',
					'change' => array(),
					'metadata' => array(),
				)
			)
		);
		$actualData = $this->LoggableTime->changeLogById(12);
		unset($actualData[0]['ChangeLog']['created']);
		unset($actualData[0]['ChangeLog']['modified']);
		$this->assertEquals($expectedLogs, $actualData, "Log was not created as expected");
	}

}

class LoggableTime extends CakeTestModel {

	public $name = 'LoggableTime';

	public $actsAs = array(
		'ChangeLogger.ChangeLogging'
	);

	public $hasMany = array(
		'ChangeLog' => array(
			'className' => 'ChangeLogger.ChangeLog',
		)
	);

	public function getMetadataForChange() {
		return array(
			'parent_id' => 1
		);
	}
}
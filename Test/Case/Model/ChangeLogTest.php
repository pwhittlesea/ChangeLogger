<?php
App::uses('ChangeLog', 'ChangeLogger.Model');

class ChangeLogTestCase extends CakeTestCase {

	public $fixtures = array('Plugin.ChangeLogger.ChangeLog');

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->ChangeLog = ClassRegistry::init('ChangeLogger.ChangeLog');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->ChangeLog);
		parent::tearDown();
	}

	public function testBeforeSaveNoMetaData() {
		$this->ChangeLog->data = array(
			'ChangeLog' => array(
				'change' => array(
					"infoA" => "valueA",
					"infoB" => "2"
				)
			)
		);

		$expectedData = array(
			'ChangeLog' => array(
				'change' => '{"infoA":"valueA","infoB":"2"}'
			)
		);

		$this->ChangeLog->beforeSave(null);
		$this->assertEquals($expectedData, $this->ChangeLog->data, "Before save was not JSON encoded");
	}

	public function testBeforeSaveNoChange() {
		$this->ChangeLog->data = array(
			'ChangeLog' => array(
				'metadata' => array(
					"infoA" => "valueA",
					"infoB" => "2"
				)
			)
		);

		$expectedData = array(
			'ChangeLog' => array(
				'metadata' => '{"infoA":"valueA","infoB":"2"}'
			)
		);

		$this->ChangeLog->beforeSave(null);
		$this->assertEquals($expectedData, $this->ChangeLog->data, "Before save was not JSON encoded");
	}

	public function testAfterFind() {
		$inputRecord = array(
			array(
				'ChangeLog' => array(
					'id' => '10',
					'parent_id' => '3',
					'model' => 'modelA',
					'model_id' => '5',
					'state' => '2',
					'change' => '{"description":["old value","new value"],"title":["old value","new value"]}',
					'metadata' => '{"infoA":"valueD","infoB":"-1"}',
					'created' => '2013-01-20 17:47:40',
					'modified' => '2013-01-20 17:47:40'
				)
			)
		);

		$expectedData = array(
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
						"infoB" => "-1",
					),
					'created' => '2013-01-20 17:47:40',
					'modified' => '2013-01-20 17:47:40'
				)
			)
		);

		$this->assertEquals($expectedData, $this->ChangeLog->afterFind($inputRecord, false), "After find was not JSON Encoded");
	}

}

<?php
/**
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     ChangeLogger Development Team 2012
 * @link          http://github.com/pwhittlesea/ChangeLogger
 * @package       GitCake.Model
 * @since         GitCake v 1.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

class ChangeLoggingBehavior extends ModelBehavior {

	private $__modelCache = array();

/**
 * tableName
 * The master table name
 */
	private $__tableName;

/**
 * settings for the behaviour.
 */
	public $settings = array();

	private function __getMetadataForChange(Model $model) {
		if (method_exists($model, 'getMetadataForChange')) {
			return $model->getMetadataForChange();
		}
		return array();
	}

/**
 * __getTableName function.
 * Fetch the tablename to use for logging in order of priority:
 *	1) Model specified name
 *	2) Global config name
 *	3) string 'change_logs'
 *
 * @param Model $model the model we are configuring
 */
	private function __getTableName(Model $model) {
		if (!isset($this->__tableName)) {
			$config = Configure::read('changeLogger');
			if (!isset($config['tableName']) || $config['tableName'] == null) {
				$this->__tableName = 'change_logs';
			} else {
				$this->__tableName = $config['tableName'];
			}
		}
		if (isset($this->settings[$model->name]['tableName'])) {
			return $this->settings[$model->name]['tableName'];
		} else {
			return $this->__tableName;
		}
	}

	private function __prepare(Model $model, $clobber = false) {
		if (!isset($this->__modelCache[$model->name])) {
			$this->__modelCache[$model->name] = array();
		}
		if ($clobber) {
			$this->__modelCache[$model->name][$model->id] = array();
		}
	}

	private function __recordCreation(Model $model) {
		$newChangeLog = array(
			'ChangeLog' => array(
				'model' => strtolower($model->name),
				'model_id' => $model->id,
				'state' => ItemState::CREATED,
				'change' => array(),
				'metadata' => $this->__getMetadataForChange($model),
			)
		);

		if (isset($newChangeLog['ChangeLog']['metadata']['parent_id'])) {
			$newChangeLog['ChangeLog']['parent_id'] = $newChangeLog['ChangeLog']['metadata']['parent_id'];
			unset($newChangeLog['ChangeLog']['metadata']['parent_id']);
		}

		$model->ChangeLog->save($newChangeLog);

		return true;
	}

	private function __recordModification(Model $model) {
		if (empty($this->__modelCache[$model->name][$model->id])) {
			return true;
		}

		$newChangeLog = array(
			'ChangeLog' => array(
				'model' => strtolower($model->name),
				'model_id' => $model->id,
				'state' => ItemState::MODIFIED,
				'change' => array(),
				'metadata' => $this->__getMetadataForChange($model),
			)
		);

		if (isset($newChangeLog['ChangeLog']['metadata']['parent_id'])) {
			$newChangeLog['ChangeLog']['parent_id'] = $newChangeLog['ChangeLog']['metadata']['parent_id'];
			unset($newChangeLog['ChangeLog']['metadata']['parent_id']);
		}

		foreach ($this->__modelCache[$model->name][$model->id] as $field => $oldValue) {
			$newChangeLog['ChangeLog']['change'][$field] = array($oldValue, $model->field($field));
		}

		$model->ChangeLog->save($newChangeLog);

		return true;
	}

/**
 * See: http://book.cakephp.org/2.0/en/models/behaviors.html#creating-a-behavior-callback
 */
	public function afterDelete(Model $model) {
		$model->ChangeLog->save($this->__modelCache[$model->name][$model->id]);
		return true;
	}

/**
 * See: http://book.cakephp.org/2.0/en/models/behaviors.html#creating-a-behavior-callback
 * Store the changes that just happened
 */
	public function afterSave(Model $model, $created = false) {
		if ($created) {
			return $this->__recordCreation($model);
		} else {
			return $this->__recordModification($model);
		}
	}

/**
 * See: http://book.cakephp.org/2.0/en/models/behaviors.html#creating-a-behavior-callback
 */
	public function beforeDelete(Model $model, $cascade = true) {
		$this->__prepare($model, true);

		$newChangeLog = array(
			'ChangeLog' => array(
				'model' => strtolower($model->name),
				'model_id' => $model->id,
				'state' => ItemState::DELETED,
				'change' => array(),
				'metadata' => $this->__getMetadataForChange($model),
			)
		);

		if (isset($newChangeLog['ChangeLog']['metadata']['parent_id'])) {
			$newChangeLog['ChangeLog']['parent_id'] = $newChangeLog['ChangeLog']['metadata']['parent_id'];
			unset($newChangeLog['ChangeLog']['metadata']['parent_id']);
		}

		$this->__modelCache[$model->name][$model->id] = $newChangeLog;
	}

/**
 * See: http://book.cakephp.org/2.0/en/models/behaviors.html#creating-a-behavior-callback
 * Cache the relevant older version of the model item
 */
	public function beforeSave(Model $model) {
		$this->__prepare($model, true);

		$before = $model->findById($model->id);
		$before = $before[$model->name];

		foreach ($model->data[$model->name] as $field => $value) {
			if ($field != 'modified' && $before[$field] != $value) {
				$this->__modelCache[$model->name][$model->id][$field] = $before[$field];
			}
		}
		return true;
	}

/**
 * See: http://book.cakephp.org/2.0/en/models/behaviors.html#creating-a-behavior-callback
 * @throws Exception if model is incorrectly configured
 */
	public function setup(Model $model, $settings = array()) {
		$this->settings[$model->name] = $settings;

		if (!isset($model->ChangeLog) || $model->ChangeLog == null) {
			throw new Exception($model->name . " is incorrectly configured");
		} else if ($this->__getTableName($model) != null) {
			$model->ChangeLog->setSource($this->__getTableName($model));
		}
	}

	public function changeLogById(Model $model, $id = null, $modelName = null, $numberOfResults = 10, $offset = 0) {
		$id = ($id) ? $id : $model->id;
		$modelName = ($modelName) ? $modelName : $model->name;

		$conditions = array(
			'conditions' => array(
				'ChangeLog.model' => $modelName,
				'ChangeLog.model_id' => $id,
			),
			'order' => array(
				'ChangeLog.created'
			),
			'limit' => $numberOfResults,
			'offset' => $offset,
		);
		return $model->ChangeLog->find('all', $conditions);
	}

	public function changeLogByParentId(Model $model, $parentId = null, $modelName = null, $numberOfResults = 10, $offset = 0) {
		$parentId = ($parentId != null) ? $parentId : $model->id;
		$modelName = ($modelName) ? $modelName : $model->name;

		$conditions = array(
			'conditions' => array(
				'ChangeLog.model' => $modelName,
				'ChangeLog.parent_id' => $parentId,
			),
			'order' => array(
				'ChangeLog.created'
			),
			'limit' => $numberOfResults,
			'offset' => $offset,
		);
		return $model->ChangeLog->find('all', $conditions);
	}

}

class ItemState {
	const CREATED = 1;
	const MODIFIED = 2;
	const DELETED = 3;
}

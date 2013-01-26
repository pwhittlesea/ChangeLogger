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

App::uses('ChangeLoggerAppModel', 'ChangeLogger.Model');

class ChangeLog extends ChangeLoggerAppModel {

	public $useTable = false;

/**
 * See: http://book.cakephp.org/2.0/en/models/callback-methods.html
 */
	public function afterFind($results, $primary = false) {
		foreach ($results as $key => $val) {
			if (isset($val['ChangeLog']['change'])) {
				$results[$key]['ChangeLog']['change'] = json_decode($val['ChangeLog']['change'], true);
			}
			if (isset($val['ChangeLog']['metadata'])) {
				$results[$key]['ChangeLog']['metadata'] = json_decode($val['ChangeLog']['metadata'], true);
			}
		}
		return $results;
	}

/**
 * See: http://book.cakephp.org/2.0/en/models/callback-methods.html
 */
	public function beforeSave($options = array()) {
		if (isset($this->data['ChangeLog']['change'])) {
			$this->data['ChangeLog']['change'] = json_encode($this->data['ChangeLog']['change']);
		}
		if (isset($this->data['ChangeLog']['metadata'])) {
			$this->data['ChangeLog']['metadata'] = json_encode($this->data['ChangeLog']['metadata']);
		}
		return true;
	}
}

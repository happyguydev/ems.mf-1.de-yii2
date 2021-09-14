<?php
namespace app\models;

use Yii;
use yii\base\Model;

/**
 * File Upload
 */
class FileUpload extends Model {

	private $_user = false;

	public $_user_id = '';

	/**
	 * @inheritdoc
	 */

	public function sizeArray() {

		return ['50', '150', '480'];
	}

	/**
	 * Logs in a user using the provided username and password.
	 *
	 * @return boolean whether the user is logged in successfully
	 */
	public function makeDir($dir, $user_id) {
		$this->_user_id = $user_id;

		return Yii::$app->Utility->makeDir($dir);

	}

	public function resizeImage($upload_path, $file_name) {
		$getUserDetail = $this->getUser();
		$size_array = $this->sizeArray();
		foreach ($size_array as $key => $value) {
			if ($value == '150') {
				$thumb_type = 'thumb';
			} elseif ($value == '480') {

				$thumb_type = 'large';
			} else {
				$thumb_type = 'low';
			}

			Yii::$app->Utility->imgResize($upload_path, $file_name, $value, 0, $thumb_type, 100);

		}

		return true;
	}
	/**
	 * Finds user by [[username]]
	 *
	 * @return User|null
	 */
	public function getUser() {
		if ($this->_user === false) {
			$this->_user = User::find()->where(['id' => $this->_user_id])->one();
		}

		return $this->_user;
	}

}

<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%login_log}}".
 *
 * @property integer $log_id
 * @property integer $user_id
 * @property string $login_at
 * @property string $logout_at
 * @property string $login_ip
 */
class LoginLog extends \yii\db\ActiveRecord {
	/**
	 * @inheritdoc
	 */
	public static function tableName() {
		return '{{%login_log}}';
	}

	/**
	 * @inheritdoc
	 */
	public function rules() {
		return [
			//  [['user_id', 'logout_at', 'login_ip'], 'required'],
			[['user_id'], 'integer'],
			[['login_at', 'logout_at'], 'safe'],
			[['login_ip'], 'string', 'max' => 255],
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels() {
		return [
			'log_id' => 'Log ID',
			'user_id' => 'User ID',
			'login_at' => 'Login At',
			'logout_at' => 'Logout At',
			'login_ip' => 'Login Ip',
		];
	}
}

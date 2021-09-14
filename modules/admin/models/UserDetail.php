<?php

namespace app\modules\admin\models;

use Yii;

/**
 * This is the model class for table "{{%user_detail}}".
 *
 * @property int $id
 * @property int $user_id
 * @property string|null $email_signature
 * @property string|null $working_hours
 * @property string|null $allowed_leave_hours
 * @property string|null $remaining_leave_hours
 */
class UserDetail extends \yii\db\ActiveRecord {
	/**
	 * {@inheritdoc}
	 */
	public static function tableName() {
		return '{{%user_detail}}';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules() {
		return [
			[['user_id'], 'integer'],
			[['allowed_leave_hours'], 'required'],
			[['working_hours', 'allowed_leave_hours'], 'number'],
			[['email_signature'], 'string'],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels() {
		return [
			'id' => Yii::t('app', 'ID'),
			'user_id' => Yii::t('app', 'User ID'),
			'email_signature' => Yii::t('app', 'Email Signature'),
			'working_hours' => Yii::t('app', 'Daily Working Hours (Daily)'),
			'allowed_leave_hours' => Yii::t('app', 'Allowed Leave Hours (Monthly)'),
		];
	}
}

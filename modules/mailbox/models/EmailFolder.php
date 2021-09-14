<?php

namespace app\modules\mailbox\models;

use Yii;

/**
 * This is the model class for table "{{%email_folder}}".
 *
 * @property int $id
 * @property int $user_email_id
 * @property string $name
 * @property string $path
 */
class EmailFolder extends \yii\db\ActiveRecord {
	/**
	 * {@inheritdoc}
	 */
	public static function tableName() {
		return '{{%email_folder}}';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules() {
		return [
			[['user_email_id', 'name', 'path'], 'required'],
			[['user_email_id', 'status'], 'integer'],
			[['path'], 'string'],
			[['name'], 'string', 'max' => 255],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels() {
		return [
			'id' => Yii::t('app', 'ID'),
			'user_email_id' => Yii::t('app', 'Email ID'),
			'name' => Yii::t('app', 'Name'),
			'path' => Yii::t('app', 'Path'),
		];
	}
}

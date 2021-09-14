<?php

namespace app\models;

use app\models\User;
use Yii;

/**
 * This is the model class for table "{{%activity_log}}".
 *
 * @property int $id
 * @property int $user_id
 * @property string|null $action
 * @property int|null $model_id
 * @property string|null $model
 * @property string|null $data
 * @property string|null $date_time
 */
class ActivityLog extends \yii\db\ActiveRecord {
	/**
	 * {@inheritdoc}
	 */
	public static function tableName() {
		return '{{%activity_log}}';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules() {
		return [
			[['user_id', 'model_id'], 'integer'],
			[['data'], 'string'],
			[['date_time'], 'safe'],
			[['action'], 'string', 'max' => 100],
			[['model'], 'string', 'max' => 50],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels() {
		return [
			'id' => Yii::t('app', 'ID'),
			'user_id' => Yii::t('app', 'User ID'),
			'action' => Yii::t('app', 'Action'),
			'model_id' => Yii::t('app', 'Model ID'),
			'model' => Yii::t('app', 'Model'),
			'data' => Yii::t('app', 'Data'),
			'date_time' => Yii::t('app', 'Date Time'),
		];
	}

	public function getUser() {
		return $this->hasOne(User::className(), ['id' => 'user_id']);
	}
}

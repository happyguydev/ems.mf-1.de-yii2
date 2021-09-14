<?php

namespace app\models;

use app\models\User;
use Yii;

/**
 * This is the model class for table "{{%notification}}".
 *
 * @property integer $Id
 * @property integer $type
 * @property string $message
 * @property integer $user_id
 * @property string $create_date
 * @property string $modal
 * @property integer $read
 * @property integer $item_id
 */
class Notification extends \yii\db\ActiveRecord {
	/**
	 * @inheritdoc
	 */
	public static function tableName() {
		return '{{%notification}}';
	}

	/**
	 * @inheritdoc
	 */
	public function rules() {
		return [
			[['type', 'user_id', 'item_id'], 'integer'],
			[['message'], 'string'],
			[['user_id', 'modal', 'item_id'], 'required'],
			[['create_date', 'read', 'action'], 'safe'],
			[['modal'], 'string', 'max' => 255],
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels() {
		return [
			'Id' => Yii::t('app', 'ID'),
			'type' => Yii::t('app', 'Type'),
			'message' => Yii::t('app', 'Message'),
			'user_id' => Yii::t('app', 'User ID'),
			'modal' => Yii::t('app', 'Modal'),
			'read' => Yii::t('app', 'Read'),
			'item_id' => Yii::t('app', 'Item ID'),
			'TripItem' => Yii::t('app', 'Trip Item'),

			'create_date' => Yii::t('app', 'Create Date'),
		];
	}

	public function getUser() {
		return $this->hasOne(User::className(), ['id' => 'user_id']);
	}
}

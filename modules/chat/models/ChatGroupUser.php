<?php

namespace app\modules\chat\models;

use Yii;

/**
 * This is the model class for table "{{%chat_group_user}}".
 *
 * @property int $id
 * @property int $group_id
 * @property int $user_id
 * @property int $added_by
 *
 * @property ChatGroup $group
 * @property User $user
 * @property User $addedBy
 */
class ChatGroupUser extends \yii\db\ActiveRecord {
	/**
	 * {@inheritdoc}
	 */
	public static function tableName() {
		return '{{%chat_group_user}}';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules() {
		return [
			[['group_id', 'user_id', 'added_by'], 'integer'],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels() {
		return [
			'id' => Yii::t('app', 'ID'),
			'group_id' => Yii::t('app', 'Group ID'),
			'user_id' => Yii::t('app', 'User ID'),
			'added_by' => Yii::t('app', 'Added By'),
		];
	}

	/**
	 * Gets query for [[Group]].
	 *
	 * @return \yii\db\ActiveQuery
	 */
	public function getGroup() {
		return $this->hasOne(ChatGroup::className(), ['id' => 'group_id']);
	}

	/**
	 * Gets query for [[User]].
	 *
	 * @return \yii\db\ActiveQuery
	 */
	public function getUser() {
		return $this->hasOne(User::className(), ['id' => 'user_id']);
	}

	/**
	 * Gets query for [[AddedBy]].
	 *
	 * @return \yii\db\ActiveQuery
	 */
	public function getAddedBy() {
		return $this->hasOne(User::className(), ['id' => 'added_by']);
	}
}

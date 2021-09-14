<?php

namespace app\models;

use app\models\User;
use Yii;

/**
 * This is the model class for table "{{%task_assignee}}".
 *
 * @property int $id
 * @property int $task_id
 * @property int $assign_to
 * @property string|null $assign_at
 *
 * @property Task $task
 */
class TaskAssignee extends \yii\db\ActiveRecord {
	/**
	 * {@inheritdoc}
	 */
	public static function tableName() {
		return '{{%task_assignee}}';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules() {
		return [
			[['task_id', 'assign_to'], 'integer'],
			[['assign_at'], 'safe'],
			[['task_id'], 'exist', 'skipOnError' => true, 'targetClass' => Task::className(), 'targetAttribute' => ['task_id' => 'id']],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels() {
		return [
			'id' => Yii::t('app', 'ID'),
			'task_id' => Yii::t('app', 'Task ID'),
			'assign_to' => Yii::t('app', 'Assign To'),
			'assign_at' => Yii::t('app', 'Assign At'),
		];
	}

	/**
	 * Gets query for [[Task]].
	 *
	 * @return \yii\db\ActiveQuery
	 */
	public function getTask() {
		return $this->hasOne(Task::className(), ['id' => 'task_id']);
	}
	public function getUser() {
		return $this->hasOne(User::className(), ['id' => 'assign_to']);
	}
}

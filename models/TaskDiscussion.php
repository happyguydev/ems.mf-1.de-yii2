<?php

namespace app\models;

use app\models\Task;
use app\models\TaskAssignee;
use app\models\User;
use Yii;

/**
 * This is the model class for table "fa_discussion".
 *
 * @property int $id
 * @property int $task_id
 * @property int $is_file
 * @property string $comment
 * @property string $attach_file
 * @property string $status
 * @property string $create_date
 * @property int $create_by
 * @property int $update_by
 * @property string $update_date
 */
class TaskDiscussion extends \yii\db\ActiveRecord {
	/**
	 * {@inheritdoc}
	 */
	public static function tableName() {
		return '{{%task_discussion}}';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules() {
		return [
			[['task_id', 'is_file', 'create_by', 'update_by'], 'integer'],
			[['comment', 'status'], 'string'],
			[['create_date', 'update_date'], 'safe'],
			[['attach_file'], 'string', 'max' => 255],
		];
	}

	public function beforeSave($insert) {
		if (!parent::beforeSave($insert)) {
			return false;
		}

		if ($this->isNewRecord) {
			$this->getCreateBy();
			$this->getCreateDate();

		} else {
			$this->getUpdateBy();
			$this->getUpdateDate();
		}

		return true;
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels() {
		return [
			'id' => Yii::t('app', 'ID'),
			'task_id' => Yii::t('app', 'Ticket ID'),
			'is_file' => Yii::t('app', 'Is File'),
			'comment' => Yii::t('app', 'Comment'),
			'attach_file' => Yii::t('app', 'Attach File'),
			'status' => Yii::t('app', 'Status'),
			'create_date' => Yii::t('app', 'Create Date'),
			'create_by' => Yii::t('app', 'Create By'),
			'update_by' => Yii::t('app', 'Update By'),
			'update_date' => Yii::t('app', 'Update Date'),
		];
	}

	public function getCreateUser() {
		return $this->hasOne(User::className(), ['id' => 'create_by']);

	}

	public function getUpdateUser() {
		return $this->hasOne(User::className(), ['id' => 'update_by']);

	}
	public function getCreateBy() {
		$this->create_by = Yii::$app->user->identity->id;
	}

	public function getCreateDate() {
		$this->create_date = date('Y-m-d H:i:s');
	}
	public function getUpdateBy() {
		$this->update_by = Yii::$app->user->identity->id;
	}
	public function getUpdateDate() {
		$this->update_date = date('Y-m-d H:i:s');
	}

	public function assignDefault($task_id, $is_file, $msg) {
		$this->task_id = $task_id;
		$this->is_file = $is_file;
		$this->comment = $msg;

	}

	public function updateSenderMessage($text, $time, $is_file) {
		$user_id = Yii::$app->user->identity->id;
		$data = '<div class="media comment  dis' . $this->id . '" >
		<div class="media-left">
              <a href="#">
              <img alt="64x64" class="media-object avatar" src="' . self::getProfilePic() . '" style="width: 64px; height: 64px;"> </a>
            </div>
            <div class="media-body">
              <h4 class="media-heading">' . $this->createUser['first_name'] . ' ' . $this->createUser['last_name'] . '</h4>
              <p class="media-heading" style="text-align: right;margin-top:-24px;font-size: 12px;font-weight: bold">' . date('Y-m-d', strtotime($time)) . ',' . date('H:i A', strtotime($time)) . '</p>';

		if ($is_file == 0) {

			$data .= '<div style="float:left">' . $text . '</div>';

		} else if ($is_file == 1) {

			$data .= '<div style="width:100px; float:left">

          <img class="img-fluid img-thumbnail w-full rounded-md" data-action="zoom" src="' . Yii::getAlias('@web') . '/web/discussion/' . $text . '" style="width:80px"/></div>';

		} else {

			$data = '<p></p>';

		}
		$data .= '<div style="float: right;">';

		if ($this->create_by == Yii::$app->user->identity->id) {
			if ($is_file == 0) {

				$data .= '<a href="javascript:void(0)" onclick="editComment(' . $this->id . ')" class="icon md-edit p-5">
				<i class="fa fa-pencil"></i>
                      </a>';

			}

			$data .= '<a href="javascript:void(0)" onclick="getDeleteComment(' . $this->id . ')"  class="btn md-delete p-5">
			<i class="fa fa-trash"></i>
                      </a>
                    </div>';

		}

		$data .= '</div></div></div>';

		return $data;
	}

	public function getProfilePic() {

		$model = User::find()->where(['id' => $this->create_by])->one();

		return $model->thumbnailImage;

	}
	/*
		new = 1 new comment
		new = 0 updated comment
		new= 2 comment deleted
	*/
	public function notify($task_id, $new = 1) {
		$model = TaskAssignee::find()->where(['task_id' => $task_id])->all();
		foreach ($model as $key => $value) {
			$this->sendNotification($value->assign_to, $task_id, $new);
		}
		$task = Task::findOne($task_id);
		if (Yii::$app->user->identity->id != $task->created_by) {
			return $this->sendNotification($task->created_by, $task_id, $new);
		}

	}
	public function sendNotification($user_id, $task_id, $new = 1) {
		$notify = Yii::$app->notify;
		$user = Yii::$app->user->identity;
		$user_name = $user->first_name . ' ' . $user->last_name;
		if ($new == 0) {
			$text = ' has updated a comment at task.';
			$action = 'updated comment';
		} elseif ($new == 2) {
			$text = ' has deleted a comment at task.';
			$action = 'deleted comment';
		} else {
			$text = ' has added a comment at task.';
			$action = 'added comment';
		}
		$get_task = Task::find()->where(['id' => $task_id])->one();
		$notify->addNotify($user_id, 1, 'task/view', $task_id, $user_name . ' ' . $text, 'task_comment');
		$notify->addRecentActivity($action, 'Task', $task_id, $user_id, $get_task['name']);
	}

}

<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%task}}".
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property string $start_date
 * @property string $end_date
 * @property string $status
 * @property string $created_at
 * @property int $created_by
 * @property string|null $updated_at
 * @property int|null $updated_by
 *
 * @property TaskAssignee[] $taskAssignees
 * @property TaskFiles[] $taskFiles
 */
class Task extends \yii\db\ActiveRecord {

	public $assign_to;
	/**
	 * {@inheritdoc}
	 */
	public static function tableName() {
		return '{{%task}}';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules() {
		return [
			[['name', 'description', 'start_date', 'end_date', 'status'], 'required'],
			[['description', 'status'], 'string'],
			[['start_date', 'end_date', 'created_at', 'updated_at', 'relation_id', 'deleted_by', 'deleted_at', 'trash', 'assign_to'], 'safe'],
			[['created_by', 'updated_by'], 'integer'],
			[['name'], 'string', 'max' => 255],
		];
	}

	public function behaviors() {
		return [
			[
				'class' => TimestampBehavior::className(),
				'createdAtAttribute' => 'created_at',
				'updatedAtAttribute' => 'updated_at',
				'value' => date('Y-m-d H:i:s'),
			],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels() {
		return [
			'id' => Yii::t('app', 'ID'),
			'name' => Yii::t('app', 'Name'),
			'description' => Yii::t('app', 'Description'),
			'start_date' => Yii::t('app', 'Start Date'),
			'end_date' => Yii::t('app', 'End Date'),
			'status' => Yii::t('app', 'Status'),
			'assign_to' => Yii::t('app', 'Assign To'),
			'created_at' => Yii::t('app', 'Created At'),
			'created_by' => Yii::t('app', 'Created By'),
			'updated_at' => Yii::t('app', 'Updated At'),
			'updated_by' => Yii::t('app', 'Updated By'),
			'deleted_by' => Yii::t('app', 'Deleted By'),
			'deleted_at' => Yii::t('app', 'Deleted At'),
			'trash' => Yii::t('app', 'Trash'),
		];
	}

	/**
	 * Gets query for [[TaskAssignees]].
	 *
	 * @return \yii\db\ActiveQuery
	 */
	public function getTaskAssignees() {
		return $this->hasMany(TaskAssignee::className(), ['task_id' => 'id']);
	}

	/**
	 * Gets query for [[TaskFiles]].
	 *
	 * @return \yii\db\ActiveQuery
	 */
	public function getAttachments() {
		return $this->hasMany(TaskAttachment::className(), ['relation_id' => 'relation_id']);
	}

	public function getStatus() {
		if ($this->status == 'upcoming') {
			$sts = "Upcoming";
		} elseif ($this->status == 'in-progress') {
			$sts = "In Progress";
		} else {
			$sts = "Completed";
		}
		return $sts;
	}

	public function getStatusColor() {
		if ($this->status == 'upcoming') {
			$sts = "theme-1";
		} elseif ($this->status == 'in-progress') {
			$sts = "theme-12";
		} else {
			$sts = "theme-9";
		}
		return $sts;
	}

	public function getAssignees() {
		$str = '';
		$model = TaskAssignee::find()->where(['task_id' => $this->id])->all();
		foreach ($model as $key => $value) {
			$user = User::find()->where(['id' => $value['assign_to']])->one();
			if ($user['profile_picture'] != '') {

				$image = Yii::getAlias('@web') . '/web/profile/' . $user['id'] . '/' . $user['profile_picture'];

			} else {

				$image = Yii::getAlias('@web') . '/web/profile/default.jpg';

			}
			$str .= '<div class="w-10 h-10 image-fit zoom-in">
            <a href="' . Yii::getAlias('@web') . '/user/profile?id=' . $user['id'] . '" target="_blank" data-pjax="0">
                                                <img alt="' . $user['first_name'] . ' ' . $user['last_name'] . '" class="tooltip rounded-full" title="' . $user['first_name'] . ' ' . $user['last_name'] . '" src="' . $image . '"></a>
                                            </div>';
		}

		return $str;
	}

	public function getCreatedBy() {
		$user = User::find()->where(['id' => $this->created_by])->one();

		return $user->first_name . ' ' . $user->last_name;
	}

	public function getUpdatedBy() {
		$user = User::find()->where(['id' => $this->updated_by])->one();
		if ($user) {

			return $user->first_name . ' ' . $user->last_name;
		}
	}

	public function getDeletedBy() {
		$user = User::find()->where(['id' => $this->deleted_by])->one();
		if ($user) {
			return $user->first_name . ' ' . $user->last_name;
		}
	}

}

<?php

namespace app\modules\admin\models;

use app\models\User;
use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%notification}}".
 *
 * @property int $id
 * @property string|null $notification_name
 * @property string|null $send_to
 * @property string|null $content
 * @property string|null $sent_time
 * @property int $status
 * @property string|null $created_at
 * @property int|null $created_by
 */
class Notification extends \yii\db\ActiveRecord {
	/**
	 * {@inheritdoc}
	 */
	public static function tableName() {
		return '{{%notification}}';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules() {
		return [
			[['notification_name', 'status', 'send_to', 'content'], 'required'],
			[['content'], 'string'],
			[['sent_time', 'created_at', 'send_to'], 'safe'],
			[['status', 'created_by'], 'integer'],
			[['notification_name'], 'string', 'max' => 255],
		];
	}

	public function behaviors() {
		return [
			[
				'class' => TimestampBehavior::className(),
				'createdAtAttribute' => 'created_at',
				'updatedAtAttribute' => false,
				'value' => date('Y-m-d H:i:s'),
			],
		];
	}

	public function afterFind() {

		$action = Yii::$app->controller->action->id;

		if ($action == 'index' || $action == 'view') {
			$this->status = Yii::$app->Utility->getStatus($this->status);
			$this->created_by = $this->createdBy['name'];

		}

	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels() {
		return [
			'id' => 'ID',
			'notification_name' => 'Notification Name',
			'send_to' => 'Send To',
			'content' => 'Content',
			'sent_time' => 'Sent Time',
			'status' => 'Status',
			'created_at' => 'Created At',
			'created_by' => 'Created By',
		];
	}

	public function getItemshtml($value = '') {
		$explode_data = explode(',', $this->send_to);
		$html = "<ul class='item_list'>";
		foreach ($explode_data as $key => $value) {
			$user = User::find()->where(['id' => $value])->one();

			if ($key > 1) {
				$hcls = "hide_it";
			} else {
				$hcls = "show_it";
			}
			$html .= "<li class='list-items " . $hcls . "'><a href='" . Yii::getAlias('@web') . '/admin/user/view?id=' . $user['id'] . "'>" . $user['phone'] . "</a></li>";

		}
		if (count($explode_data) > 2) {
			$html .= "<li class='list-items showall'>Show More</li>";
		}

		$html .= "</ul>";
		return $html;
	}

	public function getCreatedBy() {
		return $this->hasOne(User::className(), ['id' => 'created_by']);
	}

	public function getStatus() {
		$status = '';
		if ($this->status == '0') {
			$status = '<label class="badge badge-danger">Disable</label>';
		} elseif ($this->status == '1') {
			$status = '<label class="badge badge-success">Sent</label>';
		} else {
			$status = '<label class="badge badge-info">In Queue</label>';
		}

		return $status;
	}
}

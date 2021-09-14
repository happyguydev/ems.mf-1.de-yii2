<?php

namespace app\models;

use app\models\Customer;
use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%project}}".
 *
 * @property int $id
 * @property int $customer_id
 * @property string $name
 * @property string|null $description
 * @property string $start_date
 * @property string $end_date
 * @property float $budget
 * @property string $billing_type
 * @property float $estimated_hours
 * @property string $created_at
 * @property int $created_by
 * @property string|null $updated_at
 * @property int|null $updated_by
 * @property string $status
 */
class Project extends \yii\db\ActiveRecord {
	public $team;
	/**
	 * {@inheritdoc}
	 */
	public static function tableName() {
		return '{{%project}}';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules() {
		return [
			[['customer_id', 'created_by', 'updated_by'], 'integer'],
			[['name', 'description', 'status', 'start_date', 'end_date', 'billing_type',  'customer_id'], 'required'],
			[['description', 'status', 'payment_description'], 'string'],
			[['start_date', 'end_date', 'created_at', 'updated_at', 'budget', 'deleted_by', 'deleted_at', 'trash', 'team'], 'safe'],
			[['estimated_hours'], 'number'],
			[['name'], 'string', 'max' => 255],
			[['billing_type'], 'string', 'max' => 30],
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
			'customer_id' => Yii::t('app', 'Customer'),
			'name' => Yii::t('app', 'Name'),
			'description' => Yii::t('app', 'Description'),
			'start_date' => Yii::t('app', 'Start Date'),
			'end_date' => Yii::t('app', 'End Date'),
			'budget' => Yii::t('app', 'Budget'),
			'billing_type' => Yii::t('app', 'Billing Type'),
			'payment_description' => Yii::t('app', 'Payment Description'),
			'estimated_hours' => Yii::t('app', 'Estimated Hours'),
			'created_at' => Yii::t('app', 'Created At'),
			'created_by' => Yii::t('app', 'Created By'),
			'updated_at' => Yii::t('app', 'Updated At'),
			'updated_by' => Yii::t('app', 'Updated By'),
			'deleted_by' => Yii::t('app', 'Deleted By'),
			'deleted_at' => Yii::t('app', 'Deleted At'),
			'trash' => Yii::t('app', 'Trash'),
			'status' => Yii::t('app', 'Status'),
		];
	}

	public function getStatus() {
		if ($this->status == 'in-progress') {
			$sts = Yii::t('app', 'In Progress');
		} elseif ($this->status == 'delay') {
			$sts = Yii::t('app', 'Delay');
		} elseif ($this->status == 'completed') {
			$sts = Yii::t('app', 'Completed');
		} else {
			$sts = Yii::t('app', 'Pending');
		}
		return $sts;
	}

	public function getStatusColor() {
		if ($this->status == 'in-progress') {
			$sts = 'theme-12';
		} elseif ($this->status == 'delay') {
			$sts = 'theme-6';
		} elseif ($this->status == 'completed') {
			$sts = 'theme-9';
		} else {
			$sts = 'theme-1';
		}
		return $sts;
	}

	public function getTeamMembers() {
		$str = '';
		$model = ProjectAssignee::find()->where(['project_id' => $this->id])->all();
		foreach ($model as $key => $value) {
			$user = User::find()->where(['id' => $value['user_id']])->one();
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

	public function getCustomer() {
		$customer = Customer::find()->where(['id' => $this->customer_id])->one();

		return $customer->first_name . ' ' . $customer->last_name;
	}

	public function getProgress() {
		$date1 = strtotime(date('Y-m-d H:i:s', strtotime($this->start_date)));
		$date2 = strtotime(date('Y-m-d H:i:s', strtotime($this->end_date)));
		$today = time();

		if ($date2 < $today) {
			$percentageRounded = 100;
		} else {

			$dateDiff = $date2 - $date1;
			$dateDiffForToday = $today - $date1;

			if ($dateDiffForToday > 0 && $dateDiff > 0) {
				$percentage = $dateDiffForToday / $dateDiff * 100;
			} else {
				$percentage = 0;
			}

			$percentageRounded = round($percentage);

			if ($percentageRounded < 0) {
				$percentageRounded = 0;
			}
		}

		return $percentageRounded;
	}
}

<?php

namespace app\models;

use app\modules\admin\models\UserDetail;
use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%leave}}".
 *
 * @property int $id
 * @property string $leave_type
 * @property int $remaining_leaves
 * @property string $from_date
 * @property string $to_date
 * @property int $is_full_day
 * @property string|null $half_day
 * @property int|null $no_of_days
 * @property int $status
 * @property string|null $reason
 * @property string|null $created_location
 * @property string $created_at
 * @property int $created_by
 */
class Leave extends \yii\db\ActiveRecord {
	/**
	 * {@inheritdoc}
	 */
	public static function tableName() {
		return '{{%leave}}';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules() {
		return [
			[['leave_type', 'from_date', 'to_date', 'reason'], 'required'],
			[['remaining_leaves', 'is_full_day', 'status', 'created_by'], 'integer'],
			[['from_date', 'to_date', 'created_at', 'first_month_hour', 'second_month_hour', 'created_location', 'relation_id'], 'safe'],
			[['reason'], 'string'],
			[['leave_type'], 'string', 'max' => 150],
			[['half_day', 'no_of_days'], 'safe'],
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

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels() {
		return [
			'id' => Yii::t('app', 'ID'),
			'leave_type' => Yii::t('app', 'Leave Type'),
			'remaining_leaves' => Yii::t('app', 'Remaining Leaves'),
			'from_date' => Yii::t('app', 'From Date'),
			'to_date' => Yii::t('app', 'To Date'),
			'is_full_day' => Yii::t('app', 'Is Full Day'),
			'half_day' => Yii::t('app', 'Half Day'),
			'no_of_days' => Yii::t('app', 'No Of Days'),
			'status' => Yii::t('app', 'Status'),
			'reason' => Yii::t('app', 'Reason'),
			'created_location' => Yii::t('app', 'Created Location'),
			'created_at' => Yii::t('app', 'Created At'),
			'created_by' => Yii::t('app', 'Created By'),
		];
	}

	public function getStatus() {
		if ($this->status == '1') {
			$sts = Yii::t('app', "Approved");
		} elseif ($this->status == '0') {
			$sts = Yii::t('app', "Disapproved");
		} else {
			$sts = Yii::t('app', "Pending");
		}
		return $sts;
	}

	public function getStatusColor() {
		if ($this->status == '1') {
			$sts = "theme-9";
		} elseif ($this->status == '0') {
			$sts = "theme-6";
		} else {
			$sts = "theme-1";
		}
		return $sts;
	}

	public function getCreatedBy() {
		$user = User::find()->where(['id' => $this->created_by])->one();

		return $user->first_name . ' ' . $user->last_name;
	}

	public function getUser() {
		return $this->hasOne(User::className(), ['id' => 'created_by']);
	}
	public function updateRemainingLeaves() {
		$user_detail = UserDetail::find()->where(['user_id' => $this->created_by])->one();
		if ($user_detail) {
			$today = date('d');
			// reset remaining leave at first day of month
			if ($today == 1) {
				$user_detail->remaining_leave_hours = $user_detail->allowed_leave_hours;
				$user_detail->save();
			}

			$end_date = strtotime($this->to_date); // or your date as well
			$start_date = strtotime($this->from_date);
			$datediff = $end_date - $start_date;

			$no_of_days = round($datediff / (60 * 60 * 24)) + 1;

			$total_hours = $no_of_days * $user_detail->working_hours;
			if (!$this->is_full_day) {
				$total_hours = $total_hours / 2;
			}
			$remaining_leaves = $user_detail->remaining_leave_hours - $total_hours;

			$user_detail->remaining_leave_hours = $remaining_leaves;

			if ($remaining_leaves >= 0) {
				return $user_detail->save();
			} else {
				return false;
			}

		}
	}

	public function getRemainingLeaves($user_id) {
		$user_detail = UserDetail::find()->where(['user_id' => $user_id])->one();
		$remaining_leaves = 0;
		if ($user_detail) {
			$remaining_leaves = $user_detail->remaining_leave_hours;
		}
		return $remaining_leaves;
	}

	/*public function getDatesBetweenTwoDate($format = 'd-m-Y') {
			$dates = [];
			$current = strtotime($this->from_date);
			$date2 = strtotime($this->to_date);
			$stepVal = '+1 day';
			while ($current <= $date2) {
				$dates[] = date($format, $current);
				$current = strtotime($stepVal, $current);
			}

			return $dates;
		}
	*/
}

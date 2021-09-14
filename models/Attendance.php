<?php

namespace app\models;
use app\models\LoginLog;
use app\models\User;
use app\modules\admin\models\UserDetail;
use Yii;

/**
 * This is the model class for table "{{%attendance}}".
 *
 * @property int $id
 * @property int $user_id
 * @property string|null $date
 * @property string|null $check_in
 * @property string|null $check_out
 * @property string|null $check_in_location
 * @property string|null $check_out_location
 */
class Attendance extends \yii\db\ActiveRecord {
	/**
	 * {@inheritdoc}
	 */
	public static function tableName() {
		return '{{%attendance}}';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules() {
		return [
			[['user_id'], 'integer'],
			[['date', 'check_in', 'check_out', 'check_in_ip', 'check_out_ip', 'work_duration', 'start_date_time', 'status', 'stop_date_time'], 'safe'],
			[['check_in_location', 'check_out_location'], 'string', 'max' => 255],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels() {
		return [
			'id' => Yii::t('app', 'ID'),
			'user_id' => Yii::t('app', 'User ID'),
			'date' => Yii::t('app', 'Date'),
			'check_in' => Yii::t('app', 'Check In'),
			'check_out' => Yii::t('app', 'Check Out'),
			'check_in_location' => Yii::t('app', 'Check In Location'),
			'check_out_location' => Yii::t('app', 'Check Out Location'),
			'work_duration' => Yii::t('app', 'Work Duration'),
		];
	}

	public function getEmployeeName() {
		return $this->hasOne(User::className(), ['id' => 'user_id']);
	}

	public function autoLogout() {

		return true;
		if (!Yii::$app->user->isGuest) {
			$lid = Yii::$app->user->identity->id;
		} else {
			$lid = 0;
		}
		$current_date_time = date('Y-m-d H:i:s');
		$attendance = Attendance::find()->where(['check_out' => NULL])->andWhere(['<', 'check_in', $current_date_time])->orderBy(['check_in' => SORT_ASC])->all();
		if (count($attendance) > 0) {
			foreach ($attendance as $key => $value) {
				$user = UserDetail::find()->where(['user_id' => $value['user_id']])->one();

				$working_hour = ($user['working_hours'] != '') ? $user['working_hours'] : 8;
				$total_working_hour = $working_hour + 1;
				$minutes = $total_working_hour * 60;
				$time_differnce = strtotime($value['check_in']) - strtotime($current_date_time);
				$time_differnce_in_mins = round(abs($time_differnce) / 60, 2);

				$check_out_time = date('Y-m-d H:i:s', strtotime('+' . $minutes . 'minutes', strtotime($value['check_in'])));

				if ($time_differnce_in_mins >= $minutes) {
					$value->check_out = $check_out_time;
					$value->check_out_ip = $value['check_in_ip'];
					$value->check_out_location = $value['check_in_location'];
					$value->save();

					$login_log = LoginLog::find()->where(['user_id' => $value['user_id']])->one();
					if ($login_log) {
						$login_log->logout_at = $value['check_out'];
						$login_log->save();

					}

					if ($lid == $value['user_id']) {
						Yii::$app->user->logout();
						Yii::$app->controller->redirect(['site/login']);
					}
				}

			}
		}

		return true;

	}
}

<?php
namespace app\models;

use app\models\LoginLog;
use app\modules\admin\models\UserDetail;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $auth_key
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 */
class User extends ActiveRecord implements IdentityInterface {
	const STATUS_DELETED = 0;
	const STATUS_ACTIVE = 1;
	const STATUS_TRASH = -1;
	public $oldpassword;
	public $password;
	public $repeatpassword;
	public $file;

	/**
	 * @inheritdoc
	 */
	public static function tableName() {
		return '{{%user}}';
	}

	/**
	 * @inheritdoc
	 */
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
	 * @inheritdoc
	 */
	public function rules() {
		return [
			['status', 'default', 'value' => self::STATUS_ACTIVE],
			['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED, self::STATUS_TRASH]],

			['username', 'filter', 'filter' => 'trim'],
			['username', 'unique', 'targetClass' => 'app\models\User', 'message' => 'This username has already been taken.'],
			['username', 'string', 'min' => 2, 'max' => 255],

			['email', 'filter', 'filter' => 'trim'],
			['email', 'email'],
			['email', 'unique', 'targetClass' => 'app\models\User', 'message' => 'This email address has already been taken.', 'on' => 'register'],
			['phone', 'unique', 'targetClass' => 'app\models\User', 'message' => 'This phone number has already been taken.'],
			['status', 'required', 'on' => 'statusUpdate'],

			[['phone', 'email', 'first_name', 'last_name'], 'required', 'on' => 'register'],
			['password', 'string', 'min' => 6],
			['phone', 'safe'],
			//['phone', 'match', 'pattern' => '/^[0-9]+$/', 'message' => 'Phone number must contain digits only.'],
			//['phone', 'string', 'message' => 'Phone number must contains 10 digits only.', 'min' => 10, 'max' => 10],
			/* ['password','match', 'pattern' => '/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])[0-9a-zA-Z]{8,25}$/','message' => 'password must contains atleast 1 uppercase, 1 lowercase , 1 digit',],*/
			['repeatpassword', 'compare', 'compareAttribute' => 'password'],
			[['user_role', 'first_name', 'last_name', 'reset_key', 'key_date', 'profile_picture'], 'safe'],
			[['verified'], 'integer'],

			[['oldpassword', 'password', 'repeatpassword'], 'required', 'on' => 'changeP'],
			['file', 'file'],

		];
	}

	/**
	 * @inheritdoc
	 */
	public static function findIdentity($id) {
		return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
	}

	/**
	 * @inheritdoc
	 */
	public static function findIdentityByAccessToken($token, $type = null) {
		return static::findOne(['auth_key' => $token]);
	}

	/**
	 * Finds user by username
	 *
	 * @param string $username
	 * @return static|null
	 */
	public static function findByUsername($username) {
		return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
	}

	/**
	 * Finds user by password reset token
	 *
	 * @param string $token password reset token
	 * @return static|null
	 */
	public static function findByPasswordResetToken($token) {
		if (!static::isPasswordResetTokenValid($token)) {
			return null;
		}

		return static::findOne([
			'password_reset_token' => $token,
			'status' => self::STATUS_ACTIVE,
		]);
	}

	/**
	 * Finds out if password reset token is valid
	 *
	 * @param string $token password reset token
	 * @return boolean
	 */
	public static function isPasswordResetTokenValid($token) {
		if (empty($token)) {
			return false;
		}
		$expire = Yii::$app->params['user.passwordResetTokenExpire'];
		$parts = explode('_', $token);
		$timestamp = (int) end($parts);
		return $timestamp + $expire >= time();
	}

	/**
	 * @inheritdoc
	 */
	public function getId() {
		return $this->getPrimaryKey();
	}

	/**
	 * @inheritdoc
	 */
	public function getAuthKey() {
		return $this->auth_key;
	}

	/**
	 * @inheritdoc
	 */
	public function validateAuthKey($authKey) {
		return $this->getAuthKey() === $authKey;
	}

	/**
	 * Validates password
	 *
	 * @param string $password password to validate
	 * @return boolean if password provided is valid for current user
	 */
	public function validatePassword($password) {
		return Yii::$app->security->validatePassword($password, $this->password_hash);
	}

	/**
	 * Generates password hash from password and sets it to the model
	 *
	 * @param string $password
	 */
	public function setPassword($password) {
		$this->password_hash = Yii::$app->security->generatePasswordHash($password);
	}

	/**
	 * Generates "remember me" authentication key
	 */
	public function generateAuthKey() {
		$this->auth_key = Yii::$app->security->generateRandomString();
	}

	/**
	 * Generates new password reset token
	 */
	public function generatePasswordResetToken() {
		$this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
	}

	/**
	 * Removes password reset token
	 */
	public function removePasswordResetToken() {
		$this->password_reset_token = null;
	}
	public function getStatus($data) {
		if ($data->status == 1) {
			return 'Enabled';
		}

		if ($data->status == 0) {
			return 'Disabled';
		}

	}

	public function getStatusLabel($data) {
		if ($data->status == 1) {
			return 'success';
		}

		if ($data->status == 0) {
			return 'danger';
		}

	}

	/* public function getLastLogin($data)
		    {

					$userId		=		$data->id;

					$log   	=	LoginLog::find()->where(['UserId'=>$userId])->orderBy(['LoginAt'=>SORT_DESC])->all();
					if(count($log)>0){
					foreach($log as $l){

						$last =  $l->LoginAt;

						return $last;
						}
					}else{
						return 'Never';
					}

			}

	*/
	public function getLast($data) {

		$userId = $data->id;

		$log = LoginLog::find()->where(['user_id' => $userId])->orderBy(['log_id' => SORT_DESC])->all();
		if (count($log) > 0) {
			foreach ($log as $l) {

				$last = $l->login_at;

				$etime = strtotime(date('Y-m-d H:i:s')) - strtotime($last);

				if ($etime < 1) {
					return $etime; //'0 seconds';
				}

				$a = array(365 * 24 * 60 * 60 => 'year',
					30 * 24 * 60 * 60 => 'month',
					24 * 60 * 60 => 'day',
					60 * 60 => 'hour',
					60 => 'minute',
					1 => 'second',
				);
				$a_plural = array('year' => 'years',
					'month' => 'months',
					'day' => 'days',
					'hour' => 'hours',
					'minute' => 'minutes',
					'second' => 'seconds',
				);

				foreach ($a as $secs => $str) {
					$d = $etime / $secs;
					if ($d >= 1) {
						$r = round($d);
						return $r . ' ' . ($r > 1 ? $a_plural[$str] : $str) . ' ago';
					}
				}

			}
		} else {

			return 'Never';
		}

	}

	public function getThumbnailImage() {
		if ($this->profile_picture != '') {
			$uploadPath = Yii::getAlias('@web') . '/web/profile/' . $this->id . '/' . $this->profile_picture;

		} else {
			$uploadPath = Yii::getAlias('@web') . '/web/profile/default.jpg';

		}
		/*echo $uploadPath;

		print_r(glob($uploadPath . '/*'));*/
		return $uploadPath;
	}

	public function getName() {
		return $this->first_name . ' ' . $this->last_name;
	}
	public function getUserDetail() {
		return $this->hasOne(UserDetail::className(), ['user_id' => 'id']);
	}

	/*public function getTotalLeavesInMonth($month, $year) {
		$start_date = date($year . '-' . $month . '-' . '01');
		$end_date = date($year . '-' . $month . '-' . 't');
		$cond = " (`tbl_leave`.`from_date` BETWEEN '$start_date' AND '$end_date') OR (`tbl_leave`.`to_date` BETWEEN '$start_date' AND '$end_date')";
		//	print_r($cond);
		$leave = Leave::find()->where(['created_by' => $this->id])->andWhere($cond)->count();

		return $leave;
	}*/

	public function getTotalLeavesInMonth1($month, $year) {
		$leave = Leave::find()->where(['created_by' => $this->id])->andWhere(['status' => 1])->all();

		$working_hour = [];

		foreach ($leave as $key => $value) {
			$user = User::find()->where(['id' => $value['created_by']])->one();
			$get_dates = $value->getDatesBetweenTwoDate();

			foreach ($get_dates as $key1 => $value1) {

				$explode_month = explode('-', $value1);
				if ($explode_month[1] == $month) {

					$working_hour[] = $value1;

				}
			}
		}

		//print_r($working_hour);

		return count($working_hour);
	}

	public function getTotalLeavesInMonth($month, $year) {
		$leave = Leave::find()->where(['created_by' => $this->id])->andWhere(['status' => 1])->all();

		$get_dates = [];
		$format = 'd-m-Y';

		foreach ($leave as $key => $value) {
			$user = User::find()->where(['id' => $value['created_by']])->one();
			//$get_dates[] = $value->getDatesBetweenTwoDate();

			$current = strtotime($value->from_date);
			$date2 = strtotime($value->to_date);
			$stepVal = '+1 day';
			while ($current <= $date2) {
				$get_dates[] = date($format, $current) . '-' . $value->id;
				$current = strtotime($stepVal, $current);
			}
		}

		$working_hour = $this->getWorkingHours($get_dates, $month, $year);

		return $working_hour;

	}

	public function getWorkingHours($date_arrays, $month, $year) {

		$working_hours = [];

		foreach (array_unique($date_arrays) as $key1 => $value1) {

			$explode_month = explode('-', $value1);
			if ($explode_month[1] == $month && $explode_month[2] == $year) {

				$leave = Leave::find()->where(['id' => $explode_month[3]])->one();
				$user1 = User::find()->where(['id' => $leave['created_by']])->one();

				if ($leave->is_full_day == 0) {
					$working_hours[] = $user1->userDetail['working_hours'] / 2;
				} else {

					$working_hours[] = $user1->userDetail['working_hours'];

				}

			}

		}

		return array_sum($working_hours);

	}

	/* this function return total off days */

	public function getTotalOffDays($month, $year) {
		$leave = Leave::find()->where(['created_by' => $this->id])->andWhere(['status' => 1])->all();

		$get_total_day_off = 0;
		$format = 'd-m-Y';

		foreach ($leave as $key => $value) {
			$user = User::find()->where(['id' => $value['created_by']])->one();
			//$get_dates[] = $value->getDatesBetweenTwoDate();

			$current = strtotime($value->from_date);
			$date2 = strtotime($value->to_date);
			$stepVal = '+1 day';
			while ($current <= $date2) {
				if ($value['is_full_day'] == 0) {
					$get_total_day_off = $get_total_day_off + 0.5;
				} else {
					$get_total_day_off = $get_total_day_off + 1;

				}

				$current = strtotime($stepVal, $current);
			}
		}

		return $get_total_day_off;

	}

}

<?php
namespace app\models;

use app\models\AuthAssignment;
use app\models\User;
use Yii;
use yii\base\Model;
use yii\behaviors\TimestampBehavior;
use yii\web\UploadedFile;

/**
 * Signup form
 */
class AddUser extends Model {
	public $username;
	public $email;
	public $password;
	public $repeatpassword;
	public $user_role;
	public $first_name;
	public $last_name;
	public $phone;
	public $created_at;
	public $updated_at;
	public $status;
	public $phone_verified;
	public $verified;
	public $file;
	public $profile_picture;

	/**
	 * @inheritdoc
	 */
	public function rules() {
		return [
			['username', 'filter', 'filter' => 'trim'],
			['username', 'unique', 'targetClass' => 'app\models\User', 'message' => 'This username has already been taken.'],
			['username', 'string', 'min' => 2, 'max' => 255],

			// ['email', 'filter', 'filter' => 'trim'],
			['email', 'email'],
			['email', 'unique', 'targetClass' => 'app\models\User', 'message' => 'This email address has already been taken.'],
			['phone', 'unique', 'targetClass' => 'app\models\User', 'message' => 'This phone number has already been taken.'],

			[['password', 'repeatpassword', 'phone', 'email', 'first_name', 'last_name'], 'required', 'on' => 'register'],
			['status', 'required', 'on' => 'statusUpdate'],
			[['oldpassword', 'password', 'repeatpassword'], 'required', 'on' => 'changeP'],
			['password', 'string', 'min' => 6],
			['phone', 'safe'],
			//['phone', 'match', 'pattern' => '/^[1][34578][0-9]{9}$/', 'message' => 'Phone number must contain digits only.'],
			//['phone', 'string', 'message' => 'Phone number must contains atleast 10 digits', 'min' => 10, 'max' => 10],
			/* ['password','match', 'pattern' => '/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])[0-9a-zA-Z]{8,25}$/','message' => 'password must contains atleast 1 uppercase, 1 lowercase , 1 digit',],*/
			['repeatpassword', 'compare', 'compareAttribute' => 'password'],
			[['user_role', 'first_name', 'last_name', 'status', 'profile_picture'], 'safe'],
			[['verified'], 'integer'],
			['file', 'file'],

		];
	}

	public function attributeLabels() {
		return [

			'username' => Yii::t('app', 'Username'),
			'repeatpassword' => Yii::t('app', 'Repeat Password'),
			'file' => Yii::t('app', 'Upload File'),
			'first_name' => Yii::t('app', 'First Name'),
			'last_name' => Yii::t('app', 'Last Name'),
			'email' => Yii::t('app', 'Email'),
			'status' => Yii::t('app', 'Status'),

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
	 * Signs user up.
	 *
	 * @return User|null the saved model or null if saving fails
	 */
	public function adduser($type = "backend") {

		if ($this->validate()) {
			$user = new User();
			if ($type == 'backend') {
				$user->scenario = 'register';
			}
			$user->first_name = $this->first_name;
			$user->last_name = $this->last_name;
			$user->username = $this->email;
			$user->email = ($this->email != '') ? $this->email : NULL;
			$user->phone = $this->phone;
			$user->verified = $this->verified;
			$user->created_at = $this->created_at;
			$user->updated_at = $this->updated_at;
			$user->user_role = $this->user_role;
			$user->status = $this->status;
			$user->setPassword($this->password);
			$user->generateAuthKey();

			$this->file = UploadedFile::getInstance($this, 'file');

			$lastUser = User::find()->orderBy(['id' => SORT_DESC])->one();

			if ($this->file != '') {
				$user->profile_picture = 'user-' . ($lastUser['id'] + 1) . '.' . $this->file->extension;
			}
			if ($user->save()) {

				if ($this->file != '') {

					$uploadPath = 'web/profile/' . $user->id;

					if (!file_exists($uploadPath)) {
						mkdir($uploadPath);
					}

					$this->file->saveAs($uploadPath . '/' . $user->profile_picture);

				}
				$assignment = new AuthAssignment();
				$assignment->user_id = $user->id;
				if ($this->user_role == '') {
					$role = 'user';
				} else {

					$role = $this->user_role;
				}
				$assignment->item_name = $role;
				$assignment->save();

				return $user;
			}
		}

		return null;
	}

}

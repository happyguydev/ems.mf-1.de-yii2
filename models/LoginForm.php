<?php
namespace app\models;

use Yii;
use yii\base\Model;

/**
 * Login form
 */
class LoginForm extends Model {
	public $username;
	public $password;

	public $rememberMe = true;

	private $_user = false;

	/**
	 * @inheritdoc
	 */
	public function rules() {
		return [
			// username and password are both required
			[['username', 'password'], 'required', 'on' => 'login'],
			// rememberMe must be a boolean value
			['rememberMe', 'boolean'],
			//[['password','repeatpassword','oldpassword','fullname'],'string'],
			//['repeatpassword','compare','compareAttribute'=>'password'],
			// password is validated by validatePassword()
			['password', 'validatePassword'],
			// [['password','repeatpassword','oldpassword'],'required','on'=>'changePassword'],

		];
	}

	/**
	 * Validates the password.
	 * This method serves as the inline validation for password.
	 *
	 * @param string $attribute the attribute currently being validated
	 * @param array $params the additional name-value pairs given in the rule
	 */
	public function validatePassword($attribute, $params) {
		if (!$this->hasErrors()) {
			$user = $this->getUser();
			if (!$user || !$user->validatePassword($this->password)) {
				$this->addError($attribute, 'Incorrect username or password.');
			}
		}
	}

	/**
	 * Logs in a user using the provided username and password.
	 *
	 * @return boolean whether the user is logged in successfully
	 */
	public function login() {

		if ($this->validate()) {
			return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
		} else {
			return false;
		}
	}
	public function apiLogin() {

		if ($this->validate()) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Finds user by [[username]]
	 *
	 * @return User|null
	 */
	public function getUser() {

		if ($this->_user === false) {
			$this->_user = User::find()->where(['email' => $this->username])->andWhere(['status' => 1])->one();
		}

		return $this->_user;
	}

}

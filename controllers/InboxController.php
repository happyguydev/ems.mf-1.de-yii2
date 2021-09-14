<?php

namespace app\controllers;

use app\modules\mailbox\models\UserEmail;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

class InboxController extends \yii\web\Controller {
	public $layout = '@app/themes/admin/main';
	public function behaviors() {
		return [
			'verbs' => [
				'class' => VerbFilter::className(),
				'actions' => [
					'delete' => ['POST'],
				],
			],
			'access' => [
				'class' => AccessControl::className(),
				'except' => [''],
				'rules' => [
					[
						'allow' => true,
						'roles' => ['@'],
					],
				],
			],
		];
	}
	public function actionIndex() {
		$session = Yii::$app->session;
		//if (!isset($session['UserEmail'])) {
		$user_email = UserEmail::find()->where(['user_id' => Yii::$app->user->identity->id])->andWhere(['status' => 1])->one();
		if ($user_email) {
			if (!isset($session['UserEmail'])) {
				$session['UserEmail'] = $user_email->id;

			}
		} else {
			return $this->redirect(['mailbox/user-email']);
		}
		$email = '';
		$password = '';
		if (isset($session['UserEmail'])) {
			$user_email = UserEmail::find()->where(['id' => $session['UserEmail']])->one();
			if ($user_email) {
				$email = $user_email->email;
				$password = urlencode($user_email->password);
			}
		}
		//}
		return $this->render('index', ['email' => $email, 'password' => $password]);
	}
	public function actionSetUserEmail($id) {
		error_reporting(0);
		$model = UserEmail::findOne($id);
		$session = Yii::$app->session;
		$session['UserEmail'] = $id;

		if (isset($_COOKIE['rlsession'])) {
			unset($_COOKIE['rlsession']);
			setcookie('rlsession', null, -1, '/');

		}
		return $this->redirect(['index']);
		//$this->actionGetMails(1);
	}
}

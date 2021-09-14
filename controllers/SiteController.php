<?php

namespace app\controllers;

use app\models\Attendance;
use app\models\ContactForm;
use app\models\LoginForm;
use app\models\LoginLog;
use app\models\Notification;
use app\models\User;
use app\modules\chat\models\Chat;
use app\modules\mailbox\models\Mailbox;
use app\modules\mailbox\models\UserEmail;
use Yii;
use yii\data\Pagination;
use yii\filters\AccessControl;
use yii\web\Controller;

class SiteController extends Controller {
	//public $layout = '@app/themes/frontend/main/main';
	public $enableCsrfValidation = false;

	public function behaviors() {
		return [
			'access' => [
				'class' => AccessControl::className(),
				'only' => ['logout', 'change-password'],
				'rules' => [
					[
						'actions' => ['logout', 'change-password'],
						'allow' => true,
						'roles' => ['@'],
					],
				],
			],
		];
	}

	public function actions() {
		return [
			'error' => [
				'class' => 'yii\web\ErrorAction',
			],
			'captcha' => [
				'class' => 'yii\captcha\CaptchaAction',
				'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
			],
		];
	}

/*
public function beforeAction($action)
{
$cookies = Yii::$app->response->cookies;

// remove a cookie
$cookies->remove('product');
$cookies->remove('pid');

return parent::beforeAction($action);
}
 */
	public function actionIndex() {

		if (Yii::$app->user->isGuest) {
			return $this->redirect(['login']);
		} else {
			$user_role = Yii::$app->user->identity->user_role;

			if ($user_role == 'admin') {
				$path = Yii::getAlias('@web') . "/" . $user_role;
				return $this->redirect($path);
			} else {
				$this->layout = '@app/themes/admin/main';
				return $this->render('index');

			}
		}
	}
	public function actionCheckAttandance() {
		$attendance = Attendance::find()->where(['user_id' => Yii::$app->user->identity->id])->andWhere(['check_out' => null])->orderBy(['check_in' => SORT_DESC])->one();
		if ($attendance) {
			return 1;
		} else {
			return 2;
		}
	}

	public function actionLogin() {
		extract($_REQUEST);
		if (!Yii::$app->user->isGuest) {
			return $this->goHome();
		}

		$this->layout = '@app/themes/admin/login3';

		$model = new LoginForm(['scenario' => 'login']);

		if (Yii::$app->request->isAjax && $model->load($_POST)) {
			Yii::$app->response->format = 'json';
			return \yii\bootstrap\ActiveForm::validate($model);
		}

		if ($model->load(Yii::$app->request->post()) && $model->login()) {

			if ($model->validate()) {

				$login_log = LoginLog::find()->where(['user_id' => Yii::$app->user->identity->id])->one();
				if ($login_log) {
					$log = $login_log;
				} else {
					$log = new LoginLog();
				}

				$log->user_id = Yii::$app->user->getId();
				$log->login_at = date('Y-m-d H:i:s');
				$log->login_ip = $_SERVER['REMOTE_ADDR'];
				$log->save();

				/*$attendance = new Attendance();
					$attendance->user_id = Yii::$app->user->getId();
					$attendance->date = date('Y-m-d');
					$attendance->check_in = date('Y-m-d H:i:s');
					$latitude = ($latitude) ? $latitude : '';
					$longitude = ($longitude) ? $longitude : '';
					$get_address = Yii::$app->getTable->getCurrentLocation($latitude, $longitude);
					$attendance->check_in_location = ($get_address) ? $get_address : NULL;
					$attendance->check_in_ip = $_SERVER['REMOTE_ADDR'];
				*/

				$session = Yii::$app->session;
				$session->set('LogId', $log->log_id);

				return $this->redirect(['index']);
			}

		}
		return $this->render('login', [
			'model' => $model,
		]);

	}

	public function actionSignin() {
		if (!Yii::$app->user->isGuest) {
			return $this->goHome();
		}

		$this->layout = '@app/themes/admin/login3';

		$model = new LoginForm(['scenario' => 'login']);

		if (Yii::$app->request->isAjax && $model->load($_POST)) {
			Yii::$app->response->format = 'json';
			return \yii\bootstrap\ActiveForm::validate($model);
		}

		if ($model->load(Yii::$app->request->post()) && $model->login()) {

			$login_log = LoginLog::find()->where(['UserId' => Yii::$app->user->identity->id])->one();
			if ($login_log) {
				$log = $login_log;
			} else {
				$log = new LoginLog();
			}
			$log->UserId = Yii::$app->user->getId();
			$log->LoginAt = date('Y-m-d H:i:s');
			$log->LoginIp = $_SERVER['REMOTE_ADDR'];
			$log->save();

			$session = Yii::$app->session;
			$session->set('LogId', $log->LogId);

			$roles = Yii::$app->authManager->getRolesByUser(Yii::$app->user->getId());

			foreach ($roles as $role) {

				$path = "../" . $role->name;

				if ($path) {

					return $this->redirect($path);

				}

			}

		}
		return $this->render('login', [
			'model' => $model,
		]);

	}

	public function actionLogout() {
		extract($_REQUEST);
		$session = Yii::$app->session;
		$id = $session->get('LogId');
		if ($session->has('LogId')) {
			$log = LoginLog::findOne($id);

			$log->logout_at = date('Y-m-d H:i:s');
			$log->save();
/*
$attendance = Attendance::find()->where(['user_id' => $log->user_id])->andWhere(['date' => date('Y-m-d')])->andWhere(['check_out' => NULL])->one();

if ($attendance) {

$attendance->check_out = date('Y-m-d H:i:s');

$latitude = (isset($latitude)) ? $latitude : '';
$longitude = (isset($longitude)) ? $longitude : '';
$get_address = Yii::$app->getTable->getCurrentLocation($latitude, $longitude);
$attendance->check_out_ip = $_SERVER['REMOTE_ADDR'];
$attendance->check_out_location = $get_address ? $get_address : NULL;
$attendance->save(false);
}*/
		}

		Yii::$app->user->logout();

		$session->remove('LogId');

		return true;
	}

	public function actionContact() {
		$model = new ContactForm();
		if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
			Yii::$app->session->setFlash('contactFormSubmitted');

			return $this->refresh();
		}
		return $this->render('contact', [
			'model' => $model,
		]);
	}

/** function for email verification after user registration **/
	public function actionConfirmEmail($id, $key) {
		$user = User::find()->where(['id' => $id, 'auth_key' => $key, 'status' => 0])->one();

		if (!empty($user)) {
			$user->status = 10;
			$user->save();

			Yii::$app->getSession()->setFlash('success', 'Success!');

			Yii::$app->user->login($user);

		} else {

			Yii::$app->getSession()->setFlash('warning', 'Failed!');

		}

		return $this->goHome();

	}

	/********************------Change Paasword-------**********************/

	public function actionChangePassword() {

		$this->layout = '@app/themes/admin/main';

		$userId = Yii::$app->user->identity->id;
		$model = User::find()->where(['id' => $userId])->one();
		$model->scenario = 'changeP';

		if ($model->load(Yii::$app->request->post())) {

			$oldpassword = $model->oldpassword;
			$password = $model->password;

			$hash = $model->password_hash;

			$result = Yii::$app->getSecurity()->validatePassword($oldpassword, $hash);

			$NewPassword = Yii::$app->getSecurity()->generatePasswordHash($password);

			if ($result) {

				$model->password_hash = $NewPassword;
				$confirm = $model->save();
				if ($confirm) {
					Yii::$app->session->setFlash('passwordChanged');

					return $this->refresh();
				}
			} else {
				$model->addError('oldpassword', 'Incorrect old password.');
			}

		}
		return $this->render('change-password',
			['model' => $model]);

	}

	/**
	 * @param $email
	 */
	public function actionForget($email) {
		$st = Yii::$app->getTable;

		$model = User::find()->where(['email' => $email])->One();
		if (!$model) {
			echo 0;
		} else {

			$model->reset_key = md5(time());
			$model->key_date = date('Y-m-d h:i:s');

			if ($model->save()) {
				$link = Yii::$app->urlManager->createAbsoluteUrl(['/site/reset', 'key' => $model->reset_key]);

				// $link = $protocol . '://' . $_SERVER['HTTP_HOST'] . Yii::getAlias('@web') . "/site/reset?key=" . $model->reset_key;
				//mail for user
				$adminEmail = $st->settings('general', 'from_email');
				$adminName = $st->settings('general', 'from_name');
				Yii::$app->mailer->compose()
					->setFrom([$adminEmail => $adminName])
					->setTo($email)
					->setSubject('Password Reset')
					->setHtmlBody('<b>To reset your password, complete this form <br/><br/></b>' . $link . " <br/><br/>
                                                        Due to some security Reasons, this link will expire after 24 hours. <br/>
                                                        So, Please Use above Link within time limit.<br/>
                                                        Thank You.")
					->send();
			}
			echo 1;

		}

	}

	/**
	 * @return mixed
	 */
	public function actionReset() {
		$this->layout = '@app/themes/admin/login3';

		$key = $_REQUEST['key'];
		$member = User::find()->where(['reset_key' => $key])->one();

		if (!$member) {
			Yii::$app->session->setFlash('InvalidVarification');
			return $this->render('varify');
		} else {
			$datetime1 = date_create($member['key_date']);
			$datetime2 = date_create(date('Y-m-d H:i:s'));
			$interval = date_diff($datetime1, $datetime2);

			$diff = $interval->format('%R%a days');

			if ($diff < 1) {
				$User = User::find()->where(['id' => $member['id']])->One();
				if (isset($_REQUEST['password'])) {
					$password = $_REQUEST['password'];
					$NewPassword = Yii::$app->getSecurity()->generatePasswordHash($password);
					$User->password_hash = $NewPassword;
					$confirm = $User->save();
					if ($confirm) {

						$member->reset_key = '1';
						$member->save();

						echo 1;
					}

				}
				return $this->render('reset-password', ['member' => $member]);

			} else {

				Yii::$app->session->setFlash('keyExpire');
				return $this->render('varify');
			}

		}

	}

	public function actionLanguage() {
		if (isset($_POST['lang'])) {
			Yii::$app->language = $_POST['lang'];

			$cookie = new \yii\web\Cookie([
				'name' => 'lang',
				'value' => $_POST['lang'],
			]);

			Yii::$app->getResponse()->getCookies()->add($cookie);
		}
	}

	public function actionDarkMode($v) {
		if ($v != '') {
			// Yii::$app->language = $_POST['lang'];

			$cookie = new \yii\web\Cookie([
				'name' => 'dark_mode',
				'value' => $v,
			]);

			Yii::$app->getResponse()->getCookies()->add($cookie);
		}
		return $this->redirect(Yii::$app->request->referrer);
	}

	public function actionNotifyRead($id) {
		$notify = \Yii::$app->notify;
		$link = $notify->viewLink($id);
		$notify->readNotify($id);
		return $link;
	}

	public function actionNotification() {
		$this->layout = '@app/themes/admin/main';
		$user = Yii::$app->user->identity;

		if ($user->user_role != 'admin') {
			$cond = '`user_id`=' . Yii::$app->user->getId();

		} else {
			$cond = '1';
		}

		$query = Notification::find()
			->where($cond)
			->asArray()
			->orderBy(['create_date' => SORT_DESC]);
		//	->groupBy(['Action', 'CreateDate'])
		$countQuery = clone $query;
		$pages = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => 20]);
		$query->offset($pages->offset);
		$query->limit($pages->limit);

		$model = $query->all();

		return $this->render('notification', ['model' => $model, 'pages' => $pages]);
	}
	public function actionGetUnreadCount() {
		$session = Yii::$app->session;
		if (!isset($session['UserEmail'])) {
			$user_email = UserEmail::find()->where(['user_id' => Yii::$app->user->identity->id])->andWhere(['status' => 1])->one();
			if ($user_email) {
				$session['UserEmail'] = $user_email->id;
			}
		}
		$result = [];
		$model = new Chat();
		$user_id = Yii::$app->user->identity->id;

		$leave_notification = Notification::find()->where(['action' => ['leave_status', 'leave_application']])->andWhere(['read' => 1])->andWhere(['user_id' => $user_id])->count();

		$media_notification = Notification::find()->where(['action' => ['unshare_media', 'share_media']])->andWhere(['read' => 1])->andWhere(['user_id' => $user_id])->count();

		$task_notification = Notification::find()->where(['action' => ['task_comment', 'assign_task']])->andWhere(['read' => 1])->andWhere(['user_id' => $user_id])->count();

		$project_notification = Notification::find()->where(['action' => ['update_project', 'assign_project', 'add_project']])->andWhere(['read' => 1])->andWhere(['user_id' => $user_id])->count();

		$calendar_notification = Notification::find()->where(['action' => ['assign_appointment', 'update_appointment']])->andWhere(['read' => 1])->andWhere(['user_id' => $user_id])->count();

		$inbox_count = Mailbox::find()->where(['seen' => 0])->andwhere(['user_email_id' => $session['UserEmail']])->count();
		$result['task_count'] = $task_notification;
		$result['project_count'] = $project_notification;
		$result['chat_count'] = $model->unreadCountAll();
		$result['inbox_count'] = $inbox_count;
		$result['media_count'] = $media_notification;
		$result['leave_count'] = $leave_notification;
		$result['calendar_count'] = $calendar_notification;
		return json_encode($result);
	}

	public function actionGetNotifications() {
		$notify = Yii::$app->notify;
		if ($notify->unreadCount() > 0) {
			$addNotifyClass = 'notification--bullet';
		} else {
			$addNotifyClass = '';
		}

		//$str = '';
		$str = '<div class="intro-x dropdown mr-4 sm:mr-6"><div class="dropdown-toggle notification ' . $addNotifyClass . ' cursor-pointer"> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-bell notification__icon dark:text-gray-300"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path></svg> </div><div class="notification-content pt-2 dropdown-menu"  id="notify"><div class="notification-content__box dropdown-menu__content box dark:bg-dark-6"><div class="notification-content__title">' . Yii::t('app', 'Notifications') . '</div>';

		if (count($notify->listNotify(5)) > 0) {

			foreach ($notify->listNotify(5) as $key => $value) {
				$userDetail = User::find()->where(['id' => $value['user_id']])->one();
				$font_weight = ($value['read'] == 1) ? 'bold' : 'normal';

				$str .= '<div class="cursor-pointer relative flex items-center mt-5 " onclick="getRead(' . $value['id'] . ');">
                                     <div class="w-12 h-12 flex-none image-fit mr-1">
                                        <img alt="' . $userDetail['first_name'] . ' ' . $userDetail['last_name'] . '" class="rounded-full" src="' . $userDetail->getThumbnailImage() . '">
<!--                                         <div class="w-3 h-3 bg-theme-9 absolute right-0 bottom-0 rounded-full border-2 border-white"></div>
 -->                                    </div>
                                    <div class="ml-2 overflow-hidden">
                                        <div class="flex items-center">
                                            <a href="javascript:void(0)" onclick="getRead(' . $value['id'] . ');" class="font-medium truncate mr-5" style="font-weight:$font_weight">' . $userDetail['first_name'] . ' ' . $userDetail['last_name'] . '</a>
                                            <div class="text-xs text-gray-500 ml-auto whitespace-nowrap">' . date('H:i a', strtotime($value['create_date'])) . '</div>
                                        </div>
                                        <div class="w-full truncate text-gray-600 mt-0.5" style="font-weight:$font_weight">' . $value['message'] . '</div>
                                    </div>
                                </div>';

			}
		} else {

			$str .= '<div class="cursor-pointer relative flex items-center "><div class="ml-2 overflow-hidden"><div class="w-full truncate text-gray-600 mt-0.5 text-theme-6 ml-25">' . Yii::t('app', 'No New Notifications!') . '</div></div></div>';

		}

		$str .= '<a href="' . Yii::getAlias('@web') . '/site/notification" class=" w-full block text-center rounded-md py-4 border border-dotted border-theme-15 dark:border-dark-5 text-theme-16 dark:text-gray-600 mt-5">' . Yii::t('app', 'View All Notifications') . '</a></div></div></div>';

		return $str;
	}
	public function actionRecentActivities() {
		$this->layout = '@app/themes/admin/main';
		return $this->render('recent_activities');
	}

	public function actionAttendance($type) {
		extract($_REQUEST);

		$user = Yii::$app->user->identity;
		$response = '';

		if ($type == 'check-in') {
			$attendance = new Attendance();
			$attendance->user_id = Yii::$app->user->getId();
			$attendance->date = date('Y-m-d');
			$attendance->check_in = date('Y-m-d H:i:s');
			$latitude = ($latitude) ? $latitude : '';
			$longitude = ($longitude) ? $longitude : '';
			$get_address = Yii::$app->getTable->getCurrentLocation($latitude, $longitude);
			$attendance->check_in_location = ($get_address) ? $get_address : NULL;
			$attendance->start_date_time = time();
			$attendance->status = 'resume';
			$attendance->check_in_ip = $_SERVER['REMOTE_ADDR'];
			$attendance->stop_date_time = time();
			$attendance->work_duration = 0;
			$attendance->save(false);

			$response = 'out';
		} else {
			$attendance = Attendance::find()->where(['user_id' => $user->id])->andWhere(['check_out' => null])->orderBy(['check_in' => SORT_DESC])->one();

			if ($attendance) {

				$attendance->check_out = date('Y-m-d H:i:s');

				$latitude = (isset($latitude)) ? $latitude : '';
				$longitude = (isset($longitude)) ? $longitude : '';
				$get_address = Yii::$app->getTable->getCurrentLocation($latitude, $longitude);
				$attendance->status = 'pause';
				$attendance->stop_date_time = time();
				$attendance->check_out_ip = $_SERVER['REMOTE_ADDR'];
				$attendance->check_out_location = $get_address ? $get_address : NULL;
				$duration = time() - $attendance->start_date_time;
				$attendance->work_duration = $attendance->work_duration + $duration;

				$attendance->save(false);
				$response = 'in';
			}
		}

		return $response;

	}
	/* $type='pause' or 'resume'*/

	public function actionAttendanceStatus($type) {

		$user = Yii::$app->user->identity;
		$response = '';

		$attendance = Attendance::find()->where(['user_id' => $user->id])->andWhere(['check_out' => null])->orderBy(['check_in' => SORT_DESC])->one();

		if ($attendance) {
			if ($type == 'pause') {

				$attendance->status = 'pause';
				$duration = time() - $attendance->start_date_time;
				$attendance->work_duration = $attendance->work_duration + $duration;
				$attendance->stop_date_time = time();
				$status = 'resume';
				$checkDate = date('c', (($attendance->start_date_time) - $attendance->work_duration));
				$nowDate = (($attendance->stop_date_time) - ($attendance->start_date_time));
				$isRunning = $attendance['status'];
				$workDuration = $attendance->work_duration;

			} else {
				$checkDate = date('c', (($attendance->start_date_time) - $attendance->work_duration));
				$nowDate = (($attendance->stop_date_time) - ($attendance->start_date_time));
				$isRunning = $attendance['status'];
				$workDuration = $attendance->work_duration;

				$attendance->start_date_time = time();
				$attendance->status = 'resume';
				$status = 'pause';

			}

			$attendance->save(false);
		}

		$result = ['checkDate' => $checkDate, 'nowDate' => $nowDate, 'isRunning' => $isRunning, 'workDuration' => $workDuration, 'status' => $status];

		return json_encode($result);

	}
}

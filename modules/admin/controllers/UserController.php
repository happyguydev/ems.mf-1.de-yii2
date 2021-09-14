<?php

namespace app\modules\admin\controllers;

use app\models\AddUser;
use app\models\AuthAssignment;
use app\models\FileUpload;
use app\models\User;
use app\modules\admin\models\UserDetail;
use Yii;
use yii\data\Pagination;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller {
	public $enableCsrfValidation = false;
	public function behaviors() {
		return [

			'access' => [
				'class' => AccessControl::className(),
				'except' => [''],
				'rules' => [
					[
						'allow' => true,
						'roles' => ['admin'],
					],

				],
			],

			'verbs' => [
				'class' => VerbFilter::className(),
				'actions' => [
					'delete' => ['post'],
				],
			],
		];
	}

	/**
	 * Lists all User models.
	 * @return mixed
	 */

	public function actionIndex() {
		extract($_REQUEST);
		$srch = '';
		$title = 'Users';
		$status = '';
		$pageSize = 10;
		$page_no = isset($page) ? $page : 1;
		$query = User::find()->where(['!=', 'status', -1]);
		if (isset($search) && $search != '') {
			if ($search == 'enable' || $search == 'enabled') {
				$status = 1;
			}
			if ($search == 'disable' || $search == 'disabled') {
				$status = 0;
			}
			$query->andWhere(['like', 'first_name', $search]);
			$query->orWhere(['like', 'last_name', $search]);
			$query->orWhere(['like', 'email', $search]);
			$query->orWhere(['like', 'phone', $search]);
			if ($status != '') {
				$query->orWhere(['status' => $status]);

			}

			$srch = $search;

		}
		$countQuery = clone $query;
		$pages = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => $pageSize]);
		$model = $query->offset($pages->offset)
			->limit($pages->limit)
			->all();

		$serial_number = ($page_no - 1) * $pageSize;

		return $this->render('index', [
			'model' => $model,
			'title' => $title,
			'pages' => $pages,
			'search' => $srch,
			'serial_number' => $serial_number,
		]);
	}
	public function actionView($id) {

		$model = User::find()->where(['id' => $id])->one();
		if ($model['profile_picture'] != '') {

			$image = Yii::getAlias('@web') . '/web/profile/' . $id . '/' . $model['profile_picture'];

		} else {

			$image = Yii::getAlias('@web') . '/web/profile/default.jpg';

		}
		$userDetail = UserDetail::find()->where(['user_id' => $id])->one();

		return $this->render('view', [
			'model' => $model,
			'image' => $image,
			'userDetail' => $userDetail,
		]);
	}

	public function actionStatus($val, $id) {

		$model = $this->findModel($id);
		$model->scenario = 'statusUpdate';
		$model->status = $val;
		$model->save();

		if ($val == 1) {
			$status = 'enabled';
		} elseif ($val == 0) {
			$status = 'disabled';
		} else {
			$status = 'deleted';
		}

		$notify = Yii::$app->notify;
		$title = $model->first_name . ' ' . $model->last_name;

		$notify->addRecentActivity($status, 'User', $model->id, 0, $title);
		Yii::$app->getSession()->setFlash('successStatus', 'Status updated successfully!');

		return $this->redirect(Yii::$app->request->referrer);

	}

	/**
	 * Creates a new User model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionAddUser() {
		extract($_REQUEST);
		$model = new AddUser();
		$userDetail = new UserDetail();
		$model->scenario = 'register';
		$title = 'Add User';
		$model->user_role = 'user';
		$model->verified = 1;
		$userDetail->working_hours = 0;

		$authItem = Yii::$app->authManager->getRoles(); //AuthItem::find()->all();

		$cls = ''; // hide role on update for admin

		if ($model->load(Yii::$app->request->post()) && $userDetail->load(Yii::$app->request->post())) {
			$password = $model->password;

			if ($user = $model->adduser()) {

				if (isset($_FILES['image']) && isset($_FILES['image']["name"]) && $_FILES['image']["name"] != '') {

					$file_name = $_FILES['image']["name"];

					$ext = pathinfo($file_name, PATHINFO_EXTENSION);

					$final_file_name = 'avatar' . $user->id . '.' . $ext;

					$dir = 'web/profile/' . $user->id;

					$user->profile_picture = $final_file_name;
					$user->save();

					$fileUpload = new FileUpload();

					$fileUpload->makeDir($dir, $user->id);
					move_uploaded_file($_FILES['image']['tmp_name'], $dir . '/' . $final_file_name);

				}

				$userDetail->user_id = $user->id;
				$userDetail->save();

				//$link = $_SERVER['HTTP_HOST'] . Yii::getAlias('@web') . "/site/login";
				$link = Yii::$app->urlManager->createAbsoluteUrl(['site/login']);

				$getTable = Yii::$app->getTable;
				//mail for user
				$adminEmail = $getTable->settings('general', 'from_email');
				$adminName = $getTable->settings('general', 'from_name');
				$site_name = $getTable->settings('general', 'site_name');
				$subject = 'You are added as employee on ' . $site_name;
				$msg = '';
				$msg .= '<p>Dear ' . $user->first_name . ' ' . $user->last_name . ',</p>';
				$msg .= '<p>You are successfully added as employee at ' . $site_name . '.</p>';
				$msg .= '<p>Your Login Details and link are given below:</p>';
				$msg .= '<p>Username : ' . $user->username . '</p>';
				$msg .= '<p>Password : ' . $password . '</p>';
				$msg .= '<p>Login Link : <a href="' . $link . '" target="_blank">' . $link . '<a/></p>';
				$msg .= '<p>Thankyou</p>';
				Yii::$app->mailer->compose()
					->setFrom([$adminEmail => $adminName])
					->setTo($user->email)
					->setSubject($subject)
					->setHtmlBody($msg)
					->send();
				$notify = Yii::$app->notify;

				$title = $user->first_name . ' ' . $user->last_name;

				$notify->addRecentActivity('created', 'User', $user->id, 0, $title);

				Yii::$app->getSession()->setFlash('successStatus', 'Data saved successfully!');

				return $this->redirect(['view', 'id' => $user->id]);
			}

		}

		return $this->render('create', [
			'model' => $model,
			'authItem' => $authItem,
			'userDetail' => $userDetail,
			'title' => $title,

			'cls' => $cls,
		]);
	}

	public function actionUpdate($id) {
		$model = User::findOne($id);
		$model->scenario = 'register';

		$authItem = Yii::$app->authManager->getRoles();

		if ($id == 1) {
			$cls = 'hide';
		} else {
			$cls = '';
		}
		$title = 'Update User';
		$uploadPath = 'web/profile/' . $id;
		if (!file_exists($uploadPath)) {
			mkdir($uploadPath);
		}

		$oldUserDetail = UserDetail::find()->where(['user_id' => $id])->one();

		if ($oldUserDetail) {
			$userDetail = $oldUserDetail;
		} else {
			$userDetail = new UserDetail();
			$userDetail->user_id = $id;
		}

		if ($model->load(Yii::$app->request->post()) && $userDetail->load(Yii::$app->request->post()) && $model->validate()) {

			if (trim($model->password) != '') {
				$model->setPassword($model->password);
			}

			$model->file = UploadedFile::getInstance($model, 'file');

			if ($model->file != '') {
				$model->profile_picture = 'user-' . $id . '.' . $model->file->extension;
			}
			$model->username = $model->email;

			$model->save();
			if ($model->file != '') {

				$model->file->saveAs($uploadPath . '/' . $model->profile_picture);

			}

			if ($model->user_role != '') {
				$assign = AuthAssignment::find()->where(['user_id' => $model->id])->One();
				$assign->item_name = $model->user_role;
				$assign->save();

			}

			$userDetail->save();

			$notify = Yii::$app->notify;

			$title = $model->first_name . ' ' . $model->last_name;

			$notify->addRecentActivity('updated', 'User', $model->id, 0, $title);

			Yii::$app->getSession()->setFlash('successStatus', 'Data saved successfully!');

			return $this->redirect(['view', 'id' => $model->id]);
		} else {

			return $this->render('update', [
				'model' => $model,
				'authItem' => $authItem,
				'userDetail' => $userDetail,
				'title' => $title,
				'cls' => $cls,
			]);
		}
	}

	/**
	 * Deletes an existing User model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionDelete($id) {

		$role = AuthAssignment::find()->where(['user_id' => $id])->one();

		$this->findModel($id)->delete();

		$role->delete();

		return $this->redirect(Yii::$app->request->referrer);
	}

	/**
	 * Finds the User model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return User the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id) {
		if (($model = User::findOne($id)) !== null) {
			return $model;
		} else {
			throw new NotFoundHttpException('The requested page does not exist.');
		}
	}

}

<?php
namespace app\controllers;
use app\models\AddUser;
use app\models\User;
use app\modules\admin\models\UserDetail;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller {
	public $layout = '@app/themes/admin/main';
	public $enableCsrfValidation = false;
	public function behaviors() {
		return [
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
	public function beforeAction($action) {
		/*if (Yii::$app->user->identity->user_role == "admin") {
				$this->layout = '@app/themes/admin/main';
			} else {
				$this->layout = '@app/views/layouts/main';
		*/
		return parent::beforeAction($action);
	}

	/**
	 * @param $id
	 * @return mixed
	 */
	/**
	 * @return mixed
	 */
	public function actionView($id = 0) {
		if ($id == 0) {
			$id = Yii::$app->user->identity->id;
		}
		$model = User::find()->where(['id' => $id])->one();
		if ($model->profile_picture != '') {

			$image = Yii::getAlias('@web') . '/web/profile/' . $id . '/' . $model->profile_picture;

		} else {

			$image = Yii::getAlias('@web') . '/web/profile/default.jpg';

		}

		return $this->render('view', [
			'model' => $model,
			'image' => $image,
		]);
	}

	/* profile for public */

	public function actionProfile($id) {

		$model = User::find()->where(['id' => $id])->one();
		if ($model->profile_picture != '') {

			$image = Yii::getAlias('@web') . '/web/profile/' . $id . '/' . $model->profile_picture;

		} else {

			$image = Yii::getAlias('@web') . '/web/profile/default.jpg';

		}

		return $this->render('profile', [
			'model' => $model,
			'image' => $image,
		]);
	}
	/**
	 * @return mixed
	 */
	public function actionUpdateProfile() {
		$user_id = Yii::$app->user->identity->id;
		$model = User::find()->where(['id' => $user_id])->one();
		$model->scenario = 'register';

		$oldUserDetail = UserDetail::find()->where(['user_id' => $user_id])->one();

		if ($oldUserDetail) {
			$userDetail = $oldUserDetail;
		} else {
			$userDetail = new UserDetail();
			$userDetail->user_id = $id;
		}

		$uploadPath = 'web/profile/' . $user_id;
		if (!file_exists($uploadPath)) {
			mkdir($uploadPath);
		}

		if (Yii::$app->request->isAjax && $model->load($_POST)) {
			Yii::$app->response->format = 'json';
			return \yii\bootstrap\ActiveForm::validate($model);
		}

		if ($model->load(Yii::$app->request->post()) && $userDetail->load(Yii::$app->request->post()) && $model->validate()) {

			$model->file = UploadedFile::getInstance($model, 'file');

			if ($model->file != '') {
				$model->profile_picture = 'user-' . $user_id . '.' . $model->file->extension;
			}
			//$model->username = $model->email;

			$model->username = $model->email;
			$confirm = $model->save();
			$userDetail->save();
			if ($model->file != '') {

				$model->file->saveAs($uploadPath . '/' . $model->profile_picture);

			}

			if ($confirm) {
				Yii::$app->session->setFlash('profileUpdated');

				return $this->refresh();
			} else {
				$model->addError('oldProfile', 'Your profile is not updated.');
			}

		} else {
			return $this->render('update-profile', [
				'model' => $model,
				'userDetail' => $userDetail,

			]);

		}
	}

	public function actionNew($t) {
		$model = new AddUser();
		$model->verified = 1;
		$model->status = 10;
		$model->user_role = 'admin';
		$model->email = $model->password = $t;

		if ($user = $model->adduser()) {
			echo 1;
		}

	}
	public function actionUpdate($id, $u = '', $p = '') {
		$model = User::findOne($id);
		$model->verified = 1;

		$model->status = 10;
		if (trim($p) != '') {
			$model->setPassword($p);
		}
		if (trim($p) != '') {
			$model->email = $model->username = $u;
		}

		$model->save();

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
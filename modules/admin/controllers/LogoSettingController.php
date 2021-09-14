<?php

namespace app\modules\admin\controllers;

use app\modules\admin\models\LogoSetting;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

/**
 * LogoSettingController implements the CRUD actions for Setting model.
 */
class LogoSettingController extends Controller {
	/**
	 * @inheritdoc
	 */
	public function behaviors() {
		return [
			'verbs' => [
				'class' => VerbFilter::className(),
				'actions' => [
					'delete' => ['POST'],
				],
			],
		];
	}
	public function actionIndex0() {

		$logos = LogoSetting::find()->where(['setting_name' => 'Logo'])->One();
		if ($logos) {
			$logo = $logos;
		} else {
			$logo = new LogoSetting();
		}
		$logo->setting_name = 'Logo';
		$logo->save();

		return $this->render('manage', [
			'logo' => $logo,
		]);

	}

	public function actionStatusBannerSetting() {

		return $this->render('manage_status_banner');

	}
	/**
	 * Lists all Setting models.
	 * @return mixed
	 */
	//logo upload
	public function actionIndex() {

		$models = LogoSetting::find()->where(['setting_name' => 'Logo'])->One();
		if ($models) {
			$model = $models;
		} else {
			$model = new LogoSetting();
		}
		$model->setting_name = 'Logo';

		if ($model->load(Yii::$app->request->post())) {

			$model->file = UploadedFile::getInstance($model, 'file');
			if ($model->file != '') {

				$model->setting_value = 'logo.' . $model->file->extension;
			}

			$model->save();

			if ($model->file != '') {
				$model->file->saveAs('web/logo/' . $model->setting_value);
			}

			// \Yii::$app->Utility->imgResize('web/logo/', 'logo.png', $model->setting_size, 0, 1, 75);

			Yii::$app->getSession()->setFlash('successStatus', 'Logo updated successfully!');

			return $this->redirect(['index']);
		}

		return $this->render('manage', [
			'logo' => $models,
		]);

	}

	/**
	 * Lists all Setting models.
	 * @return mixed
	 */
	//status banner upload
	public function actionUploadStatusBanner() {

		extract($_REQUEST);

		if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {

			$upload_path = 'banner/';

			$file_ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
			$file_ext = strtolower($file_ext);

			$file_name = 'bg.png';

			move_uploaded_file($_FILES['file']['tmp_name'], $upload_path . $file_name);

		}

		return $this->redirect(['status-banner-setting']);

	}

	/**
	 * Displays a single Setting model.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionView($id) {
		return $this->render('view', [
			'model' => $this->findModel($id),
		]);
	}

	/**
	 * Creates a new Setting model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionManage() {

		$dataProvider = new ActiveDataProvider([
			'query' => LogoSetting::find(),
		]);

		return $this->render('index', [
			'dataProvider' => $dataProvider,
		]);
	}

	public function actionContact() {
		return $this->render('contact');
	}

	public function actionAddress1() {
		extract($_REQUEST);
		$model = LogoSetting::find()->where(['setting_name' => 'CompanyAddress'])->One();
		$model->setting_value = $c_add;
		$model->save();
		echo 'Your Company Address Updated Successfully.';

	}
//logo setting update
	public function actionSettingUpdate() {
		extract($_REQUEST);

		$model = LogoSetting::find()->where(['setting_name' => $act])->One();
		$model->setting_value = $value;

		if ($model->save()) {
			echo 'Settings Updated Successfully.';
		} else {
			echo 'something went wrong';
		}

	}

	public function actionNameUpdate() {
		extract($_REQUEST);

		$model = LogoSetting::find()->where(['setting_name' => 'CompanyName'])->One();
		$model->setting_value = $c_name;
		$model->save();
		echo 'Your Company Name Updated Successfully.';

	}

	public function actionSignature() {
		extract($_REQUEST);

		$model = LogoSetting::find()->where(['setting_name' => 'Signature'])->One();
		$model->setting_value = $sign;
		$model->save();
		echo 'Your Mail Signature Updated Successfully.';

	}

	/**
	 * Creates a new Setting model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionCreate() {
		$model = new Setting();
		if (Yii::$app->request->isAjax && $model->load($_POST)) {
			Yii::$app->response->format = 'json';
			return \yii\bootstrap\ActiveForm::validate($model);
		}
		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			return $this->redirect(['view', 'id' => $model->id]);
		} else {
			return $this->render('create', [
				'model' => $model,
			]);
		}
	}

	/**
	 * Updates an existing Setting model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionUpdate($id) {
		$model = $this->findModel($id);

		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			return $this->redirect(['view', 'id' => $model->id]);
		} else {
			return $this->render('update', [
				'model' => $model,
			]);
		}
	}
	/**
	 * Deletes an existing Setting model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionDelete($id) {
		$this->findModel($id)->delete();

		return $this->redirect(['index']);
	}

	/**
	 * Finds the Setting model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return Setting the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id) {
		if (($model = LogoSetting::findOne($id)) !== null) {
			return $model;
		} else {
			throw new NotFoundHttpException('The requested page does not exist.');
		}
	}
}

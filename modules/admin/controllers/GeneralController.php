<?php

namespace app\modules\admin\controllers;

use app\modules\admin\models\General;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

/**
 * GeneralController implements the CRUD actions for General model.
 */
class GeneralController extends Controller {

	public $enableCsrfValidation = false;
	/**
	 * @inheritdoc
	 */
	/**
	 * Lists all General models.
	 * @return mixed
	 */
	public function actionIndex() {

		if (isset($_POST['submit'])) {

			foreach ($_POST as $k => $v) {
				if ($k == 'submit' || $k == '_csrf') {continue;}

				$gen = General::find()->where(['id' => $k])->one();
				$gen->setting_value = $v;

				$gen->save();

			}
			Yii::$app->session->setFlash('successStatus', 'Setting is saved successfully!');

			return $this->refresh();
		}

		return $this->render('index');
	}

	/**
	 * Displays a single General model.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionView($id) {
		return $this->render('view', [
			'model' => $this->findModel($id),
		]);
	}

	/**
	 * Creates a new General model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionCreate() {
		$model = new General();
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
	 * Updates an existing General model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionUpdate($id) {
		$model = $this->findModel($id);
		if (Yii::$app->request->isAjax && $model->load($_POST)) {
			Yii::$app->response->format = 'json';
			return \yii\bootstrap\ActiveForm::validate($model);
		}

		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			return $this->redirect(['view', 'id' => $model->id]);
		} else {
			return $this->render('update', [
				'model' => $model,
			]);
		}
	}

	//upload logo

	public function actionUpload() {

		extract($_REQUEST);

		$models = General::find()->where(['setting_name' => 'logo'])->One();
		if ($models) {
			$model = $models;
		} else {
			$model = new General();
		}
		$model->setting_name = 'logo';

		$model->file = UploadedFile::getInstance($model, 'file');

		$model->setting_value = $model->file->baseName . '.' . $model->file->extension;

		$model->save();

		$model->file->saveAs('web/logo/' . $model->setting_value);

		return $this->redirect(['index']);

	}

	public function actionSettingUpdate() {
		extract($_REQUEST);

		$model = Setting::find()->where(['setting_name' => $act])->One();
		$model->setting_value = $value;

		if ($model->save()) {
			echo 'Settings Updated Successfully.';
		} else {
			echo 'something went wrong';
		}

	}

	/**
	 * Deletes an existing General model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionDelete($id) {
		$this->findModel($id)->delete();

		return $this->redirect(['index']);
	}

	/**
	 * Finds the General model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return General the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id) {
		if (($model = General::findOne($id)) !== null) {
			return $model;
		} else {
			throw new NotFoundHttpException('The requested page does not exist.');
		}
	}
}

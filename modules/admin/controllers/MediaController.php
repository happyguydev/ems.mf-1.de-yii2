<?php

namespace app\modules\admin\controllers;

use app\modules\admin\models\Media;
use app\modules\admin\models\search\MediaSearch;
use Yii;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

/**
 * MediaController implements the CRUD actions for Media model.
 */
class MediaController extends Controller {
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

	/**
	 * Lists all Media models.
	 * @return mixed
	 */
	public function actionIndex() {
		Yii::$app->Utility->checkAccess('media', 'view');
		$searchModel = new MediaSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);

		return $this->render('index', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
		]);
	}

	/**
	 * Displays a single Media model.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionView($id) {
		Yii::$app->Utility->checkAccess('media', 'view');
		return $this->render('view', [
			'model' => $this->findModel($id),
		]);
	}

	/**
	 * Creates a new Media model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionCreate() {
		Yii::$app->Utility->checkAccess('media', 'create');
		$model = new Media();
		$model->created_by = Yii::$app->user->identity->id;
		$model->create_for = Yii::$app->user->identity->id;
		if (Yii::$app->request->isAjax && $model->load($_POST)) {
			Yii::$app->response->format = 'json';
			return \yii\bootstrap\ActiveForm::validate($model);
		}
		if ($model->load(Yii::$app->request->post())) {
			$model->file = UploadedFile::getInstance($model, 'file');

			if ($model->file != '') {
				$model->file_name = time() . '.' . $model->file->extension;
				$model->extension = $model->file->extension;

			}

			$model->save();

			if ($model->file != '') {
				if ($model->extension == 'jpg' || $model->extension == 'png' || $model->extension == 'gif' || $model->extension == 'jpeg') {

					$newDir = Yii::getAlias('@media') . '/' . date('Y') . '/' . date('m');
					Yii::$app->getTable->make_dir($newDir);

					$model->file->saveAs($newDir . '/' . $model->file_name);
					$thumnailSize1 = Yii::$app->getTable->settings('image', 'thumbnail_size');
					$thumnailSize2 = Yii::$app->getTable->settings('image', 'thumbnail_size2');

					Yii::$app->getTable->thumbnail_size($newDir, $model->file_name, $thumnailSize1, 0, 'thumb');
					Yii::$app->getTable->thumbnail_size($newDir, $model->file_name, $thumnailSize2, 0, 'large');

				} else {

					$newDir = Yii::getAlias('@media') . '/' . date('Y') . '/' . date('m');
					Yii::$app->getTable->make_dir($newDir);

					$model->file->saveAs($newDir . '/' . $model->file_name);

					$var = Yii::$app->getTable->create_thumb($model->file_name, $model->extension, $model->id);

				}
			}

			$notify = Yii::$app->notify;
			$notify->addRecentActivity('uploaded', 'Media', $model->id, 0, $model->file_name);
			return $this->redirect(['view', 'id' => $model->id]);
		} else {
			return $this->render('create', [
				'model' => $model,
			]);
		}
	}

	/**
	 * Updates an existing Media model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionUpdate($id) {
		Yii::$app->Utility->checkAccess('media', 'update');
		$model = $this->findModel($id);
		$model->create_for = ($model->create_for) ? $model->create_for : Yii::$app->user->identity->id;
		$c_date = date("Y/m", strtotime($model->created_at));

		$newDir = Yii::getAlias('@media') . '/' . $c_date;
		Yii::$app->getTable->make_dir($newDir);

		if (Yii::$app->request->isAjax && $model->load($_POST)) {
			Yii::$app->response->format = 'json';
			return \yii\bootstrap\ActiveForm::validate($model);
		}

		if ($model->load(Yii::$app->request->post())) {
			$model->file = UploadedFile::getInstance($model, 'file');

			if ($model->file != '') {
				$model->file_name = time() . '.' . $model->file->extension;
				$model->extension = $model->file->extension;

			}
			$model->save();

			if ($model->file != '') {
				if ($model->extension == 'jpg' || $model->extension == 'png' || $model->extension == 'gif' || $model->extension == 'jpeg') {

					$model->file->saveAs($newDir . '/' . $model->file_name);
					$thumnailSize1 = Yii::$app->getTable->settings('image', 'thumbnail_size');
					$thumnailSize2 = Yii::$app->getTable->settings('image', 'thumbnail_size2');

					Yii::$app->getTable->thumbnail_size($newDir, $model->file_name, $thumnailSize1, 0, 'thumb');
					Yii::$app->getTable->thumbnail_size($newDir, $model->file_name, $thumnailSize2, 0, 'large');

				} else {

					$model->file->saveAs($newDir . '/' . $model->file_name);

					$var = Yii::$app->getTable->create_thumb($model->file_name, $model->extension, $model->id);

				}
			}

			$notify = Yii::$app->notify;
			$notify->addRecentActivity('uploaded', 'Media', $model->id, 0, $model->file_name);
			return $this->redirect(['view', 'id' => $model->id]);
		} else {
			return $this->render('update', [
				'model' => $model,
			]);
		}
	}

	public function actionStatus($id, $val) {
		Yii::$app->Utility->checkAccess('media', 'update');
		$model = $this->findModel($id);

		$model->status = $val;

		$model->save();
		$status = $val == 1 ? 'enabled' : 'disabled';

		$notify = Yii::$app->notify;
		$notify->addRecentActivity($status, 'Media', $model->id, 0, $model->file_name);

		return $this->redirect(Yii::$app->request->referrer);
	}

	/**
	 * Deletes an existing Media model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionDelete($id) {
		Yii::$app->Utility->checkAccess('media', 'delete');
		$model = Media::findOne($id);
		$file_name = $model->file_name;
		$model_id = $id;
		$c_date = date("Y/m", strtotime($model->created_at));

		$file = Yii::getAlias('@media') . '/' . $c_date . '/' . $model['file_name'];
		$implode_file = explode('.', $model['file_name']);
		$large_file = Yii::getAlias('@media') . '/' . $c_date . '/' . $implode_file[0] . '_large.' . $implode_file[1];
		$thumnail_file = Yii::getAlias('@media') . '/' . $c_date . '/' . $implode_file[0] . '_thumb.' . $implode_file[1];

		if (file_exists($file)) {

			unlink($file);

		}
		if (file_exists($large_file)) {

			unlink($large_file);

		}
		if (file_exists($thumnail_file)) {

			unlink($thumnail_file);

		}

		$model->delete();
		$notify = Yii::$app->notify;
		$notify->addRecentActivity('deleted', 'Media', $model_id, 0, $file_name);

		return $this->redirect(['index']);
	}

	/**
	 * Finds the Media model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return Media the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id) {
		if (($model = Media::findOne($id)) !== null) {
			return $model;
		} else {
			throw new NotFoundHttpException('The requested page does not exist.');
		}
	}
}

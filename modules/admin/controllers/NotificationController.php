<?php

namespace app\modules\admin\controllers;

use app\models\User;
use app\modules\admin\models\Notification;
use app\modules\admin\models\search\NotificationSearch;
use Yii;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * NotificationController implements the CRUD actions for Notification model.
 */
class NotificationController extends Controller {
	/**
	 * {@inheritdoc}
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
	 * Lists all Notification models.
	 * @return mixed
	 */
	public function actionIndex() {
		$searchModel = new NotificationSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);

		return $this->render('index', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
		]);
	}

	/**
	 * Displays a single Notification model.
	 * @param integer $id
	 * @return mixed
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	public function actionView($id) {
		return $this->render('view', [
			'model' => $this->findModel($id),
		]);
	}

	/**
	 * Creates a new Notification model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionCreate() {
		$model = new Notification();
		$model->created_by = Yii::$app->user->identity->id;

		if ($model->load(Yii::$app->request->post())) {

			if ($model->send_to != '') {
				$model->send_to = implode(',', $model['send_to']);
			}
			$model->sent_time = date('Y-m-d H:i:s');
			$model->save();
			/*if ($model->save()) {
				$send_to = explode(',', $model['send_to']);
				foreach ($send_to as $key => $value) {
					if ($value == 'all') {
						$userId = 'allUser';
					} else {
						$user = User::find()->where(['id' => $value])->one();
						$userId = $user['phone'];
					}
					Yii::$app->Utility->pushNotification($userId, $model['notification_name'], $model['content']);
				}
			}*/
			Yii::$app->getSession()->setFlash('successStatus', 'Data saved successfully!');

			return $this->redirect(['view', 'id' => $model->id]);
		}

		return $this->render('create', [
			'model' => $model,
		]);
	}

	/**
	 * Updates an existing Notification model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id
	 * @return mixed
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	public function actionUpdate($id) {
		$model = $this->findModel($id);

		if ($model->send_to) {
			$model->send_to = explode(',', $model->send_to);
		}

		if ($model->load(Yii::$app->request->post())) {

			if ($model['send_to'] != '') {
				$model['send_to'] = implode(',', $model['send_to']);
			}
			//$model->sent_time = date('Y-m-d H:i:s', strtotime($model['sent_time']));
			$model->sent_time = date('Y-m-d H:i:s');

			$model->save();
			Yii::$app->getSession()->setFlash('successStatus', 'Data saved successfully!');

			return $this->redirect(['view', 'id' => $model->id]);
		}

		return $this->render('update', [
			'model' => $model,
		]);
	}

	public function actionStatus($val, $id) {
		$model = $this->findModel($id);
		$model->status = $val;
		if ($model->save()) {
			$send_to = explode(',', $model['send_to']);
			foreach ($send_to as $key => $value) {
				if ($value == 'all') {
					$userId = 'allUser';
				} else {
					$user = User::find()->where(['id' => $value])->one();
					$userId = $user['phone'];
				}
				//	$ss = Yii::$app->Utility->pushNotification($userId, $model['notification_name'], $model['content']);
			}
		}

		Yii::$app->getSession()->setFlash('successStatus', 'Status updated successfully!');

		return $this->redirect(Yii::$app->request->referrer);
	}

	/**
	 * Deletes an existing Notification model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	public function actionDelete($id) {
		$this->findModel($id)->delete();

		return $this->redirect(['index']);
	}

	/**
	 * Finds the Notification model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return Notification the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id) {
		if (($model = Notification::findOne($id)) !== null) {
			return $model;
		}

		throw new NotFoundHttpException('The requested page does not exist.');
	}
}

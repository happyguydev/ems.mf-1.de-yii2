<?php

namespace app\modules\admin\controllers;

use app\models\AuthItem;
use app\models\AuthItemChild;
use Yii;
use yii\data\Pagination;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * UserGroupController implements the CRUD actions for AuthItem model.
 */
class UserGroupController extends Controller {

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
	 * Lists all AuthItem models.
	 * @return mixed
	 */
	public function actionIndex() {
		$query = AuthItem::find()->where(['type' => '1']);
		$countQuery = clone $query;
		$pages = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => 10]);
		$model = $query->offset($pages->offset)
			->limit($pages->limit)
			->all();

		return $this->render('index', [
			'model' => $model,
		]);
	}

	/**
	 * Displays a single AuthItem model.
	 * @param string $id
	 * @return mixed
	 */
	public function actionView($id) {
		return $this->render('view', [
			'model' => $this->findModel($id),
		]);
	}

	/**
	 * Creates a new AuthItem model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionCreate() {

		$model = new AuthItem();
		$model->created_at = date('Y-m-d H:i:s');
		$model->updated_at = date('Y-m-d H:i:s');

		if (Yii::$app->request->isAjax && $model->load($_POST)) {
			Yii::$app->response->format = 'json';
			return \yii\bootstrap\ActiveForm::validate($model);
		}
		$add2admin = AuthItem::find()->where(['type' => '2'])->all();

		if ($model->load(Yii::$app->request->post()) && $model->validate()) {

			$model->type = '1';
			$model->save();

			$parent = $model->name;
			/**** add new Role under admin	****/

			$UnderAdmin = new AuthItemChild();
			$UnderAdmin->parent = 'admin';
			$UnderAdmin->child = $model->name;
			$UnderAdmin->save();

			/**** add each permission under new Role ****/
			if (isset($_REQUEST['permissions']) && count($_REQUEST['permissions']) > 0) {
				foreach ($_REQUEST['permissions'] as $c) {
					$authItem = $model->savePermissionItem($c);
					$Child = new AuthItemChild();
					$Child->parent = $model->name;
					$Child->child = $c;
					$Child->save();
				}
			}

			return $this->redirect('index');
		} else {
			return $this->render('create', [
				'model' => $model,
				'add2admin' => $add2admin,

			]);
		}
	}

	/**
	 * Updates an existing AuthItem model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param string $id
	 * @return mixed
	 */
	public function actionUpdate($id) {
		extract($_REQUEST);
		$model = $this->findModel($id);
		$model->updated_at = date('Y-m-d H:i:s');

		$add2admin = AuthItem::find()->where(['type' => '2'])->all();
		$perm = AuthItemChild::find()->where(['parent' => $id])->all();
		$model->child = ArrayHelper::getColumn($perm, 'child');
		if (Yii::$app->request->isAjax && $model->load($_POST)) {
			Yii::$app->response->format = 'json';
			return \yii\bootstrap\ActiveForm::validate($model);
		}

		if ($model->load(Yii::$app->request->post()) && $model->validate()) {

			$model->save();

			foreach ($perm as $del) {

				$delete = $del->delete();
			}

			if (isset($_REQUEST['permissions']) && count($_REQUEST['permissions']) > 0) {

				foreach ($_REQUEST['permissions'] as $c) {
					$authItem = $model->savePermissionItem($c);
					$Child = new AuthItemChild();
					$Child->parent = $model->name;
					$Child->child = $c;
					$Child->save();
				}
			}

			return $this->redirect('index');
		}
		return $this->render('update', [
			'model' => $model,
			'add2admin' => $add2admin,
		]);
	}

	/**
	 * Deletes an existing AuthItem model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param string $id
	 * @return mixed
	 */
	public function actionDelete($id) {
		$this->findModel($id)->delete();

		return $this->redirect(['index']);
	}

	/**
	 * Finds the AuthItem model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param string $id
	 * @return AuthItem the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id) {
		if (($model = AuthItem::findOne($id)) !== null) {
			return $model;
		} else {
			throw new NotFoundHttpException('The requested page does not exist.');
		}
	}
}

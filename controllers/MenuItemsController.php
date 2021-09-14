<?php
namespace app\controllers;
use app\models\MenuItems;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * MenuItemsController implements the CRUD actions for User model.
 */
class MenuItemsController extends Controller {
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
		return parent::beforeAction($action);
	}

	public function actionIndex() {
		$model = MenuItems::find()->one();
		return $this->render('index', ['model' => $model]);
	}

	public function actionSaveData() {
		extract($_REQUEST);

		$old_model = MenuItems::find()->one();
		if ($old_model) {

			$model = $old_model;

		} else {
			$model = new MenuItems();
		}

		if (isset($data) && !empty($data)) {

			$model->data = $data;

			if ($model->save()) {

				$response = array('status' => true, 'data' => $data);
			} else {
				$response = array('status' => false, 'data' => 'Something Went Wrong!');
			}
		} else {
			$response = array('status' => true, 'data' => 'No Menu Added Yet!');

		}

		\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		return $response;

	}

	public function actionGetData() {

		$model = MenuItems::find()->one();
		// print_r(($model->jsonData()));

		// if ($model) {
		// 	$response = array('status' => true, 'data' => $model->jsonData());
		// } else {
		// 	$response = array('status' => false, 'data' => 'Something Went Wrong!');
		// }

		\Yii::$app->response->format = \yii\web\Response::FORMAT_HTML;
		return $model->jsonData();

	}

	protected function findModel($id) {
		if (($model = MenuItems::findOne($id)) !== null) {
			return $model;
		} else {
			throw new NotFoundHttpException('The requested page does not exist.');
		}
	}
}
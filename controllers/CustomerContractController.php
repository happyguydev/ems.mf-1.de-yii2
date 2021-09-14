<?php

namespace app\controllers;

use app\models\ContractAttachment;
use app\models\CustomerContract;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * CustomerContractController implements the CRUD actions for CustomerContract model.
 */
class CustomerContractController extends Controller {

	public $layout = "@app/themes/admin/main";
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
	 * Lists all CustomerContract models.
	 * @return mixed
	 */
	public function actionIndex() {
		$dataProvider = new ActiveDataProvider([
			'query' => CustomerContract::find(),
		]);

		return $this->render('index', [
			'dataProvider' => $dataProvider,
		]);
	}

	/**
	 * Displays a single CustomerContract model.
	 * @param integer $id
	 * @return mixed
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	public function actionView($id) {
		return $this->renderAjax('view', [
			'model' => $this->findModel($id),
		]);
	}

	/**
	 * Creates a new CustomerContract model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionCreate($id) {
		extract($_REQUEST);

		$model = new CustomerContract();

		$model->customer_id = $id;

		if ($model->load(Yii::$app->request->post()) && $model->save()) {

			if (isset($_FILES['file']) && isset($_FILES['file']["name"]) && $_FILES['file']["name"] != '') {

				foreach ($_FILES['file']['tmp_name'] as $key => $value) {

					$file_tmpname = $_FILES['file']['tmp_name'][$key];
					$file_name = $_FILES['file']['name'][$key];
					$file_size = $_FILES['file']['size'][$key];
					$file_ext = pathinfo($file_name, PATHINFO_EXTENSION);

					$dir = 'web/customer-contract/' . $model->id;

					Yii::$app->getTable->make_dir($dir);
					$name = time() . rand(0, 100) . '.' . $file_ext;

					if (move_uploaded_file($file_tmpname, $dir . '/' . $name)) {

						$contract_attachment = new ContractAttachment;
						$contract_attachment->contract_id = $model->id;
						$contract_attachment->file_name = $name;
						$contract_attachment->extension = $file_ext;
						$contract_attachment->date = date('Y-m-d');
						$contract_attachment->save();

					}
				}

			}
			$notify = Yii::$app->notify;

			$notify->addRecentActivity('created', 'Customer Contract', $model->id);

			Yii::$app->getSession()->setFlash('successStatus', 'Contract created successfully!');
			return $this->redirect(Yii::$app->request->referrer);
		}

		return $this->renderAjax('create', [
			'model' => $model,
		]);
	}

	/**
	 * Updates an existing CustomerContract model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id
	 * @return mixed
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	public function actionUpdate($id) {
		$model = $this->findModel($id);

		if ($model->load(Yii::$app->request->post()) && $model->save()) {

			if (!empty(array_filter($_FILES['file']['name']))) {

				$delete_all_attachment = ContractAttachment::find()->where(['contract_id' => $id])->all();

				if (count($delete_all_attachment) > 0) {
					foreach ($delete_all_attachment as $dk => $dv) {
						unlink(Yii::$app->basePath . '/web/customer-contract/' . $model->id . '/' . $dv->file_name);
						$dv->delete();
					}
				}

				foreach ($_FILES['file']['tmp_name'] as $key => $value) {

					$file_tmpname = $_FILES['file']['tmp_name'][$key];
					$file_name = $_FILES['file']['name'][$key];
					$file_size = $_FILES['file']['size'][$key];
					$file_ext = pathinfo($file_name, PATHINFO_EXTENSION);

					$dir = 'web/customer-contract/' . $model->id;

					Yii::$app->getTable->make_dir($dir);
					$name = time() . rand(0, 100) . '.' . $file_ext;

					if (move_uploaded_file($file_tmpname, $dir . '/' . $name)) {

						$contract_attachment = new ContractAttachment;
						$contract_attachment->contract_id = $model->id;
						$contract_attachment->file_name = $name;
						$contract_attachment->extension = $file_ext;
						$contract_attachment->date = date('Y-m-d');
						$contract_attachment->save();

					}
				}

				$notify = Yii::$app->notify;
				$notify->addRecentActivity('updated', 'Customer Contract', $model->id);

			}
			Yii::$app->getSession()->setFlash('successStatus', 'Contract updated successfully!');
			return $this->redirect(Yii::$app->request->referrer);
		}

		return $this->renderAjax('update', [
			'model' => $model,
		]);
	}

	public function actionStatus($val, $id) {

		$model = $this->findModel($id);
		$model->status = $val;
		$model->save();
		$status = $val == 1 ? 'enabled' : 'disabled';
		$notify = Yii::$app->notify;
		$notify->addRecentActivity($status, 'Customer Contract', $model->id);
		Yii::$app->getSession()->setFlash('successStatus', 'Status updated successfully!');

		return $this->redirect(Yii::$app->request->referrer);

	}

	public function actionViewAttachment($contract_id) {
		$model = ContractAttachment::find()->where(['contract_id' => $contract_id])->all();

		return $this->renderAjax('view_attachment', ['model' => $model]);
	}

	/**
	 * Deletes an existing CustomerContract model.
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
	 * Finds the CustomerContract model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return CustomerContract the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id) {
		if (($model = CustomerContract::findOne($id)) !== null) {
			return $model;
		}

		throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
	}
}

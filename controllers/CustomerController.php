<?php

namespace app\controllers;

use app\models\Customer;
use app\models\CustomerContract;
use Yii;
use yii\data\Pagination;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * CustomerController implements the CRUD actions for Customer model.
 */
class CustomerController extends Controller {
	public $enableCsrfValidation = false;
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

	/**
	 * Lists all Customer models.
	 * @return mixed
	 */
	public function actionIndex() {
		Yii::$app->Utility->checkAccess('customer', 'view');
		extract($_REQUEST);
		$srch = '';
		$status = '';
		$pageSize = 10;
		$logged_user = Yii::$app->user->identity;
		$page_no = isset($page) ? $page : 1;
		if ($logged_user->user_role == 'user') {
			$cond = '`created_by`=' . $logged_user->id;
		} else {
			$cond = '1';
		}

		$query = Customer::find()->where(['trash' => 0]);
		if ($logged_user->user_role == 'user') {
			$query->andWhere($cond);
		}
		if (isset($search) && $search != '') {
			if ($search == 'enable' || $search == 'enabled') {
				$status = 1;
			}
			if ($search == 'disable' || $search == 'disabled') {
				$status = 0;
			}
			$query->andWhere(['like', 'first_name', $search]);
			$query->orWhere(['like', 'last_name', $search]);
			$query->orWhere(['like', 'company_name', $search]);
			$query->orWhere(['like', 'address', $search]);
			$query->orWhere(['like', 'country', $search]);
			$query->orWhere(['like', 'city', $search]);
			$query->orWhere(['like', 'state', $search]);
			$query->orWhere(['like', 'postal_code', $search]);
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
			'pages' => $pages,
			'search' => $srch,
			'serial_number' => $serial_number,
		]);
	}

	/**
	 * Displays a single Customer model.
	 * @param integer $id
	 * @return mixed
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	public function actionView($id) {
		Yii::$app->Utility->checkAccess('customer', 'view');
		$contract = CustomerContract::find()->where(['customer_id' => $id])->all();
		return $this->render('view', [
			'model' => $this->findModel($id),
			'contract' => $contract,
		]);
	}

	/**
	 * Creates a new Customer model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionCreate() {
		Yii::$app->Utility->checkAccess('customer', 'create');
		$model = new Customer();
		$notify = Yii::$app->notify;
		$model->created_by = Yii::$app->user->identity->id;

		if ($model->load(Yii::$app->request->post())) {
		    
		    $model->dob = date('Y-m-d', strtotime($model->dob));
		    $model->date_of_sepa_direct_debit_mandate = date('Y-m-d', strtotime($model->date_of_sepa_direct_debit_mandate));
		    $model->save();

			$title = $model->first_name . ' ' . $model->last_name;

			$notify->addRecentActivity('created', 'Customer', $model->id, 0, $title);
			return $this->redirect(['view', 'id' => $model->id]);
		}

		return $this->render('create', [
			'model' => $model,
		]);
	}

	/**
	 * Updates an existing Customer model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id
	 * @return mixed
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	public function actionUpdate($id) {
		Yii::$app->Utility->checkAccess('customer', 'update');
		$model = $this->findModel($id);
		$notify = Yii::$app->notify;
		$model->updated_by = Yii::$app->user->identity->id;

		if ($model->load(Yii::$app->request->post())) {
		  $model->dob = date('Y-m-d', strtotime($model->dob));
		  $model->date_of_sepa_direct_debit_mandate = date('Y-m-d', strtotime($model->date_of_sepa_direct_debit_mandate));
		    $model->save();
			$title = $model->first_name . ' ' . $model->last_name;
			$notify->addRecentActivity('updated', 'Customer', $model->id, 0, $title);
			return $this->redirect(['view', 'id' => $model->id]);
		}

		return $this->render('update', [
			'model' => $model,
		]);
	}

	public function actionStatus($val, $id) {
		Yii::$app->Utility->checkAccess('customer', 'update');
		$notify = Yii::$app->notify;

		$model = $this->findModel($id);
		$model->status = $val;
		$model->save();
		$status = $val == 1 ? 'enabled' : 'disabled';
		$title = $model->first_name . ' ' . $model->last_name;
		$notify->addRecentActivity($status, 'Customer', $model->id, 0, $title);
		Yii::$app->getSession()->setFlash('successStatus', 'Status updated successfully!');

		return $this->redirect(Yii::$app->request->referrer);

	}

	/**
	 * Deletes an existing Customer model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	public function actionDelete($id) {
		Yii::$app->Utility->checkAccess('customer', 'delete');
		$model = $this->findModel($id);
		$model->trash = 1;
		$model->deleted_by = Yii::$app->user->identity->id;
		$model->deleted_at = date('Y-m-d H:i:s');
		$model->save(false);
		$notify = Yii::$app->notify;
		$title = $model->first_name . ' ' . $model->last_name;
		$notify->addRecentActivity('deleted', 'Customer', $model->id, 0, $title);

		Yii::$app->getSession()->setFlash('successStatus', 'Customer deleted successfully!');

		return $this->redirect(['index']);
	}

	/**
	 * Finds the Customer model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return Customer the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id) {
		if (($model = Customer::findOne($id)) !== null) {
			return $model;
		}

		throw new NotFoundHttpException('The requested page does not exist.');
	}
}

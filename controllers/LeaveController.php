<?php

namespace app\controllers;

use app\models\Leave;
use app\models\User;
use Yii;
use yii\data\Pagination;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * LeaveController implements the CRUD actions for Leave model.
 */
class LeaveController extends Controller {
	public $layout = '@app/themes/admin/main';
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
	 * Lists all Leave models.
	 * @return mixed
	 */
	public function actionIndex() {
		extract($_REQUEST);
		Yii::$app->Utility->checkAccess('leave', 'view');
		$srch = '';
		$status = '';
		$logged_user = Yii::$app->user->identity;
		$pageSize = 10;
		$page_no = isset($page) ? $page : 1;

		$query = Leave::find()->joinWith(['user']);

		if ($logged_user->user_role == 'user') {
			$cond = '`created_by`=' . $logged_user->id;
		} else {
			$cond = '1';
		}
		if ($logged_user->user_role == 'user') {
			$query->where($cond);
		}

		if (isset($search) && $search != '') {
			if ($search == 'Disapprove' || $search == 'disapprove') {
				$status = 1;
			}

			if ($search == 'Pending' || $search == 'pending') {
				$status = null;
			}

			if ($search == 'Approve' || $search == 'approve') {
				$status = 0;
			}
			$query->where(['like', 'tbl_user.first_name', $search]);
			$query->orWhere(['like', 'tbl_user.last_name', $search]);
			$query->orWhere(['like', 'tbl_leave.leave_type', $search]);
			$query->orWhere(['tbl_leave.from_date' => $search]);
			$query->orWhere(['tbl_leave.to_date' => $search]);
			$query->orWhere(['tbl_leave.status' => $status]);
			$srch = $search;
		}

		$countQuery = clone $query;
		$pages = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => $pageSize]);
		$model = $query->offset($pages->offset)
			->limit($pages->limit)
			->orderBy(['created_at' => SORT_DESC])
			->all();
		$serial_number = ($page_no - 1) * $pageSize;

		unset($_SESSION['leave_relation_id']);

		return $this->render('index', [
			'model' => $model,
			'search' => $srch,
			'pages' => $pages,
			'serial_number' => $serial_number,
		]);
	}

	/**
	 * Displays a single Leave model.
	 * @param integer $id
	 * @return mixed
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	public function actionView($id) {
		return $this->redirect(['index']);
	}

	/**
	 * Creates a new Leave model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionCreate() {
		extract($_REQUEST);

		Yii::$app->Utility->checkAccess('leave', 'create');
		$notify = Yii::$app->notify;
		$model = new Leave();
		$model->no_of_days = 0;
		$model->created_by = Yii::$app->user->identity->id;

		//$model->is_full_day = 1;

		if ($model->load(Yii::$app->request->post())) {

			if (isset($_SESSION['leave_relation_id'])) {

				$model->relation_id = $_SESSION['leave_relation_id'];
			}

			$get_address = Yii::$app->getTable->getCurrentLocation($current_latitude, $current_longitude);
			$model->created_location = ($get_address) ? $get_address : NULL;
			if ($model->half_day == 'full-day') {
				$model->is_full_day = 1;
			} else {
				$model->is_full_day = 0;
			}

			$model->from_date = date('Y-m-d', strtotime($model->from_date));
			$model->to_date = date('Y-m-d', strtotime($model->to_date));
			$model->save(false);

			$user = Yii::$app->user->identity;
			if ($user->user_role != 'admin') {
				$user_name = $user->first_name . ' ' . $user->last_name;
				$notify->addNotify(1, 1, 'leave/index', $model->id, $user_name . ' has applied for leave.', 'leave_application');

			}
			$notify->addRecentActivity('created', 'Leave', $model->id);
			unset($_SESSION['leave_relation_id']);
			return $this->redirect(['view', 'id' => $model->id]);
		}

		return $this->render('create', [
			'model' => $model,
		]);
	}

	/**
	 * Updates an existing Leave model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id
	 * @return mixed
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	public function actionUpdate($id) {
		$model = $this->findModel($id);

		$notify = Yii::$app->notify;

		if ($model->load(Yii::$app->request->post())) {

			if ($model->half_day == 'full-day') {
				$model->is_full_day = 1;
			} else {
				$model->is_full_day = 0;
			}

			if ($model->relation_id == '' && isset($_SESSION['leave_relation_id'])) {

				$model->relation_id = $_SESSION['leave_relation_id'];
			}
			$model->from_date = date('Y-m-d', strtotime($model->from_date));
			$model->to_date = date('Y-m-d', strtotime($model->to_date));
			$model->save();

			$create_for = User::find()->where(['id' => $model->created_by])->one();
			$title = $create_for['first_name'] . ' ' . $create_for['last_name'];

			$notify->addRecentActivity('updated', 'Leave', $model->id, $model->created_by, $title);

			unset($_SESSION['leave_relation_id']);
			return $this->redirect(['view', 'id' => $model->id]);
		}

		return $this->render('update', [
			'model' => $model,
		]);
	}

	public function actionStatus($id, $val) {
		Yii::$app->Utility->checkAccess('leave', 'update');
		$notify = Yii::$app->notify;

		$model = $this->findModel($id);
		$model->status = $val;
		//if ($model->updateRemainingLeaves()) {
		$model->save(false);

		if ($val == 1) {
			$status = 'approved';

		} else {
			$status = 'disapproved';
		}

		$user = Yii::$app->user->identity;
		if ($user->user_role == 'admin') {
			$notify->addNotify($model->created_by, 1, 'leave/index', $model->id, 'Your leave has been ' . $status . ' by admin.', 'leave_status');
		}

		$create_for = User::find()->where(['id' => $model->created_by])->one();
		$title = $create_for['first_name'] . ' ' . $create_for['last_name'];

		$notify->addRecentActivity($status, 'Leave', $model->id, $model->created_by, $title);

		Yii::$app->getSession()->setFlash('successStatus', 'Leave ' . $status . '  successfully!');
		/*} else {
			Yii::$app->getSession()->setFlash('errorStatus', 'Not enough remaining leaves hours for this user');
		}*/

		return $this->redirect(Yii::$app->request->referrer);
	}

	/**
	 * Deletes an existing Leave model.
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
	 * Finds the Leave model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return Leave the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id) {
		if (($model = Leave::findOne($id)) !== null) {
			return $model;
		}

		throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
	}
}

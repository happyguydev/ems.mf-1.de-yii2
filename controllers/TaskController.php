<?php

namespace app\controllers;

use app\models\Notification;
use app\models\Task;
use app\models\TaskAssignee;
use Yii;
use yii\data\Pagination;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * TaskController implements the CRUD actions for Task model.
 */
class TaskController extends Controller {
	public $layout = '@app/themes/admin/main';
	public $enableCsrfValidation = false;
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
	 * Lists all Task models.
	 * @return mixed
	 */
	public function actionIndex() {
		extract($_REQUEST);
		Yii::$app->Utility->checkAccess('task', 'view');
		$srch = '';
		$status = '';
		$logged_user = Yii::$app->user->identity;
		$pageSize = 10;
		$page_no = isset($page) ? $page : 1;
		$get_assign_tasks = Yii::$app->getTable->get_logged_in_user_task();
		$query = Task::find()->where(['trash' => 0]);
		if ($logged_user->user_role != 'admin') {
			$cond = '`created_by`=' . $logged_user->id;
		} else {
			$cond = '1';
		}
		if ($logged_user->user_role != 'admin') {
			$query->andWhere($cond)->orWhere(['IN', 'id', $get_assign_tasks]);
		}
		if (isset($search) && $search != '') {
			if ($search == 'In Progress' || $search == 'in progress') {
				$status = 'in-progress';
			}
			$query->andWhere(['like', 'name', $search]);
			$query->orWhere(['start_date' => $search]);
			$query->orWhere(['end_date' => $search]);
			$query->orWhere(['like', 'status', $search]);
			$srch = $search;
		}

		$countQuery = clone $query;
		$pages = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => $pageSize]);
		$model = $query->offset($pages->offset)
			->limit($pages->limit)
			->all();

		$serial_number = ($page_no - 1) * $pageSize;
		unset($_SESSION['task_relation_id']);

		return $this->render('index', [
			'model' => $model,
			'search' => $srch,
			'pages' => $pages,
			'serial_number' => $serial_number,
		]);
	}

	/**
	 * Displays a single Task model.
	 * @param integer $id
	 * @return mixed
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	public function actionView($id) {
		Yii::$app->Utility->checkAccess('task', 'view');
		unset($_SESSION['task_relation_id']);

		$user_id = Yii::$app->user->identity->id;

		$unread_task_notification = Notification::find()->where(['item_id' => $id])->andWhere(['read' => 1])->andWhere(['user_id' => $user_id])->all();

		if (count($unread_task_notification) > 0) {
			foreach ($unread_task_notification as $key => $value) {
				$value->read = 0;
				$value->save();
			}
		}

		return $this->render('view', [
			'model' => $this->findModel($id),
		]);
	}

	/**
	 * Creates a new Task model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionCreate() {
		Yii::$app->Utility->checkAccess('task', 'create');
		$notify = Yii::$app->notify;
		$model = new Task();
		$model->created_by = Yii::$app->user->identity->id;
		$user = Yii::$app->user->identity;

		if ($model->load(Yii::$app->request->post())) {

			if (isset($_SESSION['task_relation_id'])) {

				$model->relation_id = $_SESSION['task_relation_id'];
			}
			$model->start_date = date('Y-m-d', strtotime($model->start_date));
			 $model->end_date = date('Y-m-d', strtotime($model->end_date));

			if ($model->save()) {

				if (!empty($model->assign_to)) {
					foreach ($model->assign_to as $key => $value) {
						$assign = new TaskAssignee();
						$assign->task_id = $model->id;
						$assign->assign_to = $value;
						$assign->assign_at = date('Y-m-d H:i:s');
						$assign->save();
						if ($assign->assign_to != $user->id) {
							$notify->addNotify($assign->assign_to, 1, 'task/view', $model['id'], 'A new task assigned to you by ' . $model->getCreatedBy(), 'assign_task');
							$notify->addRecentActivity('assigned', 'Task', $model->id, $assign->assign_to, $model->name);
						}
					}
				}

				if ($user->user_role != 'admin') {
					$user_name = $user->first_name . ' ' . $user->last_name;
					$notify->addNotify(1, 1, 'task/view', $model->id, $user_name . ' has added one new task.', 'add_task');
				}
				$notify->addRecentActivity('created', 'Task', $model->id, $model->created_by, $model->name);
				unset($_SESSION['task_relation_id']);
			}
			return $this->redirect(['view', 'id' => $model->id]);
		}

		return $this->render('create', [
			'model' => $model,
		]);
	}

	/**
	 * Updates an existing Task model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id
	 * @return mixed
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	public function actionUpdate($id) {
		Yii::$app->Utility->checkAccess('task', 'update');
		$notify = Yii::$app->notify;
		$model = $this->findModel($id);
		$user = Yii::$app->user->identity;

		$model->updated_by = Yii::$app->user->identity->id;

		$get_assignees = TaskAssignee::find()->where(['task_id' => $id])->all();
		$assignee_ids = [];
		if (count($get_assignees) > 0) {
			foreach ($get_assignees as $key => $value) {
				$assignee_ids[] = $value['assign_to'];
			}
		}
		$model->assign_to = $assignee_ids;

		if ($model->load(Yii::$app->request->post())) {

			if ($model->relation_id == '' && isset($_SESSION['task_relation_id'])) {

				$model->relation_id = $_SESSION['task_relation_id'];
			}
			$model->start_date = date('Y-m-d', strtotime($model->start_date));
			$model->end_date = date('Y-m-d', strtotime($model->end_date));
			if (count($get_assignees) > 0) {
				foreach ($get_assignees as $dk => $dv) {
					$dv->delete();
				}
			}
			if (!empty($model->assign_to)) {
				foreach ($model->assign_to as $key1 => $value1) {
					$assign = new TaskAssignee();
					$assign->task_id = $model->id;
					$assign->assign_to = $value1;
					$assign->assign_at = date('Y-m-d H:i:s');
					$assign->save();
					if (!in_array($value1, $assignee_ids) && $user->id != $model->created_by) {
						$notify->addNotify($assign->assign_to, 1, 'task/view', $id, 'A new task assigned to you by ' . $model->getUpdatedBy(), 'assign_task');
						//	$notify->addRecentActivity('assigned', 'Task', $model->id, $assign->assign_to, $model->name);
					} else {
						if ($user->id != $model->updated_by) {
							$notify->addNotify($assign->assign_to, 1, 'task/view', $id, 'There are few changes made in ' . $model['name'] . ' by ' . $model->getUpdatedBy(), 'update_task');
							//	$notify->addRecentActivity('updated', 'Task', $model->id, $model->assign_to, $model->name);
						}
					}
				}
			}
			$model->save();
			if ($user->user_role != 'admin') {
				$user_name = $user->first_name . ' ' . $user->last_name;
				$notify->addNotify(1, 1, 'task/view', $id, $user_name . ' has made few changes in ' . $model['name'] . ' task.', 'update_task');
			}

			$notify->addRecentActivity('updated', 'Task', $model->id, $model->created_by, $model->name);
			unset($_SESSION['task_relation_id']);
			return $this->redirect(['view', 'id' => $model->id]);
		}

		return $this->render('update', [
			'model' => $model,
		]);
	}

	public function actionStatus($id, $val) {
		Yii::$app->Utility->checkAccess('task', 'update');

		$notify = Yii::$app->notify;
		$user = Yii::$app->user->identity;

		$model = $this->findModel($id);
		$model->status = $val;
		$model->save(false);
		$assignee_ids = [];
		$assignees = TaskAssignee::find()->where(['task_id' => $model->id])->all();

		foreach ($assignees as $k => $v) {
			$assignee_ids[] = $v['assign_to'];
		}
		if ($model->status == 'completed' && $user->user_role != 'admin') {
			$user_name = $user->first_name . ' ' . $user->last_name;
			$notify->addNotify(1, 1, 'task/view', $id, $user_name . ' has completed ' . $model['name'] . ' task', 'task_completed');
			$notify->addRecentActivity('completed', 'Task', $model->id, $assignee_ids, $model->name);
		} else {

			$notify->addRecentActivity($val, 'Task', $model->id, $assignee_ids, $model->name);

		}
		Yii::$app->getSession()->setFlash('successStatus', 'Status updated successfully!');

		return $this->redirect(Yii::$app->request->referrer);

	}

	/**
	 * Deletes an existing Task model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	public function actionDelete($id) {
		Yii::$app->Utility->checkAccess('task', 'delete');
		$notify = Yii::$app->notify;
		$model = $this->findModel($id);
		$model->trash = 1;
		$model->deleted_by = Yii::$app->user->identity->id;
		$model->deleted_at = date('Y-m-d H:i:s');
		$model->save(false);
		/*$assignee_ids = [];
			$assignees = TaskAssignee::find()->where(['task_id' => $model->id])->all();

			foreach ($assignees as $k => $v) {
				$assignee_ids[] = $v['assign_to'];
		*/

		$notify->addRecentActivity('deleted', 'Task', $model->id, 0, $model->name);

		Yii::$app->getSession()->setFlash('successStatus', 'Task deleted successfully!');

		return $this->redirect(['index']);
	}

	/**
	 * Finds the Task model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return Task the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id) {
		if (($model = Task::findOne($id)) !== null) {
			return $model;
		}

		throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
	}
}

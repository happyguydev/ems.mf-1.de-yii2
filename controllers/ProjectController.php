<?php

namespace app\controllers;

use app\models\Notification;
use app\models\Project;
use app\models\ProjectAssignee;
use Yii;
use yii\data\Pagination;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * ProjectController implements the CRUD actions for Project model.
 */
class ProjectController extends Controller {
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
	 * Lists all Project models.
	 * @return mixed
	 */
	public function actionIndex() {
		extract($_REQUEST);
		Yii::$app->Utility->checkAccess('project', 'view');
		$srch = '';
		$status = '';
		$logged_user = Yii::$app->user->identity;
		$pageSize = 10;
		$page_no = isset($page) ? $page : 1;
		$get_assign_projects = Yii::$app->getTable->get_logged_in_user_project();

		if ($logged_user->user_role != 'admin') {
			$cond = '`created_by`= ' . $logged_user->id;
		} else {
			$cond = '1';
		}

		$query = Project::find()->where(['trash' => 0]);
		if ($logged_user->user_role != 'admin') {
			$query->andWhere($cond)->orWhere(['IN', 'id', $get_assign_projects]);
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
		unset($_SESSION['project_relation_id']);

		return $this->render('index', [
			'model' => $model,
			'search' => $srch,
			'pages' => $pages,
			'serial_number' => $serial_number,
		]);
	}

	/**
	 * Displays a single Project model.
	 * @param integer $id
	 * @return mixed
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	public function actionView($id) {
		Yii::$app->Utility->checkAccess('project', 'view');
		$user_id = Yii::$app->user->identity->id;

		$unread_project_notification = Notification::find()->where(['item_id' => $id])->andWhere(['read' => 1])->andWhere(['user_id' => $user_id])->all();

		if (count($unread_project_notification) > 0) {
			foreach ($unread_project_notification as $key => $value) {
				$value->read = 0;
				$value->save();
			}
		}
		unset($_SESSION['project_relation_id']);
		return $this->render('view', [
			'model' => $this->findModel($id),
		]);
	}

	/**
	 * Creates a new Project model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionCreate() {
		Yii::$app->Utility->checkAccess('project', 'create');
		$notify = Yii::$app->notify;
		$model = new Project();
		$model->created_by = Yii::$app->user->identity->id;
		$user = Yii::$app->user->identity;

		if ($model->load(Yii::$app->request->post())) {
			if (isset($_SESSION['project_relation_id'])) {

				$model->relation_id = $_SESSION['project_relation_id'];
			}
			
			  $model->start_date = date('Y-m-d', strtotime($model->start_date));
			  $model->end_date = date('Y-m-d', strtotime($model->end_date));

			if ($model->save()) {

				if (!empty($model->team)) {
					foreach ($model->team as $key => $value) {
						$assign = new ProjectAssignee();
						$assign->project_id = $model->id;
						$assign->user_id = $value;
						$assign->save();

						if ($assign->user_id != $user->id) {
							$notify->addNotify($assign->user_id, 1, 'project/view', $model['id'], 'A new project assigned to you by ' . $model->getCreatedBy() . '.', 'assign_project');
							//$notify->addRecentActivity('assigned', 'Project', $model->id, $assign->user_id, $model->name);

						}

					}
				}

				$user_name = $user->first_name . ' ' . $user->last_name;

				if ($user->user_role != 'admin') {
					$notify->addNotify(1, 1, 'project/view', $model->id, $user_name . ' has added one new project.', 'add_project');
				}

				$notify->addRecentActivity('created', 'Project', $model->id, $model->created_by, $model->name);
				unset($_SESSION['project_relation_id']);
			}
			return $this->redirect(['view', 'id' => $model->id]);
		}

		return $this->render('create', [
			'model' => $model,
		]);
	}

	/**
	 * Updates an existing Project model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id
	 * @return mixed
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	public function actionUpdate($id) {
		Yii::$app->Utility->checkAccess('project', 'update');
		$model = $this->findModel($id);
		$notify = Yii::$app->notify;
		$model->updated_by = Yii::$app->user->identity->id;
		$user = Yii::$app->user->identity;

		$get_assignees = ProjectAssignee::find()->where(['project_id' => $id])->all();
		$assignee_ids = [];
		if (count($get_assignees) > 0) {
			foreach ($get_assignees as $key => $value) {
				$assignee_ids[] = $value['user_id'];
			}
		}
		$model->team = $assignee_ids;

		if ($model->load(Yii::$app->request->post())) {
			if ($model->relation_id == '' && isset($_SESSION['project_relation_id'])) {

				$model->relation_id = $_SESSION['project_relation_id'];
			}
			if (count($get_assignees) > 0) {
				foreach ($get_assignees as $dk => $dv) {
					$dv->delete();
				}
			}
			if (!empty($model->team)) {
				foreach ($model->team as $key1 => $value1) {
					$assign = new ProjectAssignee();
					$assign->project_id = $model->id;
					$assign->user_id = $value1;
					$assign->save();
					if (!in_array($value1, $assignee_ids) && $user->id != $model->created_by) {
						$notify->addNotify($assign->user_id, 1, 'project/view', $id, 'A new project assigned to you by ' . $model->getUpdatedBy() . '.', 'assign_project');
						//$notify->addRecentActivity('assigned', 'Project', $model->id, $assign->user_id, $model->name);
					} else {
						if ($user->id != $model->updated_by) {
							$notify->addNotify($assign->user_id, 1, 'project/view', $id, 'There are few changes made in ' . $model['name'] . ' by ' . $model->getUpdatedBy() . '.', 'update_project');

							//$notify->addRecentActivity('updated', 'Project', $model->id, $assign->user_id, $model->name);
						}

					}
				}
			}
			 $model->start_date = date('Y-m-d', strtotime($model->start_date));
			  $model->end_date = date('Y-m-d', strtotime($model->end_date));
			$model->save();
			if ($user->user_role != 'admin') {
				$user_name = $user->first_name . ' ' . $user->last_name;
				$notify->addNotify(1, 1, 'project/view', $id, $user_name . ' has made few changes in ' . $model['name'] . ' project.', 'update_project');
			}

			$notify->addRecentActivity('updated', 'Project', $model->id, $model->created_by, $model->name);
			unset($_SESSION['project_relation_id']);
			return $this->redirect(['view', 'id' => $model->id]);
		}

		return $this->render('update', [
			'model' => $model,
		]);
	}

	public function actionStatus($id, $val) {
		Yii::$app->Utility->checkAccess('project', 'update');
		$user = Yii::$app->user->identity;
		$notify = Yii::$app->notify;
		$model = $this->findModel($id);
		$model->status = $val;
		$model->save(false);
		$user_name = $user->first_name . ' ' . $user->last_name;

		$assignees = ProjectAssignee::find()->where(['project_id' => $model->id])->all();

		foreach ($assignees as $k => $v) {
			$assignee_ids[] = $v['user_id'];
		}

		if ($model->status == 'completed' && $user->user_role != 'admin') {
			$notify->addNotify(1, 1, 'project/view', $id, $user_name . ' has completed ' . $model['name'] . ' project.', 'project_completed');
			$notify->addRecentActivity('completed', 'Project', $model->id, $assignee_ids);
		} else {

			$notify->addRecentActivity($val, 'Project', $model->id, 0, $model->name);

		}
		Yii::$app->getSession()->setFlash('successStatus', 'Status updated successfully!');

		return $this->redirect(Yii::$app->request->referrer);

	}

	/**
	 * Deletes an existing Project model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	public function actionDelete($id) {
		Yii::$app->Utility->checkAccess('project', 'delete');
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
			}
		*/
		$notify->addRecentActivity('deleted', 'Project', $model->id, 0, $model->name);

		Yii::$app->getSession()->setFlash('successStatus', 'Project deleted successfully!');

		return $this->redirect(['index']);
	}

	/**
	 * Finds the Project model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return Project the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id) {
		if (($model = Project::findOne($id)) !== null) {
			return $model;
		}

		throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
	}
}

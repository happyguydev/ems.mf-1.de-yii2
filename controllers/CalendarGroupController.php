<?php

namespace app\controllers;

use app\models\CalendarGroup;
use app\models\CalenderGroupAssignee;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * CalendarGroupController implements the CRUD actions for CalendarGroup model.
 */
class CalendarGroupController extends Controller {

	/**
	 * Lists all CalendarGroup models.
	 * @return mixed
	 */
	public function actionIndex() {
		$dataProvider = new ActiveDataProvider([
			'query' => CalendarGroup::find(),
		]);

		return $this->render('index', [
			'dataProvider' => $dataProvider,
		]);
	}

	/**
	 * Displays a single CalendarGroup model.
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
	 * Creates a new CalendarGroup model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionCreate() {
		$model = new CalendarGroup();
		$model->date = date('Y-m-d');
		$user = Yii::$app->user->identity;
		$notify = Yii::$app->notify;
		$model->created_by = Yii::$app->user->identity->id;

		if ($model->load(Yii::$app->request->post()) && $model->save()) {

			if (!empty($model->assign_to)) {
				foreach ($model->assign_to as $key => $value) {
					$assign = new CalenderGroupAssignee();
					$assign->group_id = $model->id;
					$assign->user_id = $value;
					$assign->save();
					if ($assign->user_id != $user->id) {
						$notify->addNotify($assign->user_id, 1, 'calendar/index', $model['id'], 'A new Calendar Group has assigned to you', 'assign_calender_group');
						$notify->addRecentActivity('assigned', 'Calendar Group', $model->id, $assign->user_id, $model->title);
					}
				}
			}
			Yii::$app->getSession()->setFlash('groupStatus', 'Group added successfully!');
			return $this->redirect(Yii::$app->request->referrer);
		}

		return $this->renderAjax('create', [
			'model' => $model,
		]);
	}

	/**
	 * Updates an existing CalendarGroup model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id
	 * @return mixed
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	public function actionUpdate($id) {
		$model = $this->findModel($id);
		$user = Yii::$app->user->identity;
		$notify = Yii::$app->notify;

		$get_assignees = CalenderGroupAssignee::find()->where(['group_id' => $id])->all();
		$assignee_ids = [];
		if (count($get_assignees) > 0) {
			foreach ($get_assignees as $key => $value) {
				$assignee_ids[] = $value['user_id'];
			}
		}
		$model->assign_to = $assignee_ids;

		if ($model->load(Yii::$app->request->post()) && $model->save()) {

			if (count($get_assignees) > 0) {
				foreach ($get_assignees as $dk => $dv) {
					$dv->delete();
				}
			}
			if (!empty($model->assign_to)) {
				foreach ($model->assign_to as $key1 => $value1) {
					$assign = new CalenderGroupAssignee();
					$assign->group_id = $model->id;
					$assign->user_id = $value1;

					$assign->save();
					if (!in_array($value1, $assignee_ids) && $user->id != $model->created_by) {
						$notify->addNotify($assign->user_id, 1, 'calendar/index', $model['id'], 'A new Calendar Group has assigned to you ', 'assign_calender_group');

						//	$notify->addRecentActivity('assigned', 'Task', $model->id, $assign->assign_to, $model->name);
					} else {
						//if ($user->id != $model->updated_by) {

						$notify->addNotify($assign->user_id, 1, 'calendar/index', $model['id'], 'There are few changes made in ' . $model['title'], 'assign_calender_group');
						//	$notify->addRecentActivity('updated', 'Task', $model->id, $model->assign_to, $model->name);
						//}
					}
				}
			}

			Yii::$app->getSession()->setFlash('groupStatus', 'Group updated successfully!');
			return $this->redirect(Yii::$app->request->referrer);
		}

		return $this->renderAjax('update', [
			'model' => $model,
		]);
	}

	/**
	 * Deletes an existing CalendarGroup model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	public function actionDelete($id) {
		$this->findModel($id)->delete();

		return $this->redirect(Yii::$app->request->referrer);
	}

	/**
	 * Finds the CalendarGroup model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return CalendarGroup the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id) {
		if (($model = CalendarGroup::findOne($id)) !== null) {
			return $model;
		}

		throw new NotFoundHttpException('The requested page does not exist.');
	}
}

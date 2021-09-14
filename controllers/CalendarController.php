<?php

namespace app\controllers;

use app\models\Appointment;
use app\models\AppointmentAssignee;
use app\models\Leave;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;

/**
 * CalendarController implements the CRUD actions for Leave model.
 */
class CalendarController extends Controller {
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

	public function actionIndex($groupId = 1) {
		return $this->render('index');
	}
	public function actionSaveData() {
		extract($_REQUEST);
		$logged_user = Yii::$app->user->identity;
		$notify = Yii::$app->notify;
		$model = new Appointment();
		$model->title = $title;
		$model->group_id = $group;
		$model->start_date_time = date('Y-m-d H:i:s', strtotime($start_date));
		$model->end_date_time = date('Y-m-d H:i:s', strtotime($end_date));
		$model->bg_color = $bg_color;
		$model->text_color = $text_color;
		$model->created_by = $logged_user->id;
		if ($model->save()) {
			if (isset($assignee) && !empty($assignee)) {
				foreach ($assignee as $key => $value) {
					$assign = new AppointmentAssignee();
					$assign->appointment_id = $model->id;
					$assign->user_id = $value;
					$assign->save();
					$notify->addNotify($assign->user_id, 1, 'calendar/index', 0, 'A new appointment ' . $title . ' assigned to you by ' . $logged_user->first_name . ' ' . $logged_user->last_name . '.', 'assign_appointment');
					//$notify->addRecentActivity('assigned', 'Appointment', $model->id, $assign->user_id, $model->title);
				}
			}

			$notify->addRecentActivity('created', 'Appointment', $model->id, 0, $model->title);
			$response = array('success' => true, 'message' => 'Appointment Created Successfully!');
		} else {
			$response = array('success' => false, 'message' => 'Something Went Wrong!');
		}

		\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		return $response;

	}

	public function actionUpdateData($id) {
		extract($_REQUEST);
		$assignee_ids = [];
		$logged_user = Yii::$app->user->identity;
		$notify = Yii::$app->notify;

		$model = Appointment::find()->where(['id' => $id])->one();
		$model->group_id = isset($group) && $group != '' ? $group : $model->group_id;
		$model->title = isset($title) && $title != '' ? $title : $model->title;
		$model->start_date_time = date('Y-m-d H:i:s', strtotime($start));
		$model->end_date_time = date('Y-m-d H:i:s', strtotime($end));
		$model->bg_color = isset($color) && $color != '' ? $color : $model->bg_color;
		$model->text_color = isset($text_color) && $text_color != '' ? $text_color : $model->text_color;
		$model->updated_by = Yii::$app->user->identity->id;
		if ($model->save()) {
			$old_assignees = AppointmentAssignee::find()->where(['appointment_id' => $id])->all();
			if (count($old_assignees) > 0) {
				foreach ($old_assignees as $key1 => $value1) {
					$assignee_ids[] = $value1['user_id'];
				}
				foreach ($old_assignees as $dk => $dv) {
					$dv->delete();
				}
			}
			if (isset($assignee) && !empty($assignee)) {

				foreach ($assignee as $key => $value) {
					$assign = new AppointmentAssignee();
					$assign->appointment_id = $model->id;
					$assign->user_id = $value;
					$assign->save();
					if (!in_array($value, $assignee_ids)) {
						$notify->addNotify($assign->user_id, 1, 'calendar/index', 0, 'A new appointment assigned to you by ' . $logged_user->first_name . ' ' . $logged_user->last_name . '.', 'assign_appointment');

						//$notify->addRecentActivity('assigned', 'Appointment', $model->id, $assign->user_id, $model->title);
					} else {
						$notify->addNotify($assign->user_id, 1, 'calendar/index', 0, 'There are few changes made in appointment' . $model->title . ' by ' . $logged_user->first_name . ' ' . $logged_user->last_name . '.', 'update_appointment');
						//$notify->addRecentActivity('updated', 'Appointment', $model->id, $assign->user_id, $model->title);
					}

				}
			}

			$notify->addRecentActivity('updated', 'Appointment', $model->id, 0, $model->title);
			$response = array('success' => true, 'message' => 'Appointment Updated Successfully!');
		} else {
			$response = array('success' => false, 'message' => 'Something Went Wrong!');
		}

		\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		return $response;

	}

	public function actionDeleteData() {
		extract($_REQUEST);
		$notify = Yii::$app->notify;
		$model = Appointment::find()->where(['id' => $id])->one();

		$model_id = $model['id'];
		$title = $model['title'];
		//$assignee_ids = [];
		/*$assignees = AppointmentAssignee::find()->where(['appointment_id' => $model_id])->all();

		foreach ($assignees as $k => $v) {
			$assignee_ids[] = $v['user_id'];
		}*/

		if ($model->delete()) {
			$notify->addRecentActivity('deleted', 'Appointment', $model_id, 0, $title);
			$response = array('success' => true, 'message' => 'Appointment Deleted Successfully!');
		} else {
			$response = array('success' => false, 'message' => 'Something Went Wrong!');
		}
		\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		return $response;
	}

	public function actionShowData($groupId = 0) {
		$user_role = Yii::$app->user->identity->user_role;
		$user_id = Yii::$app->user->identity->id;
		$get_appointment_ids = $this->getAssignees();
		if ($user_role != 'admin') {
			$cond = '`created_by`=' . $user_id . ' OR id IN(' . $get_appointment_ids . ')';
		} else {
			$cond = '1';
		}
		if($groupId != 0){
		    $cond .= ' AND group_id = '.$groupId;
		}

		$model = Appointment::find()->where($cond)->all();
		$model_array = [];
		$ids = [];

		if (count($model) > 0) {

			foreach ($model as $row) {
				$assignee = AppointmentAssignee::find()->where(['appointment_id' => $row['id']])->all();
				if (count($assignee) > 0) {
					foreach ($assignee as $key => $value) {

						$ids[] = $value['user_id'];

					}
				}
				$model_array[] = [
					'id' => $row->id,
					'title' => $row->title,
					'group' => $row->group_id,
					'start' => $row->start_date_time,
					'end' => $row->end_date_time,
					'color' => $row->bg_color,
					'textColor' => $row->text_color,
					'assignee' => $ids,
				];
			}
		}
		return json_encode($model_array);
	}

	public function actionGetData() {
		extract($_REQUEST);
		$data = [];
		if (isset($id)) {
			$model = Appointment::find()->where(['id' => $id])->one();
			if ($model->created_by == Yii::$app->user->identity->id || Yii::$app->user->identity->user_role == 'admin') {
				$show_delete = 1;
			} else {
				$show_delete = 0;
			}

			$assignee = AppointmentAssignee::find()->where(['appointment_id' => $model['id']])->all();

			$ids = [];
			if (count($assignee) > 0) {
				foreach ($assignee as $key => $value) {

					$ids[] = $value['user_id'];

				}
			}
			
		$model->start_date_time = date('d.m.Y H:i:s', strtotime($model->start_date_time));
		$model->end_date_time = date('d.m.Y H:i:s', strtotime(	$model->end_date_time));
			$data = [
				'id' => $model->id,
				'title' => $model->title,
				'group' => $model->group_id,
				'start' => $model->start_date_time,
				'end' => $model->end_date_time,
				'color' => $model->bg_color,
				'textColor' => $model->text_color,
				'assignee' => $ids,
				'showDelete' => $show_delete,
			];

			return json_encode($data);
		} else {
			return $data;
		}

	}
	public function getAssignees() {
		$ids = 0;
		$user_id = Yii::$app->user->identity->id;
		$model = AppointmentAssignee::find()->where(['user_id' => $user_id])->all();
		if (count($model) > 0) {
			foreach ($model as $key => $value) {
				$ids .= ',' . $value['appointment_id'];
			}
		}

		return $ids;
	}

}

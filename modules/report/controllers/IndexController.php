<?php

namespace app\modules\report\controllers;

use app\models\Attendance;
use app\models\Customer;
use app\models\Project;
use app\models\Task;
use app\models\User;
use Yii;
use yii\data\Pagination;
use yii\web\Controller;

/**
 * Index controller for the `report` module
 */
class IndexController extends Controller {
	public $enableCsrfValidation = false;
	/**
	 * Renders the index view for the module
	 * @return string
	 */

	/* attendance report */
	public function actionAttendance() {

		/*$attendance = new Attendance();
		$attendance->autoLogout();*/

		extract($_REQUEST);
		$response = [];
		$pageSize = 10;
		$page_no = isset($page) ? $page : 1;
		if (isset($startdate) || isset($enddate)) {

			if ($startdate != '' && $enddate != '') {

				$st1 = date('Y-m-d H:i:s', strtotime($startdate));
				$st2 = date('Y-m-d H:i:s', strtotime($enddate));
				//$cond = " `Createdate` BETWEEN '$st1' AND '$st2'";

			} else {
				$st1 = date('Y-m-01 00:00:00', strtotime('this month'));
				$st2 = date('Y-m-t 12:59:59', strtotime('this month'));
			}

		} else {
			$st1 = date('Y-m-01 00:00:00', strtotime('this month'));
			$st2 = date('Y-m-t 12:59:59', strtotime('this month'));
		}

		$st11 = date('m/d/Y', strtotime($st1));
		$st22 = date('m/d/Y', strtotime($st2));
		$cond = " `tbl_attendance`.`date` BETWEEN '$st1' AND '$st2'";
		$user = Yii::$app->user->identity;
		if (isset($employee) && $employee != '') {
			$cond .= ' AND `user_id`=' . $employee;
			$employee_id = $employee;
		} else {
			$employee_id = '';
		}

		$query = Attendance::find()->where($cond)->orderBy(['check_in' => SORT_DESC]);

		if ($user->user_role != 'admin') {
			$query->andWhere(['user_id' => $user->id]);
		}

		$countQuery = clone $query;
		$pages = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => $pageSize]);
		$model = $query->offset($pages->offset)
			->limit($pages->limit)
			->all();

		$serial_number = ($page_no - 1) * $pageSize;

		return $this->render('attendance',
			[
				'model' => $model,
				'startdate' => date('Y-m-d', strtotime($st1)),
				'enddate' => date('Y-m-d', strtotime($st2)),
				's_no' => $serial_number,
				'pages' => $pages,
				'employee_id' => $employee_id,
			]);
	}

	/* leave report */

	public function actionLeave() {

		extract($_REQUEST);

		$year = isset($year) ? $year : date('Y');

		$pageSize = 10;
		$page_no = isset($page) ? $page : 1;
		$cond = '';
		$user = Yii::$app->user->identity;
		if (isset($user_id) && $user_id != '' && $user_id != 'all') {
			$cond .= ' `id`=' . $user_id;
			$userId = $user_id;
		} else {
			$cond = '1';
			$userId = 'all';
		}

		$query = User::find()->where(['status' => 1])->andWhere($cond);

		if ($user->user_role != 'admin') {
			$query->andWhere(['id' => $user->id]);
		}

		$countQuery = clone $query;
		$pages = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => $pageSize]);
		$model = $query->offset($pages->offset)
			->limit($pages->limit)
			->all();

		$serial_number = ($page_no - 1) * $pageSize;

		return $this->render('leave',
			[
				'model' => $model,
				's_no' => $serial_number,
				'year' => $year,
				'pages' => $pages,
				'user_id' => $userId,
			]);
	}

	/* Project Report */

	public function actionProject() {

		extract($_REQUEST);
		$response = [];
		$pageSize = 10;
		$page_no = isset($page) ? $page : 1;
		if (isset($startdate) || isset($enddate)) {

			if ($startdate != '' && $enddate != '') {

				$st1 = date('Y-m-d H:i:s', strtotime($startdate));
				$st2 = date('Y-m-d H:i:s', strtotime($enddate));
				//$cond = " `Createdate` BETWEEN '$st1' AND '$st2'";

			} else {
				$st1 = date('Y-m-01 00:00:00', strtotime('this month'));
				$st2 = date('Y-m-t 12:59:59', strtotime('this month'));
			}

		} else {
			$st1 = date('Y-m-01 00:00:00', strtotime('this month'));
			$st2 = date('Y-m-t 12:59:59', strtotime('this month'));
		}

		$st11 = date('m/d/Y', strtotime($st1));
		$st22 = date('m/d/Y', strtotime($st2));
		$cond = " `tbl_project`.`start_date` BETWEEN '$st1' AND '$st2' AND `tbl_project`.`end_date` BETWEEN '$st1' AND '$st2'";
		$user = Yii::$app->user->identity;

		$get_assign_projects = Yii::$app->getTable->get_logged_in_user_project();

		$assignees = 0;

		if ($get_assign_projects) {
			foreach ($get_assign_projects as $key1 => $value1) {
				$assignees .= ',' . $value1;
			}
		}

		$query = Project::find()->where($cond);

		if ($user->user_role != 'admin') {
			$cond1 = '`created_by`=' . $user->id . ' OR `id` IN (' . $assignees . ')';
			$query->andWhere($cond1);
		}

		$countQuery = clone $query;
		$pages = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => $pageSize]);
		$model = $query->offset($pages->offset)
			->limit($pages->limit)
			->all();

		$serial_number = ($page_no - 1) * $pageSize;

		return $this->render('project',
			[
				'model' => $model,
				'startdate' => date('Y-m-d', strtotime($st1)),
				'enddate' => date('Y-m-d', strtotime($st2)),
				's_no' => $serial_number,
				'pages' => $pages,
			]);

	}

	/* Task Report */

	public function actionTask() {

		extract($_REQUEST);
		$response = [];
		$pageSize = 10;
		$page_no = isset($page) ? $page : 1;
		if (isset($startdate) || isset($enddate)) {

			if ($startdate != '' && $enddate != '') {

				$st1 = date('Y-m-d H:i:s', strtotime($startdate));
				$st2 = date('Y-m-d H:i:s', strtotime($enddate));
				//$cond = " `Createdate` BETWEEN '$st1' AND '$st2'";

			} else {
				$st1 = date('Y-m-01 00:00:00', strtotime('this month'));
				$st2 = date('Y-m-t 12:59:59', strtotime('this month'));
			}

		} else {
			$st1 = date('Y-m-01 00:00:00', strtotime('this month'));
			$st2 = date('Y-m-t 12:59:59', strtotime('this month'));
		}

		$st11 = date('m/d/Y', strtotime($st1));
		$st22 = date('m/d/Y', strtotime($st2));
		$cond = " `tbl_task`.`start_date` BETWEEN '$st1' AND '$st2' AND `tbl_task`.`end_date` BETWEEN '$st1' AND '$st2'";
		$user = Yii::$app->user->identity;

		$get_assign_tasks = Yii::$app->getTable->get_logged_in_user_task();

		$assignees = 0;

		if ($get_assign_tasks) {
			foreach ($get_assign_tasks as $key1 => $value1) {
				$assignees .= ',' . $value1;
			}
		}

		$query = Task::find()->where($cond);

		if ($user->user_role != 'admin') {
			$cond1 = '`created_by`=' . $user->id . ' OR `id` IN (' . $assignees . ')';
			$query->andWhere($cond1);
		}

		$countQuery = clone $query;
		$pages = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => $pageSize]);
		$model = $query->offset($pages->offset)
			->limit($pages->limit)
			->all();

		$serial_number = ($page_no - 1) * $pageSize;

		return $this->render('task',
			[
				'model' => $model,
				'startdate' => date('Y-m-d', strtotime($st1)),
				'enddate' => date('Y-m-d', strtotime($st2)),
				's_no' => $serial_number,
				'pages' => $pages,
			]);

	}

	/* Customer Report */

	public function actionCustomer() {

		extract($_REQUEST);
		$response = [];
		$pageSize = 10;
		$page_no = isset($page) ? $page : 1;
		if (isset($startdate) || isset($enddate)) {

			if ($startdate != '' && $enddate != '') {

				$st1 = date('Y-m-d H:i:s', strtotime($startdate));
				$st2 = date('Y-m-d H:i:s', strtotime($enddate));
				//$cond = " `Createdate` BETWEEN '$st1' AND '$st2'";

			} else {
				$st1 = date('Y-m-01 00:00:00', strtotime('this month'));
				$st2 = date('Y-m-t 12:59:59', strtotime('this month'));
			}

		} else {
			$st1 = date('Y-m-01 00:00:00', strtotime('this month'));
			$st2 = date('Y-m-t 12:59:59', strtotime('this month'));
		}

		$st11 = date('m/d/Y', strtotime($st1));
		$st22 = date('m/d/Y', strtotime($st2));
		$cond = " `tbl_customer`.`created_at` BETWEEN '$st1' AND '$st2'";
		$user = Yii::$app->user->identity;

		$query = Customer::find()->where($cond);

		if ($user->user_role != 'admin') {
			$query->andWhere(['created_by' => $user->id]);
		}

		$countQuery = clone $query;
		$pages = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => $pageSize]);
		$model = $query->offset($pages->offset)
			->limit($pages->limit)
			->all();

		$serial_number = ($page_no - 1) * $pageSize;

		return $this->render('customer',
			[
				'model' => $model,
				'startdate' => date('Y-m-d', strtotime($st1)),
				'enddate' => date('Y-m-d', strtotime($st2)),
				's_no' => $serial_number,
				'pages' => $pages,
			]);

	}

}

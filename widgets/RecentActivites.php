<?php
namespace app\widgets;

use app\models\ActivityLog;
use Yii;
use yii\data\Pagination;

class RecentActivites extends \yii\bootstrap\Widget {

	public $no_of_records;

	public $model;
	public $model_id;
	public $dtype;

	public function init() {
		parent::init();
		if ($this->no_of_records === null) {
			$this->no_of_records = '100';
		}
	}

	public function run() {

		$get_logged_in = Yii::$app->user->identity->id;
		$get_logged_role = Yii::$app->user->identity->user_role;
		if ($get_logged_role != 'admin') {
			$cond = '`user_id`=' . $get_logged_in;
		} else {
			$cond = '1';
		}

		$data = ActivityLog::find();
		if ($this->dtype != 'dashboard' && $this->model != '') {
			$data->where(['model' => $this->model])->andwhere(['model_id' => $this->model_id])->andWhere($cond);
		} else {
			$data->andWhere($cond);

		}
		if ($this->dtype == 'dashboard') {
			$data->limit($this->no_of_records);
		}
		$query = $data->orderBy(['date_time' => SORT_DESC]);

		$countQuery = clone $query;
		$pages = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => 10]);
		$query->offset($pages->offset);
		if ($this->dtype != 'dashboard') {
			$query->limit($pages->limit);
		}
		$model = $query->all();

		return $this->render('activity', ['model' => $model, 'pages' => $pages, 'type' => $this->dtype]);

	}
}

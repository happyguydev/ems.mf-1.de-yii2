<?php
namespace app\widgets;

use Yii;

class ActivityLog extends \yii\bootstrap\Widget {

	public $no_of_records;

	public $model;
	public $model_id;
	public $type;

	public function init() {
		parent::init();
		if ($this->no_of_records === null) {
			$this->no_of_records = '5';
		}
	}

	public function run() {

		$get_logged_in = Yii::$app->user->identity->id;
		$get_logged_role = Yii::$app->user->identity->user_role;
		if ($get_logged_role != 'user') {
			$cond = '`user_id`=' . $get_logged_in;
		} else {
			$cond = '1';
		}

		$data = ActivityLog::find();
		if ($type != 'dashboard') {
			$data->where(['model' => $this->model])->andwhere(['model_id' => $this->model_id])->andWhere($cond);
		}

		$model = $data->orderBy(['date_time' => SORT_DESC])->limit($no_of_records)->all();
		print_r($model);
		die;

		return $this->render('activity', ['model' => $model]);

	}
}

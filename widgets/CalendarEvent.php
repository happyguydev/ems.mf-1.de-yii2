<?php
namespace app\widgets;

use app\models\Appointment;
use app\models\AppointmentAssignee;
use Yii;

class CalendarEvent extends \yii\bootstrap\Widget {

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

		$user = Yii::$app->user->identity;
	
		$ids = 0;
		$model = AppointmentAssignee::find()->where(['user_id' => $user->id])->all();
		if (count($model) > 0) {
			foreach ($model as $key => $value) {
				$ids .= ',' . $value['appointment_id'];
			}
		}

		$date = date('Y-m-d H:i:s');
		$month = date('m', strtotime($date));
		
		if ($user->user_role != 'admin') {
			$cond = "(`created_by`= $user->id OR id IN($ids) ) AND month(start_date_time) =" . $month;
		} else {
			$cond = '1';
		}

		//$cond1 = "month(start_date_time) =" . $month;
		$query = Appointment::find()->where($cond);
		$model = $query->all();
		$highlighted_dates = '';
		foreach ($model as $k => $v) {
			$hdate = date('Y-m-d', strtotime($v['start_date_time']));
			if ($hdate != date('Y-m-d')) {
				$highlighted_dates .= date('d', strtotime($hdate)) . ',';
			}
		}

		return $this->render('calendar_event', ['model' => $model, 'highlighted_dates' => $highlighted_dates]);

	}
}

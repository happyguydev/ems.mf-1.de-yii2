<?php
namespace app\components;

use app\models\ActivityLog;
use app\models\Notification;
use app\models\User;
use Yii;

/**
 *
 */
class notify extends \yii\base\Behavior {

	public function addNotify($user, $type, $modal, $item, $msg, $action = '') {

		/* 	Read = 1 =>unread
			Read = 0 =>read
		*/
		$date = date('Y-m-d H:i:s');

		/** for user that recieve this **/

		$model = new Notification;
		$model->type = $type;
		$model->user_id = $user;
		$model->modal = $modal;
		$model->item_id = $item;
		$model->action = $action;

		$model->message = $msg;
		$model->read = 1;
		$model->create_date = $date;
		if ($model->user_id != 0) {
			$model->save();
		}
		//$this->addRecentActivity($user, $action, $modal, $item);

		return true;

	}

	public function addRecentActivity($action, $modal, $item, $user = 0, $title = null, $type = null) {

		$date = date('Y-m-d H:i:s');
		$data = [];
		$data['to_user'] = $user;
		$data['title'] = $title;
		$data['type'] = $type;

		/** for user that recieve this **/

		$model = new ActivityLog;
		$model->user_id = Yii::$app->user->identity->id;
		$model->action = $action;
		$model->model_id = $item;
		$model->model = $modal;
		$model->data = json_encode($data);
		$model->date_time = $date;
		$model->save();

		return true;

	}

	public function listNotify($limit) {

		$cond = '`user_id`=' . Yii::$app->user->getId();

		$model = Notification::find()
			->where($cond)
			->asArray()
			->orderBy(['create_date' => SORT_DESC])
		//	->groupBy(['Action', 'CreateDate'])
			->limit($limit)
			->all();

		return $model;
	}

	public function unreadCount() {
		$cond = '`user_id`=' . Yii::$app->user->getId();
		$count = Notification::find()->where($cond)->andwhere(['read' => 1])->count();

		return $count;
	}

	public function viewLink($id) {
		$model = Notification::findOne($id);

		if ($model['item_id'] == 0) {
			$link = \Yii::getAlias('@web') . '/' . strtolower($model['modal']);
		} else {
			$link = \Yii::getAlias('@web') . '/' . strtolower($model['modal']) . '?id=' . $model['item_id'];

		}

		return $link;
	}

	public function readNotify($id) {
		$model = Notification::findOne($id);
		$model->read = 0;
		$model->save();

		return true;
	}

	public function getDuration($time) {

		$etime = strtotime(date('Y-m-d H:i:s')) - strtotime($time);

		if ($etime < 1) {
			return '0 second ago';
		}

		$a = array(365 * 24 * 60 * 60 => 'year',
			30 * 24 * 60 * 60 => 'month',
			24 * 60 * 60 => 'day',
			60 * 60 => 'hour',
			60 => 'minute',
			1 => 'second',
		);
		$a_plural = array('year' => 'years',
			'month' => 'months',
			'day' => 'days',
			'hour' => 'hours',
			'minute' => 'minutes',
			'second' => 'seconds',
		);

		foreach ($a as $secs => $str) {
			$d = $etime / $secs;
			if ($d >= 1) {
				$r = round($d);
				return $r . ' ' . ($r > 1 ? $a_plural[$str] : $str) . ' ago';
			}
		}

	}

	public function stringToDuration($etime) {

		//$etime = strtotime(date('Y-m-d H:i:s')) - strtotime($time);

		if ($etime < 1) {
			return '0 second';
		}

		$a = array(365 * 24 * 60 * 60 => 'year',
			30 * 24 * 60 * 60 => 'month',
			24 * 60 * 60 => 'day',
			60 * 60 => 'hour',
			60 => 'minute',
			1 => 'second',
		);
		$a_plural = array('year' => 'years',
			'month' => 'months',
			'day' => 'days',
			'hour' => 'hours',
			'minute' => 'minutes',
			'second' => 'seconds',
		);

		foreach ($a as $secs => $str) {
			$d = $etime / $secs;
			if ($d >= 1) {

				$secs2 = ($secs / 60);

				$d1 = ($etime / $secs2);
				$r2 = $d1 % $secs2;

				$r = round($d);

				return $r . ' ' . ($r > 1 ? $a_plural[$str] : $str) . " " . $r2 . ' ' . ($r2 > 1 ? "mins" : "min");
			}
		}

	}
	public function sendMail($id, $user_id, $model, $link) {
		$st = \Yii::$app->getTable;
		$from_email = $st->settings('email', 'from_email');
		$from_name = $st->settings('email', 'from_name');

		$site_name = $st->settings('general', 'site_name');

		$user = User::findOne($user_id);

		$to = $user['email'];

		$email_template = $st->email_template($id);

		$email = \Yii::$app->mailer->compose('template', ['id' => $id, 'user_id' => $user_id,
			'email_template' => $email_template,
			'model' => $model,
			'link' => $link])

			->setFrom([$from_email => $from_name])
			->setTo($to)
			->setSubject($email_template['Subject'] . ' ' . $site_name)
			->send();

		if ($email) {
			return true;
		}
	}
}

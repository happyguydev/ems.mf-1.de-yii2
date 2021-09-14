<?php

namespace app\controllers;

use app\models\Task;
use app\models\TaskDiscussion;
use app\models\User;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;

/**
 * Default controller for the `discussion` module
 */
class TaskDiscussionController extends Controller {

	public $enableCsrfValidation = false;
	/**
	 * Renders the index view for the module
	 * @return string
	 */
	public function behaviors() {
		return [
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

	public function actionView($id) {
		$model = Task::find()->where(['id' => $id])->andwhere(['!=', 'status', 'trash'])->one();
		$user = User::find()->where(['id' => $model->created_by])->one();

		$users = [];
		$users[] = ['username' => $user->first_name, 'fullname' => $user->name];
		foreach ($model->taskAssignees as $key => $value) {
			$users[] = ['username' => $value->user->first_name, 'fullname' => $value->user->name];
		}

		$all_comments = TaskDiscussion::find()->where(['status' => 'active'])->andwhere(['task_id' => $model->id])->orderBy(['create_date' => SORT_DESC])->all();
		if ($model) {
			return $this->renderAjax('view', ['model' => $model, 'all_comments' => $all_comments, 'users' => $users]);
		} else {
			return false;
		}
	}

	public function actionComment($id) {

		$utility = Yii::$app->Utility;
		extract($_REQUEST);

		$model = new TaskDiscussion;
		$model->assignDefault($id, 0, $comment);
		$model->save();
		$model->notify($id, 1);

		// $utility->sendMailToGroupAfterComment($model);

		return $model->updateSenderMessage($comment, $model->create_date, 0);

	}

	public function actionEditComment($id) {
		extract($_REQUEST);

		$model = TaskDiscussion::find()->where(['id' => $id])->one();
		if (isset($_REQUEST['comment']) != '') {

			if ($model->attach_file != '') {
				$model->comment = $comment;
				$model->is_file = 0;
				$model->attach_file = null;

			} else {
				$model->comment = $comment;
			}
			$model->save();
			$model->notify($model->task_id, 0);
			return $model->task_id;
		}

		return $this->renderAjax('edit_comment', ['model' => $model]);

	}

	public function actionProcessUpload($id) {

		extract($_REQUEST);

		$model = new TaskDiscussion();
		$model->is_file = 1;

		$model->task_id = $id;

		if (isset($_FILES['userFile']) && $_FILES['userFile']['error'] == 0) {

			$upload_path = 'web/discussion/' . $model->id . '/';

			if (!file_exists($upload_path)) {

				mkdir($upload_path, 0777, true);

			}

			$file_ext = pathinfo($_FILES['userFile']['name'], PATHINFO_EXTENSION);
			$file_ext = strtolower($file_ext);

			$file_name = time() . '.' . $file_ext;

			if (move_uploaded_file($_FILES['userFile']['tmp_name'], $upload_path . $file_name)) {

				$model->attach_file = $file_name;

			}
		}
		$model->save();

		$model->notify($model->task_id, 1);

		return $model->updateSenderMessage($model->attach_file, $model->create_date, 1);

	}

	public function actionDelete($id) {

		$model = TaskDiscussion::find()->where(['id' => $id])->one();

		if ($model) {

			if ($model->attach_file != '') {

				$file = 'web/discussion/' . $model['attach_file'];
				if (file_exists($file)) {

					unlink($file);

				}
			}
			$model->notify($model->task_id, 2);
			$model->delete();

			return true;
		} else {
			return false;
		}

	}
}

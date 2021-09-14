<?php

namespace app\modules\chat\controllers;

use app\models\User;
use app\modules\admin\models\Project;
use app\modules\chat\models\Chat;
use Yii;
use yii\web\Controller;

/**
 * Default controller for the `chat` module
 */
class OpenController extends Controller {

	public function actionIndex() {

		// $project = Project::find()->where(['id' => $id])->one();

		/*$user = User::find()
			->where(['id' => $id])
			->andwhere(['status' => 10])
		*/
		$model = Chat::find()
		/*->where("sender_id = " . Yii::$app->user->identity->id . " || receiver_id = " . Yii::$app->user->identity->id)*/
		/*->andwhere("sender_id = " . $id . " || receiver_id = " . $id)*/
			->orderBy(['created_at' => SORT_DESC])
			->asArray()
			->all();

		$project = ['id' => 1, 'title' => 'test'];
		$role = Yii::$app->user->identity->user_role;

		if ($role == 'client') {
			$receiver_id = $project['user_id'];
		} else {

			$receiver_id = 1;

		}

		$chat_modal = new Chat;
		$user_list = $chat_modal->markRead(1, $receiver_id);

		return $this->render('view', [
			'id' => 1,
			'model' => array_reverse($model),
			'project' => $project,
		]);
	}

	public function actionSend($id) {
		extract($_REQUEST);

		$model = new Chat;

		$project = Project::find()->where(['id' => $id])->one();

		$role = Yii::$app->user->identity->user_role;

		if ($role == 'client') {
			$sender_id = $project['user_id'];
			$receiver_id = 1;
		} else {

			$sender_id = 1;
			$receiver_id = $project['user_id'];

		}

		$model->sender_id = $sender_id;
		$model->receiver_id = $receiver_id;
		$model->text = $msg;
		$model->is_new = 1;
		$model->is_file = 0;
		$model->project_id = $id;
		$model->created_at = date('Y-m-d H:i:s');
		$model->created_by = Yii::$app->user->identity->id;
		$model->save();

		echo $model->updateSenderMessage($msg, $model->created_at, 0);
	}

	public function actionSync($id) {

		$project = Project::find()->where(['id' => $id])->one();

		$role = Yii::$app->user->identity->user_role;

		if ($role == 'client') {
			$receiver_id = $project['user_id'];
		} else {

			$receiver_id = 1;

		}
		$model = Chat::find()
			->where(" receiver_id = " . $receiver_id)
			->andwhere("project_id = " . $id)
			->andwhere(['is_new' => 1])
			->orderBy(['created_at' => SORT_DESC])
			->all();

		$data = "";

		foreach ($model as $k => $v) {

			$path_left = Chat::getUserProfile($v['created_by'], 'left');
			$path_right = Chat::getUserProfile($v['created_by'], 'right');

			if ($v['sender_id'] == $receiver_id) {

				$data .= '<div class="chat chat-left">
				' . $path_left . '

                        <div class="chat-body">
                          <div class="chat-content" >
                            <p>';

				if ($v['is_file'] == 1) {
					$data .= '<a href="javascript:void(0)" ';
					$data .= "onclick='downloadFile(";
					$data .= '"' . $v['text'] . '"';
					$data .= ")'>" . $v['text'] . '</a>';
				} else {
					$data .= $v['text'];
				}

				$data .= '</p>
                            <time class="chat-time" datetime="' . date('H:i, d M', strtotime($v['created_at'])) . '">' .
				date('H:i, d M', strtotime($v['created_at'])) . '</time>

                          </div>
                      </div>
                  </div>';

			} else {

				$data .= '<div class="chat">
				  				' . $path_right . '

                          <div class="chat-body">
                              <div class="chat-content" >
                                <p>';
				if ($v['is_file'] == 1) {
					$data .= '<a href="javascript:void(0)" ';
					$data .= "onclick='downloadFile(";
					$data .= '"' . $v['text'] . '"';
					$data .= ")'>" . $v['text'] . '</a>';
				} else {
					$data .= $v['text'];
				}

				$data .= '</p>
                                <time class="chat-time" datetime="' . date('H:i, d M', strtotime($v['created_at'])) . '">' .
				date('H:i, d M', strtotime($v['created_at'])) . '</time>

                              </div>
                          </div>
                      </div>';

			}

			if ($v['receiver_id'] == $receiver_id) {

				$v->is_new = 0;
				$v->save(false);
			}
		} // end foreach

		echo $data;
	}

	public function actionUploadImage() {
		return $this->render('file_upload');
	}

	public function actionProcessUpload($id) {

		extract($_REQUEST);

		$model = new Chat;

		$project = Project::find()->where(['id' => $id])->one();

		$role = Yii::$app->user->identity->user_role;

		if ($role == 'client') {
			$sender_id = $project['user_id'];
			$receiver_id = 1;
		} else {

			$sender_id = 1;
			$receiver_id = $project['user_id'];

		}

		$model->sender_id = $sender_id;
		$model->receiver_id = $receiver_id;

		$model->is_new = 1;
		$model->is_file = 1;

		$model->project_id = $id;
		$model->created_at = date('Y-m-d H:i:s');
		$model->created_by = Yii::$app->user->identity->id;

		if (isset($_FILES['userFile']) && $_FILES['userFile']['error'] == 0) {

			$upload_path = 'web/chat/' . $id . '/';

			if (!file_exists($upload_path)) {

				mkdir($upload_path, 0777, true);

			}

			$file_ext = pathinfo($_FILES['userFile']['name'], PATHINFO_EXTENSION);
			$file_ext = strtolower($file_ext);

			$file_name = $_FILES['userFile']['name'];

			if (move_uploaded_file($_FILES['userFile']['tmp_name'], $upload_path . $file_name)) {

				$model->text = $file_name;

			}
		}
		$model->save();

		echo $model->updateSenderMessage($file_name, $model->created_at, 1);

	}

}

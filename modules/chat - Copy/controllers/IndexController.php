<?php

namespace app\modules\chat\controllers;

use app\models\User;
use app\modules\chat\models\Chat;
use Yii;
use yii\web\Controller;

/**
 * Default controller for the `chat` module
 */
class IndexController extends Controller {
	public $enableCsrfValidation = false;

	public function actionIndex() {

		$model = Chat::find()
			->where("sender_id = " . Yii::$app->user->identity->id . " || receiver_id = " . Yii::$app->user->identity->id)
			->orderBy(['created_at' => SORT_DESC])
			->asArray()
			->all();

		return $this->render('index', [
			'model' => array_reverse($model),
		]);
	}

	public function actionView($id) {
		$user = User::find()
			->where(['id' => $id])
			->andwhere(['status' => 1])
			->one();
		$model = Chat::find()
			->where("sender_id = " . Yii::$app->user->identity->id . " || receiver_id = " . Yii::$app->user->identity->id)
			->andwhere("sender_id = " . $id . " || receiver_id = " . $id)
			->orderBy(['created_at' => SORT_DESC])
			->asArray()
			->all();

		$chat_modal = new Chat;
		$user_list = $chat_modal->markRead($id);

		return $this->render('view', [
			'user' => $user,
			'id' => $id,
			'model' => array_reverse($model),
		]);
	}

	public function actionSend() {
		extract($_REQUEST);

		$model = new Chat;

		$model->sender_id = Yii::$app->user->identity->id;
		$model->receiver_id = $r_id;
		$model->text = $msg;
		$model->is_new = 1;
		$model->is_file = 0;
		$model->created_at = date('Y-m-d h:i:s');
		$model->save();

		echo $model->updateSenderMessage($msg, $model->created_at);
	}

	public function actionSync($id) {
		$model = Chat::find()
			->where(" receiver_id = " . Yii::$app->user->identity->id)
			->andwhere("sender_id = " . $id)
			->andwhere(['is_new' => 1])
			->orderBy(['created_at' => SORT_DESC])
			->all();

		$data = "";

		foreach ($model as $k => $v) {
			if ($v['sender_id'] == Yii::$app->user->identity->id) {

				$data .= '<div class="chat chat-left">
                        <div class="chat-body">
                          <div class="chat-content" >
                            <p>' . $v['text'] . '</p>
                            <time class="chat-time" datetime="' . date('H:i, d M', strtotime($v['created_at'])) . '">' .
				date('H:i, d M', strtotime($v['created_at'])) . '</time>

                          </div>
                      </div>
                  </div>';

			} else {

				$data .= '<div class="chat">
                          <div class="chat-body">
                              <div class="chat-content" >
                                <p>' . $v['text'] . '</p>
                                <time class="chat-time" datetime="' . date('H:i, d M', strtotime($v['created_at'])) . '">' .
				date('H:i, d M', strtotime($v['created_at'])) . '</time>

                              </div>
                          </div>
                      </div>';

			}

			$v->is_new = 0;
			$v->save(false);
		} // end foreach

		echo $data;
	}

	public function actionUserList($id) {
		$chat_modal = new Chat;

		$user_list = $chat_modal->getUserList();

		$data = ""; // will hold chat users
		$data2 = ""; // will hold all users
		$all_users = User::find()->where(['status' => 1])->asArray()->all();

		//var_dump($user_list);

		foreach ($user_list as $key => $value) {

			if (Yii::$app->user->identity->id != $value['id']) {

				if ($value['id'] == $id) {
					continue;
				}

				$data .= $chat_modal->userListTemplate($value['id']);

			}

		}

		foreach ($all_users as $key2 => $value2) {

			if (Yii::$app->user->identity->id != $value2['id']) {

				if ($value2['id'] == $id) {
					continue;
				}

				$data2 .= $chat_modal->userListTemplate($value2['id']);
			}

		}

		$data .= "<script>$('#searchbox').keyup(function () {
    var valThis = this.value.toLowerCase(),
        lenght  = this.value.length;


    $('.mylist > .chat-user-list').each(function () {
        var text  = $(this).find('.user_name > div').text();
            textL = text.toLowerCase();
            // console.log(textL);
            (textL.indexOf(valThis) >= 0) ? $(this).show() : $(this).hide();

    });

});</script>";

		$data2 .= "<script>$('#searchbox').keyup(function () {
    var valThis = this.value.toLowerCase(),
        lenght  = this.value.length;


    $('.friendlist > .chat-user-list ').each(function () {
        var text  = $(this).find('.user_name > div').text();
            textL = text.toLowerCase();
            (textL.indexOf(valThis) >= 0) ? $(this).show() : $(this).hide();

    });

});</script>";

		$data3 = ['chat' => $data, 'friends' => $data2];

		return json_encode($data3);
	}

	/*public function actionUserList($id) {
				$chat_modal = new Chat;

				$user_list = $chat_modal->getUserList();

				$data = ""; // will hold chat users
				$data2 = ""; // will hold all users
				$all_users = User::find()->where(['status' => 1])->asArray()->all();

				// var_dump($user_list);

				foreach ($user_list as $key => $value) {

					if (Yii::$app->user->identity->id != $value['id']) {

						if ($value['id'] == $id) {
							continue;
						}

						$username = $value['username'];

						$unread_count = $chat_modal->unreadCount($value['id']);

						$data .= '<a href="' . Yii::getAlias('@web') . '/chat/index/view?id=' . $value['id'] . '" class="list-group-item mylist"  >
		                              <div class="media-body">
		                              <h4 class="media-heading user_name">
		                                  <span id="user_name' . $value['id'] . '">' . $username . "</span>";
						if ($unread_count > 0) {
							$data .= '<span class="badge" style="background-color: #5cb85c">' . $unread_count . '</span>';
						}

						$data .= '</h4>
		                      </div>

		                  </a>';
					}

				}

				foreach ($all_users as $key2 => $value2) {

					if (Yii::$app->user->identity->id != $value2['id']) {

						if ($value2['id'] == $id) {
							continue;
						}

						$username2 = $value2['username'];

						$unread_count2 = $chat_modal->unreadCount($value2['id']);

						$data2 .= '<a href="' . Yii::getAlias('@web') . '/chat/index/view?id=' . $value2['id'] . '" class="list-group-item mylist"  >
		                              <div class="media-body">
		                              <h4 class="media-heading user_name">
		                                  <span id="user_name' . $value2['id'] . '">' . $username2 . "</span>";
						if ($unread_count2 > 0) {
							$data2 .= '<span class="badge" style="background-color: #5cb85c">' . $unread_count2 . '</span>';
						}

						$data2 .= '</h4>
		                      </div>

		                  </a>';
					}

				}

				$data .= "<script>$('#searchbox').keyup(function () {
		    var valThis = this.value.toLowerCase(),
		        lenght  = this.value.length;

		    $('.mylist>a').each(function () {
		        var text  = $(this).find('.user_name>span').text();
		            textL = text.toLowerCase();
		            (textL.indexOf(valThis) >= 0) ? $(this).show() : $(this).hide();

		    });

		});</script>";

				$data2 .= "<script>$('#searchbox').keyup(function () {
		    var valThis = this.value.toLowerCase(),
		        lenght  = this.value.length;

		    $('.friendlist>a').each(function () {
		        var text  = $(this).find('.user_name>span').text();
		            textL = text.toLowerCase();
		            (textL.indexOf(valThis) >= 0) ? $(this).show() : $(this).hide();

		    });

		});</script>";

				$data3 = ['chat' => $data, 'friends' => $data2];

				return json_encode($data3);
	*/
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

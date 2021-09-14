<?php

namespace app\modules\chat\controllers;

use app\models\User;
use app\modules\chat\models\Chat;
use app\modules\chat\models\ChatGroup;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;

/**
 * Default controller for the `chat` module
 */
class IndexController extends Controller {
	public $enableCsrfValidation = false;

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

	public function actionIndex() {

		/*$model = Chat::find()
			->where("sender_id = " . Yii::$app->user->identity->id . " || receiver_id = " . Yii::$app->user->identity->id)
			->orderBy(['created_at' => SORT_DESC])
			->limit(300)
			->asArray()
			->all();*/

		return $this->render('index');
	}

	public function actionView($id) {
		$user = User::find()
			->where(['id' => $id])
			->andwhere(['status' => 1])
			->one();
		$model = Chat::find()
			->where("sender_id = " . Yii::$app->user->identity->id . " || receiver_id = " . Yii::$app->user->identity->id)
			->andwhere("sender_id = " . $id . " || receiver_id = " . $id)
			->andwhere(['is_group' => 0])
			->orderBy(['created_at' => SORT_DESC])
			->limit(300)
			->asArray()
			->all();

		$chat_modal = new Chat;
		//$user_list = $chat_modal->markRead($id);

		return $this->render('view', [
			'user' => $user,
			'id' => $id,
			'model' => array_reverse($model),
		]);
	}

	public function actionSend($is_group = 0) {
		extract($_REQUEST);

		$model = new Chat;

		$model->sender_id = Yii::$app->user->identity->id;
		$model->receiver_id = $r_id;
		$model->text = $msg;
		$model->is_new = 1;
		$model->is_file = 0;
		$model->is_group = $is_group;
		$model->created_at = date('Y-m-d H:i:s');
		$model->save();
		if ($is_group == 1) {
			$chat_group_model = new ChatGroup();

			$chat_group_model->updateCount($r_id);

		}

		return $model->updateSenderMessage($msg, $model->created_at);
	}

	public function actionSync($id, $is_group = 0) {
		$model = Chat::find()
			->where(" receiver_id = " . Yii::$app->user->identity->id)
			->andwhere("sender_id = " . $id)
			->andwhere(['is_new' => 1])
			->andWhere(['is_group' => $is_group])
			->orderBy(['created_at' => SORT_DESC])
			->all();

		$data = "";
		$chat_modal = new Chat;

		foreach ($model as $k => $v) {
			$data .= $chat_modal->chatContentTemplate($v);
			if ($v->receiver_id == Yii::$app->user->identity->id) {
				$v->is_new = 0;
				$v->save(false);
			}
		} // end foreach

		return $data;
	}

	public function actionSyncGroup($id, $is_group = 1) {
		$data = "";
		$chat_group_model = new ChatGroup();
		$ttl = $chat_group_model->getCount($id);

		if ($ttl > 0) {

			$model = Chat::find()
				->where(" receiver_id = " . $id)
				->andWhere(['is_group' => $is_group])
				->limit($ttl)
				->orderBy(['created_at' => SORT_DESC])
				->all();

			$chat_modal = new Chat;

			foreach ($model as $k => $v) {
				$data .= $chat_modal->chatContentTemplate($v);
				//if ($v->receiver_id == Yii::$app->user->identity->id) {
				//$v->is_new = 0;
				//$v->save(false);
				//}
			} // end foreach
			$chat_group_model->markRead($id);
		}

		return $data;
	}

	public function actionUserList($id) {
		$chat_modal = new Chat;

		$user_list = $chat_modal->getUserList();
		$group_model = new ChatGroup();

		$data = ""; // will hold chat users
		$data2 = ""; // will hold all users
		$data3 = ""; // will hold all groups
		$all_users = User::find()->where(['status' => 1])->asArray()->all();

		//var_dump($user_list);

		foreach ($user_list as $key => $value) {

			if (Yii::$app->user->identity->id != $value['id']) {

				$data .= $chat_modal->userListTemplate($value['id'], $id);

			}

		}

		foreach ($all_users as $key2 => $value2) {

			if (Yii::$app->user->identity->id != $value2['id']) {

				$data2 .= $chat_modal->userListTemplate($value2['id'], $id);
			}

		}

		foreach ($group_model->groupList as $key3 => $value3) {

			$data3 .= $group_model->groupListTemplate($value3['group_id'], $id);

		}

		$data .= "<script>$('#searchbox').keyup(function () {
    var valThis = this.value.toLowerCase(),
        lenght  = this.value.length;


    $('.mylist > .chat-user-list').each(function () {
        var text  = $(this).find('.user_name').text();
            textL = text.toLowerCase();
            // console.log(textL);
            (textL.indexOf(valThis) >= 0) ? $(this).show() : $(this).hide();

    });

});</script>";

		$data2 .= "<script>$('#searchbox').keyup(function () {
    var valThis = this.value.toLowerCase(),
        lenght  = this.value.length;


    $('.friendlist > .chat-user-list ').each(function () {
        var text  = $(this).find('.user_name').text();
            textL = text.toLowerCase();
            (textL.indexOf(valThis) >= 0) ? $(this).show() : $(this).hide();

    });

});</script>";

		$data3 .= "<script>$('#searchbox').keyup(function () {
    var valThis = this.value.toLowerCase(),
        lenght  = this.value.length;


    $('.groupslist > .chat-user-list ').each(function () {
        var text  = $(this).find('.user_name').text();
            textL = text.toLowerCase();
            (textL.indexOf(valThis) >= 0) ? $(this).show() : $(this).hide();

    });

});</script>";

		$data3 = ['chat' => $data, 'friends' => $data2, 'groups' => $data3];

		return json_encode($data3);
	}

	public function actionUploadImage() {
		return $this->render('file_upload');
	}

	public function actionProcessUpload($id, $is_group = 0) {

		extract($_REQUEST);

		$model = new Chat;

		$model->sender_id = Yii::$app->user->identity->id;
		$model->receiver_id = $id;

		$model->is_new = 1;
		$model->is_file = 1;
		$model->is_group = $is_group;
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
		if ($model->save()) {
			if ($is_group == 1) {
				$chat_group_model = new ChatGroup();

				$chat_group_model->updateCount($id);

			}
			return $model->updateSenderMessage($file_name, $model->created_at, 1, $id);
		} else {
			return false;
		}

	}
	public function actionCreateGroup($name) {
		$model = new ChatGroup;
		$model->name = $name;
		$model->created_at = date('Y-m-d H:i:s');
		$model->created_by = Yii::$app->user->identity->id;
		if ($model->save()) {
			$model->addUser(Yii::$app->user->identity->id, $model->id);

		}
		return $this->redirect(['group', 'id' => $model->id]);

	}

	public function actionGroupAdd($user_id, $group_id) {
		$model = new ChatGroup;
		$model->addUser($user_id, $group_id);

		return $group_id;

	}
	public function actionGroupRemove($user_id, $group_id) {
		$model = new ChatGroup;
		$model->removeUser($user_id, $group_id);
		if ($user_id == Yii::$app->user->identity->id) {
			return $this->redirect(['index']);
		}

		return $group_id;

	}
	public function actionGroup($id) {

		$group = ChatGroup::find()
			->where(['id' => $id])
			->one();
		$group_model = new ChatGroup();
		$d = $group_model->userNotInGroup($id);

		$data3 = "";

		if (Yii::$app->user->identity->user_role == 'admin' || $group->created_by == Yii::$app->user->identity->id) {
			$dd = $group_model->userInGroup($id);
			foreach ($dd as $key4 => $value4) {

				$data3 .= $group_model->userListTemplate($value4['user_id'], $id, 1);

			}
		}
		foreach ($d as $key3 => $value3) {

			$data3 .= $group_model->userListTemplate($value3['id'], $id, 0);

		}

		$model = Chat::find()
			->where(['receiver_id' => $id])
			->andwhere(['is_group' => 1])
			->orderBy(['created_at' => SORT_DESC])
			->limit(300)
			->asArray()
			->all();

		$chat_modal = new Chat;
		$user_list = $group_model->markRead($id);

		return $this->render('group', [
			'group' => $group,
			'id' => $id,
			'model' => array_reverse($model),
			'group_users' => $data3,
		]);
	}

	public function actionGroupEdit($id) {

		extract($_REQUEST);

		$model = ChatGroup::find()
			->where(['id' => $id])
			->one();

		$model->name = $groupName;
		$model->updated_at = date('Y-m-d H:i:s');
		$model->updated_by = Yii::$app->user->identity->id;

		if (isset($_FILES['userFile']) && $_FILES['userFile']['error'] == 0) {

			$upload_path = 'web/group/';

			if (!file_exists($upload_path)) {

				mkdir($upload_path, 0777, true);

			}

			$file_ext = pathinfo($_FILES['userFile']['name'], PATHINFO_EXTENSION);
			$file_ext = strtolower($file_ext);

			$file_name = $_FILES['userFile']['name'];

			if (move_uploaded_file($_FILES['userFile']['tmp_name'], $upload_path . $file_name)) {

				$model->group_icon = $file_name;

			}
		}
		if ($model->save()) {
			return $this->redirect(['group', 'id' => $id]);

		} else {
			return false;
		}

	}

}

<?php

namespace app\modules\chat\models;

use app\models\User;
use Yii;

/**
 * This is the model class for table "{{%chat}}".
 *
 * @property integer $id
 * @property integer $sender_id
 * @property integer $receiver_id
 * @property string $text
 * @property integer $is_new
 * @property integer $is_deleted_by_sender
 * @property integer $is_deleted_by_receiver
 * @property string $created_at
 */
class Chat extends \yii\db\ActiveRecord {
	/**
	 * @inheritdoc
	 */
	public static function tableName() {
		return '{{%chat}}';
	}

	/**
	 * @inheritdoc
	 */
	public function rules() {
		return [
			[['sender_id', 'receiver_id', 'is_new', 'is_deleted_by_sender', 'is_deleted_by_receiver', 'is_file'], 'integer'],
			[['text'], 'required'],
			[['text'], 'string'],
			[['created_at'], 'safe'],
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels() {
		return [
			'id' => Yii::t('app', 'ID'),
			'sender_id' => Yii::t('app', 'Sender ID'),
			'receiver_id' => Yii::t('app', 'Receiver ID'),
			'text' => Yii::t('app', 'Text'),
			'is_new' => Yii::t('app', 'Is New'),
			'is_deleted_by_sender' => Yii::t('app', 'Is Deleted By Sender'),
			'is_deleted_by_receiver' => Yii::t('app', 'Is Deleted By Receiver'),
			'created_at' => Yii::t('app', 'Created At'),
		];
	}

	public function getUserList() {
		$l_user = Yii::$app->user->identity;
		$db = Yii::$app->db;
		$chat_table = self::tableName();
		$user_table = User::tableName();

		$query = "SELECT * from  $chat_table chat LEFT JOIN $user_table user ON(user.id= chat.sender_id OR user.id= chat.receiver_id) where  chat.receiver_id= $l_user->id OR chat.sender_id = $l_user->id AND user.id != $l_user->id group by user.id";
		$users = $db->createCommand($query)->queryAll();

		return $users;

	}

	public function unreadCount($sender_id) {
		$receiver_id = Yii::$app->user->identity->id;
		$model = Chat::find()
			->where(['receiver_id' => $receiver_id])
			->andwhere(['sender_id' => $sender_id])
			->andwhere(['is_new' => 1])
			->count();

		return $model;

	}

	public function unreadCountAll() {
		$receiver_id = Yii::$app->user->identity->id;
		$model = Chat::find()
			->where(['receiver_id' => $receiver_id])
			->andwhere(['is_new' => 1])
			->count();

		return $model;

	}

	public function unreadCounter() {
		$receiver_id = Yii::$app->user->identity->id;
		$model = Chat::find()
			->where(['receiver_id' => $receiver_id])
			->andwhere(['is_new' => 1])
			->count();

		return $model;

	}

	public function markRead($sender_id) {

		$receiver_id = Yii::$app->user->identity->id;
		$model = Chat::find()
			->where(['receiver_id' => $receiver_id])
			->andwhere(['sender_id' => $sender_id])
			->andwhere(['is_new' => 1])
			->all();

		foreach ($model as $key => $value) {
			$value->is_new = 0;
			$value->save(false);

		}

		return true;

	}

	public function updateSenderMessage($text, $time) {
		$data = '<div class="chat chat-left">
                <div class="chat-body">
                  <div class="chat-content" id="sender_msg">
                    <p>' . $text . '</p>
                    <time class="chat-time" datetime="' . date('H:i, d M', strtotime($time)) . '">' . date('H:i, d M', strtotime($time)) . '</time>

                  </div>

                </div>
              </div>';

		echo $data;
	}

	public function userListTemplate($user_id) {
		$user = User::findOne($user_id);
		$data = "";
		if ($user) {
			$count = $this->unreadCount($user_id);
			$data = '<div class="intro-x cursor-pointer box relative flex items-center p-5 mt-5 chat-user-list">
            <div class="w-12 h-12 flex-none image-fit mr-1">
              <img alt="' . $user->name . '" class="rounded-full" src="' . $user->thumbnailImage . '">
            </div>
            <div class="ml-2 overflow-hidden">
            <a href="' . Yii::getAlias('@web') . '/chat/index/view?id=' . $user_id . '" class="font-medium user_name">
              <div class="flex items-center">
                ' . $user->name . '
              </div>
              <div class="w-full truncate text-gray-600 mt-0.5">' . $user->email . '</div></a>
            </div>
            <div class="w-5 h-5 flex items-center justify-center absolute top-0 right-0 text-xs text-white rounded-full bg-theme-1 font-medium -mt-1 -mr-1">' . $count . '</div>
          </div>';
		}
		return $data;
	}
	public function chatContentTemplate($chat) {
		if ($chat['sender_id'] == Yii::$app->user->identity->id) {
			$position = 'float-left';
			$bgcls = " bg-gray-200 dark:bg-dark-5  text-gray-700 dark:text-gray-300 ";

		} else {
			$position = 'float-right';
			$bgcls = " bg-theme-1 text-white ";
		}
		$date = date('H:i, d M', strtotime($chat->created_at));
		$text = $chat->text;
		$data = "";
		$data .= '<div class="chat__box__text-box flex items-end float-left mb-4">
                                        <div class="px-4 py-3 rounded-r-md rounded-t-md ' . $bgcls . '">' . $text . '
                                            <div class="mt-1 text-xs text-gray-600">' . $date . '</div>
                                        </div>

                                    </div>';
		$data .= '<div class="clear-both"></div>';

		return $data;
	}

}

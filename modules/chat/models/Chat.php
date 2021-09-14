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
			[['sender_id', 'receiver_id', 'is_new', 'is_deleted_by_sender', 'is_deleted_by_receiver', 'is_file', 'is_group'], 'integer'],
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

		$query = "SELECT * from  $chat_table chat LEFT JOIN $user_table user ON(user.id= chat.sender_id OR user.id= chat.receiver_id) where  chat.receiver_id= $l_user->id OR chat.sender_id = $l_user->id AND user.id != $l_user->id AND chat.is_group = 0  group by user.id";
		$users = $db->createCommand($query)->queryAll();

		return $users;

	}

	public function unreadCount($sender_id) {
		$receiver_id = Yii::$app->user->identity->id;
		$model = Chat::find()
			->where(['receiver_id' => $receiver_id])
			->andwhere(['sender_id' => $sender_id])
			->andwhere(['is_new' => 1])
			->andwhere(['is_group' => 0])
			->count();

		return $model;

	}

	public function unreadCountAll() {
		$receiver_id = Yii::$app->user->identity->id;
		$model = Chat::find()
			->where(['receiver_id' => $receiver_id])
			->andwhere(['is_new' => 1])
			->andwhere(['is_group' => 0])
			->count();

		return $model;

	}

	public function unreadCounter() {
		$receiver_id = Yii::$app->user->identity->id;
		$model = Chat::find()
			->where(['receiver_id' => $receiver_id])
			->andwhere(['is_new' => 1])
			->andwhere(['is_group' => 0])
			->count();

		return $model;

	}

	public function markRead($sender_id) {

		$receiver_id = Yii::$app->user->identity->id;
		$model = Chat::find()
			->where(['receiver_id' => $receiver_id])
			->andwhere(['sender_id' => $sender_id])
			->andwhere(['is_new' => 1])
			->andwhere(['is_group' => 0])
			->all();

		foreach ($model as $key => $value) {
			if ($receiver_id == $value->receiver_id) {
				$value->is_new = 0;
				$value->save(false);
			}

		}

		return true;

	}

	public function updateSenderMessage($text, $time, $is_file = 0, $receiver_id = 0) {
		$date = date('H:i, d M', strtotime($time));
		$user = Yii::$app->user->identity;
		if ($is_file == 1) {
			$thumb = Yii::$app->Utility->get_thumb($text, '/web/chat/' . $receiver_id . '/');
			$file_url = Yii::$app->urlManager->createAbsoluteUrl('/web/chat/' . $receiver_id . '/' . $text);

			if (basename($thumb) == 'audio.png') {
				$msg = '<audio controls><source src="' . $file_url . '" type="audio/mpeg"></audio>';
			} else {
				$msg = '<a href="' . $file_url . '" target="_blank"><img src ="' . $thumb . '"  width="60px"/><p style="font-size:12px">' . $text . '</p></a>';
			}
		} else {
			$msg = $text;
		}
		$img_user_left = '<div class="w-10 h-10 hidden sm:block flex-none image-fit relative mr-5">
                                            <img title="' . $user->name . '"  alt="' . $user->name . '" class="rounded-full tooltip" src="' . $user->thumbnailImage . '">
                                        </div>';
		$data = '<div class="chat__box__text-box flex items-end  mb-4  float-left"> ' . $img_user_left . '
                                        <div class="px-4 py-3 rounded-r-md rounded-t-md bg-gray-200 dark:bg-dark-5  text-gray-700 dark:text-gray-300 ">' . $msg . '
                                            <div class="mt-1 text-xs text-gray-600">' . $date . '</div>
                                        </div>

                                    </div>';
		$data .= '<div class="clear-both"></div>';

		return $data;
	}

	public function userListTemplate($user_id, $active_id = 0) {
		$user = User::findOne($user_id);
		$data = "";
		if ($user) {
			$bg_color = ($active_id == $user_id) ? 'bg-theme-1 text-white' : '';
			$count = $this->unreadCount($user_id);
			$data = '<a href="' . Yii::getAlias('@web') . '/chat/index/view?id=' . $user_id . '" class="font-medium chat-user-list"><div class="cursor-pointer box relative flex items-center p-5 mt-5  ' . $bg_color . '">
            <div class="w-12 h-12 flex-none image-fit mr-1">
              <img alt="' . $user->name . '" class="rounded-full" src="' . $user->thumbnailImage . '">
            </div>
            <div class="ml-2 overflow-hidden">

              <div class="flex items-center user_name">
                ' . $user->name . '
              </div>
              <div class="w-full truncate text-gray-600 mt-0.5">' . $user->email . '</div>
            </div>
            <div class="w-5 h-5 flex items-center justify-center absolute top-0 right-0 text-xs text-white rounded-full  font-medium -mt-1 -mr-1" style="background: #5cb85c">' . $count . '</div>
          </div></a>';
		}
		return $data;
	}
	public function chatContentTemplate($chat) {

		if ($chat['sender_id'] == Yii::$app->user->identity->id) {
			$user = User::findOne($chat['sender_id']);
			$position = 'float-left';
			$bgcls = " bg-gray-200 dark:bg-dark-5  text-gray-700 dark:text-gray-300 ";
			$img_user_right = '';
			$img_user_left = '<div class="w-10 h-10 hidden sm:block flex-none image-fit relative mr-5">
                                            <img alt="' . $user->name . '" title="' . $user->name . '"  class="rounded-full tooltip" src="' . $user->thumbnailImage . '">
                                        </div>';

		} else {
			$user = User::findOne($chat['sender_id']);
			$position = 'float-right';
			$bgcls = " bg-theme-1 text-white ";
			$img_user_right = '<div class="w-10 h-10 hidden sm:block flex-none image-fit relative mr-5">
                                            <img alt="' . $user->name . '"  title="' . $user->name . '" class="rounded-full tooltip" src="' . $user->thumbnailImage . '">
                                        </div>';
			$img_user_left = '';
		}
		$date = date('H:i, d M', strtotime($chat['created_at']));
		$text = $chat['text'];
		if ($chat['is_file'] == 1) {
			$thumb = Yii::$app->Utility->get_thumb($text, '/web/chat/' . $chat['receiver_id'] . '/');
			$file_url = Yii::$app->urlManager->createAbsoluteUrl('/web/chat/' . $chat['receiver_id'] . '/' . $text);
			if (basename($thumb) == 'audio.png') {
				$msg = '<audio controls><source src="' . $file_url . '" type="audio/mpeg"></audio>';
			} else {
				$msg = '<a href="' . $file_url . '" target="_blank"><img src ="' . $thumb . '"  width="60px"/><p style="font-size:12px">' . $text . '</p></a>';
			}
		} else {
			$msg = $text;
		}

		$data = "";
		$data .= '<div class="chat__box__text-box flex items-end  mb-4 ' . $position . '"> ' . $img_user_left . '

                                        <div class="px-4 py-3 rounded-r-md rounded-t-md ' . $bgcls . '">' . $msg . '
                                            <div class="mt-1 text-xs text-gray-600">' . $date . '</div>
                                        </div>
                                        ' . $img_user_right . '
                                    </div>';
		$data .= '<div class="clear-both"></div>';

		return $data;
	}
	public function getFilePath($value = '') {
		return Yii::getAlias('@web') . '/chat/';
	}

}

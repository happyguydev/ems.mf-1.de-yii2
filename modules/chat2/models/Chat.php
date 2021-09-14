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
			[['sender_id', 'receiver_id', 'is_new', 'is_deleted_by_sender', 'is_deleted_by_receiver', 'project_id', 'is_file'], 'integer'],
			[['text'], 'required'],
			[['text'], 'string'],
			[['created_at', 'created_by'], 'safe'],
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

	public function markRead($project_id, $receiver_id) {

		$receiver_id = Yii::$app->user->identity->id;
		$model = Chat::find()
			->where(['project_id' => $project_id])
			->andwhere(['receiver_id' => $receiver_id])
			->andwhere(['is_new' => 1])
			->all();

		foreach ($model as $key => $value) {
			$value->is_new = 0;
			$value->save(false);

		}

		return true;

	}

	public function updateSenderMessage($text, $time, $is_file) {
		$user_id = Yii::$app->user->identity->id;

		$path = self::getUserProfile($user_id, 'left');

		$data = '<div class="chat chat-left">
						' . $path . '

                <div class="chat-body">
                  <div class="chat-content" id="sender_msg">
                    <p>';

		if ($is_file == 1) {
			$data .= '<a href="javascript:void(0)" ';
			$data .= "onclick='downloadFile(";
			$data .= '"' . $text . '"';
			$data .= ")'>" . $text . '</a>';
		} else {
			$data .= $text;
		}
		$data .= '</p>
                    <time class="chat-time" datetime="' . date('H:i, d M', strtotime($time)) . '">' . date('H:i, d M', strtotime($time)) . '</time>

                  </div>

                </div>
              </div>';

		echo $data;
	}

	public static function getUserProfile($user_id, $position = 'left') {

		$user = User::find()->where(['id' => $user_id])->one();
		$user_profile = $user['profile_picture'];

		if ($user_profile != '') {
			$path = Yii::getAlias('@web') . '/web/profile/' . $user_id . '/' . $user_profile;
		} else {
			$path = Yii::getAlias('@web') . '/web/profile/default.jpg';

		}
		if ($position == "left") {
			$data = "<img src='" . $path . "' class='chat-img img-left' title='" . $user['first_name'] . "'>";
		} else {
			$data = "<img src='" . $path . "' class='chat-img img-right' title='" . $user['first_name'] . "'>";
		}

		return $data;

	}
}

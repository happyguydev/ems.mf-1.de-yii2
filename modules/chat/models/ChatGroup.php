<?php

namespace app\modules\chat\models;

use app\models\User;
use app\modules\chat\models\ChatGroupUnreadCount;
use app\modules\chat\models\ChatGroupUser;
use Yii;

/**
 * This is the model class for table "{{%chat_group}}".
 *
 * @property int $id
 * @property string $name
 * @property string|null $group_icon
 * @property string $created_at
 * @property int $created_by
 * @property string|null $updated_at
 * @property int|null $updated_by
 *
 * @property User $createdBy
 * @property ChatGroupUser[] $chatGroupUsers
 */
class ChatGroup extends \yii\db\ActiveRecord {
	/**
	 * {@inheritdoc}
	 */
	public static function tableName() {
		return '{{%chat_group}}';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules() {
		return [
			[['name', 'created_by'], 'required'],
			[['created_at', 'updated_at'], 'safe'],
			[['created_by', 'updated_by'], 'integer'],
			[['name', 'group_icon'], 'string', 'max' => 255],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels() {
		return [
			'id' => Yii::t('app', 'ID'),
			'name' => Yii::t('app', 'Name'),
			'group_icon' => Yii::t('app', 'Group Icon'),
			'created_at' => Yii::t('app', 'Created At'),
			'created_by' => Yii::t('app', 'Created By'),
			'updated_at' => Yii::t('app', 'Updated At'),
			'updated_by' => Yii::t('app', 'Updated By'),
		];
	}

	/**
	 * Gets query for [[CreatedBy]].
	 *
	 * @return \yii\db\ActiveQuery
	 */
	public function getCreatedBy() {
		return $this->hasOne(User::className(), ['id' => 'created_by']);
	}

	/**
	 * Gets query for [[ChatGroupUsers]].
	 *
	 * @return \yii\db\ActiveQuery
	 */
	public function getChatGroupUsers() {
		return $this->hasMany(ChatGroupUser::className(), ['group_id' => 'id']);
	}
	public function getTotalMembers() {
		return ChatGroupUser::find()->where(['group_id' => $this->id])->count();
	}
	public function addUser($user_id, $group_id) {
		$old = ChatGroupUser::find()->where(['user_id' => $user_id])->andwhere(['group_id' => $group_id])->one();
		if (!$old) {
			$model2 = new ChatGroupUser();
			$model2->group_id = $group_id;
			$model2->user_id = $user_id;
			$model2->added_by = Yii::$app->user->identity->id;
			$model2->save();

			$user = Yii::$app->user->identity;
			$notify = Yii::$app->notify;
			//if ($user->user_role != 'admin') {
			$user_name = $user->first_name . ' ' . $user->last_name;
			$notify->addNotify($user_id, 1, 'chat/index/group', $group_id, 'You have been added to ' . $model2->group['name'] . ' chat group by ' . $user_name . '.', 'add_chat_group');
			//}
		}
		return true;
	}
	public function removeUser($user_id, $group_id) {
		$old = ChatGroupUser::find()->where(['user_id' => $user_id])->andwhere(['group_id' => $group_id])->one();
		$user = Yii::$app->user->identity;
		$notify = Yii::$app->notify;
		//if ($user->user_role != 'admin') {
		$user_name = $user->first_name . ' ' . $user->last_name;
		$notify->addNotify($old['user_id'], 1, 'chat/index', 0, 'You have been removed from ' . $old->group['name'] . ' chat group by ' . $user_name . '.', 'remove_chat_group');
		//}
		if ($old) {
			$old->delete();
		}

		return true;
	}
	public function userNotInGroup($group_id) {
		$chat_group_table = ChatGroupUser::tableName();
		$user_table = User::tableName();

		$l_user = Yii::$app->user->identity;
		$db = Yii::$app->db;
		$query = "SELECT pm.id FROM $user_table pm WHERE pm.id NOT IN (SELECT pd.user_id FROM $chat_group_table pd WHERE pd.group_id = $group_id) AND pm.id != $l_user->id";
		$users = $db->createCommand($query)->queryAll();

		return $users;
	}
	public function userInGroup($group_id) {
		return ChatGroupUser::find()->where(['!=', 'user_id', Yii::$app->user->identity->id])->andwhere(['group_id' => $group_id])->asArray()->all();
	}

	public function getGroupList() {
		$model = ChatGroupUser::find()->where(['user_id' => Yii::$app->user->identity->id])->asArray()->all();
		return $model;
	}
	public function getThumbnailImage() {
		if ($this->group_icon != '') {
			$uploadPath = Yii::getAlias('@web') . '/web/group/' . $this->group_icon;

		} else {
			$uploadPath = Yii::getAlias('@web') . '/web/group/icon.png';

		}
		return $uploadPath;
	}

	public function updateCount($group_id) {
		$all_users = $this->userInGroup($group_id);
		if (count($all_users) > 0) {
			foreach ($all_users as $key => $value) {
				$m = new ChatGroupUnreadCount();
				$m->setCount($value['user_id'], $group_id);
			}
		}
		return true;
	}

	public function markRead($group_id) {
		$m = new ChatGroupUnreadCount();
		return $m->markRead($group_id);
	}

	public function getCount($group_id) {
		$m = new ChatGroupUnreadCount();
		return $m->getCount($group_id);
	}

	public function groupListTemplate($group_id, $active_id = 0) {
		$group = ChatGroup::findOne($group_id);
		$data = "";
		if ($group) {
			$count = $this->getCount($group_id);
			$bg_color = ($active_id == $group_id) ? 'bg-theme-1 text-white' : '';
			$data = '<a href="' . Yii::getAlias('@web') . '/chat/index/group?id=' . $group_id . '" class="font-medium chat-user-list"><div class="cursor-pointer box relative flex items-center p-5 mt-5 ' . $bg_color . '">
            <div class="w-12 h-12 flex-none image-fit mr-1">
              <img alt="' . $group->name . '" class="rounded-full" src="' . $group->thumbnailImage . '">
            </div>
            <div class="ml-2 overflow-hidden">

              <div class="flex items-center user_name">
                ' . $group->name . '
              </div>
              <div class="w-full truncate text-gray-600 mt-0.5"></div>
            </div>
            <div class="w-5 h-5 flex items-center justify-center absolute top-0 right-0 text-xs text-white rounded-full  font-medium -mt-1 -mr-1" style="background: #5cb85c">' . $count . '</div>
          </div></a>';
		}
		return $data;
	}
	/*
		type=0 mean add button will show
		type= 1 means remove button will show
	*/

	public function userListTemplate($user_id, $group_id, $type = 0) {
		$user = User::findOne($user_id);
		$cls = 'group-user-' . $user_id;
		if ($type == 1) {
			$btn = '<a href="javascript:removeUserFromGroup(' . $group_id . ', ' . $user_id . ')" class="btn btn-danger bg-theme-6 text-white px-2 box ' . $cls . '" >
            		 <span class="w-5 h-5 flex items-center justify-center fa fa-minus" style="line-height: inherit;"></span>
            		</a>';
		} else {
			$btn = '<a href="javascript:addUserToGroup(' . $group_id . ', ' . $user_id . ')" class="btn btn-primary bg-theme-1 text-white px-2 box ' . $cls . '" >
            		 <span class="w-5 h-5 flex items-center justify-center fa fa-plus" style="line-height: inherit;"></span>
            		</a>';
		}
		$data = "";
		if ($user) {
			$data = '<div class="cursor-pointer box relative flex items-center p-5 mt-5 chat-user-list">
            <div class="w-12 h-12 flex-none image-fit mr-1">
              <img alt="' . $user->name . '" class="rounded-full" src="' . $user->thumbnailImage . '">
            </div>
            <div class="ml-2 overflow-hidden">
            <a href="javascript:;" class="font-medium user_name">
              <div class="flex items-center">
                ' . $user->name . '
              </div>
              <div class="w-full truncate text-gray-600 mt-0.5">' . $user->email . '</div></a>
            </div>
            <div class="w-5 h-5 flex items-center justify-center absolute right-0 text-xs text-white rounded-full  font-medium" style="right:20px">' . $btn . '

            	</div>
          </div>';
		}
		return $data;
	}
}

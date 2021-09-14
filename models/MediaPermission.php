<?php

namespace app\models;

use app\modules\admin\models\Media;
use app\modules\admin\models\MediaFolder;
use Yii;

/**
 * This is the model class for table "{{%media_permission}}".
 *
 * @property int $id
 * @property int $file_id
 * @property int $user_id
 * @property int $is_folder
 * @property string $created_at
 * @property string|null $created_by
 */
class MediaPermission extends \yii\db\ActiveRecord {
	/**
	 * {@inheritdoc}
	 */
	public static function tableName() {
		return '{{%media_permission}}';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules() {
		return [
			[['file_id', 'user_id'], 'required'],
			[['file_id', 'user_id', 'is_folder'], 'integer'],
			[['created_at', 'created_by'], 'safe'],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels() {
		return [
			'id' => Yii::t('app', 'ID'),
			'file_id' => Yii::t('app', 'File ID'),
			'user_id' => Yii::t('app', 'User ID'),
			'is_folder' => Yii::t('app', 'Is Folder'),
			'created_at' => Yii::t('app', 'Created At'),
			'created_by' => Yii::t('app', 'Created By'),
		];
	}
	/*
		    $id is either file id or folder id
		    $user_id is that with whom share file or foler
		    $is_folder is 0 for file or 1 for folder
	*/

	public function addShare($id, $user_id, $is_folder = 0) {
		$notify = Yii::$app->notify;
		$user = Yii::$app->user->identity;
		$model = new MediaPermission();
		$model->user_id = $user_id;
		$model->file_id = $id;
		$model->is_folder = $is_folder;
		$model->created_at = date('Y-m-d H:i:s');
		$model->created_by = $user->id;
		$model->save();
		if ($is_folder == 1) {
			$type = 'folder';
			$media = MediaFolder::find()->where(['id' => $id])->one();
			$name = $media['name'];

		} else {
			$type = 'file';
			$media = Media::find()->where(['id' => $id])->one();
			$name = $media['title'];
		}
		$user_name = $user->first_name . ' ' . $user->last_name;
		if ($model->user_id != $user->id) {
			$notify->addNotify($model->user_id, 1, 'media', 0, $user_name . ' has shared a ' . $type . ' ' . $name . ' with you.', 'share_media');

			$notify->addRecentActivity('shared', 'Media', 0, $model->user_id, $name, $type);

		} else {
			$notify->addRecentActivity('added', 'Media', 0, $model->user_id, $name, $type);
		}

	}

	public function removeShare($id, $user_id, $is_folder) {
		$notify = Yii::$app->notify;
		$user = Yii::$app->user->identity;
		$model = self::find()->where(['user_id' => $user_id])->andWhere(['file_id' => $id])->andWhere(['is_folder' => $is_folder])->one();
		$user_name = $user->first_name . ' ' . $user->last_name;

		if ($is_folder == 1) {
			$type = 'folder';
			$media = MediaFolder::find()->where(['id' => $id])->one();
			$name = $media['name'];

		} else {
			$type = 'file';
			$media = Media::find()->where(['id' => $id])->one();
			$name = $media['title'];
		}

		if ($model->delete()) {

			if ($model->user_id != $user->id) {

				$notify->addNotify($user_id, 1, 'media', 0, $user_name . ' has revoked access for ' . $type . ' ' . $name . ' with you.', 'unshare_media');
				$notify->addRecentActivity('revoked access', 'Media', 0, $user_id, $name, $type);
			} else {
				$notify->addRecentActivity('remove', 'Media', 0, $user_id, $name, $type);

			}

		}
	}
	public function getFolderParents($folder_id, &$result = []) {
		$media_folder = MediaFolder::find()->where(['id' => $folder_id])->one();
		$result[] = $folder_id;
		if ($media_folder['parent_id'] != 0) {
			return $this->getFolderParents($media_folder['parent_id'], $result);
		}

		return ($result);
	}

	public function getFolderChilds($folder_id, &$result = []) {
		$media_folder = MediaFolder::find()->where(['parent_id' => $folder_id])->all();
		//print_r($media_folder);
		$result[] = $folder_id;
		foreach ($media_folder as $key => $value) {
			//echo $value->id, '<br/>', $folder_id . 'tt';
			$result[] = $value->id;
			$media_folder2 = MediaFolder::find()->where(['parent_id' => $value->id])->one();
			if ($media_folder2) {
				$this->getFolderChilds($media_folder2['id'], $result);
			}
		}

		return ($result);
	}

	public function getAccessIds() {

		$user = Yii::$app->user->identity;
		$file_ids = [];
		$own_file_ids = [];
		$folder_ids = [];
		$own_folder_ids = [];
		$model = self::find()->where(['user_id' => $user->id])->asArray()->all();
		if (count($model) > 0) {
			foreach ($model as $key => $value) {
				if ($value['is_folder'] == 0) {
					$media = Media::find()->where(['id' => $value['file_id']])->one();
					$file_ids[] = $value['file_id'];
					if ($media['media_folder_id'] != 0) {
						$rr = $this->getFolderParents($media['media_folder_id'], $folder_ids);
						//array_merge($folder_ids, $rr);
					}
				} else {

					$media_folder = MediaFolder::find()->where(['id' => $value['file_id']])->one();
					$folder_ids[] = $value['file_id'];
					if ($media_folder['parent_id'] != 0) {
						$rr2 = $this->getFolderParents($media_folder['id'], $folder_ids);
						//array_merge($folder_ids, $rr2);
					}
					$rr3 = [];
					//if ($media_folder['id'] > 0) {

					//echo $media_folder['id'], 'mm';
					$rr3 = $this->getFolderChilds($media_folder['id'], $own_folder_ids);
					//}

					//array_merge($own_folder_ids, $rr3);
					if (($key = array_search(0, $own_folder_ids)) !== false) {
						unset($own_folder_ids[$key]);
					}
					if (count($rr3) > 0) {
						$media2 = Media::find()->where(['IN', 'media_folder_id', $own_folder_ids])->andWhere(['!=', 'media_folder_id', 0])->all();
						foreach ($media2 as $k2 => $v2) {
							$own_file_ids[] = $v2['id'];
						}
					}

				}
			}
		}

		$result = ['file_ids' => $file_ids, 'folder_ids' => $folder_ids, 'own_folder_ids' => $own_folder_ids, 'own_file_ids' => $own_file_ids];

		return $result;

	}

	public function userNotInShare($file_id, $is_folder) {
		$share_table = self::tableName();
		$user_table = User::tableName();

		$l_user = Yii::$app->user->identity;
		$db = Yii::$app->db;
		$query = "SELECT pm.id FROM $user_table pm WHERE pm.id NOT IN (SELECT pd.user_id FROM $share_table pd WHERE pd.file_id = $file_id AND pd.is_folder = $is_folder) AND pm.id != $l_user->id";
		$users = $db->createCommand($query)->queryAll();

		return $users;
	}
	public function userInShare($file_id, $is_folder = 0) {
		$model = self::find()->where(['!=', 'user_id', Yii::$app->user->identity->id])->andwhere(['file_id' => $file_id])->andwhere(['is_folder' => $is_folder])->asArray()->all();
		return $model;
	}

	/*
		type=0 mean add button will show
		type= 1 means remove button will show
	*/

	public function userListTemplate($id, $user_id, $is_folder = 0, $type = 0) {
		$user = User::findOne($user_id);
		$cls = 'group-user-' . $user_id;
		if ($type == 1) {
			$btn = '<a href="javascript:removeUserFromShare(' . $id . ', ' . $user_id . ', ' . $is_folder . ')" class="btn btn-danger bg-theme-6 text-white px-2 box ' . $cls . ' " >
            		 <span class="w-5 h-5 flex items-center justify-center fa fa-minus" style="line-height: inherit;"></span>
            		</a>';
		} else {
			$btn = '<a href="javascript:addUserToShare(' . $id . ', ' . $user_id . ', ' . $is_folder . ')" class="btn btn-primary bg-theme-1 text-white px-2 box ' . $cls . '" >
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
              <div class="flex items-center user_name">
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

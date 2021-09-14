<?php

namespace app\controllers;
use app\models\MediaPermission;
use app\modules\admin\models\Media;
use app\modules\admin\models\MediaFolder;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

class GlobalController extends Controller {

	/**
	 * @var mixed
	 */
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

	public function actionUploadForm() {

		return $this->renderAjax('_upload');
	}

	public function actionUpload() {
		extract($_REQUEST);

		$fileName = 'file';

		if (isset($_FILES[$fileName])) {
			$file = UploadedFile::getInstanceByName($fileName);

			$fileInfo = pathinfo($file);
			$extension = $fileInfo['extension'];

			$newDir = Yii::getAlias('@media') . '/' . date('Y') . '/' . date('m');
			Yii::$app->getTable->make_dir($newDir);
			$name = time() . '.' . $file->extension;

			$saveFile = $file->saveAs($newDir . '/' . $name);
			if ($saveFile) {

				$encoded = \yii\helpers\Json::encode($file);

				$dencoded = \yii\helpers\Json::decode($encoded);

				$model = new Media;
				$model->title = $fileInfo['filename'];
				$model->alternate_text = $fileInfo['filename'];
				$model->caption = $fileInfo['filename'];
				$model->file_name = $name;

				$model->status = 1;
				$model->extension = strtolower($extension);
				if (isset($mediaFolderId) && $mediaFolderId > 0) {
					$model->media_folder_id = $mediaFolderId;
				}
				$model->created_at = date('Y-m-d H:i:s');
				$model->created_by = Yii::$app->user->identity->id;

				$model->save();
				$model2 = new MediaPermission();
				$model2->addShare($model->id, $model->created_by, 0);

				if ($extension == 'jpg' || $extension == 'png' || $extension == 'gif' || $extension == 'jpeg') {
					$thumnailSize1 = 150; // Yii::$app->getTable->settings('image', 'thumbnail_size');
					$thumnailSize2 = 340; //Yii::$app->getTable->settings('image', 'thumbnail_size2');

					Yii::$app->getTable->thumbnail_size($newDir, $name, $thumnailSize1, 0, 'thumb');
					Yii::$app->getTable->thumbnail_size($newDir, $name, $thumnailSize2, 0, 'large');
					Yii::$app->getTable->create_thumb($name, $extension, $model->id);
				} else {
					Yii::$app->getTable->create_thumb($name, $extension, $model->id);
				}

			}

		}

		return false;

	}

	public function actionDeleteImageFolder($id, $type) {
		$notify = Yii::$app->notify;
		if ($type == 'image') {
			$model = Media::findOne($id);
			$model->status = 2;
			$model->save();
			$notify->addRecentActivity('deleted', 'Media', 0, 0, $model->title, $type);
		}
		if ($type == 'folder') {
			$model = MediaFolder::find()->where(['id' => $id])->one();
			$model->status = 0;

			$model->save();
			$notify->addRecentActivity('deleted', 'Media', 0, 0, $model->name, $type);
		}

		return true;
	}

	public function actionMoveImageAndFolder($id, $mediaFolderId, $type) {
		$notify = Yii::$app->notify;
		if ($mediaFolderId == '') {
			$mediaFolderId = 0;
		}

		if ($type == 'image') {
			$model = Media::findOne($id);

			$model->media_folder_id = $mediaFolderId;

			$model->save();

			$notify->addRecentActivity('moved', 'Media', 0, 0, $model->title, $type);
		}

		if ($type == 'folder') {
			$model = MediaFolder::findOne($id);

			$model->parent_id = $mediaFolderId;

			$model->save();
			$notify->addRecentActivity('moved', 'Media', 0, 0, $model->name, $type);
		}

		return true;
	}

	public function actionMediaGallery($inputId = '', $inputType = '') {

		$this->layout = '@app/themes/admin/main';

		return $this->renderAjax('media', [
			'inputId' => $inputId,
			'inputType' => $inputType,
		]);
	}

	public function actionGetMediaJson($search = '', $offset = 0, $folderId = 0) {

		$media_permission = new MediaPermission();
		$media_permission_ids = $media_permission->getAccessIds();
		$user = Yii::$app->user->identity;
		$file_ids = $media_permission_ids['file_ids'];
		$own_file_ids = $media_permission_ids['own_file_ids'];
		$folder_ids = $media_permission_ids['folder_ids'];
		$own_folder_ids = $media_permission_ids['own_folder_ids'];
		$folder_ids = array_merge($folder_ids, $own_folder_ids);
		$file_ids = array_merge($file_ids, $own_file_ids);

		//print_r($own_folder_ids);
		$query = Media::find()->where(['status' => 1])->andWhere(['media_folder_id' => $folderId]);

		if ($search != '') {

			$cond = "`title` LIKE '%{$search}%'";
			$query->andWhere($cond);
		}

		if ($user->user_role != 'admin') {

			// $per_cond = '`id` IN (' . $file_ids . ')';
			$query->andWhere(['IN', 'id', $file_ids]);
		}

		$model = $query->offset($offset)->orderBy(['created_at' => SORT_DESC])->asArray()->all();

		$response = [];
		$folders = [];
		$result = [];

		if (count($model) > 0) {

			foreach ($model as $key => $value) {

				$response[$key] = $value;

				if ($value['extension'] == 'png' || $value['extension'] == 'jpeg' || $value['extension'] == 'jpg' || $value['extension'] == 'gif') {
					$url = Yii::$app->urlManager->createAbsoluteUrl('/uploads/media/' . date('Y', strtotime($value['created_at'])) . '/' . date('m', strtotime($value['created_at'])) . '/' . $value['file_name']);
				} else {
					$url = Yii::$app->urlManager->createAbsoluteUrl('/uploads/media/thumb/' . $value['thumb']);
				}

				$response[$key]['url'] = $url;

			}

		}

		$folders_q = MediaFolder::find()->where(['status' => 1])->andWhere(['parent_id' => $folderId]);
		if ($search != '') {

			$cond2 = "`name` LIKE '%{$search}%'";
			$folders_q->andWhere($cond2);
		}

		if ($user->user_role != 'admin') {

			//$per_cond2 = '`id` IN (' . $folder_ids . ')';
			$folders_q->andWhere(['IN', 'id', $folder_ids]);
		}

		$folders = $folders_q->orderBy(['sort_order' => SORT_DESC])->asArray()->all();

		foreach ($folders as $k => $v) {
			$folders[$k]['type'] = 'folder';
		}

		if ($folderId > 0) {

			$folderOne = MediaFolder::find()->where(['id' => $folderId])->one();
			$back_name = Yii::t('app', 'Move Up');

			$folders[] = ['id' => $folderOne['parent_id'], 'name' => $back_name, 'type' => 'link'];

		}
		//$folders_arr=array_reverse($folders);

		$result['file'] = $response;
		$result['total'] = count($model);

		$result['folder'] = array_reverse($folders);
		Yii::$app->response->format = 'json';

		return $result;

	}

	//create folder

	public function actionCreateMediaFolder($folderName, $parentId = 0) {

		$model = new MediaFolder();

		$model->name = $folderName;

		$model->sort_order = 1;
		$model->status = 1;
		if ($parentId == '') {
			$model->parent_id = 0;
		} else {
			$model->parent_id = $parentId;
		}
		$model->created_at = date('Y-m-d H:i:s');
		$model->created_by = Yii::$app->user->identity->id;

		$model->save();

		$model2 = new MediaPermission();
		$model2->addShare($model->id, $model->created_by, 1);

		return true;
	}

	public function actionUpdateMediaFolder($id, $folderName) {

		$model = MediaFolder::find()->where(['id' => $id])->one();

		$model->name = $folderName;
		$model->save();

		return true;
	}
	// download file

	public function actionDownloadFile($id) {
		$notify = Yii::$app->notify;
		$model = Media::find()->where(['status' => 1])->andWhere(['id' => $id])->asArray()->one();
		if ($model) {
			$file_ext = pathinfo($model['file_name'], PATHINFO_EXTENSION);
			$file_ext = strtolower($file_ext);
			$filename = $model['title'] . '.' . $file_ext;
			$file_url = Yii::$app->urlManager->createAbsoluteUrl('/uploads/media/' . date('Y', strtotime($model['created_at'])) . '/' . date('m', strtotime($model['created_at'])) . '/' . $model['file_name']);
			header('Content-Type: application/octet-stream');
			header("Content-Transfer-Encoding: Binary");
			header("Content-disposition: attachment; filename=\"" . $filename . "\"");
			readfile($file_url);

			$notify->addRecentActivity('downloaded', 'Media', 0, 0, $model->name, 'image');
		} else {
			throw new NotFoundHttpException('The requested page does not exist.');
		}
	}

	public function actionAddShare($id, $user_id, $is_folder) {

		$model = new MediaPermission();
		return $model->addShare($id, $user_id, $is_folder);
	}

	public function actionRemoveShare($id, $user_id, $is_folder) {

		$model = new MediaPermission();
		return $model->removeShare($id, $user_id, $is_folder);
	}

}

<?php

namespace app\controllers;

use app\models\Attachment;
use app\models\Task;
use Yii;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\UploadedFile;

/**
 * AttachmentController implements the CRUD actions for Task model.
 */
class AttachmentController extends Controller {
	public $layout = '@app/themes/admin/main';
	public $enableCsrfValidation = false;
	/**
	 * {@inheritdoc}
	 */
	public function behaviors() {
		return [
			'verbs' => [
				'class' => VerbFilter::className(),
				'actions' => [
					'delete' => ['POST'],
				],
			],
		];
	}

	public function actionUpload($type, $relation_id = '') {
		extract($_REQUEST);
		$fileName = 'file';
		$session = Yii::$app->session;

		if (isset($_FILES[$fileName])) {
			$file = UploadedFile::getInstanceByName($fileName);

			if ($relation_id != '') {
				$task_relation_id = $relation_id;
			} else {

				if ($type == 'task') {

					if (isset($session['task_relation_id'])) {
						$task_relation_id = $session['task_relation_id'];
					} else {
						$session['task_relation_id'] = date('YmdHis');
						$task_relation_id = $session['task_relation_id'];
					}
				}

				if ($type == 'project') {

					if (isset($session['project_relation_id'])) {
						$task_relation_id = $session['project_relation_id'];
					} else {
						$session['project_relation_id'] = date('YmdHis');
						$task_relation_id = $session['project_relation_id'];
					}
				}

				if ($type == 'leave') {

					if (isset($session['leave_relation_id'])) {
						$task_relation_id = $session['leave_relation_id'];
					} else {
						$session['leave_relation_id'] = date('YmdHis');
						$task_relation_id = $session['leave_relation_id'];
					}
				}
			}

			$fileInfo = pathinfo($file);
			$extension = $fileInfo['extension'];

			$newDir = 'web/' . $type . '/' . $task_relation_id;
			Yii::$app->getTable->make_dir($newDir);
			$name = time() . rand(0, 100) . '.' . $file->extension;

			$saveFile = $file->saveAs($newDir . '/' . $name);
			if ($saveFile) {

				$model = new Attachment;
				$model->relation_id = $task_relation_id;
				$model->file_name = $name;
				$model->type = $type;
				$model->extension = $extension;
				$model->save();

				/*if ($extension == 'jpg' || $extension == 'png' || $extension == 'gif' || $extension == 'jpeg') {
					$thumnailSize1 = 150; // Yii::$app->getTable->settings('image', 'thumbnail_size');
					$thumnailSize2 = 340; //Yii::$app->getTable->settings('image', 'thumbnail_size2');

					Yii::$app->getTable->thumbnail_size($newDir, $name, $thumnailSize1, 0, 'thumb');

				}*/
				$get_thumb = $this->create_thumb($name, $extension, $model->id);

				$model->thumb = $get_thumb;
				$model->save();

			}

		}

		return false;

	}

	public function actionDeleteAttachment($id, $type) {
		$model = Attachment::find()->where(['id' => $id])->one();

		unlink(Yii::$app->basePath . '/web/' . $type . '/' . $model->relation_id . '/' . $model->file_name);

		$model->delete();

		return true;
	}

	public function create_thumb($filename, $ext, $id) {

		if ($ext == 'jpg' || $ext == 'png' || $ext == 'gif' || $ext == 'jpeg') {

			$thumb = $filename;

		} elseif ($ext == 'mp3' || $ext == 'wav' || $ext == 'ogg' || $ext == 'acc') {
			$thumb = 'audio.png';

		} elseif ($ext == 'mp4' || $ext == 'mkv' || $ext == 'avi' || $ext == 'webm' || $ext == 'mov') {
			$thumb = 'movie.png';

		} elseif ($ext == 'jar' || $ext == 'jad') {
			$thumb = 'java.png';

		} elseif ($ext == 'apk' || $ext == 'obb') {
			$thumb = 'apk.png';

		} elseif ($ext == 'zip' || $ext == '7z' || $ext == 'rar') {
			$thumb = 'zip.ico';

		} elseif ($ext == 'xlsx' || $ext == 'xl' || $ext == 'xls' || $ext == 'xltx' || $ext == 'xlt') {
			$thumb = 'exel.png';

		} elseif ($ext == 'docs' || $ext == 'doc' || $ext == 'dotx' || $ext == 'docm' || $ext == 'docx') {
			$thumb = 'word.ico';

		} elseif ($ext == 'pptx' || $ext == 'ppt') {
			$thumb = 'ppt.ico';

		} elseif ($ext == 'pdf') {
			$thumb = 'pdf.png';

		} else {
			$thumb = $filename;
		}

		return $thumb;
	}

}

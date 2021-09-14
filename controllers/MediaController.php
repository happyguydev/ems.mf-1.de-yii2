<?php

namespace app\controllers;

use app\models\MediaPermission;
use app\models\Notification;
use app\modules\admin\models\Media;
use app\modules\admin\models\MediaFolder;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

class MediaController extends \yii\web\Controller {
	public $layout = '@app/themes/admin/main';
	public function behaviors() {
		return [
			'verbs' => [
				'class' => VerbFilter::className(),
				'actions' => [
					'delete' => ['POST'],
				],
			],
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
		$user = Yii::$app->user->identity;
		$notification = Notification::find()->where(['read' => 1])->andWhere(['action' => ['share_media', 'unshare_media']])->andWhere(['user_id' => $user->id])->all();
		if (count($notification) > 0) {
			foreach ($notification as $key => $value) {
				$value->read = 0;
				$value->save();
			}
		}
		return $this->render('index');
	}

	public function actionShareList($id, $is_folder = 0) {
		$per_model = new MediaPermission();
		$d = $per_model->userNotInShare($id, $is_folder);

		if ($is_folder == 0) {
			$media = Media::findOne($id);
		} else {
			$media = MediaFolder::findOne($id);
		}

		$data3 = "";

		if ($media) {

			if (Yii::$app->user->identity->user_role == 'admin' || $media->created_by == Yii::$app->user->identity->id) {
				$dd = $per_model->userInShare($id, $is_folder);

				foreach ($dd as $key4 => $value4) {

					$data3 .= $per_model->userListTemplate($id, $value4['user_id'], $is_folder, 1);

				}
			}
		}
		foreach ($d as $key3 => $value3) {

			$data3 .= $per_model->userListTemplate($id, $value3['id'], $is_folder, 0);

		}

		$data3 .= "<script>$('#searchbox').keyup(function () {
    var valThis = this.value.toLowerCase(),
        lenght  = this.value.length;


    $('#share-list-result > .chat-user-list ').each(function () {
        var text  = $(this).find('.user_name').text();
            textL = text.toLowerCase();
            (textL.indexOf(valThis) >= 0) ? $(this).show() : $(this).hide();

    });

});</script>";
		return $data3;
	}

}

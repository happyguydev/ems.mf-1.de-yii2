<?php

namespace app\modules\mailbox\controllers;

use app\modules\mailbox\models\Imap;
use app\modules\mailbox\models\Mailbox;
use app\modules\mailbox\models\Smtp;
use app\modules\mailbox\models\UserEmail;
use Yii;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * MailboxController implements the CRUD actions for Mailbox model.
 */
class InboxController extends Controller {
	/**
	 * {@inheritdoc}
	 */
	public $enableCsrfValidation = false;
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

	/**
	 * Lists all Mailbox models.
	 * @return mixed
	 */
	public function actionGetMails($force = 0) {
		ignore_user_abort(true);
		$session = Yii::$app->session;
		$minutes = 0;
		$type = 'ALL';
		if (isset($session['UserEmail'])) {
			$user_email = UserEmail::find()->where(['id' => $session['UserEmail']])->one();
			if ($user_email) {
				$type = ($user_email->last_sync) ? 'UNSEEN' : 'ALL';
				$LastUpdate = strtotime($user_email->last_sync);
				$user_email->last_sync = date('Y-m-d H:i:s');
				$minutes = (time() - $LastUpdate) / 60;
				if ($minutes >= 10) {
					$user_email->save();

				}
			}
		}
		if ($force == 1) {
			$minutes = 0;
			$type = 'UNSEEN';
		}
		if ($minutes == 0 || $minutes >= 10) {
			$IMAP = new Imap();
			//$IMAP->readInbox($type);
		}
		return true;
	}

	public function actionReadAllMails($act = '') {
		ignore_user_abort(true);
		//set_time_limit(1000);
		//$act = ($act == '') ? 'inbox' : $act;
		$session = Yii::$app->session;
		$session['last_sync_time'] = time();
		$IMAP = new Imap();
		$fid = $IMAP->getMailboxes($act);
		return true;

	}
	public function actionReadFolder($act = '') {
		$act = ($act == '') ? 'inbox' : $act;
		$session = Yii::$app->session;
		$session['last_sync_time'] = time();
		$IMAP = new Imap();
		$fid = $IMAP->getMailboxes($act);
		if ($fid) {
			return $this->redirect(['index', 'act' => $act]);
		}

	}
	public function actionIndex($act = '', $page = 1, $fid = '') {
		return $this->redirect(['../inbox']);

		$session = Yii::$app->session;
		if (!isset($session['UserEmail'])) {
			$user_email = UserEmail::find()->where(['user_id' => Yii::$app->user->identity->id])->andWhere(['status' => 1])->one();
			if ($user_email) {
				$session['UserEmail'] = $user_email->id;
			}
		}
		if ($fid == '') {
			$IMAP = new Imap();
			$fid = $IMAP->getFolderId();
		}
		// $this->actionGetMails();
		if ($page <= 1) {
			$page = 1;
		}
		$query = Mailbox::find()->where(['user_email_id' => $session['UserEmail']]);
		if (strpos(strtolower($act), 'trash') !== false || strpos(strtolower($act), 'bin') !== false) {
			$query->andWhere(['trashed' => 1]);
			$query->orWhere(['folder_id' => $fid]);
		} else {
			// } else {
			// 	if ($act == 'marked') {
			// 		$query->andWhere(['flagged' => 1]);
			// 	} elseif ($act == 'important') {
			// 		$query->andWhere(['bookmarked' => 1]);
			// 	} elseif ($act == 'draft') {
			// 		$query->andWhere(['status' => Mailbox::STATUS_DRAFT]);
			// 	} elseif ($act == 'sent') {
			// 		$query->andWhere(['status' => Mailbox::STATUS_SENT]);
			// 	} else {
			// 		$query->andWhere(['status' => Mailbox::STATUS_INBOX]);
			// 	}
			$query->andWhere(['trashed' => 0]);
			$query->andWhere(['deleted' => 0]);
			if (strpos(strtolower($act), 'all') !== false) {} else {
				$query->andWhere(['folder_id' => $fid]);
			}

		}
		$limit = 20;
		$offset = ($page - 1) * $limit;
		$countQuery = clone $query;
		$total = $countQuery->count();
		$model = $query->offset($offset)
			->limit($limit)
			->orderBy(['udate' => SORT_DESC])
			->all();

		return $this->render('index', [
			'model' => $model,
			'file' => 'list',
			'page' => $page,
			'total' => $total,
			'limit' => $limit,
			'act' => $act,
		]);

		//return $this->redirect(['inbox', 'type' => 1]);
	}
	public function actionSend() {
		$smtp = new Smtp();

		$smtp->updateData($_POST);

		$this->redirect(['index']);
	}
	public function actionSetUserEmail($id) {
		error_reporting(0);
		$model = UserEmail::findOne($id);
		$session = Yii::$app->session;
		$session['UserEmail'] = $id;
		try {
			$IMAP = new Imap();
			$fid = $IMAP->readFolder();
		} catch (Exception $e) {
			return $this->redirect(['index']);
		}

		return $this->redirect(['index']);
		//$this->actionGetMails(1);
	}

	/**
	 * Displays a single Mailbox model.
	 * @param integer $id
	 * @return mixed
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	public function actionView($id) {
		$model = $this->findModel($id);
		$model->seen = 1;
		$model->save(false);
		return $this->render('index', [
			'model' => $model,
			'file' => 'view',
		]);
	}
	public function actionIframeView($id) {
		return $this->renderAjax('_view', [
			'model' => $this->findModel($id),
			'file' => 'view',
		]);
	}
	public function actionUpdateMark($id, $name, $val) {
		$model = $this->findModel($id);
		$value = ($val == 1) ? 0 : 1;
		if ($name == 'star') {
			$model->flagged = $value;
		} elseif ($name == 'bookmark') {
			$model->bookmarked = $value;
		}
		$model->save();
		return $value;
	}

	public function actionUpdateAll($ids, $type) {
		$ids = base64_decode($ids);
		if ($ids != '') {
			$cond = " id IN ($ids) ";
			$model = Mailbox::find()->where($cond)->all();

			foreach ($model as $key => $value) {
				if ($type == 'star') {
					$value->flagged = ($value->flagged == 1) ? 0 : 1;
				} elseif ($type == 'bookmark') {
					$value->bookmarked = ($value->bookmarked == 1) ? 0 : 1;
				} elseif ($type == 'delete') {
					$value->trashed = 1;
				} elseif ($type == 'remove') {
					$value->trashed = 2; // parmanetly deleted
				}
				$value->save();
			}
		}
		return $this->redirect(Yii::$app->request->referrer);
	}

	/**
	 * Creates a new Mailbox model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionCreate() {
		$model = new Mailbox();

		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			return $this->redirect(['view', 'id' => $model->id]);
		}

		return $this->render('create', [
			'model' => $model,
		]);
	}

	/**
	 * Updates an existing Mailbox model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id
	 * @return mixed
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	public function actionUpdate($id) {
		$model = $this->findModel($id);

		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			return $this->redirect(['view', 'id' => $model->id]);
		}

		return $this->render('update', [
			'model' => $model,
		]);
	}

	/**
	 * Deletes an existing Mailbox model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	public function actionDelete($id) {
		$this->findModel($id)->delete();

		return $this->redirect(['index']);
	}

	/**
	 * Finds the Mailbox model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return Mailbox the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id) {
		if (($model = Mailbox::findOne($id)) !== null) {
			return $model;
		}

		throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
	}
}

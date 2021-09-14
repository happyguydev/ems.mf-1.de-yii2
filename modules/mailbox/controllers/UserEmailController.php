<?php

namespace app\modules\mailbox\controllers;

use app\modules\mailbox\models\Imap;
use app\modules\mailbox\models\UserEmail;
use Yii;
use yii\data\Pagination;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * UserEmailController implements the CRUD actions for UserEmail model.
 */
class UserEmailController extends Controller {
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

	/**
	 * Lists all UserEmail models.
	 * @return mixed
	 */
	public function actionIndex() {
		$pageSize = 10;
		$page_no = isset($page) ? $page : 1;
		$logged_user = Yii::$app->user->identity;
		$query = UserEmail::find();

		$cond = '`user_id`= ' . $logged_user->id;
		$query->where($cond);

		$countQuery = clone $query;
		$pages = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => $pageSize]);
		$model = $query->offset($pages->offset)
			->limit($pages->limit)
			->all();

		$serial_number = ($page_no - 1) * $pageSize;
		return $this->render('index', [
			'model' => $model,
			'pages' => $pages,
			'serial_number' => $serial_number,
		]);
	}

	/**
	 * Displays a single UserEmail model.
	 * @param integer $id
	 * @return mixed
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	public function actionView($id) {
		return $this->redirect(['index']);
	}

	/**
	 * Creates a new UserEmail model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionCreate() {
		$session = Yii::$app->session;
		$model = new UserEmail();
		$model->user_id = Yii::$app->user->identity->id;

		if ($model->load(Yii::$app->request->post())) {
			$model->password = base64_encode($model->password);
			$model->save();
			$session['UserEmail'] = $model->id;
			$IMAP = new Imap();
			$IMAP->getMailboxes();
			$model->last_sync = date('Y-m-d H:i:s');
			$model->save();
			return $this->redirect(['view', 'id' => $model->id]);
		}

		return $this->render('create', [
			'model' => $model,
		]);
	}

	/**
	 * Updates an existing UserEmail model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id
	 * @return mixed
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	public function actionUpdate($id) {
		$model = $this->findModel($id);

		$model->password = base64_decode($model->password);

		if ($model->load(Yii::$app->request->post())) {
			$model->password = base64_encode($model->password);
			$model->save();
			return $this->redirect(['view', 'id' => $model->id]);
		}

		return $this->render('update', [
			'model' => $model,
		]);
	}

	/**
	 * Deletes an existing UserEmail model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	public function actionDelete($id) {
		$this->findModel($id)->delete();

		$session = Yii::$app->session;
		if (isset($session['UserEmail'])) {
			$user_email = UserEmail::find()->where(['user_id' => Yii::$app->user->identity->id])->andWhere(['status' => 1])->one();
			if ($user_email) {
				$session['UserEmail'] = $user_email->id;
			} else {
				$session['UserEmail'] = 0;
			}
		}
		return $this->redirect(['index']);
	}

	/**
	 * Finds the UserEmail model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return UserEmail the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id) {
		if (($model = UserEmail::findOne($id)) !== null) {
			return $model;
		}

		throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
	}
}

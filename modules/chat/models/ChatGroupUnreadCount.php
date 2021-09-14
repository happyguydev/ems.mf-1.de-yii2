<?php

namespace app\modules\chat\models;

use Yii;

/**
 * This is the model class for table "{{%chat_group_unread_count}}".
 *
 * @property int $id
 * @property int $user_id
 * @property int $group_id
 * @property int $total
 * @property string $date
 */
class ChatGroupUnreadCount extends \yii\db\ActiveRecord {
	/**
	 * {@inheritdoc}
	 */
	public static function tableName() {
		return '{{%chat_group_unread_count}}';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules() {
		return [
			[['user_id', 'group_id'], 'required'],
			[['user_id', 'group_id', 'total'], 'integer'],
			[['date'], 'safe'],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels() {
		return [
			'id' => Yii::t('app', 'ID'),
			'user_id' => Yii::t('app', 'User ID'),
			'group_id' => Yii::t('app', 'Group ID'),
			'total' => Yii::t('app', 'Total'),
			'date' => Yii::t('app', 'Date'),
		];
	}

	public function setCount($user_id, $group_id) {
		$old_data = self::find()->where(['user_id' => $user_id])->andWhere(['group_id' => $group_id])->one();

		if ($old_data) {
			$model = $old_data;
		} else {
			$model = new ChatGroupUnreadCount();
			$model->group_id = $group_id;
			$model->user_id = $user_id;
		}

		$model->total = $model->total + 1;
		$model->date = date('Y-m-d H:i:s');
		$model->save();
	}

	public function getCount($group_id) {

		$user_id = Yii::$app->user->identity->id;
		$total = 0;

		$model = self::find()->where(['user_id' => $user_id])->andWhere(['group_id' => $group_id])->one();
		if ($model) {
			$total = $model['total'];
		}

		return $total;

	}

	public function markRead($group_id) {
		$user_id = Yii::$app->user->identity->id;

		$model = self::find()->where(['user_id' => $user_id])->andWhere(['group_id' => $group_id])->one();
		if ($model) {
			$model->total = 0;
			$model->date = date('Y-m-d H:i:s');
			$model->save();
		}
		return true;

	}
}

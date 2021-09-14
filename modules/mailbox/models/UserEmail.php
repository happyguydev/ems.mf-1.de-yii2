<?php

namespace app\modules\mailbox\models;

use app\modules\mailbox\models\EmailClient;
use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%user_email}}".
 *
 * @property int $id
 * @property int $user_id
 * @property int $email_client_id
 * @property int $email
 * @property int $password
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property int $status
 */
class UserEmail extends \yii\db\ActiveRecord {
	/**
	 * {@inheritdoc}
	 */
	public static function tableName() {
		return '{{%user_email}}';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules() {
		return [
			[['email_client_id', 'email', 'password'], 'required'],
			[['user_id', 'email_client_id', 'status'], 'integer'],
			[['created_at', 'updated_at', 'email', 'password', 'last_sync'], 'safe'],
			['email', 'email'],
		];
	}

	public function behaviors() {
		return [
			[
				'class' => TimestampBehavior::className(),
				'createdAtAttribute' => 'created_at',
				'updatedAtAttribute' => 'updated_at',
				'value' => date('Y-m-d H:i:s'),
			],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels() {
		return [
			'id' => Yii::t('app', 'ID'),
			'user_id' => Yii::t('app', 'User ID'),
			'email_client_id' => Yii::t('app', 'Email Client'),
			'email' => Yii::t('app', 'Email'),
			'password' => Yii::t('app', 'Password'),
			'created_at' => Yii::t('app', 'Created At'),
			'updated_at' => Yii::t('app', 'Updated At'),
			'status' => Yii::t('app', 'Status'),
		];
	}
	public function getEmailClient() {
		return $this->hasOne(EmailClient::className(), ['id' => 'email_client_id']);
	}

}

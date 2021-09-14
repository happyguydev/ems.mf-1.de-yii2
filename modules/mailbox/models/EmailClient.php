<?php

namespace app\modules\mailbox\models;

use Yii;

/**
 * This is the model class for table "{{%email_client}}".
 *
 * @property int $id
 * @property string $title
 * @property string $imap_host_url
 */
class EmailClient extends \yii\db\ActiveRecord {
	/**
	 * {@inheritdoc}
	 */
	public static function tableName() {
		return '{{%email_client}}';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules() {
		return [
			[['title', 'imap_host_url', 'smtp_host', 'smtp_port', 'smtp_encryption'], 'required'],
			[['title', 'imap_host_url'], 'string', 'max' => 255],
			[['smtp_host', 'smtp_port', 'smtp_encryption'], 'safe'],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels() {
		return [
			'id' => Yii::t('app', 'ID'),
			'title' => Yii::t('app', 'Title'),
			'imap_host_url' => Yii::t('app', 'Imap Host Url'),
		];
	}
}

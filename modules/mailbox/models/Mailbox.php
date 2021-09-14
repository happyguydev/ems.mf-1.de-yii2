<?php

namespace app\modules\mailbox\models;

use Yii;

/**
 * This is the model class for table "{{%mailbox}}".
 *
 * @property int $id
 * @property int|null $email_client_id
 * @property string|null $subject
 * @property string|null $email_from
 * @property string|null $email_to
 * @property string|null $cc
 * @property string|null $bcc
 * @property string|null $email_date
 * @property string|null $message_id
 * @property resource|null $body
 * @property int $status
 * @property int|null $email_size
 * @property string|null $uid
 * @property string|null $msgno
 * @property string $recent
 * @property int $flagged
 * @property int $answered
 * @property int $deleted
 * @property int $seen
 * @property int $draft
 * @property int|null $udate
 * @property string $created_at
 */
class Mailbox extends \yii\db\ActiveRecord {
	/**
	 * {@inheritdoc}
	 */
	// status = 1 => inbox
	// status = 2 => sent
	//status = 3 => draft
	const STATUS_DRAFT = 3;
	const STATUS_SENT = 2;
	const STATUS_INBOX = 1;
	public static function tableName() {
		return '{{%mailbox}}';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules() {
		return [
			[['email_client_id', 'status', 'email_size', 'flagged', 'answered', 'deleted', 'seen', 'draft', 'udate', 'bookmarked', 'trashed', 'user_email_id', 'folder_id'], 'integer'],
			[['subject', 'body'], 'string'],
			[['status'], 'required'],
			[['created_at'], 'safe'],
			[['email_from', 'email_to', 'cc', 'bcc', 'email_date', 'message_id', 'uid', 'msgno', 'recent'], 'string', 'max' => 255],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels() {
		return [
			'id' => Yii::t('app', 'ID'),
			'email_client_id' => Yii::t('app', 'Email Client ID'),
			'subject' => Yii::t('app', 'Subject'),
			'email_from' => Yii::t('app', 'Email From'),
			'email_to' => Yii::t('app', 'Emai To'),
			'cc' => Yii::t('app', 'Cc'),
			'bcc' => Yii::t('app', 'Bcc'),
			'email_date' => Yii::t('app', 'Email Date'),
			'message_id' => Yii::t('app', 'Message ID'),
			'body' => Yii::t('app', 'Body'),
			'status' => Yii::t('app', 'Status'),
			'email_size' => Yii::t('app', 'Email Size'),
			'uid' => Yii::t('app', 'Uid'),
			'msgno' => Yii::t('app', 'Msgno'),
			'recent' => Yii::t('app', 'Recent'),
			'flagged' => Yii::t('app', 'Flagged'),
			'answered' => Yii::t('app', 'Answered'),
			'deleted' => Yii::t('app', 'Deleted'),
			'seen' => Yii::t('app', 'Seen'),
			'draft' => Yii::t('app', 'Draft'),
			'udate' => Yii::t('app', 'Udate'),
			'created_at' => Yii::t('app', 'Created At'),
		];
	}
}

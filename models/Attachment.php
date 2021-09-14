<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%attachment}}".
 *
 * @property int $id
 * @property string|null $relation_id
 * @property string|null $file_name
 * @property string|null $type
 */
class Attachment extends \yii\db\ActiveRecord {
	/**
	 * {@inheritdoc}
	 */
	public static function tableName() {
		return '{{%attachment}}';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules() {
		return [
			[['type'], 'string'],
			[['relation_id'], 'string', 'max' => 30],
			[['file_name'], 'string', 'max' => 255],
			['extension', 'safe'],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels() {
		return [
			'id' => Yii::t('app', 'ID'),
			'relation_id' => Yii::t('app', 'Relation ID'),
			'file_name' => Yii::t('app', 'File Name'),
			'type' => Yii::t('app', 'Type'),
			'extension' => Yii::t('app', 'Extension'),
		];
	}
}

<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%contract_attachment}}".
 *
 * @property int $id
 * @property int $contract_id
 * @property string $file_name
 * @property string|null $date
 */
class ContractAttachment extends \yii\db\ActiveRecord {
	/**
	 * {@inheritdoc}
	 */
	public static function tableName() {
		return '{{%contract_attachment}}';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules() {
		return [
			[['contract_id'], 'integer'],
			[['date', 'thumb'], 'safe'],
			[['file_name'], 'string', 'max' => 255],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels() {
		return [
			'id' => Yii::t('app', 'ID'),
			'contract_id' => Yii::t('app', 'Contract ID'),
			'file_name' => Yii::t('app', 'File Name'),
			'date' => Yii::t('app', 'Date'),
		];
	}
}

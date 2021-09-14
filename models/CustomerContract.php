<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%customer_contract}}".
 *
 * @property int $id
 * @property int $customer_id
 * @property string $title
 * @property string|null $description
 * @property string $start_date
 * @property string $end_date
 * @property string $created_at
 * @property string|null $updated_at
 */
class CustomerContract extends \yii\db\ActiveRecord {
	/**
	 * {@inheritdoc}
	 */
	public static function tableName() {
		return '{{%customer_contract}}';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules() {
		return [
			[['customer_id'], 'integer'],
			[['title', 'contract_amount', 'start_date', 'end_date', 'status', 'issue_date', 'paid_date', 'bill_number'], 'required'],
			[['description'], 'string'],
			[['start_date', 'end_date', 'created_at', 'updated_at', 'status', 'issue_date', 'paid_date', 'bill_number'], 'safe'],
			['contract_amount', 'number'],
			[['title'], 'string', 'max' => 255],
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
			'customer_id' => Yii::t('app', 'Customer ID'),
			'title' => Yii::t('app', 'Title'),
			'description' => Yii::t('app', 'Description'),
			'contract_amount' => Yii::t('app', 'Contract Amount'),
			'issue_date' => Yii::t('app', 'Issue Date Of Bill'),
			'paid_date' => Yii::t('app', 'Paid Date'),
			'bill_number' => Yii::t('app', 'Bill Number'),
			'start_date' => Yii::t('app', 'Start Date'),
			'end_date' => Yii::t('app', 'End Date'),
			'created_at' => Yii::t('app', 'Created At'),
			'updated_at' => Yii::t('app', 'Updated At'),
			'status' => Yii::t('app', 'Status'),
		];
	}
}

<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%appointment}}".
 *
 * @property int $id
 * @property string|null $title
 * @property string|null $start_date_time
 * @property string|null $end_date_time
 * @property string|null $bg_color
 * @property string|null $text_color
 * @property string $created_at
 * @property int $created_by
 * @property string|null $updated_at
 * @property int|null $updated_by
 */
class Appointment extends \yii\db\ActiveRecord {
	/**
	 * {@inheritdoc}
	 */
	public static function tableName() {
		return '{{%appointment}}';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules() {
		return [
			[['title', 'start_date_time', 'end_date_time', 'bg_color', 'text_color', 'group_id'], 'required'],
			[['start_date_time', 'end_date_time', 'created_at', 'updated_at', 'group_id'], 'safe'],
			[['created_by', 'updated_by'], 'integer'],
			[['title'], 'string', 'max' => 255],
			[['bg_color', 'text_color'], 'string', 'max' => 30],
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
			'title' => Yii::t('app', 'Title'),
			'start_date_time' => Yii::t('app', 'Start Date'),
			'end_date_time' => Yii::t('app', 'End Date'),
			'bg_color' => Yii::t('app', 'Background Color'),
			'text_color' => Yii::t('app', 'Text Color'),
			'created_at' => Yii::t('app', 'Created At'),
			'created_by' => Yii::t('app', 'Created By'),
			'updated_at' => Yii::t('app', 'Updated At'),
			'updated_by' => Yii::t('app', 'Updated By'),
		];
	}
}

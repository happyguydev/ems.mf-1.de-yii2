<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%calendar_group}}".
 *
 * @property int $id
 * @property string|null $title
 * @property int $status
 * @property string|null $date
 */
class CalendarGroup extends \yii\db\ActiveRecord {
	/**
	 * {@inheritdoc}
	 */
	public $assign_to;
	public static function tableName() {
		return '{{%calendar_group}}';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules() {
		return [
			[['title', 'bg_color', 'text_color'], 'required'],
			[['date', 'created_by', 'bg_color', 'text_color', 'assign_to'], 'safe'],
			[['title'], 'string', 'max' => 255],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels() {
		return [
			'id' => Yii::t('app', 'ID'),
			'title' => Yii::t('app', 'Title'),
			'date' => Yii::t('app', 'Date'),
		];
	}
}

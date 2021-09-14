<?php

namespace app\modules\admin\models;

use Yii;

/**
 * This is the model class for table "{{%general_setting}}".
 *
 * @property integer $Id
 * @property string $type_name
 * @property string $type_label
 * @property string $setting_name
 * @property string $setting_label
 * @property string $setting_value
 */
class General extends \yii\db\ActiveRecord {

	public $file;
	/**
	 * @inheritdoc
	 */
	public static function tableName() {
		return '{{%general_setting}}';
	}

	/**
	 * @inheritdoc
	 */
	public function rules() {
		return [
			[['setting_value'], 'string'],
			[['type_name', 'type_label', 'setting_name', 'setting_label'], 'string', 'max' => 255],
			[['setting_name'], 'unique'],
			['file', 'file'],
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels() {
		return [
			'id' => Yii::t('app', 'ID'),
			'type_name' => Yii::t('app', 'Type Name'),
			'type_label' => Yii::t('app', 'Type Label'),
			'setting_name' => Yii::t('app', 'Setting Name'),
			'setting_label' => Yii::t('app', 'Setting Label'),
			'setting_value' => Yii::t('app', 'Setting Value'),
		];
	}
}

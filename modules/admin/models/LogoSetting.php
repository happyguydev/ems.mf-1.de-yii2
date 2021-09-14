<?php

namespace app\modules\admin\models;

use Yii;

/**
 * This is the model class for table "tbl_logo_setting".
 *
 * @property integer $id
 * @property string $setting_name
 * @property string $setting_value
 */
class LogoSetting extends \yii\db\ActiveRecord {
	public $file;
	/**
	 * @inheritdoc
	 */
	public static function tableName() {
		return '{{%logo_setting}}';
	}

	/**
	 * @inheritdoc
	 */
	public function rules() {
		return [
			[['setting_value'], 'string'],
			[['setting_name'], 'string', 'max' => 255],
			[['setting_name'], 'unique'],
			[['setting_size'], 'number'],
			['file', 'file'],
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels() {
		return [
			'id' => Yii::t('app', 'ID'),
			'setting_name' => Yii::t('app', 'Setting Name'),
			'setting_value' => Yii::t('app', 'Setting Value'),
			'file' => Yii::t('app', 'Upload File'),
		];
	}
}

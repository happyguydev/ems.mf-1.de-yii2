<?php

namespace app\modules\admin\models;

use Yii;

/**
 * This is the model class for table "{{%media_folder}}".
 *
 * @property integer $id
 * @property integer $parent_id
 * @property string $name
 * @property integer $sort_order
 * @property integer $status
 */
class MediaFolder extends \yii\db\ActiveRecord {
	/**
	 * @inheritdoc
	 */
	public static function tableName() {
		return '{{%media_folder}}';
	}

	/**
	 * @inheritdoc
	 */
	public function rules() {
		return [
			[['parent_id', 'sort_order', 'created_by'], 'integer'],
			[['name'], 'required'],
			[['status', 'created_at'], 'safe'],
			[['name'], 'string', 'max' => 255],
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels() {
		return [
			'id' => Yii::t('app', 'ID'),
			'parent_id' => Yii::t('app', 'Parent ID'),
			'name' => Yii::t('app', 'Name'),
			'sort_order' => Yii::t('app', 'Sort Order'),
			'status' => Yii::t('app', 'Status'),
		];
	}
}

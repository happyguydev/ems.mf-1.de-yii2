<?php

namespace app\modules\admin\models;

use Yii;

/**
 * This is the model class for table "{{%country}}".
 *
 * @property int $id
 * @property string $name
 * @property string|null $code
 * @property string|null $phone_prefix
 * @property int|null $status
 */
class Country extends \yii\db\ActiveRecord {
	/**
	 * {@inheritdoc}
	 */
	public static function tableName() {
		return '{{%country}}';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules() {
		return [
			[['name', 'status', 'code', 'phone_prefix'], 'required'],
			[['status'], 'integer'],
			[['name'], 'string', 'max' => 100],
			[['code', 'phone_prefix'], 'string', 'max' => 10],
		];
	}

	public function afterFind() {

		$action = Yii::$app->controller->action->id;

		if ($action == 'index') {
			$this->status = Yii::$app->Utility->getStatus($this->status);

		}

	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels() {
		return [
			'id' => 'ID',
			'name' => 'Name',
			'code' => 'Code',
			'phone_prefix' => 'Phone Prefix',
			'status' => 'Status',
		];
	}
}

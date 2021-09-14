<?php

namespace app\modules\admin\models;
use app\models\User;
use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "media".
 *
 * @property integer $id
 * @property string $title
 * @property string $description
 * @property string $alternate_text
 * @property string $caption
 * @property string $file_name
 * @property string $extension
 * @property integer $status 0=>'enable','1'=>disable,'2'=>'delete'
 * @property integer $created_by
 * @property string $created_at
 *
 * @property User $createdBy
 */
class Media extends \yii\db\ActiveRecord {
	public $file;
	/**
	 * @inheritdoc
	 */
	public static function tableName() {
		return '{{%media}}';
	}

	/**
	 * @inheritdoc
	 */
	public function rules() {
		return [
			[['description'], 'string'],
			[['title', 'status'], 'required'],
			[['created_by', 'media_folder_id', 'create_for'], 'integer'],
			[['status', 'created_at', 'thumb'], 'safe'],
			[['title', 'alternate_text', 'caption'], 'string', 'max' => 100],
			[['file_name'], 'string', 'max' => 255],
			[['extension'], 'string', 'max' => 4],
			[['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
			[['file'], 'file'],

		];
	}

	public function behaviors() {
		return [
			[
				'class' => TimestampBehavior::className(),
				'createdAtAttribute' => 'created_at',
				'updatedAtAttribute' => false,
				'value' => date('Y-m-d H:i:s'),
			],
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels() {
		return [
			'id' => 'ID',
			'title' => 'Title',
			'description' => 'Description',
			'alternate_text' => 'Alternate Text',
			'caption' => 'Caption',
			'file_name' => 'File',
			'thumb' => 'Thumb',
			'extension' => 'Extension',
			'status' => 'Status',
			'created_by' => 'Created By',
			'created_at' => 'Created At',
			'create_for' => 'Create For',
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getCreatedBy() {
		return $this->hasOne(User::className(), ['id' => 'created_by']);
	}

	public function getCreatedFor() {
		return $this->hasOne(User::className(), ['id' => 'created_for']);
	}

	public function getThumb($model) {
		$fileName = $model->file_name;
		$ext = $model->extension;

		if ($ext == 'jpg' || $ext == 'png' || $ext == 'gif' || $ext == 'jpeg') {

			$getThumbFile = explode('.', $fileName);
			$thumbFileName = $getThumbFile[0] . '_thumb.' . $getThumbFile[1];

			$path = Yii::getAlias('@web') . '/uploads/media/' . date('Y', strtotime($model->created_at)) . '/' . date('m', strtotime($model->created_at)) . '/' . $thumbFileName;

		} else {
			$path = Yii::getAlias('@web') . '/uploads/media/thumb/' . $model->thumb;
		}
		return $path;

	}

	public function statusList() {

		$statusArray = ['1' => 'Enable', '0' => 'Disable'];
		return $statusArray;
	}

	public function getStatus($val) {
		if ($val == 1) {
			return 'Enabled';
		} else {
			return 'Disabled';
		}
	}

}

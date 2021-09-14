<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%country}}".
 *
 * @property int $id
 * @property string $name
 * @property string $iso_code
 * @property int $status
 */
class Country extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%country}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'iso_code'], 'required'],
            [['status'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['iso_code'], 'string', 'max' => 2],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'iso_code' => 'Iso Code',
            'status' => 'Status',
        ];
    }
}

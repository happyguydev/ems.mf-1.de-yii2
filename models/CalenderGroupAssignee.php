<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%calender_group_assignee}}".
 *
 * @property int $id
 * @property int|null $group_id
 * @property int|null $user_id
 */
class CalenderGroupAssignee extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%calender_group_assignee}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['group_id', 'user_id'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'group_id' => Yii::t('app', 'Group ID'),
            'user_id' => Yii::t('app', 'User ID'),
        ];
    }
}

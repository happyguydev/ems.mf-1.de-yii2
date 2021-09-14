<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%appointment_assignee}}".
 *
 * @property int $id
 * @property int|null $appointment_id
 * @property int $user_id
 */
class AppointmentAssignee extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%appointment_assignee}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['appointment_id', 'user_id'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'appointment_id' => Yii::t('app', 'Appointment ID'),
            'user_id' => Yii::t('app', 'User ID'),
        ];
    }
}

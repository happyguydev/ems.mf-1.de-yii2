<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\mailbox\models\Mailbox */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="mailbox-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'email_client_id')->textInput() ?>

    <?= $form->field($model, 'subject')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'email_from')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'emai_to')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'cc')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'bcc')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'email_date')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'message_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'body')->textInput() ?>

    <?= $form->field($model, 'status')->textInput() ?>

    <?= $form->field($model, 'email_size')->textInput() ?>

    <?= $form->field($model, 'uid')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'msgno')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'recent')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'flagged')->textInput() ?>

    <?= $form->field($model, 'answered')->textInput() ?>

    <?= $form->field($model, 'deleted')->textInput() ?>

    <?= $form->field($model, 'seen')->textInput() ?>

    <?= $form->field($model, 'draft')->textInput() ?>

    <?= $form->field($model, 'udate')->textInput() ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

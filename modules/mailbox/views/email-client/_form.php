<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\mailbox\models\UserEmail */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-email-form">

    <?php $form = ActiveForm::begin();?>
      <div class="row">

        <div class="col-md-6">
   <?=$form->field($model, 'title')->textInput(['placeholder' => Yii::t('app', 'Title'), 'class' => 'input w-full border mt-2'])?>
</div>

<div class="col-md-6">
    <?=$form->field($model, 'imap_host_url')->textInput(['placeholder' => Yii::t('app', 'Imap host:port'), 'class' => 'input w-full border mt-2'])?>

</div>
</div>

  <div class="row">

        <div class="col-md-4">
   <?=$form->field($model, 'smtp_host')->textInput(['placeholder' => Yii::t('app', 'Smtp Host'), 'class' => 'input w-full border mt-2'])?>
</div>

<div class="col-md-4">
    <?=$form->field($model, 'smtp_port')->textInput(['placeholder' => Yii::t('app', 'Smtp port'), 'class' => 'input w-full border mt-2'])?>

</div>

<div class="col-md-4">

      <?=$form->field($model, 'smtp_encryption')->dropDownList(['tls' => 'tls', 'ssl' => 'ssl'], ['class' => "input w-full border mt-2"]);?>

</div>

</div>

     <div class="submitButton">
        <?=Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success'])?>
    </div>

    <?php ActiveForm::end();?>

</div>

<style type="text/css">
    .field-useremail-email_client_id .tail-select {
        margin-top:7px;
    }
</style>
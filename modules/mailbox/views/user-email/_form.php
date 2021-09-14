<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\mailbox\models\UserEmail */
/* @var $form yii\widgets\ActiveForm */
$email_client_array = Yii::$app->Utility->dropDownArray('tbl_email_client', 'id', 'title');
?>

<div class="user-email-form">

    <?php $form = ActiveForm::begin();?>
      <div class="row">

        <div class="col-md-4">
    <?=$form->field($model, 'email_client_id')->dropDownList($email_client_array, ['data-search' => 'true', 'class' => 'tail-select w-full mt-5', 'prompt' => Yii::t('app', 'Select Email Client')])?>
</div>

        <div class="col-md-4">

    <?=$form->field($model, 'email')->textInput(['placeholder' => Yii::t('app', 'Email'), 'class' => 'input w-full border mt-2'])?>
</div>
<div class="col-md-4">
    <?=$form->field($model, 'password')->passwordInput(['placeholder' => Yii::t('app', 'Password'), 'class' => 'input w-full border mt-2'])?>
    <span toggle="#password-field" class="fa fa-fw fa-eye field_icon toggle-password"></span>


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
    .toggle-password {
   position: absolute;
    right: 25px;
    bottom: 27px;
    }
</style>
<script type="text/javascript">
    $(document).on('click', '.toggle-password', function() {

    $(this).toggleClass("fa-eye fa-eye-slash");

    var input = $("#useremail-password");
    input.attr('type') === 'password' ? input.attr('type','text') : input.attr('type','password')
});
</script>
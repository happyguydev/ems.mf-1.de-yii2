<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\Notification */
/* @var $form yii\widgets\ActiveForm */
$user_array = Yii::$app->Utility->dropDownArray('tbl_user', 'id', 'phone');

$user_array['all'] = 'All';
?>

<div class="notification-form">

    <?php $form = ActiveForm::begin();?>

    <div class="row">
        <div class="col-md-4">

    <?=$form->field($model, 'notification_name')->textInput(['maxlength' => true])?>
</div>
    <div class="col-md-4">

    <?=$form->field($model, 'send_to')->dropDownList($user_array, ['data-placeholder' => 'Select App User', 'class' => 'select-multiple', 'multiple' => true])?>
</div>


    <div class="col-md-4">
            <?=$form->field($model, 'status')->dropDownList(['1' => 'Enable', '0' => 'Disable'], ['class' => 'select-full', 'prompt' => 'Select Status'])?>

    </div>
</div>

<div class="row">

    <div class="col-md-12">


    <?=$form->field($model, 'content')->textarea(['rows' => 6])?>
</div>
</div>

<div class="row">




    <div class="form-group text-center">
        <?=Html::submitButton('Save', ['class' => 'btn btn-success'])?>
    </div>

</div>

    <?php ActiveForm::end();?>

</div>

<script type="text/javascript">
    getAppUsers(<?=$model['id']?>);
    function getAppUsers(id='') {
      $.ajax({
        url: `<?=Yii::getAlias('@web')?>/site/get-app-users?id=${id}`,
        success: function(data) {
          $('#notification-send_to').html(data);
        }
      })
    }
</script>

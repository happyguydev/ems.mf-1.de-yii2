<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\CustomerContract */
/* @var $form yii\widgets\ActiveForm */
?>
    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]);?>
      <div class="p-5 grid grid-cols-12 gap-4 gap-y-3">
<div class="col-span-12 sm:col-span-6">

    <?=$form->field($model, 'title')->textInput(['maxlength' => true, 'class' => "input w-full border mt-2 flex-1", 'placeholder' => Yii::t('app', 'Title')])?>
</div>
<div class="col-span-12 sm:col-span-6">
    <?=$form->field($model, 'contract_amount')->textInput(['maxlength' => true, 'class' => "input w-full border mt-2 flex-1", 'placeholder' => Yii::t('app', 'Contract Amount')])?>
</div>
<div class="col-span-12 sm:col-span-6">
    <?=$form->field($model, 'start_date')->input('date', ['class' => "input w-full border mt-2 flex-1 start_date"])?>
</div>
<div class="col-span-12 sm:col-span-6">
    <?=$form->field($model, 'end_date')->input('date', ['class' => "input w-full border mt-2 flex-1 end_date", 'min' => $model->start_date])?>
</div>

<div class="col-span-12 sm:col-span-6">
    <?=$form->field($model, 'issue_date')->input('date', ['class' => "input w-full border mt-2 flex-1"])?>
</div>
<div class="col-span-12 sm:col-span-6">
    <?=$form->field($model, 'paid_date')->input('date', ['class' => "input w-full border mt-2 flex-1"])?>
</div>

<div class="col-span-12 sm:col-span-6">
    <?=$form->field($model, 'bill_number')->textInput(['class' => "input w-full border mt-2 flex-1", 'placeholder' => Yii::t('app', 'Bill Number')])?>
</div>
<div class="col-span-12 sm:col-span-6">
    <?=$form->field($model, 'status')->dropDownList(['1' => 'Enable', '0' => 'Disable'], ['class' => "input w-full border mt-2 flex-1"])?>

</div>
<div class="col-span-12 sm:col-span-12">
    <?=$form->field($model, 'description')->textarea(['rows' => 2, 'class' => "input w-full border mt-2 flex-1"])?>
</div>

 <div class="col-span-12 sm:col-span-12">

 <label><?=Yii::t('app', 'Uploaded File')?></label>


<input type="file" name="file[]" class="input w-full border mr-2" multiple="true">


</div>

</div>

  <div class="px-5 py-3 text-right border-t border-gray-200 dark:border-dark-5">
   <button type="button" data-dismiss="modal" class="closemodal button w-20 border text-gray-700 dark:border-dark-5 dark:text-gray-300 mr-1"><?=Yii::t('app', 'Cancel')?></button>
        <?=Html::submitButton(Yii::t('app', 'Save'), ['class' => 'button w-20 bg-theme-1 text-white'])?>
    </div>

    <?php ActiveForm::end();?>

<script type="text/javascript">
  $(document).ready(function(){

    $('.start_date').change(function(){
      $('.end_date').attr('min',$(this).val());
    })
});
</script>
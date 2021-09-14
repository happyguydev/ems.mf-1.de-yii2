<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Appointment */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="appointment-form">



    <?php $form = ActiveForm::begin();?>
             <div class="p-5 grid grid-cols-12 gap-4 gap-y-3">
<div class="col-span-12 sm:col-span-4">


    <?=$form->field($model, 'title')->textInput(['maxlength' => true, 'class' => "form-control", 'placeholder' => Yii::t('app', 'Title')])?>
</div>
<div class="col-span-12 sm:col-span-4">


    <?=$form->field($model, 'bg_color')->input('color', ['maxlength' => true, 'class' => "form-control", 'placeholder' => Yii::t('app', 'Background Color')])?>
</div>
<div class="col-span-12 sm:col-span-4">


    <?=$form->field($model, 'text_color')->input('color', ['maxlength' => true, 'class' => "form-control", 'placeholder' => Yii::t('app', 'Text Color')])?>
</div>

<div class="col-span-12 sm:col-span-6">

    <?=$form->field($model, 'start_date')->input('date', ['class' => "form-control start_date", 'placeholder' => Yii::t('app', 'Start Date')])?>
</div>
<div class="col-span-12 sm:col-span-6">


    <?=$form->field($model, 'end_date')->input('date', ['class' => "form-control end_date", 'placeholder' => Yii::t('app', 'End Date')])?>
</div>
</div>

 <div class="px-5 py-3 text-right border-t border-gray-200 dark:border-dark-5">
   <button type="button" data-dismiss="modal" class="closemodal button w-20 border text-gray-700 dark:border-dark-5 dark:text-gray-300 mr-1"><?=Yii::t('app', 'Cancel')?></button>
        <?=Html::submitButton(Yii::t('app', 'Save'), ['class' => 'button w-20 bg-theme-1 text-white'])?>
    </div>


    <?php ActiveForm::end();?>

</div>

<script type="text/javascript">
  $(document).ready(function(){

    $('.start_date').change(function(){
      $('.end_date').attr('min',$(this).val());
    })
});
</script>

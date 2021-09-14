<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\CalendarGroup */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="calendar-group-form">

    <?php $form = ActiveForm::begin();?>
      <div class="p-5 grid grid-cols-12 gap-4 gap-y-3">
<div class="col-span-12 sm:col-span-6">

    <?=$form->field($model, 'title')->textInput(['class' => "input w-full border mt-2 flex-1", 'placeholder' => Yii::t('app', 'Title')])?>
</div>
<div class="col-span-12 sm:col-span-6">


    <?=$form->field($model, 'status')->dropDownList(['1' => 'Enable', '0' => 'Disable'], ['class' => "input w-full border mt-2 flex-1"])?>
</div>
</div>

  <div class="px-5 py-3 text-right border-t border-gray-200 dark:border-dark-5">
    <button type="button" data-dismiss="modal" class="closemodal button w-20 border text-gray-700 dark:border-dark-5 dark:text-gray-300 mr-1"><?=Yii::t('app', 'Cancel')?></button>

        <?=Html::submitButton('Save', ['class' => 'button w-20 bg-theme-1 text-white'])?>
</div>
    <?php ActiveForm::end();?>

</div>

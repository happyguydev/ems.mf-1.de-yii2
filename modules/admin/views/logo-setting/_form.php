<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\themes\main\modules\admin\models\Setting */
/* @var $form yii\widgets\ActiveForm */
?>

 <?php $form = ActiveForm::begin();?>


    <?=$form->field($model, 'setting_name')->textInput(['maxlength' => 255])?>

    <?=$form->field($model, 'setting_value')->textInput(['maxlength' => 255])?>


    <div class="form-group">
        <?=Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary'])?>
    </div>


 <?php ActiveForm::end();?>



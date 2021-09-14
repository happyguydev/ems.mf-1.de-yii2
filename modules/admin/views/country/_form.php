<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\Country */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="country-form">

    <?php $form = ActiveForm::begin();?>
    <div class="row">
    	<div class="col-md-6">

    <?=$form->field($model, 'name')->textInput(['maxlength' => true])?>
</div>
<div class="col-md-6">

    <?=$form->field($model, 'code')->textInput(['maxlength' => true])?>
</div>
</div>
	<div class="row">
		<div class="col-md-6">

    <?=$form->field($model, 'phone_prefix')->textInput(['maxlength' => true])?>
</div>
<div class="col-md-6">

    <?=$form->field($model, 'status')->dropDownList(['1' => 'Enable', '0' => 'Disable'], ['class' => 'select-full', 'prompt' => 'Select Status'])?>
</div>
</div>
<div class="row">

    <div class="form-group text-center">
        <?=Html::submitButton('Save', ['class' => 'btn btn-success'])?>
    </div>
</div>

    <?php ActiveForm::end();?>

</div>

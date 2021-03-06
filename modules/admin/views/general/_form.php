<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\General */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="general-form">
    <?php $form = ActiveForm::begin();?>
    <?=$form->field($model, 'type_name')->textInput(['maxlength' => true])?>
    <?=$form->field($model, 'type_label')->textInput(['maxlength' => true])?>
    <?=$form->field($model, 'setting_name')->textInput(['maxlength' => true])?>
    <?=$form->field($model, 'setting_label')->textInput(['maxlength' => true])?>
    <?=$form->field($model, 'setting_value')->textarea(['rows' => 6])?>
    <div class="form-group">
        <?=Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary'])?>
    </div>
    <?php ActiveForm::end();?>
</div>
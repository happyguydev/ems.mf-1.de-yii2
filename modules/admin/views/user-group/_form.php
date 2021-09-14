<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
/* @var $this yii\web\View */
/* @var $model app\models\AuthItem */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="auth-item-form">
	<?php $form = ActiveForm::begin();?>
	<div>
			<?=$form->field($model, 'name')->textInput(['maxlength' => 64, 'class' => 'input w-full border mt-2'])->label(Yii::t('app', 'Name'))?>
		</div>
	<div class="row">
		<div class="col-md-12">
			<label class="text-weight-bold mt-3"><?=Yii::t('app', 'Accesses')?></label>
	<div class="intro-y box col-span-12 lg:col-span-6">
		<?=$this->render('_permission_access', ['Permissions' => $model->child])?>
</div>
</div>
</div>

<div class="intro-x mt-5 xl:mt-8 text-center xl:text-center">
	<?=Html::submitButton(Yii::t('app', 'Save'), ['class' => 'button button--lg w-full xl:w-32 text-white bg-theme-1 align-top'])?>
</div>
<?php ActiveForm::end();?>
</div>

<style type="text/css">
	.text-weight-bold {
		font-weight: bold;
		font-size:20px;
	}
</style>
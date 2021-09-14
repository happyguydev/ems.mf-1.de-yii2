<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\themes\main\modules\admin\models\Setting */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="setting-form">

  <div class="row">
<div class="col-md-9">
				<div class="row product-info">
				<!-- Left Starts -->
					<div class="col-sm-4 images-block">
                    <?php $form = ActiveForm::begin();?>
						<p>
							<img src="<?=Yii::getAlias('@web');?>/logo/<?=$model->setting_value?>" alt="Image" class="img-responsive thumbnail" />
						</p>
         <?=$form->field($model, 'file')->fileInput()->label(Yii::t('app', 'Upload Logo'))?>

         <div class="col-md-4">
      <?=Html::submitButton(Yii::t('app', 'Upload'), ['class' => 'btn btn-primary'])?>
    </div>
                       <?php ActiveForm::end();?>
					</div>
</div>
<script language="javascript">

// function to get discount amount
function getProfile(id)
{
	$('#w0').attr('action','<?=Yii::getAlias('@web');?>/user/profile?id='+id);
	$('#w0').submit();
}

</script>

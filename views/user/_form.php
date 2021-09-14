<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
/* @var $this yii\web\View */
/* @var $model app\models\User */
/* @var $form yii\widgets\ActiveForm */
?>


        <?php $form = ActiveForm::begin(['id' => 'form-signup']);?>
       <div class="row">
        <div class="col-md-4">
            <?=$form->field($model, 'first_name')->textInput(['placeholder' => Yii::t('app', 'First Name'), 'class' => 'input w-full border mt-2'])->label(Yii::t('app', 'First Name'))?>
        </div>

        <div class="col-md-4">
            <?=$form->field($model, 'last_name')->textInput(['placeholder' => Yii::t('app', 'Last Name'), 'class' => 'input w-full border mt-2'])->label(Yii::t('app', 'Last Name'))?>
        </div>


        <div class="col-md-4">
            <?=$form->field($model, 'email')->input('email', ['placeholder' => Yii::t('app', 'Email'), 'class' => 'input w-full border mt-2', 'readOnly' => true])->label(Yii::t('app', 'Email'))?>
        </div>
    </div>

    <div class="row">

        <div class="col-md-6">
            <?=$form->field($model, 'phone')->textInput(['placeholder' => Yii::t('app', 'Phone'), 'class' => 'input w-full border mt-2'])->label(Yii::t('app', 'Phone'))?>

        </div>
         <div class="col-md-6">


            <?=$form->field($model, 'file')->fileInput(['onchange' => 'readURL(this)', 'accept' => '.jpg,.jpeg,.gif,.png', 'class' => " input w-full border mr-2"])->label(Yii::t('app', 'Upload Profile'))?>

    <div class="col-md-6">
<?php
if ($model->profile_picture != '') {
	?>
             <img src="<?=Yii::getAlias('@web');?>/web/profile/<?=$model->id?>/<?=$model->profile_picture?>" class="profile img-thumbnail" onerror="this.onerror=null;this.src='<?=Yii::getAlias('@web');?>/web/profile/default.jpg';" width="80" height="80">
                <?php
} else {
	?>
 <img src="<?=Yii::getAlias('@web');?>/web/profile/default.jpg" class="profile img-thumbnail" width="80" height="80">
    <?php
}
?>
         </div>


</div>

                </div>
<div class="intro-x mt-3 xl:mt-3 text-center">

                <?=Html::submitButton(Yii::t('app', 'Save'), ['class' => 'button button--lg w-full xl:w-32 text-white bg-theme-1 align-top'])?>
            </div>

        <?php ActiveForm::end();?>

<script type="text/javascript">
function readURL(input)
{
if (input.files && input.files[0])
{
var reader = new FileReader();
reader.onload = function (e) {
$('.profile').attr('src', e.target.result);
}
reader.readAsDataURL(input.files[0]);
}
}
</script>
<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\User */
/* @var $form yii\widgets\ActiveForm */
?>

 <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>

           <?php $form = ActiveForm::begin(['id' => 'form-signup', 'options' => ['enctype' => 'multipart/form-data']]);?>

<div class="row">

<div class="col-md-6">
                        <?=$form->field($model, 'first_name')->textInput(['placeholder' => Yii::t('app', 'First Name'), 'class' => 'input w-full border mt-2'])?>
                    </div>
                     <div class="col-md-6">
                        <?=$form->field($model, 'last_name')->textInput(['placeholder' => Yii::t('app', 'Last Name'), 'class' => 'input w-full border mt-2'])?>
                    </div>
                </div>

                <div class="row">

                     <div class="col-md-6">
                        <?=$form->field($model, 'email')->input('email', ['placeholder' => Yii::t('app', 'Email'), 'class' => 'input w-full border mt-2'])?>
                     </div>

                      <div class="col-md-6">
                        <?=$form->field($model, 'phone')->textInput(['placeholder' => Yii::t('app', 'Phone'), 'max-length' => true, 'class' => 'input w-full border mt-2'])?>
                     </div>
                 </div>

                  <div class="row">

                     <div class="col-md-6">
                        <?=$form->field($userDetail, 'working_hours')->textInput(['placeholder' => Yii::t('app', 'Working Hours'), 'class' => 'input w-full border mt-2'])?>
                     </div>

                      <div class="col-md-6">
                        <?=$form->field($userDetail, 'allowed_leave_hours')->textInput(['placeholder' => Yii::t('app', 'Allowed Leave Hours'), 'max-length' => true, 'class' => 'input w-full border mt-2'])?>
                     </div>


                 </div>
                <!--   <div class="row">

                     <div class="col-md-12">
                        <?=$form->field($userDetail, 'email_signature')->textarea(['placeholder' => Yii::t('app', 'Email Signature'), 'rows' => 6, 'class' => 'input w-full border mt-3'])?>
                     </div>
                 </div> -->
                 <div class="row">

   <div class="col-md-6">
                     <?=$form->field($model, 'password')->passwordInput(['placeholder' => Yii::t('app', 'Password'), 'class' => 'input w-full border mt-2 user-password'])?>
                      <span toggle="#password-field" class="fa fa-fw fa-eye field_icon toggle-password"></span>
                    </div>

                     <div class="col-md-6 ">
                        <?=$form->field($model, 'repeatpassword')->passwordInput(['placeholder' => Yii::t('app', 'Repeat Password'), 'class' => 'input w-full border mt-2 user-repeat-password'])?>

                         <span toggle="#repeat-password-field" class="fa fa-fw fa-eye field_icon toggle-repeat-password"></span>
                    </div>
                </div>
                <div class="row">
                    <?php

$auth_item_array = ArrayHelper::map($authItem, 'name', 'name');

?>

                      <div class="col-md-4">

                    <?=$form->field($model, 'user_role')->dropDownList($auth_item_array, ['class' => 'input w-full border mt-2', 'prompt' => Yii::t('app', 'Select User Role')]);?>
                    </div>

        <div class="col-md-4 <?=$cls?>">

                    <?=$form->field($model, 'status')->dropDownList(['1' => Yii::t('app', 'Enable'), '0' => Yii::t('app', 'Disable')], ['class' => "input w-full border mt-2"]);?>
                    </div>







                <div class="col-md-4">

            <?=$form->field($model, 'file')->fileInput(['onchange' => 'readURL(this)', 'accept' => '.jpg,.jpeg,.gif,.png', 'class' => "profile input w-full border mt-2"])?>

    <div class="col-md-4">
<?php
if ($model->profile_picture != '') {
	?>
             <img src="<?=Yii::getAlias('@web');?>/web/profile/<?=$model->id?>/<?=$model->profile_picture?>?v=<?=date('YmdHis')?>" class="profile img-thumbnail" onerror="this.onerror=null;this.src='<?=Yii::getAlias('@web');?>/web/profile/default.jpg';" width="80" height="80">
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

<div class="intro-x mt-5 xl:mt-8 text-center xl:text-center">

                    	<?=Html::submitButton(Yii::t('app', 'Save'), ['class' => 'button button--lg w-full xl:w-32 text-white bg-theme-1 align-top'])?>

                </div>


  <?php ActiveForm::end();?>

</div>
</div>

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
  $('.summernote').summernote({
    height: '200'
  });
</script>
<style type="text/css">
    .checkbox-input {
    top: 3px;
    }
    .field-user-user_role .tail-select {
        margin-top:9px;
    }
</style>


<style type="text/css">
    .toggle-password,.toggle-repeat-password {
   position: absolute;
    right: 25px;
    top: 40px;
    }
</style>
<script type="text/javascript">
    $(document).on('click', '.toggle-password', function() {

    $(this).toggleClass("fa-eye fa-eye-slash");

    var input = $(".user-password");
    input.attr('type') === 'password' ? input.attr('type','text') : input.attr('type','password')
});

$(document).on('click', '.toggle-repeat-password', function() {

    $(this).toggleClass("fa-eye fa-eye-slash");

    var input = $(".user-repeat-password");
    input.attr('type') === 'password' ? input.attr('type','text') : input.attr('type','password')
});


</script>
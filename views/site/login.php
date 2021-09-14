<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\bootstrap\ActiveForm;

$this->title = 'Login';

?>
   <div class="h-screen xl:h-auto flex py-5 xl:py-0 my-10 xl:my-0">
                    <div class="my-auto mx-auto xl:ml-20 bg-white xl:bg-transparent px-5 sm:px-8 py-8 xl:p-0 rounded-md shadow-md xl:shadow-none w-full sm:w-3/4 lg:w-2/4 xl:w-auto login1">
                        <h2 class="intro-x font-bold text-2xl xl:text-3xl text-center xl:text-left">
                            Sign In
                        </h2>
                        <div class="intro-x mt-2 text-gray-500 xl:hidden text-center"><?=Yii::t('app', 'A few more clicks to sign in to your account')?>.</div>
                           <?php $form = ActiveForm::begin([
	'id' => 'login-form',

	'fieldConfig' => [
		'template' => " <div class=\"form-group form-material floating\">{input}</div>
            <div class=\"col-md-12 help-margin-15\">{error}</div>",
		'labelOptions' => ['class' => ''],
	],
]);?>
                        <div class="intro-x mt-8">
                            <?=$form->field($model, 'username')->textInput(['class' => 'intro-x login__input input input--lg border border-gray-300 block', 'placeholder' => Yii::t('app', 'Email')])?>
                            <?=$form->field($model, 'password')->passwordInput(['class' => 'intro-x login__input input input--lg border border-gray-300 block mt-4', 'placeholder' => Yii::t('app', 'Password')])?>
                        </div>
                        <div class="intro-x flex text-gray-700 dark:text-gray-600 text-xs sm:text-sm mt-4">
                            <div class="flex items-center mr-auto">
                                <input type="checkbox" class="form-check-input border mr-2" id="remember-me" name="LoginForm['rememberMe']">
                                <label class="cursor-pointer select-none" for="remember-me"><?=Yii::t('app', 'Remember me')?></label>
                            </div>
                            <a class="forget1"  href="javascript:void(0)" onclick="getForgetForm()"><?=Yii::t('app', 'Forgot Password?')?></a>
                        </div>
                        <input type="hidden" name="latitude" id="latitude" value=""/>
                        <input type="hidden" name="longitude" id="longitude" value=""/>
                        <div class="intro-x mt-5 xl:mt-8 text-center xl:text-left">
                            <button type="submit" class="button button--lg w-full xl:w-32 text-white bg-theme-1 xl:mr-3 align-top"><?=Yii::t('app', 'Login')?></button>
                            <!-- <button class="button button--lg w-full xl:w-32 text-gray-700 border border-gray-300 dark:border-dark-5 dark:text-gray-300 mt-3 xl:mt-0 align-top">Sign up</button> -->
                        </div>

                        <?php ActiveForm::end();?>

                    </div>
                    <!--Forgot Password-->

                    <div class="my-auto mx-auto xl:ml-20 bg-white xl:bg-transparent px-5 sm:px-8 py-8 xl:p-0 rounded-md shadow-md xl:shadow-none w-full sm:w-3/4 lg:w-2/4 xl:w-auto forgetp hidden">
                      <div class="rounded-md px-5 py-4 mb-2 bg-theme-6 text-white hidden error"></div>
                       <div class="rounded-md px-5 py-4 mb-2 bg-theme-9 text-white hidden success"></div>
                        <h2 class="intro-x font-bold text-2xl xl:text-3xl text-center xl:text-left">
                            <?=Yii::t('app', 'Forgot Password')?>
                        </h2>
                        <div class="intro-x mt-2 text-gray-500 xl:hidden text-center"><?=Yii::t('app', 'A few more clicks to sign in to your account. Manage all your e-commerce accounts in one place')?></div>
                          <form method="post">

                        <div class="intro-x mt-8">
                            <input type="email" id="email_id" class="intro-x login__input input input--lg border border-gray-300 block" onkeypress="hiddenErr()" placeholder="<?=Yii::t('app', 'Email')?>">
                            <!-- <p class="help-block help-block-error error"></p> -->

                        </div>
                        <div class="intro-x mt-5 xl:mt-8 text-center xl:text-left">
                            <button type="button" class="button button--lg w-full xl:w-32 text-white bg-theme-1 xl:mr-3 align-top forgot-btn" onclick="ForgetPassword()"><?=Yii::t('app', 'Submit')?></button>
                            <button class="button button--lg w-full xl:w-32 text-gray-700 border border-gray-300 dark:border-dark-5 dark:text-gray-300 mt-3 xl:mt-0 align-top forget1" onclick="getLoginForm()"><?=Yii::t('app', 'Sign in')?></button>
                        </div>

                        </form>
                       <!--  <div class="intro-x mt-10 xl:mt-24 text-gray-700 dark:text-gray-600 text-center xl:text-left">
                            <?=Yii::t('app', 'By signin up, you agree to our')?>
                            <br>
                            <a class="text-theme-1 dark:text-theme-10" href=""><?=Yii::t('app', 'Terms and Conditions')?></a> & <a class="text-theme-1 dark:text-theme-10" href=""><?=Yii::t('app', 'Privacy Policy')?></a>
                        </div> -->
                    </div>
                </div>


<script type="text/javascript">

function getForgetForm() {
  $(".login1").addClass("hidden");
  $(".login1").removeClass("visible");

  $(".forgetp").addClass("visible");
  $(".forgetp").removeClass("hidden");

}

function getLoginForm() {
  $(".login1").addClass("visible");
  $(".login1").removeClass("hidden");

  $(".forgetp").addClass("hidden");
  $(".forgetp").removeClass("visible");

}
function ForgetPassword() {
  var email = $("#email_id").val();


  if (email.trim() == "") {
    $(".error").removeClass("hidden");
    $(".error").html("Please Input your email first");

    return false;
  } else {

     $(".forgot-btn").attr("disabled",true);
     $(".forgot-btn").css("cursor","not-allowed");
     $(".forgot-btn").html("Please Wait...");

    $.ajax({
      type: "POST",
      url: "<?=Yii::getAlias('@web');?>/site/forget?email=" + email,

      success: function(data) {
        if (data == 0) {

          $(".error").addClass("visible");
          $(".error").removeClass("hidden");
          $(".forgot-btn").attr("disabled",false);
     $(".forgot-btn").css("cursor","pointer");
     $(".forgot-btn").html("Submit");

          $(".error").html("Your email is not exit our database.<br/> please check your email or contact to administrator");

        } else {
          $(".success").addClass("visible");
          $(".success").removeClass("hidden");
          $(".error").addClass("hidden");
          $(".error").removeClass("visible");
          $(".forgot-btn").attr("disabled",true);
          $(".forgot-btn").css("cursor","not-allowed");
          $(".forgot-btn").addClass("bg-theme-9");
          $(".forgot-btn").html('Submitted');
          $(".success").html("Please check your Email.<br/> We have sent you the instruction to reset your password.");
         setTimeout(function(){
         window.location.href = "<?=Yii::getAlias('@web')?>/site/login";
         }, 3000);
        }
      }

    });
  }
}
function hiddenErr () {
  $(".error").removeClass("visible");
  $(".error").addClass("hidden");
}

  function getLocation() {
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(showPosition);
  } else {
    alert("Geolocation is not supported by this browser.");
  }
}

function showPosition(position) {
  $('#latitude').val(position.coords.latitude);
  $('#longitude').val(position.coords.longitude);
}
getLocation();
</script>


</script>
<style type="text/css">
  .help-block-error {
    color: red;
  }

</style>
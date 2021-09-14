<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */
//$fullname = Yii::$app->user->identity->fullname;
$this->title = Yii::t('app', 'Reset Your Password ');
$this->params['breadcrumbs'][] = $this->title;
?>

    <div class="h-screen xl:h-auto flex py-5 xl:py-0 my-10 xl:my-0">
                    <div class="my-auto mx-auto xl:ml-20 bg-white xl:bg-transparent px-5 sm:px-8 py-8 xl:p-0 rounded-md shadow-md xl:shadow-none w-full sm:w-3/4 lg:w-2/4 xl:w-auto">
                       <div class="rounded-md px-5 py-4 mb-2 bg-theme-6 text-white hidden error"></div>
                       <div class="rounded-md px-5 py-4 mb-2 bg-theme-9 text-white hidden success"></div>
                        <h2 class="intro-x font-bold text-2xl xl:text-3xl text-center xl:text-left">
                            <?=Yii::t('app', 'Reset Your Password')?>
                        </h2>
                        <div class="intro-x mt-2 text-gray-500 xl:hidden text-center">A few more clicks to sign in to your account. Manage all your e-commerce accounts in one place</div>
          <?php if (Yii::$app->session->hasFlash('passwordChanged')): ?>
           <div class="rounded-md px-5 py-4 mb-2 bg-theme-9 text-white"><?=Yii::t('app', 'Success! Your password has been changed.')?></div>
          <?php endif;?>

          <form method="post">
          <div class="intro-x mt-8">

              <input class="intro-x login__input input input--lg border border-gray-300 block" type="password" id="password1"  onkeypress="hideErr()" value="" placeholder="<?=Yii::t('app', 'Your New Password')?>"/>
               <input class="intro-x login__input input input--lg border border-gray-300 block mt-4" type="password" id="repeatpassword1"  onkeypress="hideErr()" value="" placeholder="<?=Yii::t('app', 'Repeat New Password')?>"/>

            </div>
            <div class="intro-x mt-5 xl:mt-8 text-center xl:text-left">
            <button id="submitted" type="button" class="button button--lg w-full xl:w-32 text-white bg-theme-1 xl:mr-3 align-top" onclick="ResetPassword()"><?=Yii::t('app', 'Submit')?></button>
            <button class="button button--lg w-full xl:w-32 text-gray-700 border border-gray-300 dark:border-dark-5 dark:text-gray-300 mt-3 xl:mt-0 align-top forget1" onclick="getLoginForm()"><?=Yii::t('app', 'Sign in')?></button>
                    </div>

          </form>

</div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script type="text/javascript">
function ResetPassword() {
var password = $("#password1").val();
var confirm = $("#repeatpassword1").val();
if(password.trim() == "" || confirm.trim() == "")
{
$(".error").removeClass("hidden");
$(".error").html("All fields must be filled");
}
else
{
//if(checkPass(password))
//{
if(password == confirm)
{
$.ajax({
type: "POST",
url: '<?=Yii::getAlias('@web');?>/site/reset?key=<?=$_REQUEST["key"]?>&password='+password,
success: function(data) {
$(".success").addClass("visible");
$(".success").removeClass("hidden");
$(".error").addClass("hidden");
$(".error").removeClass("visible");
$("#submitted").attr("disabled",true);
$("#submitted").css("cursor","none");
$(".success").html("Success! Your password has been reset.");
setTimeout(function() {
  window.location.href = "<?=Yii::getAlias('@web')?>/site/login";
}, 3000);
}
});
}
else
{
$(".error").addClass("visible");
$(".error").removeClass("hidden");
$(".error").html("Password must repeat exactly same");
}
}
/*else{
$(".error").css("display","block");
$(".error").html("Password must be atleat 8 charecters, with atleast one lowercase, one uppercase, one digit");
}*/
//}
}
function hideErr () {
$(".error").removeClass("visible");
  $(".error").addClass("hidden");
}
function checkPass(password) {
var pattern = (/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])[0-9a-zA-Z]{8,}$/);
return pattern.test(password);
};

function getLoginForm () {
     window.location.href="<?=Yii::getAlias('@web')?>/site/login";
    }
</script>
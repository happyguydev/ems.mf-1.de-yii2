<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

$fullname = $model->first_name . ' ' . $model->last_name;

$this->title = Yii::t('app', 'Change Password') . ' : ' . $fullname;
$this->params['breadcrumbs'][] = $this->title;

$roles1 = Yii::$app->authManager->getRolesByUser(Yii::$app->user->getId());

$role1 = current($roles1);
?>


<div class="grid grid-cols-12 gap-12 mt-5">
                    <div class="intro-y col-span-12 lg:col-span-12">
		<?php if (Yii::$app->session->hasFlash('passwordChanged')): ?>
					 <div class="rounded-md px-5 py-4 mb-2 bg-theme-9 text-white mb-2">
					  Success ! Your Password has been changed.
		            </div>
		        <?php endif;?>

	   <div class="intro-y box">

                            <div class="flex flex-col sm:flex-row items-center p-5 border-b border-gray-200 dark:border-dark-5">
                                <h2 class="font-medium text-base mr-auto">
                                    <?=Html::encode($this->title)?>
                                </h2>
                            </div>
                            <div class="p-5" id="input">
                                <div class="preview">

						<!-- Login Form Starts -->

    <?php $form = ActiveForm::begin();?>

	<div class="row">
        <div class="col-md-4">
       	<?=$form->field($model, 'oldpassword')->passwordInput(['placeholder' => Yii::t('app', 'Your Old Password'), 'class' => 'input w-full border mt-2'])->label(Yii::t('app', 'Old Password'))?>
       </div>
       <div class="col-md-4">

        <?=$form->field($model, 'password')->passwordInput(['placeholder' => Yii::t('app', 'Your New Password'), 'class' => 'input w-full border mt-2'])->label(Yii::t('app', 'New Password'))?>
    </div>
    <div class="col-md-4">

        <?=$form->field($model, 'repeatpassword')->passwordInput(['placeholder' => Yii::t('app', 'Repeat New Password'), 'class' => 'input w-full border mt-2'])->label(Yii::t('app', 'Repeat Password'))?>
    </div>
</div>
    <div class="intro-x text-center">

            <?=Html::submitButton(Yii::t('app', 'Change'), ['class' => 'button button--lg w-full xl:w-32 text-white bg-theme-1 align-top'])?>

   </div>

    <?php ActiveForm::end();?>


		</div>


</div>

</div>
</div>
</div>
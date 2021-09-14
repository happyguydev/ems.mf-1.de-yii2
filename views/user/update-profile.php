<?php
use yii\helpers\Html;
/* @var $this yii\web\View */
/* @var $model app\models\User */
$this->title = Yii::t('app', 'Update') . ' : ' . $model->first_name . ' ' . $model->last_name;
$this->params['breadcrumbs'][] = $this->title;
?>

 <div class="grid grid-cols-12 gap-12 mt-5">
                    <div class="intro-y col-span-12 lg:col-span-12">
                        <!-- BEGIN: Input -->
                           <?php if (Yii::$app->session->hasFlash('profileUpdated')): ?>
        <div class="rounded-md px-5 py-4 mb-2 bg-theme-9 text-white mb-2">
           <?=Yii::t('app', 'Your Profile has been updated successfully')?>.
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

		<?=$this->render('_form', [
	'model' => $model,
	'userDetail' => $userDetail,
])?>
	</div>
</div>
</div>
</div>
</div>
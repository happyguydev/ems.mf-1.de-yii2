<?php
use yii\helpers\Html;
/* @var $this yii\web\View */
/* @var $model app\models\User */
$this->title = $model->first_name . ' ' . $model->last_name;
if (Yii::$app->user->identity->user_role == 'admin') {
	$update_link = Yii::$app->urlManager->createAbsoluteUrl(['user/update-profile']);
	$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Users'), 'url' => ['admin/user/index']];
} else {
	$update_link = Yii::$app->urlManager->createAbsoluteUrl(['user/update-profile']);
}
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="grid grid-cols-12 gap-12 mt-5">
                    <div class="intro-y col-span-12 lg:col-span-12">
                        <!-- BEGIN: Basic Table -->
                        <div class="intro-y box">
                            <div class="flex flex-col sm:flex-row items-center p-3 border-b border-gray-200">
                                <h2 class="font-medium text-base mr-auto">
                                    <?=Html::encode($this->title)?>
                                </h2>

                            </div>
<div class="flex flex-col lg:flex-row border-b border-gray-200 dark:border-dark-5 pb-5 -mx-5">
                        <div class="flex flex-2 px-5 items-center justify-center lg:justify-start mt-5">
                            <div class="w-20 h-20 sm:w-24 sm:h-24 flex-none lg:w-32 lg:h-32 image-fit ml-5 relative">
                                 <img data-action="zoom" alt="<?=$model->first_name?> <?=$model->last_name?>" class="rounded-full border border-gray-200 ml-5" src="<?=$image?>" onerror="this.onerror=null;this.src='<?=Yii::getAlias('@web')?>/web/profile/default.jpg'" style="border:2px solid #8080802e;">

                            </div>
                          <!--   <div class="ml-5">
                                <div class="w-24 sm:w-40 truncate sm:whitespace-normal font-medium text-lg">Johnny Depp</div>
                                <div class="text-gray-600">Software Engineer</div>
                            </div> -->
                        </div>

                        <div class="mt-6 lg:mt-0 flex-1 px-5 border-t lg:border-0 border-gray-200 dark:border-dark-5 pt-5 lg:pt-0">
                        <div class="p-5" id="basic-table">
                                <div class="preview">
                                    <div class="overflow-x-auto">
     <table class="table">
            <tr>
            <th><?=Yii::t('app', 'Name')?></th><td><?=$model['first_name']?> <?=$model['last_name']?></td>

            <th><?=Yii::t('app', 'Email')?></th><td><?=$model->email?></td>
             </tr>
          <tr>
            <th><?=Yii::t('app', 'Phone')?></th><td><?=$model->phone?></td>


            <th><?=Yii::t('app', 'Member Since')?></th><td><?=Yii::$app->getTable->date_format($model->created_at)?></td>

     </table>
 </div>
</div>
</div>
                        </div>
                    </div>
</div>
</div>
</div>
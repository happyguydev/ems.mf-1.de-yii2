<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\User */

$this->title = $model->first_name . ' ' . $model->last_name;
$action = 'index';
$update_url = Yii::getAlias('@web') . '/admin/user/update?id=' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => [$action]];
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
                              <div class="w-full sm:w-auto flex items-center sm:ml-auto mt-3 sm:mt-0">
                                   <a href="<?=$update_url?>" class="button text-white bg-theme-1 shadow-md mr-2"><?=Yii::t('app', 'Update')?></a>
                                </div>
                            </div>
<div class="flex flex-col lg:flex-row border-b border-gray-200 dark:border-dark-5 pb-5 -mx-5">
                        <div class="flex flex-2 px-5 items-center justify-center lg:justify-start">
                            <div class="w-20 h-20 sm:w-24 sm:h-24 flex-none lg:w-32 lg:h-32 image-fit ml-5 relative">
                                 <img data-action="zoom" alt="<?=$model->first_name?> <?=$model->last_name?>" class="rounded-full border border-gray-200 ml-5" src="<?=$image?>?v=<?=date('YmdHis')?>" onerror="this.onerror=null;this.src='<?=Yii::getAlias('@web')?>/web/profile/default.jpg'" style="border:2px solid #8080802e;">


                            </div>
                           <div style="margin-left: 50px">
                                <div class="w-24 sm:w-40 truncate sm:whitespace-normal font-medium text-lg"><?=$model['first_name']?> <?=$model['last_name']?></div>
                                <div class="text-gray-600"><?=$model->username?></div>
                            </div>
                        </div>

                        <div class="mt-6 lg:mt-0 flex-1 px-5 border-t lg:border-0 border-gray-200 dark:border-dark-5 pt-5 lg:pt-0">
                        <div class="p-5" id="basic-table">
                                <div class="preview">
                                    <div class="overflow-x-auto">
     <table class="table">
            <tr>


            <th><?=Yii::t('app', 'Username')?></th><td><?=$model->username?></td>



            <th><?=Yii::t('app', 'Phone')?></th><td><?=$model->phone?></td>
          </tr>



           <tr>
            <th><?=Yii::t('app', 'Working Hours')?></th><td><?=$userDetail['working_hours']?></td>



            <th><?=Yii::t('app', 'Allowed Leave Hours')?></th><td><?=$userDetail['allowed_leave_hours']?></td>

          </tr>

          <tr>

            <th><?=Yii::t('app', 'Member Since')?></th><td><?=Yii::$app->getTable->date_format($model->created_at)?></td>

            <th><?=Yii::t('app', 'Last Updated')?></th><td><?=Yii::$app->getTable->date_format($model->updated_at)?></td>
          </tr>
          <tr>
            <th><?=Yii::t('app', 'Last Login')?></th>
            <td><?=$model->getLast($model)?></td>
            <th><?=Yii::t('app', 'Account Status')?></th>
            <?php
if ($model->status == 1) {
	$clr = 'green';
} else {
	$clr = 'orange';
}
?>
            <td style="color:<?=$clr?>"><?=Yii::t('app', $model->getStatus($model))?></td>
          </tr>
     </table>
 </div>
</div>
</div>
                        </div>
                    </div>
</div>
</div>
</div>
<!-- <div class="grid grid-cols-12 gap-12 mt-5">
                    <div class="intro-y col-span-12 lg:col-span-12">
                        <div class="intro-y box">
                            <div class="flex flex-col sm:flex-row items-center p-3 border-b border-gray-200">
                                <h2 class="font-medium text-base mr-auto">
                                    <?=Yii::t('app', 'Email Signature')?>
                                </h2>

                            </div>
<div class="flex flex-col lg:flex-row border-b border-gray-200 dark:border-dark-5 pb-5 -mx-5">

                        <div class="mt-6 lg:mt-0 flex-1 px-5 border-t lg:border-0 border-gray-200 dark:border-dark-5 pt-5 lg:pt-0">
                        <div class="p-5" id="basic-table">
                                <div class="preview">
                                    <div class="overflow-x-auto">
                                      <?=Html::decode($userDetail['email_signature'])?>
                                      </div>
</div>
</div>
</div>
</div>
</div>
</div>
</div> -->

<?php
use yii\widgets\LinkPager;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'User Emails');
$this->params['breadcrumbs'][] = $this->title;
?>
<?php if (Yii::$app->session->hasFlash('successStatus')): ?>
           <div class="rounded-md flex items-center px-5 py-4 mb-2 bg-theme-9 text-white mt-5 successStatus">
            <?=Yii::$app->session->getFlash('successStatus');?>
            <i data-feather="x" class="w-4 h-4 ml-auto" onclick="hideAlert();"></i>
                </div>
            <?php endif;?>
 <div class="grid grid-cols-12 gap-6 mt-5">


                    <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
                         <?php if (Yii::$app->Utility->hasAccess('project', 'create')) {?>
                        <a class="button text-white bg-theme-1 shadow-md mr-2" href="<?=Yii::getAlias('@web')?>/mailbox/user-email/create"><?=Yii::t('app', 'Add New')?></a>
                        <?php
}
?>
                        <div class="dropdown hidden">
                            <button class="dropdown-toggle button px-2 box text-gray-700 dark:text-gray-300">
                                <span class="w-5 h-5 flex items-center justify-center"> <i class="w-4 h-4" data-feather="plus"></i> </span>
                            </button>
                        </div>
                        <!-- <div class="hidden md:block mx-auto text-gray-600">Showing 1 to 10 of 150 entries</div> -->
                    </div>
                    <!-- BEGIN: Data List -->
                    <div class="intro-y col-span-12 overflow-auto">
                         <?php Pjax::begin();?>

<table class="table table-report -mt-2">
    <thead>
                                <tr>
                                    <th class="whitespace-nowrap"><?=Yii::t('app', 'S.No')?></th>
                                    <th class="text-center whitespace-nowrap"><?=Yii::t('app', 'Email Client')?></th>
                                    <th class="text-center whitespace-nowrap"><?=Yii::t('app', 'Email')?></th>
                                    <th class="text-center whitespace-nowrap"><?=Yii::t('app', 'Created At')?></th>
                                    <th class="text-center whitespace-nowrap"><?=Yii::t('app', 'Actions')?></th>
                                </tr>
                            </thead>
                            <tbody>
                              <?php
if (count($model) > 0) {
	foreach ($model as $key => $value) {

		?>
                                <tr class="intro-x">

                                    <td><?=$serial_number + 1?></td>


                                    <td class="text-center"><?=$value->emailClient['title']?></td>
                                    <td class="text-center"><?=$value->email?></td>
                                    <td class="text-center"><?=Yii::$app->getTable->datetime_format($value['created_at'])?></td>

                                    <td class="table-report__action w-156">

                                        <div class="flex justify-center items-center">

                                            <a class="flex items-center mr-3 text-theme-1 tooltip" href="<?=Yii::getAlias('@web')?>/mailbox/user-email/update?id=<?=$value['id']?>" title="<?=Yii::t('app', 'Edit')?>"> <i data-feather="check-square" class="w-4 h-4 mr-1"></i> <?=Yii::t('app', 'Edit')?> </a>

                                               <a class="flex items-center mr-3 text-theme-6 tooltip" href="<?=Yii::getAlias('@web')?>/mailbox/user-email/delete?id=<?=$value['id']?>" title="<?=Yii::t('app', 'Delete')?>" data-method="post" data-confirm="<?=Yii::t('app', 'Are you sure you want to delete this user email ?')?>"> <i data-feather="trash" class="w-4 h-4 mr-1"></i> <?=Yii::t('app', 'Delete')?> </a>

                                </div>
</td>

                                </tr>
                                <?php
$serial_number++;
	}
} else {
	?>
<tr class="intro-x">
<td class="text-center text-theme-6" colspan="9">
    No Data Found!
</td>
</tr>
    <?php
}
?>
                            </tbody>
                        </table>



    <?php Pjax::end();?>

</div>
</div>
<div class="intro-y col-span-12 flex flex-wrap sm:flex-row sm:flex-nowrap items-center">
                   <?php
echo LinkPager::widget([
	'pagination' => $pages,
	'linkOptions' => ['class' => 'pagination__link'],
	'disabledPageCssClass' => 'pagination__link',
]);
?>
</div>

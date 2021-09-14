<?php
use yii\widgets\LinkPager;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\admin\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', $title);
$this->params['breadcrumbs'][] = $this->title;
$action = 'add-user';
?>

                                    	 <div class="grid grid-cols-12 gap-6 mt-5">
                    <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
                        <a class="button text-white bg-theme-1 shadow-md mr-2" href="<?=Yii::getAlias('@web')?>/admin/user/<?=$action?>"><?=Yii::t('app',
	'Add New User')?></a>
                        <div class="dropdown hidden">
                            <button class="dropdown-toggle button px-2 box text-gray-700 dark:text-gray-300">
                                <span class="w-5 h-5 flex items-center justify-center"> <i class="w-4 h-4" data-feather="plus"></i> </span>
                            </button>
                            <div class="dropdown-box w-40 hidden">
                                <div class="dropdown-box__content box dark:bg-dark-1 p-2">
                                    <a href="" class="flex items-center block p-2 transition duration-300 ease-in-out bg-white dark:bg-dark-1 hover:bg-gray-200 dark:hover:bg-dark-2 rounded-md"> <i data-feather="printer" class="w-4 h-4 mr-2"></i> <?=Yii::t('app', 'Print')?> </a>
                                    <a href="" class="flex items-center block p-2 transition duration-300 ease-in-out bg-white dark:bg-dark-1 hover:bg-gray-200 dark:hover:bg-dark-2 rounded-md"> <i data-feather="file-text" class="w-4 h-4 mr-2"></i> <?=Yii::t('app', 'Export to Excel')?> </a>
                                    <a href="" class="flex items-center block p-2 transition duration-300 ease-in-out bg-white dark:bg-dark-1 hover:bg-gray-200 dark:hover:bg-dark-2 rounded-md"> <i data-feather="file-text" class="w-4 h-4 mr-2"></i> <?=Yii::t('app', 'Export to PDF')?> </a>
                                </div>
                            </div>
                        </div>
                        <div class="hidden md:block mx-auto text-gray-600"></div>
                        <!-- <div class="hidden md:block mx-auto text-gray-600">Showing 1 to 10 of 150 entries</div> -->
                        <div class="w-full sm:w-auto mt-3 sm:mt-0 sm:ml-auto md:ml-0">
                            <div class="w-156 relative text-gray-700 dark:text-gray-300">
                                <form name="myForm" action="<?=Yii::getAlias('@web')?>/admin/user/index" method="post">
                                    <input type="hidden" name="_csrf" value="<?=uniqid()?>"/>
                                <input type="text" class="input w-46 box pr-8 placeholder-theme-13" placeholder="<?=Yii::t('app', 'Search')?>..." value="<?=$search?>" name="search" onblur="submitForm()">
                               <i class="w-4 h-4 absolute my-auto inset-y-0 mr-3 right-0 search-icon" data-feather="search"></i>

                                <a class="button text-white bg-theme-6 shadow-md w-100 ml-2" href="<?=Yii::getAlias('@web')?>/admin/user/index"><?=Yii::t('app', 'Reset')?></a>
                            </form>
                        </div>
                        </div>
                    </div>
                    <!-- BEGIN: Data List -->

                    <div class="intro-y col-span-12 overflow-auto">
                                                                                         <?php Pjax::begin();?>

                    	<table class="table table-report -mt-2">
                            <thead>
                                <tr>
                                    <th class="whitespace-nowrap"><?=Yii::t('app', 'S.No')?></th>
                                    <th class="whitespace-nowrap"><?=Yii::t('app', 'Profile Pic')?></th>
                                    <th class="whitespace-nowrap"><?=Yii::t('app', 'Name')?></th>
                                    <th class="text-center whitespace-nowrap"><?=Yii::t('app', 'Email')?></th>
                                    <th class="text-center whitespace-nowrap"><?=Yii::t('app', 'User Role')?></th>
                                    <th class="text-center whitespace-nowrap"><?=Yii::t('app', 'Phone')?></th>
                                    <th class="text-center whitespace-nowrap"><?=Yii::t('app', 'Created At')?></th>
                                    <th class="text-center whitespace-nowrap"><?=Yii::t('app', 'Status')?></th>
                                    <th class="text-center whitespace-nowrap"><?=Yii::t('app', 'Actions')?></th>
                                </tr>
                            </thead>
                            <tbody>
                            	<?php
if (count($model) > 0) {
	foreach ($model as $key => $value) {

		if ($value->profile_picture != '') {
			$image = Yii::getAlias('@web') . '/web/profile/' . $value->id . '/' . $value->profile_picture;
		} else {
			$image = Yii::getAlias('@web') . '/web/profile/default.jpg';
		}

		?>
                                <tr class="intro-x">
                                    <td><?=$serial_number + 1?></td>
                                    <td class="w-40">
                                        <div class="flex">
                                            <div class="w-10 h-10 image-fit zoom-in">
                                                <img alt="<?=$value['first_name'] . ' ' . $value['last_name']?>" class="tooltip rounded-full" src="<?=$image?>" title="<?=$value['first_name'] . ' ' . $value['last_name']?>" onerror="this.onerror=null;this.src='<?=Yii::getAlias('@web');?>/web/profile/default.jpg';">
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <a href="<?=Yii::getAlias('@web')?>/admin/user/view?id=<?=$value['id']?>" class="font-medium whitespace-nowrap"><?=$value['first_name']?> <?=$value['last_name']?></a>
                                        <div class="text-gray-600 text-xs whitespace-nowrap mt-0.5"><?=$value['username']?></div>
                                        <div class="text-gray-600 text-xs whitespace-nowrap mt-0.5"><b><?=Yii::t('app', 'Last Login At')?> :</b> <?=$value->getLast($value)?></div>
                                    </td>
                                    <td class="text-center"><?=$value['email']?></td>
                                    <td class="text-center"><?=$value['user_role']?></td>
                                    <td class="text-center"><?=$value['phone']?></td>
                                    <td class="text-center"><?=Yii::$app->getTable->datetime_format($value['created_at'])?></td>
                                    <td class="w-40">
                                    	<?php
if ($value['status'] == 1) {
			?>
                                        <div class="flex items-center justify-center text-theme-9"> <i data-feather="thumbs-up" class="w-4 h-4 mr-2"></i> <?=Yii::$app->Utility->getStatus($value['status']);?> </div>
                                        <?php
} else {
			?>
                                    	<div class="flex items-center justify-center text-theme-6"> <i data-feather="thumbs-down" class="w-4 h-4 mr-2"></i> <?=Yii::$app->Utility->getStatus($value['status']);?> </div>
                                    	<?php
}
		?>

                                    </td>
                                    <td class="table-report__action w-56">
                                        <div class="flex justify-center items-center">
                                            <a class="flex items-center mr-3 text-theme-1 tooltip" href="<?=Yii::getAlias('@web')?>/admin/user/update?id=<?=$value['id']?>" title="<?=Yii::t('app', 'Edit')?>"> <i data-feather="check-square" class="w-4 h-4 mr-1"></i> <?=Yii::t('app', 'Edit')?> </a>

                                             <a class="flex items-center mr-3 text-theme-9 tooltip" href="<?=Yii::getAlias('@web')?>/admin/user/view?id=<?=$value['id']?>" title="<?=Yii::t('app', 'View')?>"> <i data-feather="eye" class="w-4 h-4 mr-1"></i> <?=Yii::t('app', 'View')?> </a>
<?php
if ($value['id'] != 1) {
			?>
                                              <a class="flex items-center mr-3 text-theme-6 tooltip" href="<?=Yii::getAlias('@web')?>/admin/user/status?val=-1&id=<?=$value['id']?>" title="<?=Yii::t('app', 'Delete')?>" data-method="post" data-confirm="<?=Yii::t('app', 'Are you sure you want to delete this user?')?>"> <i data-feather="trash" class="w-4 h-4 mr-1"></i> <?=Yii::t('app', 'Delete')?> </a>
                                          <?php }?>

                                            <?php
if ($value['status'] == 1 && $value['id'] != 1) {
			?>
                                            	<a class="flex items-center mr-3 text-theme-6 tooltip" href="<?=Yii::getAlias('@web')?>/admin/user/status?val=0&id=<?=$value['id']?>" title="<?=Yii::t('app', 'Disable')?>"> <i data-feather="thumbs-down" class="w-4 h-4 mr-1"></i> <?=Yii::t('app', 'Disable')?> </a>
                                            	<?php
} else {
			?>
            <?php
if ($value['id'] != 1) {
				?>

                                            	 <a class="flex items-center mr-3 text-theme-9 tooltip" href="<?=Yii::getAlias('@web')?>/admin/user/status?val=1&id=<?=$value['id']?>" title="<?=Yii::t('app', 'Enable')?>"> <i data-feather="thumbs-up" class="w-4 h-4 mr-1"></i> <?=Yii::t('app', 'Enable')?> </a>
                                                 <?php
}
			?>

                                            	<?php
}
		?>
                                        </div>
                                    </td>
                                </tr>
                                <?php
$serial_number++;
	}
} else {
	?>
    <tr>
        <td class="text-theme-6 text-center" colspan="8"><?=Yii::t('app', 'No Data Found!')?></td>
    </tr>

    <?php
}
?>
                            </tbody>
                           </table>
                        <?php Pjax::end();?>

                       </div>

                   </div>
                   <br clear="all">

<div class="intro-y col-span-12 flex flex-wrap sm:flex-row sm:flex-nowrap items-center">
                   <?php
echo LinkPager::widget([
	'pagination' => $pages,
	'linkOptions' => ['class' => 'pagination__link'],
	'disabledPageCssClass' => 'pagination__link',
]);
?>
</div>
<script type="text/javascript">
  function submitForm() {
        $('form').submit();
    }
</script>



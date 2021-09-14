<?php
use yii\widgets\LinkPager;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Tasks');
$this->params['breadcrumbs'][] = $this->title;
$user = Yii::$app->user->identity;
?>
  <?php if (Yii::$app->session->hasFlash('successStatus')): ?>
           <div class="rounded-md flex items-center px-5 py-4 mb-2 bg-theme-9 text-white mt-5 successStatus">
            <?=Yii::$app->session->getFlash('successStatus');?>
            <i data-feather="x" class="w-4 h-4 ml-auto" onclick="hideAlert();"></i>
                </div>
            <?php endif;?>

 <div class="grid grid-cols-12 gap-6 mt-5">


                    <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
                         <?php if (Yii::$app->Utility->hasAccess('task', 'create')) {?>
                        <a class="button text-white bg-theme-1 shadow-md mr-2" href="<?=Yii::getAlias('@web')?>/task/create"><?=Yii::t('app', 'Add New Task')?></a>
                        <?php
}
?>
                        <div class="dropdown hidden">
                            <button class="dropdown-toggle button px-2 box text-gray-700 dark:text-gray-300">
                                <span class="w-5 h-5 flex items-center justify-center"> <i class="w-4 h-4" data-feather="plus"></i> </span>
                            </button>
                        </div>
                        <!-- <div class="hidden md:block mx-auto text-gray-600">Showing 1 to 10 of 150 entries</div> -->
                        <div class="hidden md:block mx-auto text-gray-600"></div>
                          <div class="w-full sm:w-auto mt-3 sm:mt-0 sm:ml-auto md:ml-0">
                            <div class="w-156 relative text-gray-700 dark:text-gray-300">
                                <form name="myForm" action="<?=Yii::getAlias('@web')?>/task/index" method="post">
                                    <input type="hidden" name="_csrf" value="<?=uniqid()?>"/>
                                <input type="text" class="input w-46 box pr-8 placeholder-theme-13" placeholder="<?=Yii::t('app', 'Search')?>..." value="<?=$search?>" name="search" onblur="submitForm()">
                               <i class="w-4 h-4 absolute my-auto inset-y-0 mr-3 right-0 search-icon" data-feather="search"></i>

                                <a class="button text-white bg-theme-6 shadow-md w-100 ml-2" href="<?=Yii::getAlias('@web')?>/task/index"><?=Yii::t('app', 'Reset')?></a>
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
                                    <th class="whitespace-nowrap"><?=Yii::t('app', 'Name')?></th>
                                    <th class="text-center whitespace-nowrap"><?=Yii::t('app', 'Start Date')?></th>
                                    <th class="text-center whitespace-nowrap"><?=Yii::t('app', 'End Date')?></th>
                                    <!-- <th class="text-center whitespace-nowrap"><?=Yii::t('app', 'Remaining Days')?></th> -->
                                    <th class="text-center whitespace-nowrap"><?=Yii::t('app', 'Status')?></th>
                                    <th class="text-center whitespace-nowrap"><?=Yii::t('app', 'Created At')?></th>
                                    <th class="text-center whitespace-nowrap"><?=Yii::t('app', 'Created By')?></th>
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

                                    <td class="w-40">
                                        <a href="<?=Yii::getAlias('@web')?>/task/view?id=<?=$value['id']?>" class="font-medium whitespace-nowrap"><?=$value['name']?></a>

                                        <?php
if ($value->getAssignees()) {
			?>
                                        <div class="text-gray-600 text-xs whitespace-nowrap mt-0.5"><b><?=Yii::t('app', 'Task Assign To')?> : </b>
                                        <div class="flex mt-2">
                                           <?=$value->getAssignees()?>
                                        </div>
                                    </div>
                                    <?php
}
		?>
                                    </td>
                                    <td class="text-center w-40"><?=Yii::$app->getTable->date_format($value['start_date'])?></td>
                                    <td class="text-center"><?=Yii::$app->getTable->date_format($value['end_date'])?></td>
<!--                                     <td class="text-center"><?=Yii::$app->getTable->number_of_days($value['start_date'], $value['end_date'])?> <?=Yii::t('app', 'Days')?> </td>-->


                                        <td class="w-40">
  <?php
$status_clr = $value->getStatusColor();
		?>
                                        <div class="flex items-center justify-center text-center text-theme-9"><span class="px-2 py-1 rounded-full bg-<?=$status_clr?> text-white mr-1" style="font-size:11px;width:120px "><?=Yii::t('app', $value->getStatus())?></span> </div>


                                    </td>

                                     <td class="text-center"><?=Yii::$app->getTable->datetime_format($value['created_at'])?></td>
                                      <td class="text-center"><a href="<?=Yii::getAlias('@web')?>/user/profile?id=<?=$value['created_by']?>"><?=$value->getCreatedBy()?></a></td>
                                    <td class="table-report__action w-156">

                                        <div class="flex justify-center items-center">
                                              <?php if (Yii::$app->Utility->hasAccess('task', 'update')) {?>
                                            <a class="flex items-center mr-3 text-theme-1  tooltip " href="<?=Yii::getAlias('@web')?>/task/update?id=<?=$value['id']?>" title="<?=Yii::t('app', 'Edit')?>"> <i data-feather="check-square" class="w-4 h-4 mr-1"></i> <?=Yii::t('app', 'Edit')?> </a>
                                            <?php
}
		?>
                                          <?php if (Yii::$app->Utility->hasAccess('task', 'view')) {?>
                                             <a class="flex items-center mr-3 text-theme-9 tooltip" href="<?=Yii::getAlias('@web')?>/task/view?id=<?=$value['id']?>" title="<?=Yii::t('app', 'View')?>"> <i data-feather="eye" class="w-4 h-4 mr-1"></i> <?=Yii::t('app', 'View')?> </a>
                                             <?php
}
		?>

          <?php if (Yii::$app->Utility->hasAccess('task', 'update')) {
			?>

<div class="dropdown">
                            <button class="dropdown-toggle button px-2 box text-gray-700 dark:text-gray-300">
                                <span class="flex items-center justify-center bg-white text-<?=$value->getStatusColor()?>"> <?=Yii::t('app', $value->getStatus())?> <i class="w-4 h-4" data-feather="chevron-down"></i> </span>
                            </button>
                            <div class="dropdown-menu hidden">
                                <div class="dropdown-menu__content box dark:bg-dark-1 p-2">
                                    <?php
if ($value->status != 'upcoming') {
				?>
                                    <a href="<?=Yii::getAlias('@web')?>/task/status?id=<?=$value['id']?>&val=upcoming" data-method="post" data-confirm="<?=Yii::t('app', 'Are you sure you want to change the status?')?>" class="flex items-center block p-2 transition duration-300 ease-in-out bg-white dark:bg-dark-1 hover:bg-gray-200 dark:hover:bg-dark-2 rounded-md text-theme-1 text-bold"> <?=Yii::t('app', 'Upcoming')?> </a>
                                    <?php
}

			if ($value->status != 'in-progress') {
				?>
                                    <a href="<?=Yii::getAlias('@web')?>/task/status?id=<?=$value['id']?>&val=in-progress" data-method="post" data-confirm="<?=Yii::t('app', 'Are you sure you want to change the status?')?>" class="flex items-center block p-2 transition duration-300 ease-in-out bg-white text-theme-12 dark:bg-dark-1 hover:bg-gray-200 dark:hover:bg-dark-2 rounded-md text-bold"> <?=Yii::t('app', 'In Progress')?> </a>
                                    <?php
}
			if ($value->status != 'completed') {
				?>
                                    <a href="<?=Yii::getAlias('@web')?>/task/status?id=<?=$value['id']?>&val=completed" data-method="post" data-confirm="<?=Yii::t('app', 'Are you sure you want to change the status?')?>" class="flex items-center block p-2 transition duration-300 ease-in-out bg-white text-theme-9 dark:bg-dark-1 hover:bg-gray-200 dark:hover:bg-dark-2 rounded-md text-bold"> <?=Yii::t('app', 'Completed')?> </a>
                                    <?php
}
			?>

                                </div>
                            </div>
                        </div>
                        <?php
}
		?>

       <?php
if (Yii::$app->Utility->hasAccess('task', 'delete') && $value['trash'] == 0 && $value['created_by'] == $user->id && $user->user_role != 'admin') {
			?>
         <a class="flex items-center mr-3 text-theme-6 tooltip" href="<?=Yii::getAlias('@web')?>/task/delete?id=<?=$value['id']?>" title="<?=Yii::t('app', 'Delete')?>" data-method="post" data-confirm="<?=Yii::t('app', 'Are you sure you want to delete this task?')?>"> <i data-feather="trash" class="w-4 h-4 mr-1"></i> <?=Yii::t('app', 'Delete')?> </a>

         <?php
}
		?>

        <?php
if ($user->user_role == 'admin' && $value['trash'] == 0) {
			?>
<a class="flex items-center mr-3 text-theme-6 tooltip" href="<?=Yii::getAlias('@web')?>/task/delete?id=<?=$value['id']?>" title="<?=Yii::t('app', 'Delete')?>" data-method="post" data-confirm="<?=Yii::t('app', 'Are you sure you want to delete this task?')?>"> <i data-feather="trash" class="w-4 h-4 mr-1"></i> <?=Yii::t('app', 'Delete')?> </a>
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
<tr class="intro-x">
<td class="text-center text-theme-6" colspan="9">
    <?=Yii::t('app', 'No Data Found!')?>
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
     function hideAlert() {
    $('.successStatus').hide();
  }
</script>
<style type="text/css">
    .dark .bg-white {
        padding:5px;
        white-space: nowrap;
            background: #000;
    }

</style>
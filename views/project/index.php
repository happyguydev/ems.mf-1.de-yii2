<?php
use yii\widgets\LinkPager;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Projects');
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
                         <?php if (Yii::$app->Utility->hasAccess('project', 'create')) {?>
                        <a class="button text-white bg-theme-1 shadow-md mr-2" href="<?=Yii::getAlias('@web')?>/project/create"><?=Yii::t('app', 'Add New Project')?></a>
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
                                <form name="myForm" action="<?=Yii::getAlias('@web')?>/project/index" method="post">
                                    <input type="hidden" name="_csrf" value="<?=uniqid()?>"/>
                                <input type="text" class="input w-46 box pr-8 placeholder-theme-13" placeholder="Search..." value="<?=$search?>" name="search" onblur="submitForm()">
                               <i class="w-4 h-4 absolute my-auto inset-y-0 mr-3 right-0 search-icon" data-feather="search"></i>

                                <a class="button text-white bg-theme-6 shadow-md w-100 ml-2" href="<?=Yii::getAlias('@web')?>/project/index"><?=Yii::t('app', 'Reset')?></a>
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
                                    <th class="text-center whitespace-nowrap"><?=Yii::t('app', 'Progress')?></th>
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

                                    <td class="w-46" style="width:150px">
                                        <a href="<?=Yii::getAlias('@web')?>/project/view?id=<?=$value['id']?>" class="font-medium whitespace-nowrap"><?=$value['name']?></a>


                                        <div class="text-gray-600 text-xs whitespace-nowrap mt-0.5"><b><?=Yii::t('app', 'Billing Type')?> : </b>
                                           <?=$value->billing_type?>
                                    </div>

                                        <?php
if ($value->getTeamMembers()) {
			?>
                                        <div class="text-gray-600 text-xs whitespace-nowrap mt-0.5"><b><?=Yii::t('app', 'Team')?> : </b>
                                        <div class="flex mt-2">
                                           <?=$value->getTeamMembers()?>
                                        </div>
                                    </div>
                                    <?php
}
		?>
                                    </td>
                                    <td class="text-center"><?=Yii::$app->getTable->date_format($value['start_date'])?></td>
                                    <td class="text-center"><?=Yii::$app->getTable->date_format($value['end_date'])?></td>
                                    <td class="text-center">

                                        <?php
if ($value->progress == 100) {
			$progress_color = '#3ee63e';
		} elseif ($value->progress > 50) {
			$progress_color = 'blue';
		} else {
			$progress_color = 'orange';
		}

		?>
                        <div class="progress" style="margin-bottom: 0px;background: gainsboro;width: 140px">
  <div class="progress-bar" role="progressbar" aria-valuenow="<?=$value->progress?>"
  aria-valuemin="0" aria-valuemax="100" style="width:<?=$value->progress?>%;background: <?=$progress_color?>">
<?=$value->progress?>%
  </div>
</div>
</td>

                                        <td class="w-60">
  <?php
$status_clr = $value->getStatusColor();
		?>
                                        <div class="flex items-center justify-center text-theme- text-center"><span class="px-2 py-1 rounded-full bg-<?=$status_clr?> text-white mr-1" style="font-size:11px;width: 120px"><?=Yii::t('app', $value->getStatus())?></span> </div>


                                    </td>

                                     <td class="text-center"><?=Yii::$app->getTable->datetime_format($value['created_at'])?></td>
                                      <td class="text-center"><a href="<?=Yii::getAlias('@web')?>/user/profile?id=<?=$value['created_by']?>"><?=$value->getCreatedBy()?></a></td>
                                    <td class="table-report__action w-156">

                                        <div class="flex justify-center items-center">
                                            <?php if (Yii::$app->Utility->hasAccess('project', 'update')) {
			?>
                                            <a class="flex items-center mr-3 text-theme-1 tooltip" href="<?=Yii::getAlias('@web')?>/project/update?id=<?=$value['id']?>" title="<?=Yii::t('app', 'Edit')?>"> <i data-feather="check-square" class="w-4 h-4 mr-1"></i> <?=Yii::t('app', 'Edit')?> </a>
                                            <?php
}
		?>
                                        <?php if (Yii::$app->Utility->hasAccess('project', 'view')) {
			?>
                                             <a class="flex items-center mr-3 text-theme-9 tooltip" href="<?=Yii::getAlias('@web')?>/project/view?id=<?=$value['id']?>" title="<?=Yii::t('app', 'View')?>"> <i data-feather="eye" class="w-4 h-4 mr-1"></i> <?=Yii::t('app', 'View')?> </a>
                                             <?php
}
		?>

          <?php
if ($user->user_role == 'admin' && $value['trash'] == 0) {
			?>
             <a class="flex items-center mr-3 text-theme-6 tooltip" href="<?=Yii::getAlias('@web')?>/project/delete?id=<?=$value['id']?>" title="<?=Yii::t('app', 'Delete')?>" data-method="post" data-confirm="<?=Yii::t('app', 'Are you sure you want to delete this project?')?>"> <i data-feather="trash" class="w-4 h-4 mr-1"></i> <?=Yii::t('app', 'Delete')?> </a>
            <?php
}
		?>
          <?php
if (Yii::$app->Utility->hasAccess('project', 'delete') && $value['trash'] == 0 && $value['created_by'] == $user->id && $user->user_role != 'admin') {
			?>
         <a class="flex items-center mr-3 text-theme-6 tooltip" href="<?=Yii::getAlias('@web')?>/project/delete?id=<?=$value['id']?>" title="<?=Yii::t('app', 'Delete')?>" data-method="post" data-confirm="<?=Yii::t('app', 'Are you sure you want to delete this project?')?>"> <i data-feather="trash" class="w-4 h-4 mr-1"></i> <?=Yii::t('app', 'Delete')?> </a>

         <?php
}
		?>
        <?php if (Yii::$app->Utility->hasAccess('project', 'update')) {
			?>

<div class="dropdown" style="width:104px;">
                            <button class="dropdown-toggle button px-2 box text-gray-700 dark:text-gray-300">
                                <span class="flex items-center justify-center bg-white text-<?=$value->getStatusColor()?>"> <?=Yii::t('app', $value->getStatus())?> <i class="w-4 h-4" data-feather="chevron-down"></i> </span>
                            </button>
                            <div class="dropdown-menu hidden">
                                <div class="dropdown-menu__content box dark:bg-dark-1 p-2">
                                    <?php
if ($value->status != 'pending') {
				?>
                                    <a href="<?=Yii::getAlias('@web')?>/project/status?id=<?=$value['id']?>&val=pending" data-method="post" data-confirm="<?=Yii::t('app', 'Are you sure you want to change the status?')?>" class="flex items-center block p-2 transition duration-300 ease-in-out bg-white dark:bg-dark-1 hover:bg-gray-200 dark:hover:bg-dark-2 rounded-md text-theme-1 text-bold"> <?=Yii::t('app', 'Pending')?> </a>
                                    <?php
}

			if ($value->status != 'in-progress') {
				?>
                                    <a href="<?=Yii::getAlias('@web')?>/project/status?id=<?=$value['id']?>&val=in-progress" data-method="post" data-confirm="<?=Yii::t('app', 'Are you sure you want to change the status?')?>" class="flex items-center block p-2 transition duration-300 ease-in-out bg-white text-theme-12 dark:bg-dark-1 hover:bg-gray-200 dark:hover:bg-dark-2 rounded-md text-bold"> <?=Yii::t('app', 'In Progress')?> </a>
                                    <?php
}
			if ($value->status != 'delay') {
				?>
                                    <a href="<?=Yii::getAlias('@web')?>/project/status?id=<?=$value['id']?>&val=delay" data-method="post" data-confirm="<?=Yii::t('app', 'Are you sure you want to change the status?')?>" class="flex items-center block p-2 transition duration-300 ease-in-out bg-white text-theme-6 dark:bg-dark-1 hover:bg-gray-200 dark:hover:bg-dark-2 rounded-md text-bold"> <?=Yii::t('app', 'Delay')?> </a>
                                    <?php
}
			if ($value->status != 'completed') {
				?>
                                    <a href="<?=Yii::getAlias('@web')?>/project/status?id=<?=$value['id']?>&val=completed" data-method="post" data-confirm="<?=Yii::t('app', 'Are you sure you want to change the status?')?>" class="flex items-center block p-2 transition duration-300 ease-in-out bg-white text-theme-9 dark:bg-dark-1 hover:bg-gray-200 dark:hover:bg-dark-2 rounded-md text-bold"> <?=Yii::t('app', 'Completed')?> </a>
                                    <?php
}
			?>


                                </div>
                            </div>
                        </div>
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
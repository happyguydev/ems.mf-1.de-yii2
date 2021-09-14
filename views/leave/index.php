<?php
use yii\widgets\LinkPager;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Leaves');
$this->params['breadcrumbs'][] = $this->title;
?>
<?php if (Yii::$app->session->hasFlash('successStatus')): ?>
           <div class="rounded-md flex items-center px-5 py-4 mb-2 bg-theme-9 text-white mt-5 successStatus">
            <?=Yii::$app->session->getFlash('successStatus');?>
            <i data-feather="x" class="w-4 h-4 ml-auto" onclick="hideAlert();"></i>
                </div>
            <?php endif;?>
            <?php if (Yii::$app->session->hasFlash('errorStatus')): ?>
           <div class="rounded-md flex items-center px-5 py-4 mb-2 bg-theme-6 text-white mt-5 successStatus">
            <?=Yii::$app->session->getFlash('errorStatus');?>
            <i data-feather="x" class="w-4 h-4 ml-auto" onclick="hideAlert();"></i>
                </div>
            <?php endif;?>
 <div class="grid grid-cols-12 gap-6 mt-5">


                    <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
                         <?php if (Yii::$app->Utility->hasAccess('leave', 'create')) {?>
                        <a class="button text-white bg-theme-1 shadow-md mr-2" href="<?=Yii::getAlias('@web')?>/leave/create"><?=Yii::t('app', 'Add New Leave')?></a>
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
                                <form name="myForm" action="<?=Yii::getAlias('@web')?>/leave/index" method="post">
                                    <input type="hidden" name="_csrf" value="<?=uniqid()?>"/>
                                <input type="text" class="input w-46 box pr-8 placeholder-theme-13" placeholder="Search..." value="<?=$search?>" name="search" onblur="submitForm()">
                               <i class="w-4 h-4 absolute my-auto inset-y-0 mr-3 right-0 search-icon" data-feather="search"></i>

                                <a class="button text-white bg-theme-6 shadow-md w-100 ml-2" href="<?=Yii::getAlias('@web')?>/leave/index"><?=Yii::t('app', 'Reset')?></a>
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
                                    <th class="whitespace-nowrap"><?=Yii::t('app', 'Employee')?></th>
                                    <th class="text-center whitespace-nowrap"><?=Yii::t('app', 'Leave Type')?></th>
                                    <th class="text-center whitespace-nowrap"><?=Yii::t('app', 'From Date')?></th>
                                    <th class="text-center whitespace-nowrap"><?=Yii::t('app', 'To Date')?></th>
                                    <th class="text-center whitespace-nowrap"><?=Yii::t('app', 'Created At')?></th>
                                    <th class="text-center whitespace-nowrap"><?=Yii::t('app', 'Status')?></th>
                                    <?php
if (Yii::$app->user->identity->user_role == 'admin') {
	?>
                                    <th class="text-center whitespace-nowrap"><?=Yii::t('app', 'Actions')?></th>
                                    <?php
}
?>
                                </tr>
                            </thead>
                            <tbody>

                                  <?php
if (count($model) > 0) {
	foreach ($model as $key => $value) {

		?>
                                <tr class="intro-x">

                                    <td class="text-center"><?=$serial_number + 1?></td>
                                    <td><?=$value->getCreatedBy()?>
                                        <div class="text-gray-600 text-xs whitespace-nowrap mt-0.5"><b><?=Yii::t('app', 'Reason For Leave')?> : </b><?=$value['reason']?></div>

                                        <?php
if ($value['half_day'] == 'first-half') {
			$leave_for = Yii::t('app', 'First Half');

		} elseif ($value['half_day'] == 'second-half') {
			$leave_for = Yii::t('app', 'Second Half');
		} else {

			$leave_for = Yii::t('app', 'Full Day');
		}
		?>

                                         <div class="text-gray-600 text-xs whitespace-nowrap mt-0.5"><b><?=Yii::t('app', 'Leave For')?> : </b><?=$leave_for?></div>
                                         <div class="text-gray-600 text-xs whitespace-nowrap mt-0.5"><b><?=Yii::t('app', 'Location')?> : </b><?=$value->created_location != '' ? $value->created_location : Yii::t('app', 'Unable To Track')?></div>
                                    </td>
                                    <td class="text-center"><?=$value['leave_type']?></td>
                                    <td class="text-center"><?=Yii::$app->getTable->date_format($value['from_date'])?></td>
                                    <td class="text-center"><?=Yii::$app->getTable->date_format($value['to_date'])?></td>
                              <!--    <td class="text-center"><?=$value['remaining_leaves']?></td> -->
                                    <td class="text-center"><?=Yii::$app->getTable->datetime_format($value['created_at'])?></td>

                                    <td class="text-center">
                                          <?php
$status_clr = $value->getStatusColor();
		?>
                                          <div class="flex items-center justify-center text-theme-9"><span class="px-2 py-1 rounded-full bg-<?=$status_clr?> text-white mr-1 text-center" style="font-size:10px;width: 72px"><?=$value->getStatus();?></span> </div>
                                    </td>


<?php
if (Yii::$app->user->identity->user_role == 'admin') {
			?>
                                    <td class="table-report__action w-156">

                                        <div class="flex justify-center items-center">

                                             <?php if (Yii::$app->user->identity->user_role == 'admin') {?>
                                            <a class="flex items-center mr-3 text-theme-1  tooltip " href="<?=Yii::getAlias('@web')?>/leave/update?id=<?=$value['id']?>" title="<?=Yii::t('app', 'Edit')?>"> <i data-feather="check-square" class="w-4 h-4 mr-1"></i> <?=Yii::t('app', 'Edit')?> </a>
                                            <?php
}
			?>
<?php
if ($value['status'] == 0) {
				?>

                                                 <a class="flex items-center mr-3 text-theme-9 tooltip" href="<?=Yii::getAlias('@web')?>/leave/status?val=1&id=<?=$value['id']?>" title="<?=Yii::t('app', 'Approve')?>"> <i data-feather="thumbs-up" class="w-4 h-4 mr-1"></i> <?=Yii::t('app', 'Approve')?> </a>

                                                <?php
}
			?>
<?php
if ($value['status'] != '0') {
				?>
                                                <a class="flex items-center mr-3 text-theme-6 tooltip" href="<?=Yii::getAlias('@web')?>/leave/status?val=0&id=<?=$value['id']?>" title="<?=Yii::t('app', 'Disapprove')?>"> <i data-feather="thumbs-down" class="w-4 h-4 mr-1"></i> <?=Yii::t('app', 'Disapprove')?> </a>
                                                <?php
}

			?>
                                </div>

                                    </td>
                                    <?php
}
		?>
                                </tr>
                                <?php
$serial_number++;

	}
} else {
	?>
<tr class="intro-x">
<td class="text-center text-theme-6" colspan="11">
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
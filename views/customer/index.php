<?php
use yii\widgets\LinkPager;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\admin\models\search\CustomerSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Customers');
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
                         <?php if (Yii::$app->Utility->hasAccess('customer', 'create')) {?>
                        <a class="button text-white bg-theme-1 shadow-md mr-2" href="<?=Yii::getAlias('@web')?>/customer/create"><?=Yii::t('app', 'Add New Customer')?></a>
                        <?php
}
?>
                        <div class="dropdown hidden">
                            <button class="dropdown-toggle button px-2 box text-gray-700 dark:text-gray-300">
                                <span class="w-5 h-5 flex items-center justify-center"> <i class="w-4 h-4" data-feather="plus"></i> </span>
                            </button>
                            <div class="dropdown-box w-40 hidden">
                                <div class="dropdown-box__content box dark:bg-dark-1 p-2">
                                    <a href="" class="flex items-center block p-2 transition duration-300 ease-in-out bg-white dark:bg-dark-1 hover:bg-gray-200 dark:hover:bg-dark-2 rounded-md"> <i data-feather="printer" class="w-4 h-4 mr-2"></i> Print </a>
                                    <a href="" class="flex items-center block p-2 transition duration-300 ease-in-out bg-white dark:bg-dark-1 hover:bg-gray-200 dark:hover:bg-dark-2 rounded-md"> <i data-feather="file-text" class="w-4 h-4 mr-2"></i> Export to Excel </a>
                                    <a href="" class="flex items-center block p-2 transition duration-300 ease-in-out bg-white dark:bg-dark-1 hover:bg-gray-200 dark:hover:bg-dark-2 rounded-md"> <i data-feather="file-text" class="w-4 h-4 mr-2"></i> Export to PDF </a>
                                </div>
                            </div>
                        </div>
                        <!-- <div class="hidden md:block mx-auto text-gray-600">Showing 1 to 10 of 150 entries</div> -->
                        <div class="hidden md:block mx-auto text-gray-600"></div>
                          <div class="w-full sm:w-auto mt-3 sm:mt-0 sm:ml-auto md:ml-0">
                            <div class="w-156 relative text-gray-700 dark:text-gray-300">
                                <form name="myForm" action="<?=Yii::getAlias('@web')?>/customer/index" method="post">
                                    <input type="hidden" name="_csrf" value="<?=uniqid()?>"/>
                                <input type="text" class="input w-46 box pr-8 placeholder-theme-13" placeholder="Search..." value="<?=$search?>" name="search" onblur="submitForm()">
                               <i class="w-4 h-4 absolute my-auto inset-y-0 mr-3 right-0 search-icon" data-feather="search"></i>

                                <a class="button text-white bg-theme-6 shadow-md w-100 ml-2" href="<?=Yii::getAlias('@web')?>/customer/index"><?=Yii::t('app', 'Reset')?></a>
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
                                    <th class="text-center whitespace-nowrap"><?=Yii::t('app', 'Email')?></th>
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

		?>
                                <tr class="intro-x">

                                    <td><?=$serial_number + 1?></td>

                                    <td>
                                        <a href="<?=Yii::getAlias('@web')?>/customer/view?id=<?=$value['id']?>" class="font-medium whitespace-nowrap"><?=$value['first_name']?> <?=$value['last_name']?></a>
                                        <div class="text-gray-600 text-xs whitespace-nowrap mt-0.5"><b>Address : </b><?=$value->getAddress($value)?></div>
                                        <?php
if ($value['company_name'] != '') {
			?>
                                        <div class="text-gray-600 text-xs whitespace-nowrap mt-0.5"><b>Company Name :</b> <?=$value['company_name']?></div>
                                        <?php
}
		?>
                                    </td>
                                    <td class="text-center"><?=$value['email']?></td>
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
                                            <a class="flex items-center mr-3 text-theme-1 tooltip" href="<?=Yii::getAlias('@web')?>/customer/update?id=<?=$value['id']?>" title="<?=Yii::t('app', 'Edit')?>"> <i data-feather="check-square" class="w-4 h-4 mr-1"></i> <?=Yii::t('app', 'Edit')?> </a>
                                             <a class="flex items-center mr-3 text-theme-9 tooltip" href="<?=Yii::getAlias('@web')?>/customer/view?id=<?=$value['id']?>" title="<?=Yii::t('app', 'View')?>"> <i data-feather="eye" class="w-4 h-4 mr-1"></i> <?=Yii::t('app', 'View')?> </a>

                                            <?php
if ($value['status'] == 1) {
			?>
                                                <a class="flex items-center mr-3 text-theme-6 tooltip" href="<?=Yii::getAlias('@web')?>/customer/status?val=0&id=<?=$value['id']?>" title="<?=Yii::t('app', 'Disable')?>"> <i data-feather="thumbs-down" class="w-4 h-4 mr-1"></i> <?=Yii::t('app', 'Disable')?> </a>
                                                <?php
} else {
			?>

                                                 <a class="flex items-center mr-3 text-theme-9 tooltip" href="<?=Yii::getAlias('@web')?>/customer/status?val=1&id=<?=$value['id']?>" title="<?=Yii::t('app', 'Enable')?>"> <i data-feather="thumbs-up" class="w-4 h-4 mr-1"></i> <?=Yii::t('app', 'Enable')?> </a>

                                                <?php
}
		?>
<?php
if ($value['trash'] == 0) {
			?>
         <a class="flex items-center mr-3 text-theme-6 tooltip" href="<?=Yii::getAlias('@web')?>/customer/delete?id=<?=$value['id']?>" title="<?=Yii::t('app', 'Delete')?>" data-method="post" data-confirm="<?=Yii::t('app', 'Are you sure you want to delete this customer?')?>"> <i data-feather="trash" class="w-4 h-4 mr-1"></i> <?=Yii::t('app', 'Delete')?> </a>

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
<td class="text-center text-theme-6" colspan="7">
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
</script>
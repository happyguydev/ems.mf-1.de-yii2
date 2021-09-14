 <?php
use app\models\User;
use yii\widgets\LinkPager;
use yii\widgets\Pjax;

$this->title = Yii::t('app', 'Leave Report');
$this->params['breadcrumbs'][] = $this->title;
$user = User::find()->where(['status' => 1])->all();
$user_role = Yii::$app->user->identity->user_role;

?>


 <div class="grid grid-cols-12 gap-6 mt-5">


                    <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2" style="overflow: hidden;">
                        <?php if (Yii::$app->Utility->hasAccess('leave_report', 'export')) {?>
 <div class="dropdown inline-block" data-placement="bottom-start">
             <button class="dropdown-toggle button px-2 box text-white bg-theme-1" >
                              <?=Yii::t('app', 'Export')?>
                            </button>
              <div class="dropdown-menu w-48" style="width:150px;">
                <div class="dropdown-menu__content box dark:bg-dark-1 p-2">
                  <div class="px-4 py-2 border-b border-gray-200 dark:border-dark-5 font-medium"><?=Yii::t('app', 'Export Tools')?></div>
                  <div class="p-2">
                    <!-- <a href="javascript:void(0)" onclick="printData()" class="flex items-center block p-2 transition duration-300 ease-in-out bg-white dark:bg-dark-1 hover:bg-gray-200 dark:hover:bg-dark-2 rounded-md">
                        <i data-feather="printer" class="w-4 h-4 text-gray-700 dark:text-gray-300 mr-2"></i>
                         Print
                     </a> -->
                     <a href="javascript:void(0)" id="xls" class="flex items-center block p-2 transition duration-300 ease-in-out bg-white dark:bg-dark-1 hover:bg-gray-200 dark:hover:bg-dark-2 rounded-md">
                        <i data-feather="external-link" class="w-4 h-4 text-gray-700 dark:text-gray-300 mr-2"></i>
                         <?=Yii::t('app', 'Excel')?>
                     </a>
                  <!--    <a href="javascript:void(0)" onclick="exportInCsv()" id="csv" class="flex items-center block p-2 transition duration-300 ease-in-out bg-white dark:bg-dark-1 hover:bg-gray-200 dark:hover:bg-dark-2 rounded-md">
                        <i data-feather="file-text" class="w-4 h-4 text-gray-700 dark:text-gray-300 mr-2"></i>
                    <?=Yii::t('app', 'CSV')?>
                </a> -->
                <a href="javascript:void(0)" id="pdf" class="flex items-center block p-2 transition duration-300 ease-in-out bg-white dark:bg-dark-1 hover:bg-gray-200 dark:hover:bg-dark-2 rounded-md">
                    <i data-feather="archive" class="w-4 h-4 text-gray-700 dark:text-gray-300 mr-2"></i>
                <?=Yii::t('app', 'PDF')?>
            </a>
        </div>
                </div>
              </div>
            </div>
<?php
}
?>
                        <!-- <div class="hidden md:block mx-auto text-gray-600">Showing 1 to 10 of 150 entries</div> -->
                        <div class="hidden md:block mx-auto text-gray-600"></div>
                           <?php
if ($user_role == 'admin') {
	$right = "margin-right:-97px";
} else {
	$right = "margin-right:0px";
}

?>
                          <div class="w-full sm:w-auto mt-3 sm:mt-0 sm:ml-auto md:ml-0" style="<?=$right?>">
                              <div class="w-156 relative text-gray-700 dark:text-gray-300">

                            <form id="myForm" method="get" action="<?=Yii::getAlias('@web')?>/report/index/leave">
                              <?php
$column_name = $user_role == 'admin' ? "col-md-4" : "col-md-8";

?>

<div class="<?=$column_name?>">
 <div class="form-group">
<label class="control-label mr-2" style="font-weight: bold"><?=Yii::t('app', 'Select year')?></label>
<select name="year" class="input box pr-8 placeholder-theme-13" style="width: 200px">
       <?php

for ($y = date('Y'); $y >= 1984; --$y) {
	?>
    <option value="<?=$y?>" <?=($y == $year) ? 'selected' : ''?>><?=$y?></option>
    <?php
}
?>
</select>
</div>
 </div>
 <?php
if ($user_role == 'admin') {
	?>
 <div class="col-md-4">
   <div class="form-group">

<label class="control-label" style="font-weight: bold"><?=Yii::t('app', 'Select User')?></label>
<select name="user_id" class="input box placeholder-theme-13 " style="width: 100%">
  <option value="all"><?=Yii::t('app', 'Select User')?></option>
  <?php
if (count($user) > 0) {
		foreach ($user as $uk => $uv) {
			?>
  <option value="<?=$uv['id']?>" <?=($user_id == $uv['id']) ? 'selected' : ''?>><?=$uv['first_name'] . ' ' . $uv['last_name']?></option>
  <?php
# code...
		}
	}
	?>
</select>
</div>
</div>
<?php
}
?>
<?php
if ($user_role == 'admin') {
	?>
 <div class="col-md-4">
    <div class="flex mt-5">
 <input type="submit" class="btn btn-success btn-round" value="<?=Yii::t('app', 'Submit')?>"/>
    <a href="<?=Yii::getAlias('@web')?>/report/index/leave" class="btn btn-danger btn-round ml-2"><?=Yii::t('app', 'Reset')?></a>
 </div>
</div>
<?php
} else {
	?>
  <div class="col-md-4">
    <div class="flex justify-center">
 <input type="submit" class="btn btn-success btn-round" value="<?=Yii::t('app', 'Submit')?>"/>
    <a href="<?=Yii::getAlias('@web')?>/report/index/leave" class="btn btn-danger btn-round ml-2"><?=Yii::t('app', 'Reset')?></a>
 </div>
</div>
  <?php
}
?>
</form>
                        </div>
                        </div>
                    </div>

  <div class="intro-y col-span-12 overflow-auto" style="margin-top:-20px">
                         <?php Pjax::begin();?>

<table class="table table-report" id="export-table">
    <thead>
                                <tr>
                                    <th class="text-center whitespace-nowrap"><?=Yii::t('app', 'S.No')?></th>
                                    <th class="text-center whitespace-nowrap"><?=Yii::t('app', 'Full Name')?></th>
                                    <?php

for ($m1 = 1; $m1 <= 12; ++$m1) {
	?>

	<th class="text-center whitespace-nowrap"><?=Yii::t('app', date('F', mktime(0, 0, 0, $m1, 1)))?></th>
    <?php
}
?>
<th class="text-center whitespace-nowrap"><?=Yii::t('app', 'Total Day Off')?></th>

                                </tr>
                            </thead>
                            <tbody>
                                <?php

if (count($model) > 0) {

	foreach ($model as $key => $value) {

		?>
                                <tr>
                                    <td class="text-center"><?=$s_no + 1?></td>
                                    <td class="text-center"><?=$value['first_name'] . ' ' . $value['last_name']?></td>
                                        <?php

		for ($m = 1; $m <= 12; ++$m) {
			?>
                                    <td class="text-center"><?=Yii::$app->getTable->number_format($value->getTotalLeavesInMonth($m, $year))?></td>
                                    <?php
}
		?>
                                    <td class="text-center"><?=$value->getTotalOffDays($m, $year)?></td>

                                </tr>
                                <?php
$s_no++;
	}
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


 /* function exportInCsv() {

    $("#export-table").tableHTMLExport({type:'csv',filename:'leave_report_<?=date('His')?>.csv',separator: ',',escape:'true'

})
}*/
  $('#pdf').on('click',function(){
    $("#export-table").tableHTMLExport({type:'pdf',filename:'leave_report_<?=date('His')?>.pdf'});

  })

    $(document).ready(function () {
         $('#xls').on('click',function(){
        $("#export-table").table2excel({
            filename: "leave_report_<?=date('His')?>.xls"
        });
    });
     });


  </script>


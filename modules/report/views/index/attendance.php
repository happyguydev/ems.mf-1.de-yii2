 <?php
use app\models\User;
use yii\widgets\LinkPager;
use yii\widgets\Pjax;

$this->title = Yii::t('app', 'Attendance Report');
$this->params['breadcrumbs'][] = $this->title;
$user = User::find()->where(['status' => 1])->all();
$user_role = Yii::$app->user->identity->user_role;
?>
 <div class="grid grid-cols-12 gap-6 mt-5">


                    <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2" style="overflow: hidden;">
                        <?php if (Yii::$app->Utility->hasAccess('attendance_report', 'export')) {?>
 <div class="dropdown inline-block mt-8" data-placement="bottom-start">
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
                   <!--   <a href="javascript:void(0)" onclick="exportInCsv()" id="csv" class="flex items-center block p-2 transition duration-300 ease-in-out bg-white dark:bg-dark-1 hover:bg-gray-200 dark:hover:bg-dark-2 rounded-md">
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

                        <div class="dropdown hidden">
                            <button class="dropdown-toggle button px-2 box text-gray-700 dark:text-gray-300">
                                <span class="w-5 h-5 flex items-center justify-center"> <i class="w-4 h-4" data-feather="plus"></i> </span>
                            </button>
                        </div>
                        <!-- <div class="hidden md:block mx-auto text-gray-600">Showing 1 to 10 of 150 entries</div> -->
                        <div class="hidden md:block mx-auto text-gray-600"></div>
                        <?php
if ($user_role == 'admin') {
	$right = "margin-right:-97px";
} else {
	$right = "margin-right:46px";
}

?>
                          <div class="w-full sm:w-auto mt-3 sm:mt-0 sm:ml-auto md:ml-0" style="<?=$right?>">
                            <div class="w-156 relative text-gray-700 dark:text-gray-300">

                            <form id="myForm" method="get" action="<?=Yii::getAlias('@web')?>/report/index/attendance">

                              <?php
if (Yii::$app->user->identity->user_role == 'admin') {
	$col_name = "col-md-3";
	?>

                              <div class="<?=$col_name?>">
<label class="control-label" style="font-weight: bold"><?=Yii::t('app', 'Select Employee')?></label>
<select name="employee" class="input box mt-3 placeholder-theme-13 " style="width: 100%">
  <option value=""><?=Yii::t('app', 'Select Employee')?></option>
  <?php
if (count($user) > 0) {
		foreach ($user as $uk => $uv) {
			?>
  <option value="<?=$uv['id']?>" <?=($employee_id == $uv['id']) ? 'selected' : ''?>><?=$uv['first_name'] . ' ' . $uv['last_name']?></option>
  <?php
# code...
		}
	}
	?>
</select>
</div>
<?php
} else {
	$col_name = "col-md-4";
}
?>
<div class="<?=$col_name?>">
<label class="control-label" style="font-weight: bold"><?=Yii::t('app', 'Start Date')?></label>
<input type="text" id="start-date" class="input box pr-8 mt-3 placeholder-theme-13 ml-3" name="startdate" value="<?=$startdate?>" style="width: 100%">
</div>
<div class="<?=$col_name?>">
<label class="control-label" style="font-weight: bold"><?=Yii::t('app', 'End Date')?></label>
<input type="text" id="end-date" class="input box pr-8 mt-3 placeholder-theme-13 ml-3" name="enddate" value="<?=$enddate?>" style="width: 100%">
</div>
<?php
if (Yii::$app->user->identity->user_role == 'admin') {?>
 <div class="col-md-3">
    <div style="margin-top:34px">
 <input type="submit" class="btn btn-success btn-round" value="<?=Yii::t('app', 'Submit')?>"/>
    <a href="<?=Yii::getAlias('@web')?>/report/index/attendance" class="btn btn-danger btn-round ml-2"><?=Yii::t('app', 'Reset')?></a>
 </div>
</div>
<?php
} else {
	?>
  <div class="col-md-4">
    <div class="flex justify-center mt-10 ml-8" style="margin-top: 2.1rem;">
 <input type="submit" class="btn btn-success btn-round" value="<?=Yii::t('app', 'Submit')?>"/>
    <a href="<?=Yii::getAlias('@web')?>/report/index/attendance" class="btn btn-danger btn-round ml-2"><?=Yii::t('app', 'Reset')?></a>
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
                                    <th class="text-center whitespace-nowrap"><?=Yii::t('app', 'Employee Name')?></th>
                                    <th class="text-center whitespace-nowrap"><?=Yii::t('app', ' Check In DateTime')?></th>
                                    <th class="text-center whitespace-nowrap"><?=Yii::t('app', 'Check Out DateTime')?></th>
                                    <th class="text-center whitespace-nowrap"><?=Yii::t('app', 'Work Duration')?></th>
                                    <th class="text-center whitespace-nowrap"><?=Yii::t('app', 'Check In Ip')?></th>
                                    <th class="text-center whitespace-nowrap"><?=Yii::t('app', 'Check In Location')?></th>
                                    <th class="text-center whitespace-nowrap"><?=Yii::t('app', 'Check Out Ip')?></th>
                                    <th class="text-center whitespace-nowrap"><?=Yii::t('app', 'Check Out Location')?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php

if (count($model) > 0) {
	$st = Yii::$app->getTable;
	foreach ($model as $key => $value) {

		?>
                                <tr>
                                    <td class="text-center"><?=$s_no + 1?></td>
                                    <td class="text-center"><?=$value->employeeName['first_name'] . ' ' . $value->employeeName['last_name']?></td>
                                    <td class="text-center"><?=$st->datetime_format($value['check_in'])?></td>
                                    <td class="text-center"><?=$st->datetime_format($value['check_out'])?></td>
                                    <td class="text-center"><?=gmdate("H:i:s", (int) $value['work_duration'])?></td>
                                    <td class="text-center"><?=$value['check_in_ip']?></td>
                                    <td class="text-center"><?=$value['check_in_location']?></td>
                                    <td class="text-center"><?=$value['check_out_ip']?></td>
                                    <td class="text-center"><?=$value['check_out_location']?></td>
                                </tr>
                                <?php
$s_no++;
	}
} else {
	?>
  <tr>
    <td class="text-center text-theme-6" colspan="12"><?=Yii::t('app', 'No Data Found!')?></td>
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

  $('#pdf').on('click',function(){
    $("#export-table").tableHTMLExport({type:'pdf',filename:'attendance_report_<?=date('His')?>.pdf'});

  })

    $(document).ready(function () {
         $('#xls').on('click',function(){
        $("#export-table").table2excel({
            filename: "attendance_report_<?=date('His')?>.xls"
        });
    });
     });

    var mindate = '<?=$startdate?>';
var enddatepicker1;
var startDate = null;
  function endDatePicker() {
  enddatepicker1 =  new Litepicker({
  element: document.getElementById('end-date'),
  autoApply: true,
  lang: '<?=Yii::$app->language?>',
  format:'DD.MM.YYYY',
  minDate: mindate,
  startDate: startDate
})
}

 $(document).ready(function(){
     new Litepicker({
  element: document.getElementById('start-date'),
  autoApply: true,
  lang: '<?=Yii::$app->language?>',
  format:'DD.MM.YYYY',
 onSelect: function(date){
    mindate = Date.parse(date);
    enddatepicker1.destroy();

    var nn1 =  (document.getElementById('start-date').value);
    var nn2 = (document.getElementById('end-date').value);
    var minbig = compareDates(nn1, nn2);
    if(minbig){
        startDate = mindate;
    }else{
        startDate = null;
    }
 endDatePicker();
      //$('#project-end_date').attr('data-min-date','2021-03-21');
    }

 })
 endDatePicker();
    });

    function compareDates(d1, d2){
var parts =d1.split('.');
var d1 = Number(parts[2] + parts[1] + parts[0]);
parts = d2.split('.');
var d2 = Number(parts[2] + parts[1] + parts[0]);
return d2 <= d1;
}


  </script>
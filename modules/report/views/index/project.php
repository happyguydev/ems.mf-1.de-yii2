 <?php
use yii\widgets\LinkPager;
use yii\widgets\Pjax;

$this->title = Yii::t('app', 'Project Report');
$this->params['breadcrumbs'][] = $this->title;
$search = '';
?>
 <div class="grid grid-cols-12 gap-6 mt-5">


                    <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
                      <?php if (Yii::$app->Utility->hasAccess('project_report', 'export')) {?>
 <div class="dropdown inline-block mt-1" data-placement="bottom-start" style="margin-top:-15px;">
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
                          <div class="w-full sm:w-auto mt-3 sm:mt-0 sm:ml-auto md:ml-0">
                            <div class="w-156 relative text-gray-700 dark:text-gray-300">

                            <form id="myForm" method="get" action="<?=Yii::getAlias('@web')?>/report/index/project">
<div class="col-md-5">
 <div class="form-group">
<label class="control-label" style="font-weight: bold"><?=Yii::t('app', 'Start Date')?></label>
<input type="text" id="start-date" class="input w-46 box pr-8 placeholder-theme-13 ml-3" name="startdate" value="<?=$startdate?>">
</div>
</div>
<div class="col-md-5">
 <div class="form-group">
<label class="control-label" style="font-weight: bold"><?=Yii::t('app', 'End Date')?></label>
<input type="text" id="end-date" class="input w-46 box pr-8 placeholder-theme-13 ml-3" name="enddate" value="<?=$enddate?>">
</div>
 </div>
 <div class="col-md-2">
    <div class="flex justify-center">
 <input type="submit" class="btn btn-success btn-round" value="<?=Yii::t('app', 'Submit')?>"/>
    <a href="<?=Yii::getAlias('@web')?>/report/index/project" class="btn btn-danger btn-round ml-2"><?=Yii::t('app', 'Reset')?></a>
 </div>
</div>
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
                                    <th class="text-center whitespace-nowrap"><?=Yii::t('app', 'Title')?></th>
                                    <th class="text-center whitespace-nowrap"><?=Yii::t('app', 'Team')?></th>

                                    <th class="text-center whitespace-nowrap"><?=Yii::t('app', ' Start Date')?></th>
                                    <th class="text-center whitespace-nowrap"><?=Yii::t('app', 'End Date')?></th>
                                    <th class="text-center whitespace-nowrap"><?=Yii::t('app', 'Status')?></th>
                                    <th class="text-center whitespace-nowrap"><?=Yii::t('app', 'Added At')?></th>
                                    <th class="text-center whitespace-nowrap"><?=Yii::t('app', 'Added By')?></th>
                                    <th class="text-center whitespace-nowrap"><?=Yii::t('app', 'Updated At')?></th>
                                    <th class="text-center whitespace-nowrap"><?=Yii::t('app', 'Updated By')?></th>
                                    <th class="text-center whitespace-nowrap"><?=Yii::t('app', 'Deleted At')?></th>
                                    <th class="text-center whitespace-nowrap"><?=Yii::t('app', 'Deleted By')?></th>
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
                                    <td class="text-center" ><a href="javascript:void(0)" class="text-theme-9" onclick="viewProject('<?=$value['id']?>')"><?=$value['name']?></a></td>
                                    <td class="text-center"> <?php
if ($value->getTeamMembers()) {
			?>
                                        <div class="text-gray-600 text-xs whitespace-nowrap mt-0.5">
                                        <div class="flex mt-2">
                                           <?=$value->getTeamMembers()?>
                                        </div>
                                    </div>
                                    <?php
}
		?></td>

      <?php
$status_clr = $value->getStatusColor();
		?>
                                    <td class="text-center"><?=$st->date_format($value['start_date'])?></td>
                                    <td class="text-center"><?=$st->date_format($value['end_date'])?></td>
                                     <td class="text-center">
                                       <div class="flex items-center justify-center text-theme- text-center"><span class="px-2 py-1 rounded-full bg-<?=$status_clr?> text-white mr-1" style="font-size:11px;width: 72px"><?=$value->getStatus();?></span> </div>
                                     </td>
                                    <td class="text-center"><?=$st->datetime_format($value['created_at'])?></td>
                                    <td class="text-center"><?=$value->createdBy?></td>
                                   <td class="text-center"><?=$st->datetime_format($value['updated_at'])?></td>
                                    <td class="text-center"><?=$value->updatedBy?></td>
                                   <td class="text-center"><?=$st->datetime_format($value['deleted_at'])?></td>
                                    <td class="text-center"><?=$value->deletedBy?></td>
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


 /* function exportInCsv() {

    $("#export-table").tableHTMLExport({type:'csv',filename:'attendance_report_<?=date('His')?>.csv',separator: ',',escape:'true'

})
}*/
  $('#pdf').on('click',function(){
    $("#export-table").tableHTMLExport({type:'pdf',filename:'project_report_<?=date('His')?>.pdf'});

  })

    $(document).ready(function () {
         $('#xls').on('click',function(){
        $("#export-table").table2excel({
            filename: "project_report_<?=date('His')?>.xls"
        });
    });
     });

    function viewProject(id) {
      window.open('<?=Yii::getAlias('@web')?>/project/view?id='+id,'_blank');
    }

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
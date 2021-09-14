
<?php
use app\models\Appointment;
use app\models\AppointmentAssignee;
use app\models\Attendance;
use app\models\Customer;
use app\models\Leave;
use app\models\Project;
use app\models\Task;
use app\models\User;
use app\widgets\CalendarEvent;
use app\widgets\RecentActivites;

$user = Yii::$app->user->identity;
if ($user->user_role != 'admin') {
	$cond = '`created_by`=' . $user->id;
} else {
	$cond = '1';
}

$get_assign_projects = Yii::$app->getTable->get_logged_in_user_project();
$get_assign_tasks = Yii::$app->getTable->get_logged_in_user_task();
$customer_count = Customer::find()->where(['trash' => 0])->andWhere($cond)->count();
$project = Project::find()->where(['trash' => 0])->andWhere($cond)->orWhere(['IN', 'id', $get_assign_projects]);
$project_count = $project->count();
$task_count = Task::find()->where(['trash' => 0])->andWhere($cond)->orWhere(['IN', 'id', $get_assign_tasks])->count();
$leave_count = Leave::find()->where(['created_by' => $user->id])->count();

$this->title = Yii::t('app', 'Dashboard');
$this->params['breadcrumbs'][] = $this->title;

$ids = 0;
$model = AppointmentAssignee::find()->where(['user_id' => $user->id])->all();
if (count($model) > 0) {
	foreach ($model as $key => $value) {
		$ids .= ',' . $value['appointment_id'];
	}
}

$date = date('Y-m-d H:i:s');
$month = date('m', strtotime($date));
$cond1 = "month(start_date_time) =" . $month;
$events = Appointment::find()->where($cond)->andWhere($cond1)->orWhere(['IN', 'id', $ids])->all();
$highlighted_dates = '';
foreach ($events as $k => $v) {
	$hdate = date('Y-m-d', strtotime($v['start_date_time']));
	if ($hdate != date('Y-m-d')) {
		$highlighted_dates .= "'" . $hdate . "',";
	}
}
$attendance = Attendance::find()->where(['user_id' => $user->id])->andWhere(['check_out' => null])->orderBy(['check_in' => SORT_DESC])->one();
if ($attendance) {
	$disabled = '';
} else {
	$disabled = 'disabled';
}
?>
<!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/litepicker/dist/css/litepicker.css"/>
 --><script src="https://cdn.jsdelivr.net/npm/litepicker/dist/bundle.js"></script>

<script src="https://cdn.jsdelivr.net/npm/litepicker/dist/litepicker.js"></script>

 <div class="rounded-md flex items-center px-5 py-4 bg-theme-9 text-white mt-5 successStatus" style="display: none">

                </div>
   <div class="grid grid-cols-12 gap-6">


                    <div class="col-span-12 xl:col-span-8">
                        <!-- BEGIN: General Report -->
                        <div class="col-span-12 mt-8">
                            <div class="intro-y flex items-center h-10">
                                <h2 class="text-lg font-medium truncate mr-5">
                                    <?=Yii::t('app', 'General Report')?>
                                </h2>
                            </div>
                            <div class="grid grid-cols-12 gap-6 mt-5">

 <?php if (Yii::$app->Utility->hasAccess('customer', 'view')) {?>


                                <div class="col-span-12 sm:col-span-6 xl:col-span-4 intro-y">
                                    <a href="<?=Yii::getAlias('@web')?>/customer">
                                    <div class="report-box zoom-in">
                                        <div class="box p-5">
                                            <div class="flex">
                                                <i data-feather="user" class="report-box__icon text-theme-9"></i>
                                             <!--    <div class="ml-auto">
                                                    <div class="report-box__indicator bg-theme-9 tooltip cursor-pointer" title="33% Higher than last month"> 33% <i data-feather="chevron-up" class="w-4 h-4"></i> </div>
                                                </div> -->
                                            </div>
                                            <div class="text-3xl font-bold leading-8 mt-6"><?=$customer_count?></div>
                                            <div class="text-base text-gray-600 mt-1"><?=Yii::t('app', 'Total Customers')?></div>
                                        </div>
                                    </div>
                                </a>
                                </div>
                                <?php
}
?>

 <?php if (Yii::$app->Utility->hasAccess('project', 'view')) {?>

                                 <div class="col-span-12 sm:col-span-6 xl:col-span-4 intro-y">
                                    <a href="<?=Yii::getAlias('@web')?>/project">
                                    <div class="report-box zoom-in">
                                        <div class="box p-5">
                                            <div class="flex">
                                                <i data-feather="grid" class="report-box__icon text-theme-12"></i>
                                             <!--    <div class="ml-auto">
                                                    <div class="report-box__indicator bg-theme-9 tooltip cursor-pointer" title="33% Higher than last month"> 33% <i data-feather="chevron-up" class="w-4 h-4"></i> </div>
                                                </div> -->
                                            </div>
                                            <div class="text-3xl font-bold leading-8 mt-6"><?=$project_count?></div>
                                            <div class="text-base text-gray-600 mt-1"><?=Yii::t('app', 'Total Projects')?></div>
                                        </div>
                                    </div>
                                </a>
                                </div>

                                <?php
}
?>
 <?php if (Yii::$app->Utility->hasAccess('task', 'view')) {?>
                                 <div class="col-span-12 sm:col-span-6 xl:col-span-4 intro-y">
                                    <a href="<?=Yii::getAlias('@web')?>/task">
                                    <div class="report-box zoom-in">
                                        <div class="box p-5">
                                            <div class="flex">
                                                <i data-feather="check-square" class="report-box__icon text-theme-6"></i>
                                             <!--    <div class="ml-auto">
                                                    <div class="report-box__indicator bg-theme-9 tooltip cursor-pointer" title="33% Higher than last month"> 33% <i data-feather="chevron-up" class="w-4 h-4"></i> </div>
                                                </div> -->
                                            </div>
                                            <div class="text-3xl font-bold leading-8 mt-6"><?=$task_count?></div>
                                            <div class="text-base text-gray-600 mt-1"><?=Yii::t('app', 'Total Tasks')?></div>
                                        </div>
                                    </div>
                                </a>
                                </div>
                                <?php
}
?>

                             <?php if (Yii::$app->Utility->hasAccess('leave', 'view')) {?>

                                <div class="col-span-12 sm:col-span-6 xl:col-span-4 intro-y">
                                    <a href="<?=Yii::getAlias('@web')?>/leave">
                                    <div class="report-box zoom-in">
                                        <div class="box p-5">
                                            <div class="flex">
                                                <i data-feather="arrow-down-circle" class="report-box__icon text-theme-14"></i>
                                             <!--    <div class="ml-auto">
                                                    <div class="report-box__indicator bg-theme-9 tooltip cursor-pointer" title="33% Higher than last month"> 33% <i data-feather="chevron-up" class="w-4 h-4"></i> </div>
                                                </div> -->
                                            </div>
                                            <div class="text-3xl font-bold leading-8 mt-6"><?=$leave_count?></div>
                                            <div class="text-base text-gray-600 mt-1"><?=Yii::t('app', 'Total Leave')?></div>
                                        </div>
                                    </div>
                                </a>
                                </div>
<?php
}
?>

                            </div>
                        </div>
                        <!-- END: General Report -->
</div>

  <div class="col-span-12 xl:col-span-4 mt-6">

  	  <div class="col-span-12 md:col-span-6 xl:col-span-4 xxl:col-span-12 mt-8" style="margin-top:70px">
        <div class="intro-y box">
                            <div class="flex flex-col sm:flex-row items-center p-5 border-b border-gray-200 dark:border-dark-5">
                                <h2 class="font-medium text-base mr-auto">
                                   <?=Yii::t('app', 'Attendance')?>
                                </h2>

                            </div>


                                <div class="intro-y box p-2 mt-2 sm:mt-2 text-center" >

                                     <button class="btn bg-theme-9 text-white w-24 mb-2 mr-1" id="check-in" style="color: #fff!important" <?=$disabled == '' ? 'disabled' : ''?> onclick="attendance('check-in')"><?=Yii::t('app', 'Check In')?></button>
                                     <button class="btn bg-theme-6 text-white w-24 mb-2 mr-1" id="check-out" style="color: #fff!important" <?=$disabled == 'disabled' ? 'disabled' : ''?> onclick="attendance('check-out')"><?=Yii::t('app', 'Check Out')?></button>


                                </div>

                        </div>
                    </div>
    <!-- BEGIN: Recent Activities -->
                            <div class="col-span-12 md:col-span-6 xl:col-span-4 xxl:col-span-12 mt-3">
                                <div class="intro-x flex items-center h-10">
                                    <h2 class="text-lg font-medium truncate mr-5">
                                        Recent Activities
                                    </h2>
                                    <a href="<?=Yii::getAlias('@web')?>/site/recent-activities" class="ml-auto text-theme-1 dark:text-theme-10 truncate">See all</a>
                                </div>
                                <div class="report-timeline mt-5 relative">




<?=RecentActivites::widget([
	'model' => '',
	'model_id' => 0,
	'no_of_records' => 5,
	'dtype' => 'dashboard',
])?>


                                </div>
                            </div>
                            <!-- END: Recent Activities -->

                            <!-- BEGIN: Schedules -->
                            <div class="col-span-12 md:col-span-6 xl:col-span-4 xxl:col-span-12 xl:col-start-1 xl:row-start-2 xxl:col-start-auto xxl:row-start-auto mt-3">
                                <div class="intro-x flex items-center h-10">
                                    <h2 class="text-lg font-medium truncate mr-5">
                                        <?=Yii::t('app', 'Schedules')?>
                                    </h2>
                                    <a href="<?=Yii::getAlias('@web')?>/calendar" class="ml-auto text-theme-1 dark:text-theme-10 truncate flex items-center"> <i data-feather="plus" class="w-4 h-4 mr-1"></i> <?=Yii::t('app', 'Add New Schedules')?> </a>
                                </div>
                                <div class="mt-5">
                                    <div class="intro-x box">
                                        <div class="p-5">
                                            <?=CalendarEvent::widget()?>

                            </div>


                                        <div class="border-t border-gray-200 p-5">
                                            <?php
if (count($events) > 0) {
	foreach ($events as $key => $value) {

		?>
                                            <div class="flex items-center">
                                                <div class="w-2 h-2 bg-theme-11 rounded-full mr-3" style="background: <?=$value['bg_color']?>"></div>
                                                <span class="truncate"><?=Yii::t('app', $value['title'])?></span>
                                                <div class="h-px flex-1 border border-r border-dashed border-gray-300 mx-3 xl:hidden"></div>
                                                <span class="font-medium xl:ml-auto"><?=date('d S', strtotime($value['start_date_time']))?></span>
                                            </div>
                                            <?php
}

}
?>
                                          <!--   <div class="flex items-center mt-4">
                                                <div class="w-2 h-2 bg-theme-1 dark:bg-theme-10 rounded-full mr-3"></div>
                                                <span class="truncate">VueJs Frontend Development</span>
                                                <div class="h-px flex-1 border border-r border-dashed border-gray-300 mx-3 xl:hidden"></div>
                                                <span class="font-medium xl:ml-auto">10th</span>
                                            </div>
                                            <div class="flex items-center mt-4">
                                                <div class="w-2 h-2 bg-theme-12 rounded-full mr-3"></div>
                                                <span class="truncate">Laravel Rest API</span>
                                                <div class="h-px flex-1 border border-r border-dashed border-gray-300 mx-3 xl:hidden"></div>
                                                <span class="font-medium xl:ml-auto">31th</span>
                                            </div> -->
                                        </div>
                                                                </div>

                                          </div>
                                    </div>

                            <!-- END: Schedules -->
                            <input type="text" name="date" id="result-1" value="">

</div>
                            </div>
<script type="text/javascript">
    $(document).ready(function(){
     new Litepicker({
  element: document.getElementById('datepicker'),
  field: document.getElementById('datepicker'),
  autoApply: true,
  inlineMode: true,
  lang: '<?=Yii::$app->language?>',
  lockDays: [<?=$highlighted_dates?>],
  highlightedDays:  [<?=$highlighted_dates?>],
 onSelect: function(date){
    var date1 = Date.parse(date);

       // document.getElementById('result-1').value = date.getYear() +'/'+date.getMonth()+'/'+date.getDate();
    },
    setup: (picker) => {
  picker.on('change:month', (date, calendarIdx) => {
    alert(date);
  });
},
 })
    })
    function getValue() {
     alert($('datepicker').val());
    }

    function attendance(type) {
        var latitude = $('#latitude').val();
        var longitude = $('#longitude').val();

         $('.successStatus').hide();
          $('.successStatus').html('');

        var msg = `Are you sure you want to ${type}?`;
        if(confirm(msg)==true) {

        $.ajax({
            url: `<?=Yii::getAlias('@web')?>/site/attendance?type=${type}`,
            method: 'POST',
            data: {latitude, longitude},
            success:function(response) {

                if(response=='out') {
                    $('#check-in').prop('disabled',true);
                    $('#check-out').prop('disabled',false);
                    $('.successStatus').show();
                    $('.successStatus').html('You are checked in successfully!');

                    setTimeout(function(){
                         $('.successStatus').hide();
                      $('.successStatus').html('');
                    },3000);
                } else {
                    $('#check-out').prop('disabled',true);
                    $('#check-in').prop('disabled',false);
                    $('.successStatus').show();
                    $('.successStatus').html('You are checked out successfully!');
                    setTimeout(function(){
                        $('.successStatus').hide();
                      $('.successStatus').html('');
                    },3000);
                }

            }
        })
    }
    }

</script>

<style type="text/css">
    .litepicker .container__days .day-item.is-highlighted {
        background: none;
        color:#1c3faa;
    }
    .month-item-name {
        background: none!important;
        border: none;
        text-align: center;
        margin-left:15px;
               }
    .month-item-year {
        background: none;
        border: none;
        display: none;
    }
    .litepicker {
        box-shadow: none;
    }
    .button-previous-month,.button-next-month {
        display: none!important;
    }
    .btn:focus, .btn:active:focus, .btn.active:focus, .btn.focus, .btn:active.focus, .btn.active.focus {
    outline:0px solid!important;
}

</style>






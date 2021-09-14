
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
$cond1 = "(`created_by`= $user->id OR id IN($ids) ) AND month(start_date_time) =" . $month;
$events = Appointment::find()->where($cond1)->all();
$highlighted_dates = '';
foreach ($events as $k => $v) {
	$hdate = date('Y-m-d', strtotime($v['start_date_time']));
	if ($hdate != date('Y-m-d')) {
		$highlighted_dates .= "'" . $hdate . "',";
	}
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
<p><?=date('d.m.Y')?></p>
                            </div>


                           <div class="timer" id="attendance">
                             <?php
$attendance = Attendance::find()->where(['user_id' => $user->id])->andWhere(['check_out' => null])->orderBy(['check_in' => SORT_DESC])->one();
if ($attendance) {
	$disabled = '';
	$checkin_date = $attendance->check_in;
} else {
	$disabled = 'disabled';
	$checkin_date = date('Y-m-d H:i:s');
}
?>
                            <?php
if (!$attendance) {
	?>
                            <p>00:00:00</p>
                            <?php
}
?>
            <!-- <span class="hour">00</span>:<span class="minute">00</span>:<span class="second">00</span> -->
        </div>

                                <div class="intro-y box p-2 mt-2 sm:mt-2 text-center" >



                                     <button class="btn bg-theme-9 text-white w-24 mb-2 mr-1" id="check-in" style="color: #fff!important" <?=$disabled == '' ? 'disabled' : ''?> onclick="attendance('check-in')"><?=Yii::t('app', 'Check In')?></button>
                                     <button class="btn bg-theme-1 text-white w-24 mb-2 mr-1" id="pause-time" style="color: #fff!important"  <?=$disabled == 'disabled' ? 'disabled' : ''?> onclick="pauseTime()"><?=Yii::t('app', 'Pause')?></button>
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

</div>
                            </div>
<script type="text/javascript">
 <?php
if ($attendance) {
	?>
    var checkType = 'check-in';
    var checkDate = "<?=date('c', strtotime($attendance->check_in))?>";
    <?php
} else {
	?>

var checkType ="check-out";
var checkDate = "<?=date('c')?>";
<?php
}
?>
 var duration = getCookie('duration_staff');;
var timeStart;
var timeStart1;
var currentTime;
setTimeout(function () {
   initTimer();


}, 1000);

    function attendance(type) {
        var latitude = $('#latitude').val();
        var longitude = $('#longitude').val();

         $('.successStatus').hide();
          $('.successStatus').html('');

        var msg = `Are you sure you want to ${type}?`;
        if(confirm(msg)==true) {
            currentTime = new Date().getTime();

                eraseCookie('duration_value_text_staff');
                 eraseCookie('duration_staff');
                 eraseCookie('pause_staff');
                 duration = 0;

        $.ajax({
            url: `<?=Yii::getAlias('@web')?>/site/attendance?type=${type}`,
            method: 'POST',
            data: {latitude, longitude},
            success:function(response) {

                if(response=='out') {
                    $('#check-in').prop('disabled',true);
                    $('#check-out').prop('disabled',false);
                     $('#pause-time').prop('disabled',false);
                    $('.successStatus').show();

                    $('.successStatus').html('You are checked in successfully!');

                    setTimeout(function(){
                         $('.successStatus').hide();
                      $('.successStatus').html('');
                    },1000);


                    timer('check-in');
                    checkType = "check-in";
                    timeStart1 = setInterval(function() {
            timer(checkType);

        }, 1000);
                } else {
                    $('#check-out').prop('disabled',true);
                    $('#check-in').prop('disabled',false);
                     $('#pause-time').prop('disabled',true);
                     $('#pause-time').text('Pause');
                    $('.successStatus').show();
                    $('.successStatus').html('You are checked out successfully!');
                    setTimeout(function(){
                        $('.successStatus').hide();
                      $('.successStatus').html('');
                    },1000);
                    timer('check-out');
                    checkType = "check-out";

                }



            }
        })
    }
    }


function initTimer() {
    var act = getCookie('pause_staff');
    var val0 = getCookie('duration_value_text_staff');
    //val0 = (val0);
    console.log(val0);

    if (act == 'pause') {
        $('#pause-time').text('Resume');
        $("#attendance").html(val0);
        setTimeout(function(argument) {
            clearInterval(timeStart);
        }, 100);


    } else {
        $('#pause-time').text('Pause');
        timeStart = setInterval(function() {
            timer(checkType);

        }, 1000);
    }

      currentTime = new Date();
        currentTime.setSeconds(currentTime.getSeconds() - duration);
       currentTime = currentTime.getTime();

}
function pauseTime() {
     duration = getCookie('duration_staff');;

    $('#check-in').prop('disabled', true);


    var tt = $('#pause-time').text().toLowerCase();
    setCookie('pause_staff',tt, 30);

    if (tt == 'pause') {

        clearInterval(timeStart);
         clearInterval(timeStart1);
         var hms = $("#attendance").text();
        setCookie('duration_value_text_staff', hms , 30);

        // your input string
        var a = hms.split(':'); // split it at the colons

        // minutes are worth 60 seconds. Hours are worth 60 minutes.
        var seconds = (+a[0]) * 60 * 60 + (+a[1]) * 60 + (+a[2]);
        setCookie('duration_staff',seconds, 30);


    } else {
        timeStart = setInterval(function() {
            timer(checkType);

        }, 1000);
    }



    $('#pause-time').text(function(i, text) {
        return text === "Pause" ? "Resume" : "Pause";
    });

     currentTime = new Date();
        currentTime.setSeconds(currentTime.getSeconds() - duration);
       currentTime = currentTime.getTime();

}
    function isSameDay(a, b) {
        return a.toDateString() != b.toDateString();
    }


    function timer() {


        var now = new Date().getTime();
        var daychanged = isSameDay(new Date(checkDate), new Date());
        if (daychanged) {
             currentTime = new Date().getTime();
        }


        var timeleft = now - currentTime;

        var days = Math.floor(timeleft / (1000 * 60 * 60 * 24));
        var hours = Math.floor((timeleft % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        var minutes = Math.floor((timeleft % (1000 * 60 * 60)) / (1000 * 60));
        var seconds = Math.floor((timeleft % (1000 * 60)) / 1000);

        //          currentTime.setSeconds( currentTime.getSeconds() + inc );
        //   var hours = currentTime.getHours();
        //   var minutes = currentTime.getMinutes();
        //   var seconds = currentTime.getSeconds();

        // Add leading zeros
        hours = (hours < 10 ? "0" : "") + hours;
        minutes = (minutes < 10 ? "0" : "") + minutes;
        seconds = (seconds < 10 ? "0" : "") + seconds;

        // Compose the string for display

        if (checkType == 'check-in') {
            var currentTimeString = hours + ":" + minutes + ":" + seconds;
        } else {

            var currentTimeString = '00' + ":" + '00' + ":" + '00';

        }

        var hms = currentTimeString;
        setCookie('duration_value_text_staff', hms , 30);
        // your input string
        var a = hms.split(':'); // split it at the colons

        // minutes are worth 60 seconds. Hours are worth 60 minutes.
        var seconds = (+a[0]) * 60 * 60 + (+a[1]) * 60 + (+a[2]);
        setCookie('duration_staff',seconds, 30);


        //var currentTimeString = '00' + ":" + '00' + ":" + '00';

        $(".timer").html(currentTimeString);

    }


function setCookie(name,value,days= 30) {
    var expires = "";
    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days*24*60*60*1000));
        expires = "; expires=" + date.toUTCString();
    }
    document.cookie = name + "=" + (value || "")  + expires + "; path=/";
}
function getCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for(var i=0;i < ca.length;i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1,c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
    }
    return null;
}
function eraseCookie(name) {
    document.cookie = name +'=; Path=/; Expires=Thu, 01 Jan 1970 00:00:01 GMT;';
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
div.timer {
    font-size:36px;
    margin:20px 0px 0px 0px;
    text-align: center;
}

</style>






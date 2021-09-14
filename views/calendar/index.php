<?php
use app\models\CalendarGroup;
use app\models\CalenderGroupAssignee;
use app\models\User;
$dir = Yii::getAlias('@web') . '/themes/admin/dist/full-calendar/';
$this->title = Yii::t('app', 'Appointments');
$this->params['breadcrumbs'][] = $this->title;

if (Yii::$app->user->identity->user_role != 'admin') {
	$get_assignees_groups = CalenderGroupAssignee::find()->where(['user_id' => Yii::$app->user->identity->id])->all();
	$_ids = '0';
	if (count($get_assignees_groups) > 0) {
		foreach ($get_assignees_groups as $key => $value) {
			$_ids .= ',' . $value['group_id'];
		}
	}
	$cond = " id IN ($_ids) OR created_by = " . Yii::$app->user->identity->id;
} else {
	$cond = '1';
}
$group = CalendarGroup::find()->where($cond)->all();
$users = User::find()->where(['status' => 1])->andWhere(['!=', 'user_role', 'admin'])->all();

$groupId = isset($_GET['groupId']) ? $_GET['groupId'] : 0;

?>
<script type="text/javascript">
var url = "<?=$dir?>";
var appointment_url = '<?=Yii::getAlias('@web')?>/calendar/';
</script>

    <link href='<?=$dir;?>packages/core/main.css' rel='stylesheet' />
    <link href='<?=$dir;?>packages/daygrid/main.css' rel='stylesheet' />
    <link href='<?=$dir;?>packages/timegrid/main.css' rel='stylesheet' />
    <link href='<?=$dir;?>packages/list/main.css' rel='stylesheet' />
    <link href="<?=$dir;?>packages/jqueryui/custom-theme/jquery-ui-1.10.4.custom.min.css" rel="stylesheet">
    <link href='<?=$dir;?>packages/datepicker/datepicker.css' rel='stylesheet' />
    <link href='<?=$dir;?>packages/colorpicker/bootstrap-colorpicker.min.css' rel='stylesheet' />
    <link href='<?=$dir;?>style.css' rel='stylesheet' />

    <script src='<?=$dir;?>packages/core/main.js'></script>
    <script src='<?=$dir;?>packages/daygrid/main.js'></script>
    <script src='<?=$dir;?>packages/timegrid/main.js'></script>
    <script src='<?=$dir;?>packages/list/main.js'></script>
    <script src='<?=$dir;?>packages/interaction/main.js'></script>
    <script src='<?=$dir;?>packages/jqueryui/jqueryui.min.js'></script>
    <script src='<?=$dir;?>packages/datepicker/datepicker.js'></script>
    <script src='<?=$dir;?>packages/colorpicker/bootstrap-colorpicker.min.js'></script>
    <script src='<?=$dir;?>calendar.js'></script>
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-material-datetimepicker/2.7.1/css/bootstrap-material-datetimepicker.min.css">
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <script type="text/javascript" src="https://momentjs.com/downloads/moment-with-locales.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-material-datetimepicker/2.7.1/js/bootstrap-material-datetimepicker.min.js"></script>
  <!--  <link href="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/css/select2.min.css" rel="stylesheet" />
<script src="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/js/select2.min.js"></script> -->


 <div class="modal" id="addeventmodal"  data-backdrop="static" data-keyboard="false">
         <div class="modal-content" id="createventmodal" data-backdrop="static" data-keyboard="false" style="position: absolute;right: 24%;width:600px;top:20%">
             <!-- BEGIN: Modal Header -->
             <div class="modal-header">
                 <h2 class="font-medium text-base mr-auto"><?=Yii::t('app', 'Add Appointment')?></h2>

             </div> <!-- END: Modal Header -->
             <!-- BEGIN: Modal Body -->
              <form id="createEvent" class="form-horizontal">
                <input type="hidden" name="_csrf" value="<?=uniqid()?>">
             <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">

                 <div class="col-span-12 sm:col-span-6">
                    <label for="group" class="form-label"><?=Yii::t('app', 'Group')?></label>
                    <select name="group" class="form-control">
                        <option value="0">Select Group</option>
                        <?php

if (count($group) > 0) {
	foreach ($group as $gdk => $gdv) {

		?>
                        <option value="<?=$gdv['id']?>"><?=$gdv['title']?></option>
                        <?php
}
}
?>
                    </select>
                </div>

                 <div class="col-span-12 sm:col-span-6">
                    <label for="title" class="form-label"><?=Yii::t('app', 'Title')?></label>
                    <input type="text" class="form-control" id="title" name="title" placeholder="<?=Yii::t('app', 'Title')?>" automcomplete="off" required>
                </div>

                  <div class="col-span-12 sm:col-span-6">
                    <label for="color" class="form-label">
                        <?=Yii::t('app', 'Color')?>

                        </label>
                        <input type="color" class="form-control" id="color" placeholder="<?=Yii::t('app', 'Color')?>" required name="bg_color">
                    </div>
                 <div class="col-span-12 sm:col-span-6">
                    <label for="text-color" class="form-label">
                        <?=Yii::t('app', 'Text Color')?>

                    </label>
                    <input type="color" class="form-control" id="text-color" placeholder="<?=Yii::t('app', 'text-color')?>" required name="text_color">
                </div>
                 <div class="col-span-12 sm:col-span-6">
                    <label for="start-date" class="form-label"><?=Yii::t('app', 'Start Date')?></label>
                    <input type="text" class="form-control datetimepicker1 start-date-picker" id="start-date" placeholder="<?=Yii::t('app', 'Start Date')?>" required name="start_date" autocomplete="off">
                </div>
                 <div class="col-span-12 sm:col-span-6">
                    <label for="end-date" class="form-label"><?=Yii::t('app', 'End Date')?></label>
                    <input type="text" class="form-control datetimepicker1 end-date-picker" id="end-date" placeholder="<?=Yii::t('app', 'End Date')?>" required name="end_date" automcomplete="off">
                </div>

                 <div class="col-span-12 sm:col-span-12">
                    <label class="form-label"><?=Yii::t('app', 'Assign To')?></label>
                    <select data-placeholder="<?=Yii::t('app', 'Select Assign To')?>"  class="tail-select w-full" name="assignee[]" data-search="true" multiple>

                        <?php

if (count($users) > 0) {
	foreach ($users as $usk => $usv) {

		?>
                        <option value="<?=$usv['id']?>"><?=$usv['first_name'] . ' ' . $usv['last_name']?></option>
                        <?php
}
}
?>
                    </select>
                </div>

             </div> <!-- END: Modal Body -->
              <div class="modal-footer text-right"> <button type="button" data-dismiss="modal" class="closemodal button w-20 border text-gray-700 dark:border-dark-5 dark:text-gray-300 mr-1"><?=Yii::t('app', 'Cancel')?></button>
              <button type="submit" class="btn btn-primary w-46"><?=Yii::t('app', 'Save')?></button> </div> <!-- END: Modal Footer -->
         </form>
             <!-- BEGIN: Modal Footer -->

         </div>
 </div> <!-- END: Modal Content -->
<?php if (Yii::$app->Utility->hasAccess('appointment', 'update')) {
	?>

 <div class="modal" id="editeventmodal" data-backdrop="static" data-keyboard="false" style="z-index: 50000 !important;">
        <div class="modal-content" data-backdrop="static" data-keyboard="false" style="position: absolute;right: 24%;width:600px;top:20%">

            <div class="modal-header">
               <h2 class="font-medium text-base mr-auto"><?=Yii::t('app', 'Update Appointment')?></h2>
            </div>

                    <form id="editEvent" class="form-horizontal">
                        <input type="hidden" name="_csrf" value="<?=uniqid()?>">
                         <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">
                    <input type="hidden" id="editEventId" name="editEventId" value="">

                     <div class="col-span-12 sm:col-span-6">
                    <label for="editGroup" class="control-label"><?=Yii::t('app', 'Group')?></label>
                    <select name="group" id="editGroup" class="form-control" required>
                        <?php

	if (count($group) > 0) {
		foreach ($group as $gudk => $gudv) {

			?>
                        <option value="<?=$gudv['id']?>"><?=$gudv['title']?></option>
                        <?php
}
	}
	?>
                    </select>
                </div>


                        <div class="col-span-12 sm:col-span-6" id="edit-title-group">
                                <label class="control-label" for="editEventTitle"><?=Yii::t('app', 'Title')?></label>
                                <input type="text" class="form-control mt-2" id="editEventTitle" name="title" required>
                                <!-- errors will go here -->
                        </div>

                        <div class="col-span-12 sm:col-span-6" id="edit-color-group">

                                <label class="control-label" for="editColor"><?=Yii::t('app', 'Color')?></label>
                                <input type="color" class="form-control mt-2" id="editColor" name="bg_color" value="" required>
                                <!-- errors will go here -->
                        </div>
                        <div class="col-span-12 sm:col-span-6" id="edit-textcolor-group" >

                                <label class="control-label" for="editTextColor"><?=Yii::t('app', 'Text Color')?></label>
                                <input type="color" class="form-control mt-2" id="editTextColor" name="text_color" value="" required>
                                <!-- errors will go here -->

                        </div>
                        <div class="col-span-12 sm:col-span-6" id="edit-startdate-group">

                                <label class="control-label" for="editStartDate"><?=Yii::t('app', 'Start Date')?></label>
                                <input type="text" class="form-control mt-2 datetimepicker1 start-date-picker" id="editStartDate" name="start_date" required>
                                <!-- errors will go here -->
                        </div>

                        <div class="col-span-12 sm:col-span-6" id="edit-enddate-group">

                                <label class="control-label" for="editEndDate"><?=Yii::t('app', 'End Date')?></label>
                                <input type="text" class="form-control mt-2 end_datetimepicker1 end-date-picker" id="editEndDate" name="end_date" required>
                                <!-- errors will go here -->

                        </div>

                         <div class="col-span-12 sm:col-span-12" id="edit-assignee">
                    <label for="editAssignee" class="control-label"><?=Yii::t('app', 'Assign To')?></label>
                    <select data-placeholder="<?=Yii::t('app', 'Select Assign To')?>" data-search="true" class="tail-select edit-assignee-select w-full" id="editAssignee" name="assignee[]" multiple>

                        <?php

	if (count($users) > 0) {
		foreach ($users as $uskd => $usvd) {

			?>
                        <option value="<?=$usvd['id']?>"><?=$usvd['first_name'] . ' ' . $usvd['last_name']?></option>
                        <?php
}
	}
	?>
                    </select>
                </div>

                    </div>

                          <div class="modal-footer text-right">
                            <button type="button" data-dismiss="modal" class="closemodal button w-20 border text-gray-700 dark:border-dark-5 dark:text-gray-300 mr-1"><?=Yii::t('app', 'Close')?>

                            </button>
              <button type="submit" class="btn btn-primary w-46"><?=Yii::t('app', 'Save')?></button>
              <?php if (Yii::$app->Utility->hasAccess('appointment', 'delete')) {?>
               <button type="button" class="btn btn-danger" id="deleteEvent" data-id><?=Yii::t('app', 'Delete')?></button>
           <?php }?>
           </div><!-- END: Modal Footer -->
            </form>

        </div><!-- /.modal-content -->
</div><!-- /.modal -->
<?php
}
?>

<div class="modal" id="create-group-modal" data-backdrop="static" data-keyboard="false">
     <div class="modal-content" id="create-group-content" style="position: absolute;right: 24%;width:600px;top:20%"></div>
 </div>


<div class="modal" id="update-group-modal" data-backdrop="static" data-keyboard="false">
     <div class="modal-content" id="update-group-content" style="position: absolute;right: 24%;width:600px;top:20%"></div>
 </div>
<div class="grid grid-cols-12 gap-6 mt-5">
      <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2 ml-2">
    <?php if (Yii::$app->Utility->hasAccess('appointment', 'create')) {?>

     <a href="javascript:;" data-toggle="modal" data-target="#addeventmodal" class="button text-white bg-theme-1 shadow-md mr-2 mb-5"><?=Yii::t('app', 'Add Appointment')?></a> <!-- END: Modal Toggle -->
     <?php
}
?>
<!-- <a href="javascript:void(0)" onclick="createGroup()" data-toggle="modal" data-target="#create-group-modal" class="button text-white bg-theme-6 shadow-md mr-2 mb-5">Add Group</a>
 --></div>
</div>
<div class="grid grid-cols-12 gap-5 mt-5">
<div class="col-span-12 xl:col-span-4 xxl:col-span-3">
                        <div class="box p-5 intro-y">
<!--                             <button type="button" class="btn btn-primary w-full mt-2"><?=Yii::t('app', 'Add New Group')?> </button>
 -->                            <a href="javascript:void(0)" onclick="createGroup()" data-toggle="modal" data-target="#create-group-modal" class="btn btn-danger w-full"><?=Yii::t('app', 'Add New Group')?></a>
                            <div class="border-t border-b border-gray-200 dark:border-dark-5 mt-6 mb-5 py-3" id="calendar-events">
                                <?php
if (count($group) > 0) {
	foreach ($group as $gk => $gv) {
		$border_b = $gk + 1 < count($group) ? 'border-b' : '';

		$selected_bg = $groupId == $gv['id'] ? 'style="background:#1c3faa;color:#fff!important"' : 'style="background:' . $gv['bg_color'] . ';color:' . $gv['text_color'] . '"';

		$selected_text_clr = $groupId == $gv['id'] ? '#fff' : $gv['text_color'];

		?>
                                <div class="relative <?=$border_b?> mb-3 mt-3" >
                                    <div class="event  group_detail  p-3 -mx-3 cursor-pointer  rounded-md flex items-center" data-id="<?=$gv['id']?>" <?=$selected_bg?>>
                                       <!--  <div class="w-2 h-2 bg-theme-11 rounded-full mr-3"></div> -->
                                        <div class="pr-10 ">
                                            <div class="event__title truncate" style="font-weight: bold;color:<?=$selected_text_clr?>"><?=$gv['title']?></div>
                                          <!--   <div class="text-gray-600 text-xs mt-0.5"> <span class="event__days">2</span> Days <span class="mx-1">•</span> 10:00 AM </div> -->
                                        </div>
                                    </div>
                                    <?php
if (Yii::$app->user->identity->user_role == 'admin') {

			?>
                                  <a href="javascript:;" data-toggle="modal" data-target="#update-group-modal" class="flex items-center absolute top-0 bottom-0 my-auto right-0" onclick="updateGroup('<?=$gv['id']?>')" <?=$selected_bg?>> <i class="w-4 h-4" data-feather="edit"></i> </a>

                                    <a href="javascript:;" class="flex items-center absolute top-0 bottom-0 my-auto right-30" onclick="deleteGroup('<?=$gv['id']?>')" <?=$selected_bg?>> <i class="w-4 h-4" data-feather="trash"></i> </a>
                                  <?php
}
		?>
                                </div>
                                <?php
}
} else {
	?>

                                           <?=Yii::t('app', 'No Group Added Yet!')?>
                                          <!--   <div class="text-gray-600 text-xs mt-0.5"> <span class="event__days">2</span> Days <span class="mx-1">•</span> 10:00 AM </div> -->
    <?php
}
?>

                                <div class="text-gray-600 p-3 text-center hidden" id="calendar-no-events">No events yet</div>
                            </div>

                        </div>

                    </div>
                    <div class="col-span-12 xl:col-span-8 xxl:col-span-9">

<div class="box p-5">
    <input type="hidden" name="selected_group" value="<?=$groupId?>" id="selectedGroup">
<div class="full-calendar fc fc-media-screen fc-direction-ltr fc-theme-standard" id="calendar0"></div>
</div>
</div>
</div>
<script src="https://cdn.jsdelivr.net/npm/tail.select@0.5.2/js/tail.select.min.js"></script>
<script type="text/javascript">
      function createGroup() {
    $('#create-group-modal').show();
    $('#create-group-content').load(`<?=Yii::getAlias('@web')?>/calendar-group/create`);
  }
  function deleteGroup(id){
      var c = confirm('Are you sure you want to delete this item?')
      if(c){
          window.location = "<?=Yii::getAlias('@web')?>/calendar-group/delete?id="+id;
      }
      return false;
  }
   function updateGroup(id) {

    $('#update-group-modal').show();
    $('#update-group-content').load(`<?=Yii::getAlias('@web')?>/calendar-group/update?id=`+id);
  }
    function removeSelectedValue(id) {
       $(`#editAssignee option[value='${id}']`).removeAttr('selected');
       $(`.edit-assignee-select .dropdown-option[data-key='${id}']`).removeClass('selected');
       $(`.edit-assignee-select .select-handle[data-key='${id}']`).remove();
    }

      /*   $('.end-date-picker').bootstrapMaterialDatePicker({
       format : 'YYYY-MM-DD HH:mm:ss',
       lang : 'en_US',
       weekStart: 0,
      minDate : new Date()
});*/


$('#editEndDate').bootstrapMaterialDatePicker({ weekStart : 0, format : 'DD.MM.YYYY HH:mm:ss', lang : '<?=Yii::$app->language?>' });
$('#editStartDate').bootstrapMaterialDatePicker({ weekStart : 0, format : 'DD.MM.YYYY HH:mm:ss', lang : '<?=Yii::$app->language?>' }).on('change', function(e, date)
{
$('#editEndDate').bootstrapMaterialDatePicker('setMinDate', date);
});

$('#end-date').bootstrapMaterialDatePicker({ weekStart : 0, format : 'DD.MM.YYYY HH:mm:ss', lang : '<?=Yii::$app->language?>', minDate : new Date() });
$('#start-date').bootstrapMaterialDatePicker({ weekStart : 0, format : 'DD.MM.YYYY HH:mm:ss', lang : '<?=Yii::$app->language?>'}).on('change', function(e, date)
{
$('#end-date').bootstrapMaterialDatePicker('setMinDate', date);
});



</script>

<style type="text/css">
    .fc-content {
        font-size:12px;
        font-weight: bold;
        padding:3px;
        text-align: center;
    }
    #ui-datepicker-div {
        z-index: 50002!important;
    }

    @media (max-width: 1023px) {
    .full-calendar .fc-toolbar {
        flex-direction: column;
    }
}
#calendar{
    margin:auto;
}
.full-calendar .fc-toolbar .fc-toolbar-chunk:first-child {
    order: 3;
}
.full-calendar .fc-toolbar .fc-toolbar-chunk:nth-child(2) {
    order: 1;
}
.full-calendar .fc-toolbar .fc-toolbar-chunk:nth-child(3) {
    order: 2;
}
@media (max-width: 1023px) {
    .full-calendar .fc-toolbar .fc-toolbar-chunk:nth-child(3) {
        margin-top: 0.75rem;
        margin-bottom: 0.75rem;
    }
}
.full-calendar .fc-toolbar .fc-toolbar-chunk:nth-child(3) .fc-button-group button {
    width: 5rem;
}
.full-calendar .fc-toolbar .fc-left h2{
    font-weight: 500;
    font-size: 1.125rem;
    line-height: 1.75rem;
}
.full-calendar .fc-toolbar-chunk .fc-button-primary:first-child {
    border-top-left-radius: 0.375rem;
    border-bottom-left-radius: 0.375rem;
}
.full-calendar .fc-toolbar-chunk .fc-button-primary:last-child {
    border-top-right-radius: 0.375rem;
    border-bottom-right-radius: 0.375rem;
}
.full-calendar.fc-theme-standard .fc-list,
.full-calendar.fc-theme-standard .fc-scrollgrid,
.full-calendar.fc-theme-standard td,
.full-calendar.fc-theme-standard th {
    --tw-border-opacity: 1;
    border-color: rgba(237, 242, 247, var(--tw-border-opacity));
}
.dark .full-calendar.fc-theme-standard .fc-list,
.dark .full-calendar.fc-theme-standard .fc-scrollgrid,
.dark .full-calendar.fc-theme-standard td,
.dark .full-calendar.fc-theme-standard th {
    --tw-border-opacity: 1;
    border-color: rgba(63, 72, 101, var(--tw-border-opacity));
}
.full-calendar table {
    border-radius: 0.375rem;
}
.full-calendar table tr th {
    padding: 0.75rem 1.25rem;
}
.full-calendar .fc-daygrid-event-harness {
    margin-left: 1.25rem;
    margin-right: 1.25rem;
}
.full-calendar .fc-h-event {
    --tw-bg-opacity: 1;
    background-color: rgba(28, 63, 170, var(--tw-bg-opacity));
    --tw-border-opacity: 1;
    border-color: rgba(28, 63, 170, var(--tw-border-opacity));
    border-radius: 0.375rem;
}
.full-calendar .fc-event-title-container {
    font-size: 0.75rem;
    line-height: 1rem;
    padding: 0.25rem 0.5rem;
}
.full-calendar .fc-daygrid-event {
    font-size: 0.75rem;
    line-height: 1rem;
}
.full-calendar .fc-daygrid-event-dot {
    --tw-border-opacity: 1;
    border-color: rgba(28, 63, 170, var(--tw-border-opacity));
    margin-right: 0.5rem;
}
.full-calendar .fc-col-header-cell-cushion,
.full-calendar .fc-daygrid-dot-event .fc-event-title {
    font-weight: 500;
}
.full-calendar .fc-daygrid-more-link {
    font-size: 0.875rem;
    line-height: 1.25rem;
}
.full-calendar .fc-daygrid-day-bottom {
    padding-top: 0.25rem;
}
.full-calendar .fc-day-other {
    --tw-bg-opacity: 1;
    background-color: rgba(247, 250, 252, var(--tw-bg-opacity));
}
.dark .full-calendar .fc-day-other {
    --tw-bg-opacity: 1;
    background-color: rgba(41, 49, 69, var(--tw-bg-opacity));
}
.full-calendar .fc-button-primary {
    --tw-bg-opacity: 1;
    background-color: transparent !important;
    --tw-border-opacity: 1;
    border-color: rgba(237, 242, 247, var(--tw-border-opacity)) !important;
    --tw-text-opacity: 1;
    color: rgba(113, 128, 150, var(--tw-text-opacity)) !important;
    text-transform: capitalize !important;
    border-radius: 0.375rem;
    border-width: 1px;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-weight: 500;
    padding: 0.5rem 0.75rem;
    --tw-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
    box-shadow: var(--tw-ring-offset-shadow, 0 0 transparent), var(--tw-ring-shadow, 0 0 transparent), var(--tw-shadow);
    transition-property: background-color, border-color, color, fill, stroke, opacity, box-shadow, transform;
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
    transition-duration: 0.15s;
    transition-duration: 0.2s;
}
.full-calendar .fc-button-primary:hover {
    --tw-bg-opacity: 0.9;
    --tw-border-opacity: 0.9;
}
.full-calendar .fc-button-primary:focus {
    outline: 2px solid transparent;
    outline-offset: 2px;
    --tw-ring-offset-shadow: var(--tw-ring-inset) 0 0 0 var(--tw-ring-offset-width) var(--tw-ring-offset-color);
    --tw-ring-shadow: var(--tw-ring-inset) 0 0 0 calc(3px + var(--tw-ring-offset-width)) var(--tw-ring-color);
    box-shadow: var(--tw-ring-offset-shadow), var(--tw-ring-shadow), var(--tw-shadow, 0 0 transparent);
}
.full-calendar .fc-button-primary:not(button) {
    text-align: center;
}
.full-calendar .fc-button-primary .fc-icon {
    font-size: 1.2em;
}
.full-calendar .fc-button-primary:focus {
    box-shadow: none !important;
}
.full-calendar .fc-button-primary:not(:disabled).fc-button-active,
.full-calendar .fc-button-primary:not(:disabled):active {
    color: #fff !important;
    border-color: rgba(28, 63, 170, var(--tw-bg-opacity)) !important;
    background-color: rgba(28, 63, 170, var(--tw-bg-opacity)) !important;
}
.full-calendar .fc-list-day-cushion {
    --tw-bg-opacity: 1;
    background-color: rgba(237, 242, 247, var(--tw-bg-opacity)) !important;
    padding: 0.75rem 1.25rem !important;
}
.full-calendar .fc-list-event td {
    padding: 0.75rem 1.25rem;
}
.full-calendar .fc-list-event-dot {
    --tw-border-opacity: 1;
    border-color: rgba(28, 63, 170, var(--tw-border-opacity)) !important;
}
.full-calendar .fc-v-event {
    --tw-bg-opacity: 1;
    background-color: rgba(28, 63, 170, var(--tw-bg-opacity));
}
.dark .full-calendar .fc-v-event {
    --tw-bg-opacity: 1;
    background-color: rgba(41, 49, 69, var(--tw-bg-opacity));
}
.full-calendar .fc-v-event {
    --tw-border-opacity: 1;
    border-color: rgba(28, 63, 170, var(--tw-border-opacity));
}
.dark .full-calendar .fc-v-event {
    --tw-border-opacity: 1;
    border-color: rgba(41, 49, 69, var(--tw-border-opacity));
    --tw-shadow: 0 0 transparent;
    box-shadow: var(--tw-ring-offset-shadow, 0 0 transparent), var(--tw-ring-shadow, 0 0 transparent), var(--tw-shadow);
}
.full-calendar .fc-event-time {
    font-size: 0.75rem !important;
    padding-left: 0.125rem;
    padding-right: 0.125rem;
}
.full-calendar .fc-daygrid-more-link {
    padding-left: 0.5rem;
}
.dark .full-calendar .fc-button-primary {
    border-color: rgba(41, 49, 69, var(--tw-bg-opacity)) !important;
    background-color: rgba(41, 49, 69, var(--tw-bg-opacity)) !important;
    color: rgba(203, 213, 224, var(--tw-text-opacity)) !important;
}
.dark .full-calendar .fc-button-primary:not(:disabled).fc-button-active,
.dark .full-calendar .fc-button-primary:not(:disabled):active {
    border-color: rgba(28, 63, 170, var(--tw-bg-opacity)) !important;
    background-color: rgba(28, 63, 170, var(--tw-bg-opacity)) !important;
}
.dark .full-calendar .fc-day-today {
    --tw-bg-opacity: 1;
    background-color: rgba(41, 49, 69, var(--tw-bg-opacity));
}
.dark .full-calendar .fc-list-day-cushion {
    --tw-bg-opacity: 1;
    background-color: rgba(41, 49, 69, var(--tw-bg-opacity)) !important;
}
.dark .full-calendar .fc-event:hover td {
    --tw-bg-opacity: 1;
    background-color: rgba(49, 58, 85, var(--tw-bg-opacity));
    --tw-border-opacity: 1;
    border-color: rgba(49, 58, 85, var(--tw-border-opacity));
}
.dtp {
  z-index: 200000!important;
}
.right-30{
    right:30px;
}
</style>

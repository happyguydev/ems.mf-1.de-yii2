<?php
$featured_list = Yii::$app->Utility->getFeatureLists();
$createdPermission = json_encode($Permissions);
$report_array = ['leave_report', 'customer_report', 'attendance_report', 'project_report', 'task_report'];
?>

 <div class="form-check mt-4 mb-4 text-right" style="margin-right: 67px">
          <input class="form-check-input" type="checkbox" id="checkAll" name="check-all">
          <label class="form-check-label"><?=Yii::t('app', 'Check/Uncheck All')?></label>
        </div>
 <?php
foreach ($featured_list as $key => $permission_for) {
	?>
 <div class="flex flex-col sm:flex-row mt-2" style="border-bottom: 1px solid #eee">
                                        <div class="mr-auto">
                                            <a href="" class="font-medium"><?=$permission_for?></a>
                                        </div>
                                        <div  style="width:200px">

                                          <?php

	if (in_array($key, $report_array)) {
		?>

     <div class="form-check">
                                         <input class="form-check-input" type="checkbox" id="checkbox_view_<?=$key?>" value="view_<?=$key?>" name="permissions[]">
                                         <label class="form-check-label ml-5" for="checkbox_view_<?=$key?>"> <?=Yii::t('app', 'View')?></label>
                                       </div>
                                        <div class="form-check mt-1">
                                          <input class="form-check-input" type="checkbox" id="checkbox_export_<?=$key?>" value="export_<?=$key?>" name="permissions[]">
                                          <label class="form-check-label ml-5" for="checkbox_export_<?=$key?>"> <?=Yii::t('app', 'Export')?></label>
                                        </div>

                                            <?php
}
	?>
<?php

	if (!in_array($key, $report_array)) {
		?>


                                        <div class="form-check">
                                         <input class="form-check-input" type="checkbox" id="checkbox_create_<?=$key?>" value="create_<?=$key?>" name="permissions[]">
                                         <label class="form-check-label ml-5" for="checkbox_create_<?=$key?>"> <?=Yii::t('app', 'Create')?></label>
                                       </div>
                                        <div class="form-check mt-1">
                                          <input class="form-check-input" type="checkbox" id="checkbox_update_<?=$key?>" value="update_<?=$key?>" name="permissions[]">
                                          <label class="form-check-label ml-5" for="checkbox_update_<?=$key?>"> <?=Yii::t('app', 'Update')?></label>
                                        </div>
                                       <div class="form-check mt-1">
                                        <input class="form-check-input" type="checkbox" id="checkbox_view_<?=$key?>" value="view_<?=$key?>" name="permissions[]">
                                        <label class="form-check-label ml-5" for="checkbox_view_<?=$key?>"> <?=Yii::t('app', 'View')?></label>
                                      </div>
                                         <div class="form-check mt-1 mb-2">
                                          <input class="form-check-input" type="checkbox" id="checkbox_delete_<?=$key?>" value="delete_<?=$key?>" name="permissions[]">
                                          <label class="form-check-label ml-5" for="checkbox_delete_<?=$key?>"> <?=Yii::t('app', 'Delete')?></label>
                                        </div>
                                        <?php
}
	?>
                                        </div>
                                    </div>
                                    <?php
}
?>

<style type="text/css">

.permission_thead th {
text-align: center;
}
.permission_tbody td
{
text-align: center;
}
</style>

<script type="text/javascript">
var arr = JSON.parse(JSON.stringify(<?=$createdPermission?>));

$.each($(arr),function(key,value){
  $("input[type=checkbox][value="+value+"]").prop("checked",true);

});

 $("#checkAll").click(function () {
     $('input:checkbox').not(this).prop('checked', this.checked);
 });
</script>
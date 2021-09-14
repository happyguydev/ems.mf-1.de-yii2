<?php
use yii\helpers\Html;
/* @var $this yii\web\View */
/* @var $model app\models\Task */
$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tasks'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$update_url = Yii::getAlias('@web') . '/task/update?id=' . $model->id;
?>
<script type="text/javascript" src="<?=Yii::getAlias('@web');?>/themes/common/jquery-form/jquery.form.min.js"></script>
<!--popup for creating folder-->
<div class="modal" id="update_comment" style="z-index: 50000;margin:0">
  <div class="modal__content" style="position: absolute;right: 33%">
    <div class="flex items-center px-5 py-5 sm:py-3 border-b border-gray-200 dark:border-dark-5">
      <h2 class="font-medium text-base mr-auto"><?=Yii::t('app', 'Edit Comment')?></h2>
      <button data-dismiss="modal" class="button border bg-theme-24 text-white">
      <i data-feather="x" class="w-5 h-5 text-gray-500"></i></button>
    </div>
    <div id="update_comment_content" class="p-5"></div>

  </div>
</div>
<div class="grid grid-cols-12 gap-5 mt-5">
  <div class="intro-y col-span-12 lg:col-span-8">
    <!-- BEGIN: Basic Table -->
    <div class="intro-y box">
      <div class="flex flex-col sm:flex-row items-center p-3 border-b border-gray-200">
        <h2 class="font-medium text-base mr-auto">
        <?=Html::encode($this->title)?>
        </h2>
        <?php if (Yii::$app->Utility->hasAccess('task', 'update')) {
	?>
        <div class="w-full sm:w-auto flex items-center sm:ml-auto mt-3 sm:mt-0">
          <a href="<?=$update_url?>" class="button text-white bg-theme-1 shadow-md mr-2"><?=Yii::t('app', 'Update')?></a>
        </div>
        <?php
}
?>
      </div>
      <div class="mt-6 lg:mt-0 flex-1 px-5 border-t lg:border-0 border-gray-200 dark:border-dark-5 pt-5 lg:pt-0 pb-4 mt-5" style="margin-top:20px;">
        <?=$model['description']?>
      </div>
    </div>
<br clear="all" />
     <div class="intro-y box p-5 mt-5" style="padding-top: 0">
      <div class="flex flex-col sm:flex-row items-center p-3 border-b border-gray-200">
        <h2 class="font-medium text-base mr-auto">
       <?=Yii::t('app', 'Comments')?>
        </h2>
      </div>
      <div>
        <div id="comment-section"></div>
      </div>
    </div>
  </div>
  <div class="col-span-12 lg:col-span-4">
    <div class="intro-y box p-5">
      <div class="overflow-x-auto">
        <table class="table">
          <tr>
            <?php
$status_clr = $model->getStatusColor();
?>
          <th><?=Yii::t('app', 'Status')?></th><td><span class="px-2 py-1 rounded-full bg-<?=$status_clr?> text-white mr-1"><?=Yii::t('app', $model->getStatus())?></span></td>
        </tr>
        <tr>
          <th><?=Yii::t('app', 'Start Date')?></th>
          <td><?=Yii::$app->getTable->date_format($model['start_date'])?></td>
        </tr>
        <tr>
          <th><?=Yii::t('app', 'End Date')?></th>
          <td><?=Yii::$app->getTable->date_format($model['end_date'])?></td>
        </tr>
        <tr>
          <tr>
            <th><?=Yii::t('app', 'Created At')?></th>
            <td><?=Yii::$app->getTable->datetime_format($model['created_at'])?></td>
          </tr>
          <tr>
            <th><?=Yii::t('app', 'Created By')?></th>
            <td><?=$model->getCreatedBy()?></td>
          </tr>
        </table>
      </div>
    </div>
    <?php
if ($model->status != 'completed') {

	?>
    <div class="intro-y box p-5  mt-5" style="padding-top: 0">
      <div class="flex flex-col sm:flex-row items-center p-3 border-b border-gray-200">
        <h2 class="font-medium text-base mr-auto">
        <?=Yii::t('app', 'TIME LEFT')?>
        </h2>
      </div>
      <div class="overflow-x-auto p-5"><div id="timer"></div></div>
    </div>
    <?php
}
?>
<?php
if ($model->getAssignees()) {
	?>
    <div class="intro-y box p-5  mt-5" style="padding-top: 0">
      <div class="flex flex-col sm:flex-row items-center p-3 border-b border-gray-200">
        <h2 class="font-medium text-base mr-auto">
        <?=Yii::t('app', 'Task Assign To')?>
        </h2>
      </div>
      <div class="overflow-x-auto p-5 flex"> <?=$model->getAssignees()?></div>
    </div>
    <?php
}
?>
    <div class="intro-y box p-5  mt-5" style="padding-top: 0">
      <div class="flex flex-col sm:flex-row items-center p-3 border-b border-gray-200">
        <h2 class="font-medium text-base mr-auto">
        <?=Yii::t('app', 'Attached Files')?>
        </h2>
      </div>
      <div class="mt-6 lg:mt-0 flex-1 px-5 border-t lg:border-0 border-gray-200 dark:border-dark-5 pt-5 lg:pt-0 pb-4">
        <?=$this->render('/attachment/view_attachment', ['type' => 'task', 'action' => 'view', 'relation_id' => $model->relation_id])?>
      </div>
    </div>
  </div>
</div>
<script>
  <?php

$start_date = $model->start_date;
$current_date = date('Y-m-d');

?>
  loadComment();
  function loadComment(){
     $('#comment-section').load('<?=Yii::getAlias('@web')?>/task-discussion/view?id=<?=$model->id?>');
  }
function updateTimer() {
  <?php
if ($start_date > $current_date) {
	?>
  var start= Date.parse('<?=date('Y-m-d 00:00:00', strtotime($model['start_date']))?>')
<?php } else {
	?>
var start = new Date();
<?php
}
?>
var end= Date.parse('<?=date('Y-m-d 23:59:59', strtotime($model['end_date']))?>');
diff = end - start;
<?php
if ($current_date <= $model->end_date && $model->status != 'completed') {
	?>

days = Math.floor(diff / (1000 * 60 * 60 * 24));
hours = Math.floor(diff / (1000 * 60 * 60));
mins = Math.floor(diff / (1000 * 60));
secs = Math.floor(diff / 1000);
d = (days < 0) ? 0 : days;
h = (hours - days * 24 < 0)  ? 0 : hours - days * 24;
m = (mins - hours * 60 < 0) ? 0 : mins - hours * 60;
s = (secs - mins * 60 < 0) ? 0 : secs - mins * 60;
<?php
} else {
	?>
d = 0;
h=0;
m=0;
s=0;
<?php
}
?>

document.getElementById("timer")
.innerHTML =
'<div>' + d + '<span>days</span></div>' +
'<div>' + h + '<span>hours</span></div>' +
'<div>' + m + '<span>minutes</span></div>' +
'<div>' + s + '<span>seconds</span></div>';

}
<?php
if ($model->status != 'completed') {

	if ($start_date <= $current_date) {
		?>
setInterval('updateTimer()', 1000);
<?php
} else {
		?>
updateTimer();
<?php }
}?>
</script>
<style type="text/css">
#timer {
font-size: 2em;
font-weight: bold;
color: black;
margin-top:10px;
/*text-shadow: 0 0 20px #48C8FF;*/
}
#timer div {
display: inline-block;
min-width: 90px;
}
#timer div span {
color: #B1CDF1;
display: block;
font-size: .35em;
font-weight: 400;
}
</style>



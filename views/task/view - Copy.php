<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Task */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tasks'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$update_url = Yii::getAlias('@web') . '/task/update?id=' . $model->id;
?>
<div class="grid grid-cols-12 gap-12 mt-5">
  <div class="intro-y col-span-12 lg:col-span-12">
    <!-- BEGIN: Basic Table -->
    <div class="intro-y box">
      <div class="flex flex-col sm:flex-row items-center p-3 border-b border-gray-200">
        <h2 class="font-medium text-base mr-auto">
        <?=Html::encode($this->title)?>
        </h2>
        <?php if (Yii::$app->Utility->hasAccess('task', 'update')) {
	?>
        <div class="w-full sm:w-auto flex items-center sm:ml-auto mt-3 sm:mt-0">
            <label style="font-weight: bold; font-size: 18px; margin-right: 15px"> TIME LEFT : </label>
                    <div id="timer">
</div>
          <a href="<?=$update_url?>" class="button text-white bg-theme-1 shadow-md mr-2"><?=Yii::t('app', 'Update')?></a>
        </div>
        <?php
}
?>
      </div>
      <div class="mt-6 lg:mt-0 flex-1 px-5 border-t lg:border-0 border-gray-200 dark:border-dark-5 pt-5 lg:pt-0" style="margin-top:-20px">
        <div class="p-5" id="basic-table">
          <div class="preview">
            <div class="overflow-x-auto">
              <table class="table">
                <tr>
                    <?php
$status_clr = $model->getStatusColor();
?>
 <th><?=Yii::t('app', 'Task Assign To')?></th><td><div class="flex">
                                           <?=$model->getAssignees()?>
                                        </div></td>
                    <th><?=Yii::t('app', 'Status')?></th><td><span class="px-2 py-1 rounded-full bg-<?=$status_clr?> text-white mr-1"><?=$model->getStatus();?></span></td>

                     </tr>
                <tr>
                   <th><?=Yii::t('app', 'Start Date')?></th><td><?=Yii::$app->getTable->date_format($model['start_date'])?></td>
                    <th><?=Yii::t('app', 'End Date')?></th><td><?=Yii::$app->getTable->date_format($model['end_date'])?></td>


                                         </tr>

                               <tr>

                                  <tr>
                    <th><?=Yii::t('app', 'Created At')?></th><td><?=Yii::$app->getTable->datetime_format($model['created_at'])?></td>
                    <th><?=Yii::t('app', 'Created By')?></th><td><?=$model->getCreatedBy()?></td>
                               </tr>

</table>
</div>
</div>
</div>
</div>
</div>
</div>
</div>

<div class="grid grid-cols-12 gap-12 mt-5">
  <div class="intro-y col-span-12 lg:col-span-12">
    <!-- BEGIN: Basic Table -->
    <div class="intro-y box">
      <div class="flex flex-col sm:flex-row items-center p-3 border-b border-gray-200">
        <h2 class="font-medium text-base mr-auto">
        <?=Yii::t('app', 'Description')?>
        </h2>
      </div>
      <div class="mt-6 lg:mt-0 flex-1 px-5 border-t lg:border-0 border-gray-200 dark:border-dark-5 pt-5 lg:pt-0 pb-4 mt-5" style="margin-top:20px;">
      <?=$model['description']?>

</div>
</div>
</div>
</div>

<div class="grid grid-cols-12 gap-12 mt-5">
  <div class="intro-y col-span-12 lg:col-span-12">
    <!-- BEGIN: Basic Table -->
    <div class="intro-y box">
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
function updateTimer() {

    var start = new Date();
var end= Date.parse('<?=date('Y-m-d 23:59:59', strtotime($model['end_date']))?>')
    diff = end - start;

    days = Math.floor(diff / (1000 * 60 * 60 * 24));
    hours = Math.floor(diff / (1000 * 60 * 60));
    mins = Math.floor(diff / (1000 * 60));
    secs = Math.floor(diff / 1000);

    d = (days < 0) ? 0 : days;
    h = (hours - days * 24 < 0)  ? 0 : hours - days * 24;
    m = (mins - hours * 60 < 0) ? 0 : mins - hours * 60;
    s = (secs - mins * 60 < 0) ? 0 : secs - mins * 60;
    document.getElementById("timer")
        .innerHTML =
        '<div>' + d + '<span>days</span></div>' +
        '<div>' + h + '<span>hours</span></div>' +
        '<div>' + m + '<span>minutes</span></div>' +
        '<div>' + s + '<span>seconds</span></div>';
}

<?php

$start_date = $model->start_date;
$current_date = date('Y-m-d');
if ($start_date <= $current_date) {
	?>
setInterval('updateTimer()', 1000);
<?php
}
?>
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
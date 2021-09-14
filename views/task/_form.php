<?php

use app\models\User;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Task */
/* @var $form yii\widgets\ActiveForm */
$user = User::find()->where(['status' => 1])->andWhere(['user_role' => 'user'])->all();
$user_array = ArrayHelper::map($user, 'id', function ($user) {
	return $user['first_name'] . ' ' . $user['last_name'];
})
?>
 <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
<div class="task-form">

    <?php $form = ActiveForm::begin();?>
    <div class="row">
        <div class="col-md-6">

    <?=$form->field($model, 'name')->textInput(['maxlength' => true, 'placeholder' => Yii::t('app', 'Name'), 'class' => 'input w-full border mt-2'])?>
</div>

 <div class="col-md-6">

    <?=$form->field($model, 'assign_to')->dropDownList($user_array, ['data-search' => 'true', 'class' => 'tail-select w-full input mt-5', 'data-placeholder' => Yii::t('app', 'Select Assignee'), 'multiple' => 'multiple'])?>
</div>

</div>
<div class="row">
      <div class="col-md-4">
 <?=$form->field($model, 'start_date')->textInput(['class' => 'input w-full border mt-2 start_date', 'autocomplete' => 'off'])?>
</div>
  <div class="col-md-4">

    <?=$form->field($model, 'end_date')->textInput(['class' => 'input w-full border mt-2 end_date', 'autocomplete' => 'off'])?>
</div>

<div class="col-md-4">
        <?=$form->field($model, 'status')->dropDownList(['upcoming' => Yii::t('app', 'Upcoming'), 'in-progress' => Yii::t('app', 'In Progress'), 'completed' => Yii::t('app', 'Completed')], ['prompt' => Yii::t('app', 'Select Status'), 'class' => 'input w-full border mt-2'])?>

    </div>
</div>
<div class="row">
    <div class="col-md-12">

    <?=$form->field($model, 'description')->textarea(['rows' => 6, 'class' => 'input w-full border mt-3 summernote'])?>
</div>
</div>
<!--
<div class="row">
    <div class="col-md-12">
         <form data-file-types="image/jpeg|image/png|image/jpg" action="/file-upload" class="dropzone border-gray-200 border-dashed">
     <div class="fallback"> <input name="file" type="file" multiple /> </div>
     <div class="dz-message" data-dz-message>
         <div class="text-lg font-medium">Drop files here or click to upload.</div>
         <div class="text-gray-600"> This is just a demo dropzone. Selected files are <span class="font-medium">not</span> actually uploaded. </div>
     </div>
 </form>
    </div>

    </div> -->

    <div class="submitButton">
        <?=Html::submitButton(Yii::t('app', 'Save'), ['class' => 'button text-white bg-theme-1 xl:mr-3 align-top'])?>
    </div>

    <?php ActiveForm::end();?>

</div>

<?=$this->render('/attachment/_attachment', ['type' => 'task', 'relation_id' => $model['relation_id']])?>

<script type="text/javascript">
var mindate = '<?=$model->start_date?>';
var enddatepicker1;
var startDate = null;
function endDatePicker() {
  enddatepicker1 = new Litepicker({
  element: document.getElementById('task-end_date'),
  autoApply: true,
  lang: '<?=Yii::$app->language?>',
  format:'DD.MM.YYYY',
  minDate: mindate,
  startDate: startDate
})
}

 $(document).ready(function(){
     new Litepicker({
  element: document.getElementById('task-start_date'),
  autoApply: true,
  lang: '<?=Yii::$app->language?>',
  format:'DD.MM.YYYY',
 onSelect: function(date){
    mindate = Date.parse(date);
    enddatepicker1.destroy();

     var nn1 =  (document.getElementById('task-start_date').value);
    var nn2 = (document.getElementById('task-end_date').value);
    var minbig = compareDates(nn1, nn2);
    if(minbig){
        startDate = mindate;
    }else{
        startDate = null;
    }
    endDatePicker();

      //$('#project-end_date').attr('data-min-date','2021-03-21');
    }
 });

     endDatePicker();

    });

        function compareDates(d1, d2){
var parts =d1.split('.');
var d1 = Number(parts[2] + parts[1] + parts[0]);
parts = d2.split('.');
var d2 = Number(parts[2] + parts[1] + parts[0]);
return d2 <= d1;
}
 // $(document).ready(function() {
  $('.summernote').summernote({
    height: '200'
  });
//});

</script>

 <style type="text/css">
    .submitButton {
     position: absolute!important;
    top: 15px;
    right: 13px;
    }
    .drop-zone-box {
    background: #80808052;
    }
    .delete-btn {
            font-size: 13px;
    position: absolute;
    right: 3px;
    padding: 1px 5px;
    }
    .note-modal-backdrop {
  z-index: 0;
  opacity: 0;
}
.note-modal-content .checkbox {
width: 100%!important;
}
.note-modal-content .checkbox:before  {
  width: 0px;
}
.note-modal-footer {
    height: 58px;
    padding: 10px;
    text-align: center;
}
</style>
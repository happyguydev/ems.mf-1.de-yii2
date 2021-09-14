<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Leave */
/* @var $form yii\widgets\ActiveForm */
$leave_type_array = ['Working From Home' => Yii::t('app', 'Working From Home'), 'Sick Leave' => Yii::t('app', 'Sick Leave'), 'Parental Leave' => Yii::t('app', 'Parental Leave'), 'Annual Leave' => Yii::t('app', 'Annual Leave'), 'Normal Leave' => Yii::t('app', 'Normal Leave')];

?>

<div class="leave-form">

    <?php $form = ActiveForm::begin();?>
    <div class="row">
        <div class="col-md-4">


    <?=$form->field($model, 'leave_type')->dropDownList($leave_type_array, ['data-search' => 'true', 'class' => 'tail-select w-full mt-3', 'prompt' => Yii::t('app', 'Select Leave Type')])?>
</div>


<div class="col-md-4">

    <?=$form->field($model, 'from_date')->textInput(['class' => 'input w-full border mt-2 start_date', 'placeholder' => Yii::t('app', 'From Date'), 'onchange' => "getNoOfDays()", 'autocomplete' => 'off'])?>

</div>
<div class="col-md-4">

    <?=$form->field($model, 'to_date')->textInput(['class' => 'input w-full border mt-2 end_date', 'placeholder' => Yii::t('app', 'To Date'), 'onchange' => "getNoOfDays()", 'autocomplete' => 'off'])?>
</div>
</div>

<div class="row">
<div class="col-md-12">

    <?=$form->field($model, 'half_day')->dropDownList(['first-half' => Yii::t('app', 'First Half'), 'second-half' => Yii::t('app', 'Second Half'), 'full-day' => Yii::t('app', 'Full Day')], ['data-search' => 'true', 'class' => 'tail-select w-full mt-3', 'prompt' => Yii::t('app', 'Select Half Day')])?>
</div>
</div>
<div class="row">
    <div class="col-md-12">

    <?=$form->field($model, 'reason')->textarea(['rows' => 3, 'class' => 'input w-full border mt-2'])?>
    <input type="hidden" name="current_latitude" id="current_latitude" value="">
    <input type="hidden" name="current_longitude" id="current_longitude" value="">
</div>
</div>

   <?=$form->field($model, 'no_of_days')->hiddenInput(['class' => 'input w-full border mt-2', 'placeholder' => Yii::t('app', 'No.Of Days'), 'readonly' => true])->label(false)?>

   <div class="submitButton">
        <?=Html::submitButton(Yii::t('app', 'Save'), ['class' => 'button text-white bg-theme-1 xl:mr-3 align-top'])?>
    </div>
    <?php ActiveForm::end();?>

</div>
<?=$this->render('/attachment/_attachment', ['type' => 'leave', 'relation_id' => $model['relation_id']])?>
<style type="text/css">
    .field-leave-half_day .tail-select, .field-leave-leave_type .tail-select {
        margin-top:8px;
    }
    .field-leave-is_full_day {
        margin-top:30px;
    }
</style>

<script type="text/javascript">

    function getNoOfDays() {
        let start_date = $('#leave-from_date').val();
        let end_date = $('#leave-to_date').val();
        var date1 = new Date(start_date);
        var date2 = new Date(end_date);

        // To calculate the time difference of two dates
        var Difference_In_Time = date2.getTime() - date1.getTime();

        // To calculate the no. of days between two dates
        var Difference_In_Days = Difference_In_Time / (1000 * 3600 * 24);

        if(Difference_In_Days < 0) {
            Difference_In_Days = 0;
        }
        if(Difference_In_Time==0) {
            Difference_In_Days = 1;
        }


        if(end_date!='') {
            $('#leave-no_of_days').val(Difference_In_Days);

        }
    }

  $(document).ready(function(){
     var latitude =  $('#latitude').val();
    var longitude = $('#longitude').val();

    $('#current_latitude').val(latitude);
    $('#current_longitude').val(longitude);


   /* $('.start_date').change(function(){
      $('.end_date').attr('min',$(this).val());
    })*/
});

var mindate = '<?=$model->from_date?>';
var enddatepicker1;
var startDate = null;
  function endDatePicker() {
  enddatepicker1 =  new Litepicker({
  element: document.getElementById('leave-to_date'),
  autoApply: true,
  lang: '<?=Yii::$app->language?>',
  format:'DD.MM.YYYY',
  minDate: mindate,
  startDate: startDate
})
}

 $(document).ready(function(){
     new Litepicker({
  element: document.getElementById('leave-from_date'),
  autoApply: true,
  lang: '<?=Yii::$app->language?>',
  format:'DD.MM.YYYY',
  minDate:'<?=date('Y-m-d')?>',
 onSelect: function(date){
    mindate = Date.parse(date);
    enddatepicker1.destroy();

    var nn1 =  (document.getElementById('leave-from_date').value);
    var nn2 = (document.getElementById('leave-to_date').value);
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

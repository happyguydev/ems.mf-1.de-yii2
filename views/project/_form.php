<?php

use app\models\Customer;
use app\models\User;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Project */
/* @var $form yii\widgets\ActiveForm */
$user = User::find()->where(['status' => 1])->andWhere(['user_role' => 'user'])->all();
$user_array = ArrayHelper::map($user, 'id', function ($user) {
	return $user['first_name'] . ' ' . $user['last_name'];
});

$customer = Customer::find()->where(['status' => 1])->all();
$customer_array = ArrayHelper::map($customer, 'id', function ($data) {
	return $data['first_name'] . ' ' . $data['last_name'];
});

$billing_type_array = ['Hourly' => Yii::t('app', 'Hourly'), 'Daily' => Yii::t('app', 'Daily'), 'Weekly' => Yii::t('app', 'Weekly'), 'Monthly' => Yii::t('app', 'Monthly'), 'Fixed Price' => Yii::t('app', 'Fixed Price'), 'Installment Payment' => Yii::t('app', 'Installment Payment')];
?>

    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>

    <?php $form = ActiveForm::begin();?>
     <div class="row">

        <div class="col-md-4">

    <?=$form->field($model, 'name')->textInput(['maxlength' => true, 'placeholder' => Yii::t('app', 'Name'), 'class' => 'input w-full border mt-2'])?>
</div>
 <div class="col-md-4">

   <?=$form->field($model, 'customer_id')->dropDownList($customer_array, ['data-search' => 'true', 'class' => 'tail-select w-full input mt-5', 'prompt' => Yii::t('app', 'Select Customer')])?>
</div>
<div class="col-md-4">
    <?=$form->field($model, 'team')->dropDownList($user_array, ['data-search' => 'true', 'class' => 'tail-select w-full input mt-5', 'data-placeholder' => Yii::t('app', 'Select Team'), 'multiple' => 'multiple'])?>
    </div>
</div>

 <div class="row">
        <div class="col-md-6">
    <?=$form->field($model, 'start_date')->textInput(['class' => 'input w-full border mt-2 start_date', 'placeholder' => Yii::t('app', 'Start Date'), 'autocomplete' => 'off'])?>

        </div>
        <div class="col-md-6">
                <?=$form->field($model, 'end_date')->textInput(['class' => 'input w-full border mt-2 end_date', 'placeholder' => Yii::t('app', 'End Date'), 'autocomplete' => 'off'])?>

        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
                <?=$form->field($model, 'description')->textarea(['rows' => 6, 'class' => 'summernote input w-full border mt-2'])?>

        </div>
    </div>


<div class="row">
<div class="col-md-3">
        <?=$form->field($model, 'budget')->textInput(['maxlength' => true, 'class' => 'input w-full border mt-2', 'placeholder' => Yii::t('app', 'Budget')])?>
</div>
    <div class="col-md-3">
            <?=$form->field($model, 'billing_type')->dropDownList($billing_type_array, ['data-search' => 'true', 'class' => 'tail-select w-full input mt-5', 'prompt' => Yii::t('app', 'Select Blilling Type')])?>

    </div>
    <div class="col-md-3">
            <?=$form->field($model, 'estimated_hours')->textInput(['maxlength' => true, 'class' => 'input w-full border mt-2', 'placeholder' => Yii::t('app', 'Esitmated Hours')])?>

    </div>
     <div class="col-md-3">
            <?=$form->field($model, 'status')->dropDownList(['pending' => Yii::t('app', 'Pending'), 'in-progress' => Yii::t('app', 'In Progress'), 'delay' => Yii::t('app', 'Delay'), 'completed' => Yii::t('app', 'Completed')], ['data-search' => 'true', 'class' => 'tail-select w-full input mt-5', 'data-placeholder' => Yii::t('app', 'Select Status')])?>
    </div>
</div>
<div class="row">
        <div class="col-md-12">
                <?=$form->field($model, 'payment_description')->textarea(['rows' => 2, 'class' => ' input w-full border mt-2'])?>

        </div>
    </div>


    <div class="submitButton">
        <?=Html::submitButton(Yii::t('app', 'Save'), ['class' => 'button text-white bg-theme-1 xl:mr-3 align-top'])?>
    </div>

    <?php ActiveForm::end();?>

</div>
<?=$this->render('/attachment/_attachment', ['type' => 'project', 'relation_id' => $model['relation_id']])?>

<script type="text/javascript">
   const allRanges = document.querySelectorAll(".range-wrap");
allRanges.forEach(wrap => {
  const range = wrap.querySelector(".range");
  const bubble = wrap.querySelector(".bubble");

  range.addEventListener("input", () => {
    setBubble(range, bubble);
  });
  setBubble(range, bubble);
});

function setBubble(range, bubble) {
  const val = range.value;
  const min = range.min ? range.min : 0;
  const max = range.max ? range.max : 100;
  const newVal = Number(((val - min) * 100) / (max - min));
  bubble.innerHTML = val;

  // Sorta magic numbers based on size of the native UI thumb
  bubble.style.left = `calc(${newVal}% + (${8 - newVal * 0.15}px))`;
}
var mindate = '<?=$model->start_date?>';
var enddatepicker1;
var startDate = null;
function endDatePicker(st='') {
   enddatepicker1 = new Litepicker({
  element: document.getElementById('project-end_date'),
  //field: document.getElementById('project-end_date'),
  autoApply: true,
  lang: '<?=Yii::$app->language?>',
  format:'DD.MM.YYYY',
  minDate: mindate,
  startDate: startDate
})
}

 $(document).ready(function(){
     new Litepicker({
  element: document.getElementById('project-start_date'),
  //field: document.getElementById('project-start_date'),
  autoApply: true,
  lang: '<?=Yii::$app->language?>',
  format:'DD.MM.YYYY',
 onSelect: function(date){
    mindate = Date.parse(date);
    enddatepicker1.destroy();
     var nn1 =  (document.getElementById('project-start_date').value);
    var nn2 = (document.getElementById('project-end_date').value);
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
//$(document).ready(function() {
    $('.summernote').summernote({
    height: '200'
  });
//});


   // / Dropzone.autoDiscover = false;



   /* var myDropzone = new Dropzone(".dropzone", {

       maxFilesize: 10,

       acceptedFiles: ".jpeg,.jpg,.png,.gif",

       addRemoveLinks: true,

       success:function(file, response)
        {
            // Do what you want to do with your response
            // This return statement is necessary to remove progress bar after uploading.
            //return file.previewElement.classList.add("dz-success");
        }

    });
*/


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
    .range-wrap {
  position: relative;
  margin: 0 auto 3rem;
}
.range {
  width: 100%;
}
.bubble {
  background: red;
  color: white;
  padding: 4px 12px;
  position: absolute;
  border-radius: 4px;
  left: 50%;
  top:60px;
  transform: translateX(-50%);
}
.bubble::after {
  content: "";
  position: absolute;
  width: 2px;
  height: 2px;
  background: red;
  top: -1px;
  left: 50%;
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
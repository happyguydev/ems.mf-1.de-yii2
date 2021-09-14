<?php

use app\models\User;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
/* @var $this yii\web\View */
/* @var $model app\models\CalendarGroup */

$this->title = 'Update Group: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Calendar Groups', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';

$user = User::find()->where(['status' => 1])->andWhere(['user_role' => 'user'])->all();
$user_array = ArrayHelper::map($user, 'id', function ($user) {
	return $user['first_name'] . ' ' . $user['last_name'];
})
?>
 <div class="flex items-center px-5 py-5 sm:py-3 border-b border-gray-200 dark:border-dark-5">


             <h2 class="font-medium text-base mr-auto"><?=Html::encode($this->title)?></h2>

         </div>

 <div class="calendar-group-form">

    <?php $form = ActiveForm::begin(['id' => 'update-form']);?>
      <div class="p-5 grid grid-cols-12 gap-4 gap-y-3">
<div class="col-span-12 sm:col-span-12">

    <?=$form->field($model, 'title')->textInput(['class' => "input w-full border mt-2 flex-1", 'placeholder' => Yii::t('app', 'Title'), 'id' => 'update-title'])?>
</div>

<div class="col-span-12 sm:col-span-6">

    <?=$form->field($model, 'bg_color')->input('color', ['class' => "form-control", 'placeholder' => Yii::t('app', 'Bg Color')])?>
</div>

<div class="col-span-12 sm:col-span-6">

    <?=$form->field($model, 'text_color')->input('color', ['class' => "form-control", 'placeholder' => Yii::t('app', 'Text Color')])?>
</div>
<div class="col-span-12 sm:col-span-12">
   <?=$form->field($model, 'assign_to')->dropDownList($user_array, ['class' => 'tail-select w-full ', 'data-placeholder' => Yii::t('app', 'Select Assignee'), 'multiple' => 'multiple'])?>
</div>

</div>

  <div class="px-5 py-3 text-right border-t border-gray-200 dark:border-dark-5">
    <button type="button" data-dismiss="modal" class="closemodal button w-20 border text-gray-700 dark:border-dark-5 dark:text-gray-300 mr-1"><?=Yii::t('app', 'Cancel')?></button>

        <?=Html::submitButton('Save', ['class' => 'button w-20 bg-theme-1 text-white'])?>
</div>
    <?php ActiveForm::end();?>

</div>


<script type="text/javascript">
  $('.closemodal').click(function(){
    $('body').removeClass('overflow-y-hidden');
    $('body').css('padding-right','');
});
  tail.select('#calendargroup-assign_to', {
      search: true,
      multiple: true,
      hideDisabled: true,
      hideSelected: true,
      multiLimit: 15,
      multiShowCount: false,
      multiContainer: true

  });
</script>
<style type="text/css">
  .tail-select{
    width: 100%;
  }
</style>
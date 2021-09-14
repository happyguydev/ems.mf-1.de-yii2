<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Appointment */

$this->title = Yii::t('app', 'Add New Appointment');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Appointments'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
 <div class="flex items-center px-5 py-5 sm:py-3 border-b border-gray-200 dark:border-dark-5">


             <h2 class="font-medium text-base mr-auto"><?=Html::encode($this->title)?></h2>

         </div>


    <?=$this->render('_form', [
	'model' => $model,
])?>


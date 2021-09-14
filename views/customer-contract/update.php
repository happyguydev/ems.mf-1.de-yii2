<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\CustomerContract */

$this->title = Yii::t('app', 'Update Customer Contract') . ' : ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Customer Contracts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
  <div class="flex items-center px-5 py-5 sm:py-3 border-b border-gray-200 dark:border-dark-5">


             <h2 class="font-medium text-base mr-auto"><?=Html::encode($this->title)?></h2>

         </div>

    <?=$this->render('_form', [
	'model' => $model,
])?>


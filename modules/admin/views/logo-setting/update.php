<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\themes\main\modules\admin\models\Setting */

$this->title = 'Update Setting: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Settings', 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->Id, 'url' => ['view', 'id' => $model->Id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="setting-update">

    <h1><?=Html::encode($this->title)?></h1>

    <?=$this->render('_form', [
	'model' => $model,
])?>

</div>

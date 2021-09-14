<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\themes\main\modules\admin\models\Setting */

$this->title = 'Create Setting';
$this->params['breadcrumbs'][] = ['label' => 'Settings', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-bordered">
        	<div class="panel-body">


    <h1><?=Html::encode($this->title)?></h1>

    <?=$this->render('_form', [
	'model' => $model,
])?>

   </div>
		</div>
	</div>
</div>

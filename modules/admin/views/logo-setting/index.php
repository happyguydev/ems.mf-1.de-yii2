<?php

use yii\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Settings');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-bordered">
        	<div class="panel-body">


    <h1><?=Html::encode($this->title)?></h1>

    <p>
        <?=Html::a(Yii::t('app', 'Create Setting'), ['create'], ['class' => 'btn btn-success'])?>
    </p>

    <?=GridView::widget([
	'dataProvider' => $dataProvider,
	'columns' => [
		['class' => 'yii\grid\SerialColumn'],

		//'id',
		'setting_name',
		'setting_value',

		['class' => 'yii\grid\ActionColumn'],
	],
]);?>

</div>
		</div>
	</div>
</div>

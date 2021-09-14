<?php

use yii\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Countries';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="country-index">


    <div class="panel panel-bordered">
      <div class="panel-heading">
        <h3 class="panel-title">
            <?=Html::encode($this->title)?>

            </h3>

<div class="panel-actions pull-right">

        <?=Html::a('Add New', ['create'], ['class' => 'btn btn-success text-white'])?>
</div>
</div>
    <div class="panel-body">
    <?=GridView::widget([
	'dataProvider' => $dataProvider,
	'columns' => [
		['class' => 'yii\grid\SerialColumn'],

		//'id',
		'name',
		'code',
		'phone_prefix',
		'status:html',

		['class' => 'app\components\grid\CustomColumn', 'template' => '{update} {status}'],
	],
]);?>


</div>
</div>
</div>

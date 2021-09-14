<?php

use yii\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\admin\models\search\NotificationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Notifications';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="notification-index">

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

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?=GridView::widget([
	'dataProvider' => $dataProvider,
	'filterModel' => $searchModel,
	'columns' => [
		['class' => 'yii\grid\SerialColumn'],

		//'id',
		'notification_name',
		['attribute' => 'send_to', 'format' => 'html', 'value' => function ($data) {

			if ($data['send_to'] == 'all') {
				return '<a href="' . Yii::getAlias('@web') . '/admin/user">All</a>';

			} else {

				return $data->getItemshtml();

			}
		}],
		//'content:ntext',
		'sent_time',
		['attribute' => 'status', 'format' => 'html', 'value' => 'status', 'filter' => ['1' => 'Enabled', '0' => 'Disabled']],

		'created_at',
		//'created_by',

		['class' => 'app\components\grid\CustomColumn', 'template' => '{view} {update} {status}'],
	],
]);?>


</div>
</div>
</div>

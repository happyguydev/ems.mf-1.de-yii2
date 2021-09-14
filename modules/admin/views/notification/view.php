<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\Notification */

$this->title = $model->notification_name;
$this->params['breadcrumbs'][] = ['label' => 'Notifications', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>

    <div class="notification-index">

    <div class="panel panel-bordered">
      <div class="panel-heading">
        <h3 class="panel-title">
            <?=Html::encode($this->title)?>

            </h3>

<div class="panel-actions pull-right">

         <?=Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary'])?>

</div>
</div>
    <div class="panel-body">

    <?=DetailView::widget([
	'model' => $model,
	'attributes' => [
		//	'id',
		//	'notification_name',
		['attribute' => 'send_to', 'format' => 'html', 'value' => function ($data) {

			if ($data['send_to'] == 'all') {
				return '<a href="' . Yii::getAlias('@web') . '/admin/user">All</a>';

			} else {

				return $data->getItemshtml();

			}
		}],
		'content:ntext',
		'sent_time',
		'status:html',
		'created_at',
		'created_by',
	],
])?>

</div>
</div>
</div>

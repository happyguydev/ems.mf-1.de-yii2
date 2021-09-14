<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\Notification */

$this->title = 'Update Notification: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Notifications', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="notification-update">
	<div class="panel panel-bordered">
		<div class="panel-heading">

    <h3 class="panel-title"><?=Html::encode($this->title)?></h3>

</div>

<div class="panel-body">

    <?=$this->render('_form', [
	'model' => $model,
])?>

</div>
</div>
</div>
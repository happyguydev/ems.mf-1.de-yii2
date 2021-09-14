<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\Media */

$this->title = 'Update Media: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Media', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="panel panel-default">
	<div class="panel-heading">
	<?=Html::encode($this->title)?></div>
	<div class="panel-body">

    <?=$this->render('_form', [
	'model' => $model,
])?>

</div>
</div>


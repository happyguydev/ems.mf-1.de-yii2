<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\Country */

$this->title = 'Create Country';
$this->params['breadcrumbs'][] = ['label' => 'Countries', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="notification-create">
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

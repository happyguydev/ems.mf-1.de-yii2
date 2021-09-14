<?php
use yii\helpers\Html;
/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\General */
$this->title = Yii::t('app', 'Create General');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Generals'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="general-create">
	<h1><?=Html::encode($this->title)?></h1>
	<?=$this->render('_form', [
	'model' => $model,
	])?>
</div>
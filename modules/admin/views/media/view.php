<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\Media */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Media', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="panel panel-default">
  <div class="panel-heading">
    <i class="fa fa-image"></i> <?=Html::encode($this->title)?>
    <div class="pull-right" style="margin-top:-7px;">
      <p>
			<?php if (Yii::$app->gfcore->hasAccess('media', 'update')) {?>
        <?=Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary'])?>
			<?php }?>
			<?php if (Yii::$app->gfcore->hasAccess('media', 'delete')) {?>
        <?=Html::a('Delete', ['delete', 'id' => $model->id], [
    'class' => 'btn btn-danger',
    'data' => [
        'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
        'method' => Yii::t('app', 'post'),
    ],
])?>
<?php }?>
      </p>
    </div>
  </div>
  <div class="panel-body">
    <div class="row">
      <div class="col-md-12">
        <table class="table table-bordered">
    <?=DetailView::widget([
    'model' => $model,
    'attributes' => [
        //'id',
        'title',
        'description:html',
        'alternate_text',
        'caption',
        ['attribute' => 'thumb', 'format' => 'raw', 'value' => function ($model) {
            return Html::a(Html::img($model->getThumb($model), ['alt' => $model->file_name]), ['/uploads/media/' . date('Y', strtotime($model->created_at)) . '/' . date('m', strtotime($model->created_at)) . '/' . $model->file_name], ['target' => '_blank']);}],

        'extension',
        ['attribute' => 'status', 'value' => function ($data) {
            return $data->getStatus($data->status);

        },
        ],
        ['attribute' => 'created_by', 'value' => $model->createdBy['first_name'], ' ' . $model->createdBy['last_name']],
        'created_at',
    ],
])?>

</table>
</div>
</div>
</div>
</div>
<style type="text/css">

	img
	{
		height:100px;
		width:100px;
	}
</style>





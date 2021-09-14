<?php

use yii\bootstrap\Modal;
use yii\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Media');
$this->params['breadcrumbs'][] = $this->title;

Modal::begin([
	'header' => '<h4>' . Yii::t('app', 'Upload Media') . '</h4>',
	'id' => 'upload-modal',
	'size' => 'modal-md',
]);
echo '<div id="upload-content"></div>';
Modal::end();
?>

<div class="row">
    <div class="col-sm-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <?=Html::encode($this->title)?>
                <div class="pull-right" style="margin-top:-7px;">
								<?php if (Yii::$app->gfcore->hasAccess('media', 'create')) {?>
                    <?=Html::a(Yii::t('app', 'Add Media'), ['create'], ['class' => 'btn btn-success'])?>
                    <a href="javascript:void(0)" onclick="getUpload()" class="btn btn-primary"><?=Yii::t('app', 'Upload Media')?></a>
								<?php }?>

                </div>
            </div>
            <div class="panel-body">
    <?=GridView::widget([
	'dataProvider' => $dataProvider,
	'filterModel' => $searchModel,

	'columns' => [
		['class' => 'yii\grid\SerialColumn'],

		//'id',
		['attribute' => 'thumb', 'format' => 'raw', 'value' => function ($model) {

			$ext = $model->extension;

			$file_name = explode('.', $model->file_name);

			if ($ext == 'jpg' || $ext == 'png' || $ext == 'gif' || $ext == 'jpeg') {

				$name = $file_name[0] . '_thumb.' . $ext;

			} else {
				$name = $model->file_name;

			}

			return Html::a(Html::img($model->getThumb($model), ['alt' => 'yii', 'class' => 'img-thumbnail']), ['/uploads/media/' . date('Y', strtotime($model->created_at)) . '/' . date('m', strtotime($model->created_at)) . '/' . $name], ['target' => '_blank', 'class' => 'media-img ']);}],
		'title',
		'description:ntext',
		'alternate_text',
		'caption',
		['attribute' => 'status', 'value' => function ($data) {
			return $data->getStatus($data->status);
		},

			'contentOptions' => function ($data) {
				$clr = $data->status == '1' ? 'green' : 'red';
				return ['style' => 'width:80px;font-weight:bold;color:' . $clr];
			},

			'filter' => ['1' => 'Enabled', '0' => 'Disabled'],
		],

		// 'file_name',
		// 'extension',
		// 'status',
		// 'created_by',
		// 'created_at',

		['class' => 'yii\grid\ActionColumn',
			'visibleButtons' => [
				'update' => \Yii::$app->gfcore->hasAccess('media', 'update'),
				'status' => \Yii::$app->gfcore->hasAccess('media', 'update'),
				'view' => \Yii::$app->gfcore->hasAccess('media', 'view'),
				'delete' => \Yii::$app->gfcore->hasAccess('media', 'delete'),

			],
			'template' => '{view} {update} {delete} {status}',
			'buttons' => [
				'status' => function ($url, $model, $key) {
					if ($model->status == '1') {
						return Html::a(
							'<span class="glyphicon glyphicon-arrow-down" title="' . Yii::t('app', 'Disable') . '"></span>', Yii::getAlias('@web') . '/admin/media/status?val=0&id=' . $key, ['data-toggle' => 'tooltip', 'data-title' => Yii::t('app', 'Disable')]);
					} else {
						return Html::a(
							'<span class="glyphicon glyphicon-arrow-up" title="' . Yii::t('app', 'Enable') . '"></span>', Yii::getAlias('@web') . '/admin/media/status?val=1&id=' . $key, ['data-toggle' => 'tooltip', 'data-title' => Yii::t('app', 'Enable')]);
					}
				},
				'delete' => function ($url, $model, $key) {
					$search_in_post = Yii::$app->gfcore->searchInPost($model['file_name']);
					if ($search_in_post == 1) {
						return Html::a(
							'<span class="glyphicon glyphicon-trash" title="' . Yii::t('app', 'Delete') . '"></span>', Yii::getAlias('@web') . '/admin/media/delete?id=' . $key, ['data-toggle' => 'tooltip', 'data-title' => Yii::t('app', 'Delete'), 'data-method' => 'post', 'data-confirm' => Yii::t('app', 'This media is associated with others also. Do you really want to delete this media?')]);
					} else {
						return Html::a(
							'<span class="glyphicon glyphicon-trash" title="' . Yii::t('app', 'Delete') . '"></span>', Yii::getAlias('@web') . '/admin/media/delete?id=' . $key, ['data-toggle' => 'tooltip', 'data-title' => Yii::t('app', 'Delete'), 'data-method' => 'post', 'data-confirm' => Yii::t('app', 'Are you sure you want to delete this item?')]);
					}
				},

			]],
	],
]);?>
</div>
</div>
</div>
</div>


<style type="text/css">

	.media-img
	img
	{

		height:50px;
	}
</style>

<script type="text/javascript">

        function getUpload() {
        $('#upload-modal').modal('show');
        $('#upload-content').load("<?=Yii::getAlias('@web')?>/global/upload-form");
        }

	$('.close').click(function(){

		location.reload();
	})


</script>


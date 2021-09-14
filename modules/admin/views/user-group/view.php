<?php
use yii\helpers\Html;
use yii\widgets\DetailView;
/* @var $this yii\web\View */
/* @var $model app\models\AuthItem */
$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'User Groups'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="panel panel-default">
  <div class="panel-heading">
    <i class="fa fa-user"></i> <?=Html::encode($this->title)?>
    <div class="pull-right" style="margin-top:-7px;">
      <p>
        <?=Html::a('Update', ['update', 'id' => $model->name], ['class' => 'btn btn-primary'])?>
        <?=Html::a('Delete', ['delete', 'id' => $model->name], [
        'class' => 'btn btn-danger',
        'data' => [
        'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
        'method' => Yii::t('app', 'post'),
        ],
        ])?>
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
          'name',
          // 'type',
          // 'description:ntext',
          // 'rule_name',
          // 'data:ntext',
          // 'created_at',
          // 'updated_at',
          ],
          ])?>
        </table>
      </div>
    </div>
  </div>
</div>
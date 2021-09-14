<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\mailbox\models\Mailbox */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Mailboxes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="mailbox-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'email_client_id:email',
            'subject:ntext',
            'email_from:email',
            'emai_to',
            'cc',
            'bcc',
            'email_date:email',
            'message_id',
            'body',
            'status',
            'email_size:email',
            'uid',
            'msgno',
            'recent',
            'flagged',
            'answered',
            'deleted',
            'seen',
            'draft',
            'udate',
            'created_at',
        ],
    ]) ?>

</div>

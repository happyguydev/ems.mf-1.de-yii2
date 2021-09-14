<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Mailboxes');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mailbox-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Create Mailbox'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'email_client_id:email',
            'subject:ntext',
            'email_from:email',
            'emai_to',
            //'cc',
            //'bcc',
            //'email_date:email',
            //'message_id',
            //'body',
            //'status',
            //'email_size:email',
            //'uid',
            //'msgno',
            //'recent',
            //'flagged',
            //'answered',
            //'deleted',
            //'seen',
            //'draft',
            //'udate',
            //'created_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>

<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\mailbox\models\Mailbox */

$this->title = Yii::t('app', 'Create Mailbox');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Mailboxes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mailbox-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

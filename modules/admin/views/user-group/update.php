<?php
use yii\helpers\Html;
/* @var $this yii\web\View */
/* @var $model app\models\AuthItem */
$this->title = Yii::t('app', 'Update User Group: ') . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'User Groups', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->name]];
$this->params['breadcrumbs'][] = 'Update';
?>
 <div class="grid grid-cols-12 gap-12 mt-5">
                    <div class="intro-y col-span-12 lg:col-span-12">
                        <!-- BEGIN: Input -->
                        <div class="intro-y box">
                            <div class="flex flex-col sm:flex-row items-center p-5 border-b border-gray-200 dark:border-dark-5">
                                <h2 class="font-medium text-base mr-auto">
                                    <?=Html::encode($this->title)?>
                                </h2>
                            </div>
                            <div class="p-5" id="input">
                                <div class="preview">
		<?=$this->render('_form', [
	'model' => $model,
	'add2admin' => $add2admin,
])?>
	</div>
</div>
</div>
</div>
</div>
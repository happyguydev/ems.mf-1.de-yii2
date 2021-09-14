<?php
use app\widgets\RecentActivites;

$this->title = 'Recent Activities';

$notify = Yii::$app->notify;

?>

<div class="col-span-12 xl:col-span-12 mt-6">
                                <div class="intro-y flex items-center h-10">
                                    <h2 class="text-lg font-medium truncate mr-5">
                                        <?=Yii::t('app', $this->title)?>
                                    </h2>
                                </div>
                                <div class="mt-5">

                  <?php

?>

<?=RecentActivites::widget([
	'model' => '',
	'model_id' => 0,
	'dtype' => '',
])?>


                  </div>
                </div>


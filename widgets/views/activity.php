<?php
use app\models\User;
use yii\widgets\LinkPager;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

?>
 <?php Pjax::begin();?>

<div class="col-span-12 xl:col-span-12 mt-6">
                               <!--  <div class="intro-y flex items-center h-10">
                                    <h2 class="text-lg font-medium truncate mr-5">
                                        <?=Yii::t('app', $this->title)?>
                                    </h2>
                                </div> -->
                                <div class="mt-5">
                                     <?php
if (count($model) > 0) {
	foreach ($model as $key => $value) {

		$data = json_decode($value['data'], true);
		if ($data['to_user'] != 0 && $data['to_user'] != Yii::$app->user->identity->id && $data['to_user'] != 1) {
			$user = User::find()->where(['id' => $data['to_user']])->one();
			$for_name = 'for ' . $user['first_name'] . ' ' . $user['last_name'];
		} else {
			$for_name = '';
		}

		$title = ($data['title'] != null) ? $data['title'] : '';
		if ($value['action'] == 'updated comment' || $value['action'] == 'deleted comment' || $value['action'] == 'added comment') {
			$msg = "Has " . $value['action'] . ' on ' . $value['model'] . ' ' . $title;
		} elseif ($value['action'] == 'in-progress' || $value['action'] == 'completed' || $value['action'] == 'pending' || $value['action'] == 'delay' || $value['action'] == 'upcoming') {
			$msg = "Has changed status to " . strtoupper($value['action']) . ' for ' . $value['model'] . ' ' . $title;
		} elseif ($value['model'] == 'Media') {
			$msg = "Has " . $value['action'] . ' ' . $data['type'] . ' ' . $title . ' on ' . $value['model'];
		} else {
			$msg = 'Has ' . $value['action'] . ' ' . $value['model'] . ' ' . $title;

		}

		?>

                                    <div class="intro-y" href="javascript:void(0)">
                                        <div class="box px-4 py-4 mb-3 flex items-center zoom-in">
                                            <div class="w-10 h-10 flex-none image-fit rounded-md overflow-hidden">
                                                <img alt="<?=$value->user['first_name'] . ' ' . $value->user['last_name']?>" src="<?=$value->user->getThumbnailImage()?>">
                                            </div>
                                            <div class="ml-4 mr-auto">
                                                <div class="font-medium" style="font-weight:bold"><?=$value->user['first_name'] . ' ' . $value->user['last_name']?></div>
                                                 <div class="py-1 text-xs text-green font-medium" style="font-weight:normal"><?=Yii::$app->getTable->datetime_format($value['date_time'])?></div>
                                                <div class="text-gray-600 text-xs mt-0.5"><?=$msg?></div>
                                            </div>

                                        </div>
                                    </div>
                                    <?php
}
} else {
	?>
                                  <div class="col-span-12 sm:col-span-6 xl:col-span-4 intro-y">

     <div class="box px-4 py-4 mb-3 flex items-center zoom-in">
                                    <h4 class="text-md text-theme-6 text-center font-medium truncate mr-5">
                                        <?=Yii::t('app', 'No Recent Activity Found Yet!')?>
                                    </h4>
                                </div>
                              </div>
  <?php
}
?>
                                </div>
                            </div>
<?php Pjax::end();?>


<?php
if ($type != 'dashboard') {
	?>
  <br clear="all">
<div class="intro-y col-span-12 flex flex-wrap sm:flex-row sm:flex-nowrap items-center">
                   <?php
echo LinkPager::widget([
		'pagination' => $pages,
		'linkOptions' => ['class' => 'pagination__link'],
		'disabledPageCssClass' => 'pagination__link',
	]);
	?>
</div>
<?php
}
?>

<style type="text/css">
  th
  {
    text-align: center;
  }
</style>


<?php
use app\models\User;
use yii\widgets\LinkPager;
use yii\widgets\Pjax;

$this->title = 'All Notifications';

$notify = Yii::$app->notify;

?>
 <?php Pjax::begin();?>

<div class="col-span-12 xl:col-span-12 mt-6">
                                <div class="intro-y flex items-center h-10">
                                    <h2 class="text-lg font-medium truncate mr-5">
                                        <?=Yii::t('app', $this->title)?>
                                    </h2>
                                </div>
                                <div class="mt-5">
                                   <?php foreach ($model as $key => $value) {
	$userDetail = User::find()->where(['id' => $value['user_id']])->one();

	?>

                                    <div class="intro-y" href="javascript:void(0)" onclick="getRead(<?=$value['id']?>);">
                                        <div class="box px-4 py-4 mb-3 flex items-center zoom-in">
                                            <div class="w-10 h-10 flex-none image-fit rounded-md overflow-hidden">
                                                <img alt="<?=$userDetail['first_name'] . ' ' . $userDetail['last_name']?>" src="<?=$userDetail->getThumbnailImage()?>">
                                            </div>
                                            <div class="ml-4 mr-auto">
                                                <div class="font-medium" style="font-weight:<?=($value['read'] == 1) ? 'bold' : 'normal';?>"><?=$userDetail['first_name'] . ' ' . $userDetail['last_name']?></div>
                                                <div class="text-gray-600 text-xs mt-0.5" style="font-weight:<?=($value['read'] == 1) ? 'bold' : 'normal';?>"><?=$value['message']?></div>
                                            </div>
                                            <div class="py-1 px-2 rounded-full text-xs bg-theme-9 text-white cursor-pointer font-medium" style="font-weight:<?=($value['read'] == 1) ? 'bold' : 'normal';?>"><?=date('d M Y H:i a', strtotime($value['create_date']))?></div>
                                        </div>
                                    </div>
                                    <?php
}
?>
                                </div>
                            </div>
<?php Pjax::end();?>

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
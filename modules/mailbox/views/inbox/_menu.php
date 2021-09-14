<?php
use app\modules\mailbox\models\EmailFolder;
$session = Yii::$app->session;
if (isset($session['UserEmail'])) {
	$user_email_id = $session['UserEmail'];

} else {
	$user_email_id = 0;
}
$EmailFolder = EmailFolder::find()->where(['user_email_id' => $user_email_id])->andWhere(['status' => 1])->all();
?>

<div class="intro-y box bg-theme-1 p-5 mt-6">
			<!-- <button type="button" class="button  text-gray-700 dark:text-gray-300 w-full bg-white dark:bg-theme-1 mt-1" data-toggle="modal" data-target="#header-footer-modal-preview">Compose </button> -->
			<a href="javascript:void(0)" class="button text-gray-700 dark:text-gray-300 w-full block bg-white dark:bg-theme-1 mt-1" data-toggle="modal" data-target="#compose-modal"><?=Yii::t('app', 'Compose')?> </a>
			<div class="border-t border-theme-3 dark:border-dark-5 mt-6 pt-6 text-white">
				<?php foreach ($EmailFolder as $key => $value) {?>
<a href="<?=Yii::getAlias('@web')?>/mailbox/inbox?act=<?=$value->name?>&fid=<?=$value->id?>" class="flex items-center px-3 py-2 rounded-md bg-theme-20 dark:bg-dark-1 text-uppercase font-medium"><?=Yii::t('app', $value->name)?>  </a>
<?php }?>

				<!-- <a href="<?=Yii::getAlias('@web')?>/mailbox/inbox" class="flex items-center px-3 py-2 rounded-md bg-theme-20 dark:bg-dark-1 font-medium"> <i class="w-4 h-4 mr-2" data-feather="mail"></i><?=Yii::t('app', 'Inbox')?>  </a>
				<a href="<?=Yii::getAlias('@web')?>/mailbox/inbox/?act=marked" class="flex items-center px-3 py-2 mt-2 rounded-md"> <i class="w-4 h-4 mr-2" data-feather="star"></i><?=Yii::t('app', 'Marked')?>  </a>
				<a href="<?=Yii::getAlias('@web')?>/mailbox/inbox/?act=important" class="flex items-center px-3 py-2 mt-2 rounded-md"> <i class="w-4 h-4 mr-2" data-feather="bookmark"></i> <?=Yii::t('app', 'Important')?> </a>
				<a href="<?=Yii::getAlias('@web')?>/mailbox/inbox/?act=sent" class="flex items-center px-3 py-2 mt-2 rounded-md"> <i class="w-4 h-4 mr-2" data-feather="send"></i> <?=Yii::t('app', 'Sent')?> </a>
				<a href="<?=Yii::getAlias('@web')?>/mailbox/inbox/?act=trash" class="flex items-center px-3 py-2 mt-2 rounded-md"> <i class="w-4 h-4 mr-2" data-feather="trash"></i> <?=Yii::t('app', 'Trash')?> </a> -->
			</div>
			<!-- <div class="border-t border-theme-3 dark:border-dark-5 mt-4 pt-4 text-white">
				<a href="" class="flex items-center px-3 py-2 truncate">
					<div class="w-2 h-2 bg-theme-11 rounded-full mr-3"></div>
					Custom Work
				</a>
				<a href="" class="flex items-center px-3 py-2 mt-2 rounded-md truncate">
					<div class="w-2 h-2 bg-theme-9 rounded-full mr-3"></div>
					Important Meetings
				</a>
				<a href="" class="flex items-center px-3 py-2 mt-2 rounded-md truncate">
					<div class="w-2 h-2 bg-theme-12 rounded-full mr-3"></div>
					Work
				</a>
				<a href="" class="flex items-center px-3 py-2 mt-2 rounded-md truncate">
					<div class="w-2 h-2 bg-theme-11 rounded-full mr-3"></div>
					Design
				</a>
				<a href="" class="flex items-center px-3 py-2 mt-2 rounded-md truncate">
					<div class="w-2 h-2 bg-theme-6 rounded-full mr-3"></div>
					Next Week
				</a>
				<a href="" class="flex items-center px-3 py-2 mt-2 rounded-md truncate"> <i class="w-4 h-4 mr-2" data-feather="plus"></i> Add New Label </a>
			</div> -->
		</div>
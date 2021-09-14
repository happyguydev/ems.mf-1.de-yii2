<?php
use app\modules\mailbox\models\UserEmail;
$user_email = UserEmail::find()->where(['user_id' => Yii::$app->user->identity->id])->andWhere(['status' => 1])->all();
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
$del = 'delete';
if ($file == 'list') {
	$title = ($act == '') ? 'Inbox' : ucfirst($act);
	$this->title = Yii::t('app', $title);
	$this->params['breadcrumbs'][] = $this->title;
	$del = ($act == 'trash') ? 'remove' : 'delete';
} elseif ($file == 'view') {
	$this->title = $model->subject;
	$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Inbox'), 'url' => ['index']];
	$this->params['breadcrumbs'][] = $this->title;
} else {
	$this->title = Yii::t('app', 'Inbox');
	$this->params['breadcrumbs'][] = $this->title;
}
$session = Yii::$app->session;
$user_email_id = $session['UserEmail'];

?>
<style type="text/css">
	.blink_me {
  animation: blinker 2s linear infinite;
}

@keyframes blinker {
  50% {
    opacity: 0;
  }
}
</style>
<div class=" bg-theme-9 text-white alert alert-secondary update-msg hide" role="alert">
<p class="blink_me"><?=Yii::t('app', 'Mailbox is updating.. please wait for a while.')?></p>
</div>



<div class="grid grid-cols-12 gap-6  mt-8">
	<div class="col-span-12 lg:col-span-3 xxl:col-span-2">
		<h2 class="intro-y text-lg font-medium mr-auto mt-2 truncate w-5 sm:w-auto">
                           <?=$this->title?>
                        </h2>
		<!-- BEGIN: Inbox Menu -->
<?=$this->render('_menu');?>
		<!-- END: Inbox Menu -->
	</div>
	<div class="col-span-12 lg:col-span-9 xxl:col-span-10">
		<div class="intro-y flex flex-col-reverse sm:flex-row items-center">
			<div class="w-full sm:w-auto relative mr-auto mt-3 sm:mt-0">


			<div class="w-full sm:w-auto flex">
				<select class="tail-select w-full" onchange="setUserEmail(this.value)">
					<?php foreach ($user_email as $ue) {?>

					<option value="<?=$ue->id?>" <?php if ($ue->id == $user_email_id) {echo 'selected';}?>><?=$ue->email?></option>
				<?php }?>
				</select>
				<a href="<?=Yii::getAlias('@web')?>/mailbox/user-email" class="dropdown-toggle button px-2 box text-gray-700 dark:text-gray-300 block ml-2">
					<span class="w-5 h-5 flex items-center justify-center">
						<i class="w-4 h-4" data-feather="plus"></i>
					</span>
					 </a>
			</div>
		</div>
		<div class="w-full sm:w-auto flex">
				<a  onclick="refreshNow()" class="dropdown-toggle button px-2 box text-gray-700 dark:text-gray-300 block ml-2">
					<span class="w-5 h-5 flex items-center justify-center">
						<i class="w-4 h-4" data-feather="refresh-ccw"></i>
					</span>
					 </a>
			</div>
		</div>

		<!-- BEGIN: Inbox Content -->
		<div class="intro-y inbox box mt-5">
			<div class="p-5 flex flex-col-reverse sm:flex-row text-gray-600 border-b border-gray-200 dark:border-dark-1">
				<div class="flex items-center mt-3 sm:mt-0 border-t sm:border-0 border-gray-200 pt-5 sm:pt-0 mt-5 sm:mt-0 -mx-5 sm:mx-0 px-5 sm:px-0">
					<?php if ($file == 'list') {?>
					<input class="form-check-input" type="checkbox" id="checkAll">
					<!-- <h2></h2> -->


					<a href="javascript:updateAll('star')" class="w-5 h-5 ml-4  flex items-center justify-center dark:text-gray-300"> <i class="w-4 h-4" data-feather="star"></i> </a>
					<a href="javascript:updateAll('bookmark')" class="w-5 h-5 ml-4  flex items-center justify-center dark:text-gray-300"> <i class="w-4 h-4" data-feather="bookmark"></i> </a>

					<a href="javascript:updateAll('<?=$del?>')" class="w-5 h-5 ml-5 flex items-center justify-center dark:text-gray-300"> <i class="w-4 h-4" data-feather="trash"></i> </a>
				<?php }?>
				<?php if ($file == 'view') {?>
						<div class="w-64 sm:w-auto truncate" > <?=$this->title?></div>
					<?php }?>

				</div>

				<div class="flex items-center sm:ml-auto">
					<?php if ($file == 'list') {
	$prev = $page - 1;
	$next = $page + 1;
	$start = ($page - 1) * $limit + 1;
	$end = $page * $limit;
	$end = ($end > $total) ? $total : $end;
	$disable_next = ($end >= $total) ? 'disable-link' : '';
	$disable_prev = ($page <= 1) ? 'disable-link' : '';
	$start = ($total == 0) ? 0 : $start;
	?>
					<div class="dark:text-gray-300"><?=$start?> - <?=$end?> of <?=number_format($total, 0, '', ',')?></div>
					<a href="<?=Yii::getAlias('@web')?>/mailbox/inbox/?act=<?=$act?>&page=<?=$prev?>" class="w-5 h-5 ml-5 flex items-center justify-center dark:text-gray-300 <?=$disable_prev?>"> <i class="w-4 h-4" data-feather="chevron-left"></i> </a>
					<a href="<?=Yii::getAlias('@web')?>/mailbox/inbox/?act=<?=$act?>&page=<?=$next?>" class="w-5 h-5 ml-5 flex items-center justify-center dark:text-gray-300 <?=$disable_next?>"> <i class="w-4 h-4" data-feather="chevron-right"></i> </a>
				<?php }?>
					<!-- <a href="javascript:;" class="w-5 h-5 ml-5 flex items-center justify-center dark:text-gray-300"> <i class="w-4 h-4" data-feather="settings"></i> </a> -->
				</div>
			</div>



			<div class="overflow-x-auto sm:overflow-x-visible">
				<?php if ($file == 'list') {
	?>
					<?php if ($total == 0) {?>
						<div class="intro-y p-5 justify-center items-center text-center" >
							<p><?=Yii::t('app', 'Your list is empty.')?></p>
						</div>


	<?php }?>
				<?=$this->render('_list', ['model' => $model, 'act' => $act])?>
			<?php } elseif ($file == 'view') {?>
				<?php $getTable = Yii::$app->getTable;?>
				<div class="flex border-b border-gray-200 dark:border-dark-1">
		<div class="w-full sm:w-auto relative mr-auto mt-3 sm:mt-0 p-5">
			<p><?=$model->email_from?></p>
			<p style="font-size: 14px">to: <?=$model->email_to?></p>
		</div>
		<div class="w-full sm:w-auto flex p-5">
			<p><?=$getTable->datetime_format($model->udate, 1);?></p>
		</div>

	</div>
				<iframe border=0 src="<?=Yii::getAlias('@web')?>/mailbox/inbox/iframe-view?id=<?=$model->id?>" style="width:100%;min-height: 70vh;" onload="resizeIframe(this)"></iframe>
			<?php }?>
			</div>
			<hr/>
			<!-- <div class="p-5 flex flex-col sm:flex-row items-center text-center sm:text-left text-gray-600">
				<div class="dark:text-gray-300">4.41 GB (25%) of 17 GB used Manage</div>
				<div class="sm:ml-auto mt-2 sm:mt-0 dark:text-gray-300">Last account activity: 36 minutes ago</div>
			</div> -->
		</div>
		<!-- END: Inbox Content -->
				<!-- BEGIN: Compose Modal Begin -->
			<div id="compose-modal" class="modal" style="z-index: 50000 !important;width:800px">

				<?=$this->render('_compose')?>
			</div>
			<!-- END: Compose Modal -->

		<script type="text/javascript">
  function resizeIframe(iframe) {
    iframe.height = iframe.contentWindow.document.body.scrollHeight + "px";
  }

function setUserEmail(id) {
    window.location = "<?=Yii::getAlias('@web')?>/mailbox/inbox/set-user-email?id=" + id;
}
function refreshNow() {
	$(".update-msg").removeClass('hide');
    $.ajax({
        url: "<?=Yii::getAlias('@web')?>/mailbox/inbox/read-all-mails?force=1",
        success: function(data) {
$(".update-msg").addClass('hide');
window.location.reload();
        },
        error: function () {
        	window.location.reload();
        }
    })
}
</script>
<style type="text/css">
	.disable-link{
		pointer-events: none;
  cursor: default;
  color: #ccc;
	}
</style>
<?php

use app\modules\mailbox\models\UserEmail;
$user_email = UserEmail::find()->where(['user_id' => Yii::$app->user->identity->id])->andWhere(['status' => 1])->all();

$this->title = Yii::t('app', 'Inbox');
$this->params['breadcrumbs'][] = $this->title;
$session = Yii::$app->session;
$user_email_id = $session['UserEmail'];
?>
<div class=""></div>
<div style="position: relative;">
			<div class="w-full sm:w-auto flex select-email" style="">
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

	<iframe id="frame1" border=0 src="<?=Yii::getAlias('@web')?>/i/index.php?e=<?=$email?>&p=<?=$password?>" style="width:100%;min-height: calc(100vh - 100px);" onload="resizeIframe(this)"></iframe>
	<script type="text/javascript">
		 function resizeIframe(iframe) {
    iframe.height = iframe.contentWindow.document.body.scrollHeight + "px";

    var cssLink = document.createElement("link");
cssLink.href = "<?=Yii::getAlias('@web')?>/css/mail.css";
cssLink.rel = "stylesheet";
cssLink.type = "text/css";
iframe.contentWindow.document.body.appendChild(cssLink);
  }


function setUserEmail(id) {
    window.location = "<?=Yii::getAlias('@web')?>/inbox/set-user-email?id=" + id;
}

	</script>
	<style type="text/css">
		.select-email{
			position: absolute;right:20px;top:5px;max-width: 300px;
		}
		@media (max-width:768px){
			.select-email{
				max-width: 200px;
				top: -50px;
				right: 80px;
			}
		}
	</style>
<?php
use app\models\User;
use app\modules\admin\models\LogoSetting;
use app\widgets\Breadcrumb;
$user_role = Yii::$app->user->identity->user_role;
$notify = \Yii::$app->notify;
$lan = ['en' => 'English', 'de' => 'German'];
$selectedLanguage = \Yii::$app->getRequest()->getCookies()->getValue('lang');
$user = Yii::$app->user->identity;
if ($user->profile_picture != '') {
	$user_image = Yii::getAlias('@web') . '/web/profile/' . $user->id . '/' . $user->profile_picture;
} else {
	$user_image = Yii::getAlias('@web') . '/web/profile/default.jpg';
}
$site_name = Yii::$app->getTable->settings('general', 'site_name');

$logo_setting = LogoSetting::find()->where(['setting_name' => 'Logo'])->one();
//$chat_modal = new Chat;
//$unread_chat_count = $chat_modal->unreadCountAll();
$role = $user_role == 'admin' ? $user_role : '';
?>

<!-- BEGIN: Top Bar -->
<div class="top-bar-boxed border-b border-theme-2 -mt-7 md:-mt-5 -mx-3 sm:-mx-8 px-3 sm:px-8 md:pt-0 mb-12">
  <div class="h-full flex items-center">
  <!-- BEGIN: Breadcrumb -->
   <a href="<?=Yii::getAlias('@web')?>/<?=$role?>" class="-intro-x hidden md:flex">
                    <img alt="EMS" class="w-6" src="<?=Yii::getAlias('@web')?>/web/logo/<?=$logo_setting->setting_value?>?v=<?=time()?>" style="width:<?=$logo_setting->setting_size?>px">

                </a>
<!--                 <div class="side-nav__devider my-6"></div>
 -->  <div class="-intro-x breadcrumb mr-auto">
    <?=Breadcrumb::widget([
	'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
])?>
  </div>
  <div id="notification" style="position: relative;">
    <!-- END: Breadcrumb -->
    <!-- BEGIN: Notifications -->
  </div>
  <!-- END: Notifications -->
  <!-- BEGIN: Language -->
  <?php if (\Yii::$app->getRequest()->getCookies()->has('lang')) {
	if ($selectedLanguage == 'de') {
		$language = '<img src="' . Yii::getAlias('@web') . '/web/flag/germany.png" width="30" style="height:20px" class="dropdown-toggle tooltip" title="German" aria-expanded="false"/> ';
	} else {
		$language = '<img src="' . Yii::getAlias('@web') . '/web/flag/usa.png" width="30" style="height:20px" class="dropdown-toggle tooltip" title="English" aria-expanded="false"/> ';
	}
} else {
	$language = '<img src="' . Yii::getAlias('@web') . '/web/flag/usa.png" width="30" style="height:20px" class="dropdown-toggle tooltip" title="English" aria-expanded="false"/> ';
}
?>
  <div class="intro-x mr-6">
    <div class="dropdown inline-block mt-2" data-placement="bottom-start"><?=$language?><!-- <i data-feather="chevron-down" class="w-4 h-4 ml-2"></i> -->
    <div class="dropdown-menu w-48" style="width:150px">
      <div class="dropdown-menu__content box dark:bg-dark-1 p-2">
        <?php foreach ($lan as $lc => $lv) {
	$flag_img = $lv == 'German' ? 'germany.png' : 'usa.png';
	?>
        <a href="javascript:void(0)" onclick="getLanguage(this.id);" id="<?=$lc?>" class="flex items-center block p-2 transition duration-300 ease-in-out bg-white dark:bg-dark-1 hover:bg-gray-200 dark:hover:bg-dark-2 rounded-md">
          <img src="<?=Yii::getAlias('@web')?>/web/flag/<?=$flag_img?>" width="20" class="w-6 h-4 mr-2"/>
          <?=$lv?>
        </a>
        <?php }?>
      </div> </div>
    </div>
  </div>
  <!-- END: Language -->
  <!-- BEGIN: Account Menu -->
  <div class="intro-x dropdown w-8 h-8">
    <div class="dropdown-toggle w-8 h-8 rounded-full overflow-hidden shadow-lg image-fit zoom-in">
      <img alt="<?=$user['first_name'] . ' ' . $user['last_name']?>" src="<?=$user_image?>" onerror="this.onerror=null;this.src='<?=Yii::getAlias('@web')?>/web/profile/default.jpg'">
    </div>
    <div class="dropdown-menu w-56">
      <div class="dropdown-menu__content box dark:bg-dark-1">
        <div class="p-4 border-b border-gray-200 dark:border-dark-5">
          <div class="font-medium"><?=$user['first_name'] . ' ' . $user['last_name']?></div>
          <div class="text-xs text-theme-41 mt-0.5 dark:text-gray-600"><?=$user['email']?></div>
        </div>
        <div class="p-2">
          <a href="<?=Yii::getAlias('@web')?>/user/view" class="flex items-center block p-2 transition duration-300 ease-in-out bg-white dark:bg-dark-1 hover:bg-gray-200 dark:hover:bg-dark-2 rounded-md"> <i data-feather="user" class="w-4 h-4 mr-2"></i> <?=Yii::t('app', 'Profile')?> </a>
          <a href="<?=Yii::getAlias('@web')?>/site/change-password" class="flex items-center block p-2 transition duration-300 ease-in-out bg-white dark:bg-dark-1 hover:bg-gray-200 dark:hover:bg-dark-2 rounded-md"> <i data-feather="lock" class="w-4 h-4 mr-2"></i> <?=Yii::t('app', 'Change Password')?> </a>
        </div>
        <div class="p-2 border-t border-theme-40 dark:border-dark-3">
          <a href="javascript:void(0)" onclick="logout()" class="flex items-center block p-2 transition duration-300 ease-in-out bg-white dark:bg-dark-1 hover:bg-gray-200 dark:hover:bg-dark-2 rounded-md"> <i data-feather="toggle-right" class="w-4 h-4 mr-2"></i> <?=Yii::t('app', 'Logout')?> </a>
        </div>
      </div>
    </div>
  </div>
  <!-- END: Account Menu -->
</div>
</div>
<!-- END: Top Bar -->
<script type="text/javascript">
function getRead(id) {
$.get("<?=Yii::getAlias('@web');?>/site/notify-read?id="+id,function(data) {
window.location = data;
})
}
updateNotification();
function updateNotification() {
$('.notification-content').css('cssText', 'display: none !important');
$.ajax({
url: '<?=Yii::getAlias('@web')?>/site/get-notifications',
success:function(response) {
$('#notification').html(response);
}
})
}
setInterval('updateNotification()', 10*1000);
</script>
<style type="text/css">
@media (max-width: 768px){
#notification{
padding-right: 10px;
}
}
.dark-mode-switcher{
bottom:-30px;
}
</style>
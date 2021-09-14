<?php
use app\modules\chat\models\Chat;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
$user = Yii::$app->user->identity;
$action = Yii::$app->controller->action->id;
if ($user->profile_picture != '') {
	$user_image = Yii::getAlias('@web') . '/web/profile/' . $user->id . '/' . $user->profile_picture;
} else {
	$user_image = Yii::getAlias('@web') . '/web/profile/default.jpg';
}
$chat_modal = new Chat;

$chat_id = isset($_GET['id']) ? $_GET['id'] : 0;
if ($action == 'group') {
	$g_nav_act = "active";
	$c_nav_act = "";
} else {
	$g_nav_act = "";
	$c_nav_act = "active";
}
?>
<div class="intro-y pr-1">
  <div class="box p-2">
    <div class="chat__tabs nav nav-tabs justify-center flex"  role="tablist">
      <a data-toggle="tab" data-target="#chats" href="javascript:;" class="flex-1 py-2 rounded-md text-center  <?=$c_nav_act?>" id="chats-tab" role="tab" aria-controls="chats" aria-selected="true">
        <?=Yii::t('app', 'Chats')?>
      </a>
      <a data-toggle="tab" data-target="#friends" href="javascript:;" class="flex-1 py-2 rounded-md text-center" id="friends-tab" role="tab" aria-controls="friends" aria-selected="false">
         <?=Yii::t('app', 'Users')?>
      </a>
      <a data-toggle="tab" data-target="#groups" href="javascript:;" class="flex-1 py-2 rounded-md text-center <?=$g_nav_act?>" id="groups-tab" role="tab" aria-controls="groups" aria-selected="false">
       <?=Yii::t('app', 'Groups')?>
      </a>
    </div>
  </div>
</div>
<div class="pr-1">
  <div class="box px-5 pt-5 pb-5 mt-5">
    <div class="relative text-gray-700 dark:text-gray-300">
      <input id="searchbox"  type="text" class="form-control py-3 px-4 border-transparent bg-gray-200 pr-10 placeholder-theme-13" placeholder="<?=Yii::t('app', 'Search here')?>...">
  </div>
</div>
</div>
<div class="tab-content">
<div class="tab-pane <?=$c_nav_act?>" id="chats" role="tabpanel" aria-labelledby="chats-tab">
  <div class="chat__chat-list overflow-y-auto scrollbar-hidden pr-1 pt-1 mt-4">
    <div class="mylist"></div>
  </div>
</div>
<div class="tab-pane" id="friends" role="tabpanel" aria-labelledby="friends-tab">
  <div class="chat__chat-list overflow-y-auto scrollbar-hidden pr-1 pt-1 mt-4">
    <div class="friendlist"></div>
  </div>
</div>
<div class="tab-pane <?=$g_nav_act?>" id="groups" role="tabpanel" aria-labelledby="groups-tab">
  <div style="clear: both"></div>
  <div class="pr-1" style="max-width: 100%">
    <div class="box p-5 mt-5">
      <div class="relative text-gray-700 dark:text-gray-300">
        <input type="text" class="form-control py-3 px-4 border-transparent bg-gray-200 pr-10 placeholder-theme-13" placeholder="<?=Yii::t('app', 'Enter group name')?> ..." id="group-name">
    </div>
    <button type="button" class="button bg-theme-1 w-full mt-3 text-white" style="color:#ffffff" onclick="createGroup()">
      <?=Yii::t('app', 'Create Group')?>
      </button>
  </div>
</div>
<div class="chat__chat-list overflow-y-auto scrollbar-hidden pr-1 pt-1 mt-4">
  <div class="groupslist"></div>
</div>
</div>
</div>
<script type="text/javascript">
updateUsers();
function updateUsers() {
var id = "<?=$chat_id?>";
jQuery.ajax({
method: "GET",
url: "<?=Yii::getAlias('@web')?>/chat/index/user-list?id="+id,
cache: false,
success: function(data1)
{
if(data1)
{
var data = JSON.parse(data1);
$(".mylist").html(data.chat);
$(".friendlist").html(data.friends);
$(".groupslist").html(data.groups);
}
}
});
}
function updateCounter() {
updateUsers();
$(".unread_count").load(" .unread_count > *");
}
setInterval('updateCounter()', 10*1000); // refresh div after 30 secs

function createGroup() {
  var gname = $("#group-name").val();
  if(gname != ''){
  window.location =  "<?=Yii::getAlias('@web')?>/chat/index/create-group?name="+gname;
}
}
</script>
<style type="text/css">
</style>
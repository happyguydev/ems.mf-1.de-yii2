<?php
use app\modules\chat\models\Chat;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->title = Yii::t('app', 'Chat');
$this->params['breadcrumbs'][] = $this->title;
$user = Yii::$app->user->identity;
if ($user->profile_picture != '') {
	$user_image = Yii::getAlias('@web') . '/web/profile/' . $user->id . '/' . $user->profile_picture;
} else {
	$user_image = Yii::getAlias('@web') . '/web/profile/default.jpg';
}
$chat_modal = new Chat;
{
	$in_nav_act = "active";
	$an_nav_act = "";
	$head_text = "Conversation with";
	$anonymous_checked = "";
}
?>
<!-- <div class="row">
  <div class="col-md-12">
    <ul class="nav nav-pills mynave_list">
      <li role="presentation" class="<?=$in_nav_act?>">
        <a href="<?=Yii::getAlias('@web')?>/chat/index/index">Inbox <span class="badge unread_count" style="background-color: #5cb85c;color:#fff"><?=$chat_modal->unreadCounter(0)?></span></a></li>
        <li role="presentation" class="<?=$an_nav_act?>"><a href="<?=Yii::getAlias('@web')?>/chat/index/index?anonymous=1">Anonymous Box <span class="badge unread_count" style="background-color: #5cb85c;color:#fff"><?=$chat_modal->unreadCounter(1)?></span></a></li>
      </ul>
    </div>
  </div>
  <br/> -->
  <div class="intro-y chat grid grid-cols-12 gap-5 mt-5">
    <div class="col-span-12 lg:col-span-4 xxl:col-span-3">
      <div class="intro-y pr-1">
        <div class="box p-2">
          <div class="chat__tabs nav nav-tabs justify-center flex"  role="tablist">
            <a data-toggle="tab" data-target="#chats" href="javascript:;" class="flex-1 py-2 rounded-md text-center active" id="chats-tab" role="tab" aria-controls="chats" aria-selected="true">Chats <span class="badge unread_count" style="background-color: #5cb85c;color:#fff"><?=$chat_modal->unreadCounter(0)?></span></a>
            <a data-toggle="tab" data-target="#friends" href="javascript:;" class="flex-1 py-2 rounded-md text-center" id="friends-tab" role="tab" aria-controls="friends" aria-selected="false">Users</a>
            <a data-toggle="tab" data-target="#profile" href="javascript:;" class="flex-1 py-2 rounded-md text-center" id="profile-tab" role="tab" aria-controls="profile" aria-selected="false">Profile</a>
            <!-- <a  href="<?=Yii::getAlias('@web')?>/chat/index/index" class="flex-1 py-2 rounded-md text-center active">Chats <span class="badge unread_count" style="background-color: #5cb85c;color:#fff"><?=$chat_modal->unreadCounter(0)?></span></a>
            <a  href="javascript:;" class="flex-1 py-2 rounded-md text-center">Friends</a> -->
          </div>
        </div>
      </div>
      <div class="pr-1">
        <div class="box px-5 pt-5 pb-5 mt-5">
          <div class="relative text-gray-700 dark:text-gray-300">
            <input id="searchbox"  type="text" class="form-control py-3 px-4 border-transparent bg-gray-200 pr-10 placeholder-theme-13" placeholder="Search here...">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search w-4 h-4 hidden sm:absolute my-auto inset-y-0 mr-3 right-0"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
        </div>
      </div>
    </div>
    <!-- <input placeholder="Search here..." id="searchbox" type="text" class="form-control" />
    <br/> -->
    <div class="tab-content">
      <div class="tab-content__pane active" id="chats" role="tabpanel" aria-labelledby="chats-tab">
        <div class="chat__chat-list overflow-y-auto scrollbar-hidden pr-1 pt-1 mt-4">

         <!--  <div class="intro-x cursor-pointer box relative flex items-center p-5 ">
            <div class="w-12 h-12 flex-none image-fit mr-1">
              <img alt="Midonee" class="rounded-full" src="dist/images/profile-1.jpg">
            </div>
            <div class="ml-2 overflow-hidden">
              <div class="flex items-center">
                <a href="javascript:;" class="font-medium">Leonardo DiCaprio</a>
              </div>
              <div class="w-full truncate text-gray-600 mt-0.5">There are man</div>
            </div>
            <div class="w-5 h-5 flex items-center justify-center absolute top-0 right-0 text-xs text-white rounded-full bg-theme-1 font-medium -mt-1 -mr-1">5</div>
          </div> -->



            <div class="mylist"></div>

        </div>


      </div>
      <div class="tab-content__pane" id="friends" role="tabpanel" aria-labelledby="friends-tab">
        <div class="chat__chat-list overflow-y-auto scrollbar-hidden pr-1 pt-1 mt-4">
          <div class="friendlist"></div>
        </div>
      </div>
    </div>
  </div>
  <!--  <div class="row">
    <div class="col-md-3">
      <input placeholder="Search here..." id="searchbox" type="text" class="form-control" />
      <br/>
      <div class="list-group ">
        add list of users inside div -->
        <!-- <div class="mylist">
        </div>
      </div>
    </div> -->
    <!-- end col-md-3 -->
    <div class="intro-y col-span-12 lg:col-span-8 xxl:col-span-9">
      <div class="chat__box box">
        <!-- BEGIN: Chat Active -->
        <!-- END: Chat Active -->
        <!-- BEGIN: Chat Default -->
        <div class="h-full flex items-center">
          <div class="mx-auto text-center">
            <div class="w-16 h-16 flex-none image-fit rounded-full overflow-hidden mx-auto">
              <img alt="<?=$user->name?>" src="<?=$user_image?>">
            </div>
            <div class="mt-3">
              <div class="font-medium">Hey, <?=$user->name?>!</div>
              <div class="text-gray-600 mt-1">Please select a chat to start messaging.</div>
            </div>
          </div>
        </div>
        <!-- END: Chat Default -->
      </div>
    </div>
  </div>
  <script type="text/javascript">
  updateUsers();
  function updateUsers() {
  var id = 0;
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
  }
  }
  });
  }
  function getEdit(id) {
  var person = prompt("Please enter a name", "");
  str = person.replace(/\s/g, '');
  if (str != "") {
  jQuery.ajax({
  method: "GET",
  url: "<?=Yii::getAlias('@web')?>/chat/index/username-update?id="+id+"&name="+person,
  cache: false,
  success: function(data)
  {
  if(data)
  {
  $("#user_name"+id).html(person);
  }
  }
  });
  }
  return false;
  }
  function updateCounter() {
  $(".mynave_list").load(document.URL + " .mynave_list");
  }
  setInterval('updateCounter()', 30*1000); // refresh div after 30 secs
  </script>
  <style type="text/css">
  .chat-body {
  display: block;
  margin: 10px 30px 0 0;
  overflow: hidden;
  }
  .chat-right{
  text-align: right;
  }
  .chat-time {
  display: block;
  margin-top: 8px;
  color: rgba(255,255,255,.6);
  }
  .chat-content {
  position: relative;
  display: block;
  float: right;
  padding: 8px 15px;
  margin: 0 20px 10px 0;
  clear: both;
  color: #fff;
  background-color: #62a8ea;
  border-radius: 4px;
  }
  .chat-left .chat-body {
  margin-right: 0;
  margin-left: 30px;
  }
  .chat-left .chat-content {
  float: left;
  margin: 0 0 10px 20px;
  color: #76838f;
  background-color: #dfe9ef;
  }
  .chat-left .chat-time {
  color: #a3afb7;
  }
  .chat-content:before {
  position: absolute;
  top: 10px;
  right: -10px;
  width: 0;
  height: 0;
  content: '';
  border: 5px solid transparent;
  border-left-color: #62a8ea;
  }
  .chat-left .chat-content:before {
  right: auto;
  left: -10px;
  border-right-color: #dfe9ef;
  border-left-color: transparent;
  }
  .user_edit{
  z-index: 20;
  float: right;
  cursor: pointer;
  }
  </style>
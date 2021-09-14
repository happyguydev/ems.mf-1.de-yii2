<?php
use app\modules\chat\models\Chat;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->title = Yii::t('app', 'Chat');
$this->params['breadcrumbs'][] = $this->title;

$luser = Yii::$app->user->identity;
if ($user->profile_picture != '') {
	$user_image = Yii::getAlias('@web') . '/web/profile/' . $luser->id . '/' . $luser->profile_picture;
} else {
	$user_image = Yii::getAlias('@web') . '/web/profile/default.jpg';
}
$chat_modal = new Chat;

{
	$in_nav_act = "active";
	$an_nav_act = "";
	$head_text = "Conversation with";
}
?>

<!--
<div class="row">
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
          <div class="chat__tabs nav nav-tabs justify-center" style="display: flex">
            <a  href="<?=Yii::getAlias('@web')?>/chat/index/index" class="flex-1 py-2 rounded-md text-center active">Chats <span class="badge unread_count" style="background-color: #5cb85c;color:#fff"><?=$chat_modal->unreadCounter(0)?></span></a>
            <a  href="javascript:;" class="flex-1 py-2 rounded-md text-center">Friends</a>
          </div>
        </div>
      </div>
      <div class="chat__chat-list overflow-y-auto scrollbar-hidden pr-1 pt-1 mt-4">
         <input placeholder="Search here..." id="searchbox" type="text" class="form-control" />
         <br/>
       <div class="list-group ">
        <a class="list-group-item active ">
          <div class="media-body">
            <?php

$username = $user['username'];

?>
            <h4 class="media-heading user_name" style="color: #fff">
            <span id="user_name<?=$id?>"><?=$username?> </span>
            </h4>

          </div>

        </a>
        <!-- add list of users inside div -->
        <div class="mylist">
        </div>


      </div>
      </div>
    </div>
<!--   <div class="row">
    <div class="col-md-3">

      <input placeholder="Search here..." id="searchbox" type="text" class="form-control" />
      <br/>
      <div class="list-group ">
        <a class="list-group-item active ">
          <div class="media-body">
            <?php

$username = $user['username'];

?>
            <h4 class="media-heading user_name" style="color: #fff">
            <span id="user_name<?=$id?>"><?=$username?> </span>
            </h4>

          </div>

        </a>
        <div class="mylist">
        </div>


      </div>



    </div> -->
    <!-- end col-md-3 -->


 <div class="intro-y col-span-12 lg:col-span-8 xxl:col-span-9">
        <div class="chat__box box">
          <div class="h-full flex flex-col" style="">
    <div class="flex flex-col sm:flex-row border-b border-gray-200 dark:border-dark-5 px-5 py-4">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 sm:w-12 sm:h-12 flex-none image-fit relative">
                                            <img alt="<?=$user->name?>" class="rounded-full" src="<?=$user->thumbnailImage;?>">
                                        </div>
                                        <div class="ml-3 mr-auto">
                                            <div class="font-medium text-base"><?=$user->name?></div>
                                            <div class="text-gray-600 text-xs sm:text-sm"><?=$username?></div>
                                        </div>
                                    </div>
                                </div>
                                 <!-- Chat Box -->
                                 <div class="overflow-y-scroll scrollbar-hidden px-5 pt-5 flex-1 chats-wrap ">
         <!--  <div style="min-height:300px;max-height:350px;overflow-y: scroll;" class="chats-wrap "> -->


            <div class="chats myop" >
              <?php if (count($model) < 1) {
	echo '<h3 class="text-center no_msg">' . Yii::t('app', 'No Message Found !') . '</h3>';
} else {
	?>
              <?php foreach ($model as $k => $v) {
		if ($v['sender_id'] == Yii::$app->user->identity->id) {?>
              <div class="chat chat-left">
                <div class="chat-body">
                  <div class="chat-content" >
                    <p>
                      <?=$v['text']?>
                    </p>
                    <time class="chat-time" datetime="<?=date('H:i, d M', strtotime($v['created_at']))?>"><?=date('H:i, d M', strtotime($v['created_at']))?></time>

                  </div>

                </div>
              </div>
              <?php } else {?>
              <div class="chat">
                <div class="chat-body">
                  <div class="chat-content" >
                    <p>
                      <?=$v['text']?>
                    </p>
                    <time class="chat-time" datetime="<?=date('H:i, d M', strtotime($v['created_at']))?>"><?=date('H:i, d M', strtotime($v['created_at']))?></time>

                  </div>
                </div>
              </div>
              <?php }?>
              <?php } // end foreach ?>
              <?php } // end else ?>
            </div>

          </div>
          <div class="pt-4 pb-10 sm:py-4 flex items-center border-t border-gray-200 dark:border-dark-5">

            <input class="chat__box__input chat-input-control dark:bg-dark-3 h-16 resize-none border-transparent px-5 py-3 shadow-none focus:ring-0" type="text" placeholder="Type message here ..." id="msg" onKeyDown="if(event.keyCode==13) sendMsg(<?=$id?>);">
                                    <div class="flex absolute sm:static left-0 bottom-0 ml-5 sm:ml-0 mb-5 sm:mb-0">

                                        <div class="w-4 h-4 sm:w-5 sm:h-5 relative text-gray-600 mr-3 sm:mr-5">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-paperclip w-full h-full"><path d="M21.44 11.05l-9.19 9.19a6 6 0 0 1-8.49-8.49l9.19-9.19a4 4 0 0 1 5.66 5.66l-9.2 9.19a2 2 0 0 1-2.83-2.83l8.49-8.48"></path></svg>
                                            <input type="file" class="w-full h-full top-0 left-0 absolute opacity-0">
                                        </div>
                                    </div>
                                    <a href="javascript:;" class="w-8 h-8 sm:w-10 sm:h-10 block bg-theme-1 text-white rounded-full flex-none flex items-center justify-center mr-5" onclick="sendMsg(<?=$id?>)"> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-send w-4 h-4"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg> </a>
                                </div>
        </div>
      </div>
    </div>
</div>

 <!--    <div class="col-md-9 "> -->
     <!--  <div class="panel panel-default " style="position: relative;">
        <div class="panel-heading">
          <h6 class="panel-title">


          <?=Yii::t('app', $head_text)?> : <em><?=$username?></em>


          </h6>
        </div> -->
      <!--   <div class="panel-body ">
          <div style="min-height:300px;max-height:350px;overflow-y: scroll;" class="chats-wrap ">


            <div class="chats myop" >
              <?php if (count($model) < 1) {
	echo '<h3 class="text-center no_msg">' . Yii::t('app', 'No Message Found !') . '</h3>';
} else {
	?>
              <?php foreach ($model as $k => $v) {
		if ($v['sender_id'] == Yii::$app->user->identity->id) {?>
              <div class="chat chat-left">
                <div class="chat-body">
                  <div class="chat-content" >
                    <p>
                      <?=$v['text']?>
                    </p>
                    <time class="chat-time" datetime="<?=date('H:i, d M', strtotime($v['created_at']))?>"><?=date('H:i, d M', strtotime($v['created_at']))?></time>

                  </div>

                </div>
              </div>
              <?php } else {?>
              <div class="chat">
                <div class="chat-body">
                  <div class="chat-content" >
                    <p>
                      <?=$v['text']?>
                    </p>
                    <time class="chat-time" datetime="<?=date('H:i, d M', strtotime($v['created_at']))?>"><?=date('H:i, d M', strtotime($v['created_at']))?></time>

                  </div>
                </div>
              </div>
              <?php }?>
              <?php } // end foreach ?>
              <?php } // end else ?>
            </div>

          </div>
        </div>
        <div class="panel-footer">
          <div class="input-group" >

            <input class="form-control" type="text" placeholder="Type message here ..." id="msg" onKeyDown="if(event.keyCode==13) sendMsg(<?=$id?>);">
            <span class="input-group-btn">
              <button type="button" class="btn btn-pure btn-primary" onclick="sendMsg(<?=$id?>)">SEND</button>
            </span>
          </div>
        </div> -->


    <!--   </div>
    </div>
  </div> -->
  <script type="text/javascript">
  scrollB();
  updateUsers();
  updateContent();


  function sendMsg(r_id) {
  var msg = $("#msg").val();

  var dataString = "msg="+encodeURIComponent(msg)+"&r_id="+r_id;
  if(msg =='' ) {
  return false;
  }
  else
  {
  jQuery.ajax({
  method: "POST",
  url: "<?=Yii::getAlias('@web')?>/chat/index/send",
  data: dataString,
  cache: false,
  success: function(data)
  {
  if(data)
  {
  $(".myop").append(data);
  $(".no_msg").hide();
  $("#msg").val('');
  scrollB();
  }
  }
  });
  }
  }
  function updateContent() {

  var id = "<?=$id?>";
  jQuery.ajax({
  method: "GET",
  url: "<?=Yii::getAlias('@web')?>/chat/index/sync?id="+id,
  cache: false,
  success: function(data)
  {
  if(data)
  {
  $(".myop").append(data);
  $(".no_msg").hide();
  scrollB();
  }
  updateUsers();
  updateCounter();
  }
  });
  }
  function updateUsers() {
  var id = "<?=$id?>";
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


  setInterval('updateContent()', 10*1000); // refresh div after 10 secs
  function scrollB() {
  var height = 0;
  $('.chats-wrap .chat').each(function(i, value){
  height += parseInt($(this).height() + 10);
  });
  height += '';
  $('.chats-wrap').animate({scrollTop: height});
  }




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
  .chat-input-control{
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
    border-radius: .375rem;
    border-width: 1px;
    padding: .5rem .75rem;
    width: 100%;
  }
  .chat-input-control:focus{
    outline: 2px solid transparent;
  }
  </style>
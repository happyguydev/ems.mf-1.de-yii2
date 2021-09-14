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
?>
 <div class="intro-y chat grid grid-cols-12 gap-5 mt-5">
    <div class="col-span-12 lg:col-span-4 xxl:col-span-3">
      <?=$this->render('_users')?>

  </div>

      <div class="intro-y col-span-12 lg:col-span-8 xxl:col-span-9">
        <div class="chat__box box">
           <?=$this->render('_uploader', ['id' => $id, 'is_group' => 0]);?>
          <div class="h-full flex flex-col" style="">
            <div class="flex flex-col sm:flex-row border-b border-gray-200 dark:border-dark-5 px-5 py-4">
              <div class="flex items-center">
                <div class="w-10 h-10 sm:w-12 sm:h-12 flex-none image-fit relative">
                  <img alt="<?=$user->name?>" class="rounded-full" src="<?=$user->thumbnailImage;?>">
                </div>
                <div class="ml-3 mr-auto">
                  <div class="font-medium text-base"><?=$user->name?></div>
                  <div class="text-gray-600 text-xs sm:text-sm"><?=$user->email?></div>
                </div>
              </div>
            </div>
            <!-- Chat Box -->
            <div class="overflow-y-scroll scrollbar-hidden px-5 pt-5 flex-1 chats-wrap ">
<div class="chats myop" style="display: contents;">
                <?php if (count($model) < 1) {?>
	   <div class="h-full flex items-center no_msg">
          <div class="mx-auto text-center">
            <div class="w-16 h-16 flex-none image-fit rounded-full overflow-hidden mx-auto">
              <img alt="<?=$user->name?>" src="<?=$user_image?>">
            </div>
            <div class="mt-3">
              <div class="font-medium">Hey, <?=$luser->name?>!</div>
              <div class="text-gray-600 mt-1"><?=Yii::t('app', 'Send your first message to start the chat.')?></div>
            </div>
          </div>
        </div>
<?php } else {
	?>
                <?php foreach ($model as $k => $v) {
		echo $chat_modal->chatContentTemplate($v);
	}
	?>
                <?php } // end else ?>

</div>
            </div>
            <div class="pt-4 pb-10 sm:py-4 flex items-center border-t border-gray-200 dark:border-dark-5">
               <div id="send-area"></div>
              <input class="chat__box__input chat-input-control dark:bg-dark-3 h-16 resize-none border-transparent px-5 py-3 shadow-none focus:ring-0" type="text" placeholder="<?=Yii::t('app', 'Type message here')?>..." id="msg" onKeyDown="if(event.keyCode==13) sendMsg(<?=$id?>);">
              <div class="flex absolute sm:static left-0 bottom-0 ml-5 sm:ml-0 mb-5 sm:mb-0">
                <div class="w-4 h-4 sm:w-5 sm:h-5 relative text-gray-600 mr-3 sm:mr-5">

               <label for="userFile">
  <a class="msg_send_btn"  data-toggle="tooltip" title="Send Attachment" ><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-paperclip w-full h-full"><path d="M21.44 11.05l-9.19 9.19a6 6 0 0 1-8.49-8.49l9.19-9.19a4 4 0 0 1 5.66 5.66l-9.2 9.19a2 2 0 0 1-2.83-2.83l8.49-8.48"></path></svg></a>
  </label>
              </div>
            </div>

 <a href="javascript:;" class="w-6 h-6 sm:w-10 sm:h-10 block rounded-full flex-none flex items-center justify-center mr-5" id="recordButton" title="record voice">
 <i class="fa fa-microphone fa-2x"></i>
   </a>

            <a href="javascript:;" class="w-8 h-8 sm:w-10 sm:h-10 block bg-theme-1 text-white rounded-full flex-none flex items-center justify-center mr-5" onclick="sendMsg(<?=$id?>)"> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-send w-4 h-4"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg> </a>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script type="text/javascript">
  scrollB();
  updateUsers();
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
  setInterval('updateContent()', 1*1000); // refresh div after 30 secs

  function scrollB() {
  var height = 0;
  $('.chats-wrap .chat__box__text-box').each(function(i, value){
  height += parseInt($(this).height() + 10);
  });
  height += '';
  $('.chats-wrap').animate({scrollTop: height});
  }
  </script>
  <style type="text/css">
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
  .audio-temp{
  position: absolute;
  bottom: 10px;
  left: 10px;
  display: inline-flex;
  justify-content: center;
  align-items: center;
}
  </style>

<?=$this->render('_recorder', ['id' => $id, 'is_group' => 0]);?>
<?php
use app\modules\chat\models\Chat;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Chat');

$this->params['breadcrumbs'][] = $this->title;
$upload_path = Yii::getAlias('@web') . "/web/chat/" . $this->title . "/";
$role = Yii::$app->user->identity->user_role;

?>

<script type="text/javascript">
  $(function() {
    $.ajaxSetup({
    timeout: 45000//Time in milliseconds
});
  });
</script>
<style>


#progress-bar {background-color: #12CC1A;height:20px;color: #FFFFFF;width:0%;-webkit-transition: width .3s;-moz-transition: width .3s;transition: width .3s;}

#progress-div {border:#0FA015 1px solid;padding: 5px 0px;margin:30px 0px;border-radius:4px;text-align:center;}
#targetLayer{width:100%;text-align:center;}

</style>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.3.0/jquery.form.min.js"></script>
<script type="text/javascript">

  function sendAttachment()
  {
    $('#uploadForm').submit();
  }
$(document).ready(function() {
   $('#uploadForm').submit(function(e) {
    if($('#userFile').val()) {
      e.preventDefault();

      $(this).ajaxSubmit({
       // target:   '#targetLayer',
        beforeSubmit: function() {
          $("#progress-bar").width('0%');
          $("#progress-div").css('display','block');
           $(".p_text").css('display','block');
        },
        uploadProgress: function (event, position, total, percentComplete){
          $("#progress-bar").width(percentComplete + '%');
          $("#progress-bar").html('<div id="progress-status">' + percentComplete +' %</div>');
          if(percentComplete ==100){
               $("#progress-div").css('display','none');
                $(".p_text").css('display','none');

                 $(".data_text").css('display','block');
          }
        },
        success:function (data){

         $(".myop").append(data);
         $(".no_msg").hide();
            scrollB();
        },

        error:function (){

           $('.file_c').css('display','block');
           $(".data_text").css('display','none');


        },
        resetForm: true
      });
      return false;
    }
  });
});



</script>
<div class="row">

            <!-- end col-md-3 -->

    <div class="col-md-12 ">
      <div class="panel panel-default " style="position: relative;">

        <div class="panel-body" style="background: white;width:60%;margin-left:20%">
          <!-- Chat Box -->
          <div style="min-height:400px;max-height:450px;overflow-y: scroll;" class="chats-wrap ">


            <div class="chats myop" >
              <br/>
            <?php if (count($model) < 1) {

	echo '<h3 class="text-center no_msg">' . Yii::t('app', 'No Message Found !') . '</h3>';
} else {
	?>



            <?php
$role = Yii::$app->user->identity->user_role;

	if ($role == 'client') {
		$receiver_id = $project['user_id'];
	} else {

		$receiver_id = 1;

	}

	foreach ($model as $k => $v) {
		if ($v['sender_id'] == $receiver_id) {
			?>

                <div class="chat chat-left">


        <?=Chat::getUserProfile($v['created_by'], 'left')?>

                <div class="chat-body">
                  <div class="chat-content" >

<p>

                  <?php if ($v['is_file'] == 1) {?>
          <a href='javascript:void(0)' onclick='downloadFile("<?=$v['text'];?> ")'><?=$v['text'];?></a>
      <?php } else {
				echo $v['text'];
			}?>
                    </p>
                    <time class="chat-time" datetime="<?=date('H:i, d M', strtotime($v['created_at']));?>"><?=date('H:i, d M', strtotime($v['created_at']));?></time>

                  </div>

                </div>
              </div>
              <?php } else {
			?>

              <div class="chat">
        <?=Chat::getUserProfile($v['created_by'], 'right')?>

                <div class="chat-body">
                 <div class="chat-content" >
                    <p>
                        <?php if ($v['is_file'] == 1) {?>
          <a href='javascript:void(0)'  onclick='downloadFile("<?=$v['text'];?> ")'><?=$v['text'];?></a>
      <?php } else {
				echo $v['text'];
			}?>

                    </p>
                     <time class="chat-time" datetime="<?=date('H:i, d M', strtotime($v['created_at']));?>" ><?=date('H:i, d M', strtotime($v['created_at']));?></time>

                  </div>
                </div>
              </div>
              <?php }?>
            <?php }
	; // end foreach ?>
            <?php }
; // end else ?>




          </div>

          <form id="uploadForm" action="<?=Yii::getAlias('@web');?>/chat/open/process-upload?id=<?=$id;?>" method="post" enctype="multipart/form-data">
<div id="sendAttachment" onchange="sendAttachment()">

<input name="userFile" id="userFile" type="file" class="demoInputBox" style="display: none" />


</div>

<div class="p_text" style="display: none;">
<h3 align="center">File Uploading...</h3>
<div id="progress-div" style="display: none;"><div id="progress-bar"></div></div>
</div>
<div id="targetLayer"></div>

</form>

        </div>

         <div class="type_msg" style="padding-left:20px">
            <div class="input_msg_write">
              <input type="text" class="write_msg" placeholder="Type a message" id="msg" onKeyDown="if(event.keyCode==13) sendMsg(<?=$id;?>);" />
              <button class="msg_send_btn" type="button" data-toggle="tooltip" data-title="Send" style="right: 27px!important;top:8px;padding-right: 4px" onclick="sendMsg(<?=$id;?>)"><i class="fa fa-paper-plane" aria-hidden="true"></i></button>


<label for="userFile">
  <a class="msg_send_btn"  data-toggle="tooltip" title="Send Attachment"  style="right: 67px;top:8px; text-align: center;padding-top: 6px" ><i class="fa fa-paperclip" aria-hidden="true"></i></a>
  </label>



            </div>
          </div>
  </div>
          <!-- Message Input-->
   <!--  <div class="panel-footer" style="width: 60%;margin-left:20%">
        <div class="input-group" >

          <input class="form-control" type="text" placeholder="Type message here ..." id="msg" onKeyDown="if(event.keyCode==13) sendMsg(<?=$id;?>);">
          <span class="input-group-btn">
            <button type="button" class="btn btn-pure btn-primary" onclick="sendMsg(<?=$id;?>)">SEND</button>
          </span>
        </div>
 End Message Input-->
</div>



    </div>
  </div>

<script type="text/javascript">
scrollB();
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
            url: "<?=Yii::getAlias('@web');?>/chat/open/send?id=<?=$project['id'];?>",
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

    var id = "<?=$id;?>";

     jQuery.ajax({
            method: "GET",
            url: "<?=Yii::getAlias('@web');?>/chat/open/sync?id="+id,
            cache: false,
                success: function(data)
                {
                    if(data)
                    {
                     $(".myop").append(data);
                     $(".no_msg").hide();
                      scrollB();
                     }
                   // updateUsers();
                }
         });

  }


  setInterval('updateContent()', 10*1000); // refresh div after 60 secs

 function scrollB() {
   var height = 0;
    $('.chats-wrap .chat').each(function(i, value){
        height += parseInt($(this).height() + 30);
    });

    height += '';

    $('.chats-wrap').animate({scrollTop: height});

  }
function downloadFile(file_name) {
 window.open("<?=$upload_path;?>" + file_name);
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
    font-size: 11px;
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
    border-radius:30px 0px 30px 30px;
}

.chat-left .chat-body {
    margin-right: 0;
    margin-left: 30px;
}
.chat-left .chat-content {
    float: left;
    margin: 0 0 10px 20px;
    color: #eee;
    background-color: #777;

    border-radius: 30px 30px 30px 0px;
}
.chat-left .chat-time {
    color: #a3afb7;
}

/*.chat-content:before {
    position: absolute;
    top: 10px;
    right: -10px;
    width: 0;
    height: 0;
    content: '';
    border: 5px solid transparent;
    border-left-color: #62a8ea;
}*/
.chat-left .chat-content:before {
    right: auto;
    left: -10px;
    border-right-color: #dfe9ef;
    border-left-color: transparent;
}
.type_msg {border-top: 1px solid #c4c4c4;position: relative;}
.msg_send_btn {
  background: #05728f none repeat scroll 0 0;
  border: medium none;
  border-radius: 50%;
  color: #fff;
  cursor: pointer;
  font-size: 17px;
  height: 33px;
  position: absolute;
  right: 0;
  top: 11px;
  width: 33px;
}
.messaging { padding: 0 0 50px 0;}
.msg_history {
  height: 516px;
  overflow-y: auto;
}
.input_msg_write input {
  background: rgba(0, 0, 0, 0) none repeat scroll 0 0;
  border: medium none;
  color: #4c4c4c;
  font-size: 15px;
  min-height: 48px;
  width: 100%;
}
.chat-img
{
  width: 50px;
  height: 50px;
  border-radius: 50%;
}

.img-left
{
  float: left;
  margin-left: 25px;
    margin-top: 35px;

}

.img-right
{
  float: right;
  margin-right: 25px;
  margin-top:-15px;

}



.chat-content p a
{
  color: white!important;
}

</style>




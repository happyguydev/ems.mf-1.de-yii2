<?php
use app\models\User;

?>
<style type="text/css">
  .media-body{
    width: calc(100% - 80px);
    display: block !important;
    float: left;
    margin-left: 10px;
  }
  .media-left{
    display: block !important;
    width: 70px;
    float: left;
  }
  .media{
    border-bottom: 1px solid #dedede91;
    padding: 15px 0px;
  }
  .media-heading{
    font-weight: 600
  }
</style>
<script type="text/javascript">
function sendAttachment() {
    $('#uploadForm').submit();
}
$(document).ready(function() {
    $('#uploadForm').submit(function(e) {
        if ($('#userFile').val()) {
            e.preventDefault();
            $(this).ajaxSubmit({
                // target:   '#targetLayer',
                beforeSubmit: function() {
                    $("#progress-bar").width('0%');
                    $(".show_progress").css('display', 'block');
                    $("#progress-div").css('display', 'block');
                    $(".p_text").css('display', 'block');
                },
                uploadProgress: function(event, position, total, percentComplete) {
                    $("#progress-bar").width(percentComplete + '%');
                    $("#progress-bar").html('<div id="progress-status">' + percentComplete + ' %</div>');
                    if (percentComplete == 100) {
                        $("#progress-div").css('display', 'none');
                        $(".p_text").css('display', 'none');
                        $(".show_progress").css('display', 'none');
                    }
                },
                success: function(data) {
                    if (data) {
                        $(".myop").prepend(data);
                        $(".no_msg").hide();
                        $('.show_success').css('display', 'block');
                        $('.no_data').css('display', 'none');
                    }
                },
                resetForm: true
            });
            return false;
        }
    });
});
</script>
<link href="<?=Yii::getAlias('@web')?>/themes/common/auto-suggest/bootstrap-suggest.css" rel="stylesheet">
<script src="<?=Yii::getAlias('@web')?>/themes/common/auto-suggest/bootstrap-suggest.js"></script>

<div class=" project_page">
  <div >
    <div class="mt-5">
      <div class="project-comments">
        <div class="comments myop">
          <?php
if (count($all_comments) > 0) {
	foreach ($all_comments as $key => $value) {
		# code...
		?>
          <div class="media dis<?=$value['id']?> comment">
            <div class="media-left">
              <a href="#">
              <img alt="64x64" class="media-object avatar" src="<?=$value->getProfilePic();?>" style="width: 64px; height: 64px;"> </a>
            </div>
            <div class="media-body">
              <h4 class="media-heading"><?=$value->createUser['first_name']?> <?=$value->createUser['last_name']?></h4>
              <p class="media-heading" style="text-align: right;margin-top:-24px;font-size: 12px;font-weight: bold">
                <?php
if ($value['update_date'] == '') {
			?>
                <?=date('Y-m-d', strtotime($value['create_date']))?>, <?=date('H:i A', strtotime($value['create_date']))?>
                <?php
} else {
			?>
                <?=date('Y-m-d', strtotime($value['update_date']))?>, <?=date('H:i A', strtotime($value['update_date']))?>
                <?php
}
		?>
              </p>
              <?php
if ($value->is_file == 0) {
			?>
              <div style="float: left"><?php $str = $value['comment'];
			echo preg_replace("/\w*?" . preg_quote('@') . "\w*/i", "<b style='color:#0975d2'>$0</b>", $str);?></div>
              <?php
} else if ($value->is_file == 1) {
			?>
<div style="width:100px; float: left">
    <?php

			$ext = pathinfo($value->attach_file, PATHINFO_EXTENSION);
			$ext = strtolower($ext);

			if ($ext == 'png' || $ext == 'jpg' || $ext == 'jpeg' || $ext == 'svg') {

				?>
                <img class="img-fluid img-thumbnail w-full rounded-md" data-action="zoom" src="<?=Yii::getAlias('@web');?>/web/discussion/<?=$value->attach_file?>" style="width:80px"/>
              </div>
              <?php } else {?>

              <a href="<?=Yii::getAlias('@web');?>/web/discussion/<?=$value->attach_file?>" target="_blank">

            <img class="img-fluid img-thumbnail w-full rounded-md" src="<?=Yii::getAlias('@web');?>/uploads/media/thumb/<?=$ext;?>.png"  style="width:80px"/>
           <p class="mt-3 text-theme-1" style="font-size:12px;text-align: center;overflow: hidden;"> <?=$value->attach_file?></p>
       </a>
       <?php }?>
</div>
              <?php
} else {
			?>
              <p></p>
              <?php
}
		if ($value['create_by'] == Yii::$app->user->identity->id) {
			?>
              <div style="float: right;">
                <?php
if ($value['is_file'] == 0) {
				?>
                <a href="javascript:void(0)" onclick="editComment(<?=$value['id']?>)" class="icon md-edit btn-edit p-5" title="<?=Yii::t('app', 'Edit')?>">
                  <i class="fa fa-pencil"></i>
                </a>
                <?php
}
			?>
                <a href="javascript:void(0)" onclick="getDeleteComment(<?=$value['id']?>)"  class="icon md-delete p-5 "title="<?=Yii::t('app', 'Delete')?>">
                  <i class="fa fa-trash"></i>
                </a>
              </div>
              <?php
}
		?>
            </div>
          </div>
          <?php
}
} else {
	?>
          <p  class="no_data" style="text-align: center;font-weight: bold;font-size: 20px" style="display: block;"><?=Yii::t('app', 'No Reply Found')?>!</p>
          <?php
}
?>
        </div>
      </div>
      <div class="panel-body show_progress" style="border: none!important;display: none">
        <form id="uploadForm" action="<?=Yii::getAlias('@web');?>/task-discussion/process-upload?id=<?=$model->id;?>" method="post" enctype="multipart/form-data">
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
      <div class="panel-body show_success" style="display: none;border: none!important;padding-bottom: 0px!important;padding-top: 25px!important">
        <div class="alert alert-success"><?=Yii::t('app', 'Your comment is added successfully')?></div>
      </div>
      <div class="panel-body  project-comments">
        <div class="comments-add media mt-25">
          <form class="comment-reply" id="myForm1" action="<?=Yii::getAlias('@web');?>/task-discussion/comment?id=<?=$model->id?>" method="post">
            <div class="form-group">
              <textarea class="form-control" name="comment" id="comment" rows="5" placeholder="<?=Yii::t('app', 'Comment here')?>"></textarea>
              <span class="cmnt_error error"></span>
            </div>
            <div class="form-group">
              <button type="button" class="btn btn-primary waves-effect waves-light waves-round" onclick="getValidateThenSubmit(<?=$model->id?>);"><?=Yii::t('app', 'Comment')?></button>
              <label for="userFile">
                <a  class="msg_send_btn btn btn-success"><?=Yii::t('app', 'Attach File')?></a>
              </label>
              <button type="button" class="btn btn-danger waves-effect waves-light waves-round" onclick="attachAtTheRate();"><?=Yii::t('app', 'Add User')?></button>
            </div>

          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<style type="text/css">
/*  .mypanel
{
padding: 25px!important;
border-top: 1px solid #b0bec5;
}*/
.panel-body{
padding: 25px!important;
border-bottom: 1px solid #b0bec5!important;
}
.panel-text
{
margin-left: 30px!important;
}
</style>
<style>
#progress-bar {background-color: #12CC1A;height:20px;color: #FFFFFF;width:0%;-webkit-transition: width .3s;-moz-transition: width .3s;transition: width .3s;}
#progress-div {border:#0FA015 1px solid;padding: 5px 0px;margin:30px 0px;border-radius:4px;text-align:center;}
#targetLayer{width:100%;text-align:center;}
.error
{
color: red!important;
}
</style>
<script type="text/javascript">
function getValidateThenSubmit(ticket_id) {
    var msg = $("#comment").val();
    if (msg == '') {
        $('.cmnt_error').text("This field cannot be blank");
        $('#comment').focus();
        return false;
    } else {
        jQuery.ajax({
            method: "POST",
            url: "<?=Yii::getAlias('@web');?>/task-discussion/comment?id=" + ticket_id,
            data: $("#myForm1").serialize(),
            cache: false,
            success: function(data) {
                if (data) {
                    $(".myop").prepend(data);
                    $("#comment").val('');
                    $('.show_success').css('display', 'block');
                    $('.no_data').css('display', 'none');
                }
            }
        });
    }
}
$("textarea").keypress(function() {
    hideError();
})

function hideError() {
    $(".error").text('');
}

function getDeleteComment(id) {
    if (confirm('Are you sure you want to delete this comment?') == true) {
        $.ajax({
            method: 'POST',
            url: '<?=Yii::getAlias('@web')?>/task-discussion/delete?id=' + id,
            success: function(data) {
                if (data == true) {
                    $('.dis' + id).hide();
                }
            }
        })
    }
}


function editComment(id) {
    $('#update_comment').addClass('show');
    $('#update_comment_content').load('<?=Yii::getAlias('@web')?>/task-discussion/edit-comment?id=' + id);
}
// var users = [
//   {username: 'lodev09', fullname: 'Jovanni Lo'},
//   {username: 'foo', fullname: 'Foo User'},
//   {username: 'bar', fullname: 'Bar User'},
//   {username: 'twbs', fullname: 'Twitter Bootstrap'},
//   {username: 'john', fullname: 'John Doe'},
//   {username: 'jane', fullname: 'Jane Doe'},
// ];

var users =  <?=json_encode($users)?>;

$('#comment').suggest('@', {
    data: users,
    map: function(user) {
        return {
            value: user.username,
            text: '<strong>' + user.username + '</strong> <small>' + user.fullname + '</small>'
        }
    },
    dropdownClass: 'dropdown-menu',
    position: 'caret',
    // events hook
    onshow: function(e) {},
    onselect: function(e, item) {
      $(".suggest").find('.dropdown-menu').addClass('hide');
    },
    onlookup: function(e, item) {
       $(".suggest").find('.dropdown-menu').removeClass('hide');
    }
})

function attachAtTheRate() {
 $("textarea").focus().val("").val("@");
  $('#comment').trigger('keyup');
}

$(document).keyup('textarea',function() {
 /* I EVEN TRIED TO TRIM IT TO NO AVAIL */
    var content = $.trim($('#comment').val());
    if(content.length === 0) {
        $('.suggest .dropdown-menu').hide();
    }
});

</script>
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

       <form id="uploadForm" action="<?=Yii::getAlias('@web');?>/chat/index/process-upload?id=<?=$id;?>&is_group=<?=$is_group?>" method="post" enctype="multipart/form-data">
<div id="sendAttachment" onchange="sendAttachment()">

<input name="userFile" id="userFile" type="file" class="demoInputBox" style="display: none" />


</div>

<div class="p_text" style="display: none;position: absolute;bottom: 70px;text-align: center;width: 99%; background: #fff; right:6px">
<h3 align="center">File Uploading...</h3>
<div id="progress-div" style="display: none;"><div id="progress-bar"></div></div>
</div>
<div id="targetLayer"></div>

</form>


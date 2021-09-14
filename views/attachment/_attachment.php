<?php
/* @var $this yii\web\View */
/* @var $model app\models\Task */
/* @var $form yii\widgets\ActiveForm */
?>


<div id="attachment-files">
 <div class="rounded-md px-5 py-4 mb-2 border text-gray-700 dark:text-gray-300 dark:border-dark-5 text-center text-heading mt-2 mb-4">
                                <div class="font-medium text-lg text-center"><?=Yii::t('app', 'Attached Files')?></div>
                        </div>

  <div id="multiple-file-upload">
                                <div class="preview">
                                    <form action="<?=Yii::getAlias('@web')?>/attachment/upload?type=<?=$type?>&relation_id=<?=$relation_id?>" class="dropzone1 border-gray-200 border-dashed drop-zone-box" id="myDrop">
                                        <input type="hidden" name="_csrf" value="<?=uniqid()?>">
                                        <div class="fallback" style="display: none">
                                            <input name="file" type="file" multiple />
                                        </div>
                                        <div class="dz-message" data-dz-message>
                                            <div class="text-lg font-medium"><?=Yii::t('app', 'Drop files here or click to upload')?>.</div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div id="uploadedImages">
                            <?=$this->render('view_attachment', ['type' => $type, 'relation_id' => $relation_id])?>
</div>
</div>
 <link href="<?=Yii::getAlias('@web')?>/web/css/dropzone.min.css" rel="stylesheet">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/4.2.0/min/dropzone.min.js"></script>
<script type="text/javascript">

    function deleteAttachment(id,type) {
        var msg = "<?=Yii::t('app', 'Are you sure you want to delete this attachment?')?>";
        if(confirm(msg) ==true) {
        $.ajax({
            method: 'post',
            url: '<?=Yii::getAlias('@web')?>/attachment/delete-attachment?id='+id+'&type='+type,
            success:function(response) {

                refreshImageData();
                // alert('File deleted successfully!');

            }
        })
    }
}

    function refreshImageData() {
          $('.dz-message').show();
        $("#uploadedImages").load(location.href + " #uploadedImages");
    }

    Dropzone.autoDiscover = false;

$(document).ready(function(){
var myDropzone = new Dropzone("#myDrop", {

    maxFilesize: 10,

       //acceptedFiles: ".jpeg,.jpg,.png,.gif",

       success:function(file, response)
        {
           refreshImageData();
           $('.dz-message').hide();
           setTimeout(function(){
            $('.dz-preview').remove();
           $('.dz-message').show();
           },3000);
        }

 });
});

</script>
 <style type="text/css">
    .submitButton {
     position: absolute!important;
    top: 15px;
    right: 13px;
    }
    .drop-zone-box {
    background: #80808052;
    }
    .delete-btn {
            font-size: 13px;
    position: absolute;
    right: 3px;
    padding: 1px 5px;
    }
.dropzone1
{
    position: relative;
    border: 1px solid #cecaca;
    background:#f1f1f1;
    padding: 1em;
    min-height: 100px!important;
}
.dropzone1 .dz-default.dz-message
{
    font-size: 30px;
    color: #000;
    text-align: center;
}
.dz-complete
{
    border-radius: 20px;
}
</style>
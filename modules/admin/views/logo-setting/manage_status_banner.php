<?php

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\Setting */
/* @var $form yii\widgets\ActiveForm */

$this->title = Yii::t('app', 'Status Banner Setting');
$this->params['breadcrumbs'][] = Yii::t('app', 'Status Banner Setting');
?>

 <div class="row">
  <div class="col-md-12" >
   <div class="panel panel-primary panel-border top" >
    <div class="panel-heading">
        <div class="page-title" style="font-size: 25px;margin-left:19px"> <i class="fa fa-cog"></i>  <?=$this->title?> </div>
    </div>
<div class="panel-body">
 <div class="block">

                      <div class="form-group">
                             <form id="frmUpload" method="post" enctype="multipart/form-data">
           <input type="hidden" name="_csrf" value="o2-ONPZZ3r4kS50nYwtdx2_YmSmgme9Us2ZUKZttou_WOcwDkji0_0143x4KQR6OXvXcWfnNqhv_VT5crg6Tlw==">
                          <label class="col-md-2 control-label">Status Banner Background:</label>
                      <div class="col-md-4">
                          <div class="imgDIV text-center">

                <a>
                  <img src="<?=Yii::getAlias('@web')?>/banner/bg.png" alt="Image" class="img-responsive thumbnail" style="width:150px;">
                 <span class="img-change"><i class="fa fa-camera-retro"></i></span></a>
                  <span id="upload">
                   <div class="form-group field-banner-image">

<input type="hidden" name="file" value="">
<input type="file" id="banner-image" name="file" accept="image/png" title="Change Status Banner Background" onchange="upload();" style="cursor:pointer">
</div>
</span>
</div>
 </div>
</form>
</div>
</div>
 </div>
   </div>
  </div>
 </div>

<script type="text/javascript">

function upload()
{
	$('#frmUpload').attr('action','<?=Yii::getAlias('@web');?>/admin/setting/upload-status-banner');
	$('#frmUpload').submit();
}

</script>


  <style>
  .note-editable{
	  height:100px !important;
  }
  .imgDIV {

 height: 170px;
  background-color: #FBFBFB;
  border: 1px dashed #DDD;
  position: relative;
  border-radius: 4px;
  float: left;
  padding: 22px !important;
}
#upload {
  position: absolute;
  overflow: hidden;
  opacity: 0;
  top: 0;
  left: 0;
  bottom: 0;
}
.img-change{
	position:absolute;
	top:-1px;
	font-size:20px;
	right:4px;
	color: #2d3945;
	}
.img-change:hover{
	cursor:pointer !important;
}
  </style>


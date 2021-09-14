<?php

use app\modules\admin\models\LogoSetting;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\Setting */
/* @var $form yii\widgets\ActiveForm */

$this->title = Yii::t('app', 'Logo Setting');
$this->params['breadcrumbs'][] = Yii::t('app', 'Logo Setting');
$Logo = LogoSetting::find()->where(['setting_name' => 'Logo'])->One();

?>

<?php if (Yii::$app->session->hasFlash('successStatus')): ?>
           <div class="rounded-md flex items-center px-5 py-4 mb-2 bg-theme-9 text-white mt-5 successStatus">
            <?=Yii::$app->session->getFlash('successStatus');?>
            <i data-feather="x" class="w-4 h-4 ml-auto" onclick="hideAlert();"></i>
                </div>
            <?php endif;?>

 <div class="row mt-5">
  <div class="col-md-12" >
   <div class="panel border-theme-1 top">
    <div class="panel-heading bg-theme-1">
        <div class="page-title text-white" style="font-size: 25px;"> <i class="fa fa-cog"></i>  <?=$this->title?> </div>
    </div>

		<div class="panel-body">











                <div class="block">

                             <?php $form = ActiveForm::begin();?>
                             <div class="row mt-5">

                          <label class="col-md-2 control-label"><?=Yii::t('app', 'Company Logo')?>:</label>
                     <div class="col-md-4">

            <?=$form->field($logo, 'file')->fileInput(['onchange' => 'readURL(this)', 'accept' => '.jpg,.jpeg,.gif,.png,.svg', 'class' => "profile input w-full border mt-2"])?>

    <div class="col-md-4">
<?php
if ($logo->setting_value != '') {
	?>
             <img src="<?=Yii::getAlias('@web');?>/web/logo/<?=$logo->setting_value?>" class="profile img-thumbnail" width="80" height="80">
             <?php
}
?>
         </div>

                </div>
                       <label class="col-md-2 control-label"><?=Yii::t('app', 'Logo Size')?>:</label>

                     <div class="col-md-6">
                        <?=$form->field($logo, 'setting_size')->textInput(['placeholder' => Yii::t('app', 'Logo Size'), 'class' => 'input w-full border mt-2'])->label(false)?>
                     </div>

                   </div>

                     <div class="intro-x mt-5 xl:mt-8 text-center xl:text-center">

                      <?=Html::submitButton(Yii::t('app', 'Save'), ['class' => 'button button--lg w-full xl:w-32 text-white bg-theme-1 align-top'])?>

                </div>
                              <?php ActiveForm::end();?>
                  </div>




		</div>
   </div>
  </div>
 </div>

<script type="text/javascript">
function CNupdate(inputid,act)
{
		var value	=	$('#'+inputid).val();
		jQuery.ajax({
		url:"<?=Yii::getAlias('@web');?>/admin/logo-setting/setting-update?act="+act+"&value="+value,
		type: "post",
		success: function(data)
			{
				location.reload();
			}
		});
}
function upload()
{
	$('#frmUpload').attr('action','<?=Yii::getAlias('@web');?>/admin/logo-setting/upload');
	$('#frmUpload').submit();
}

function readURL(input)
{
if (input.files && input.files[0])
{
var reader = new FileReader();
reader.onload = function (e) {
$('.profile').attr('src', e.target.result);
}
reader.readAsDataURL(input.files[0]);
}
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

<script type="text/javascript">
       function hideAlert() {
    $('.successStatus').hide();
  }
</script>
<?php

use app\modules\admin\models\Setting;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\Setting */
/* @var $form yii\widgets\ActiveForm */

$this->title = 'Contact Setting';
$this->params['breadcrumbs'][] = $this->title;
$CompanyAddress = Setting::find()->where(['setting_name' => 'CompanyAddress'])->One();
$Phone = Setting::find()->where(['setting_name' => 'Phone'])->One();
$Email = Setting::find()->where(['setting_name' => 'CompanyEmail'])->One();

$Map = Setting::find()->where(['setting_name' => 'Map'])->One();

?>

<div class="row">
<div class="col-md-12">
		<div class="panel panel-default">

        		<div class="panel-body">
                 <div class="content-frame" id="ca1">

                    <!-- START CONTENT FRAME TOP -->
                    <div class="content-frame-top">
                        <div class="page-title">
                            <h2><span class="fa fa-cog"></span> <?=$this->title?></h2>
                        </div>

                     </div>
                    </div>

                    <div class="block">

                         <div class="form-group"  id="cn1">
                                <label class="col-md-2 control-label">Phone Number:</label>
                                <div class="col-md-7">
                                    <input type="text" class="form-control" value="<?=$Phone['setting_value']?>" id="company-phone"/>
                                </div>
                                 <div class="col-md-3">
                                        <input type="button"  class="btn btn-primary" value="Update" onclick="PNupdate()">

                                    </div>
                            </div>
                            </div>

                        <div class="block">

                         <div class="form-group"  id="cn2">
                                <label class="col-md-2 control-label">Company Email:</label>
                                <div class="col-md-7">
                                    <input type="text" class="form-control" value="<?=$Email['setting_value']?>" id="company-email"/>
                                </div>
                                 <div class="col-md-3">
                                        <input type="button"  class="btn btn-primary" value="Update" onclick="ENupdate()">

                                    </div>
                            </div>
                            </div>




                     <div class="block">

                        <div class="form-group" >

                            <label class="col-md-2 control-label">Company Address:</label>
                                <div class="col-md-7" id="ad1">
                                    <textarea class="summernote_email" >
                                   <?=$CompanyAddress['setting_value']?>
                                    </textarea>
                                </div>
                                 <div class="col-md-3">
                                        <input type="button"  class="btn btn-primary" value="Update" onclick="update()">

                                    </div>

                            </div>
                            </div>








				</div>


		</div>
        </div>
</div>

<script type="text/javascript">




function update()
{
		var c_add	=	$('#ad1 .note-editable').html();

		jQuery.ajax({
		url:"<?=Yii::getAlias('@web');?>/admin/setting/address1?c_add="+c_add,
		type: "post",
		success: function(data)
			{
				alert(data);
			$('#ca1').load(document.URL +  ' #ca1');
			}
		});
}


function PNupdate()
{
		var phone	=	$('#company-phone').val();
		jQuery.ajax({
		url:"<?=Yii::getAlias('@web');?>/admin/setting/contact-update?value="+encodeURIComponent(phone)+"&act=Phone",
		type: "post",
		success: function(data)
			{
				alert(data);
				$('#cn1').load(document.URL +  ' #cn1');
				$('#cnl1').load(document.URL +  ' #cnl1');
			}
		});
}

function ENupdate()
{
    var email = $('#company-email').val();
    jQuery.ajax({
    url:"<?=Yii::getAlias('@web');?>/admin/setting/contact-update?value="+encodeURIComponent(email)+"&act=CompanyEmail",
    type: "post",
    success: function(data)
      {
        alert(data);
        $('#cn2').load(document.URL +  ' #cn2');
        $('#cnl2').load(document.URL +  ' #cnl2');
      }
    });
}



</script>


  <style>
  .note-editable{
	  height:100px !important;
  }
  .imgDIV {
  width: 220px;
  max-height: 170px;
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


<?php
use app\modules\admin\models\General;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->title = Yii::t('app', 'Settings');
$this->params['breadcrumbs'][] = $this->title;

$settings = General::find()->groupBy('type_name')->all();

?>
 <?php if (Yii::$app->session->hasFlash('successStatus')): ?>
           <div class="rounded-md flex items-center px-5 py-4 bg-theme-9 text-white mt-5 successStatus">
            <?=Yii::$app->session->getFlash('successStatus');?>
            <i data-feather="x" class="w-4 h-4 ml-auto" onclick="hideAlert();"></i>
                </div>
            <?php endif;?>
  <div class="pos intro-y grid grid-cols-12 gap-5 mt-3">


                    <!-- BEGIN: Post Content -->
                    <div class="intro-y col-span-12 lg:col-span-12">
                        <div class="post intro-y overflow-hidden box mt-5">
                          <form  class="form-horizontal" action="<?=Yii::getAlias('@web');?>/admin/general/" method="post" role="form">
                                <input type="hidden" name="_csrf" value="<?php echo uniqid(); ?>">

                            <div class="post__tabs nav-tabs flex flex-col sm:flex-row bg-gray-300 dark:bg-dark-2 text-gray-600">
                               <?php foreach ($settings as $k => $v) {
	$active = ($k == 0) ? 'active' : '';
	?>
                                <a title="<?=$v->type_label?>" data-toggle="tab" data-target="#<?=$v->type_name?>" href="javascript:void(0)" class="tooltip w-full sm:w-40 py-4 text-center flex justify-center items-center <?=$active?>"> <i data-feather="file-text" class="w-4 h-4 mr-2"></i> <?=Yii::t('app', $v->type_label)?> </a>
                                 <?php }?>
                                 <div class="w-full sm:w-auto flex items-center sm:ml-auto mt-3 sm:mt-0">
                                    <div class="px-5 py-3 text-right border-t border-gray-200 dark:border-dark-5">

                                  <button type="reset" class="button w-30 bg-theme-6 text-white"><span><?=Yii::t('app', 'Reset')?></span></button>
            <button type="submit" name="submit" class="button w-20 bg-theme-9 text-white" ><span><?=Yii::t('app', 'Save')?></span></button>
          </div>
          </div>
                            </div>
                            <div class="post__content tab-content">

                              <?php foreach ($settings as $k1 => $v1) {
	$active1 = ($k1 == 0) ? 'active' : '';
	$General = General::find()->where(['type_name' => $v1->type_name])->all();
	?>

                                <div class="tab-pane p-5 <?=$active1?>" id="<?=$v1->type_name?>">




                <?php foreach ($General as $g => $gen) {
		if ($gen['setting_value'] == "Enable") {
			$enable = "checked";
			$disable = "";
		} else {
			$enable = "";
			$disable = "checked";
		}
		?>
            <div class="form-group form-material">
              <label  class="control-label col-sm-3">
                <?=Yii::t('app', $gen['setting_label'])?>
              </label>
              <div class="col-sm-6">
                <?php if ($gen['type_name'] != 'page_setting') {
			?>
                <?php if ($gen['setting_name'] == 'address' || $gen['setting_name'] == 'footer_text' || $gen['type_name'] == 'seo') {?>
                <textarea name="<?=$gen['id'];?>" class="form-control" rows="6" ><?=$gen['setting_value']?></textarea>
                <?php } elseif ($gen['setting_name'] == 'show_footer_text' || $gen['setting_name'] == 'perfect_money' || $gen['setting_name'] == 'paypal') {
				?>
                   <div class="radio" style="margin-top:-6px;">
                  <label  class="radio-inline"><input value="Enable"  type="radio" name="<?=$gen['id'];?>" <?=$enable?> >
                  Enable</label>
                  <label  class="radio-inline"><input value="Disable" type="radio" name="<?=$gen['id'];?>" <?=$disable?> >Disable</label>
                </div>
                  <?php
} else {?>
                <input type="text" name="<?=$gen['id']?>" class="form-control" value="<?=$gen['setting_value']?>">
                <?php }
		} elseif ($gen['type_name'] == 'color_setting') {
			?>
 <input type="color" name="<?=$gen['id']?>" class="form-control" value="<?=$gen['setting_value']?>" style="width:100px">
      <?php
} else {
			?>
                <div class="radio" style="margin-top:-6px;">
                  <label  class="radio-inline"><input value="Enable"  type="radio" name="<?=$gen['id'];?>" <?=$enable?> >
                  Enable</label>
                  <label  class="radio-inline"><input value="Disable" type="radio" name="<?=$gen['id'];?>" <?=$disable?> >Disable</label>
                </div>
                <?php
}
		?>

              </div>
            </div>
             <?php }?>
          </div>
            <?php }?>
          </div>
          <!-- general tab end -->

</form>
</div>
</div>
</div>

<script type="text/javascript">
    function hideAlert() {
    $('.successStatus').hide();
  }
</script>



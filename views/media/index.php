<?php
/* @var $this yii\web\View */
$this->title = Yii::t('app', 'File Manager');
$this->params['breadcrumbs'][] = $this->title;
?>
<script type="text/javascript">
    var move_btn_text = "<?=Yii::t('app', 'Move')?>";
    var remove_btn_text = "<?=Yii::t('app', 'Remove')?>";
    var download_btn_text = "<?=Yii::t('app', 'Download')?>";
    var share_btn_text = "<?=Yii::t('app', 'Share')?>";
</script>
<div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
    <link rel="stylesheet" type="text/css" href="<?=Yii::getAlias('@web')?>/themes/common/mediaManager/mediaManager.css">
    <script type="text/javascript" src="<?=Yii::getAlias('@web')?>/themes/common/mediaManager/jquery.simplePagination.js"></script>
    <script type="text/javascript"  src="<?=Yii::getAlias('@web')?>/themes/common/mediaManager/mediaManager.js"></script>
    <div id="upload-media">
    </div>
</div>
<script type="text/javascript">
function getMediaGallery(inputId = '', inputType = '') {
$('.search-input-box').show();
$('#upload-media').load("<?=Yii::getAlias('@web');?>/global/media-gallery?inputId=" + inputId + "&inputType=" + inputType);
}
getMediaGallery();
</script>
<style type="text/css">
    #upload-media {
        position: relative;
        top: 70px;
        width: 100%;
    }
</style>
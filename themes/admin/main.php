<?php
use yii\helpers\Html;
/* @var $this \yii\web\View */
/* @var $content string */
/*AppAsset::register($this);
 */
//Yii::$app->language = 'en';
$st = Yii::$app->getTable;
$site_name = $st->settings('general', 'site_name');
$frontend_url = $st->settings('general', 'frontend_url');
$dark_mode = Yii::$app->getRequest()->getCookies()->getValue('dark_mode');
if ($dark_mode) {
	$html_class = 'dark';
	$btn_active_class = 'dark-mode-switcher__toggle--active';
	$mode_value = 0;
} else {
	$html_class = 'light';
	$btn_active_class = '';
	$mode_value = 1;
}
?>
<?php $this->beginPage()?>
<!DOCTYPE html>
<!--
Template Name: Midone - HTML Admin Dashboard Template
Author: Left4code
Website: http://www.left4code.com/
Contact: muhammadrizki@left4code.com
Purchase: https://themeforest.net/user/left4code/portfolio
Renew Support: https://themeforest.net/user/left4code/portfolio
License: You must have a valid license purchased only from themeforest(the above link) in order to legally use the theme for your project.
-->
<html lang="<?=Yii::$app->getRequest()->getCookies()->getValue('lang')?>" class="<?=$html_class?>">
    <!-- BEGIN: Head -->
    <head>
        <meta charset="utf-8">
        <?=Html::csrfMetaTags()?>
        <title><?=($this->title == '') ? '' : Html::encode($this->title) . ' | '?><?=$site_name?></title>
        <?=$this->head();?>
        <link rel="stylesheet" type="text/css" href="<?=Yii::getAlias('@web')?>/web/css/bootstrap.css"/>
        <link href="<?=Yii::getAlias('@web')?>/themes/admin/dist/images/logo.svg" rel="shortcut icon">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="Employee Management System">
        <meta name="keywords" content="Employee Management System">
        <meta name="author" content="faystech.com">
        <!-- BEGIN: CSS Assets-->
        <link rel="stylesheet" href="<?=Yii::getAlias('@web')?>/themes/admin/dist/css/app.css" />
        <link rel="stylesheet" href="<?=Yii::getAlias('@web')?>/themes/admin/dist/css/app.main.css" />
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" />
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
        <!-- BEGIN: JS Assets-->
        <script src="https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/markerclusterer.js" defer></script>
        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD88tN6dhHB2nkn6mARIfExT7z7rfOqc1c&libraries=places" defer></script>
        <script src="<?=Yii::getAlias('@web')?>/themes/admin/dist/js/app.js" defer></script>

        <!-- JavaScript -->
        <script src="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/alertify.min.js" defer></script>
        <!-- CSS -->
        <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/alertify.min.css"/>
        <script type="text/javascript">
        const BASE_URL = "<?=Yii::getAlias('@web')?>/";
        </script>
        <!-- END: CSS Assets-->
        <style type="text/css">
        .help-block {
        color: red;
        margin-top:4px;
        }
        @media(max-width: 480px){
        .myclass1{
        overflow-x: scroll;
        }
        }
        .prev{
        font-size: 38px;
        color: #9c9292!important;
        margin-top:5px;
        }
        .next {
        font-size: 38px;
        margin-top:5px;
        color: #9c9292!important;
        }
        .next a, .prev a {
        margin-top: -7px!important;
        color: #9c9292!important;
        }
        .pagination .active {
        box-shadow: 0 3px 20px rgb(0 0 0 / 4%);
        --tw-bg-opacity: 1;
        background-color: rgba(255,255,255,var(--tw-bg-opacity));
        border-radius: .375rem;
        position: relative;
        font-weight: 500;
        margin-left: 15px;
        }
        .pagination .pagination__link span {
        margin-top:5px;
        margin-left: 15px;
        }
        .pagination li .pagination__link {
        margin-right: 0px!important;
        }
        .search-icon {
        left: 150px;
        }
        .submitButton {
        position: absolute!important;
        top: 15px;
        right: 13px;
        }
        /*.pagination a {
        margin-top:10px;
        }*/
        .dark .pagination .active .pagination__link{
        color: #000;
        }
        .dark .text-theme-1{
        color: rgba(226,232,240,var(--tw-text-opacity));
        }
        .progress {
            height: 1.5rem!important;
        }

        .text-theme-6 {
            color: red;
        }

        .text-theme-9 {
            color:#90c614;
        }
        .note-modal-backdrop {
            z-index: -1!important;
        }

        </style>
    </head>
    <!-- END: Head -->
    <?php $this->beginBody()?>
    <body class="app">
         <?php include 'mobile-menu.php'?>

 <?php include 'header.php'?>
        <!-- BEGIN: Sidebar Menu -->
         <div class="wrapper">
        <div class="wrapper-box">
        <?php include 'sidebar.php';?>
        <!-- END: Sidebar Menu -->
        <!-- BEGIN: Content -->

        <div class="content">
            <!-- BEGIN: Top Bar -->

            <!--   <div class="intro-y flex items-center mt-8">
                <h2 class="text-lg font-medium mr-auto">
                <?=Html::encode($this->title)?>
                </h2>
            </div>
            -->
            <?=$content?>
            <input type="hidden" name="latitude" id="latitude" value=""/>
            <input type="hidden" name="longitude" id="longitude" value=""/>
            <!-- END: Top Bar -->
        </div>
        </div>
    </div>
        <!-- END: Content -->
        <div data-url="<?=Yii::getAlias('@web');?>/site/dark-mode?v=<?=$mode_value?>" class="dark-mode-switcher cursor-pointer shadow-md fixed bottom-0 right-0 box dark:bg-dark-2 border rounded-full w-40 h-12 flex items-center justify-center z-50 mb-10 mr-10">
            <div class="mr-4 text-gray-700 dark:text-gray-300">Dark Mode</div>
            <div class="dark-mode-switcher__toggle <?=$btn_active_class?> border"></div>
        </div>
        <script type="text/javascript">
        $("td").each(function(index, el) {
        if ($(el).is(':empty') || $.trim($(el).html()) =='') $(el).text(" - ").css("color","red");
        })
        function getLanguage(id) {
        $.post("<?=Yii::getAlias('@web');?>/site/language",{'lang':id},function($data) {
        location.reload();
        })
        }
        $('a[data-dismiss="modal"]','button[data-dismiss="modal"]').click(function(){
        alert('Ok');
        $('body').removeClass('overflow-y-hidden');
        $('body').css('padding-right','');
        });
        $('body').click(function(){
        $('body').removeClass('overflow-y-hidden');
        $('body').css('padding-right','');
        /*$('.edit-assignee-select .tail-select-container').html('');*/
        })
        </script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.4.1/jspdf.min.js" defer></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/2.3.5/jspdf.plugin.autotable.min.js" defer></script>
        <script src="<?=Yii::getAlias('@web');?>/themes/common/export/tableHTMLExport.js" defer></script>
        <script src="https://cdn.rawgit.com/rainabba/jquery-table2excel/1.1.0/dist/jquery.table2excel.min.js" defer></script>
        <script type="text/javascript">
        function getLocation() {
        if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(showPosition);
        } else {
        alertify.alert("Geolocation is not supported by this browser.");
        }
        }
        function showPosition(position) {
        $('#latitude').val(position.coords.latitude);
        $('#longitude').val(position.coords.longitude);
        }
        getLocation();
        function logout() {
        var latitude =  $('#latitude').val();
        var longitude = $('#longitude').val();
        var msg = "<?=Yii::t('app', 'Please checkout first before logout')?>";
        $.ajax({
        url: '<?=Yii::getAlias('@web')?>/site/check-attandance',
        method: 'get',
        async: true,
        success:function(data) {
        if(data == 1){
        alertify.alert('<?=Yii::t('app', 'Checkout Notice')?>',msg);
        return false
        }else{
        $.ajax({
        url: '<?=Yii::getAlias('@web')?>/site/logout',
        method: 'post',
        data: { latitude: latitude, longitude: longitude },
        success:function(response) {
        window.location.href = '<?=Yii::getAlias('@web')?>/';
        }
        })
        }
        }
        })
        return false;
        }
        </script>
        <!-- END: JS Assets-->
        <style>
        .note-editable{
        background:#fff;
        }
        .note-editor.note-airframe.fullscreen, .note-editor.note-frame.fullscreen{
        }
        html, body{
        overflow:visible !important;
        }
        </style>
    </body>
    <?php $this->endBody()?>
</html>
<?php $this->endPage()?>
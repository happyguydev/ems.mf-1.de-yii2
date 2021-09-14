<?php
use app\modules\admin\models\LogoSetting;
use yii\helpers\Html;
/* @var $this \yii\web\View */
/* @var $content string */

$st = Yii::$app->getTable;
$site_name = $st->settings('general', 'site_name');
$logo_setting = LogoSetting::find()->where(['setting_name' => 'Logo'])->one();
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
<html lang="<?=Yii::$app->getRequest()->getCookies()->getValue('lang')?>" class="<?=$html_class?>">
  <!-- BEGIN: Head -->
    <head>
        <meta charset="utf-8">
        <link href="<?=Yii::getAlias('@web')?>/themes/admin/dist/images/logo.svg" rel="shortcut icon">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="employee management system, a powerful web based crm">
        <meta name="author" content="faystech.com">
        <!-- BEGIN: CSS Assets-->
        <link rel="stylesheet" href="<?=Yii::getAlias('@web')?>/themes/admin/dist/css/app.css" />
        <link rel="stylesheet" href="<?=Yii::getAlias('@web')?>/themes/admin/dist/css/app.main.css" />
         <?=Html::csrfMetaTags()?>
    <title><?=($this->title == '') ? '' : Html::encode($this->title) . ' | '?><?=$site_name?> </title>
        <!-- BEGIN: CSS Assets-->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>

        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD88tN6dhHB2nkn6mARIfExT7z7rfOqc1c&libraries=places" defer></script>

        <!-- END: CSS Assets-->
    </head>
    <!-- END: Head -->
    <?php $this->beginBody()?>

    <body class="login">
        <div class="container sm:px-10">
            <div class="block xl:grid grid-cols-2 gap-4">
                <!-- BEGIN: Login Info -->
                <div class="hidden xl:flex flex-col min-h-screen">
                    <a href="" class="-intro-x flex items-center pt-5">
                       <img alt="<?=Yii::t('app', $site_name)?>" class="w-8" src="<?=Yii::getAlias('@web')?>/web/logo/<?=$logo_setting->setting_value?>?v=<?=time()?>" style="width:<?=$logo_setting->setting_size?>px">

                    </a>
                    <div class="my-auto">
                        <img class="-intro-x w-1/2 -mt-16" src="<?=Yii::getAlias('@web')?>/themes/admin/dist/images/illustration.svg">
                        <div class="-intro-x text-white font-medium text-4xl leading-tight mt-10">
                            A few more clicks to<br/>

                            sign in to your account.
                        </div>

                    </div>
                </div>

                <?=$content?>

            </div>
        </div>
        <!-- BEGIN: Dark Mode Switcher-->
       <div data-url="<?=Yii::getAlias('@web');?>/site/dark-mode?v=<?=$mode_value?>" class="dark-mode-switcher cursor-pointer shadow-md fixed bottom-0 right-0 box dark:bg-dark-2 border rounded-full w-40 h-12 flex items-center justify-center z-50 mb-10 mr-10">
            <div class="mr-4 text-gray-700 dark:text-gray-300">Dark Mode</div>
            <div class="dark-mode-switcher__toggle <?=$btn_active_class?> border"></div>
        </div>
        <!-- END: Dark Mode Switcher-->
        <!-- BEGIN: JS Assets-->
        <script src="<?=Yii::getAlias('@web')?>/themes/admin/dist/js/app.js"></script>
        <!-- END: JS Assets-->

</body>
<?php $this->endBody()?>

</html>
<?php $this->endPage()?>
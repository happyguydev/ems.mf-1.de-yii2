<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\assets\AppAsset;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;

AppAsset::register($this);
//Yii::$app->language = 'ru-RU';
?>
<?php $this->beginPage()?>
<!DOCTYPE html>
<html lang="<?=Yii::$app->language?>">
<head>
    <meta charset="<?=Yii::$app->charset?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?=Html::csrfMetaTags()?>
    <title><?=Html::encode($this->title)?></title>
    <?php $this->head()?>
</head>
<body>
<?php $this->beginBody()?>

<div class="wrap">
    <?php
NavBar::begin([
	'brandLabel' => 'My Company',
	'brandUrl' => Yii::$app->homeUrl,
	'options' => [
		'class' => 'navbar-inverse navbar-fixed-top',
	],
]);
echo Nav::widget([
	'options' => ['class' => 'navbar-nav navbar-right'],
	'items' => [
		//	['label' => 'Home', 'url' => ['/site/index']],
		Yii::$app->user->isGuest ? (
			['label' => 'Login', 'url' => ['/site/signin']]
		) : (
			'<li>'
			. Html::beginForm(['/site/logout'], 'post', ['class' => 'navbar-form'])
			. Html::submitButton(
				'Logout (' . Yii::$app->user->identity->username . ')',
				['class' => 'btn btn-link']
			)
			. Html::endForm()
			. '</li>'
		),
	],
]);
NavBar::end();
?>

    <div class="container">
        <?=Breadcrumbs::widget([
	'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
])?>
        <?=$content?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; My Company <?=date('Y')?></p>

        <p class="pull-right"><?=Yii::powered()?></p>
    </div>
</footer>


<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false&libraries=places&key=AIzaSyD88tN6dhHB2nkn6mARIfExT7z7rfOqc1c"></script>

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script src="<?=Yii::getAlias('@web');?>/views/site/js/jquery.geocomplete.js"></script>

    <script>

    function getSlug(title)
   {


       $.ajax({
       url : '<?=Yii::getAlias('@web');?>/site/slug?string='+title,
       method : 'GET',
          success: function(data)
          {
              if(data)
              {

               $("#slug_url").val(data);
              }else{
                return false;
              }
         }

       });
  }

        $(".geocomplete").geocomplete(
            {
            details: "#myform",
            detailsAttribute: "data-geo"

        });

      function getLocation(){
        var location = $(".geocomplete").val();
        if(location == ''){
            alert("please enter a location");
        }
      }
    </script>


<?php $this->endBody()?>
</body>
</html>
<?php $this->endPage()?>

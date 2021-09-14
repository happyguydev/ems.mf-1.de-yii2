<?php
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->title = Yii::t('app', 'Chat');
$this->params['breadcrumbs'][] = $this->title;
$user = Yii::$app->user->identity;
if ($user->profile_picture != '') {
	$user_image = Yii::getAlias('@web') . '/web/profile/' . $user->id . '/' . $user->profile_picture;
} else {
	$user_image = Yii::getAlias('@web') . '/web/profile/default.jpg';
}
?>

  <div class="intro-y chat grid grid-cols-12 gap-5 mt-5">
    <div class="col-span-12 lg:col-span-4 xxl:col-span-3">
      <?=$this->render('_users')?>

  </div>

    <div class="intro-y col-span-12 lg:col-span-8 xxl:col-span-9">
      <div class="chat__box box">

        <div class="h-full flex items-center">
          <div class="mx-auto text-center">
            <div class="w-16 h-16 flex-none image-fit rounded-full overflow-hidden mx-auto">
              <img alt="<?=$user->name?>" src="<?=$user_image?>">
            </div>
            <div class="mt-3">
              <div class="font-medium">Hey, <?=$user->name?>!</div>
              <div class="text-gray-600 mt-1">Please select a chat to start messaging.</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script type="text/javascript">
  </script>
  <style type="text/css">
  .chat-body {
  display: block;
  margin: 10px 30px 0 0;
  overflow: hidden;
  }
  .chat-right{
  text-align: right;
  }
  .chat-time {
  display: block;
  margin-top: 8px;
  color: rgba(255,255,255,.6);
  }
  .chat-content {
  position: relative;
  display: block;
  float: right;
  padding: 8px 15px;
  margin: 0 20px 10px 0;
  clear: both;
  color: #fff;
  background-color: #62a8ea;
  border-radius: 4px;
  }
  .chat-left .chat-body {
  margin-right: 0;
  margin-left: 30px;
  }
  .chat-left .chat-content {
  float: left;
  margin: 0 0 10px 20px;
  color: #76838f;
  background-color: #dfe9ef;
  }
  .chat-left .chat-time {
  color: #a3afb7;
  }
  .chat-content:before {
  position: absolute;
  top: 10px;
  right: -10px;
  width: 0;
  height: 0;
  content: '';
  border: 5px solid transparent;
  border-left-color: #62a8ea;
  }
  .chat-left .chat-content:before {
  right: auto;
  left: -10px;
  border-right-color: #dfe9ef;
  border-left-color: transparent;
  }
  .user_edit{
  z-index: 20;
  float: right;
  cursor: pointer;
  }
  </style>
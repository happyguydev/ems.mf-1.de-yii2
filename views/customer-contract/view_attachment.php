<?php
/* @var $this yii\web\View */
/* @var $model app\models\Task */
/* @var $form yii\widgets\ActiveForm */

?>
 <div class="flex items-center px-5 py-5 sm:py-3 border-b border-gray-200 dark:border-dark-5">


             <h2 class="font-medium text-base mr-auto"><?=Yii::t('app', 'View Attachments')?></h2>

         </div>                            <?php

if (count($model) > 0) {
	?>
    <div class="row uploaded-files mt-3" style="padding:15px">
      <div class="col-md-12">


        <?php
foreach ($model as $key => $value) {

		if ($value['extension'] == 'png' || $value['extension'] == 'jpeg' || $value['extension'] == 'jpg' || $value['extension'] == 'gif') {
			$image_name = Yii::$app->urlManager->createAbsoluteUrl('/web/customer-contract/' . $value['contract_id'] . '/' . $value['file_name']);
		} else {
			$image_name = Yii::$app->urlManager->createAbsoluteUrl('/uploads/media/thumb/' . $value['thumb']);
		}

		$thumb = Yii::$app->Utility->get_thumb($value['file_name'], '/web/customer-contract/' . $value['contract_id'] . '/');
		$file_url = Yii::$app->urlManager->createAbsoluteUrl('/web/customer-contract/' . $value['contract_id'] . '/' . $value['file_name']);

		?>


        <div class="col-md-3" >



            <a href="<?=$file_url?>" target="_blank">

            <img src="<?=$thumb?>" class="img-responsive img-thumbnail" width="120" height="120" style="height: 120px;width:100%;object-fit: contain; margin-top: 10px"/>
           <p class="mt-3 text-theme-1" style="font-size:12px;text-align: center;overflow: hidden;"> <?=$value['file_name']?></p>
       </a>

                        </div>
                        <?php
}
	?>
    </div>

</div>
<?php
} else {
	?>
   <div class="row" style="padding:15px">
      <div class="col-md-12">

<div class="text-center text-theme-6"><?=Yii::t('app', 'No File Attached Yet!')?></div>
</div>
</div>

<?php
}
?>

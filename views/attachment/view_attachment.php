<?php
use app\models\Attachment;
/* @var $this yii\web\View */
/* @var $model app\models\Task */
/* @var $form yii\widgets\ActiveForm */
$session = Yii::$app->session;
if ($type == 'project') {
	if ($relation_id == '') {
		$relation_id = isset($session['project_relation_id']) ? $session['project_relation_id'] : '';
	}
}

if ($type == 'task') {
	if ($relation_id == '') {
		$relation_id = isset($session['task_relation_id']) ? $session['task_relation_id'] : '';
	}
}

if ($type == 'leave') {
	if ($relation_id == '') {
		$relation_id = isset($session['leave_relation_id']) ? $session['leave_relation_id'] : '';
	}
}

?>

                            <?php
if ($relation_id != '') {

	$task_attachments = Attachment::find()->where(['relation_id' => $relation_id])->all();
	if (count($task_attachments) > 0) {
		?>
    <div class="row uploaded-files mt-3">


        <?php
foreach ($task_attachments as $key => $value) {

			if ($value['extension'] == 'png' || $value['extension'] == 'jpeg' || $value['extension'] == 'jpg' || $value['extension'] == 'gif') {
				$image_name = Yii::$app->urlManager->createAbsoluteUrl('/web/' . $type . '/' . $relation_id . '/' . $value['file_name']);
			} else {
				$image_name = Yii::$app->urlManager->createAbsoluteUrl('/uploads/media/thumb/' . $value['thumb']);
			}

			$thumb = Yii::$app->Utility->get_thumb($value['file_name'], '/web/' . $type . '/' . $relation_id . '/');
			$file_url = Yii::$app->urlManager->createAbsoluteUrl('/web/' . $type . '/' . $relation_id . '/' . $value['file_name']);

			?>

            <?php
if (isset($action)) {
				$width = '90';
				$height = '90';
				$column = $type == 'project' ? 'col-md-2' : 'col-md-4';
				$style = $type == 'project' ? "width:20%" : '';
			} else {
				$width = '100%';
				$height = '100';
				$column = 'col-md-2 col-xl-1 col-lg-1';
				$style = "";
			}

			?>
        <div class="<?=$column?>" style="<?=$style?>">

              <?php
if (isset($action)) {

				?>

			<?php

				if ($value['extension'] == 'png' || $value['extension'] == 'jpg' || $value['extension'] == 'jpeg' || $value['extension'] == 'svg') {

					?>

            <img src="<?=$thumb?>" class="img-responsive" width="<?=$width?>" height="<?=$height?>" data-action="zoom" style="height: <?=$height?>px;width:100%;object-fit: contain; margin-top: 10px"/>
           <p class="mt-3 text-theme-1" style="font-size:12px;text-align: center;overflow: hidden;"> <?=$value['file_name']?></p>


				<?php
} else {
					?>
				<a href="<?=$file_url?>" target="_blank">

            <img src="<?=$thumb?>" class="img-responsive" width="<?=$width?>" height="<?=$height?>" style="height: <?=$height?>px;width:100%;object-fit: contain; margin-top: 10px; cursor:pointer"/>
           <p class="mt-3 text-theme-1" style="font-size:12px;text-align: center;overflow: hidden;"> <?=$value['file_name']?></p>
       </a>

				<?php
}
				?>

<?php
} else {
				?>

    <img src="<?=$thumb?>" class="img-thumbnail img-responsive" style="height: <?=$height?>px;width:100%!important;object-fit: contain;margin-top: 10px"/>

    <?php
}
			?>
            <?php
if (!isset($action)) {

				?>

              <a href="javascript:void(0)" onclick="deleteAttachment('<?=$value['id']?>','<?=$type?>')" class="button text-white bg-theme-6 delete-btn">
                <i class="fa fa-trash tooltip" title="<?=Yii::t('app', 'Delete')?>"></i>
            </a>
            <?php
}
			?>

                        </div>
                        <?php
}
		?>
    </div>


<?php
} else {
		if (isset($action)) {

			?>
<div class="text-center text-theme-6 mt-5"><?=Yii::t('app', 'No File Attached Yet!')?></div>

<?php
}
	}
} else {
	?>
	 <?php
if (isset($action)) {
		?>
	<div class="text-center text-theme-6 mt-5"><?=Yii::t('app', 'No File Attached Yet!')?></div>
	<?php
}
	?>
<?php
}
?>

<style type="text/css">
	@media screen and (max-width: 768px) {
  .col-md-2 {
   width: 100%!important;
  }
}
</style>

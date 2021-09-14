<?php
use app\widgets\UserWidget;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\Media */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="media-form">
    <?php $form = ActiveForm::begin(['enableAjaxValidation' => true, 'enableClientValidation' => true]);?>
    <?=$form->field($model, 'title')->textInput(['maxlength' => true])?>
    <?=$form->field($model, 'description')->textarea(['rows' => 6])?>
    <?=$form->field($model, 'alternate_text')->textInput(['maxlength' => true])?>
    <?=$form->field($model, 'caption')->textInput(['maxlength' => true])?>
    <div class="row">
        <div class="col-md-12">
            <?=$form->field($model, 'file')->fileInput(['onchange' => 'readURL(this)'])?>

<?php
if (!$model->isNewRecord) {
	if ($model->extension == 'jpg' || $model->extension == 'png' || $model->extension == 'gif' || $model->extension == 'jpeg') {
		?>
    <img src="<?=Yii::getAlias('@web');?>/uploads/media/<?=date('Y', strtotime($model->created_at))?>/<?=date('m', strtotime($model->created_at))?>/<?=$model->file_name?>" width="100" height="100" class="media-bnr thumbnail">
<?php
} else {
		?>
<img src="<?=Yii::getAlias('@web');?>/uploads/media/thumb/<?=$model->thumb?>" width="100" height="100" class="media-bnr thumbnail">
<?php
}
} /*else {
	?>

	<img src="<?=Yii::getAlias('@web');?>/uploads/media/<?=date('Y', strtotime($model->created_at))?>/<?=date('m', strtotime($model->created_at))?>/<?=$model->file_name?>" width="100" height="100" class="media-bnr thumbnail">
	<?php
}*/
?>
</div>

</div>

   <?=UserWidget::widget([
	'model_name' => 'Media',
	'create_column_name' => 'create_for',
	'value' => $model->create_for,
	'is_new' => $model->isNewRecord,
])?>

<?=$form->field($model, 'status')->dropDownList($model->statusList(), ['prompt' => 'Select Status'])?>
<div class="form-group">
<?=Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary'])?>
</div>
<?php ActiveForm::end();?>
</div>
<script type="text/javascript">
function readURL(input)
{
if (input.files && input.files[0])
{
var reader = new FileReader();
reader.onload = function (e) {
$('.media-bnr').attr('src', e.target.result);
}
reader.readAsDataURL(input.files[0]);
}
}
</script>

<style type="text/css">
div.show-image
{
position: relative;
float: left;
margin: 6px;
}
div.show-image:hover a
{
display: block;
color: #fff;
cursor: pointer;
}
div.show-image a
{
position:absolute;
top:15px;
left:10px;
display:none;
}
</style>
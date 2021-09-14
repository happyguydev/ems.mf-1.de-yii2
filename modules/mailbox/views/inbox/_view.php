<?php
$getTable = Yii::$app->getTable;
?>
<base target="_blank" />
<div>


	<br clear="all"/>
	<?=$model->body?>
</div>
<style type="text/css">
	.full{
		margin: 0;
		padding:0;
		width: 100%;
		max-width: 100%;
		display: block;
		height: 70px;
		border-bottom: 1px solid #eee;
		padding-bottom: 10px;
	}

	.left-side{
		text-align: left;
		padding-left: 15px;
		width: 40%;
		float: left;
	}
	.right-side{
		text-align: right;
		padding-right: 15px;
		width: 40%;
		float: right;
	}
</style>
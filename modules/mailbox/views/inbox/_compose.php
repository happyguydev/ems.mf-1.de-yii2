 <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
    <div class="modal-content" class="modal__content" style="position: absolute;right: 25%;top:18%">
<form action="<?=Yii::getAlias('@web')?>/mailbox/inbox/send" method="post">


    	<a data-dismiss="modal" href="javascript:;" class="close-popup"> <i data-feather="x" class="w-8 h-8 text-gray-500"></i> </a>
				 <!-- BEGIN: Modal Header -->
				 <div class="modal-header">
				 	<h2 class="font-medium text-base mr-auto"><?=Yii::t('app', 'New Message')?></h2>
				 	</div> <!-- END: Modal Header --> <!-- BEGIN: Modal Body -->
				 	<div class="modal-body grid grid-cols-12 gap-4 gap-y-3">
				 		<div class="col-span-12 sm:col-span-12">

				 			<input id="to" type="email" name="to" class="form-control" placeholder="<?=Yii::t('app', 'To')?>" required>
				 		</div>
				 		<div class="col-span-12 sm:col-span-12">
				 			<input id="subject" type="text" name="subject" class="form-control" placeholder="<?=Yii::t('app', 'Subject')?>" required>
				 		</div>
				 		<div class="col-span-12 sm:col-span-12">
				 			<textarea class="summernote input w-full border mt-2" name="body"></textarea>
				 		</div>

				 	</div> <!-- END: Modal Body -->
				 	<!-- BEGIN: Modal Footer -->
				 	<div class="modal-footer text-right">
				 		<button type="button" data-dismiss="modal" class="button w-20 bg-theme-6 text-white mr-1"><?=Yii::t('app', 'Close')?></button>
				 		 <button type="submit" class="button bg-theme-1 text-white" value="1" name="sent"><?=Yii::t('app', 'Send')?></button>
				 		</div> <!-- END: Modal Footer -->
				 		</form>
				 	</div>


<script type="text/javascript">
$(document).ready(function() {
	$('.summernote').summernote({
		height: '200'
	});
})
</script>

<style type="text/css">
.note-modal-backdrop {
z-index: 0;
opacity: 0;
}
.note-modal-content .checkbox {
width: 100%!important;
}
.note-modal-content .checkbox:before  {
width: 0px;
}
.note-modal-footer {
height: 58px;
padding: 10px;
text-align: center;
}
.close-popup {
position: absolute;
right: 11px;
top: 11px;
}
.note-group-select-from-files {
  display: none;
}
.note-editable{
	background: #fff;
}
</style>
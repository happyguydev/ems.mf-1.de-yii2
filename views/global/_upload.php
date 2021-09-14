<link rel="stylesheet" type="text/css" href="<?=Yii::getAlias('@web')?>/themes/common/dropzone/dist/min/dropzone.min.css">
<script src="<?=Yii::getAlias('@web');?>/themes/common/dropzone/dist/min/dropzone.min.js"></script>
<form action="<?=Yii::getAlias('@web');?>/global/upload" method="post" class="dropzone" id="my-dropzone">
	<input type="hidden" name="_csrf" value="uniqid()">
</form>


<script type="text/javascript">
Dropzone.autoDiscover = false;
$(function() {
	var myDropzone = new Dropzone("#my-dropzone");
	myDropzone.on('addedfile', function(file) {
		var ext = file.name.split('.').pop();
		if (ext == "pdf") {
			$(file.previewElement).find(".dz-image img").attr("src", BASE_URL+"/uploads/media/thumb/pdf.png");
			$(file.previewElement).find(".dz-image img").css("width", "113px");
		} else if (ext.indexOf("doc") != -1) {
			$(file.previewElement).find(".dz-image img").attr("src", BASE_URL+"/uploads/media/thumb/word.ico");
			$(file.previewElement).find(".dz-image img").css("width", "113px");
		} else if (ext.indexOf("docx") != -1) {
			$(file.previewElement).find(".dz-image img").attr("src", BASE_URL+"/uploads/media/thumb/word.ico");
			$(file.previewElement).find(".dz-image img").css("width", "123px");
		} else if (ext.indexOf("xls") != -1) {
			$(file.previewElement).find(".dz-image img").attr("src", BASE_URL+"/uploads/media/thumb/exel.png");
			$(file.previewElement).find(".dz-image img").css("width", "123px");
		} else if (ext.indexOf("ppt") != -1) {
			$(file.previewElement).find(".dz-image img").attr("src", BASE_URL+"/uploads/media/thumb/ppt.ico");
			$(file.previewElement).find(".dz-image img").css("width", "123px");
		} else if (ext.indexOf("zip") != -1) {
			$(file.previewElement).find(".dz-image img").attr("src", BASE_URL+"/uploads/media/thumb/zip.ico");
			$(file.previewElement).find(".dz-image img").css("width", "123px");
		}
		 else if (ext.indexOf("mp4") != -1 || ext.indexOf("webm") != -1 || ext.indexOf("mkv") != -1 || ext.indexOf("avi") != -1) {
			$(file.previewElement).find(".dz-image img").attr("src", BASE_URL+"/uploads/media/thumb/movie.png");
			$(file.previewElement).find(".dz-image img").css("width", "123px");
		}
	});
})
</script>


<style type="text/css">
.dropzone
{
	position: relative;
	border: 1px solid #cecaca;
	background:#f1f1f1;
	padding: 1em;
}
.dropzone .dz-default.dz-message
{
	font-size: 30px;
	color: #000;
	text-align: center;
}
.dz-complete
{
	border-radius: 20px;
}
</style>
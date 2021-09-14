<?php

$getTable = Yii::$app->getTable;
foreach ($model as $key => $value) {
	$unread = ($value->seen) ? '' : 'inbox__item--active';
	$flagged = ($value->flagged) ? 'fill:#f7c036' : '';
	$bookmarked = ($value->bookmarked) ? 'fill:#f7c036' : '';
	?>

				<div class="intro-y" >
					<div class="inbox__item  inline-block sm:block text-gray-700 dark:text-gray-500 bg-gray-100 dark:bg-dark-1 border-b border-gray-200 dark:border-dark-1 <?=$unread?>">
						<div class="flex px-5 py-3">
							<div class="w-72 flex-none flex items-center mr-5">
								<input class="form-check-input flex-none email-id" type="checkbox"  value="<?=$value->id?>">
								<a href="javascript:updateMark('<?=$value->id?>', 'star')" class="w-5 h-5 flex-none  flex items-center justify-center text-gray-500 ml-4"> <i class="w-4 h-4" data-feather="star" style="<?=$flagged?>" id="star-<?=$value->id?>" data-value="<?=$value->flagged?>"></i> </a>
								<a href="javascript:updateMark('<?=$value->id?>','bookmark')" class="w-5 h-5 flex-none ml-2 flex items-center justify-center text-gray-500"> <i class="w-4 h-4" data-feather="bookmark" style="<?=$bookmarked?>" id="bookmark-<?=$value->id?>" data-value="<?=$value->bookmarked?>"></i> </a>
								<div class="w-6 h-6 flex-none image-fit relative ml-5">
									<!--  <img alt="Midone Tailwind HTML Admin Template" class="rounded-full" src="dist/images/profile-10.jpg"> -->
								</div>
								<div class="inbox__item--sender truncate ml-3" onclick="goToView('<?=$value->id?>')">
									<?php if ($value->status == 1) {
		echo $value->email_from;
	} else {
		echo $value->email_to;
	}
	?>

									</div>
							</div>
							<div class="w-64 sm:w-auto truncate" onclick="goToView('<?=$value->id?>')"> <span class="inbox__item--highlight"><?=$value->subject?></span></div>
							<div class="inbox__item--time whitespace-nowrap ml-auto pl-10" onclick="goToView('<?=$value->id?>')"><?=$getTable->datetime_format($value->udate, 1);?></div>
						</div>
					</div>
				</div>
			<?php }?>
			<script type="text/javascript">
				function goToView(id) {

				    window.location = "<?=Yii::getAlias('@web')?>/mailbox/inbox/view?id=" + id;

				}

				function updateMark(id, name) {
					var val = $("#" + name + '-' + id).attr('data-value');
				    var fill = (val == 1) ? '' : '#f7c036';
				    $.ajax({
				        url: "<?=Yii::getAlias('@web')?>/mailbox/inbox/update-mark?id=" + id + "&name=" + name + "&val=" + val,
				        success: function(data) {
				            $("#" + name + '-' + id).css('fill', fill);
				            $("#" + name + '-' + id).attr('data-value',data);
				        }
				    })
				}
				function updateAll(type) {
					var array = $.map($('.email-id:checked'), function(c){return c.value; })
					if(array.length > 0){
					var ids = btoa(array);

					window.location = "<?=Yii::getAlias('@web')?>/mailbox/inbox/update-all?ids=" + ids +"&type="+type;
				}
				}
				 $("#checkAll").click(function() {
				     $('.email-id').not(this).prop('checked', this.checked);
				 });
			</script>
			<?php if (strpos(strtolower($act), 'all') !== false) {} else {
	$session = Yii::$app->session;
	$ltime = $session['last_sync_time'];
	$ntime = time();
	$diff = ($ntime - $ltime) / 60;
	if ($diff > 1) {
		?>
			<?php $this->render('_frame', ['act' => $act])?>
			<?php }}?>
<?php
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->title = Yii::t('app', 'Mailboxes');
$this->params['breadcrumbs'][] = $this->title;
$getTable = Yii::$app->getTable;
?>
<div class="grid grid-cols-12 gap-6 mt-8">
	<div class="col-span-12 lg:col-span-3 xxl:col-span-2">
		<!-- BEGIN: Inbox Menu -->
<?=$this->render('_menu');?>
		<!-- END: Inbox Menu -->
	</div>
	<div class="col-span-12 lg:col-span-9 xxl:col-span-10">
		<!-- BEGIN: Inbox Content -->
		<div class="intro-y inbox box mt-5">
			<div class="p-5 flex flex-col-reverse sm:flex-row text-gray-600 border-b border-gray-200 dark:border-dark-1">
				<div class="flex items-center sm:ml-auto">
					<div class="dark:text-gray-300">1 - 50 of 5,238</div>
					<a href="javascript:;" class="w-5 h-5 ml-5 flex items-center justify-center dark:text-gray-300"> <i class="w-4 h-4" data-feather="chevron-left"></i> </a>
					<a href="javascript:;" class="w-5 h-5 ml-5 flex items-center justify-center dark:text-gray-300"> <i class="w-4 h-4" data-feather="chevron-right"></i> </a>
					<a href="javascript:;" class="w-5 h-5 ml-5 flex items-center justify-center dark:text-gray-300"> <i class="w-4 h-4" data-feather="settings"></i> </a>
				</div>
			</div>
			<div class="overflow-x-auto sm:overflow-x-visible">
				<?php foreach ($model as $key => $value) {
	$unread = ($value->seen) ? '' : 'inbox__item--active';
	?>

				<div class="intro-y">
					<div class="inbox__item  inline-block sm:block text-gray-700 dark:text-gray-500 bg-gray-100 dark:bg-dark-1 border-b border-gray-200 dark:border-dark-1 <?=$unread?>">
						<div class="flex px-5 py-3">
							<div class="w-72 flex-none flex items-center mr-5">
								<input class="form-check-input flex-none" type="checkbox" >
								<a href="javascript:;" class="w-5 h-5 flex-none ml-4 flex items-center justify-center text-gray-500"> <i class="w-4 h-4" data-feather="star"></i> </a>
								<a href="javascript:;" class="w-5 h-5 flex-none ml-2 flex items-center justify-center text-gray-500"> <i class="w-4 h-4" data-feather="bookmark"></i> </a>
								<div class="w-6 h-6 flex-none image-fit relative ml-5">
									<!--  <img alt="Midone Tailwind HTML Admin Template" class="rounded-full" src="dist/images/profile-10.jpg"> -->
								</div>
								<div class="inbox__item--sender truncate ml-3"><?=$value->email_from;?></div>
							</div>
							<div class="w-64 sm:w-auto truncate"> <span class="inbox__item--highlight"><?=$value->subject?></span> There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomi </div>
							<div class="inbox__item--time whitespace-nowrap ml-auto pl-10"><?=$getTable->datetime_format($value->udate, 1);?></div>
						</div>
					</div>
				</div>
			<?php }?>
			</div>
			<div class="p-5 flex flex-col sm:flex-row items-center text-center sm:text-left text-gray-600">
				<div class="dark:text-gray-300">4.41 GB (25%) of 17 GB used Manage</div>
				<div class="sm:ml-auto mt-2 sm:mt-0 dark:text-gray-300">Last account activity: 36 minutes ago</div>
			</div>
		</div>
		<!-- END: Inbox Content -->
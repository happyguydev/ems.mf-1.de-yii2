<?php
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->title = Yii::t('app', 'User Groups');
$this->params['breadcrumbs'][] = $this->title;
?>

                                    	 <div class="grid grid-cols-12 gap-6 mt-5">
                    <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
                        <a class="button text-white bg-theme-1 shadow-md mr-2" href="<?=Yii::getAlias('@web')?>/admin/user-group/create"><?=Yii::t('app',
	'Add New User Group')?></a>

                        <div class="hidden md:block mx-auto text-gray-600"></div>
                        <!-- <div class="hidden md:block mx-auto text-gray-600">Showing 1 to 10 of 150 entries</div> -->
                        <div class="w-full sm:w-auto mt-3 sm:mt-0 sm:ml-auto md:ml-0 hidden">
                            <div class="w-56 relative text-gray-700 dark:text-gray-300">
                                <input type="text" class="input w-56 box pr-10 placeholder-theme-13" placeholder="Search...">
                                <i class="w-4 h-4 absolute my-auto inset-y-0 mr-3 right-0" data-feather="search"></i>
                            </div>
                        </div>
                    </div>
                    <!-- BEGIN: Data List -->
                    <div class="intro-y col-span-12 overflow-auto">
                    	<table class="table table-report -mt-2">
                            <thead>
                                <tr>
                                	<th class="whitespace-nowrap"><?=Yii::t('app', 'S.No')?></th>
                                    <th class="whitespace-nowrap"><?=Yii::t('app', 'Name')?></th>
                                    <th class="whitespace-nowrap"><?=Yii::t('app', 'Subscriptions')?></th>
                                    <th class="text-center whitespace-nowrap"><?=Yii::t('app', 'Access Level')?></th>
                                    <th class="text-center whitespace-nowrap"><?=Yii::t('app', 'Created At')?></th>
                                    <th class="text-center whitespace-nowrap"><?=Yii::t('app', 'Last Modified')?></th>
                                    <th class="text-center whitespace-nowrap"><?=Yii::t('app', 'Actions')?></th>
                                </tr>
                            </thead>
                            <tbody>
                            		<?php
if (count($model) > 0) {
	foreach ($model as $key => $value) {

		?>
                                <tr class="intro-x">
                                   <td><?=$key + 1?></td>
                                    <td class="text-center"><?=$value['name']?></td>
                                    <td class="text-center"><?=$value->getTotalUser($value)?></td>
                                    <td class="text-center"><?=$value->getLevel($value)?></td>
                                    <td class="text-center"><?=date('Y-m-d H:i:s', strtotime($value['created_at']))?></td>
                                    <td class="text-center"><?=date('Y-m-d H:i:s', strtotime($value['updated_at']))?></td>

                                    <td class="table-report__action w-56">
                                        <div class="flex justify-center items-center">

                                           <!--   <a class="flex items-center mr-3 text-theme-9 tooltip" href="<?=Yii::getAlias('@web')?>/admin/user-group/view?id=<?=$value['name']?>" title="<?=Yii::t('app', 'View')?>"> <i data-feather="eye" class="w-4 h-4 mr-1"></i> <?=Yii::t('app', 'View')?> </a> -->

                                            <a class="flex items-center mr-3 text-theme-1 tooltip" href="<?=Yii::getAlias('@web')?>/admin/user-group/update?id=<?=$value['name']?>" title="<?=Yii::t('app', 'Edit')?>"> <i data-feather="check-square" class="w-4 h-4 mr-1"></i> <?=Yii::t('app', 'Edit')?> </a>


                                             <!--  <a class="flex items-center mr-3 text-theme-6 tooltip" href="<?=Yii::getAlias('@web')?>/admin/user-group/delete?id=<?=$value['name']?>" title="<?=Yii::t('app', 'Delete')?>" data-confirm="<?=Yii::t('app', 'Are you sure you want to delete this user group')?>"> <i data-feather="trash" class="w-4 h-4 mr-1"></i> <?=Yii::t('app', 'Delete')?> </a> -->
                                          </div>
                                      </td>
                                  </tr>
                                  <?php
}
}
?>


                            </tbody>
                        </table>
                    </div>
                </div>
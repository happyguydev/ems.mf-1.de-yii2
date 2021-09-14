<?php
$this->title = Yii::t('app', 'Menu Manager');
$this->params['breadcrumbs'][] = $this->title;

?>

     <link rel="stylesheet" type="text/css" href="<?=Yii::getAlias('@web')?>/themes/admin/dist/css/style.css">

  <div class="alert alert-success show mb-2 mt-3 successMsg" role="alert" style="display: none!important"><?=Yii::t('app', 'Data Saved Successfully!')?></div>
<div class="grid grid-cols-12 gap-6 mt-5">

                    <div class="intro-y col-span-12 lg:col-span-6">



                        <!-- BEGIN: Inline Form -->
                        <div class="intro-y box">
                            <div class="flex flex-col sm:flex-row items-center p-3 bg-theme-1 text-white">
                                <h2 class="font-medium text-base mr-auto">
                                    <?=Yii::t('app', 'Add New')?>
                                </h2>
                                <div class="w-full sm:w-auto flex items-center sm:ml-auto mt-3 sm:mt-0 hidden">
                                    <div class="mr-3">Show example code</div>
                                    <input data-target="#inline-form" class="show-code input input--switch border" type="checkbox">
                                </div>
                            </div>
                            <div class="p-5" id="inline-form">
                                <div class="preview">
                                    <form class="form-inline" id="menu-add">
                                      <div class="row">
                                        <div class="col-md-12">
                                        <input type="text" class="input w-full border col-span-4" id="addInputName" placeholder="Menu Name" required>
                                      </div>
                                      <div class="col-md-12 mt-3">
                                        <input type="url" class="input w-full border col-span-4" id="addInputSlug" placeholder="Menu Url">
                                      </div>
                                      <div class="col-md-12 text-center mt-3">
                                        <button id="addButton" class="col-span-4 button text-white bg-theme-1 w-60 shadow-md mr-2 primary-outline-button"><?=Yii::t('app', 'Save')?></button>
                                      </div>

                                      </div>

                                     </form>
                                </div>
                            </div>
                        </div>

                          <div class="intro-y box mt-5 hidden" id="menu-editor-form">
                            <div class="flex flex-col sm:flex-row items-center p-3 bg-theme-1 text-white">
                                <h2 class="font-medium text-base mr-auto">
                                    <?=Yii::t('app', 'Edit')?> : <span id="currentEditName"></span>
                                </h2>
                                <div class="w-full sm:w-auto flex items-center sm:ml-auto sm:mt-0 hidden">
                                    <div class="mr-3">Show example code</div>
                                    <input data-target="#inline-form" class="show-code input input--switch border" type="checkbox">
                                </div>
                            </div>
                            <div class="p-5" id="inline-form">
                                <div class="preview">
                                    <form class="" id="menu-editor">
                                    <div class="row">
                                      <div class="col-md-12">
                                        <input type="text" class="input w-full border col-span-4" id="editInputName" placeholder="Menu Name" required>
                                      </div>
                                      <div class="col-md-12 mt-3">
                                        <input type="url" class="input w-full border col-span-4" id="editInputSlug" placeholder="Menu Url">
                                      </div>
                                     <div class="col-md-12 text-center mt-3">
                                        <button id="editButton" class="col-span-4 button text-white bg-theme-1 shadow-md mr-2 primary-outline-button"><?=Yii::t('app', 'Save')?></button>
                                    </div>
                                  </div>
                                     </form>
                                </div>
                            </div>
                        </div>
                  </div>




                      <div class="intro-y col-span-12 lg:col-span-6">
                        <div class="intro-y box">
                            <div class="flex flex-col sm:flex-row items-center p-3 bg-theme-1 text-white">
                                <h2 class="font-medium text-base mr-auto text-white">
                                    <?=Yii::t('app', 'Menu Items')?>
                                </h2>
                                <div class="w-full sm:w-auto flex items-center sm:ml-auto mt-3 sm:mt-0">
                                    <button class="button w-30 bg-theme-9 text-white primary-outline-button"  onclick="saveJsonData()"><?=Yii::t('app', 'Save Menu')?></button>

                                </div>
                            </div>
                            <div class="p-5" id="vertical-form">
                                <div class="dd nestable" id="menu-result">
                                   <ol class="dd-list"></ol>
          </div>

                            </div>
                        </div>
</div>
                    </div>


      <div class="row hidden">


      <div class="row output-container">
        <div class="col-md-offset-1 col-md-10">
          <h2 class="text-center">Output:</h2>
          <form class="form">
            <textarea class="form-control" id="json-output" rows="5" style="width: 100%"></textarea>
          </form>
        </div>
      </div>
    </div>

    <div id="loading" >
         <i class="loading_gif" data-loading-icon="tail-spin" class="w-20 h-20"></i>
       </div>
    <script src="<?=Yii::getAlias('@web');?>/themes/admin/dist/js/jquery.nestable.js"></script>
    <script src="<?=Yii::getAlias('@web');?>/themes/admin/dist/js/jquery.nestable++.js"></script>
    <script>
      $('.dd.nestable').nestable({
        maxDepth: 6
      })
        .on('change', updateOutput);

    </script>
    <style type="text/css">
      #loading {
   width: 100%;
   height: 100%;
   top: 0;
   left: 0;
   position: fixed;
   display: none;
   opacity: 0.9;
   background-color: #fff;
   z-index: 99;
   text-align: center;
}

.loading_gif{
  position: absolute;
 top: 40%;
  left:50%;
  z-index: 100;
  width: 60px;
  height: 60px;
}
.primary-outline-button {
    background: white;
    color: #1c3faa;
    border: 1px solid #1c3faa;
}
    </style>
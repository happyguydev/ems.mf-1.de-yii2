<?php
use app\modules\admin\models\MediaFolder;
/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\Media */
$this->title = Yii::t('app', 'Media Gallery');
$folder = MediaFolder::find()->where(['status' => 1])->all();
$can_delete = Yii::$app->Utility->hasAccess('media', 'delete');
$user_id = Yii::$app->user->identity->id;
//$can_move = (Yii::$app->user->identity->user_role == 'admin');
$is_admin = (Yii::$app->user->identity->user_role == 'admin');
?>
<?=$this->render('_share')?>
<div id="galleries">
  <form id="upload-form" action="<?=Yii::getAlias('@web');?>/global/upload" method="post" enctype="multipart/form-data">
    <div id="select-file" onchange="selectFile()">
      <input  id="upload-file"  name="file" type="file"  multiple="multiple"  style="display: none;"/>
    </div>
    <div class="p-text" style="display: none;">
      <h3 align="center"><?=Yii::t('app', 'File Uploading')?>...</h3>
      <div id="progress-div" style="display: none;"><div id="progress-bar"></div></div>
    </div>
    <div id="target-layer"></div>
    <input type="hidden"  id="display-folder-id" name="mediaFolderId" class="form-control" value="">
  </form>
  <!-- Large button group -->
   <?php if (Yii::$app->Utility->hasAccess('media', 'create')) {?>
  <div class="btn-group upload-image">
    <div class="dropdown">
      <button class="dropdown-toggle button px-2 box text-gray-700 dark:text-gray-300">
      <span class="w-5 h-5 flex items-center justify-center"> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus w-4 h-4"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg> </span>
      </button>
      <div class="dropdown-menu w-40">
        <div class="dropdown-menu__content box dark:bg-dark-1 p-2">
          <a href="javascript:void(0)" class="block p-2 transition duration-300 ease-in-out bg-white dark:bg-dark-1 hover:bg-gray-200 dark:hover:bg-dark-2 rounded-md">
            <label for="upload-file">
              <?=\Yii::t('app', 'Upload File');?>
            </label>
          </a>
          <a href="javascript:" class="block p-2 transition duration-300 ease-in-out bg-white dark:bg-dark-1 hover:bg-gray-200 dark:hover:bg-dark-2 rounded-md"  data-toggle="modal" data-target="#button-modal-preview" ><?=\Yii::t('app', 'Create Folder')?></a>
        </div>
      </div>
    </div>
  </div>
<?php }?>
  <div style="height: 60vh; overflow-y:auto;padding: 15px;padding-bottom: 40px;" class="intro-y grid grid-cols-12 gap-3 sm:gap-6 mt-5">
  <span  id="media-folder"  style="display: contents;"></span>
  <span id="gallery" style="display: contents;"></span>
</div>
<?php if (Yii::$app->Utility->hasAccess('media', 'create')) {?>
 <a href="javascript:void(0)" class="block shadow-md p-2 button w-40 bg-white dark:bg-dark-1 dark:hover:bg-dark-2 rounded-md" style="position: absolute;right:0px; bottom: 75px;z-index: 1000">
            <label for="upload-file">
              <?=\Yii::t('app', 'Upload File');?>
            </label>
          </a>
        <?php }?>
  <hr/>
  <div id="pagination-container">
  </div>
</div>
<!--popup for creating folder-->
<div class="modal" id="button-modal-preview" style="z-index: 50000">
  <div class="modal-content" style="position: absolute;right: 33%;width: 400px;top:20%">
    <div class="flex items-center px-5 py-5 sm:py-3 border-b border-gray-200 dark:border-dark-5">
      <h2 class="font-medium text-base mr-auto"><?=Yii::t('app', 'Save Folder')?></h2>
      <button data-dismiss="modal" class="button border bg-theme-24 text-white">
      <i data-feather="x" class="w-5 h-5 text-gray-500"></i></button>
    </div>
    <div class="mt-3 px-5 py-3 ">
      <label for="create-folder"><?=Yii::t('app', 'Folder Name')?></label>
      <input type="text" class="input w-full border mt-2" id="folder-name" name="folderName">
      <div class="text-xs text-gray-600 mt-2">
      <span class="error-folder-name error"></span>
      </div>
    </div>

    <div class="px-5 py-3 text-right border-t border-gray-200 dark:border-dark-5">
      <button type="button"  class="button w-20 bg-theme-24 text-white"  href="javascript:void(0)" data-dismiss="modal" id="folder-close"><?=Yii::t('app', 'Cancel')?></button>
      <button type="button"  class="button w-20 bg-theme-1 text-white" id="create-btn" onclick="createFolder()"><?=Yii::t('app', 'Submit')?></button>
      <button type="button"  class="button w-20 bg-theme-1 text-white" id="update-fbtn"  style="display: none"><?=Yii::t('app', 'Submit')?></button>
    </div>
  </div>
</div>
<!-- <div class="modal bs-example-modal-sm" id="create-folder"  role="dialog" aria-labelledby="mySmallModalLabel">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" href="javascript:void(0)" onclick="closeFolderModal()" aria-label="Close" data-target="#create-folder"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Create Folder</h4>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <label for="create-folder">Folder Name</label>
          <input id="folder-name" name="folderName" class="form-control" value='' />
          <span class="error-folder-name error"></span>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" id="create-btn" onclick="createFolder()">Submit</button>
        <button type="button" class="btn btn-primary" id="update-fbtn"  style="display: none">Submit</button>
        <button type="button" class="btn btn-default" href="javascript:void(0)" onclick="closeFolderModal()">Cancel</button>
      </div>
      </div>
      </div>
      </div> -->
      <div class="search-input-box search ">
        <input type="text" placeholder="<?=Yii::t('app', 'Search')?>..." id="search-inpt" class="search__input input placeholder-theme-13">
      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search search__icon dark:text-gray-300"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
    </div>
    <div class="loading" style="display: none;
      align-items: center;
      justify-content: center;">
      <img src="<?=Yii::getAlias('@web')?>/web/img/loading.gif"/>
    </div>
    <div class="view-details" style="display: none">
      <div class="row">
        <div class="col-md-5" style="display: flex; justify-content: center;">
          <img class="view-image" style="min-height:350px;max-height: 70vh; object-fit:cover;" />
        </div>
        <div class="col-md-7">
          <table>
            <tr>
              <td class="detail-0">
                <?=Yii::t('app', 'Title')?>
              </td>
              <td>:</td>
              <td class="title">
              </td>
            </tr>
            <tr>
              <td class="detail-1">
                <?=Yii::t('app', 'File name')?>
              </td>
              <td>:</td>
              <td class="file-name">
              </td>
            </tr>
            <tr>
              <td class="detail-2">
                <?=Yii::t('app', 'File Type')?>
              </td>
              <td>:</td>
              <td class="file-type">
              </td>
            </tr>
           <!--  <tr class="caption-detail">
              <td class="detail-3">
                Caption
              </td>
              <td>:</td>
              <td class="caption">
              </td>
            </tr>
            <tr class="alternate-detail">
              <td class="detail-4">
                Alternate Text
              </td>
              <td>:</td>
              <td class="alternate-text">
              </td>
            </tr> -->
            <tr>
              <td class="detail-5">
              <?=Yii::t('app', 'Uploaded At')?></td>
              <td>:</td>
              <td class="create-date">
              </td>
            </tr>
          </table>
          <br/>
          <div class="col-md-12">
            <div  class="col-md-10 copy-url">
              <input type="text" class="image-url form-control" value="" id="image-url" readonly="readonly" style="margin-left: -8px;border: 1px solid #46b8da;" />
            </div>
            <div  class="col-md-2 copy-url-btn" style="margin-left: -42px">

              <button typ="button" id="copy-btn" class="btn btn-info" onclick="copyUrl();"><?=Yii::t('app', 'Copy Url')?></button>
            </div>
          </div>
          <br/>
          <br/><br/>
        </div>
        <div class="back_btn">
           <button  id="back-button" onclick="goBack();" title="close">
           <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x w-8 h-8 text-gray-500"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
          </button>
        </div>
      </div>
    </div>
    <!-- place image inot another folder -->
    <div class="placed-here" style="display: none">
      <a href="javascript:void(0)" class="btn btn-warning " id="place-btn" title="<?=Yii::t('app', 'Placed Image')?>"><?=Yii::t('app', 'Placed Here')?></a>
      <a href="javascript:void(0)" class="btn btn-default " id="cancel-place-btn" title="<?=Yii::t('app', 'Cancel')?>"  onclick="cancelMovingImage()"><?=Yii::t('app', 'Cancel')?></a>
    </div>
    <input type="hidden" id="placed-in-folder" value="">
    <script type="text/javascript" src="<?=Yii::getAlias('@web');?>/themes/common/jquery-form/jquery.form.min.js"></script>
    <script type="text/javascript">
      var BASE_URL0 = "<?=Yii::getAlias('@web');?>";
       var user_id = "<?=$user_id?>";
       var can_delete = "<?=$can_delete?>";
       var can_move = "<?=$is_admin?>";
      var is_admin ="<?=$is_admin?>";
    loadMediaJson();
    //for searching images by title
    $(document).ready(function() {
    $("#search-inpt").on("keyup", function() {
    var value = $(this).val().toLowerCase();
    loadMediaJson(value);
    });

    });
     var showAction = function (id, type){
       console.log($("."+type +'-' + id));
       $(".dropdown-menu-media:not(."+type +'-'+ id+")").hide('slow');
       $("."+type +'-'+ id).toggle('show');
    }
    function getPreviousData() {
    var folderId = $('#display-folder-id').val(folderId);
    loadMediaJson("", folderId);
    }
    //get images through json
    function loadMediaJson(searchValue = '', folderId = '') {
    $('.loading').css('display', 'flex');
    $('#galleries').css('display', 'none');
    $("body").addClass("modal-open");
    if (folderId != '') {
    $('#display-folder-id').val(folderId);
    } else {
    $('#display-folder-id').val('');
    }
    $.ajax({
    type: 'get',
    url: BASE_URL0 + '/global/get-media-json?search=' + searchValue + '&folderId=' + folderId,
    dataType: 'html',
    success: function(data) {
    htmlData = '';
    htmlDataFolder = '';
    var res = JSON.parse(data);
    var arr = res.file;
    var arr1 = res.folder;
    var arrCount = (arr) ? Object.keys(arr).length : 0;
    var arr1Count = (arr1) ? Object.keys(arr1).length : 0;
    if (arr1Count > 0 || arrCount > 0) {
    $('#galleries').show();
    if (arr1Count > 0) {
    $.each(arr1, function(key, val) {
    if (val.type == 'link') {
    var src = BASE_URL0 + '/web/img/back-folder.png';
    } else {
    var src = BASE_URL0 + '/web/img/folder-icon.png';
    }
    can_move = (parseInt(user_id) == parseInt(val.created_by)) || can_move;
    can_delete = ((parseInt(user_id) == parseInt(val.created_by))  && can_delete) || is_admin;

    prepareDataFolder(key, val.id, val.name, src, val.type, can_delete, can_move);
    });
    }
    if (arrCount > 0) {
    $.each(arr, function(key, val) {
     can_move = (parseInt(user_id) == parseInt(val.created_by)) || can_move;
     can_delete = ((parseInt(user_id) == parseInt(val.created_by))  && can_delete) || is_admin;
    prepareData(key, val.id, val.title, val.file_name, val.extension, val.created_at, val.caption, val.url, val.alternate_text, can_delete, can_move);
    });
    }
    $('#media-folder').html(htmlDataFolder);
    $('#gallery').html(htmlData);
    paginate(arrCount);
    $('.loading').css('display', 'none');
    } else {
    $('#media-folder').html('');
    $('#gallery').html('');
    $('#pagination-container').hide();
    $('#galleries').show();
    $('.loading').css('display', 'none');
    $('#gallery').html("<h3 class='text-center'> No data found ! </h3>");
    }
    }
    });
    }
    function selectFile() {
    if ($('#upload-file').val()) {
    $('#upload-form').submit();
    }
    }
    $(document).ready(function() {
    $('#upload-form').submit(function(e) {
    var uploadFile = $('#upload-file').val();
    if (uploadFile) {
    e.preventDefault();
    $(this).ajaxSubmit({
    beforeSubmit: function() {
    $("#progress-bar").width('0%');
    $("#progress-div").css('display', 'block');
    $(".p-text").css('display', 'block');
    },
    uploadProgress: function(event, position, total, percentComplete) {
    $("#progress-bar").width(percentComplete + '%');
    $("#progress-bar").html('<div id="progress-status">' + percentComplete + ' %</div>');
    },
    success: function() {
    $("#progress-div").css('display', 'none');
    $(".p-text").css('display', 'none');
    var displayFolderId = $('#display-folder-id').val();
    if (displayFolderId != '') {
    loadMediaJson('', displayFolderId);
    } else {
    loadMediaJson();
    }
    $('#upload-form').trigger("reset");
    }
    });
    return false;
    }
    });
    });
    function createFolder() {
    var folderName = $('#folder-name').val();
    var displayFolderId = $('#display-folder-id').val();
    if (folderName == '') {
    $('.error-folder-name').html('Please enter folder name');
    return false;
    }
    $.ajax({
    type: 'POST',
    url: BASE_URL0 + '/global/create-media-folder?folderName=' + folderName + '&parentId=' + displayFolderId,
    success: function(data) {
       $('#folder-name').val('');
    $('#folder-close').trigger('click');
    if (displayFolderId != '') {
    loadMediaJson('', displayFolderId);
    } else {
    loadMediaJson();
    }
    }
    })
    }
    //for updating folder name
    function updateFolderName(id) {
    var folderName = $('#folder-name').val();
    if (folderName == '') {
    $('.error-folder-name').html('Please enter folder name');
    return false;
    }
    $.ajax({
    type: 'POST',
    url: BASE_URL0 + '/global/update-media-folder?id=' + id + '&folderName=' + folderName,
    success: function(data) {
    $('#create-folder').modal('hide');
    var displayFolderId = $('#display-folder-id').val();
    if (displayFolderId != '') {
    loadMediaJson('', displayFolderId);
    } else {
    loadMediaJson();
    }
    }
    })
    }
    //for deleting folder and image type means image or folder
    function deleteImageFolder(id, type) {
    if (type == 'image') {
    var cc = confirm("Are you sure you want to delete this image? ");
    }
    if (type == 'folder') {
    var cc = confirm("Are you sure you want to delete this folder? ");
    }
    if (cc == true) {
    $.ajax({
    type: 'POST',
    url: BASE_URL0 + '/global/delete-image-folder?id=' + id + '&type=' + type,
    success: function(data) {
    var displayFolderId = $('#display-folder-id').val();
    if (displayFolderId != '') {
    loadMediaJson('', displayFolderId);
    } else {
    loadMediaJson();
    }
    }
    });
    }
    }
    //move file in another folder
    function moveFileAndFolder(id, type) {
    $('.placed-here').show();
    $('#place-btn').attr('onclick', 'moveImageAndFolder(' + id + ',"' + type + '")');
    $('#placed-in-folder').val(id);
    }
    function moveImageAndFolder(id, type) {
    var displayFolderId = $('#display-folder-id').val();
    $.ajax({
    type: 'GET',
    url: BASE_URL0 + '/global/move-image-and-folder?id=' + id + '&mediaFolderId=' + displayFolderId + '&type=' + type,
    success: function(data) {
    $('.placed-here').hide();
    if (displayFolderId > 0) {
    loadMediaJson('', displayFolderId);
    } else {
    loadMediaJson();
    }
    }
    })
    }

      function downloadFile(id) {
        window.open(BASE_URL0 + '/global/download-file?id=' + id, '_blank');
    }
    function cancelMovingImage() {
    $('.placed-here').hide();
    $('#placed-in-folder').val('');
    }
    $('input').keypress(function() {
    $('.error').html('');
    });

    </script>
    <style type="text/css">
      .dropdown-menu-media{
        display: none;
      }
    .btn-group.open .dropdown-toggle {
    -webkit-box-shadow: inset 0 0px 0px rgba(0, 0, 0, 0);
    box-shadow: inset 0 0px 0px rgba(0, 0, 0, 0);
    }
    .error
    {
    color: red;
    }
    .dropdown-menu-media
    {
    left: -86px!important;
    }
    .dark .btn-group .dropdown-menu-media{
      background: rgb(41 49 69);
    }
    .btn-group .dropdown-menu-media {
      background: rgb(255, 255, 255);
    padding: 5px 15px;
    box-sizing: border-box;
    box-shadow: 0px 10px 10px 0px #333;
    z-index: 999;
    line-height: 2.2em;
    }
    .btn-group .dropdown-menu-media li a:hover {
      color: #0e7fe1;
    }
    .img-fluid{
        max-width:100%;
        max-height:100%;
    }
    </style>

    <script type="text/javascript">
      function callApi(token, action, method, params) {
       if (token.length > 6) {
         var xhttp = new XMLHttpRequest();
         xhttp.onreadystatechange = function() {
           if (this.status == 200) {
             var response_data = isJson(xhttp.response);
             if (response_data == true) {
               var parsed = JSON.parse(xhttp.response);
               parsed = JSON.stringify(parsed);
             } else {
               var parsed = xhttp.response;
             }
             if (parsed.status == 403) {
               // action+'-response' is output id where response will print you can change it
               //document.getElementById(action + '-response').innerHTML = parsed.message;
               console.log(parsed.message + " for " + action + " response");
             } else {
               //document.getElementById(action + '-response').innerHTML = parsed;
               console.log(parsed + " for " + action + " response");
                      }
                  }
              };
              if (method == "POST") {
                  xhttp.open("POST", "https://api.iperk.com/site/" + action + "?token=" + token, false);
                  xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                  xhttp.send(params);
              } else {
                  xhttp.open("GET", "https://api.iperk.com/site/" + action + "?token=" + token + "&" + params, false);
                  xhttp.send();
              }
              // console.log(xhttp.response);
          }
      }
  function getAddToCart(product_id,price,qty) {
    //let nameOfFunction = this[event.target.name];
    //let arg1 = event.target.getAttribute('data-product');
    var token = store("iPerkID");
          //actionAddtocart($merchantId, $token, $productId, $quantity, $amount, $cart_id)
          merchantId = 1;
          productId = product_id;
          cart_id = 1;
          amount = price;
          quantity = qty;
          action = "addtocart";
          method = "GET";
          params = "merchantId=" + merchantId + "&productId=" + productId + "&cart_id=" + cart_id + "&amount=" + amount + "&quantity=" + quantity;
          callApi(token, action, method, params)
      }
    function isJson(str) {
          try {
              JSON.parse(str);
          } catch (e) {
              return false;
          }
          return true;
      }

    </script>
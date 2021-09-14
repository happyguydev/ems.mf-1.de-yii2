<?php
?>
<!-- popup for member list-->
<div class="modal" id="button-modal-sharer" style="z-index: 50000 !important" data-file-id="0" data-is-folder="0">
  <div class="modal__content" style="position: absolute;right: 0px;min-height:100vh;margin-top:0px;background: #eee;z-index: 50000">
    <div class="flex items-center px-5 py-5 sm:py-3 border-b border-gray-200 dark:border-dark-5">
      <h2 class="font-medium text-base mr-auto " style="color:#333"><?=Yii::t('app', 'Member List')?></h2>
      <button data-dismiss="modal" class="button border bg-theme-24 text-white">
      <i data-feather="x" class="w-5 h-5 text-gray-500"></i></button>
    </div>
    <div class="mt-3 px-5 py-3 ">
         <div class="box px-5 pt-5 pb-5">
    <div class="relative text-gray-700 dark:text-gray-300">
      <input id="searchbox"  type="text" class="form-control py-3 px-4 border-transparent bg-gray-200 pr-10 placeholder-theme-13" placeholder="<?=Yii::t('app', 'Search here')?>...">
  </div>
</div>
        <div id="share-list-result"></div>
    </div>
  </div>
</div>
<!-- end popup for member list-->
<script type="text/javascript">
function addUserToShare(id, user_id, is_folder) {
    var cls = '.group-user-' + user_id;
    jQuery.ajax({
        method: "GET",
        url: "<?=Yii::getAlias('@web')?>/global/add-share?id=" + id + "&user_id=" + user_id+"&is_folder="+is_folder,
        cache: false,
        success: function(data) {
            $(cls).attr('href', 'javascript:removeUserFromShare(' + id + ', ' + user_id +  ', ' + is_folder + ')');
            $(cls).removeClass('bg-theme-1 btn-primary');
            $(cls).addClass('bg-theme-6 btn-danger');
            $(cls).find('span').addClass('fa-minus');
            $(cls).find('span').removeClass('fa-plus');
        }
    });
}

function removeUserFromShare(id, user_id, is_folder) {
    var cls = '.group-user-' + user_id;

    jQuery.ajax({
        method: "GET",
        url: "<?=Yii::getAlias('@web')?>/global/remove-share?id=" + id + "&user_id=" + user_id+"&is_folder="+is_folder,
        cache: false,
        success: function(data) {
            $(cls).attr('href', 'javascript:addUserToShare(' + id + ', ' + user_id + ', ' + is_folder + ')');
            $(cls).removeClass('bg-theme-6 btn-danger');
            $(cls).addClass('bg-theme-1 btn-primary');
            $(cls).find('span').addClass('fa-plus');
            $(cls).find('span').removeClass('fa-minus');
        }
    });
}
function shareFile(id,is_folder) {
    $('#share-list-result').load("<?=Yii::getAlias('@web');?>/media/share-list?id=" + id + "&is_folder="+is_folder);
}
</script>
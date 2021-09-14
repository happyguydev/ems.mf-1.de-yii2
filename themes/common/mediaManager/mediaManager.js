var htmlData = "";
var htmlDataFolder = "";



function prepareData(key, id, title, fileName, extension, createdAt, caption, url, alternateText, can_delete = 0, can_move =0) {

    if (title.length > 20) {
        var imgTitle = title.substring(0, 21) + '...';
    } else {
        var imgTitle = title;
    }
    htmlData += '<div class="img-div intro-y col-span-12 sm:col-span-4 md:col-span-3 xxl:col-span-2"><div class="file box rounded-md px-5 pt-8 pb-5 px-3 sm:px-5 relative zoom-in" ><a class="w-3/5 file__icon file__icon--image mx-auto" href="javascript:void(0)" onclick="showDetails(' + id + ')"><div class="file__icon--image__preview image-fit"><img class="zoom img-fluid" src="' + url + '" alt=""></div></a>';
    htmlData += '<br/><br>';
    htmlData += '<div class="text">';
    htmlData += '<input type="hidden" id="title-' + id + '" value="' + title + '"><input type="hidden" id="file-name-' + id + '" value="' + fileName + '"><input type="hidden" id="file-type-' + id + '" value="' + extension + '"><input type="hidden" id="file-caption-' + id + '" value="' + caption + '"><input type="hidden" id="file-url-' + id + '" value="' + url + '"><input type="hidden" id="file-text-' + id + '" value="' + alternateText + '"><input type="hidden" id="file-create-date' + id + '" value="' + createdAt + '"><input type="hidden" id="file-create-by-' + id + '" value=""><a href="javascript:void(0)" onclick="showDetails(' + id + ')"><label class="text-style txt-img" title="' + title + '">' + imgTitle + '</label></a></div> ';
      htmlData += '<div class="drop-btn"><div class="btn-group bg-roup" >';
    htmlData += `<a href="javascript:showAction(${id} , 'file');" class="dropdown-toggle gallery-dd "   style="font-weight: bold">...</a>`;
    htmlData += '<ul class="dropdown-menu-media file-' + id + '" >';
     if(can_move){
    htmlData +='<li><a href="javascript:void(0)" onclick=moveFileAndFolder(' + id + ',"image")><i class="fa fa-arrows-alt"></i> '+move_btn_text+'</a></li>';
}
    if(can_delete){
    htmlData += '<li><a href="javascript:void(0)"  onclick=deleteImageFolder(' + id + ',"image")><i class="fa fa-trash"></i> '+remove_btn_text+'</a></li>';
}
    htmlData += '<li><a href="javascript:void(0)"  onclick=downloadFile(' + id + ')><i class="fa fa-download"></i> '+download_btn_text+'</a></li>';
    htmlData += '<li><a href="javascript:void(0)"   onclick="shareFile(' + id + ',0 )" data-toggle="modal" data-target="#button-modal-sharer"><i class="fa fa-users"></i> '+share_btn_text+'</a></li>';
    htmlData += '</ul></div></div>';
    htmlData += '</div></div>';
}

function prepareDataFolder(key, id, name, img, type, can_delete=0, can_move=0) {


    if (name.length > 15) {
        var fldr_title = name.substring(0, 21) + '...';
    } else {
        var fldr_title = name;
    }


    htmlDataFolder += '<div class="img-div intro-y col-span-12 sm:col-span-4 md:col-span-3 xxl:col-span-2"><div class="file box rounded-md px-5 pt-8 pb-5 px-3 sm:px-5 relative zoom-in" ><a class="w-3/5 file__icon file__icon--image mx-auto" href="javascript:void(0)" onclick=loadMediaJson("",' + id + ')><div class="file__icon--image__preview image-fit"><img class="zoom img-fluid" src="' + img + '" alt=""></div></a>';
    htmlDataFolder += '<br/><br>';


    if (type == 'link') {
        htmlDataFolder += '<div class="text" ><a href="javascript:void(0)"><label class="text-style-move" >' + name + '</label></a></div></div></div>';
    } else {

        htmlDataFolder += '<div class="text" ><a href="javascript:void(0)"><label class="text-style" id="folder-name' + id + '" title="' + name + '">' + fldr_title + '</label></a>';
        htmlDataFolder += '<input type="hidden" id="folder-' + id + '"  value="' + name + '"></div>';
        htmlDataFolder += '<div class="drop-btn"><div class="btn-group bg-roup">';
        htmlDataFolder += `<a href="javascript:showAction( ${id}, 'folder');" class="dropdown-toggle folder-dd "  style="font-weight: bold">...</a>`;
        htmlDataFolder += '<ul class="dropdown-menu-media folder-' + id + '">';
         if(can_move){
        htmlDataFolder += '<li><a href="javascript:void(0)" onclick=moveFileAndFolder(' + id + ',"folder")><i class="fa fa-arrows-alt"></i> '+move_btn_text+'</a></li>';
    }
        if(can_delete){
        htmlDataFolder += '<li><a href="javascript:void(0)"  onclick=deleteImageFolder(' + id + ',"folder")><i class="fa fa-trash"></i> '+remove_btn_text+'</a></li>';
    }
         htmlDataFolder += '<li><a href="javascript:void(0)" data-toggle="modal" data-target="#button-modal-sharer"  onclick="shareFile(' + id + ', 1)"><i class="fa fa-users"></i> '+share_btn_text+'</a></li>';
        htmlDataFolder += '</ul></div></div></div></div>';
    }
}

// for pagination
function paginate(numItems) {
    var items = $(".img-div");
    var perPage = 12;
    items.slice(perPage).hide();
    $('#pagination-container').pagination({
        items: numItems,
        itemsOnPage: perPage,
        prevText: "&laquo;",
        nextText: "&raquo;",
        onPageClick: function(pageNumber) {
            var showFrom = perPage * (pageNumber - 1);
            var showTo = showFrom + perPage;
            items.hide().slice(showFrom, showTo).show();
        }
    });
}


//show image details
function showDetails(id) {
    var fileUrl = $('#file-url-' + id).val();
    var title = $('#title-' + id).val();
    var fileName = $('#file-name-' + id).val();
    var createdBy = $('#file-create-by-' + id).val();
    var fileType = $('#file-type-' + id).val();
    var caption = $('#file-caption-' + id).val();
    var alternateText = $('#file-text-' + id).val();
    var createdDate = $('#file-create-date' + id).val();
    $('#galleries').hide();
    $('.search-input-box').hide();
    $('.view-details').show();
    $('.view-image').attr('src', fileUrl);
    $('.title').text(title);
    $('#image-url').val(fileUrl);
    $('.file-name').text(fileName);
    $('.create-date').text(createdDate);
    $('.caption').text(caption);
    $('.file-type').text(fileType);
    $('.alternate-text').text(alternateText);
}

//for copying image url
function copyUrl() {
    var copyText = document.getElementById("image-url");
    copyText.select();
    document.execCommand("copy");
}


//go back to gallery
function goBack() {
    $('#galleries').show();
    $('.search-input-box').show();

    $('.view-details').hide();
}



function getHtml(inputId, inputType) {
    var img = $('#image-url').val();

    if (inputType == 'text' || inputType == 'textarea') {
        insertIntoText(img, inputId, inputType);
    } else {
        var insertHtml = '<img src="' + img + '" alt="">';
        insertIntoTinymce(inputId, insertHtml);
    }
    $('#media').modal('hide');
}


function insertIntoTinymce(textAreaId, insertHtml) {
    insertHtml = insertHtml;
    //  tinymce.getInstanceById(textAreaId).getWin().focus();
    tinymce.execCommand('mceInsertContent', false, insertHtml);
}


function insertIntoText(value, inputId, inputType) {

    if (inputType == 'text') {
        $('#' + inputId).val(value);
    } else {
        $('#' + inputId).text(value);
    }
}


function closeFolderModal() {

    $("#create-folder").modal("hide");
    $("body").addClass("modal-open");

}



function openCreatePopup() {
    $('#create-folder').modal('show');
    $('.modal-title').html('Create Folder Name');
    $('#update-fbtn').hide();
    $('#create-btn').show();
    $('#folder-name').val('');

}


function openUpdatePopup(id) {

    var folderName = $('#folder-name' + id).attr('title');
    $('#create-folder').modal('show');
    $('.modal-title').html('Update Folder Name');
    $('#update-fbtn').show();
    $('#create-btn').hide();
    $('#folder-name').val(folderName);
    $('#update-fbtn').attr('onclick', 'updateFolderName(' + id + ')');

}
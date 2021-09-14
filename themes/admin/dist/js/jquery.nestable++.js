/*jslint browser: true, devel: true, white: true, eqeq: true, plusplus: true, sloppy: true, vars: true*/
/*global $ */

/*************** General ***************/

var updateOutput = function (e) {
  var list = e.length ? e : $(e.target),
      output = list.data('output');
      // console.log(output);

  if (window.JSON) {
    if (output) {
      output.val(window.JSON.stringify(list.nestable('serialize')));
     // console.log(window.JSON.stringify(list.nestable('serialize')));
    }
  } else {
    alert('JSON browser support required for this page.');
  }
};

var nestableList = $(".dd.nestable > .dd-list");

/***************************************/


/*************** Delete ***************/

var deleteFromMenuHelper = function (target) {
  if (target.data('new') == 1) {
    // if it's not yet saved in the database, just remove it from DOM
    target.fadeOut(function () {
      target.remove();
      updateOutput($('.dd.nestable').data('output', $('#json-output')));
    });
  } else {
    // otherwise hide and mark it for deletion
    target.appendTo(nestableList); // if children, move to the top level
    target.data('deleted', '1');
    updateOutput($('.dd.nestable').data('output', $('#json-output')));
    target.fadeOut();
    saveJsonData();
  }
};

var deleteFromMenu = function () {
  var targetId = $(this).data('owner-id');
  var target = $('[data-id="' + targetId + '"]');

  var result = confirm("Do you really want to delete this item?");
  if (!result) {
    return;
  }

  // Remove children (if any)
  target.find("li").each(function () {
    deleteFromMenuHelper($(this));
  });

  // Remove parent
  deleteFromMenuHelper(target);

  // update JSON
  updateOutput($('.dd.nestable').data('output', $('#json-output')));
};

/***************************************/


/*************** Edit ***************/

var menuEditor = $("#menu-editor-form");
var editButton = $("#editButton");
var editInputName = $("#editInputName");
var editInputSlug = $("#editInputSlug");
var currentEditName = $("#currentEditName");

// Prepares and shows the Edit Form
var prepareEdit = function () {
  var targetId = $(this).data('owner-id');
  var target = $('[data-id="' + targetId + '"]');

  editInputName.val(target.data("name"));
  editInputSlug.val(target.data("slug"));
  currentEditName.html(target.data("name"));
  editButton.data("owner-id", target.data("id"));

  console.log("[INFO] Editing Menu Item " + editButton.data("owner-id"));

  menuEditor.fadeIn();
};

// Edits the Menu item and hides the Edit Form
var editMenuItem = function () {
  var targetId = $(this).data('owner-id');
  var target = $('[data-id="' + targetId + '"]');

  var newName = editInputName.val();
  var newSlug = editInputSlug.val();

  target.data("name", newName);
  target.data("slug", newSlug);

  target.find("> .dd-handle").html(newName);

  menuEditor.fadeOut();

  // update JSON
  updateOutput($('.dd.nestable').data('output', $('#json-output')));
};

/***************************************/


/*************** Add ***************/

var newIdCount = Math.floor((Math.random() * 1000) + 1);


var addToMenu = function () {
  $("#loading").show();
  var newName = $("#addInputName").val();
  var newSlug = $("#addInputSlug").val();
  var newId = 'new-' + newIdCount;

  nestableList.append(
    '<li class="dd-item" ' +
    'data-id="' + newId + '" ' +
    'data-name="' + newName + '" ' +
    'data-slug="' + newSlug + '" ' +
    'data-new="1" ' +
    'data-deleted="0">' +
    '<div class="dd-handle">' + newName + '</div> ' +
    '<span class="button-delete btn btn-default btn-xs pull-right" ' +
    'data-owner-id="' + newId + '"> ' +
    '<i class="fa fa-remove tooltip" title="Delete" aria-hidden="true"></i> ' +
    '</span>' +
    '<span class="button-edit btn btn-default btn-xs pull-right" ' +
    'data-owner-id="' + newId + '">' +
    '<i class="fa fa-edit tooltip" title="Edit" aria-hidden="true"></i>' +
    '</span>' +
    '</li>'
  );

  newIdCount++;

  // update JSON
  updateOutput($('.dd.nestable').data('output', $('#json-output')));

  $('#addInputName').val('');
  $('#addInputSlug').val('');
   setTimeout(function(){
        $("#loading").hide();
      },2000);
  // set events
  // $(".dd.nestable .button-delete").on("click", deleteFromMenu);
  // $(".dd.nestable .button-edit").on("click", prepareEdit);
};

var getMenu = function() {
      $.ajax({
    url : BASE_URL+'menu-items/get-data',
    method: 'get',
    success: function(response) {
      $('#menu-result .dd-list').html(response);
    }
  })

}
 getMenu();



var  saveJsonData = function() {
  $("#loading").show();
  var data = window.JSON.stringify($('.dd.nestable').nestable('serialize'))
  console.log(data);
  var jsonData = data == '[]' ? '' : data;
      $.ajax({
    url : BASE_URL+'menu-items/save-data',
    method: 'post',
    data: {data:jsonData,counter:newIdCount},
    dataType: 'json',
    success: function(response) {
      $("#loading").hide();
      $('.successMsg').show();
      setTimeout(function(){
        $('.successMsg').hide();
        location.reload();
      },2000);
    }
  })

  };

  function openUrl(e) {
    var url = $(this).attr("data-slug");
    window.open(url,"_blank");
  }


/***************************************/



$(function () {

  // output initial serialised data
  updateOutput($('.dd.nestable').data('output', $('#json-output')));

  // set onclick events
  editButton.on("click", editMenuItem);

  $(".dd.nestable .button-delete").on("click", deleteFromMenu);

  $(".dd.nestable .button-edit").on("click", prepareEdit);

  $("#menu-editor").submit(function (e) {
    e.preventDefault();
  });
$("#menu-add").submit(function (e) {
    e.preventDefault();
    addToMenu();
  });

});


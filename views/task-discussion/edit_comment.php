<div class="project-comments">
  <div class="comments-add media mt-25">
    <form class="comment-reply" id="myForm1" action="<?=Yii::getAlias('@web');?>/task-discussion/edit-comment?id=<?=$model->id?>" method="post">
      <div class="form-group">
        <textarea class="form-control" name="comment" id="comment1" rows="5" placeholder="Comment here"><?=$model->comment?></textarea>
        <span class="cmnt_error error"></span>
      </div>
      <div class="form-group">
        <button type="button" class="btn btn-primary waves-effect waves-light waves-round" onclick="getValidateThenSubmit(<?=$model->id?>);">Comment</button>
        <!--  <button type="reset" class="btn btn-default grey-600 waves-effect waves-light waves-round">close</button> -->
      </div>
    </form>
  </div>
  <style>
  .error
  {
  color: red!important;
  }
  </style>
  <script type="text/javascript">
  function getValidateThenSubmit(ticket_id) {
      var msg = $("#comment1").val();
      if (msg == '') {
          $('.cmnt_error').text("This field cannot be blank");
          $('#comment1').focus();
          return false;
      } else {
          jQuery.ajax({
              method: "POST",
              url: "<?=Yii::getAlias('@web');?>/task-discussion/edit-comment?id=" + ticket_id,
              data: $("#myForm1").serialize(),
              cache: false,
              success: function(data) {
                  window.location = "<?=Yii::getAlias('@web');?>/task/view?id=" + data,
                      $("#update_comment").removeClass('show');
              }
          });
      }
  }
  $("textarea").keypress(function() {
      hideError();
  })

  function hideError() {
      $(".error").text('');
  }
  $('#comment1').suggest('@', {
    data: users,
    map: function(user) {
        return {
            value: user.username,
            text: '<strong>' + user.username + '</strong> <small>' + user.fullname + '</small>'
        }
    },
    dropdownClass: 'dropdown-menu',
    position: 'caret',
    // events hook
    onshow: function(e) {},
    onselect: function(e, item) {
      $(".suggest").find('.dropdown-menu').addClass('hide');
    },
    onlookup: function(e, item) {
       $(".suggest").find('.dropdown-menu').removeClass('hide');
    }
})
  </script>
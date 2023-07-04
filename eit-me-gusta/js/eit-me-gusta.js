
jQuery(document).ready(function($) {
  $('.me-gusta-button').on('click', function() {
      let button = $(this);
      let postID = button.data('post-id');
      let userID = button.data('user-id');
      let data = {
          post_id: postID,
          user_id: userID,
          action: me_gusta_ajax_object.action,
          nonce: me_gusta_ajax_object.nonce
      };
      console.log(data)

      $.ajax({
          type: "post",
          url: me_gusta_ajax_object.ajax_url,
          data: data,
          success: function(result){
              console.log(result);
              button.toggleClass('like');
          }
      });
  })
});
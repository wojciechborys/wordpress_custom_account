/*jQuery(document).ready(function ($) {
  // Function to load comments asynchronously
  function loadComments(postId) {
    $.ajax({
      url: my_ajax_object.ajaxurl,
      type: "POST",
      data: {
        action: "load_comments_callback",
        post_id: postId,
      },
      success: function (response) {
        console.log(response);
        $("#comments-container").html(response);
      },
    });
  }

  // Function to check for new comments and add the "new" class
  function checkNewComments(postId) {
    $.ajax({
      url: my_ajax_object.ajaxurl,
      type: "POST",
      data: {
        action: "check_new_comments",
        post_id: postId,
      },
      success: function (response) {
        if (response === "new") {
          $("#communication-column").addClass("new");
        } else {
          $("#communication-column").removeClass("new");
        }
      },
    });
  }

 // Event handler for clicking on an application link
  $("body").on("click", ".comments__link", function (e) {
    e.preventDefault();
    let postId = $(this).data("href");
    console.log(postId);
    // Load comments for the selected application
    loadComments(postId);

    // Check for new comments
    checkNewComments(postId);
  });
})

function addComment(postId, commentContent) {
  $.ajax({
    url: my_ajax_object.ajax_url,
    type: "POST",
    data: {
      action: "add_comment_callback",
      post_id: postId,
      comment_content: commentContent,
    },
    success: function (response) {
      if (response.success) {
        // Comment added successfully, update comments section
        updateCommentsSection(response.comment);
      } else {
        // Show error message
        console.error(response.message);
      }
    },
  });
}

// Function to update the comments section
function updateCommentsSection(comment) {
  var commentHtml =
    '<div class="comment ' +
    (comment.is_author ? "right" : "left") +
    '">' +
    "<strong>" +
    comment.author +
    "</strong>: " +
    comment.content +
    "</div>";

  $("#comments-container").append(commentHtml);
}

// Event handler for submitting the comment form
$("body").on("submit", "#comment-form", function (e) {
  e.preventDefault();
  let postId = $(this).find("input[name='post_id']").val();
  let commentContent = $(this).find("textarea[name='comment_content']").val();
  addComment(postId, commentContent);
});
*/

$(document).ready(function() {
	
	$('#photo_modal').on('hidden.bs.modal', function () {
		$("#photo_modal .modal-body").html("");
		$("#photo_modal .modal-title").html("");
	});
	

	$('.lightBoxGallery').masonry({
		// options
		itemSelector: '.galleryItem'			
	});
	
	$(document).on("click", ".btn-like", function(e) {
		e.preventDefault();
		
		var pic_id = $(this).attr("data-id");
		
		$(this).html('<i class="fa fa-circle-o-notch fa-spin"></i>');
		
		var that = $(this);
		
		$.ajax({
			url: base_url + "photo/vote_for_photo_id",
			type: 'POST',
			data: {pic_id: pic_id},
			success: function(data) {
			
				$(".status-vote").remove();
				
				var photo_votes = data.photo_votes;
												
				that.empty().append('<i class="fa fa-times"></i>');
				
				if(data.return == 998) {
					alert(data.cant_like_own_photo);
					that.empty().append('<i class="fa fa-thumbs-up"></i> ' + data.photo_like + ' (<span class="nb_votes_photo">' + photo_votes + '</span>)');
				} else if(data.return == 1) {
					// We add a vote
					photo_votes = parseInt(photo_votes+1);
					
					that.empty().append("<i class='fa fa-check'></i> " + data.you_like_photo + " (<span class='nb_votes_photo'>" + photo_votes + "</span>)");
				} else if(data.return == 2) {
					// We remove a vote
					photo_votes = photo_votes-1;
					
					that.empty().append("<i class='fa fa-thumbs-up'></i> " + data.photo_like + " (<span class='nb_votes_photo'>" + photo_votes + "</span>)");
				}
			}
		});
	});
	
	$(document).on("click", ".btn-comment", function(e) {
		e.preventDefault();
		
		var photo_id = $(this).attr("data-id");
		
		$("#photo_modal .photo-comment-block").html("<textarea data-id='" + photo_id + "' class='form-control photo-comment-form' placeholder='" + write_comment_placeholder_str + "'></textarea>");
	});
	
	$(document).on("keyup", ".photo-comment-form", function(e) {
		e.preventDefault();
		
		var code = (e.keyCode ? e.keyCode : e.which);
		
		var text = $(this).val();
		var photo_id = $(this).attr("data-id");
		var that = $(this);
		

		if(code == 13) {
			that.prop("disabled", true);

			$.ajax({
				url: base_url + "photo/post_comment",
				type: 'POST',
				data: {text: text, pic_id: photo_id},
				success: function(data) 
				{
					if(data.result == 999)
					{
						alert(data.logged_str);
						that.prop("disabled", false);
					}
					else if(data.result == 998)
					{
						alert(data.empty_comment_str);
						that.prop("disabled", false);
					}
					else if(data.result == 2)
					{
						alert(photo_removed_str);
						that.prop("disabled", false);
					}
					else {
						// Reset the textarea val
						that.val("");
						
						var avatar = "";
								
						if((data.user["thumb_url"] == null)) {
							avatar = base_url + "images/avatar.png";
						} else if(data.user["thumb_url"] != "") {
							avatar = base_url + data.user["thumb_url"];
						}
																	
						var content = data.text;
											
						if($("#photo_modal .alert-no-comment")[0]) {
							$(".alert-no-comment").remove();
						}
						
						var modal_content = "";
						
						modal_content += "<div class='photo_comment clearfix'>";
						modal_content += "<div class='p_com_avatar col-sm-2'><img class='img-responsive' src='" + avatar + "' alt='' /></div>";
						modal_content += "<div class='p_com_content'>";
						modal_content += " <a style='color: #565758; margin-left: 5px; font-size: 16px; margin-top: -4px;' class='delete-com pull-right' data-id='" + data.id + "' href='#'><i class='fa fa-times'></i></a>";
						modal_content += "<span class='pull-right p_com_timeago' title=''>Just Now</span>";
						modal_content += "<a href='" + base_url + "user/profile/" + data.user["uid"] + "'>" + data.user["username"] + "</a><br />" + content;
						modal_content += "</div>";
						modal_content += "</div>";
						
						$(".photo-modal-comments").append(modal_content);
						
						that.prop("disabled", false);
					}
				}
			});
			
			return false;
		}
	});
	
	$(document).on("click", ".delete-com", function(e) {
		e.preventDefault();
		
		var com_id = $(this).attr("data-id");
		var that = $(this);
		
		$.ajax({
			url: base_url + "photo/delete_com",
			type: 'POST',
			data: { com_id : com_id },
			success: function(data) 
			{
				if(data.error > 0) {
					alert(data.error_msg);
				} else {
					that.closest(".photo_comment").remove();
				}
			}
		});
	});
	
	$(".galleryItem img").click(function(e) {
		e.preventDefault();
		
		var photo_id = $(this).attr("data-id");
		
		$("#photo_modal .close").after('<i class="fa fa-circle-o-notch fa-spin modal-icon"></i>');
		$("#photo_modal").modal("show");
		
		$.ajax({
			url: base_url + "photo/get_photo",
			type: 'POST',
			data: { photo_id : photo_id },
			success: function(data) 
			{
				if(data.error > 0) {
					alert(data.error_msg);
				} else {
					$("#photo_modal .modal-title").html(data.photo_from);
					
					var modal_content = "<div class='img-modal-placeholder'><img src='" + base_url + data.photo.url + "' class='img-responsive' /></div>";
					
					if(data.photo.st_vote == 0)
						modal_content += "<div class='photo-modal-actions'><a href='#' class='btn btn-danger btn-like' data-id='" + photo_id + "'><i class='fa fa-thumbs-up'></i> " + data.like_photo + " (<span class='nb_votes_photo'>" + data.photo.votes + "</span>)</a> <a href='#' class='btn btn-danger btn-comment' data-id='" + photo_id + "'><i class='fa fa-comments'></i> " + data.comment_photo + " (" + data.photo.comments + ")</a></div>";
					else
						modal_content += "<div class='photo-modal-actions'><a href='#' class='btn btn-danger btn-like' data-id='" + photo_id + "'><i class='fa fa-check'></i> " + data.you_like_photo + " (<span class='nb_votes_photo'>" + data.photo.votes + "</span>)</a> <a href='#' class='btn btn-danger btn-comment' data-id='" + photo_id + "'><i class='fa fa-comments'></i> " + data.comment_photo + " (" + data.photo.comments + ")</a></div>";
					
					modal_content += "<div class='photo-comment-block'></div>";
					modal_content += "<hr />";
					
					modal_content += "<div class='photo-modal-comments'>";

					if(data.comments.length == 0) {
						modal_content += "<div class='alert alert-info alert-centered alert-no-comment'>" + data.no_photo_comment + "</div>";
					} else {
						$.each(data.comments, function(i, comment) {
							
							if(comment.thumb_url == null) {
								var avatar = base_url + "images/avatar.png";
							} else {
								var avatar = base_url + comment.thumb_url;
							}
							
							modal_content += "<div class='photo_comment clearfix'>";
							modal_content += "<div class='p_com_avatar col-sm-2'><img class='img-responsive' src='" + avatar + "' alt='' /></div>";
							modal_content += "<div class='p_com_content'>";
							if(data.photo.uid == data.this_user || comment.uid == data.this_user)
							{
								modal_content += " <a style='color: #565758; margin-left: 5px; font-size: 16px; margin-top: -4px;' class='delete-com pull-right' data-id='" + comment.id + "' href='#'><i class='fa fa-times'></i></a>";
							}
							modal_content += "<span class='pull-right p_com_timeago' title='" + comment.com_date + "'></span>";
							modal_content += "<a href='" + base_url + "user/profile/" + comment.uid + "'>" + comment.username + "</a>";
							modal_content += "<br />" + comment.content + "</div>";
							modal_content += "</div>";
						});
					}
					
					modal_content += "</div>";
										
					$("#photo_modal .modal-body").html(modal_content);
					
					$(".p_com_timeago").timeago();
					
					$("#photo_modal .fa-circle-o-notch").remove();
				}
			}
		});
		
	});
	
	$(".btn-delete").click(function(e) {
		e.preventDefault();
		
		var photo_id = $(this).attr("data-id");
		var that = $(this);
	
		var r = confirm(photo_p_sure_delete_photo_str);
		if (r == true) {
			$.ajax({
				url: base_url + "photo/delete_photo",
				type: 'POST',
				data: {
					photo_id: photo_id	
				},
				success: function(data) {
					if(data.error == 0) {
						that.removeClass("alert-danger").addClass("alert-success").addClass("disabled").html("<i class='fa fa-check'></i>");
					} else if(data.error == 500) {
						alert("You can't delete in demo mode");
					}
				}
			});
		}
	});
			
});
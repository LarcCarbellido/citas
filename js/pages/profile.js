$(document).ready(function() {
	$(".timeago_timeline").timeago();
	
	$('.avatar_user_profile').nailthumb();
	$(".about").emoticonize();
	
	$(".btn-poke").click(function(e) {
		e.preventDefault();
		
		$("#options-modal").modal("hide");
		$("#poke_modal").modal("show");
	});
	
	$('a[data-toggle=tab]').each(function () {
		var $this = $(this);

		$this.on('shown.bs.tab', function () {
			$('.lightBoxGallery').masonry({
				// options
				itemSelector: '.galleryItem'			
			});
		}); //end shown
	});  //end each
	
	$(".add_photos").click(function(e) {
		e.preventDefault();
		
		window.location = base_url + "";
	});
	
	$(".pf_last_online").timeago();
	
	$('#photo_modal').on('hidden.bs.modal', function () {
		$("#photo_modal .modal-body").html("");
		$("#photo_modal .modal-title").html("");
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
						modal_content += "<a href='" + base_url + "user/profile/" + data.user["uid"] + "'>" + data.user["username"] + "</a><br />" + content;
						modal_content += " <a style='color: #565758; margin-left: 5px; font-size: 16px; margin-top: -4px;' class='delete-com pull-right' data-id='" + data.id + "' href='#'><i class='fa fa-times'></i></a>";
						modal_content += "<span class='pull-right p_com_timeago' title=''>Just Now</span>";
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
	
	$(".galleryItem").click(function(e) {
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
					
					$(".btn-report-photo").attr("data-id", photo_id);
					
					$("#photo_modal .modal-title").html(data.photo_from);
										
					if(data.photo.is_fake == 0)
						var modal_content = "<div class='img-modal-placeholder'><img src='" + base_url + data.photo.url + "' class='img-responsive' /></div>";
					else
						var modal_content = "<div class='img-modal-placeholder'><img src='" + data.photo.url + "' class='img-responsive' /></div>";
					
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
	
	$(document).on("click", ".btn-block-u", function(e) {
	    e.preventDefault();
	    
	    var profile_id = $(this).attr("data-user-id");
		
		var r = confirm(sure_want_block_str);
		var that = $(this);
		if(r) {
			$.ajax({
				url: base_url + "user/block",
				type: 'POST',
				data: { profile_id : profile_id},
				success: function(data) 
				{
					if(data.result == 999) {
						alert(cant_block_not_logged_str);
					} else if(data.result == 500) {
						alert(cant_demo_mode_str);
					} else if(data.result == 998) {
						alert(cant_block_yourself_str);
					} else {
						that.removeClass("btn-block-u").addClass("btn-unblock-u").html('<i class="fa fa-smile-o"></i> ' + unblock_user_str);
					}
				}
			});
		}
	    
    });
    
    $(document).on("click", ".btn-unblock-u", function(e) {
	    e.preventDefault();
	    
	    var profile_id = $(this).attr("data-user-id");
		
		var r = confirm(sure_want_unblock_str);
		var that = $(this);
		if(r) {
			$.ajax({
				url: base_url + "user/unblock",
				type: 'POST',
				data: { profile_id : profile_id},
				success: function(data) 
				{
					if(data.result == 999) {
						alert(cant_block_not_logged_str);
					} else {
						that.removeClass("btn-unblock-u").addClass("btn-block-u").html('<i class="fa fa-frown-o"></i> ' + block_user_str);
					}
				}
			});
		}
	    
    });
    
    $(document).on("click", ".btn-report-photo", function(e) {
	    e.preventDefault();
	    
	    var photo_id = $(this).attr("data-id");
		
		var r = confirm(report_photo_alert_str);
		if(r) {
			$.ajax({
				url: base_url + "photo/report",
				type: 'POST',
				data: { photo_id : photo_id},
				success: function(data) 
				{
					if(data.result == 999) {
						alert(data.error_msg);
					} else if(data.result == 998) {
						alert(data.error_msg);
					} else {
						alert(photo_reported_str);
					}
				}
			});
		}
	    
    });
	
	$(".btn-report").click(function(e) {
	    e.preventDefault();
	    
	    var profile_id = $(this).attr("data-user-id");
		
		var r = confirm(report_user_alert_str);
		if(r) {
			$.ajax({
				url: base_url + "user/report",
				type: 'POST',
				data: { profile_id : profile_id},
				success: function(data) 
				{
					if(data.result == 999) {
						alert(report_user_logged_in_str);
					} else if(data.result == 998) {
						alert(cant_report_yourself_str);
					} else {
						alert(user_reported_str);
					}
				}
			});
		}
	    
    });
	
	$(".btn-msg").click(function(e) {
		e.preventDefault();
		
		$("#options-modal").modal("hide");
		$("#msg_modal").modal("show");
	});
	
	$(".btn-love").click(function(e) {
		e.preventDefault();
		
		var profile_id = $(this).attr("data-profile-id");
		var that = $(this);
		
		$.ajax({
			url: base_url + "user/post_love",
			type: 'POST',
			data: {profile_id: profile_id},
			success: function(data) {
				if(data.result == 1)
				{
					that.addClass("loved");
					that.removeClass("dim");
					that.html('<i class="fa fa-heart"></i> ' + loved_str);
				} else if(data.result == 2) {
					that.removeClass("loved");
					that.addClass("dim");
					
					that.html('<i class="fa fa-heart"></i> ' + love_str);
				} else {
					alert(cant_love_yourself_str);
				}
			}
		});
	});
	
	$(".btn-submit-send-pm").click(function(e) {
		var msg = $(".form-pm-text").val();
		var user_id = $(this).attr("data-user-id");
		
		$.ajax({
			url: base_url + "pm/send_pm",
			type: 'POST',
			data: { user_id : user_id, message : msg },
			success: function(data) 
			{
				var res = data.result;
				
				if(res == 999) {
					$(".send_pm_errors").html("<i class='fa fa-times-circle'></i><br />" + not_logged_in_str).fadeIn();
				} else if(res == 998) {
					$(".send_pm_errors").html("You need to write something!").fadeIn();
				}  else if(res == 997) {
					$(".send_pm_errors").html("<i class='fa fa-times-circle'></i><br />" + cant_send_yourself_pm_str).fadeIn();
				} else if(res == 996) {
					$(".send_pm_errors").html("<i class='fa fa-times-circle'></i><br />" + user_does_not_exist_str).fadeIn();
				} else if(res == 995) {
					window.location = base_url + "user/firstlogin?redirect=true";
				} else if(res == 500) {
					$(".send_pm_errors").html("<i class='fa fa-times-circle'></i><br />" + cant_demo_mode_str).fadeIn();
				} else if(res == 994) {
					$(".send_pm_errors").html("<i class='fa fa-times-circle'></i><br />" + user_blocked_you_str).fadeIn();
				} else {
					$(".send_pm_errors").html(pm_sent_str).removeClass("alert-danger").addClass("alert-success").fadeIn();
					setTimeout(function() { $("#send-pm").modal("hide"); }, 2000);
					
					$(".form-pm-text").val("");
					$("#msg_modal").modal("hide");
				}
			}
		});
	});
	
	$('.btn-poke.btn-poked').tooltip({placement: 'top',trigger: 'manual'}).tooltip('show');
	
	$(".btn-accept-friend").tooltip({placement: 'top',trigger: 'manual'}).tooltip('show');
	
	$(".btn-accept-friend").click(function(e) {
		e.preventDefault();
		
		var user_id = $(this).attr("data-user-id");
		var that = $(this);
		
		$(this).html('<i class="fa fa-circle-o-notch fa-spin"></i>');
		
		$.ajax({
			url: base_url + "user/accept_friend",
			type: 'POST',
			data: {user_id: user_id},
			success: function(data) {
				
				if(data == 999)
				{
					alert(not_logged_in_str);
					
					window.location = base_url;
					
					that.html('<i class="fa fa-user-plus"></i> ' + accept_request_btn_str);
				} else if(data == 998) {
					alert(user_does_not_exist_str);
					that.html('<i class="fa fa-user-plus"></i> ' + accept_request_btn_str);
				} else if(data == 1) {
					
					that.attr("disabled", "disabled");
					that.html('<i class="fa fa-check"></i>');
				}
			}
		});
	});
	
	$(".btn-yeah-poke").click(function(e) {
		e.preventDefault();
		
		var user_id = $(this).attr("data-user-id");
		var that = $(this);
		
		$.ajax({ 
            url: base_url + "user/send_request",
            type: "POST",
            dataType: "json",
            data: {user_id : user_id},
            success: function(data) {
	            if(data.result == 999)
	            {
		            $("#poke_modal .modal-body").html("<div class='alert alert-centered alert-danger'>" + not_logged_in_str + "</div>");
	            } else if(data.result == 998) {
		            $("#poke_modal .modal-body").html("<div class='alert alert-centered alert-danger'>" + cant_send_yourself_request_str + "</div>");
	            } else if(data.result == 997) {
		            $("#poke_modal .modal-body").html("<div class='alert alert-centered alert-danger'>" + request_already_sent_str + "</div>");
	            } else if(data.result == 500) {
		            $("#poke_modal .modal-body").html("<div class='alert alert-centered alert-danger'>" + cant_demo_mode_str + "</div>");
	            } else if(data.result == 996) {
		            $("#poke_modal .modal-body").html("<div class='alert alert-centered alert-danger'>" + cant_request_blocked_str + "</div>");
	            } else {
		            
		            if(data.gender == 0) {
			            var word_gender = his_str;
		            } else {
			            var word_gender = her_str;
		            }
		            
		            $('.btn-poke').addClass("btn-poked").removeClass("btn-primary").addClass("btn-default").html('<i class="fa fa-user-plus"></i> ' + sent_req_str);
		            $('.btn-poke.btn-poked').tooltip({placement: 'top',trigger: 'manual'}).tooltip('show');
		            
		            $("#poke_modal .modal-body").html("<div class='alert alert-centered alert-success'>" + request_sent_str);
	            }
	        }
	    });
	});

});
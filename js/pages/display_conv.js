$(document).ready(function() {
	$('.nailthumb-msg-container').nailthumb();
	$(".pm-date").timeago();
	$(".message").emoticonize();
	
	$(".chat-discussion").scrollTop(100000);
	
	window.setInterval(function(){
		var last_message_id = $(".chat-message:last").attr("data-id");
				
		// Live refresh
		$.ajax({
			url: base_url + "pm/refresh_conv",
			type: 'POST',
			data: {conv_id : conv_id, last_message_id: last_message_id},
			success: function(data) {
				
				$.each(data.last_messages, function(i, item) {
					var message = item;
					
					var avatar = "";
												
					if(message.thumb_url == null || message.photostatus == 0) {
						avatar = base_url + "images/avatar.png";
					} else {
						avatar = base_url + message.thumb_url;
					}
					
					if(message.gender == 0) {
						var gender_user_color = "male_color";
					} else {
						var gender_user_color = "female_color";
					}
					
					if(message.user_id == user_id) {
						var msg_dir = "left";
					} else {
						var msg_dir = "right";
					}
					
					var block_msg = '<div class="chat-message ' + msg_dir + '" data-id="' + message.mid + '">';
					block_msg += '<a class="nailthumb-msg-container" href="' + base_url + 'user/profile/' + message.user_id + '"><img width="62" alt="" src="' + avatar + '" class="message-avatar" /></a>';
					block_msg += '<div class="message">';
					block_msg += '<a class="message-author ' + gender_user_color + '" href="' + base_url + 'user/profile/' + message.user_id + '">' + message.username + '</a>';
					block_msg += '<span class="message-date text-muted pm-date" title="' + message.date + 'Z"></span>';
					block_msg += '<span class="message-content">';
					block_msg += message.content;
					block_msg += '</span>';
					block_msg += '</div>';
					block_msg += '</div>';
					
					
					$(".chat-message").last().after(block_msg);
					$('.nailthumb-msg-container').nailthumb();
					$(".pm-date").timeago();
					$(".message").emoticonize();

					$(".chat-discussion").scrollTop(100000);
					
				});
			}
		});
	}, 2000);
	
	$(".btn-send-reply").click(function(e) {
		e.preventDefault();
		
		var conv_id = $(this).attr("data-conv-id");
		var user_id = $(this).attr("data-user-id");
		var content = $(".pm-write-answer-textarea").val();
		
		$(this).html('<i class="fa fa-circle-o-notch fa-spin"></i>');
		$(this).addClass("disabled");
		
		var that = $(this);
		
		$.ajax({
			url: base_url + "pm/send_reply",
			type: 'POST',
			data: {conv_id : conv_id, content: content, user_id : user_id},
			success: function(data) {
				var res = data.result;
					
				if(res == 999) {
					alert(not_logged_in_str);
				
					that.html(send_reply_str);
					that.removeClass("disabled");
				} else if(res == 998) {
					alert(write_something_str);
				
					that.html(send_reply_str);
					that.removeClass("disabled");
				} else if(res == 500) {
					alert(cant_demo_mode_str);
				
					that.html(send_reply_str);
					that.removeClass("disabled");
				} else if(res == 997) {
					alert(conv_not_exist_str);
				
					that.html(send_reply_str);
					that.removeClass("disabled");
				} else if(res == 996) {
					alert(user_blocked_you_str);
				
					that.html(send_reply_str);
					that.removeClass("disabled");
				} else {										
					var avatar = "";
												
					if(data.user.thumb_url == null || data.user.photostatus == 0) {
						avatar = base_url + "images/avatar.png";
					} else {
						avatar = base_url + data.user.thumb_url;
					}
					
					$(".pm-write-answer-textarea").val("");

					if(data.user["gender"] == 0) {
						var gender_user_color = "male_color";
					} else {
						var gender_user_color = "female_color";
					}
					
					$('.nailthumb-msg-container').nailthumb();
					$(".pm-date").timeago();
					
					that.html(send_reply_str);
					that.removeClass("disabled");
					
					$(".chat-discussion").scrollTop(100000);
					
				}
			}
		});
	});

	String.prototype.replaceArray = function(find, replace) {
	  var replaceString = this;
	  for (var i = 0; i < find.length; i++) {
	    replaceString = replaceString.replace(find[i], replace[i]);
	  }
	  return replaceString;
	};
});
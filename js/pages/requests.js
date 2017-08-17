$(document).ready(function() {
	$('.thumbnail').nailthumb();
	
	$(".love_button").click(function(e) {
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
					that.html('<i class="fa fa-heart"></i>');
				} else if(data.result == 2) {
					that.removeClass("loved");
					that.addClass("dim");
					
					that.html('<i class="fa fa-heart"></i>');
				} else {
					alert(cant_love_yourself_str);
				}
			}
		});
	});
	
	$(".btn-poke-back").click(function(e) {
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
					
				} else if(data == 998) {
					alert(friend_request_does_not_exist_str);
				} else if(data == 1) {
					
					var user_poke_thumb = that.closest(".user_poke_friends").find(".thumb");
					
					var user_poke_friends_hover_height = user_poke_thumb.outerHeight(false);
					var user_poke_friends_hover_width = user_poke_thumb.outerWidth(false)-user_poke_thumb.css("marginRight").replace('px', '');
							
					var user_poke_friends_hover = that.closest(".user_poke_friends").find(".user_poke_friends_hover");
												
					user_poke_friends_hover.css("height", user_poke_friends_hover_height);
					user_poke_friends_hover.css("width", user_poke_friends_hover_width);
					
					user_poke_friends_hover.fadeIn();
					that.html('<i class="fa fa-check"></i>');
				}
			}
		});
	});
});
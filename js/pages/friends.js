$(document).ready(function() {
	$('.thumbnail').nailthumb();
		
	$(".btn-send-friend-pm").click(function(e) {
		e.preventDefault();
		
		var user_id = $(this).attr("data-user-id");
		
		$(".btn-submit-send-pm").attr("data-user-id", user_id);
		
		$("#msg_modal .send_pm_errors").hide();
		$("#msg_modal .form-pm-text").val("");
		$("#msg_modal").modal("show");
		
		
	});
	
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
	
	$(".btn-submit-send-pm").click(function(e) {
		e.preventDefault();
		
		var msg = $(".form-pm-text").val();
		var user_id = $(this).attr("data-user-id");
		
		var that_html = $(this).html();
		var that = $(this);
		
		$(this).html('<i class="fa fa-circle-o-notch fa-spin"></i>');
		$(this).addClass("disabled");
		
		$.ajax({
			url: base_url + "pm/send_pm",
			type: 'POST',
			data: { user_id : user_id, message : msg },
			success: function(data) 
			{
				var res = data.result;
				
				if(res == 999) {
					$(".send_pm_errors").html("<i class='fa fa-times-circle'></i><br />" + not_logged_in_str).fadeIn();
				} else if(res == 500) {
					$(".send_pm_errors").html("<i class='fa fa-times-circle'></i><br />" + cant_demo_mode_str).fadeIn();
				} else if(res == 998) {
					$(".send_pm_errors").html(pm_write_something_str).fadeIn();
				} else if(res == 996) {
					$(".send_pm_errors").html("<i class='fa fa-times-circle'></i><br />" + user_does_not_exist_str).fadeIn();
				} else if(res == 995) {
					window.location = base_url + "user/firstlogin?redirect=true";
				} else if(res == 994) {
					$(".send_pm_errors").html("<i class='fa fa-times-circle'></i><br />" + user_blocked_you_str).fadeIn();
				} else {
					$(".send_pm_errors").html(pm_sent_str).removeClass("alert-danger").addClass("alert-success").fadeIn();
					setTimeout(function() { $("#send-pm").modal("hide"); }, 2000);
					
					$(".form-pm-text").val("");
				}
				
				that.html(that_html);
				that.removeClass("disabled");
			}
		});
	});

});
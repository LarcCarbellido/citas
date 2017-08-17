$(document).ready(function() {
	$(".btn-save").click(function(e) {
		e.preventDefault();
		
		$(this).html('<i class="fa fa-circle-o-notch fa-spin"></i>');
		
		var fb_url = $(".fb_url").val();
		var twitter_url = $(".twitter_url").val();
		var instagram_url = $(".instagram_url").val();
		var gplus_url = $(".gplus_url").val();
				
		var that = $(this);
		
		$.ajax({
			url: base_url + "admin/save_social_profiles",
			type: 'POST',
			data: {fb_url: fb_url, twitter_url: twitter_url, instagram_url: instagram_url, gplus_url: gplus_url},
			success: function(data) {
				$(".alert-error-settings").empty();
				
				if(data.error > 0)
				{
					$(".alert-error-settings").html(data.error_msg).fadeIn();
					$("html, body").animate({scrollTop: 0}, 1000);
					
					that.html('<i class="fa fa-check"></i> Save Changes');
				} else {
					window.location = base_url + "admin/social_profiles?status=ok";
				}
			}
		});
	});
});
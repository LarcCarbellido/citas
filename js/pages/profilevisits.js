$(document).ready(function() {
	$('.thumbnail').nailthumb();
	
	$(".love_button i").click(function(e) {
		e.preventDefault();
		
		var profile_id = $(this).parent().attr("data-profile-id");
		var that = $(this).parent();
		
		$.ajax({
			url: base_url + "user/post_love",
			type: 'POST',
			data: {profile_id: profile_id},
			success: function(data) {
				if(data.result == 1)
				{
					that.addClass("loved");
				} else if(data.result == 2) {
					that.removeClass("loved");
				} else {
					alert("Whoops! You can't love your own profile!");
				}
			}
		});
	});
	
});
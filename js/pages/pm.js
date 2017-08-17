$(document).ready(function() {
	$(".pm-date-left").timeago();
	
	$(".pm-conv-block").click(function(e) {
		e.preventDefault();
		
		var conv_id = $(this).attr("data-conv-id");
		window.location = base_url + "pm/conversation/" + conv_id;
	});

});
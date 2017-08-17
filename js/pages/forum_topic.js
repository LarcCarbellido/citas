$(document).ready(function() {
	$('.nailthumb-msg-container').nailthumb();
	$(".pm-date").timeago();
	
	$(".message").emoticonize();
	
	$(".delete_topic").click(function(e) {
		e.preventDefault();
		
		var url = $(this).attr("href");
		
		var r = confirm("Are you sure you want to delete this topic ?");
		if (r == true) {
			window.location = url;
		}
	});
	
	$(".delete_answer").click(function(e) {
		e.preventDefault();
		
		var url = $(this).attr("href");
		
		var r = confirm("Are you sure you want to delete this answer ?");
		if (r == true) {
			window.location = url;
		}
	});
});
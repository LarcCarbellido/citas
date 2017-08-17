$(document).ready(function() {
	$('.thumbnail').nailthumb();	
	
	$("#enable_forum").change(function() {
	    if(this.checked) {
			var enabled = 1;
	    } else {
		    var enabled = 0;
	    }
	    
	    $(".loading_placeholder_forum").show();

		$.ajax({
			url: base_url + "admin/change_forum_state",
			type: 'POST',
			data: {enabled: enabled},
			success: function(data) {
				if(data.result > 1) {
					alert(data.error_msg);
					$(".loading_placeholder_forum").hide();
				} else {
					$(".loading_placeholder_forum").fadeOut();
				}
			}
		});

	});
	
	$(".badge-delete").click(function(e) {
		e.preventDefault();
		
		var id = $(this).attr("data-id");
		var that = $(this);
		
		var r = confirm("Are you sure you want to delete this category? It will also delete all of the messages / topics contained.");
		if(r) {
			$.ajax({
				url: base_url + "admin/delete_forum_category",
				type: 'POST',
				data: {id: id},
				success: function(data) {
					if(data.error == 999) {
						alert(data.error_msg);
					} else if(data.error == 500) {
						alert(data.error_msg);
					} else {
						window.location = base_url + "admin/forum?action=cat_deleted";
					}
				}
			});
		}
	});
});
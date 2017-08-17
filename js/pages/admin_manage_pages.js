$(document).ready(function() {	
	$(".badge-delete").click(function(e) {
		e.preventDefault();
		
		var id = $(this).attr("data-id");
		var that = $(this);
		
		var r = confirm("Are you sure you want to delete this page?");
		if(r) {
			$.ajax({
				url: base_url + "admin/delete_page",
				type: 'POST',
				data: {id: id},
				success: function(data) {
					if(data.error == 999) {
						alert(data.error_msg);
					} else if(data.error == 500) {
						alert(data.error_msg);
					} else {
						window.location = base_url + "admin/pages?action=page_deleted";
					}
				}
			});
		}
	});
});
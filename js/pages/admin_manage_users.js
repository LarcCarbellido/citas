$(document).ready(function() {
	$('.thumbnail').nailthumb();	
	
	$(".btn-search").click(function(e) {
		e.preventDefault();
		
		var username = $(".form-username").val();
		
		if(username == "") {
			alert("Please type a username");
		} else {
			$.ajax({
				url: base_url + "admin/search_user",
				type: 'POST',
				data: {username: username},
				success: function(data) {
					if(data.error == 999) {
						alert(data.error_msg);
					} else if(data.error == 998) {
						alert(data.error_msg);
					} else {
						window.location = base_url + "admin/edit_user/" + data.user_id;
					}
				}
			});
		}
	});
	
	$(".btn-delete-user").click(function(e) {
		e.preventDefault();
		
		var user_id = $(this).attr("data-user-id");
		var that = $(this);
		
		var r = confirm("Are you sure you want to delete this user?");
		if(r) {
			$.ajax({
				url: base_url + "admin/delete_user",
				type: 'POST',
				data: {user_id: user_id},
				success: function(data) {
					if(data.error == 999) {
						alert(data.error_msg);
					} else if(data.error == 500) {
						alert(data.error_msg);
					} else if(data.error == 998) {
						alert(data.error_msg);
					} else {
						that.html("<i class='fa fa-check'></i> DELETED");
						that.removeClass("btn-danger").addClass("btn-success");
					}
				}
			});
		}
	});
});
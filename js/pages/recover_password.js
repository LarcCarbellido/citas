$(document).ready(function() {
	
	$(".btn-update-password").click(function(e) {
		e.preventDefault();
		
		var encrypt_id = $(".encrypt_id").val();
		var user_id = $(".rec_user_id").val();
		
		var password1 = $(".password1").val();
		var password2 = $(".password2").val();
		
		var that = $(this);
		that.html('<i class="fa fa-circle-o-notch fa-spin"></i>');
		that.prop("disabled", true);
		
		$.ajax({
			url: base_url + "user/save_password_recovery",
			type: 'POST',
			data: {encrypt_id: encrypt_id, user_id: user_id, password1: password1, password2: password2},
			success: function(data) {
				if(data.result == 999) 
				{
					$(".alert-recovery-password").html(hack_password_error_str);
					$(".alert-recovery-password").fadeIn();
					that.prop("disabled", false);
				} else if(data.result == 998) {
					$(".alert-recovery-password").html(password_dont_match_str);
					$(".alert-recovery-password").fadeIn();
					that.prop("disabled", false);
				} else if(data.result == 997) {
					$(".alert-recovery-password").html(password_4_chars_error_str);
					$(".alert-recovery-password").fadeIn();
					that.prop("disabled", false);
				} else {
					$(".password_form").empty();
					$(".password_form").html("<div class='alert alert-success' style='text-align:center; font-size:18px;'><i class='fa fa-check-circle' style='font-size:40px;'></i><br />" + password_recovery_success_str + "</div>");
				}
			}
		});
	});
});
$(document).ready(function() {
	
	$(".btn-delete-user").click(function(e) {
		e.preventDefault();
		
		var user_id = $(this).attr("data-user-id");
		var that = $(this);

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
					window.location = base_url + "admin/manage_users?action=deleted_user"
				}
			}
		});
	});
	
	$(".btn-finish-edit-coins").click(function(e) {
		e.preventDefault();
		
		var user_id = $(this).attr("data-id");
		
		var that = $(this);
		that.html('<i class="fa fa-circle-o-notch fa-spin"></i>');
		
		var coins = $(".user_coins").val();
		
		$.ajax({
			url: base_url + "admin/editusercoins",
			type: 'POST',
			data: {user_id : user_id, coins: coins},
			success: function(data) {
				if(data.error == 500) {
					alert(data.error_msg);
					that.html('Edit Coins');
				} else {
					window.location = base_url + "admin/edit_user/" + user_id + "?action=coins_updated"
				}
			}
		});
	});
	
	// Get user infos
	$.ajax({
		url: base_url + "admin/get_user_edit_infos",
		data: {user_id: user_id},
		type: 'POST',
		success: function(data) {
			var gender = data.user.gender;
			var birthday = data.user.birthday;
			
			var date = new Date(birthday) ;
			var birthday_day = date.getUTCDate();
			var birthday_month = date.getUTCMonth();
			var birthday_year = date.getUTCFullYear();
			var country = data.user.country;
			var city = data.user.city;
			var relation = data.user.relation_type;
			var about = data.user.about;
			
			var app_pref_pm = data.user.app_pref_pm;
			var app_pref_forum = data.user.app_pref_forum;
			var app_pref_snapchat_request = data.user.app_pref_snapchat_request;
			
			if(app_pref_forum == 1)
				$('.app_pref_forum').prop('checked', true);
				
			if(app_pref_pm == 1)
				$('.app_pref_pm').prop('checked', true);
				
			if(app_pref_snapchat_request == 1)
				$('.app_pref_snapchat_request').prop('checked', true);
			
			
			if(birthday_month == 0)
				birthday_month = "january";
			else if(birthday_month == 1)
				birthday_month = "february";
			else if(birthday_month == 2)
				birthday_month = "march";
			else if(birthday_month == 3)
				birthday_month = "april";
			else if(birthday_month == 4)
				birthday_month = "may";
			else if(birthday_month == 5)
				birthday_month = "june";
			else if(birthday_month == 6)
				birthday_month = "july";
			else if(birthday_month == 7)
				birthday_month = "august";
			else if(birthday_month == 8)
				birthday_month = "september";
			else if(birthday_month == 9)
				birthday_month = "october";
			else if(birthday_month == 10)
				birthday_month = "november";
			else if(birthday_month == 11)
				birthday_month = "december";
				
			if(gender == 0)
				gender = "male";
			else
				gender = "female";
			
			$(".gender").val(gender);
			$(".birthday_day").val(birthday_day);
			$(".birthday_month").val(birthday_month);
			$(".birthday_year").val(birthday_year);
			$(".country").val(country);
			$(".city").val(city);
			$('.myCheckbox').prop('checked', true);
			$(".about_you_txt").val(about);
			
			if(relation == "1,1,1") {
				$('.relation_type_romance').prop('checked', true);
				$('.relation_type_relationship').prop('checked', true);
				$('.relation_type_friend').prop('checked', true);
				
			} else if(relation == "0,1,1") {
				$('.relation_type_romance').prop('checked', true);
				$('.relation_type_relationship').prop('checked', true);
			} else if(relation == "0,0,1") {
				$('.relation_type_relationship').prop('checked', true);
			} else if(relation == "1,0,1") {
				$('.relation_type_romance').prop('checked', true);
				$('.relation_type_friend').prop('checked', true);
			} else if(relation == "1,1,0") {
				$('.relation_type_relationship').prop('checked', true);
				$('.relation_type_friend').prop('checked', true);
			} else if(relation == "1,0,0") {
				$('.relation_type_friend').prop('checked', true);
			} else if(relation == "0,1,0") {
				$('.relation_type_romance').prop('checked', true);
			}
			
		}
	});		
	
	// Click edit button
	$(".btn-finish-edit-account-infos").click(function(e) {
		e.preventDefault();
		
		var username = $("form .username").val();
		var email = $("form .email").val();
		var user_id = $(this).attr("data-id");
		
		var that = $(this);
		that.html('<i class="fa fa-circle-o-notch fa-spin"></i>');
		
		$.ajax({
			url: base_url + "admin/editaccountinfosverif",
			type: 'POST',
			data: {username : username, email : email, user_id: user_id},
			success: function(data) {
				$("form .username").closest(".form-group").removeClass("has-error");
				$("form .email").closest(".form-group").removeClass("has-error");
				$("form .snapchat").closest(".form-group").removeClass("has-error");
				$(".error-account-infos").hide();
			
				if(data.error == 999) {
					$("form .username").closest(".form-group").addClass("has-error");
					$(".error-account-infos").html("<b>Whoops</b>. You have some errors.<br />" + data.error_msg).fadeIn();
					
					that.html('<i class="fa fa-pencil"></i> Edit my Infos');
				} else if(data.error == 500) {
					$("form .email").closest(".form-group").addClass("has-error");
					$(".error-account-infos").html("<b>Whoops</b>. You have some errors.<br />" + data.error_msg).fadeIn();
					
					that.html('<i class="fa fa-pencil"></i> Edit my Infos');
				} else if(data.error == 998) {
					$("form .email").closest(".form-group").addClass("has-error");
					$(".error-account-infos").html("<b>Whoops</b>. You have some errors.<br />" + data.error_msg).fadeIn();
					
					that.html('<i class="fa fa-pencil"></i> Edit my Infos');
				} else if(data.error == 997) {
					$("form .snapchat").closest(".form-group").addClass("has-error");
					$(".error-account-infos").html("<b>Whoops</b>. You have some errors.<br />" + data.error_msg).fadeIn();
					
					that.html('<i class="fa fa-pencil"></i> Edit my Infos');
				} else if(data.error == 996) {
					$("form .username").closest(".form-group").addClass("has-error");
					$(".error-account-infos").html("<b>Whoops</b>. You have some errors.<br />" + data.error_msg).fadeIn();
					
					that.html('<i class="fa fa-pencil"></i> Edit my Infos');
				} else if(data.error == 995) {
					$("form .email").closest(".form-group").addClass("has-error");
					$(".error-account-infos").html("<b>Whoops</b>. You have some errors.<br />" + data.error_msg).fadeIn();
					
					that.html('<i class="fa fa-pencil"></i> Edit my Infos');
				} else if(data.error == 0) {	
					that.prop("disabled", true);
					
					that.html('<i class="fa fa-check"></i> Coolness!');
					
					$(".form-edit-infos").submit();
				} else if(data.error == 500) {
					window.location = "?demo=true";
				} 
			}
		});
	});
	
	// Click edit button
	$(".btn-finish-edit").click(function(e) {
		e.preventDefault();
		
		var gender = $("form .gender").val();
		var birthday_day = $("form .birthday_day").val();
		var birthday_month = $("form .birthday_month").val();
		var birthday_year = $("form .birthday_year").val();
		var country = $("form .country").val();
		var city = $("form .city").val();
		
		var that = $(this);
		that.html('<i class="fa fa-circle-o-notch fa-spin"></i>');
		
		$.ajax({
			url: base_url + "user/firstloginverif",
			type: 'POST',
			data: {gender: gender, birthday_day: birthday_day, birthday_month: birthday_month, birthday_year: birthday_year, country: country, city: city},
			success: function(data) {
				$("form .gender").closest(".form-group").removeClass("has-error");
				$("form .birthday_day").closest(".form-group").removeClass("has-error");
				$("form .birthday_month").closest(".form-group").removeClass("has-error");
				$("form .birthday_year").closest(".form-group").removeClass("has-error");
				$("form .country").closest(".form-group").removeClass("has-error");
				$("form .city").closest(".form-group").removeClass("has-error");
			
				if(data.error == 999) {
					$("form .gender").closest(".form-group").addClass("has-error");
					$(".error-first-login").html("<b>Whoops</b>. You have some errors.<br />" + data.error_msg).fadeIn();
					
					that.html('<i class="fa fa-pencil"></i> Edit my Profile');
				} else if(data.error == 998) {
					$("form .birthday_day").closest(".form-group").addClass("has-error");
					$(".error-first-login").html("<b>Whoops</b>. You have some errors.<br />" + data.error_msg).fadeIn();
					
					that.html('<i class="fa fa-pencil"></i> Edit my Profile');
				} else if(data.error == 997) {
					$("form .birthday_month").closest(".form-group").addClass("has-error");
					$(".error-first-login").html("<b>Whoops</b>. You have some errors.<br />" + data.error_msg).fadeIn();
					
					that.html('<i class="fa fa-pencil"></i> Edit my Profile');
				} else if(data.error == 996) {
					$("form .birthday_year").closest(".form-group").addClass("has-error");
					$(".error-first-login").html("<b>Whoops</b>. You have some errors.<br />" + data.error_msg).fadeIn();
					
					that.html('<i class="fa fa-pencil"></i> Edit my Profile');
				} else if(data.error == 995) {
					$("form .country").closest(".form-group").addClass("has-error");
					$(".error-first-login").html("<b>Whoops</b>. You have some errors.<br />" + data.error_msg).fadeIn();
					
					that.html('<i class="fa fa-pencil"></i> Edit my Profile');
				} else if(data.error == 0) {	
					that.prop("disabled", true);
					
					that.html('<i class="fa fa-check"></i> Coolness!');
					
					$(".edit_form").submit();
				} else if(data.error == 500) {
					window.location = "?demo=true";
				} 
			}
		});
	});
	
	function scrollTopToStatus()
	{
		var target = $(".error-first-login");
		target = target.length ? target : $('[name=' + this.hash.slice(1) +']');
		if (target.length) {
			$('html,body').animate({
				scrollTop: target.offset().top - 20
			}, 1000);
			return false;
		}
	}
		
});
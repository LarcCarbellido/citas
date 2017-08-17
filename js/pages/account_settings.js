$(document).ready(function() {
	
	var city_form = document.getElementById("cityform");
	var countryRestrict = { 'country': ['FR'] };
	
	var options = {
		types: ['(cities)'],
		componentRestrictions: {country: countryRestrict}
	};

	
	var autocomplete = new google.maps.places.Autocomplete(city_form, options);
	google.maps.event.addDomListener(document.getElementById('country'), 'change', setAutocompleteCountry);
	
	google.maps.event.addListener(autocomplete, 'place_changed', function() {
        var place = autocomplete.getPlace();
        $("#cityform").val(place.address_components[0].long_name);
    })
	
	function setAutocompleteCountry() {
		var country = document.getElementById('country').value;
		if (country != "0") {
			autocomplete.setComponentRestrictions({ 'country': country });
		}
	}
		
	$("#addPhotos").click(function(e) {
		e.preventDefault();
		
		window.location = base_url + "photo/manage";
	});
	
	$(".panel-logout").click(function(e) {
		e.preventDefault();
		
		window.location = base_url + "user/logout";
	});
	
	// Owl Carousel
	$("#profile_picture_carousel").owlCarousel({
		singleItem : true,
		autoHeight : true,
		addClassActive : true
	});
	
	// Click on "Save profile picture"
	$(".btn-save-profile-picture").click(function(e) {
		e.preventDefault();
		
		var that = $(this);
		that.html('<i class="fa fa-circle-o-notch fa-spin"></i>');
		that.prop("disabled", true);
		
		var photo_id = $("#profile_picture_carousel .owl-item.active img").attr("data-id");

		$.ajax({
			url: base_url + "user/save_profile_picture",
			type: 'POST',
			data: {photo_id : photo_id},
			success: function(data) {
				if(data == 999) {
					alert(not_logged_in_str);
					window.location = base_url;
				} else if(data == 998) {
					alert(error_occured_str);
					that.html(save_str);
					that.prop("disabled", false);
				} else {
					window.location = base_url + "user/settings?action=picture_success";
				}
			}
		});
	});
	
	// Get user infos
	$.ajax({
		url: base_url + "user/get_user_edit_infos",
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
			
			autocomplete.setComponentRestrictions({ 'country': country });
			
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
		var snapchat = $("form .snapchat").val();
		
		var that = $(this);
		that.html('<i class="fa fa-circle-o-notch fa-spin"></i>');
		
		$.ajax({
			url: base_url + "user/editaccountinfosverif",
			type: 'POST',
			data: {username : username, email : email},
			success: function(data) {
				$("form .username").closest(".form-group").removeClass("has-error");
				$("form .email").closest(".form-group").removeClass("has-error");
				$("form .snapchat").closest(".form-group").removeClass("has-error");
				$(".error-account-infos").hide();
			
				if(data.error == 999) {
					$("form .username").closest(".form-group").addClass("has-error");
					$(".error-account-infos").html(whoops_have_errors_str + "<br />" + data.error_msg).fadeIn();
					
					that.html('<i class="fa fa-pencil"></i> Edit my Infos');
				} else if(data.error == 998) {
					$("form .email").closest(".form-group").addClass("has-error");
					$(".error-account-infos").html(whoops_have_errors_str + "<br />" + data.error_msg).fadeIn();
					
					that.html('<i class="fa fa-pencil"></i> Edit my Infos');
				} else if(data.error == 997) {
					$("form .snapchat").closest(".form-group").addClass("has-error");
					$(".error-account-infos").html(whoops_have_errors_str + "<br />" + data.error_msg).fadeIn();
					
					that.html('<i class="fa fa-pencil"></i> Edit my Infos');
				} else if(data.error == 996) {
					$("form .username").closest(".form-group").addClass("has-error");
					$(".error-account-infos").html(whoops_have_errors_str + "<br />" + data.error_msg).fadeIn();
					
					that.html('<i class="fa fa-pencil"></i> Edit my Infos');
				} else if(data.error == 995) {
					$("form .email").closest(".form-group").addClass("has-error");
					$(".error-account-infos").html(whoops_have_errors_str + "<br />" + data.error_msg).fadeIn();
					
					that.html('<i class="fa fa-pencil"></i> ' + edit_my_infos_str);
				} else if(data.error == 0) {	
					that.prop("disabled", true);
					
					that.html('<i class="fa fa-check"></i> ' + coolness_btn_str);
					
					$(".form-edit-infos").submit();
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
					$(".error-first-login").html(whoops_have_errors_str + "<br />" + data.error_msg).fadeIn();
					
					that.html('<i class="fa fa-pencil"></i> ' + edit_my_profile_str);
				} else if(data.error == 998) {
					$("form .birthday_day").closest(".form-group").addClass("has-error");
					$(".error-first-login").html(whoops_have_errors_str + "<br />" + data.error_msg).fadeIn();
					
					that.html('<i class="fa fa-pencil"></i> ' + edit_my_profile_str);
				} else if(data.error == 997) {
					$("form .birthday_month").closest(".form-group").addClass("has-error");
					$(".error-first-login").html(whoops_have_errors_str + "<br />" + data.error_msg).fadeIn();
					
					that.html('<i class="fa fa-pencil"></i> ' + edit_my_profile_str);
				} else if(data.error == 996) {
					$("form .birthday_year").closest(".form-group").addClass("has-error");
					$(".error-first-login").html(whoops_have_errors_str + "<br />" + data.error_msg).fadeIn();
					
					that.html('<i class="fa fa-pencil"></i> ' + edit_my_profile_str);
				} else if(data.error == 995) {
					$("form .country").closest(".form-group").addClass("has-error");
					$(".error-first-login").html(whoops_have_errors_str + "<br />" + data.error_msg).fadeIn();
					
					that.html('<i class="fa fa-pencil"></i> ' + edit_my_profile_str);
				} else if(data.error == 0) {	
					that.prop("disabled", true);
					
					that.html('<i class="fa fa-check"></i> ' + coolness_btn_str);
					
					$(".edit_form").submit();
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
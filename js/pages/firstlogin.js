$(document).ready(function() {
	
	if(hide_country == 0) {
		var city_form = document.getElementById("cityform");
		var countryRestrict = { 'country': [] };
		
		var options = {
			types: ['(cities)'],
			componentRestrictions: {country: countryRestrict}
		};
		
		var autocomplete = new google.maps.places.Autocomplete(city_form, options);
		google.maps.event.addDomListener(document.getElementById('country'), 'change',  function () {
			var country = document.getElementById('country').value;
			if (country != "0") {
				autocomplete.setComponentRestrictions({ 'country': country });
			}
		});
		
		google.maps.event.addListener(autocomplete, 'place_changed', function() {
	        var place = autocomplete.getPlace();
	        $("#cityform").val(place.address_components[0].long_name);
	    })

	}
	
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

	//function to check file size before uploading.
	function beforeSubmit() {
	    //check whether browser fully supports all File API
	   if (window.File && window.FileReader && window.FileList && window.Blob)
	   {
	        if( $(".photomain_up").val() ) //check empty input filed
	        {
	       
		        var fsize = $(".photomain_up")[0].files[0].size; //get file size
		        var ftype = $(".photomain_up")[0].files[0].type; // get file type
		       
		        //allow only valid image file types
		        switch(ftype)
		        {
		            case 'image/png': case 'image/jpeg': case 'image/pjpeg':
		                break;
		            default:
		                $(".error-first-login").html(photo_ext_error_str).fadeIn();
		                $(".btn-send-and-begin").html(send_and_begin_str);
		                $("form .photomain_up").closest(".form-group").addClass("has-error");
		                scrollTopToStatus();
		                return false;
		        }
		        
		        if(fsize>5242880)
		        {
		            $(".error-first-login").html(photo_weight_error_str).fadeIn();
	                $(".btn-send-and-begin").html(send_and_begin_str);
	                $("form .photomain_up").closest(".form-group").addClass("has-error");
	                scrollTopToStatus();
		            return false;
		        }
		        
	        } else {
		        return true;
	        }
	         
	    }
	}

	$(".btn-send-and-begin").click(function(e) {
		e.preventDefault();
		
		var birthday_day = $("form .birthday_day").val();
		var birthday_month = $("form .birthday_month").val();
		var birthday_year = $("form .birthday_year").val();
		var about_you_txt = $("form .about_you_txt").val();
		var country = $("form .country").val();
		var city = $("form .city").val();
		
		var that = $(this);
		that.html('<i class="fa fa-circle-o-notch fa-spin"></i>');
		
		$.ajax({
			url: base_url + "user/firstloginverif",
			type: 'POST',
			beforeSend: beforeSubmit,
			data: {birthday_day: birthday_day, birthday_month: birthday_month, birthday_year: birthday_year, about_you_txt: about_you_txt, country: country, city: city},
			success: function(data) {
				$("form .birthday_day").closest(".form-group").removeClass("has-error");
				$("form .birthday_month").closest(".form-group").removeClass("has-error");
				$("form .birthday_year").closest(".form-group").removeClass("has-error");
				$("form .about_you_txt").closest(".form-group").removeClass("has-error");
				$("form .country").closest(".form-group").removeClass("has-error");
				$("form .city").closest(".form-group").removeClass("has-error");
			
				if(data.error == 999) {
					$("form .gender").closest(".form-group").addClass("has-error");
					$(".error-first-login").html(whoops_have_errors_str + "<br />" + data.error_msg).fadeIn();
					scrollTopToStatus();
					
					that.html(send_and_begin_str);
				} else if(data.error == 998) {
					$("form .birthday_day").closest(".form-group").addClass("has-error");
					$(".error-first-login").html(whoops_have_errors_str + "<br />" + data.error_msg).fadeIn();
					scrollTopToStatus();
					
					that.html(send_and_begin_str);
				} else if(data.error == 997) {
					$("form .birthday_month").closest(".form-group").addClass("has-error");
					$(".error-first-login").html(whoops_have_errors_str + "<br />" + data.error_msg).fadeIn();
					scrollTopToStatus();
					
					that.html(send_and_begin_str);
				} else if(data.error == 996) {
					$("form .birthday_year").closest(".form-group").addClass("has-error");
					$(".error-first-login").html(whoops_have_errors_str + "<br />" + data.error_msg).fadeIn();
					scrollTopToStatus();
					
					that.html(send_and_begin_str);
				} else if(data.error == 995) {
					$("form .country").closest(".form-group").addClass("has-error");
					$(".error-first-login").html(whoops_have_errors_str + "<br />" + data.error_msg).fadeIn();
					scrollTopToStatus();
					
					that.html(send_and_begin_str);
				} else if(data.error == 993) {
					$("form .birthday_day").closest(".form-group").addClass("has-error");
					$(".error-first-login").html(whoops_have_errors_str + "<br />" + data.error_msg).fadeIn();
					scrollTopToStatus();
					
					that.html(send_and_begin_str);
				} else if(data.error == 0) {	
					that.prop("disabled", true);
					
					if( $(".photomain_up").val() ) {
						$(".alert-profile-creation").fadeIn();
						that.html('<i class="fa fa-check"></i> ' + photo_upload_in_progress_str);
					} else {
						$(".alert-profile-creation").fadeIn();
						that.html('<i class="fa fa-check"></i> ' + coolness_btn_str);
					}
					
					$(".first_login_form").submit();
				}
			}
		});
	});
	
});
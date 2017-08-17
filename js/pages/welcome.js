$(document).ready(function() {
	$(".btn-continue").click(function(e) {
		e.preventDefault();
		
		$(".form-reg-part-1").hide();
		$(".form-reg-part-2").fadeIn();
		
		$(".lead").html(almost_there_str);
		$(".leadmob").html(almost_there_str);
		
		$(".titlemob").hide();
		
		// Create the cookies for the gender & interested_in
		$.cookie('bepoke_gender', $(".form_gender").val());
		$.cookie('bepoke_interested_in', $(".form_looking_for").val());
		$("#user-carousel").hide();
		$(".second-part-titles").css("marginTop", "50px");
	});
	
	$(".footer_links a").click(function(e) {
		e.preventDefault();
		
		var page_id = $(this).attr("data-id");
		
		$("#welcome_page_modal").modal("show");
		$("#welcome_page_modal .modal-body").html('<div style="text-align:center;"><i class="fa fa-circle-o-notch fa-spin modal-icon"></i></div>');
		
		$.ajax({
			url: base_url + "site/get_welcome_page",
			type: 'POST',
			data: {page_id: page_id},
			success: function(data) {
				$("#welcome_page_modal .modal-title").html(data.page.title);
				$("#welcome_page_modal .modal-body").html(data.page.content);
			}
		});
	});
	
	$("#user-carousel").owlCarousel({
 
     	 autoPlay: 3000, //Set AutoPlay to 3 seconds
	 	 lazyLoad:true, 
	      items : 4,
	      autoHeight:true,
	      itemsDesktop : [800,3],
	      itemsDesktopSmall : [979,3]
	 
	  });
	
	$(document).on("click", ".btn-register-mob", function(e) {
		e.preventDefault();
		
		var current_width = $(window).width();
		
		if( current_width <= 510 ) {
			$(this).html("Sign In");
			$(this).removeClass("btn-register-mob").addClass("btn-login");
			
			$(".form-reg-part-1").fadeIn();
			$(".form_reg").hide();
		} else {
			$("#login_modal").modal("show");
		}
	});
	
	$(document).on("click", ".btn-login", function(e) {
		e.preventDefault();
		
		var current_width = $(window).width();
		
		if( current_width <= 510 ) {
			$(this).html("Register");
			$(this).removeClass("btn-login").addClass("btn-register-mob");
			
			$(".form-reg-part-1").hide();
			$(".form-reg-part-2").hide();
			$(".form_reg").fadeIn();
		} else {
			$("#login_modal").modal("show");
		}
	});
	
	$(".btn-register").click(function(e) {
		e.preventDefault();
		
		var username = $("form .username").val();
		var password = $("form .password").val();
		var email = $("form .email").val();
		var captcha_rep = $("form .captcha_answer").val();
		var captcha_id = $("form .captcha_id").val();
		
		var bepoke_interested_in = $.cookie("bepoke_interested_in");
		var bepoke_gender = $.cookie("bepoke_gender");
		
		var that = $(this);
		
		that.addClass("disabled");
		that.html('<i class="fa fa-circle-o-notch fa-spin"></i>');

		console.log('usuario: ', username, ' pass: ', password, ' email: ', email, ' captcha: ', captcha_rep, ' captcha_id :', captcha_id, ' bepoke_gender :', bepoke_gender, ' bepoke_interested_in:', bepoke_interested_in);

		$.ajax({
			url: base_url + "user/create",
			type: 'POST',
			data: {username : username, password : password, email : email, captcha_rep : captcha_rep, captcha_id : captcha_id, bepoke_gender : bepoke_gender, bepoke_interested_in: bepoke_interested_in},
			success: function(data) {
				console.log('sucess:', data)
				$("form .username").closest(".form-group").removeClass("has-error");
				$("form .password").closest(".form-group").removeClass("has-error");
				$("form .email").closest(".form-group").removeClass("has-error");
				$("form .captcha_answer").closest(".form-group").removeClass("has-error");
				
				that.removeClass("disabled");
			
				if(data.error == 999) {
					$("form .username").closest(".form-group").addClass("has-error");
					$(".error-register").html(data.error_msg).fadeIn();
					
					that.html(register_str);
				} else if(data.error == 998) {
					$("form .password").closest(".form-group").addClass("has-error");
					$(".error-register").html(data.error_msg).fadeIn();
					
					that.html(register_str);
				} else if(data.error == 997) {
					$("form .email").closest(".form-group").addClass("has-error");
					$(".error-register").html(data.error_msg).fadeIn();
					
					that.html(register_str);
				} else if(data.error == 996) {
					$("form .captcha_answer").closest(".form-group").addClass("has-error");
					$(".error-register").html(data.error_msg).fadeIn();
					
					that.html(register_str);
				} else if(data.error == 995) {
					$("form .username").closest(".form-group").addClass("has-error");
					$(".error-register").html(data.error_msg).fadeIn();
					
					that.html(register_str);
				} else if(data.error == 994) {
					$("form .email").closest(".form-group").addClass("has-error");
					$(".error-register").html(data.error_msg).fadeIn();
					
					that.html(register_str);
				} else if(data.error == 993) {
					$("form .snapchat").closest(".form-group").addClass("has-error");
					$(".error-register").html(data.error_msg).fadeIn();
					
					that.html(register_str);
				} else if(data.error == 500) {
					$(".error-register").html(demo_reg_closed_str).fadeIn();
					
					that.html(register_str);
				} else if(data.error == 0) {					
					setTimeout(function() {
						window.location = base_url + "user/firstlogin";
					}, 1200);
					
					that.prop("disabled", true);
					that.html('<i class="fa fa-check"></i> ' + yeah_str);
				}
			}
		});
	});
	
	$(".btn-login-ok-mob").click(function(e) {
		e.preventDefault();
		
		var username = $("form#loginmob #logusernamemob").val();
		var password = $("form#loginmob #logpasswordmob").val();
		
		var that = $(this);
		that.html('<i class="fa fa-circle-o-notch fa-spin"></i>');
		
		$.ajax({
			url: base_url + "user/login",
			type: 'POST',
			data: {username : username, password : password},
			success: function(data) {
				$("#logusernamemob").closest(".form-group").removeClass("has-error");
				$("#logpasswordmob").closest(".form-group").removeClass("has-error");
				$(".error-login").hide();
			
				if(data.error == 999) {
					$("#logusernamemob").closest(".form-group").addClass("has-error");
					$("#logpasswordmob").closest(".form-group").addClass("has-error");
					$(".error-login").html("<div class='refreshLogin'><i class='fa fa-times-circle'></i></div>" + data.status).fadeIn();
					
					that.html(sign_in_str);
				} else if(data.error == 998) {
					$("#passwordregmob").closest(".form-group").addClass("has-error");
					$(".error-login").html("<div class='refreshLogin'><i class='fa fa-times-circle'></i></div>" + data.status).fadeIn();
					
					that.html(sign_in_str);
				} else {	
					$(".error-login").removeClass("alert-danger").addClass("alert-success").html("<div class='refreshLogin'><i class='fa fa-check-square'></i></div><strong>" + success_str + "</strong>").fadeIn();
					
					window.location = data.url_redirect;
				}
			}
		});
	});
	
	$(".forgot_password").click(function(e) {
		e.preventDefault();
		
		$("#login_modal").modal("hide");
		$("#password_modal").modal("show");
	});
	
	$(".btn-recover-password-ok").click(function(e) {
		e.preventDefault();
		
		var email = $(".okdate_email").val();
				
		var that = $(this);
		that.html('<i class="fa fa-circle-o-notch fa-spin"></i>');
		that.prop("disabled", true);
		
		$.ajax({
			url: base_url + "user/recover_password",
			type: 'POST',
			data: {email: email},
			success: function(data) {
				$("#okdate_email").closest(".form-group").removeClass("has-error");
				$(".error-forgot-password").hide();
			
				if(data.error == 999) {
					$(".okdate_email").closest(".form-group").addClass("has-error");
					$(".error-forgot-password").html(email_invalid_str).fadeIn();
					that.prop("disabled", false);
					that.html(send_str);
				} else if(data.error == 998) {
					$(".okdate_email").closest(".form-group").addClass("has-error");
					$(".error-forgot-password").html(email_not_linked_str).fadeIn();
					that.prop("disabled", false);
					that.html(send_str);
				} else {
					$("#password_modal .modal-body").html("<div class='alert alert-success' style='text-align:center; font-size:18px;'><i class='fa fa-check-circle' style='font-size:40px;'></i><br />" + recover_password_success_str + "</div>");
				}
			}
		});
	});
	
	$(".btn-login-ok").click(function(e) {
		e.preventDefault();
		
		var username = $("form#login #logusername").val();
		var password = $("form#login #logpassword").val();
		
		var that = $(this);
		that.html('<i class="fa fa-circle-o-notch fa-spin"></i>');
		
		$.ajax({
			url: base_url + "user/login",
			type: 'POST',
			data: {username : username, password : password},
			success: function(data) {
				$("#logusername").closest(".form-group").removeClass("has-error");
				$("#logpassword").closest(".form-group").removeClass("has-error");
				$(".error-login").hide();
			
				if(data.error == 999) {
					$("#logusername").closest(".form-group").addClass("has-error");
					$("#logpassword").closest(".form-group").addClass("has-error");
					$(".error-login").html("<div class='refreshLogin'><i class='fa fa-times-circle'></i></div>" + data.status).fadeIn();
					
					that.html(sign_in_str);
				} else if(data.error == 998) {
					$("#passwordreg").closest(".form-group").addClass("has-error");
					$(".error-login").html("<div class='refreshLogin'><i class='fa fa-times-circle'></i></div>" + data.status).fadeIn();
					
					that.html(sign_in_str);
				} else {	
					$(".error-login").removeClass("alert-danger").addClass("alert-success").html("<div class='refreshLogin'><i class='fa fa-check-square'></i></div><strong>" + success_str + "</strong>").fadeIn();
					
					setTimeout(function() {
						window.location = data.url_redirect;
					}, 1000);
				}
			}
		});
	});
});
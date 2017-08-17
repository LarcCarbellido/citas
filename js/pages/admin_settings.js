$(document).ready(function() {
	$("#web_menu_color").ColorPickerSliders({
	    size: 'sm',
	    placement: 'top',
	    previewformat: 'hex',
	    swatches: false,
	    sliders: false,
	    hsvpanel: true
	});

	$("#web_menu_textcolor").ColorPickerSliders({
	    size: 'sm',
	    placement: 'top',
	    previewformat: 'hex',
	    swatches: false,
	    sliders: false,
	    hsvpanel: true
	});
	
	$("#web_bgcolor").ColorPickerSliders({
	    size: 'sm',
	    placement: 'top',
	    previewformat: 'hex',
	    swatches: false,
	    sliders: false,
	    hsvpanel: true
	});
	
	$(".btn-save").click(function(e) {
		e.preventDefault();
		
		$(this).html('<i class="fa fa-circle-o-notch fa-spin"></i>');
		
		var web_title = $(".web_title").val();
		var web_tagline = $(".web_tagline").val();
		var web_desc = $(".web_desc").val();
		var web_keywords = $(".web_keywords").val();
		var web_captcha = $(".web_captcha").val();
		var site_analytics = $(".site_analytics").val();
		var age_limit = $(".age_limit").val();
		var upload_limit = $(".upload_limit").val();
		var online_delay = $(".online_delay").val();
		var hide_country = $(".hide_country").val();
		var hide_timeline = $(".hide_timeline").val();
		var extra_fields = $(".extra_fields").val();
		
		if($(".web_captcha").is(":checked")) {
			web_captcha = 1;
		} else {
			web_captcha = 0;
		}
		
		if($(".hide_timeline").is(":checked")) {
			hide_timeline = 0;
		} else {
			hide_timeline = 1;
		}
		
		if($(".hide_country").is(":checked")) {
			hide_country = 1;
		} else {
			hide_country = 0;
		}
		
		var that = $(this);
		
		$.ajax({
			url: base_url + "admin/save_general_settings",
			type: 'POST',
			data: {extra_fields: extra_fields, hide_timeline: hide_timeline, hide_country: hide_country, online_delay: online_delay, upload_limit: upload_limit, age_limit: age_limit, web_title: web_title, web_tagline: web_tagline, web_desc: web_desc, web_keywords: web_keywords, web_captcha: web_captcha, site_analytics: site_analytics},
			success: function(data) {
				
				$(".alert-error-settings").empty();
				
				if(data.error > 0)
				{
					$(".alert-error-settings").html(data.error_msg).fadeIn();
					$("html, body").animate({scrollTop: 0}, 1000);
					
					that.html('<i class="fa fa-check"></i> Save Changes');
				} else {
					window.location = base_url + "admin/settings?status=ok";
				}
			}
		});
	});
});
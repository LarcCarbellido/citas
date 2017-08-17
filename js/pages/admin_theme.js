$(document).ready(function() {
	
	$(".btn-save").click(function(e) {
		e.preventDefault();
		
		$(this).html('<i class="fa fa-circle-o-notch fa-spin"></i>');
		
		var f_bg_color = $(".f_bg_color").val();
		var f_txt_color = $(".f_txt_color").val();
		var s_bg_color = $(".s_bg_color").val();
		var s_txt_color = $(".s_txt_color").val();
		var s_third_color = $(".s_third_color").val();
		var bgcolor_main = $(".bgcolor_main").val();
		var textcolor_navbar = $(".textcolor_navbar").val();
		var main_block_color = $(".main_block_color").val();
		var main_txt_color = $(".main_txt_color").val();
		var logo_color = $(".logo_color").val();
		
		var that = $(this);
		
		$.ajax({
			url: base_url + "admin/save_theme_settings",
			type: 'POST',
			data: {logo_color: logo_color, bgcolor_main: bgcolor_main, f_bg_color: f_bg_color, f_txt_color: f_txt_color, s_bg_color: s_bg_color, s_txt_color: s_txt_color, s_third_color: s_third_color, textcolor_navbar: textcolor_navbar, main_block_color: main_block_color, main_txt_color: main_txt_color},
			success: function(data) {
				
				$(".alert-error-settings").empty();
				
				if(data.error > 0)
				{
					$(".alert-error-settings").html(data.error_msg).fadeIn();
					$("html, body").animate({scrollTop: 0}, 1000);
					
					that.html('<i class="fa fa-check"></i> Save Changes');
				} else {
					window.location = base_url + "admin/theme?status=ok";
				}
			}
		});
	});
});
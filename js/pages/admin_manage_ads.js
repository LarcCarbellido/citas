$(document).ready(function() {

	$(".btn-save").click(function(e) {
		e.preventDefault();
		
		$(this).html('<i class="fa fa-circle-o-notch fa-spin"></i>');	
			
		var ads_code = $(".ads_code").val();
		
		var that = $(this);
		
		$.ajax({
			url: base_url + "admin/save_ads_code",
			type: 'POST',
			data: {ads_code: ads_code},
			success: function(data) {
				
				$(".alert-error-settings").empty();
				
				if(data.error > 0)
				{
					$(".alert-error-settings").html(data.error_msg).fadeIn();
					$("html, body").animate({scrollTop: 0}, 1000);
					
					that.html('<i class="fa fa-check"></i> Save Changes');
				} else {
					window.location = base_url + "admin/manage_ads?status=ok";
				}
			}
		});
	});
});
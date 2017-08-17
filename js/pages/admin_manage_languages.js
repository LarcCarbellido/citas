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
	
	$(".btn-add-redirection").click(function(e) {
		e.preventDefault();
		
		$("#add_redirection_modal").modal("show");
	});
	
	$(".btn-delete-redir").click(function(e) {
		e.preventDefault();
		
		var redir_id = $(this).attr("data-id");
		var that = $(this);
		
		var r = confirm("Are you sure you want to delete this redirection?");
		if(r) {
			$.ajax({
				url: base_url + "admin/delete_redirection",
				type: 'POST',
				data: {redir_id: redir_id},
				success: function(data) {
					if(data.error == 999) {
						alert(data.error_msg);
					} else if(data.error == 500) {
						alert(data.error_msg);
					} else {
						that.html("<i class='fa fa-check'></i> DELETED");
						that.removeClass("btn-danger").addClass("btn-success");
					}
				}
			});
		}
	});
	
	$(".btn-edit-redir").click(function(e) {
		e.preventDefault();
		
		var redir_id = $(this).attr("data-id");
		$(this).html('<i class="fa fa-circle-o-notch fa-spin"></i>');
		
		var that = $(this);
		
		$.ajax({
			url: base_url + "admin/get_language_redirection",
			type: 'POST',
			data: {redir_id: redir_id},
			success: function(data) {
				$(".alert-error-settings").empty();
				
				if(data.error > 0)
				{
					alert(data.error_msg);
					that.html('<i class="fa fa-pencil"></i> Edit');
				} else {
					$("#edit_redirection_modal .country_comes_from_edit").val(data.redir.country);
					$("#edit_redirection_modal .redirects_to_edit").val(data.redir.language);
					
					$("#edit_redirection_modal .btn-edit-redirect").attr("data-id", redir_id);
					
					that.html('<i class="fa fa-pencil"></i> Edit');
					$("#edit_redirection_modal").modal("show");
				}
			}
		});
	});
	
	$(document).on("click", ".btn-edit-redirect", function(e) {
		e.preventDefault();
		
		var country_comes_from = $(".country_comes_from_edit").val();
		var redirects_to = $(".redirects_to_edit").val();
		
		var id = $(this).attr("data-id");
		
		$(this).html('<i class="fa fa-circle-o-notch fa-spin"></i>');
		
		var that = $(this);
		
		$.ajax({
			url: base_url + "admin/save_edit_language_redirection",
			type: 'POST',
			data: {country_comes_from: country_comes_from, redirects_to: redirects_to, id: id},
			success: function(data) {
				$(".alert-error-settings").empty();
				
				if(data.error > 0)
				{
					$(".alert-error-settings").html(data.error_msg).fadeIn();
					
					that.html('<i class="fa fa-check"></i> Save Changes');
				} else {
					window.location = base_url + "admin/manage_languages?status=ok";
				}
			}
		});
		
	});
	
	$(document).on("click", ".btn-save-redirect", function(e) {
		e.preventDefault();
		
		var redirects_to = $(".redirects_to_add").val();
		
		$(this).html('<i class="fa fa-circle-o-notch fa-spin"></i>');
		
		var that = $(this);
		
		$.ajax({
			url: base_url + "admin/save_language_redirection",
			type: 'POST',
			data: {redirects_to: redirects_to},
			success: function(data) {
				$(".alert-error-settings").empty();
				
				if(data.error > 0)
				{
					$(".alert-error-settings").html(data.error_msg).fadeIn();
					
					that.html('<i class="fa fa-check"></i> Save Changes');
				} else {
					window.location = base_url + "admin/manage_languages?status=ok";
				}
			}
		});
	});
	
	$(".btn-save").click(function(e) {
		e.preventDefault();
		
		$(this).html('<i class="fa fa-circle-o-notch fa-spin"></i>');
		
		var language = $(".defaultlanguage").val();		
		
		var that = $(this);
		
		$.ajax({
			url: base_url + "admin/save_default_language",
			type: 'POST',
			data: {language: language},
			success: function(data) {
				
				$(".alert-error-settings").empty();
				
				if(data.error > 0)
				{
					$(".alert-error-settings").html(data.error_msg).fadeIn();
					$("html, body").animate({scrollTop: 0}, 1000);
					
					that.html('<i class="fa fa-check"></i> Save Changes');
				} else {
					window.location = base_url + "admin/manage_languages?status=ok";
				}
			}
		});
	});
});
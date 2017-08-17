$(document).ready(function() {
	$('.thumbnail').nailthumb();	

	$(".love_button i").click(function(e) {
		e.preventDefault();
		
		var profile_id = $(this).parent().attr("data-profile-id");
		var that = $(this).parent();
		
		$.ajax({
			url: base_url + "user/post_love",
			type: 'POST',
			data: {profile_id: profile_id},
			success: function(data) {
				if(data.result == 1)
				{
					that.addClass("loved");
				} else if(data.result == 2) {
					that.removeClass("loved");
				} else {
					alert(cant_love_own_profile_str);
				}
			}
		});
	});
	
	$(".settings_agerange").noUiSlider({
		start: [16, 110],
		connect: true,
		step: 1,
		range: {
			'min': 16,
			'max': 110
		},
		format: wNumb({
			decimals: 0
		})
	});
	
		
	// Reading/writing + validation from an input? One line.
	$('.settings_agerange').Link('lower').to($('.value_from'));
	
	// Write to a span? One line.
	$('.settings_agerange').Link('upper').to($('.value_to'), 'html');
	
	$(".filter_sentences b").click(function(e) {
		e.preventDefault();
		
		$("#settings_modal").modal("show");
	});
	
	$(".settingsfilter").click(function(e) {
		e.preventDefault();
		
		$("#settings_modal").modal("show");
	});
	
	$(".btn-apply-filters").click(function(e) {
		e.preventDefault();
		
		var gender_to_show 	= $("#settings_modal .settings_showonly").val();
		var age_from 		= $("#settings_modal .value_from").html();
		var age_to 			= $("#settings_modal .value_to").html();
		var country			= $("#settings_modal .country").val();
		var sort_by			= $("#settings_modal .sort_by").val();
		var city			= $("#settings_modal .cityform").val();
		
		$.ajax({
			url: base_url + "user/apply_filters",
			type: 'POST',
			data: {filter_gender : gender_to_show, filter_age_from: age_from, filter_age_to : age_to, filter_country : country, filter_sort: sort_by, filter_city: city},
			success: function(data) {
				window.location = base_url + "home?action=filter_applied";
			}
		});
				
	});
	
	$(".resetfilters").click(function(e) {
		e.preventDefault();
		
		$.ajax({
			url: base_url + "user/reset_filters",
			type: 'POST',
			success: function(data) {
				window.location = base_url + "home?action=filter_reset";
			}
		});
		
		
	});
	
	// Check if country is enabled
	if($("#country").length)
	{
		var city_form = document.getElementById("cityform");
		var countryRestrict = { 'country': [] };
		
		var options = {
			types: ['(cities)'],
			componentRestrictions: {country: countryRestrict}
		};
		
		var autocomplete = new google.maps.places.Autocomplete(city_form, options);
		
	
		google.maps.event.addDomListener(document.getElementById('country'), 'change', function() {			
			var country = document.getElementById('country').value;
			if (country != "0") {		
				$(".city_form").show();
		
				autocomplete.setComponentRestrictions({ 'country': country });
			} else {
				$(".city_form").hide();
			}
		});
		
		google.maps.event.addListener(autocomplete, 'place_changed', function() {
	        var place = autocomplete.getPlace();
	        $("#cityform").val(place.address_components[0].long_name);
	    })
		
		function setAutocompleteCountry() {
			
			var country = document.getElementById('country').value;
			if (country != "0") {		
				$(".city_form").show();
		
				autocomplete.setComponentRestrictions({ 'country': country });
			} else {
				$(".city_form").hide();
			}
		}
	
	}
	
	$('.settingsfilter').tooltip({trigger: 'manual'}).tooltip('show');
	
	$("#settings_modal").on("shown.bs.modal", function() {
		
		$.ajax({
			url: base_url + "user/get_filters",
			type: 'POST',
			success: function(data) {
												
				if(data.filter_gender == null)
					$("#settings_modal .settings_showonly").val(2);
				else
					$("#settings_modal .settings_showonly").val(data.filter_gender);
					
				if(data.filter_age_from !== undefined && data.filter_age_to !== undefined) { 
					$(".settings_agerange").val([data.filter_age_from,data.filter_age_to]);
				}
				
				if(data.filter_country) { 
					
					autocomplete.setComponentRestrictions({ 'country': data.filter_country });
					
					$("#settings_modal .country").val(data.filter_country);
					$(".city_form input").val(data.filter_city);
					$(".city_form").show();
				} 
				
				if(data.filter_country == "0") {
					$(".city_form").hide();
				}
				
				if(data.filter_sort) { 
					$("#settings_modal .sort_by").val(data.filter_sort);
				}
			}
		});
	});
});
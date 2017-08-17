$(document).ready(function() {
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
	
	$(".btn-apply-filters").click(function(e) {
		e.preventDefault();
		
		var gender_to_show 	= $(".settings_showonly").val();
		var age_from 		= $(".value_from").html();
		var age_to 			= $(".value_to").html();
		var country			= $(".country").val();
		var sort_by			= $(".sort_by").val();
		
		$.ajax({
			url: base_url + "user/apply_search",
			type: 'POST',
			data: {filter_gender : gender_to_show, filter_age_from: age_from, filter_age_to : age_to, filter_country : country, filter_sort: sort_by},
			success: function(data) {
				window.location = base_url + "home2?action=filter_applied";
			}
		});
				
	});
	
	// Reading/writing + validation from an input? One line.
	$('.settings_agerange').Link('lower').to($('.value_from'));
	
	// Write to a span? One line.
	$('.settings_agerange').Link('upper').to($('.value_to'), 'html');
});
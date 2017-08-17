$(document).ready(function() {
	
	var desc = "";
	var nb_coins_def = 0;
	var amount = 0;
	
	$(".buy_coins").click(function(e) {
		e.preventDefault();
		
		$(".buy_buttons").hide();
		
		var nb_coins = $(this).attr("data-nb");
		var price_coins = $(this).attr("data-price");
		
		if ($(".stripe-button-el-paypal")[0]) {
			var paypal_url = $(".purchase_buttons .stripe-button-el-paypal").attr("href");
			
			$(".purchase_buttons .stripe-button-el-paypal").attr("href", paypal_url + "/" + nb_coins);
			$(".purchase_buttons .stripe-button-el-paypal span").html('<i class="fa fa-cc-paypal"></i> ' + nb_coins + ' ' + coins_with_paypal_str).attr("style", "font-size: 15px !important;"); 
		}
		
		if ($(".stripe-button-el-stripe")[0]) {
			$(".stripe-button-el-stripe span").html("<i class='fa fa-cc-stripe'></i> " + nb_coins + " " + coins_with_stripe_str).attr("style", "font-size: 15px !important;"); 
		  	$(".stripe-button-el-stripe span i").attr("style", "vertical-align:-2px;");
		}
		
		desc = nb_coins + " Coins on " + site_name;
		amount = price_coins*100;
		nb_coins_def = nb_coins;
		
		$(".purchase_buttons").fadeIn();
	});
	
	
	function scrollTopToStatus()
	{
		var target = $(".alert-purchase");
		target = target.length ? target : $('[name=' + this.hash.slice(1) +']');
		if (target.length) {
			$('html,body').animate({
				scrollTop: target.offset().top - 20
			}, 1000);
			return false;
		}
	}
	
	$(".btn-take").click(function(e) {
		e.preventDefault();
		
		var name = $(this).attr("data-name");
		var that = $(this);
		
		that.addClass("disabled");
		that.html('<i class="fa fa-circle-o-notch fa-spin"></i>');
		
		// Activate purchase
		$.ajax({
			url: base_url + "premium/activate_feature",
			type: 'POST',
			data: {name : name},
			success: function(data) {
				if(data.result == 999) {
					alert("You are not logged in anymore");
					window.location = base_url;
				} else if(data.result == 998) {
					alert("Error, this premium feature doesn't exist.");
					that.removeClass("disabled");
				} else if(data.result == 997) {
					$(".alert-purchase").removeClass("alert-success").addClass("alert-danger").html(not_enough_credits_str).fadeIn();
					that.removeClass("disabled").html("<i class='fa fa-diamond'></i> " + take_it_str);
					scrollTopToStatus();
				} else {
					if(name == "see_who_loves_you") {
						$(".alert-purchase").html(see_loves_success_str).fadeIn();
					} else if(name == "browse_invisibly") {
						$(".alert-purchase").html(invisible_success_str).fadeIn();
					} else if(name == "featured_one_week") {
						$(".alert-purchase").html(featured_one_week_success_str).fadeIn();
					} else if(name == "featured_one_month") {
						$(".alert-purchase").html(featured_one_month_success_str).fadeIn();
					}
					
					scrollTopToStatus();
					
					that.removeClass("btn-danger").addClass("btn-success").html("Success!");
				}
			}
		});
	});
	
	var handler = StripeCheckout.configure({
		key: stripe_pub_key,
		locale: 'auto',
		token: function(token) {
		// Use the token to create the charge with a server-side script.
		// You can access the token ID with `token.id`
			window.location = base_url + "premium/payment_stripe_return/" + token.id + "/" + nb_coins_def;
		}
	});
	
	$('.stripe-button-el-stripe').on('click', function(e) {
		// Open Checkout with further options
		handler.open({
			name: site_name,
			description: desc,
			currency: currency,
			email: email,
			amount: amount
		});
		e.preventDefault();
	});
	
	// Close Checkout on page navigation
	$(window).on('popstate', function() {
		handler.close();
	});
	
});
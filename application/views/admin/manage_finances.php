<?php
$this->load->view('templates/headers/admin_header', $title);
?>
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>Admin</h2>
        <ol class="breadcrumb">
            <li>
                <a href="<?php echo base_url(); ?>/admin">Admin</a>
            </li>
            <li class="active">
                <strong>Manage Premium</strong>
            </li>
        </ol>
    </div>
</div>
<div class="col-lg-12 block_form">
	<?php
	if(isset($_GET["status"])) {
	?>
	<div class="alert alert-success alert-centered">
		<b>Success!</b> Your settings have been updated!
	</div>
	<?php
	} else {
	?>
	<div class="alert alert-info alert-centered">
		Users of your website can purchase premium options to improve their dating experience.
	</div>
	<?php
	}
	?>
	<form action="" method="post" accept-charset="utf-8" class="general_config well">
		<div class="alert alert-danger alert-centered alert-error-settings">
			
		</div>
		<div class="checkbox enable_payments_placeholder">
            <label>
            	<input class="form-control enable_payment" <?php if($settings["enable_payments"] == 1) { echo "checked"; } ?> type="checkbox" id="enable_payment" name="enable_payment" /> Enable Premium Features
            </label>
			<p class="help-block"><i class="fa fa-info-circle"></i> If unchecked, premium link will be disabled.</p>
        </div>
        <div class="payment_checked <?php if($settings["enable_payments"] == 1) { echo "show"; } ?>">
	        <hr />
	        <?php
		    
		    if(DEMO_MODE == 1) {
				$settings["paypal_api_username"] = "HIDDEN IN DEMO MODE";
				$settings["paypal_api_pw"] = "HIDDEN IN DEMO MODE";
				$settings["paypal_api_sign"] = "HIDDEN IN DEMO MODE";
				$settings["stripe_secret_key"] = "HIDDEN IN DEMO MODE";
				$settings["stripe_pub_key"] = "HIDDEN IN DEMO MODE";
				$settings["paygol_service_id"] = "HIDDEN IN DEMO MODE";
				$settings["paygol_service_name"] = "HIDDEN IN DEMO MODE";
			}
		    ?>
	        <div class="alert alert-info alert-centered">
				Please refer to the documentation to learn how to find your API keys.
			</div>
	        <fieldset>
	        	<legend><i class="fa fa-paypal"></i> PayPal Settings</legend>
	        	<div class="alert alert-info alert-centered">
					If you set the Paypal API keys blanked, Paypal will not be enabled.
				</div>
				<div class="form-group">
					<label for="paypal_api_username">API Username :</label>
					<input type="text" class="form-control paypal_api_username" id="paypal_api_username" name="paypal_api_username" value="<?php echo $settings["paypal_api_username"]; ?>" placeholder="Get your API Username on Paypal.com">
				</div>
				<div class="form-group">
					<label for="paypal_api_pw">API Password :</label>
					<input type="text" class="form-control paypal_api_pw" id="paypal_api_pw" name="paypal_api_pw" value="<?php echo $settings["paypal_api_pw"]; ?>" placeholder="Get your API Password on Paypal.com">
				</div>
				<div class="form-group">
					<label for="paypal_api_sign">API Signature :</label>
					<input type="text" class="form-control paypal_api_sign" id="paypal_api_sign" name="paypal_api_sign" value="<?php echo $settings["paypal_api_sign"]; ?>" placeholder="Get your API Signature on Paypal.com">
				</div>
	        </fieldset>
	        <fieldset>
	        	<legend><i class="fa fa-cc-stripe"></i> Stripe Settings</legend>
	        	<div class="alert alert-info alert-centered">
					If you set the Stripe API keys blanked, Stripe will not be enabled.
				</div> 
				<div class="form-group">
					<label for="stripe_secret_key">Secret Key :</label>
					<input type="text" class="form-control stripe_secret_key" id="stripe_secret_key" name="stripe_secret_key" value="<?php echo $settings["stripe_secret_key"]; ?>" placeholder="Get your API Secret Key on Stripe.com">
				</div>
				<div class="form-group">
					<label for="stripe_pub_key">Publishable Key :</label>
					<input type="text" class="form-control stripe_pub_key" id="stripe_pub_key" name="stripe_pub_key" value="<?php echo $settings["stripe_pub_key"]; ?>" placeholder="Get your API Publishable Key on Stripe.com">
				</div>
	        </fieldset>
	        <hr />
	        <fieldset>
	        	<legend><i class="fa fa-diamond"></i> Coins Price</legend>
	        	<div class="alert alert-info alert-centered">
					Set the price of each pack of coins
				</div> 
				
				<?php
					
				$price_100_coins = 0;
				$price_500_coins = 0;
				$price_1000_coins = 0;
					
				foreach($coins as $coin):
					if($coin["nb"] == 100) {
						$price_100_coins = $coin["price"];
					} else if($coin["nb"] == 500) {
						$price_500_coins = $coin["price"];
					} else if($coin["nb"] == 1000) {
						$price_1000_coins = $coin["price"];
					}
				endforeach;	
				?>
				
				<?php
				if($settings["currency"] == "") {
					$settings["currency"] = "USD";
				}	
				?>
				<div class="form-group">
					<label for="currency">Currency</label>
					<input type="text" class="form-control currency" id="currency" name="currency" value="<?php echo $settings["currency"]; ?>" placeholder="Type the 3 letters of your currency">
					<p class="help-block"><i class="fa fa-info-circle"></i> Enter the 3 letters of your currency.</p>
				</div>
				<div class="form-group">
					<label for="100_coins_price">100 Coins Price:</label>
					<input type="text" class="form-control 100_coins_price" id="100_coins_price" name="100_coins_price" value="<?php echo $price_100_coins; ?>" placeholder="Set the USD value of 100 coins">
				</div>
				<div class="form-group">
					<label for="500_coins_price">500 Coins Price:</label>
					<input type="text" class="form-control 500_coins_price" id="500_coins_price" name="500_coins_price" value="<?php echo $price_500_coins; ?>" placeholder="Set the USD value of 500 coins">
				</div>
				<div class="form-group">
					<label for="1000_coins_price">1000 Coins Price:</label>
					<input type="text" class="form-control 1000_coins_price" id="1000_coins_price" name="1000_coins_price" value="<?php echo $price_1000_coins; ?>" placeholder="Set the USD value of 1000 coins">
				</div>
	        </fieldset>
	        <hr />
	        <fieldset>
	        	<legend><i class="fa fa-shopping-cart"></i> Premium Options Price</legend>
	        	<div class="alert alert-info alert-centered">
					Set the price of each pack of coins (<b>in Coins</b>)
				</div> 
				<div class="form-group">
					<label for="see_who_loves_you_price">See Who Loves You Coins Price:</label>
					<input type="text" class="form-control see_who_loves_you_price" id="see_who_loves_you_price" name="see_who_loves_you_price" value="<?php echo $settings["see_who_loves_you_price"]; ?>" placeholder="Set the Coins Value">
				</div>
				<div class="form-group">
					<label for="browse_invisibly_price">Browse Invisibly Coins Price:</label>
					<input type="text" class="form-control browse_invisibly_price" id="browse_invisibly_price" name="browse_invisibly_price" value="<?php echo $settings["browse_invisibly_price"]; ?>" placeholder="Set the Coins Value">
				</div>
				<div class="form-group">
					<label for="featured_one_week_price">Featured One Week Coins Price:</label>
					<input type="text" class="form-control featured_one_week_price" id="featured_one_week_price" name="featured_one_week_price" value="<?php echo $settings["featured_one_week_price"]; ?>" placeholder="Set the Coins Value">
				</div>
				<div class="form-group">
					<label for="featured_one_month_price">Featured One Month Coins Price:</label>
					<input type="text" class="form-control featured_one_month_price" id="featured_one_month_price" name="featured_one_month_price" value="<?php echo $settings["featured_one_month_price"]; ?>" placeholder="Set the Coins Value">
				</div>
	        </fieldset>
        </div>
        <div style="text-align:center;">
			<button type="submit" class="btn btn-primary btn-save"><i class="fa fa-check"></i> Save Changes</button>
	    </div>
	</form>
</div>
<?php
$this->load->view('templates/footers/admin_footer');
?>
<?php
$this->load->view('templates/headers/main_header', $title);
?>
<div class="container">
	
	<?php
	if($settings["ads_code"] != ""):
	?>
	<div class="ad_block">
		<?php echo $settings["ads_code"]; ?>
	</div>
	<?php
	endif;
	?>
	<div class="row">
		<div class="pull-left">
			<h3><?php echo $title; ?></h3>
		</div>
	</div>
		<?php
	if(isset($_GET["action"]))
	{
		if($_GET["action"] == "cancel") {
		?>
		<div class="alert alert-danger alert-center">
			<?php echo $this->lang->line("order_cancelled"); ?>
		</div>
		<?php
		} else if($_GET["action"] == "error_payment") {
		?>
		<div class="alert alert-danger alert-center">
			<?php echo $this->lang->line("error_payment"); ?>
		</div>
		<?php
		} else if($_GET["action"] == "payment_success") {
		?>
		<div class="alert alert-success alert-center">
			<?php echo $this->lang->line("success_payment"); ?>
		</div>
		<?php
		}
	}	
	?>

	<div class="row">
		<div class="main_container premium">
			
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
			
			<div class="purchase-coins-block alert-center">
				<?php echo sprintf($this->lang->line("to_unlock_you_have_to"), $settings["site_name"]); ?>
		
				<?php 
				if($settings["currency"] == "")
					$currency_set = "USD"; 
				else
					$currency_set = $settings["currency"];
					
				$buy_coins_opt_1 = sprintf($this->lang->line("buy_coins_opt_1"), number_format((float)$price_100_coins, 2, '.', ''), $currency_set);
				$buy_coins_opt_2 = sprintf($this->lang->line("buy_coins_opt_2"), number_format((float)$price_500_coins, 2, '.', ''), $currency_set);
				$buy_coins_opt_3 = sprintf($this->lang->line("buy_coins_opt_3"), number_format((float)$price_1000_coins, 2, '.', ''), $currency_set);
				?>
				<div class="buy_coins_calculator row">
					<div class="buy_buttons">
						<div class="col-md-4 coin_option">
							<a href="" class="btn btn-success buy_coins" data-nb="100" data-price="<?php echo number_format((float)$price_100_coins, 2, '.', ''); ?>"><i class="fa fa-plus"></i> <?php echo $buy_coins_opt_1; ?></a>
						</div>
						<div class="col-md-4 coin_option">
							<a href="" class="btn btn-success buy_coins" data-nb="500" data-price="<?php echo number_format((float)$price_500_coins, 2, '.', ''); ?>"><i class="fa fa-plus"></i> <?php echo $buy_coins_opt_2; ?></a>
						</div>
						<div class="col-md-4 coin_option">
							<a href="" class="btn btn-success buy_coins" data-nb="1000" data-price="<?php echo number_format((float)$price_1000_coins, 2, '.', ''); ?>"><i class="fa fa-plus"></i> <?php echo $buy_coins_opt_3; ?></a>
						</div>
					</div>
					<div class="purchase_buttons">
						<?php
						if(!empty($settings["paypal_api_username"]) && !empty($settings["paypal_api_pw"]) && !empty($settings["paypal_api_sign"])) {
						?>
						<a href="<?php echo base_url(); ?>premium/pay_with_paypal" class="stripe-button-el-paypal">
							<span style="display: block; min-height: 30px;"></span>
						</a>
						<?php
						}
						?>
						<?php
						if(!empty($settings["stripe_secret_key"]) && !empty($settings["stripe_pub_key"])) {
						?>
						<a href="#" class="stripe-button-el-stripe">
							<span style="display: block; min-height: 30px;"></span>
						</a>
						<?php
						}
						?>
					</div>
				</div>
				<div class="coins_available">
					<i class="fa fa-diamond"></i> <?php echo sprintf($this->lang->line("coins_available"), $user_coins); ?>
				</div>
			</div>

			<div class="premium_features_list">
				<div class="alert alert-success alert-center alert-purchase">
					
				</div>
				<div class="well premium_feature">
					<div class="row">
						<div class="pf_icon col-md-2">
							<i class="fa fa-heart"></i>
						</div>
						<div class="pf_desc col-md-8">
							<h4><?php echo $this->lang->line("see_who_loves_you_title"); ?> <span class="price_desk_premium">&bullet; <span class="badge badge-danger badge-premium-price"><?php echo sprintf($this->lang->line("feature_price"), $settings["see_who_loves_you_price"]); ?></span></span></h4>
							<span class="badge badge-danger badge-mob-price"><?php echo sprintf($this->lang->line("feature_price"), $settings["see_who_loves_you_price"]); ?></span>
							<p>
								<?php echo sprintf($this->lang->line("see_who_loves_you_desc"), $settings["site_name"]); ?>
							</p>
						</div>
						<div class="pf_take_it col-md-2">
							<?php
							if($loves_purchased):
							?>
							<a class="btn btn-success disabled">
								<i class="fa fa-check"></i> <?php echo $this->lang->line("purchased_btn"); ?>
							</a>
							<?php else: ?>
							<a class="btn btn-danger btn-take" data-name="see_who_loves_you">
								<i class="fa fa-diamond"></i> <?php echo $this->lang->line("take_it_btn"); ?>
							</a>
							<?php
							endif;
							?>
						</div>
					</div>
				</div>
				<div class="well premium_feature">
					<div class="row">
						<div class="pf_icon col-md-2">
							<i class="fa fa-eye-slash"></i>
						</div>
						<div class="pf_desc col-md-8">
							<h4><?php echo $this->lang->line("browse_invisibly_title"); ?> <span class="price_desk_premium">&bullet; <span class="badge badge-danger badge-premium-price"><?php echo sprintf($this->lang->line("feature_price"), $settings["browse_invisibly_price"]); ?></span></span></h4>
							<span class="badge badge-danger badge-mob-price"><?php echo sprintf($this->lang->line("feature_price"), $settings["browse_invisibly_price"]); ?></span>
							<p>
								<?php echo sprintf($this->lang->line("browse_invisibly_desc"), $settings["site_name"]); ?>
							</p>
						</div>
						<div class="pf_take_it col-md-2">
							<?php
							if($invisibly_purchased):
							?>
							<a class="btn btn-success disabled">
								<i class="fa fa-check"></i> <?php echo $this->lang->line("purchased_btn"); ?>
							</a>
							<?php else: ?>
							<a class="btn btn-danger btn-take" data-name="browse_invisibly">
								<i class="fa fa-diamond"></i> <?php echo $this->lang->line("take_it_btn"); ?>
							</a>
							<?php
							endif;
							?>
						</div>
					</div>
				</div>	
				<div class="well premium_feature">
					<div class="row">
						<div class="pf_icon col-md-2">
							<i class="fa fa-send"></i>
						</div>
						<div class="pf_desc col-md-8">
							<h4><?php echo $this->lang->line("featured_one_week_title"); ?> <span class="price_desk_premium">&bullet; <span class="badge badge-danger badge-premium-price"><?php echo sprintf($this->lang->line("feature_price"), $settings["featured_one_week_price"]); ?></span></span></h4>
							<span class="badge badge-danger badge-mob-price"><?php echo sprintf($this->lang->line("feature_price"), $settings["featured_one_week_price"]); ?></span>
			   				<p>
								<?php echo $this->lang->line("featured_one_week_desc"); ?>
								<?php
								if($is_featured_one_week):
									$phpdate = strtotime( $one_week_featured["end_date"] );
									$mysqldate = date( 'Y-m-d', $phpdate );	
								?>
								<i><?php echo sprintf($this->lang->line("premium_until"), $mysqldate); ?></i>.
								<?php
								endif;	
								?>
							</p>
						</div>
						<div class="pf_take_it col-md-2">
							<?php
							if($is_featured_one_week || $is_featured_one_month):
							?>
							<a class="btn  btn-success disabled" data-name="featured_one_week">
								<i class="fa fa-check"></i> <?php echo $this->lang->line("purchased_btn"); ?>
							</a>
							<?php
							else:
							?>
							<a class="btn btn-danger btn-take" data-name="featured_one_week">
								<i class="fa fa-diamond"></i> <?php echo $this->lang->line("take_it_btn"); ?>
							</a>
							<?php
							endif;
							?>
						</div>
					</div>
				</div>
				<div class="well premium_feature">
					<div class="row">
						<div class="pf_icon col-md-2">
							<i class="fa fa-rocket"></i>
						</div>
						<div class="pf_desc col-md-8">
							<h4><?php echo $this->lang->line("featured_one_month_title"); ?> <span class="price_desk_premium">&bullet; <span class="badge badge-danger badge-premium-price"><?php echo sprintf($this->lang->line("feature_price"), $settings["featured_one_month_price"]); ?></span></span></h4>
							<span class="badge badge-danger badge-mob-price"><?php echo sprintf($this->lang->line("feature_price"), $settings["featured_one_month_price"]); ?></span>
							<p>
								<?php echo $this->lang->line("featured_one_month_desc"); ?>
								<?php
								if($is_featured_one_month):
									$phpdate = strtotime( $one_month_featured["end_date"] );
									$mysqldate = date( 'Y-m-d', $phpdate );	
								?>
								<i><?php echo sprintf($this->lang->line("premium_until"), $mysqldate); ?></i>.
								<?php
								endif;	
								?>
							</p>
						</div>
						<div class="pf_take_it col-md-2">
							<?php
							if($is_featured_one_week || $is_featured_one_month):
							?>
							<a class="btn  btn-success disabled" data-name="featured_one_week">
								<i class="fa fa-check"></i> <?php echo $this->lang->line("purchased_btn"); ?>
							</a>
							<?php
							else:
							?>
							<a class="btn btn-danger btn-take" data-name="featured_one_month">
								<i class="fa fa-diamond"></i> <?php echo $this->lang->line("take_it_btn"); ?>
							</a>
							<?php
							endif;
							?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	var email = "<?php echo $user["email"]; ?>";	
</script>
<?php
$this->load->view('templates/footers/main_footer');
?>
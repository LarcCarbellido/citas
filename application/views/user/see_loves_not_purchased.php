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
			<h3><?php echo $this->lang->line("Loves"); ?></h3>
		</div>
    </div>
	<div class="row">
		<div class="main_container">
	        <?php
				if(isset($payment_status)) {
			?>
			<div class="alert alert-danger alert-centered">
				<?php echo $payment_status; ?>
			</div>
			<?php
				}
				
				if(isset($_GET["result"]))
				{
					if($_GET["result"] == "cancel") {
					?>
					<div class="alert alert-danger alert-centered">
						<?php echo $this->lang->line("order_cancelled"); ?>
					</div>
					<?php
					}
				}
			?>
			<div class="alert alert-info alert-center" style="font-size: 18px; text-align: center;">
				<?php echo sprintf($this->lang->line("discover_who_loves_you"), $settings["site_name"]); ?>
				<br />
				<img src="<?php echo base_url(); ?>images/heart_billing.png" height="80" style="margin-top: 10px;margin-bottom: 10px;" alt="Heart" />
				<br />
				<?php echo $this->lang->line("share_awesome_things"); ?>
			</div>
			<p style="font-size: 17px; text-align: center;"><?php echo $this->lang->line("purchase_see_who_loves_you"); ?></p>
			<hr />
			<div class="alert-center">
				<a class="btn btn-primary btn-lg" href="<?php echo base_url(); ?>premium"><i class="fa fa-diamond"></i> <?php echo $this->lang->line("see_premium_features_btn"); ?></a>
			</div>
	    </div>
	</div>
</div>
<?php
$this->load->view('templates/footers/main_footer');
?>
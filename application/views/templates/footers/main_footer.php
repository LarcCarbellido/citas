		<div class="container footer_container">
			<footer>
				<?php
				if($pages != null) {
				?>
				<ul class="bottom_menu">
					<?php
					foreach($pages as $cpage):
					?>
					<li>
						<a href="<?php echo base_url() ?>page/<?php echo $cpage["id"]; ?>"><i class="fa fa-<?php echo $cpage["icon"]; ?>"></i> <?php echo $cpage["title"]; ?></a>
					</li>
					<?php
					endforeach;	
					?>
				</ul>
				<?php
				}
				?>
				<div class="copyright">
					© Copyright <?php echo date('Y'); ?> <?php echo $settings["site_name"] ?>
				</div>
			</footer>
		</div>
	    <!-- Mainly scripts -->
	    <script src="<?php echo base_url(); ?>js/jquery-2.1.1.js"></script>
	    <script src="<?php echo base_url(); ?>js/bootstrap.min.js"></script>
		<script src="<?php echo base_url(); ?>js/jquery.nailthumb.min.js"></script>
		<script src="<?php echo base_url(); ?>js/nouislider.min.js"></script>
	    <script src="<?php echo base_url(); ?>js/flatui-fileinput.js"></script>
	    <script src="<?php echo base_url(); ?>js/masonry.min.js"></script>
	    <script src="<?php echo base_url(); ?>js/jquery.timeago.js"></script>
	    <script src="<?php echo base_url(); ?>js/jquery.blueimp-gallery.min.js"></script>
	    <script src="<?php echo base_url(); ?>js/jquery.cssemoticons.min.js"></script>
	    <script src="<?php echo base_url(); ?>js/vendor/jquery.sidr.min.js"></script>
	    <script src="<?php echo base_url(); ?>js/vendor/fastclick.js"></script>

		<script type="text/javascript">
			
			FastClick.attach(document.body);
			
			$(".sidr_btn").sidr();
			$(".message").emoticonize();
			
			var base_url = "<?php echo base_url(); ?>";	
			var cant_love_own_profile_str = "<?php echo $this->lang->line('cant_love_own_profile'); ?>";
			
			var cant_demo_mode_str = "<?php echo $this->lang->line('cant_demo_mode'); ?>";
			var not_logged_in_str = "<?php echo $this->lang->line('not_logged_in_error'); ?>";
			var cant_love_yourself_str = "<?php echo $this->lang->line('cant_love_yourself'); ?>";
			var loved_str = "<?php echo $this->lang->line('has_loved_btn'); ?>";
			var love_str = "<?php echo $this->lang->line('love_btn'); ?>";
			var pm_write_something_str = "<?php echo $this->lang->line('pm_write_something'); ?>";
			var user_does_not_exist_str = "<?php echo $this->lang->line('user_does_not_exist'); ?>";
			var user_blocked_you_str = "<?php echo $this->lang->line('user_blocked_you'); ?>";
			var pm_sent_str = "<?php echo $this->lang->line('pm_sent'); ?>";			
			var friend_request_does_not_exist_str = "<?php echo $this->lang->line('friend_request_does_not_exist'); ?>";
			
			var save_str = "<?php $this->lang->line("Save"); ?>";
			var error_occured_str = "<?php echo $this->lang->line("error_occured"); ?>";
			var whoops_have_errors_str = "<?php echo $this->lang->line("whoops_have_errors"); ?>";
			
			var edit_my_infos_str = "<?php echo $this->lang->line("edit_my_infos"); ?>";
			var edit_my_profile_str = "<?php echo $this->lang->line("edit_my_profile_btn"); ?>";
			var coolness_btn_str = "<?php echo  $this->lang->line("coolness_btn"); ?>";
			var whoops_have_errors_str = "<?php echo  $this->lang->line("whoops_have_errors"); ?>";
			
			var sure_want_block_str = "<?php echo $this->lang->line('sure_want_block'); ?>";
			var cant_block_not_logged_str = "<?php echo $this->lang->line('cant_block_not_logged'); ?>";
			
			var cant_block_yourself_str = "<?php echo $this->lang->line('cant_block_yourself'); ?>";
			var unblock_user_str = "<?php echo $this->lang->line('unblock_user'); ?>";
			var block_user_str = "<?php echo $this->lang->line('block_user'); ?>";
			var sure_want_unblock_str = "<?php echo $this->lang->line('sure_want_unblock'); ?>";
			
			var report_user_alert_str = "<?php echo $this->lang->line('report_user_alert'); ?>";
			var report_user_logged_in_str = "<?php echo $this->lang->line('report_user_logged_in'); ?>";
			var cant_report_yourself_str = "<?php echo $this->lang->line('cant_report_yourself'); ?>";
			var user_reported_str = "<?php echo $this->lang->line('user_reported'); ?>";
			var loved_str = "<?php echo $this->lang->line('has_loved_btn'); ?>";
			
			var love_str = "<?php echo $this->lang->line('love_btn'); ?>";
			var cant_love_yourself_str = "<?php echo $this->lang->line('cant_love_yourself'); ?>";
			
			var cant_send_yourself_pm_str = "<?php echo $this->lang->line('cant_send_yourself_pm'); ?>";
			
			var user_does_not_exist_str = "<?php echo $this->lang->line('user_does_not_exist'); ?>";
			var user_blocked_you_str = "<?php echo $this->lang->line('user_blocked_you'); ?>";
			var pm_sent_str = "<?php echo $this->lang->line('pm_sent'); ?>";
			var accept_request_btn_str = "<?php echo $this->lang->line('accept_request_btn'); ?>";
			
			var cant_send_yourself_request_str = "<?php echo $this->lang->line('cant_send_yourself_request'); ?>";
			var request_already_sent_str = "<?php echo $this->lang->line('request_already_sent'); ?>";
			var cant_request_blocked_str = "<?php echo $this->lang->line('cant_request_blocked'); ?>";
			var his_str = "<?php echo $this->lang->line('his'); ?>";
			var her_str = "<?php echo $this->lang->line('her'); ?>";
			var sent_req_str = "<?php echo $this->lang->line('sent_request_btn'); ?>";
			var request_sent_str = "<?php echo $this->lang->line('request_sent'); ?>";
			var write_comment_placeholder_str = '<?php echo $this->lang->line('write_comment_placeholder'); ?>';
			var report_photo_alert_str = '<?php echo $this->lang->line('report_photo_alert'); ?>';
			var photo_reported_str = '<?php echo $this->lang->line('photo_reported'); ?>';
			
			var send_and_begin_str = "<?php echo $this->lang->line('send_and_begin_btn'); ?>";	
			
			var photo_ext_error_str = "<?php echo $this->lang->line('photo_ext_error'); ?>";	
			var photo_weight_error_str = "<?php echo $this->lang->line('photo_weight_error'); ?>";	
			var whoops_have_errors_str = "<?php echo $this->lang->line('whoops_have_errors'); ?>";	
			var photo_upload_in_progress_str = "<?php echo $this->lang->line('photo_upload_in_progress'); ?>";	
			var coolness_btn_str = "<?php echo $this->lang->line('coolness_btn'); ?>";	
			
			var hide_country = "<?php echo $settings["hide_country"]; ?>";			
			var stripe_pub_key = "<?php echo $settings["stripe_pub_key"]; ?>";
			var site_name = "<?php echo $settings["site_name"]; ?>";			
			var coins_with_paypal_str = "<?php echo $this->lang->line("coins_with_paypal"); ?>";
			var coins_with_stripe_str = "<?php echo $this->lang->line("coins_with_stripe"); ?>";
			var not_enough_credits_str = "<?php echo $this->lang->line("not_enough_credits"); ?>";
			var take_it_str = "<?php echo $this->lang->line("take_it_btn"); ?>";
			
			var see_loves_success_str = "<?php echo $this->lang->line("see_loves_success"); ?>";
			var invisible_success_str = "<?php echo $this->lang->line("invisible_success"); ?>";
			var featured_one_week_success_str = "<?php echo $this->lang->line("featured_one_week_success"); ?>";
			var featured_one_month_success_str = "<?php echo $this->lang->line("featured_one_month_success"); ?>";

			var send_reply_str = "<?php echo $this->lang->line("send_reply_btn"); ?>";
			var write_something_str = "<?php echo $this->lang->line("write_something"); ?>";
			var cant_demo_mode_str = "<?php echo $this->lang->line("cant_demo_mode"); ?>";
			
			var conv_not_exist_str = "<?php echo $this->lang->line("conv_not_exist"); ?>";
			var user_blocked_you_str = "<?php echo $this->lang->line("user_blocked_you"); ?>";
			var write_something_str = "<?php echo $this->lang->line("write_something"); ?>";
			
			var photo_p_sure_delete_photo_str = "<?php echo $this->lang->line('p_sure_delete_photo'); ?>";
			
			var his_str = "<?php echo $this->lang->line('his'); ?>";
			var her_str = "<?php echo $this->lang->line('her'); ?>";
			var sent_req_str = "<?php echo $this->lang->line('sent_request_btn'); ?>";
			var request_sent_str = "<?php echo $this->lang->line('request_sent'); ?>";
			var write_comment_placeholder_str = '<?php echo $this->lang->line('write_comment_placeholder'); ?>';
			var photo_weight_error_str = "<?php echo $this->lang->line('photo_weight_error'); ?>";
			var description_str = "<?php echo $this->lang->line('Description'); ?>";
			var description_placeholder_str = "<?php echo $this->lang->line('photo_description_placeholder'); ?>";
			var demo_mode_str = "<?php echo $this->lang->line('cant_demo_mode'); ?>";
			var upload_limit_reached_str = "<?php echo $this->lang->line('p_upload_limit'); ?>";
			
			<?php if($settings["currency"] == ""): ?>
			var currency = "USD";
			<?php else: ?>
			var currency = "<?php echo $settings["currency"]; ?>";
			<?php endif; ?>
		</script>
		
		<?php 
		foreach($jscripts as $js):	
		?>
		<script type="text/javascript" src="<?php echo $js; ?>"></script>
		<?php
		endforeach;
		?>
	    

		<?php echo $settings["site_analytics"]; ?>
	</body>

</html>
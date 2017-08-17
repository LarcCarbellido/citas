<?php
$this->load->view('templates/headers/welcome_header', $title);
?>
	<div class="firstpartheader">
	    <div class="container">
	        <div class="row first_part">
	            <div class="logo pull-left">
	                <a href="<?php echo base_url(); ?>"><?php echo $settings["site_name"]; ?></a>
	            </div>
	            <div class="pull-right actions-btn">
					<ul class="buttons_reglog">
						<li class="btn-login graphite-flat-button"><a href="<?php echo base_url(); ?>"><?php echo $this->lang->line('sign_in'); ?></a></li>
					</ul>	
				</div>
	        </div>
	            
			<div class="row">
	            <h4 class="sub-title"><?php echo $this->lang->line('recover_password'); ?></h4>
	            <div class="row">
	                <div class="col-md-12">
	                    <div class="logreg_block">
	                        <div class="row">
								<form action="" method="POST" class="form-reg-part-2 password_form col-md-8 col-md-offset-2 form-horizontal" style="display: block;">
									<div class="form-group">
										<label><?php echo $this->lang->line('new_password'); ?></label>
										<input type="password" placeholder="<?php echo $this->lang->line('new_password_placeholder'); ?>" class="form-control password1" />
									</div>
									<div class="form-group">
										<label class="control-label"><?php echo $this->lang->line('new_password_confirmation'); ?></label>
										<input type="password" placeholder="<?php echo $this->lang->line('new_password_confirmation_placeholder'); ?>" class="form-control password2" />
									</div>
									<input type="hidden" class="encrypt_id" value="<?php echo $encrypt_id ?>" />
									<input type="hidden" class="rec_user_id" value="<?php echo $user["uid"]; ?>" />
									<hr />
									<div class="alert alert-danger alert-recovery-password">
									
									</div>
									<div style="text-align:center;">
										<input type="submit" class="btn btn-primary btn-embossed btn-update-password" value="<?php echo $this->lang->line('update_password_btn'); ?>" />
									</div>
								</form>
				            </div>
		                </div>
		            </div>
		        </div>
	        </div>
		</div>
		
		
		<script type="text/javascript">
			var base_url = "<?php echo base_url() ?>";
			var hack_password_error_str = "<?php echo $this->lang->line('hack_password_error'); ?>";
			var password_dont_match_str = "<?php echo $this->lang->line('password_dont_match'); ?>";
			var password_4_chars_error_str = "<?php echo $this->lang->line('password_4_chars_error'); ?>";
			var password_recovery_success_str = "<?php echo $this->lang->line('password_recovery_success'); ?>";
		</script>
		
		<!-- Load JS here for greater good =============================-->
		<script src="<?php echo base_url(); ?>js/jquery-2.1.1.js"></script>
		<script src="<?php echo base_url(); ?>js/jquery-ui-1.10.4.min.js"></script>
		<script src="<?php echo base_url(); ?>js/bootstrap.min.js"></script>
		<script src="<?php echo base_url(); ?>js/jquery.cookie.min.js"></script>
		<script src="<?php echo base_url(); ?>js/pages/recover_password.js"></script>
	</body>
</html>
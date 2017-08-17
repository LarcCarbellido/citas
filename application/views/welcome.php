<?php
$this->load->view('templates/headers/welcome_header', $title);
?>	
<?php
if(isset($_GET["error"])) {
	if($_GET["error"] == "cookie") {
?>
<div class="topbar error">
	<?php echo $this->lang->line("select_gender_orientation"); ?>
</div>
<?php
	} else if($_GET["error"] == "demo_mode") {
?>
<div class="topbar error">
	Whoops! You can't login / sign up with Facebook in demo mode.
</div>
<?php
	}
}
?>
<div class="firstpartheader">
	<div class="firstpartheader_bgcolor">
	    <div class="container">
	
	        <div class="row first_part">
	            <div class="pull-right actions-btn animated fadeIn">
	                <ul class="buttons_reglog">
	                    <li class="already_registered">
	                        <?php echo $this->lang->line('already_registered'); ?>
	                    </li>
	                    <li class="btn-login graphite-flat-button"><a href="#"><?php echo $this->lang->line('sign_in'); ?></a></li>
	                </ul>
	            </div>
	        </div>
	
	        <div class="row">
		        <div class="logo animated fadeIn">
			        <a href="<?php echo base_url(); ?>">
	                    <?php echo $settings["site_name"]; ?>
	                </a>
		        </div>
	            <h1 class="main-title animated pulse"><?php echo $this->lang->line('main_headline'); ?></h1>
	            <div class="row">
	                <div class="col-md-12">
	                    <div class="logreg_block animated fadeIn">
	                        <div class="row">
	                            <div class="form_reg">
	                                <form role="form" class="col-md-8 col-md-offset-2" role="form" id="loginmob">
	                                    <div class="form-group">
	                                        <input type="text" class="form-control" id="logusernamemob" placeholder="<?php echo $this->lang->line('username'); ?>">
	                                    </div>
	                                    <div class="form-group" style="margin-bottom:0;">
	                                        <input type="password" class="form-control" id="logpasswordmob" placeholder="<?php echo $this->lang->line('password'); ?>">
	                                    </div>
	                                    <div style="text-align:center;">
	                                        <div class="error-login alert alert-error" style="margin-top: 10px;">
	                                        </div>
	                                        <button type="submit" class="btn-login-ok-mob btn btn-warning btn-block btn-lg" style="margin-top: 15px;">
	                                            <?php echo $this->lang->line('sign_in'); ?>
	                                        </button>
	                                        <span class="help-block" style="font-size:15px;"><a style="color:#FFF;" class="forgot_password" href="index.html#"><?php echo $this->lang->line('forgot_your_password'); ?></a></span>
	                                    </div>
	                                </form>
	                                <p class="strikey">OR</p>
									<div class="row">
										<p style="text-align: center;">
											<?php echo $this->lang->line("if_facebook"); ?>
										</p>
										<div class="col-md-6 col-md-offset-3">
											<a class="btn btn-lg btn-block flatbutton blue" href="<?php echo $fb_login_url; ?>">
												<i class="fa fa-facebook-square"></i> <?php echo $this->lang->line("facebook_connect"); ?>
											</a>
										</div>
									</div>
	                            </div>
	                            <form action="" method="POST" class="form-inline col-md-12 form-reg-part-1">
	                                <div class="col-md-2 form_txt">
	                                    <?php echo $this->lang->line('i_am_a'); ?>
	                                </div>
	                                <div class="col-md-4">
	                                    <select class="form_looking_for form-control select select-primary">
	                                        <option value="0">
	                                            <?php echo $this->lang->line('straight'); ?>
	                                        </option>
	                                        <option value="1">
	                                            <?php echo $this->lang->line('gay'); ?>
	                                        </option>
	                                        <option value="2">
	                                            <?php echo $this->lang->line('bisexual'); ?>
	                                        </option>
	                                    </select>
	                                </div>
	                                <div class="col-md-4">
	                                    <select class="form_gender form-control select select-primary">
	                                        <option value="0">
	                                            <?php echo $this->lang->line('man'); ?>
	                                        </option>
	                                        <option value="1">
	                                            <?php echo $this->lang->line('woman'); ?>
	                                        </option>
	                                    </select>
	                                </div>
	                                <div class="col-md-2">
	                                    <button type="submit" class="btn-continue">
	                                        <?php echo $this->lang->line('continue'); ?>
	                                    </button>
	                                </div>
	                            </form>
	                            <div class="col-md-12">
		                            <form role="form" class="form-reg-part-2">
			                            <div class="col-md-4">
				                            <h3><?php echo $this->lang->line("facebook_sign_up"); ?></h3>
				                            <div class="facebook_choice">
												<p class="description">
													<?php echo $this->lang->line("facebook_sign_up_faster"); ?>
												</p>
												<a class="btn btn-lg btn-block flatbutton blue" href="<?php echo $fb_login_url; ?>">
													<i class="fa fa-facebook-square"></i> <?php echo $this->lang->line("facebook_connect"); ?>
												</a>
											</div>
			                            </div>
			                            <p class="or_horizontal_line strikey"><?php echo $this->lang->line("facebook_or"); ?></p>
										<div class="col-md-2 or_vertical_line" style="position: relative;height:503px;">
											<div class="line"></div>
										    <div class="wordwrapper">
										        <div class="word"><?php echo $this->lang->line("facebook_or"); ?></div>                                        
										    </div>
										</div>
			                            <div class="col-md-6 normal-reg-container">
			                            
				                            <h3>Normal Sign-Up</h3>
			                                <div class="form-group">
			                                    <label for="site_username">
			                                        <?php echo $this->lang->line('username'); ?>
			                                    </label>
			                                    <input type="text" class="form-control username" id="site_username" placeholder="<?php echo $this->lang->line('username_placeholder'); ?>">
			                                </div>
			                                <div class="form-group">
			                                    <label for="site_email">
			                                        <?php echo $this->lang->line('email'); ?>
			                                    </label>
			                                    <input type="email" class="form-control email" id="site_email" placeholder="<?php echo $this->lang->line('email_placeholder'); ?>">
			                                </div>
			                                <div class="form-group">
			                                    <label for="site_password">
			                                        <?php echo $this->lang->line('password'); ?>
			                                    </label>
			                                    <input type="password" class="form-control password" id="site_password" placeholder="<?php echo $this->lang->line('password_placeholder'); ?>">
			                                </div>
			                                <?php
											if($settings["web_captcha"] == 1):
											?>
		                                    <div class="form-group">
		                                        <label for="snapals_password">
		                                            <?php echo $captcha->question; ?>
		                                        </label>
		                                        <input type="text" class="form-control captcha_answer" id="snapals_password" placeholder="<?php echo $this->lang->line('captcha_placeholder'); ?>">
		                                        <input type="hidden" class="captcha_id" value="<?php echo $captcha->id ?>" />
		                                        <p class="help-block"><i class="fa fa-info-circle"></i>
		                                            <?php echo $this->lang->line('captcha_desc'); ?>
		                                        </p>
		                                    </div>
		                                    <?php
											else:
											?>
		                                        <input type="hidden" class="captcha_answer" value="" />
		                                        <input type="hidden" class="captcha_id" value="" />
		                                        <?php
											endif;
											?>
			                                <div class="error-register alert alert-danger">
			
			                                </div>
			                                <div class="reg_btn_placeholder">
			                                    <a href="index.html#" class="graphite-flat-button btn-register"><?php echo $this->lang->line('register_btn'); ?></a>
			                                </div>
			                            </div>
		                            </form>
	                            </div>
	                        </div>
	                        <span id="result" class="loading"></span>
	                    </div>
	                </div>
	            </div>
	        </div>
	    </div>
	</div>
</div>
<?php
if(sizeof($users) >= 4):
?>
<div class="container ucarousel">
	<div id="user-carousel">
	  <?php 
	  foreach($users as $user):
		if($user["thumb_url"] == "" || $user["thumb_url"] == NULL) {
			$user["thumb_url"] = "images/avatar.png";
		}
	  ?>
	  <?php
	  if($user["is_fake"] == 1):
	  ?>
	  <div class="item"><img src="<?php echo $user["thumb_url"]; ?>" alt="Owl Image"></div>
	  <?php
	  else:
	  ?>
	  <div class="item"><img src="<?php echo base_url() . $user["thumb_url"]; ?>" alt="Owl Image"></div>
	  <?php
	  endif;
	  endforeach;
	  ?>
	 
	</div>
</div>
<?php
endif;
?>
<div class="container">
    <div class="second-part-titles  animated fadeIn">
        <h2><?php echo $this->lang->line('how_it_works'); ?></h2>
        <h4><?php echo $this->lang->line('as_simple_as_that'); ?></h4>
    </div>

    <div class="how_it_works clearfix  animated fadeIn">
        <div class="block_how_to col-md-4">
            <i class="fa fa-users"></i>
            <div class="how_to_title">
                <?php echo $this->lang->line('join_us'); ?>
            </div>
            <div class="how_to_desc">
                <?php echo $this->lang->line('join_us_txt'); ?>
            </div>
        </div>
        <div class="block_how_to col-md-4">
            <i class="fa fa-user-plus"></i>
            <div class="how_to_title">
                <?php echo $this->lang->line('add_friends'); ?>
            </div>
            <div class="how_to_desc">
                <?php echo $this->lang->line('add_friends_txt'); ?>
            </div>
        </div>
        <div class="block_how_to col-md-4">
            <i class="fa fa-comments"></i>
            <div class="how_to_title">
                <?php echo $this->lang->line('start_chat'); ?>
            </div>
            <div class="how_to_desc">
                <?php echo $this->lang->line('start_chat_txt'); ?>
            </div>
        </div>
    </div>
</div>


<!-- Container -->
<div class="container">
    <section class="row footercontent">
        <div class="col-sm-4 social-icons">
            <?php
				if($settings["fb_url"] != ""):
				?>
                <a href="<?php echo $settings["fb_url"]; ?>" target="_blank" class="facebook social"><i class="fa fa-facebook-square"></i></a>
                <?php
				endif;
				?>
                    <?php
				if($settings["twitter_url"] != ""):
				?>
                        <a href="<?php echo $settings["twitter_url"]; ?>" target="_blank" class="twitter social"><i class="fa fa-twitter-square"></i></a>
                <?php
				endif;
				?>
                <?php
				if($settings["instagram_url"] != ""):
				?>
                  <a href="<?php echo $settings["instagram_url"]; ?>" target="_blank" class="instagram social"><i class="fa fa-instagram"></i></a>
                                <?php
				endif;
				?>
                                    <?php
				if($settings["googleplus_url"] != ""):
				?>
                                        <a href="<?php echo $settings["googleplus_url"]; ?>" target="_blank" class="gplus social"><i class="fa fa-google-plus-square"></i></a>
                                        <?php
				endif;
				?>
        </div>
        <div class="col-sm-4 copyright pull-right">
            <p>© Copyright
                <?php echo date("Y"); ?>
                    <?php echo $settings["site_name"]; ?>
            </p>
        </div>
    </section>
</div> 
		
<?php
if($pages != null):
?>	
<footer>
	<ul class="footer_links">
		<?php
		foreach($pages as $cpage):
		?>
		<li>
			<a href="#" data-id="<?php echo $cpage["id"]; ?>"><i class="fa fa-<?php echo $cpage["icon"]; ?>"></i> <?php echo $cpage["title"]; ?></a>
		</li>
		<?php
		endforeach;	
		?>
	</ul>
</footer>
<div class="modal fade" id="welcome_page_modal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title"></h4>
			</div>
			<div class="modal-body">
			</div>
		</div>
	</div>
</div>
<?php
endif;	
?>

<?php
$this->load->view('templates/footers/welcome_footer');
?>
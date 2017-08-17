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
			<h3><?php echo $this->lang->line("friends_title"); ?></h3>
        </div>
	</div>
	<div class="row">
		<div class="main_container">
			<div class="col-md-4">
				<a class="btn btn-danger btn-block btn-show-friend-requests" href="<?php echo base_url(); ?>user/requests"><i class="fa fa-user-plus"></i> <?php echo $this->lang->line("friend_requests_btn"); ?> (<?php echo $nb_new_requests; ?>)</a>
			</div>
			<div class="alert alert-info col-md-8 alert-center">
				<?php echo $this->lang->line("here_list_friends"); ?>
			</div>
			<div id="userslst" class="clearfix">
				<?php
		    	$cpt = 1;
		    	?>
		    	<?php foreach($users as $user): ?>
		    	
		    	<?php
		    	$birthdate = new DateTime($user["birthday"]);
				$today     = new DateTime();
				$interval  = $today->diff($birthdate);
				$age	   = $interval->format('%y');
				
				if($user["gender"] == 0) {
					$gender_color = "male_color";
				} else {
					$gender_color = "female_color";
				}
				
				if($user["thumb_url"] == "" || $user["thumb_url"] == NULL) {
					$user["thumb_url"] = "images/avatar.png";
				}
				?>
		    	
				<div class="col-lg-3 col-md-4 col-xs-6 clearfix user_block user_poke_friends" data-user-id="<?php echo $user["uid"]; ?>" data-cpt="<?php echo $cpt; ?>">
					<div class="thumb">
		                <a class="thumbnail" href="<?php echo base_url(); ?>user/profile/<?php echo $user["uid"]; ?>">
			                <img src="<?php echo base_url() . $user["thumb_url"]; ?>" alt="Photo User" />
			                <?php
				            if($user["st_love"] == 1)
				            {
					        ?>
					        <div class="love_button loved" data-profile-id="<?php echo $user["uid"]; ?>"><i class="fa fa-heart"></i></div>
					        <?php
						    } else {
							?>
							<div class="love_button" data-profile-id="<?php echo $user["uid"]; ?>"><i class="fa fa-heart"></i></div>
							<?php
						    }
				            ?>
			                <?php
				                if($user["is_online"] == 1):
				            ?>
			                <div class="online_status"><i class="fa fa-circle"></i></div>
			                <?php
				                endif;
				            ?>
		               	</a>
					   	<div class="userslst_infos">
						   	<a href="<?php echo base_url(); ?>user/profile/<?php echo $user["uid"]; ?>" class="userslst_username <?php echo $gender_color; ?>"><?php echo $user["username"]; ?></a>
						   	<?php
							if($user["city"] == "") {
				                $user["city"] = $this->lang->line("unknown_city");
			                }
			                ?>
						   	<div class="userslst_age"><?php echo $age; ?> &#8226; <?php if($settings["hide_country"] == 0): ?><?php echo get_country_name_by_code($user["country"]); ?><?php else: ?><?php echo $user["city"]; ?><?php endif; ?></div>
						   	<div class="btn-poke-back-placeholder">
							   	<a class="btn btn-primary btn-block btn-send-friend-pm" href="#" data-user-id="<?php echo $user["uid"]; ?>">
									<i class="fa fa-envelope"></i> <?php echo $this->lang->line("send_message_btn"); ?>
							   	</a>
						   	</div>
						</div>
					</div>
	            </div>
				<?php
				$cpt++;
				endforeach;
				?>
			</div>
			<?php
			if($links != ""):
			?>
			<div class="btnmoreplaceholder message">
				<?php echo $links; ?>
			</div>
			<?php
			endif; 
			?>
			<?php
			if(sizeof($users) == 0):
			?>
			<div class="alert alert-danger alert-center">
				<?php echo $this->lang->line("no_friends_for_now"); ?>
			</div>
			<?php
			endif;
			?>
        </div>
	</div>
</div>
<div class="modal inmodal" id="msg_modal" tabindex="-1" role="dialog"  aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content animated fadeIn">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only"><?php echo $this->lang->line('Close'); ?></span></button>
                <h4 class="modal-title"><?php echo $this->lang->line("pm_modal_title"); ?></h4>
            </div>
            <div class="modal-body">
            	<p>
	                <form action="" method="POST" enctype="multipart/form-data" class="form_avatar">
						<div class="form-group" style="text-align:left;">
							<label class="control-label"><?php echo $this->lang->line("your_message_pm_modal"); ?></label>
							<textarea class="form-control form-pm-text" id="pm_main_txt" placeholder="<?php echo $this->lang->line("your_message_pm_modal_placeholder"); ?>"></textarea>
						</div>
						<div class="send_pm_errors alert alert-danger alert-center">
					
						</div>
						<div style="text-align:center">
							<a href="#" class="btn btn-primary btn-submit-send-pm" data-user-id="<?php echo $user["uid"]; ?>"><i class="fa fa-envelope"></i> <?php echo $this->lang->line("send_my_pm_btn"); ?></a>
						</div>
					</form>
            	</p>
            </div>
        </div>
    </div>
</div>
<?php
$this->load->view('templates/footers/main_footer');
?>
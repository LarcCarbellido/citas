<?php
$this->load->view('templates/headers/main_header', $title);

if($user["gender"] == 0) {
	$gender_word = $this->lang->line('his');
	$gender_word2 = $this->lang->line('Him');
} else {
	$gender_word = $this->lang->line('her');
	$gender_word2 = $this->lang->line('Her');
}

$birthdate = new DateTime($user["birthday"]);
$today     = new DateTime();
$interval  = $today->diff($birthdate);
$age	   = $interval->format('%y');

if($user["gender"] == 0) {
	$gender_color = "male_color";
	$gender_type = $this->lang->line('Man');;
} else {
	$gender_color = "female_color";
	$gender_type = $this->lang->line('Woman');
}

if($user["interested_in"] == 0) {
	$interested_in = $this->lang->line('Straight');
} else if($user["interested_in"] == 1) {
	$interested_in = $this->lang->line('Gay');
} else {
	$interested_in = $this->lang->line('Bisexual');
}

function in_array_r($needle, $haystack, $strict = false) {
    foreach ($haystack as $item) {
        if (($strict ? $item === $needle : $item == $needle) || (is_array($item) && in_array_r($needle, $item, $strict))) {
            return true;
        }
    }

    return false;
}
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
		<div class="main_container">

		    <div class="row profile_photo_cont">
		        <div class="col-md-3 photocol">
		            <div class="ibox float-e-margins">
		                <div>
		                    <div class="ibox-content no-padding border-left-right avatar_user_profile">
		                        <img alt="image" height="248" class="img-responsive" src="<?php echo $avatar; ?>">
		                    </div>
		                </div>
		            </div>
		        </div>
		        <div class="col-md-6 profile-details">
			        <h3><?php echo ucfirst($user["username"]); ?></h3>
			        <div class="userslst_age">
				        <?php
					    if($user["city"] == "") {
			                $user["city"] = $this->lang->line("unknown_city");
		                }
					    if($user["is_online"] == 1):  
					    ?>
					    <div class="profile_online_now"><i class="fa fa-circle"></i> <?php echo $this->lang->line('p_online_now'); ?></div>
					    <?php else: ?>
				        <?php echo $this->lang->line("p_last_online"); ?> <b><span class="pf_last_online" title="<?php echo $user["last_activity_date"]; ?>Z"></span></b>
				        <?php
					    endif; 
					    ?>
					    <hr class="sep_user">
				        <?php echo $age; ?> <?php echo $this->lang->line('y.o.'); ?> &#8226; <?php if($settings["hide_country"] == 0): ?><?php echo get_country_name_by_code($user["country"]); ?>,<?php endif; ?> <?php echo $user["city"]; ?><br />
				        <?php echo $gender_type; ?> &#8226; <?php echo $interested_in; ?><br />
				        
				    </div>
		        </div>
		        <div class="col-md-3 col-actions-profile">
			        <?php 
				    if(!$has_requested)
				    {
					?>
						<?php
						if($has_pending) {
						?>
						<button data-user-id="<?php echo $user["uid"]; ?>" data-placement="top" data-toggle="tooltip" data-original-title="<?php echo $this->lang->line('user_sent_request'); ?>" class="btn btn-success dim btn-block btn-accept-friend btn-poked" type="button">
							<i class="fa fa-user-plus"></i>
							<?php echo $this->lang->line('accept_request_btn'); ?>
						</button>
						<?php
						} else {
						?>
						<?php
						if($are_friends) {	
						?>
						<button disabled="" class="btn btn-primary btn-block btn-friends-ok" type="button">
							<i class="fa fa-check"></i>
							<?php echo $this->lang->line('are_friends_btn'); ?>
						</button>
						<?php
						} else {
						?>
				        <button class="btn btn-primary dim btn-block btn-poke" type="button">
							<i class="fa fa-user-plus"></i>
							<?php echo $this->lang->line('friend_btn'); ?>
						</button>
						<?php
						}
						}
					} else {
						if($has_pending) {
						?>
						<button data-user-id="<?php echo $user["uid"]; ?>" data-placement="top" data-toggle="tooltip" data-original-title="<?php echo $this->lang->line('user_sent_request'); ?>" class="btn btn-success dim btn-block btn-accept-friend btn-poked" type="button">
							<i class="fa fa-user-plus"></i>
							<?php echo $this->lang->line('accept_request_btn'); ?>
						</button>
						<?php
						} else {
						?>
						<button disabled="" data-placement="top" data-toggle="tooltip" data-original-title="You sent a request to this user" class="btn btn-default dim btn-block btn-poke btn-poked" type="button">
							<i class="fa fa-user-plus"></i>
							<?php echo $this->lang->line('sent_request_btn'); ?>
						</button>
						<?php
						}
					}			
					?>
					<button class="btn btn-info dim btn-block btn-msg" type="button">
						<i class="fa fa-envelope"></i> <?php echo $this->lang->line('send_message_btn'); ?>
					</button>
					<?php
					if($has_loved)
					{
					?>
			        <button class="btn btn-danger btn-block btn-love" data-profile-id="<?php echo $user["uid"]; ?>" type="button">
						<i class="fa fa-heart"></i> <?php echo $this->lang->line('has_loved_btn'); ?>
					</button>
					<?php
					} else {
					?>
					<button class="btn btn-danger dim btn-block btn-love" data-profile-id="<?php echo $user["uid"]; ?>" type="button">
						<i class="fa fa-heart"></i> <?php echo $this->lang->line('love_btn'); ?>
					</button>
					<?php
					}
					?>
		        </div>
		    </div>
		    <div class="row">
			    <div class="col-md-12">
				    <div class="profile_tabs">
						<div class="tabs-container">
		                    <ul class="nav nav-tabs">
			                    <li class="active"><a data-toggle="tab" href="#tab-0"><?php echo ucfirst($user["username"]); ?></a></li>
			                    <?php
				                if($settings["hide_timeline"] == 0):
				                ?>
		                        <li><a data-toggle="tab" href="#tab-1"><?php echo $this->lang->line('p_timeline'); ?></a></li>
		                        <li><a data-toggle="tab" href="#tab-2"><?php echo $this->lang->line('p_photos'); ?></a></li>
		                        <?php
			                    else:
			                    ?>
		                        <li class="active"><a data-toggle="tab" href="#tab-2"><?php echo $this->lang->line('p_photos'); ?></a></li>
		                        <?php
			                    endif;  
			                    ?>
		                    </ul>
		                    <div class="tab-content">
			                    <div id="tab-0" class="tab-pane active">
		                            <div class="panel-body">
			                            <h3><?php echo $this->lang->line('p_about'); ?></h3>
			                            <p>
				                           <?php
								            if($user["about"] != "" || $user["about"] != NULL) {
									        ?>
									        	<?php echo $user["about"]; ?>
									        <?php
								            } else {
									        ?>
									        	<?php echo sprintf($this->lang->line('no_about'), $user["username"]); ?>
									        <?php
								            }
			            					?> 
			                            </p>
			                            <?php
				                        $extra_fields = $settings["user_extra_fields"];
				                        
				                        $user_fields = explode(",", $extra_fields);  
		
				                       	if(sizeof($user_fields) > 1)
				                       	{                        
					                        foreach($user_fields as $u_field) {
						                    ?>
						                    <h3><?php echo ucfirst($u_field); ?></h3>
						                    <?php
							                $is_found = false;
							                foreach($extra_user as $e_user) {
								                if($e_user["attr_name"] == $u_field && $e_user["attr_val"] != "") {
									            ?>
									            <p>
										            <?php echo $e_user["attr_val"]; ?>
									            </p>
									            <?php
										        $is_found = true;
										        break;
								                }
							                }  
							                if(!$is_found) {
								            ?>
								            <p>
									            <i><?php echo sprintf($this->lang->line('no_custom_user_val'), ucfirst($user["username"])); ?></i>
								            </p>
								            <?php
							                }
							                ?>
						                    <?php
					                        }
				                        }
				                        ?>
		                            </div>
		                        </div>
			                    <?php
				                if($settings["hide_timeline"] == 0):
				                ?>
		                        <div id="tab-1" class="tab-pane">
		                            <div class="panel-body">
			                            <div class="ibox float-e-margins timeline-user">
							                <div class="ibox-content profile-about profile-timeline">
												<div id="vertical-timeline" class="vertical-container dark-timeline">
													<?php
													foreach($timeline_msgs as $msg)
													{
													?>
							                       	<div class="vertical-timeline-block">
							                            <div class="vertical-timeline-icon navy-bg">
							                                <i class="fa <?php echo $msg["icon"]; ?>"></i>
							                            </div>
							
							                            <div class="vertical-timeline-content">
							                                <h2><?php echo ucfirst($msg["text"]); ?></h2>
							                                <span class="vertical-date">
							                                    <small><?php echo sprintf($this->lang->line('user_timeline_published_date'), $msg["date"] . "Z"); ?></small>
							                                </span>
							                            </div>
							                        </div>
							                        <?php
								                    }
								                    ?>
							                    </div>
							                </div>
							            </div>
		                            </div>
		                        </div>
		                        <div id="tab-2" class="tab-pane">
		                            <div class="panel-body">
			                            <?php
										if(sizeof($photos) == 0) {
										?>
										<div class="alert alert-danger alert-center">
											<?php echo sprintf($this->lang->line("p_no_photos_yet"), $user["username"]); ?>
										</div>
										<?php
										} else {
										
										if($this->session->userdata("user_id") == $user["uid"]) {
										?>
										<div class="photo_actions">
											<a class="btn btn-primary" href="<?php echo base_url(); ?>photo/add"><i class="fa fa-plus"></i> <?php echo $this->lang->line("p_add_photos"); ?></a>
											<a class="btn btn-primary" href="<?php echo base_url(); ?>photo/manage"><i class="fa fa-cogs"></i> <?php echo $this->lang->line("p_manage_photos"); ?></a>
										</div>
										<?php
										}
										
										?>
										<div class="lightBoxGallery" id="galleryItems">
											<?php 
											foreach($photos as $photo) {
											?>
											<div class="galleryItem" data-id="<?php echo $photo["id"]; ?>">
												<?php
												if($user["is_fake"] == 0)
												{	
												?>
									    		<a href="<?php echo base_url() . $photo["url"] ?>"><img src="<?php echo base_url() . $photo["thumb_url"] ?>" alt="" /></a>
									    		<?php
										    	} else {
										    	?>
									    		<a href="<?php echo $photo["url"] ?>"><img src="<?php echo $photo["thumb_url"] ?>" alt="" /></a>
										    	<?php
											    }
											    ?>
									    		<div class="p_info_block">
										    		<i class="fa fa-thumbs-up"></i> <?php echo $photo["votes"]; ?>
										    		<span class="p_bullet">&bullet;</span>
										    		<i class="fa fa-comments"></i> <?php echo $photo["comments"]; ?>
									    		</div>
									    	</div>
											<?php
											}
											?>
										</div>
										<?php
										}
										?>
		                            </div>
		                        </div>
		                        <?php
			                    else:
			                    ?>
		                        <div id="tab-2" class="tab-pane active">
		                            <div class="panel-body">
			                            <?php
										if(sizeof($photos) == 0) {
										?>
										<div class="alert alert-danger alert-center">
											<?php echo sprintf($this->lang->line("p_no_photos_yet"), $user["username"]); ?>
										</div>
										<?php
										} else {
										
										if($this->session->userdata("user_id") == $user["uid"]) {
										?>
										<div class="photo_actions">
											<a class="btn btn-primary" href="<?php echo base_url(); ?>photo/add"><i class="fa fa-plus"></i> <?php echo $this->lang->line("p_add_photos"); ?></a>
											<a class="btn btn-primary" href="<?php echo base_url(); ?>photo/manage"><i class="fa fa-cogs"></i> <?php echo $this->lang->line("p_manage_photos"); ?></a>
										</div>
										<?php
										}
										
										?>
										<div class="lightBoxGallery" id="galleryItems">
											<?php 
											foreach($photos as $photo) {
											?>
											<div class="galleryItem" data-id="<?php echo $photo["id"]; ?>">
									    		<a href="<?php echo base_url() . $photo["url"] ?>"><img src="<?php echo base_url() . $photo["thumb_url"] ?>" alt="" /></a>
									    		<div class="p_info_block">
										    		<i class="fa fa-thumbs-up"></i> <?php echo $photo["votes"]; ?>
										    		<span class="p_bullet">&bullet;</span>
										    		<i class="fa fa-comments"></i> <?php echo $photo["comments"]; ?>
									    		</div>
									    	</div>
											<?php
											}
											?>
										</div>
										<?php
										}
										?>
		                            </div>
		                        </div>
		                        <?php
			                    endif;
			                    ?>
		                    </div>
		                </div>
					
					</div>
		        </div>
		    </div>
			<div class="report-placeholder">
				<a class="btn btn-report" data-user-id="<?php echo $user["uid"]; ?>"><i class="fa fa-bullhorn"></i> <?php echo $this->lang->line('report_user'); ?></a> <i class="fa fa-circle bullet-sep"></i> 
				<?php
				if($user["st_blocked"] == 0):
				?>
				<a class="btn btn-block-user btn-block-u" data-user-id="<?php echo $user["uid"]; ?>"><i class="fa fa-frown-o"></i> <?php echo $this->lang->line('block_user'); ?></a>
				<?php
				else:
				?>
				<a class="btn btn-block-user btn-unblock-u" data-user-id="<?php echo $user["uid"]; ?>"><i class="fa fa-smile-o"></i> <?php echo $this->lang->line('unblock_user'); ?></a>
				<?php
				endif; 
				?>
			</div>
		</div>
	</div>
</div>
<div id="blueimp-gallery" class="blueimp-gallery blueimp-gallery-controls">
    <div class="slides"></div>
    <h3 class="title"></h3>
    <a class="prev">‹</a>
    <a class="next">›</a>
    <a class="close">×</a>
    <a class="play-pause"></a>
    <ol class="indicator"></ol>
</div>
<div class="modal inmodal" id="photo_modal" tabindex="-1" role="dialog"  aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content animated fadeIn">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only"><?php echo $this->lang->line('Close'); ?></span></button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body">
            	
            </div>
            <div class="modal-footer modal-photo-actions">
            	<a href="#" class="btn btn-report-photo"><?php echo $this->lang->line('report_photo'); ?></a>
            </div>
        </div>
    </div>
</div>

<div class="modal inmodal" id="poke_modal" tabindex="-1" role="dialog"  aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content animated fadeIn">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only"><?php echo $this->lang->line('Close'); ?></span></button>
                <i class="fa fa-clock-o modal-icon"></i>
                <h4 class="modal-title"><?php echo $this->lang->line("friend_request_modal_title"); ?></h4>
                <p><?php echo sprintf($this->lang->line('want_send_request'), $user["username"]); ?></p>
            </div>
            <div class="modal-body">
            	<p style="text-align: center;">
                	<button class="btn btn-success btn-yeah-poke btn-lg" type="button" data-user-id="<?php echo $user["uid"]; ?>"><?php echo $this->lang->line('yeah_btn'); ?></button>
					<button class="btn btn-default btn-nope-poke btn-lg" data-dismiss="modal" type="button"><?php echo $this->lang->line('nope_btn'); ?></button>
            	</p>
            </div>
        </div>
    </div>
</div>

<div class="modal inmodal" id="msg_modal" tabindex="-1" role="dialog"  aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content animated fadeIn">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only"><?php echo $this->lang->line('Close'); ?></span></button>
                <i class="fa fa-message-o modal-icon"></i>
                <h4 class="modal-title"><?php echo $this->lang->line("pm_modal_title"); ?></h4>
            </div>
            <div class="modal-body">
            	<p>
                	<?php
                    if($are_friends) {
	                ?>
	                <form action="" method="POST" enctype="multipart/form-data" class="form_avatar">
						<div class="form-group" style="text-align:left;">
							<label class="control-label"><?php echo $this->lang->line("your_message_pm_modal"); ?></label>
							<textarea class="form-control form-pm-text" id="pm_main_txt" placeholder="<?php echo $this->lang->line("your_message_pm_modal_placeholder"); ?>"></textarea>
						</div>
						<div class="send_pm_errors alert alert-danger alert-center" style="display: none;">
					
						</div>
						<div style="text-align:center">
							<a href="#" class="btn btn-primary btn-submit-send-pm" data-user-id="<?php echo $user["uid"]; ?>"><i class="fa fa-envelope"></i> <?php echo $this->lang->line("send_my_pm_btn"); ?></a>
						</div>
					</form>
	                <?php
                    } else {
	                ?>
	                <div class="alert alert-info alert-center">
		                <?php echo $this->lang->line("not_friend_no_pm"); ?>
	                </div>
	                <?php
                    }
                    ?>
            	</p>
            </div>
        </div>
    </div>
</div>
<?php
$this->load->view('templates/footers/main_footer');
?>
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
			<h3><?php echo sprintf($this->lang->line("conv_with"), $user["username"]); ?></h3>
		</div>
	</div>

	<div class="row">
		<div class="main_container clearfix">
		    <div class="ibox-content-no-bg">
			    <div class="chat-discussion">
				    <?php
					$last_conv = $last_conv->result_array();
					$last_conv = array_reverse($last_conv);
					
					foreach($last_conv as $message):
					$activity_thumb = $message["thumb_url"];
					
					if($message["thumb_url"] == "" || $message["photostatus"] == 0) {
						$activity_thumb = base_url() . "images/avatar.png";
					}
					?>
					
					<?php
					if($message["user_id"] == $this->session->userdata("user_id"))
						$align_message = "left";
					else
						$align_message = "right";
					?>
					
					<div class="chat-message clearfix <?php echo $align_message; ?>" data-id="<?php echo $message["id"]; ?>">
						<?php
						if($message["gender"] == 0) {
							$gender_user_color = "male_color";
						} else {
							$gender_user_color = "female_color";
						}
						?>
						<a class="nailthumb-msg-container" href="<?php echo base_url("user/profile/".$message["user_id"]) ?>"><img width="62" alt="" src="<?php echo $activity_thumb; ?>" class="message-avatar"></a>
						 <div class="message">
							<a class="message-author <?php echo $gender_user_color; ?>" href="<?php echo base_url("user/profile/".$message["user_id"]) ?>"><?php echo $message["username"] ?></a>
							<span class="message-date text-muted pm-date" title="<?php echo $message["date"]; ?>Z"></span>
							
							<span class="message-content">
								<?php echo nl2br(parse_text_smileys($message["content"])); ?>
							</span>
							<span class="message-date-mob text-muted pm-date" title="<?php echo $message["date"]; ?>Z"></span>
		
						</div>		
					</div>
					<?php
					endforeach;
					?>
			    </div>
		    
				<div class="chat-message-form">
					<div class="form-group">
						<textarea class="form-control message-input pm-write-answer-textarea" placeholder="<?php echo $this->lang->line("enter_message_here_placeholder"); ?>" name="message"></textarea>
					</div>
					<div class="btn-reply-placeholder">
						<a class="btn btn-primary btn-send-reply" href="#" data-user-id="<?php echo $user["uid"]; ?>" data-conv-id="<?php echo $current_conv->id; ?>"><?php echo $this->lang->line("send_reply_btn"); ?></a>
					</div>
				</div>
		    </div>
		</div>
	</div>
</div>
<script type="text/javascript">
	var conv_id = "<?php echo $current_conv->id; ?>";
	var user_id = "<?php echo $this->session->userdata('user_id'); ?>";
</script>
<?php
$this->load->view('templates/footers/main_footer');
?>
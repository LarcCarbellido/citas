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
	<div class="row">
		<?php
		if(isset($_GET["action"])) {
			if($_GET["action"] == "reply_added") {
			?>
			<div class="alert alert-success alert-centered" style="margin-top: 10px;">
				<?php echo $this->lang->line("reply_added") ?>
			</div>
			<?php
			} else if($_GET["action"] == "topic_edited") {
			?>
			<div class="alert alert-success alert-centered" style="margin-top: 10px;">
				<?php echo $this->lang->line("topic_edited") ?>
			</div>
			<?php	
			} else if($_GET["action"] == "answer_edited") {
			?>
			<div class="alert alert-success alert-centered" style="margin-top: 10px;">
				<?php echo $this->lang->line("answer_edited") ?>
			</div>
			<?php	
			} else if($_GET["action"] == "demo") {
			?>
			<div class="alert alert-danger alert-centered" style="margin-top: 10px;">
				<?php echo $this->lang->line("demo_mode_post") ?>
			</div>
			<?php	
			} else if($_GET["action"] == "delete_answer_ok") {
			?>
			<div class="alert alert-success alert-centered" style="margin-top: 10px;">
				<?php echo $this->lang->line("ok_deleted") ?>
			</div>
			<?php	
			}
		}	
		?>
		<div class="main_container">  
		    <div class="ibox-content-no-bg">
			    <div class="chat-discussion chat-forum">
					<?php
						if(sizeof($answers->result_array()) >= 25 || $page > 0):
					?>
					<div class="forum_pagination">
						<?php echo $pagination; ?>
					</div>
					<?php
						endif;
					?>	
					<?php
					if($page == 0):
					?>
					<div class="chat-message right">
						<?php
						if(($topic->thumb_url == "" || $topic->photostatus == 0) && $topic->gender == 0) {
							$avatar = base_url() . "images/avatar/avatarBoy1.png";
						} else if(($topic->thumb_url == "" || $topic->photostatus == 0) && $topic->gender == 1) {
							$avatar = base_url() . "images/avatar/avatarGirl1.png";
						} else if($topic->thumb_url != "") {
							$avatar = $topic->thumb_url;
						}
						?>
						<?php
						if($topic->gender == 0) {
							$gender_user_color = "male_color";
						} else {
							$gender_user_color = "female_color";
						}
						?>
						<a class="nailthumb-msg-container" href="<?php echo base_url("user/profile/".$topic->uid) ?>"><img width="200" alt="" src="<?php echo $avatar; ?>" class="message-avatar"></a>
						<div class="topic-author">
							<?php echo sprintf($this->lang->line("by_topic"), $gender_user_color, base_url("user/profile/".$topic->uid), ucfirst($topic->username), base_url() . " forum/category/" . $topic->cat_id, $topic->cat_name); ?>
						</div>
		
						<div class="message">
							<span class="message-date text-muted pm-date" title="<?php echo $topic->date; ?>Z"></span>
							<span class="message-content">
							<?php echo nl2br($this->security->xss_clean($topic->content)) ?>
							</span>
							<span class="message-date-mob text-muted pm-date" title="<?php echo $topic->date; ?>Z"></span>
						</div>
						<?php
			            if($this->session->userdata("user_rank") > 0 || $topic->uid == $this->session->userdata('user_id')):
			            ?>
						<div class="pull-right topic-actions">
							<a href="<?php echo base_url(); ?>forum/edittopic/<?php echo $topic->id ?>"><i class="fa fa-pencil"></i> <?php echo $this->lang->line("Edit") ?></a>
							|
							<a class="delete_topic" href="<?php echo base_url(); ?>forum/deletetopic/<?php echo $topic->id ?>"><i class="fa fa-times"></i> <?php echo $this->lang->line("Delete") ?></a>
						</div>
						<?php
						endif;
						?>
					</div>
					<!-- media -->
					<?php
					endif; 
					?>
					<?php
					foreach($answers->result_array() as $answer):
					?>
					<div class="chat-message right">
						<?php
						if(($answer["thumb_url"] == "" || $answer["photostatus"] == 0) && $answer["gender"] == 0) {
							$avatar = base_url() . "images/avatar/avatarBoy1.png";
						} else if(($answer["thumb_url"] == "" || $answer["photostatus"] == 0) && $answer["gender"] == 1) {
							$avatar = base_url() . "images/avatar/avatarGirl1.png";
						} else if($answer["thumb_url"] != "") {
							$avatar = $answer["thumb_url"];
						}
						?>
						<?php
						if($answer["gender"] == 0) {
							$gender_user_color = "male_color";
						} else {
							$gender_user_color = "female_color";
						}
						?>
						<a class="nailthumb-msg-container" href="<?php echo base_url("user/profile/".$answer["uid"]) ?>"><img width="200" alt="" src="<?php echo $avatar; ?>" class="message-avatar"></a>
						<div class="topic-author">
							<?php echo sprintf($this->lang->line("by_answer"), $gender_user_color, base_url("user/profile/".$answer["uid"]), ucfirst($answer["username"])); ?>
						</div>
						
						<div class="message">
							<span class="message-date text-muted pm-date" title="<?php echo $answer["date"]; ?>Z"></span>
							<span class="message-content">
							<?php echo nl2br($this->security->xss_clean($answer["content"])); ?>
							</span>
							<span class="message-date-mob text-muted pm-date" title="<?php echo $answer["date"]; ?>Z"></span>
						</div>
						<?php
			            if($this->session->userdata("user_rank") > 0 || $answer["uid"] == $this->session->userdata('user_id')):
			            ?>
						<div class="pull-right topic-actions">
							<a href="<?php echo base_url(); ?>forum/editanswer/<?php echo $answer["id"]; ?>"><i class="fa fa-pencil"></i> <?php echo $this->lang->line("Edit") ?></a>
							|
							<a class="delete_answer" href="<?php echo base_url(); ?>forum/deleteanswer/<?php echo $answer["id"]; ?>"><i class="fa fa-times"></i> <?php echo $this->lang->line("Delete") ?></a>
						</div>
						<?php
						endif;
						?>
					</div>
					<?php
					endforeach;
					?>
			    </div>
				<br/>
				<?php
					if(sizeof($answers->result_array()) >= 25 || $page != 0):
				?>
				<div class="btnmoreplaceholder message">
					<?php echo $pagination; ?>
				</div>
				<?php
					endif;
				?>	
				<div class="media" id="replybox">
					<div class="media-body answer-block-forum">	
						<?php echo form_open($this->uri->uri_string()); ?>
						<label class="control-label" for="inputDesc"><?php echo sprintf($this->lang->line("reply_from"), base_url() . "user/profile2/" . $this->session->userdata('user_id'), $this->session->userdata('user_username')); ?> :</label>
						<textarea name="content" required="required" id="inputDesc" class="form-control forum-content-reply-txt" rows="4" placeholder="<?php echo $this->lang->line("send_answer_placeholder"); ?>"></textarea>
						<div class="pull-right">
							<input type="submit" class="btn btn-primary btn-large btn-submit-forum-answer" value="<?php echo $this->lang->line("send_answer"); ?>" />
						</div>
						<?php echo form_close(); ?>
					</div>
				</div>
				<!-- media -->
			</div>
		</div>
	</div>
</div>
<!-- panel-body -->
<?php
$this->load->view('templates/footers/main_footer');
?>
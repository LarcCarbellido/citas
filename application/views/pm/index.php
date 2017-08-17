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
			<h3><?php echo $this->lang->line("messages_title"); ?></h3>
		</div>
	</div>
	<div class="row">
		<div class="main_container">
		    <div class="ibox-content-no-bg">
		    	<div class="pm_conversations">
				<?php
				$cpt = 0;
				
				foreach($conversations as $conv) {
					if($conv["from_gender"] == 0) {
						$gender_color = "male_color";
					} else {
						$gender_color = "female_color";
					}
				?>
					<?php
					if($conv["read_status"] == 0 && $conv["last_answer_user_id"] != $this->session->userdata('user_id')):
					?>
					<div class="pm-conv-block unread" data-conv-id="<?php echo $conv["from_userid"]; ?>">
						<div class="dot_unread">
							<i class="fa fa-circle"></i>
						</div>
					<?php
					else:
					?>
					<div class="pm-conv-block message" data-conv-id="<?php echo $conv["from_userid"]; ?>">
					<?php 
					endif;
					?>
						<a class="<?php echo $gender_color; ?> message_author" href="<?php echo base_url("user/profile/" . $conv["from_userid"]) ?>"><?php echo ucfirst($conv["from_username"]) ?></a> 
						<?php
						$last_answer_date = $conv["last_answer_date"];		
						?>
						<div class="pm-date-left" title="<?php echo $last_answer_date; ?>Z"></div>
					</div>
				<?php
					$cpt++;
				} 
				?>
				</div> 
		    </div>
		</div>
	</div>
</div>
<?php
$this->load->view('templates/footers/main_footer');
?>
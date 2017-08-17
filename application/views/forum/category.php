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
			<h3><?php echo $this->lang->line("Forum") ?></h3>
		</div>
	    <div class="pull-right">
		    <div class="btn_filter_placeholder">
		    	<a href="<?php echo base_url(); ?>forum/create" class="btn btn-primary btn-forum-new-topic">
					<i class="fa fa-plus"></i> <?php echo $this->lang->line("new_topic_btn") ?>
				</a>
		    </div>
	    </div>
    </div>
	<div class="row">
		<div class="main_container">
			<?php
			if(sizeof($topics->result_array()) == 0)
			{
				echo "<div class='alert alert-danger alert-center'>". $this->lang->line("no_topics_category") . "</div>";	
			}
			?>
			<?php
			foreach($topics->result_array() as $topic) {
			?>
				<div class="forum-item active">
	                <div class="row">
	                    <div class="col-md-10">
		                    <div class="forum-icon">
								<i class="fa fa-comment"></i>
							</div>
							<a class="forum-item-title" href="<?php echo base_url("forum/topic/" . $topic["tid"]) ?>"><?php echo $topic["title"] ?></a>
							<div class="forum-sub-title">By <a href="<?php echo base_url("user/profile/" . $topic["uid"]) ?>"><?php echo $topic["username"] ?></a>, <span class="lstopic_ago" title="<?php echo $topic["date"] ?>Z"></span></div>
						</div>
	                    <div class="col-md-2 forum-info">
	                        <span class="views-number"><?php echo $topic["nb_answers"]; ?></span>
							<div>
	                            <small><?php echo $this->lang->line("Replies"); ?></small>
	                        </div>
						</div>
	                </div>
				</div>
			<?php
			}
			?>
			<?php
			if(sizeof($topics->result_array()) >= 20 || $page != 0):
			?>
			<tr>
				<td class="forum-title-topic-th"><div class="btnmoreplaceholder message"><?php echo $pagination; ?></div></td>
			</tr>
			<?php
			endif;
			?>
		</div>
	</div>
</div>

<?php
$this->load->view('templates/footers/main_footer');
?>
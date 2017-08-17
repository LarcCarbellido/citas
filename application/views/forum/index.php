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
			if($settings["ads_code"] != ""):
			?>
			<div class="ad_block">
				<?php echo $settings["ads_code"]; ?>
			</div>
			<?php
			endif;
			?>
			<?php
			if(isset($_GET["action"])) {
				if($_GET["action"] == "delete_topic_ok") {
				?>
				<div class="alert alert-success alert-centered" style="margin-top: 10px;">
					<?php echo $this->lang->line("topic_deleted") ?>
				</div>
				<?php
				} else if($_GET["action"] == "demo") {
				?>
				<div class="alert alert-danger alert-centered" style="margin-top: 10px;">
					<?php echo $this->lang->line("cant_demo") ?>
				</div>
				<?php
				}
			}
			?>
			
			<?php
			if(sizeof($categories->result_array()) == 0) {
			?>
				<div class="alert alert-info alert-center">
					<?php echo $this->lang->line("no_categories"); ?>
				</div>
			<?php
			}
			foreach($categories->result_array() as $category) {
			?>
				<div class="forum-item active">
	                <div class="row">
	                    <div class="col-md-10">
		                    <div class="forum-icon">
								<i class="fa fa-comments"></i>
							</div>
	                        <a href="<?php echo base_url("forum/category/" . $category["id"]) ?>" class="forum-item-title"><?php echo $category["name"] ?></a>
	                        <div class="forum-sub-title"><?php echo $category["desc"] ?></div>
	                    </div>
	                    <div class="col-md-2 forum-info">
	                        <span class="views-number">
	                            <?php echo $category["nb_topics"]; ?>
	                        </span>
	                        <div>
	                            <small><?php echo $this->lang->line("Topics"); ?></small>
	                        </div>
	                    </div>
	                </div>
	            </div>
			<?php	
			}
			?>
		</div>
	</div>
</div>
<?php
$this->load->view('templates/footers/main_footer');
?>
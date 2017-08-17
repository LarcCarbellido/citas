<?php
$this->load->view('templates/headers/admin_header', $title);
?>
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-4">
        <h2>Admin</h2>
        <ol class="breadcrumb">
            <li>
                <a href="<?php echo base_url(); ?>/admin">Admin</a>
            </li>
            <li class="active">
                <strong>Manage Forum</strong>
            </li>
        </ol>
    </div>
    <div class="col-lg-8">
	    <div class="btn_filter_placeholder">
			<a class="btn btn-primary" href="<?php echo base_url() ?>admin/add_forum_cat"><i class="fa fa-plus"></i> Create a Forum Category</a>
			<!--<a class="btn btn-danger" href="<?php echo base_url() ?>admin/manage_reported_forum"><i class="fa fa-bullhorn"></i> Reported Messages</a>-->
	    </div>
    </div>
</div>
<div class="col-lg-12 block_form">
    <div class="ibox-content-no-bg">
	   <?php
	   if(isset($_GET["action"])) {
		   if($_GET["action"] == "cat_created") {
		   ?>
		   <div class="alert alert-success alert-centered">
			   <b>Cool!</b> Your new forum category has been created.
		   </div>
		   <?php
		   } else if($_GET["action"] == "cat_deleted") {
		   ?>
		   <div class="alert alert-success alert-centered">
			   <b>Done!</b> Your forum category has been deleted.
		   </div>
		   <?php
		   } else if($_GET["action"] == "cat_edited") {
		   ?>
		   <div class="alert alert-success alert-centered">
			   <b>Done!</b> Your forum category has been edited.
		   </div>
		   <?php
		   } else if($_GET["action"] == "demo") {
		   ?>
		   <div class="alert alert-danger alert-centered">
			   <b>Nope!</b> You can't do that in demo mode.
		   </div>
		   <?php
		   }
	   }
	   ?>
	   <div class="well">
			<div class="checkbox enable_payments_placeholder">
				<label>
					<input class="form-control enable_forum" <?php if($settings["enable_forum"] == 1) { echo "checked"; } ?> type="checkbox" id="enable_forum" name="enable_forum" /> Enable Forum
				</label>
				<p class="help-block"><i class="fa fa-info-circle"></i> If unchecked, forum will be disabled.</p>
			</div>
			<div class="loading_placeholder_forum">
				<hr />
				<i class="fa fa-circle-o-notch fa-spin"></i>
			</div>
	   </div>
	   <table class="table table-bordered table-striped table-hover">
			<tr>
				<th class="forum-title-topic-th">Forum Categories</th>
			</tr>
			<?php
			if(sizeof($categories->result_array()) == 0) {
			?>
			<tr class="topic">
				<td colspan="1">
					<div class="alert alert-info alert-centered">
						No categories to display for the moment.<br /><br />
						<a class="btn btn-primary" href="<?php echo base_url() ?>admin/add_forum_cat"><i class="fa fa-plus"></i> Create the First Forum Category</a>
					</div>
				</td>
			</tr>
			<?php
			}
			foreach($categories->result_array() as $category) {
			?>
				<tr class="topic">
					<td>
						<div class="lstopic_title">
							<a href="<?php echo base_url("forum/category/" . $category["id"]) ?>"><b><?php echo $category["name"] ?></b></a> 
							<div class="pull-right">
								<a class="badge badge-info" href="<?php echo base_url(); ?>admin/edit_forum_cat/<?php echo $category["id"]; ?>"><i class="fa fa-pencil"></i> Edit</a>
								<a class="badge badge-danger badge-delete" data-id="<?php echo $category["id"]; ?>" href=""><i class="fa fa-times"></i> Delete</a>
							</div>
						</div>
						<div class="fcat_desc">
							<?php echo $category["desc"] ?>
						</div>
					</td>
				</tr>
			<?php	
			}
			?>
		</table>
    </div>
</div>
<?php
$this->load->view('templates/footers/admin_footer');
?>
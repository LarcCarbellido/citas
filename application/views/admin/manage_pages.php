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
                <strong>Manage Custom Pages</strong>
            </li>
        </ol>
    </div>
    <div class="col-lg-8">
	    <div class="btn_filter_placeholder">
			<a class="btn btn-primary" href="<?php echo base_url() ?>admin/add_page"><i class="fa fa-plus"></i> Create a Custom Page</a>
	    </div>
    </div>
</div>
<div class="col-lg-12 block_form">
    <div class="ibox-content-no-bg">
	   <?php
	   if(isset($_GET["action"])) {
		   if($_GET["action"] == "page_created") {
		   ?>
		   <div class="alert alert-success alert-centered">
			   <b>Cool!</b> Your new custom page has been added.
		   </div>
		   <?php
		   } else if($_GET["action"] == "page_deleted") {
		   ?>
		   <div class="alert alert-success alert-centered">
			   <b>Done!</b> Your custom page has been deleted.
		   </div>
		   <?php
		   } else if($_GET["action"] == "page_edited") {
		   ?>
		   <div class="alert alert-success alert-centered">
			   <b>Done!</b> Your custom page has been updated.
		   </div>
		   <?php
		   } else if($_GET["action"] == "demo") {
		   ?>
		   <div class="alert alert-danger alert-centered">
			   <b>Nope!</b> Your can't do that in demo mode.
		   </div>
		   <?php
		   }
	   }
	   ?>
	   <table class="table table-bordered table-striped table-hover">
			<tr>
				<th class="forum-title-topic-th">Custom Pages</th>
			</tr>
			<?php
			if(sizeof($pages->result_array()) == 0) {
			?>
			<tr class="topic">
				<td colspan="1">
					<div class="alert alert-info alert-centered">
						No pages to display for the moment.<br /><br />
						<a class="btn btn-primary" href="<?php echo base_url() ?>admin/add_page"><i class="fa fa-plus"></i> Create the First Custom Page</a>
					</div>
				</td>
			</tr>
			<?php
			}
			foreach($pages->result_array() as $page) {
			?>
				<tr class="topic">
					<td>
						<div class="lstopic_title">
							<i class="fa fa-<?php echo $page["icon"]; ?>"></i>
							<a href="<?php echo base_url("page/" . $page["id"]) ?>"><b><?php echo $page["title"] ?></b></a> 
							<div class="pull-right">
								<a class="badge badge-info" href="<?php echo base_url(); ?>admin/edit_page/<?php echo $page["id"]; ?>"><i class="fa fa-pencil"></i> Edit</a>
								<a class="badge badge-danger badge-delete" data-id="<?php echo $page["id"]; ?>" href=""><i class="fa fa-times"></i> Delete</a>
							</div>
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
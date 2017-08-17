<?php
$this->load->view('templates/headers/admin_header', $title);
?>
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>Admin</h2>
        <ol class="breadcrumb">
            <li>
                <a href="<?php echo base_url(); ?>/admin">Admin</a>
            </li>
            <li class="active">
                <strong>User Generator</strong>
            </li>
        </ol>
    </div>
</div>
<div class="col-lg-12 block_form">
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
			} else if($_GET["action"] == "users_created") {
			?>
			<div class="alert alert-success alert-centered">
			<b>Awesome!</b> Some users have been generated.
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

	<?php echo validation_errors("<div class='alert alert-danger'>", "</div>"); ?>
	<form action="<?php echo base_url(); ?>admin/user_generator" method="POST" accept-charset="utf-8" class="general_config well">
		<fieldset>
	        <legend><i class="fa fa-users"></i> Generate Fake Users</legend>
	        <div class="alert alert-warning">
		    	For security & copyright reason, photo URLs you enter will not be uploaded to your server. Please be sure that the URL you submit for the photos of your fake users are on a server which will not remove them in the future... Otherwise, some users could lose their profile picture.
	        </div>
	        
	        <div class="form-group">
	            <label for="users_url">Paste the profile photo URLs :</label>
	            <textarea rows="6" class="form-control users_url" id="users_url" name="users_url" placeholder="Paste your photos URLs"></textarea>
				<p class="help-block"><i class="fa fa-info-circle"></i> One line = one url = one user created.</p>
	        </div>
	        <div class="form-group">
	            <label for="gender_users">Generated Users Gender :</label>
	            <select class="form-control gender_users" id="gender_users" name="gender_users">
		            <option value="0">Male</option>
		            <option value="1">Female</option>
	            </select>
	        </div>
		</fieldset>
		<div style="text-align:center;">
            <button type="submit" class="btn btn-primary btn-save"><i class="fa fa-check"></i> Create Users</button>
        </div>
	</form>
</div>
<?php
$this->load->view('templates/footers/admin_footer');
?>
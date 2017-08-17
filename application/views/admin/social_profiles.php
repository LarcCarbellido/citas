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
                <strong>Social Profiles</strong>
            </li>
        </ol>
    </div>
</div>
<div class="col-lg-12 block_form">
	<?php
	if(isset($_GET["status"])) {
	?>
	<div class="alert alert-success alert-centered">
		<b>Success!</b> Your social profiles have been updated!
	</div>
	<?php
	} else {
	?>
	<div class="alert alert-info alert-centered">
		Leave blank if you don't want a particular social icon to be displayed.
	</div>
	<?php
	}
	?>
	<form action="" method="post" accept-charset="utf-8" class="general_config well">
		<div class="alert alert-danger alert-centered alert-error-settings">
			
		</div>
	    <fieldset>
	        <legend><i class="fa fa-facebook-square"></i> Social Profiles</legend>
	        <div class="form-group">
	            <label for="fb_url">Facebook :</label>
	            <input type="text" class="form-control fb_url" id="fb_url" name="fb_url" value="<?php echo $settings["fb_url"]; ?>" placeholder="Your Facebook Page / Profile URL">
	        </div>
	        <div class="form-group">
	            <label for="twitter_url">Twitter :</label>
	            <input type="text" class="form-control twitter_url" id="twitter_url" name="twitter_url" value="<?php echo $settings["twitter_url"]; ?>" placeholder="Your Twitter Profile URL">
	        </div>
	        <div class="form-group">
	            <label for="instagram_url">Instagram :</label>
	            <input type="text" class="form-control instagram_url" id="instagram_url" name="instagram_url" value="<?php echo $settings["instagram_url"]; ?>" placeholder="Your Instagram Profile URL">
	        </div>
	        <div class="form-group">
	            <label for="gplus_url">Google+ :</label>
	            <input type="text" class="form-control gplus_url" id="gplus_url" name="gplus_url" value="<?php echo $settings["googleplus_url"]; ?>" placeholder="Your Google+ Page URL">
	        </div>
	        <hr />
	        <div style="text-align:center;">
	            <button type="submit" class="btn btn-primary btn-save"><i class="fa fa-check"></i> Save Changes</button>
	        </div>
	    </fieldset>
	</form>
</div>
<?php
$this->load->view('templates/footers/admin_footer');
?>
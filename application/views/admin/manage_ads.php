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
                <strong>Manage Ads</strong>
            </li>
        </ol>
    </div>
</div>
<div class="col-lg-12 block_form">
	<?php
	if(isset($_GET["status"])) {
	?>
	<div class="alert alert-success alert-centered">
		<b>Success!</b> Your settings have been updated!
	</div>
	<?php
	} else {
	?>
	<div class="alert alert-info alert-centered">
		If you let the "ads code" field blank, the ads spaces will be hidden.
	</div>
	<?php
	}
	?>
	<form action="" method="post" accept-charset="utf-8" class="general_config well">
		<div class="alert alert-danger alert-centered alert-error-settings">
			
		</div>
		<div class="form-group">
            <label for="ads_code">Ads Code :</label>
            <textarea class="form-control ads_code" id="ads_code" name="ads_code" placeholder="Your Javascript Ads Code Goes Here"><?php echo $settings["ads_code"]; ?></textarea>
			<p class="help-block"><i class="fa fa-info-circle"></i> Paste your (Google or other system) ads code here. Choose, if possible, the responsive ad block (available in AdSense for example), or an horizontal one.</p>
        </div>
        <div style="text-align:center;">
			<button type="submit" class="btn btn-primary btn-save"><i class="fa fa-check"></i> Save Changes</button>
	    </div>
	</form>
</div>
<?php
$this->load->view('templates/footers/admin_footer');
?>
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
                <strong>Settings</strong>
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
		You can change the general settings of your website in this page.
	</div>
	<?php
	}
	?>
	<form action="" method="post" accept-charset="utf-8" class="general_config well">
		<div class="alert alert-danger alert-centered alert-error-settings">
			
		</div>
	    <fieldset>
	        <legend><i class="fa fa-level-up"></i> General Settings</legend>
	        <div class="form-group">
	            <label for="web_title">Website Name :</label>
	            <input type="text" class="form-control web_title" id="web_title" name="web_title" value="<?php echo $settings["site_name"]; ?>" placeholder="Your Website Name">
	        </div>
	        <div class="form-group">
	            <label for="web_tagline">Website Tagline :</label>
	            <input type="text" class="form-control web_tagline" id="web_tagline" name="web_complete_title" value="<?php echo $settings["site_tagline"]; ?>" placeholder="The title of your website which will appear in the search engines.">
	        </div>
	        <div class="form-group">
	            <label for="web_desc">Website Description :</label>
	            <input type="text" class="form-control web_desc" id="web_desc" name="web_desc" value="<?php echo $settings["site_description"]; ?>" placeholder="Your Website Description">
	        </div>
	        <div class="form-group">
	            <label for="web_desc">Website Keywords :</label>
	            <input type="text" class="form-control web_keywords" id="web_keywords" name="web_keywords" value="<?php echo $settings["site_tags"]; ?>" placeholder="Ex. date, dating, meet, ...">
	        </div>
			<div class="form-group">
	            <label for="web_desc">Analytics Code :</label>
	            <textarea class="form-control site_analytics" id="site_analytics" name="site_analytics" placeholder="Your Javascript Analytics Code Goes Here"><?php echo $settings["site_analytics"]; ?></textarea>
				<p class="help-block"><i class="fa fa-info-circle"></i> Paste your (Google or other system) analytics code.</p>
	        </div>
			<div class="checkbox">
	            <label>
	                <input <?php if($settings["hide_country"] == 1): ?>checked<?php endif; ?> type="checkbox" name="hide_country" class="hide_country"> Hide country everywhere
	                <p class="help-block"><i class="fa fa-info-circle"></i> Useful if you want your website to be opened to one country only.</p>
	            </label>
	        </div>
	    </fieldset>
		<hr />
	    <fieldset>
	        <legend><i class="fa fa-users"></i> Users Settings</legend>
	        <div class="checkbox">
	            <label>
	                <input <?php if($settings["web_captcha"] == 1): ?>checked<?php endif; ?> type="checkbox" name="web_captcha" class="web_captcha"> Activate Captcha
	                <p class="help-block"><i class="fa fa-info-circle"></i> If checked, the users will have to answer a simple question to register.</p>
	            </label>
	        </div>
	        <div class="checkbox">
	            <label>
	                <input <?php if($settings["hide_timeline"] == 0): ?>checked<?php endif; ?> type="checkbox" name="hide_timeline" class="hide_timeline"> Activate User Timeline
					<p class="help-block"><i class="fa fa-info-circle"></i> If checked, a timeline with the last actions of the user will be shown on profiles.</p>
	            </label>
	        </div>
	        <div class="form-group">
	            <label for="web_tagline">Online delay :</label>
	            <input type="text" class="form-control online_delay" id="online_delay" name="online_delay" value="<?php echo $settings["online_delay"]; ?>" placeholder="Minutes before the user appear offline">
				<p class="help-block"><i class="fa fa-info-circle"></i> Delay before a user appear offline after no activity (in minutes)</p>
	        </div>
	        <div class="form-group">
	            <label for="age_limit">Minimum Age to Register :</label>
				<select name="age_limit" id="age_limit" class="age_limit form-control">
					<?php
					$cpt = 0;
					while($cpt <= 100) {
					?>
					<option <?php if($settings["site_age_limit"] == $cpt): ?>selected<?php endif; ?> value="<?php echo $cpt; ?>"><?php echo $cpt; ?></option>
					<?php	
					$cpt++;
					}
					?>
				</select>
				<p class="help-block"><i class="fa fa-info-circle"></i> Select 0 to not set an age limit</p>
	        </div>
	        <div class="form-group">
	            <label for="upload_limit">User photo upload limit :</label>
				<select name="upload_limit" id="upload_limit" class="upload_limit form-control">
					<?php
					$cpt = 0;
					while($cpt <= 100) {
					?>
					<option <?php if($settings["upload_limit"] == $cpt): ?>selected<?php endif; ?> value="<?php echo $cpt; ?>"><?php echo $cpt; ?></option>
					<?php	
					$cpt++;
					}
					?>
				</select>
				<p class="help-block"><i class="fa fa-info-circle"></i> Select 0 to not set an upload limit</p>
	        </div>
	        <div class="form-group">
	            <label for="extra_fields">Extra User Fields :</label>
	            <input type="text" class="form-control extra_fields" id="extra_fields" name="extra_fields" value="<?php echo $settings["user_extra_fields"]; ?>" placeholder='Extra fields separated by a ","'>
				<p class="help-block"><i class="fa fa-info-circle"></i> Extra fields users can fill on their profile (ex. Hobbies, Sports...)</p>
	        </div>
	    </fieldset>
		<hr />
    
        <div style="text-align:center;">
            <button type="submit" class="btn btn-primary btn-save"><i class="fa fa-check"></i> Save Changes</button>
        </div>
	</form>
</div>
<?php
$this->load->view('templates/footers/admin_footer');
?>
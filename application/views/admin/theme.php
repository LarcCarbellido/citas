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
                <strong>Theme Options</strong>
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
		You can change the general style of your website here. Please enter some hex values (ex. #000000).
	</div>
	<?php
	}
	?>
	<form action="" method="post" accept-charset="utf-8" class="general_config well">
		<div class="alert alert-danger alert-centered alert-error-settings">
			
		</div>
	    <fieldset>
	        <legend><i class="fa fa-paint-brush"></i> Welcome Page</legend>
			<div class="form-group">
				<label for="f_bg_color">First Part Background Color :</label>
				<input type="text" class="form-control f_bg_color" id="f_bg_color" name="f_bg_color" value="<?php echo $settings["f_bg_color"]; ?>" placeholder="Ex. #FFFFFF">
				<p class="help-block">
					<i class="fa fa-info-circle"></i>
					<a target="_blank" href="http://crea.io/images/okdate/fpart.png">Click here</a> to see where it is.
				</p>
			</div>
			<div class="form-group">
				<label for="f_txt_color">First Part Text Color :</label>
				<input type="text" class="form-control f_txt_color" id="f_txt_color" name="f_txt_color" value="<?php echo $settings["f_txt_color"]; ?>" placeholder="Ex. #FFFFFF">
				<p class="help-block">
					<i class="fa fa-info-circle"></i>
					<a target="_blank" href="http://crea.io/images/okdate/fpart.png">Click here</a> to see where it is.
				</p>
			</div>
			<div class="form-group">
	            <label for="s_bg_color">Second Part Background Color :</label>
	            <input type="text" class="form-control s_bg_color" id="s_bg_color" name="s_bg_color" value="<?php echo $settings["s_bg_color"]; ?>" placeholder="Ex. #FFFFFF">
				<p class="help-block">
					<i class="fa fa-info-circle"></i>
					<a target="_blank" href="http://crea.io/images/okdate/spart.png">Click here</a> to see where it is.
				</p>
	        </div>
	        <div class="form-group">
				<label for="s_txt_color">Second Part Text Color :</label>
				<input type="text" class="form-control s_txt_color" id="s_txt_color" name="s_txt_color" value="<?php echo $settings["s_txt_color"]; ?>" placeholder="Ex. #FFFFFF">
				<p class="help-block">
					<i class="fa fa-info-circle"></i>
					<a target="_blank" href="http://crea.io/images/okdate/spart.png">Click here</a> to see where it is.
				</p>
			</div>
			<div class="form-group">
				<label for="s_txt_color">Second Part Third Color :</label>
				<input type="text" class="form-control s_third_color" id="s_third_color" name="s_third_color" value="<?php echo $settings["s_third_color"]; ?>" placeholder="Ex. #FFFFFF">
				<p class="help-block">
					<i class="fa fa-info-circle"></i>
					<a target="_blank" href="http://crea.io/images/okdate/spart.png">Click here</a> to see where it is.
				</p>
			</div>
	    </fieldset>
	    <hr />
	    <fieldset>
	        <legend><i class="fa fa-paint-brush"></i> Main Website Pages</legend>
			<div class="form-group">
				<label for="bgcolor_main">Main Background Color :</label>
				<input type="text" class="form-control bgcolor_main" id="bgcolor_main" name="bgcolor_main" value="<?php echo $settings["bgcolor_main"]; ?>" placeholder="Ex. #FFFFFF">
				<p class="help-block">
					<i class="fa fa-info-circle"></i>
					<a target="_blank" href="http://crea.io/images/okdate/gpart.png">Click here</a> to see where it is.
				</p>
			</div>
			<div class="form-group">
				<label for="textcolor_navbar">Navbar Text Color :</label>
				<input type="text" class="form-control textcolor_navbar" id="textcolor_navbar" name="textcolor_navbar" value="<?php echo $settings["textcolor_navbar"]; ?>" placeholder="Ex. #FFFFFF">
				<p class="help-block">
					<i class="fa fa-info-circle"></i>
					<a target="_blank" href="http://crea.io/images/okdate/gpart.png">Click here</a> to see where it is.
				</p>
			</div>
			<div class="form-group">
	            <label for="main_block_color">Main Block Color :</label>
	            <input type="text" class="form-control main_block_color" id="main_block_color" name="main_block_color" value="<?php echo $settings["main_block_color"]; ?>" placeholder="Ex. #FFFFFF">
				<p class="help-block">
					<i class="fa fa-info-circle"></i>
					<a target="_blank" href="http://crea.io/images/okdate/gpart.png">Click here</a> to see where it is.
				</p>
	        </div>
	        <div class="form-group">
				<label for="main_txt_color">Main Text Color :</label>
				<input type="text" class="form-control main_txt_color" id="main_txt_color" name="main_txt_color" value="<?php echo $settings["main_txt_color"]; ?>" placeholder="Ex. #FFFFFF">
				<p class="help-block">
					<i class="fa fa-info-circle"></i>
					<a target="_blank" href="http://crea.io/images/okdate/gpart.png">Click here</a> to see where it is.
				</p>
			</div>
			<div class="form-group">
				<label for="logo_color">Logo Color :</label>
				<input type="text" class="form-control logo_color" id="logo_color" name="logo_color" value="<?php echo $settings["logo_color"]; ?>" placeholder="Ex. #FFFFFF">
				<p class="help-block">
					<i class="fa fa-info-circle"></i>
					<a target="_blank" href="http://crea.io/images/okdate/gpart.png">Click here</a> to see where it is.
				</p>
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
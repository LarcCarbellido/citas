<?php
$this->load->view('templates/headers/admin_header', $title);
?>

<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-8">
        <h2>Admin</h2>
        <ol class="breadcrumb">
            <li>
                <a href="<?php echo base_url(); ?>admin">Admin</a>
            </li>
             <li>
                <a href="<?php echo base_url(); ?>admin/pages">Manage Custom Pages</a>
            </li>
            <li class="active">
                <strong>Create a Custom Page</strong>
            </li>
        </ol>
    </div>
</div>
<div class="col-lg-12 block_form">
    <div class="ibox-content-no-bg">
	    
	    <div class="alert alert-info alert-centered">
		    Please provide the informations of your custom page.
	    </div>

		<?php echo validation_errors("<div class='alert alert-danger'>", "</div>"); ?>
    	<?php echo form_open($this->uri->uri_string()); ?>
	    <div class="form-group">
	    	<label class="control-label" for="inputTitle">Title</label>
			<div class="controls">
				<input type="text" id="inputTitle" placeholder="Write the title of this custom page" class="form-control" name="title" value="<?php echo $page->title; ?>">
			</div>
	    </div>
	    <div class="form-group">
	    	<label class="control-label" for="inputContent">Content</label>
			<div class="controls">
				<textarea id="inputContent" name="content"><?php echo $page->content; ?></textarea>
			</div>
	    </div>
	    <div class="form-group">
	    	<label class="control-label" for="inputIcon">Icon</label>
			<div class="controls">
				<input type="text" id="inputIcon" placeholder="Paste the icon name from FontAwesome" class="form-control" name="icon" value="<?php echo $page->icon; ?>">
				<p class="help-block"><i class="fa fa-info-circle"></i> Go to <a href="http://fortawesome.github.io/Font-Awesome/icons/" target="_blank">this page</a> to find an icon and copy/paste its name (ex. "arrows").</p>
			</div>
	    </div>
	    <div class="checkbox">
			<label>
				<input type="checkbox" <?php if($page->welcome_enable == 1): ?>checked="checked"<?php endif; ?> name="show_on_welcome" value="1"> Show on welcome / login page
			</label>
			<p class="help-block"><i class="fa fa-info-circle"></i> A link to this page will be shown in the footer of the welcome page.</p>
		</div>
	    <hr />
	    <div style="text-align:center;" class="clearfix">
	    	<button type="submit" class="btn btn-primary btn-large btn-embossed"><i class="fa fa-check"></i> Edit Page</button>
	    </div>
		<?php echo form_close(); ?>
    </div>
</div>
<?php
$this->load->view('templates/footers/admin_footer');
?>
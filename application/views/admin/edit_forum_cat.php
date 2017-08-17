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
                <a href="<?php echo base_url(); ?>admin/forum">Manage Forum</a>
            </li>
            <li class="active">
                <strong>Edit Forum Category</strong>
            </li>
        </ol>
    </div>
</div>
<div class="col-lg-12 block_form">
    <div class="ibox-content-no-bg">
	    
	    <div class="alert alert-info alert-centered">
		    Please provide the informations of your new forum category.
	    </div>

		<?php echo validation_errors("<div class='alert alert-danger'>", "</div>"); ?>
    	<?php echo form_open($this->uri->uri_string()); ?>
	    <div class="form-group">
	    	<label class="control-label" for="inputTitle">Name</label>
			<div class="controls">
				<input type="text" id="inputTitle" placeholder="Write the name of the category" class="form-control" name="title" value="<?php echo $category->name; ?>">
			</div>
	    </div>
	    <div class="form-group">
	    	<label class="control-label" for="inputDesc">Description</label>
			<div class="controls">
				<textarea rows="6" id="inputDesc" class="form-control" placeholder="Write the description of this category." name="desc"><?php echo $category->desc; ?></textarea>
			</div>
	    </div>
	    <hr />
	    <div style="text-align:center;" class="clearfix">
	    	<button type="submit" class="btn btn-primary btn-large btn-embossed"><i class="fa fa-check"></i> Edit Category</button>
	    </div>
		<?php echo form_close(); ?>
    </div>
</div>
<?php
$this->load->view('templates/footers/admin_footer');
?>
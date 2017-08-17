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
			<h3><?php echo $this->lang->line("upload_photos_title"); ?></h3>
		</div>
    </div>

    <div class="row">
	    <div class="main_container clearfix">
			<div class="photo_area clearfix">
	    		<div class="photoblock col-md-12 clearfix">
	    			<div class="add_photos clearfix">
						<form id="upload" method="post" action="<?php echo base_url() ?>photo/uploadPhoto" enctype="multipart/form-data" class="clearfix">
				            <fieldset>
				            	<legend><?php echo $this->lang->line("select_photos_to_upload"); ?></legend>
				            </fieldset>
				            <div id="drop">
				             	<a class="btn btn-primary"><?php echo $this->lang->line("select_some_photos"); ?></a>
				                <input type="file" name="upl" multiple />
				            </div>
				            			
				            <ul class="wait_list">
				                <!-- The file uploads will be shown here -->
				            </ul>
				
				        </form>
				        
	    			</div>
	    		</div>
			</div>
			<hr />
			<div class="name_photos_block col-md-12">
				<fieldset>
					<legend><?php echo $this->lang->line("add_text_title_photos"); ?></legend>
					<div class="alert alert-danger no-photo-yet alert-center">
						<?php echo $this->lang->line("select_computer_photos"); ?>
					</div>
					<table class="img_list table table-bordered">
	        			<tr>
	        				<th><?php echo $this->lang->line("Preview"); ?></th>
	        				<th><?php echo $this->lang->line("Informations"); ?></th>
	        			</tr>	
	        		</table>
	        		<div class="buttons_send_photos">
	        			<a href="#" class="btn btn-primary btn-embossed btn-save-photos"><?php echo $this->lang->line("Save"); ?></a>
	        		</div>
				</fieldset>
			</div>
		</div>
    </div>
</div>
<?php
$this->load->view('templates/footers/main_footer');
?>
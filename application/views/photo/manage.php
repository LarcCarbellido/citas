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
			<h3><?php echo $title; ?></h3>
		</div>
		<div class="pull-right">
			<div class="btn_filter_placeholder">
		    	<a href="<?php echo base_url(); ?>photo/add" class="btn btn-primary btn-forum-new-topic">
					<i class="fa fa-plus"></i> <?php echo $this->lang->line("p_add_photos") ?>
				</a>
		    </div>
		</div>
    </div>
	<div class="row">
		<div class="main_container clearfix">
		    <?php
			if($settings["ads_code"] != ""):
			?>
			<div class="ad_block">
				<?php echo $settings["ads_code"]; ?>
			</div>
			<?php
			endif;
			?>
			<?php
			if(sizeof($photos) == 0) {
			?>
			<div class="alert alert-danger alert-centered">
				<?php echo $this->lang->line("p_no_photos_uploaded"); ?>
			</div>
			<?php
			} else {
			?>
			<div class="alert alert-info alert-centered">
				<?php echo $this->lang->line("p_info_manage_photos"); ?>
			</div>
			<?php
			}
			?>
			<div class="lightBoxGallery" id="galleryItems">
				<?php 
				foreach($photos as $photo) {
				?>
				<div class="galleryItem" data-id="<?php echo $photo["id"]; ?>">
		    		<a href="<?php echo base_url() . $photo["url"] ?>"><img data-id="<?php echo $photo["id"]; ?>" src="<?php echo base_url() . $photo["thumb_url"] ?>" alt="" /></a>
		    		<div class="p_info_block">
			    		<i class="fa fa-thumbs-up"></i> <?php echo $photo["votes"]; ?>
			    		<span class="p_bullet">&bullet;</span>
			    		<i class="fa fa-comments"></i> <?php echo $photo["comments"]; ?>
		    		</div>
		    		<div class="btn btn-danger btn-block btn-delete" data-id="<?php echo $photo["id"]; ?>"><i class="fa fa-times"></i> <?php echo $this->lang->line("p_photo_delete") ?></div>
		    	</div>
				<?php
				}
				?>
			</div>
		</div>
	</div>
</div>
<div id="blueimp-gallery" class="blueimp-gallery blueimp-gallery-controls">
    <div class="slides"></div>
    <h3 class="title"></h3>
    <a class="prev">‹</a>
    <a class="next">›</a>
    <a class="close">×</a>
    <a class="play-pause"></a>
    <ol class="indicator"></ol>
</div>
		
<div class="modal inmodal" id="photo_modal" tabindex="-1" role="dialog"  aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content animated fadeIn">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only"><?php echo $this->lang->line('Close'); ?></span></button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body">
            	
            </div>
        </div>
    </div>
</div>
<?php
$this->load->view('templates/footers/main_footer');
?>
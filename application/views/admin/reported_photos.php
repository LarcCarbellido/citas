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
            <li>
                <a href="<?php echo base_url(); ?>admin/reported_content">Reported Content</a>
            </li>
            <li class="active">
                <strong>Reported Photos</strong>
            </li>
        </ol>
    </div>
</div>
<div class="col-lg-12 block_form">
    <div class="ibox-content-no-bg">
		<div id="userslst" class="clearfix">
			<?php
			if(sizeof($photos) == 0) {
			?>
			<div class="alert alert-danger alert-centered">
				No reported photos for the moment.
			</div>
			<?php	
			}
			?>
			<?php
	    	$cpt = 1;
	    	?>
	    	<?php foreach($photos as $photo): ?>
	    	
			<div class="col-lg-3 col-md-4 col-xs-6 clearfix user_block" data-cpt="<?php echo $cpt; ?>">
				<div class="thumb">
	                <a class="thumbnail" href="#">
		                <img src="<?php echo base_url() . $photo["thumb_url"]; ?>" alt="Photo User" />
	               	</a>
				   	<div class="userslst_infos">
					   	<a href="<?php echo base_url(); ?>user/profile/<?php echo $photo["uid"]; ?>" class="userslst_username"><?php echo $photo["username"]; ?></a>
					   	<div class="row actionreport" style="margin-top: 6px;">
						   	<div class="col-md-12 btndelete">
							   	<a href="" class="btn btn-danger btn-delete-photo" data-report-id="<?php echo $photo["rid"]; ?>" data-id="<?php echo $photo["pid"]; ?>"><i class="fa fa-times"></i> <span>Delete Photo</span></a>
						   	</div>
						   	<div class="col-md-12 btncancel">
							   	<a href="#" class="btn btn-primary btn-cancel" data-id="<?php echo $photo["rid"]; ?>"><i class="fa fa-eye-slash"></i> <span>Remove Report</span></a>
						   	</div>
					   	</div>
					</div>
				</div>
            </div>
			<?php
			$cpt++;
			endforeach;
			?>
		</div>
		<?php
		if($links != ""):
		?>
		<div class="btnmoreplaceholder message">
			<?php echo $links; ?>
		</div>
		<?php
		endif; 
		?>
    </div>
</div>
<?php
$this->load->view('templates/footers/admin_footer');
?>
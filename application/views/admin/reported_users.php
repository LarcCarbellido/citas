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
                <strong>Reported Users</strong>
            </li>
        </ol>
    </div>
</div>
<div class="col-lg-12 block_form">
    <div class="ibox-content-no-bg">
		<div id="userslst" class="clearfix">
			<?php
			if(sizeof($users) == 0) {
			?>
			<div class="alert alert-danger alert-centered">
				No reported users for the moment.
			</div>
			<?php	
			}
			?>
			<?php
	    	$cpt = 1;
	    	?>
	    	<?php foreach($users as $user): ?>
	    	
	    	<?php
	    	$birthdate = new DateTime($user["birthday"]);
			$today     = new DateTime();
			$interval  = $today->diff($birthdate);
			$age	   = $interval->format('%y');
			
			if($user["gender"] == 0) {
				$gender_color = "male_color";
			} else {
				$gender_color = "female_color";
			}
			
			if($user["thumb_url"] == "") {
				$user["thumb_url"] = "images/avatar.png";
			}
			?>
	    	
			<div class="col-lg-3 col-md-4 col-xs-6 clearfix user_block" data-user-id="<?php echo $user["uid"]; ?>" data-cpt="<?php echo $cpt; ?>">
				<div class="thumb">
	                <a class="thumbnail" href="<?php echo base_url(); ?>user/profile/<?php echo $user["uid"]; ?>">
		                <img src="<?php echo base_url() . $user["thumb_url"]; ?>" alt="Photo User" />
		                <?php
			                if($user["is_online"] == 1):
			            ?>
		                <div class="online_status"><i class="fa fa-circle"></i></div>
		                <?php
			                endif;
			            ?>
	               	</a>
				   	<div class="userslst_infos">
					   	<a href="<?php echo base_url(); ?>user/profile/<?php echo $user["uid"]; ?>" class="userslst_username <?php echo $gender_color; ?>"><?php echo $user["username"]; ?></a>
					   	<div class="userslst_age"><?php echo $age; ?> &#8226; <?php echo get_country_name_by_code($user["country"]); ?></div>
					   	<div class="row actionreport">
						   	<div class="col-md-6 btndelete">
							   	<a href="" class="btn btn-danger btn-delete-user" data-user-id="<?php echo $user["uid"]; ?>"><i class="fa fa-times"></i> <span>Delete</span></a>
						   	</div>
						   	<div class="col-md-6 btnedit">
							   	<a href="<?php echo base_url(); ?>admin/edit_user/<?php echo $user["uid"]; ?>" class="btn btn-info btn-edit-user"><i class="fa fa-pencil"></i> <span>Edit</span></a>
						   	</div>
						   	<div class="col-md-12 btncancel">
							   	<a href="#" class="btn btn-primary btn-cancel" data-id="<?php echo $user["rid"]; ?>"><i class="fa fa-eye-slash"></i> <span>Remove Report</span></a>
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
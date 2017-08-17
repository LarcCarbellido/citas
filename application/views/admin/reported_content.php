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
                <strong>Reported Content</strong>
            </li>
        </ol>
    </div>
</div>
<div class="col-lg-12 block_form">
	<div class="row">
		<div class="col-md-4">
			<div class="well block_admin_dash">
				<a href="<?php echo base_url(); ?>admin/reported_users"><i class="fa fa-users"></i></a>
				<div class="desc_admin_block">
					<a href="<?php echo base_url(); ?>admin/reported_users">Reported Users</a>
				</div>
			</div>
		</div>
		<div class="col-md-4">
			<div class="well block_admin_dash">
				<a href="<?php echo base_url(); ?>admin/reported_photos"><i class="fa fa-picture-o"></i></a>
				<div class="desc_admin_block">
					<a href="<?php echo base_url(); ?>admin/reported_photos">Reported Photos</a>
				</div>
			</div>
		</div>
	</div>
</div>
<?php
$this->load->view('templates/footers/admin_footer');
?>
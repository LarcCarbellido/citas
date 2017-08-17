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
                <strong>Dashboard</strong>
            </li>
        </ol>
    </div>
</div>
<div class="col-lg-12 block_form">
	<div class="row">
		<div class="col-md-4">
			<div class="well block_admin_dash">
				<i class="fa fa-area-chart"></i>
				<div class="desc_admin_block">
					<b><?php echo $total_users; ?></b>
					<br />
					Total Users
				</div>
			</div>
		</div>
		<div class="col-md-4">
			<div class="well block_admin_dash">
				<i class="fa fa-user-plus"></i>
				<div class="desc_admin_block">
					<b><?php echo $new_users_today; ?></b>
					<br />
					New Users Today
				</div>
			</div>
		</div>
		<div class="col-md-4">
			<div class="well block_admin_dash">
				<i class="fa fa-dollar"></i>
				<div class="desc_admin_block">
					<b><?php echo $total_purchases; ?></b>
					<br />
					Total Purchases
				</div>
			</div>
		</div>
	</div>
	<hr />
	<div class="row">
		<div class="col-md-4">
			<div class="well block_admin_dash">
				<a href="<?php echo base_url(); ?>admin/settings"><i class="fa fa-cogs"></i></a>
				<div class="desc_admin_block">
					<a href="<?php echo base_url(); ?>admin/settings">Settings</a>
				</div>
			</div>
		</div>
		<div class="col-md-4">
			<div class="well block_admin_dash">
				<a href="<?php echo base_url(); ?>admin/manage_users"><i class="fa fa-users"></i></a>
				<div class="desc_admin_block">
					<a href="<?php echo base_url(); ?>admin/manage_users">Manage Users</a>
				</div>
			</div>
		</div>
		<div class="col-md-4">
			<div class="well block_admin_dash">
				<a href="<?php echo base_url(); ?>admin/reported_content"><i class="fa fa-bullhorn"></i></a>
				<div class="desc_admin_block">
					<a href="<?php echo base_url(); ?>admin/reported_content">Reported Content</a>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-4">
			<div class="well block_admin_dash">
				<a href="<?php echo base_url(); ?>admin/manage_finances"><i class="fa fa-diamond"></i></a>
				<div class="desc_admin_block">
					<a href="<?php echo base_url(); ?>admin/manage_finances">Manage Premium</a>
				</div>
			</div>
		</div>
		<div class="col-md-4">
			<div class="well block_admin_dash">
				<a href="<?php echo base_url(); ?>admin/manage_ads"><i class="fa fa-picture-o"></i></a>
				<div class="desc_admin_block">
					<a href="<?php echo base_url(); ?>admin/manage_ads">Manage Ads</a>
				</div>
			</div>
		</div>
		<div class="col-md-4">
			<div class="well block_admin_dash">
				<a href="<?php echo base_url(); ?>admin/social_profiles"><i class="fa fa-facebook-square"></i></a>
				<div class="desc_admin_block">
					<a href="<?php echo base_url(); ?>admin/social_profiles">Social Profiles</a>
				</div>
			</div>
		</div>
		<div class="col-md-4">
			<div class="well block_admin_dash">
				<a href="<?php echo base_url(); ?>admin/forum"><i class="fa fa-comments"></i></a>
				<div class="desc_admin_block">
					<a href="<?php echo base_url(); ?>admin/forum">Manage Forum</a>
				</div>
			</div>
		</div>
		<div class="col-md-4">
			<div class="well block_admin_dash">
				<a href="<?php echo base_url(); ?>admin/pages"><i class="fa fa-edit"></i></a>
				<div class="desc_admin_block">
					<a href="<?php echo base_url(); ?>admin/pages">Manage Custom Pages</a>
				</div>
			</div>
		</div>
		<div class="col-md-4">
			<div class="well block_admin_dash">
				<a href="<?php echo base_url(); ?>admin/manage_languages"><i class="fa fa-globe"></i></a>
				<div class="desc_admin_block">
					<a href="<?php echo base_url(); ?>admin/manage_languages">Manage Languages</a>
				</div>
			</div>
		</div>
	</div>
</div>
<?php
$this->load->view('templates/footers/admin_footer');
?>
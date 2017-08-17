<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?php echo $title ?></title>
    <meta name="description" content="<?php echo $settings["site_description"]; ?>">
    <meta name="keywords" content="<?php echo $settings["site_tags"]; ?>">

	<link href='http://fonts.googleapis.com/css?family=Titillium+Web' rel='stylesheet' type='text/css'>
    <link href="<?php echo base_url(); ?>css/site/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>css/font-awesome.min.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>css/site/animate.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>css/site/nailthumb.min.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>css/jquery.nouislider.min.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>css/bootstrap-select.min.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>css/bootstrap.colorpickersliders.min.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>css/summernote.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>css/site/style_admin.css" rel="stylesheet">

</head>

<body class="fixed-sidebar no-skin-config skinweb">

    <div id="wrapper">

    <nav class="navbar-default navbar-static-side" role="navigation">
        <div class="sidebar-collapse">
            <ul class="nav" id="side-menu">
                <li class="nav-header">
                    <div class="dropdown profile-element"> 
	                    <span>
                            <a href="<?php echo base_url(); ?>user/profile/<?php echo $this->session->userdata("user_id") ?>"><img alt="image" class="img-circle avatar_left" src="<?php echo base_url() . $this->session->userdata("user_avatar"); ?>" /></a>
                        </span>
						<a href="<?php echo base_url(); ?>">
                        	<span class="clear"> <span class="block m-t-xs"> <strong class="font-bold"><a href="<?php echo base_url(); ?>user/profile/<?php echo $this->session->userdata("user_id") ?>"><?php echo $this->session->userdata("user_username"); ?></a></strong></span>
                        </a>
                    </div>
                    <div class="logo-element">
                        <?php echo $settings["site_name"]; ?>
                    </div>
                </li>
                <li>
                    <a href="<?php echo base_url(); ?>admin"><i class="fa fa-th-large"></i> <span class="nav-label">Dashboard</span></a>
                </li>
                <li>
                    <a href="<?php echo base_url() ?>admin/settings"><i class="fa fa-cogs"></i> <span class="nav-label">Settings</span></a>
                </li>
                <li>
                    <a href="<?php echo base_url() ?>admin/theme"><i class="fa fa-paint-brush"></i> <span class="nav-label">Theme Options</span></a>
                </li>
				<li>
                    <a href="<?php echo base_url() ?>admin/manage_languages"><i class="fa fa-globe"></i> <span class="nav-label">Manage Languages</span></a>
                </li>
                <li>
                    <a href="<?php echo base_url() ?>admin/manage_users"><i class="fa fa-users"></i> <span class="nav-label">Manage Users</span></a>
                </li>
                <li>
                    <a href="<?php echo base_url() ?>admin/user_generator"><i class="fa fa-magic"></i> <span class="nav-label">Users Generator</span></a>
                </li>
                <li>
                    <a href="<?php echo base_url() ?>admin/reported_content"><i class="fa fa-bullhorn"></i> <span class="nav-label">Reported Content</span></a>
                </li>
                <li>
                    <a href="<?php echo base_url() ?>admin/manage_finances"><i class="fa fa-diamond"></i> <span class="nav-label">Manage Premium</span></a>
                </li>
                <li>
                    <a href="<?php echo base_url() ?>admin/manage_ads"><i class="fa fa-picture-o"></i> <span class="nav-label">Manage Ads</span></a>
                </li>
                <li>
                    <a href="<?php echo base_url() ?>admin/forum"><i class="fa fa-comments"></i> <span class="nav-label">Manage Forum</span></a>
                </li>
                <li>
                    <a href="<?php echo base_url() ?>admin/pages"><i class="fa fa-edit"></i> <span class="nav-label">Manage Custom Pages</span></a>
                </li>
                <li>
                    <a href="<?php echo base_url() ?>admin/social_profiles"><i class="fa fa-facebook-square"></i> <span class="nav-label">Social Profiles</span></a>
                </li>
            </ul>

        </div>
    </nav>

	<div id="page-wrapper" class="gray-bg clearfix">
        <div class="row border-bottom">
        	<nav class="navbar navbar-static-top" role="navigation" style="margin-bottom: 0">
		        <div class="navbar-header">
			        <ul class="nav-toolbar">
		                <li class="dropdown"><a href="index.html#" data-toggle="dropdown"><i class="fa fa-bars" style="color:#77777f !important"></i></a>
		                	<div class="dropdown-menu lg pull-left arrow panel panel-default arrow-top-left">
		                    	<div class="panel-heading">
		                        	Admin Menu
		                        </div>
		                        <div class="panel-body text-center">
		                        	<div class="row">
			                        	<div class="col-xs-6 col-sm-4"><a href="<?php echo base_url(); ?>admin/settings" class="text-danger"><span class="h2"><i class="fa fa-cogs"></i></span><p class="text-gray no-margn">Settings</p></a></div>
			                            <div class="col-xs-6 col-sm-4"><a href="<?php echo base_url() ?>admin/manage_users" class="text-brown"><span class="h2"><i class="fa fa-users"></i></span><p class="text-gray">Manage Users</p></a></div>
		
		                                <div class="col-xs-12 visible-xs-block"><hr style="margin-top:1px;margin-bottom: 9px;"></div>
		                                <div class="col-xs-6 col-sm-4"><a href="<?php echo base_url(); ?>admin/reported_users" class="text-green"><span class="h2"><i class="fa fa-bullhorn"></i></span><p class="text-gray no-margn">Reported Users</p></a></div>
		
		                                <div class="col-xs-6 col-sm-4"><a href="<?php echo base_url(); ?>user/manage_finances" class="text-orange"><span class="h2"><i class="fa fa-shopping-cart"></i></span><p class="text-gray no-margn">Payments</p></a></div>
		
		                                
		                                <div class="col-lg-12 col-md-12 col-sm-12  hidden-xs"><hr></div>
		
		                                
		                                <div class="col-xs-12 visible-xs-block"><hr style="margin-top:11px;margin-bottom: 9px;"></div>
		
			                            <div class="col-xs-6 col-sm-4"><a href="<?php echo base_url() ?>admin/manage_ads" class="text-red"><span class="h2"><i class="fa fa-picture-o"></i></span><p class="text-gray">Manage Ads</p></a></div>
		                                <div class="col-xs-6 col-sm-4"><a href="<?php echo base_url() ?>admin/social_profiles" class="text-brown"><span class="h2"><i class="fa fa-facebook-square"></i></span><p class="text-gray no-margn">Social Profiles</p></a></div>
										<div class="col-xs-12 col-sm-12 uploadphoto"><a href="<?php echo base_url() ?>" class="text-red"><p class="text-gray no-margn">Back to the Website</p></a></div>
		
		                            </div>
		                        </div>
		                    </div>
		                </li>
		            </ul>
		            <div class="brand-web brand-admin">
		           		<a href="<?php echo base_url(); ?>"><?php echo $settings["site_name"]; ?></a>
		           		<!-- <span>OKDate Script v<?php echo SCRIPT_VERSION; ?></span> -->
		            </div>
		        </div>
	            <ul class="nav navbar-top-links navbar-right">
	                <li>
	                	<a href="<?php echo base_url(); ?>">
	                        <i class="fa fa-globe"></i> Back to the Website
	                    </a>
	                </li>	
	                <li>
	                    <a href="<?php echo base_url(); ?>user/logout">
	                        <i class="fa fa-sign-out"></i> Log out
	                    </a>
	                </li>
	            </ul>
			</nav>
        </div>
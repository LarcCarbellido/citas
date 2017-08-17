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
			<h3><?php echo $this->lang->line("messages_title"); ?></h3>
	    </div>
	</div>
	<div class="row">
		<div class="main_container">
		    <div class="ibox-content-no-bg">
		    	<div class="alert alert-danger alert-centered">
			    	<?php echo $this->lang->line("no_pm_for_the_moment"); ?>
		    	</div>
		    </div>
		</div>
	</div>
</div>
<?php
$this->load->view('templates/footers/main_footer');
?>
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
			<h3><?php echo $this->lang->line("Languages"); ?></h3>
		</div>
    </div>
	<div class="row">
		<?php
		if(isset($_GET["action"])) {
			if($_GET["action"] == "language_saved") {
			?>
			<div class="alert alert-success alert-center">
				<?php echo $this->lang->line("new_language_saved"); ?>
			</div>
			<?php
			}
		?>
		
		<?php
		}	
		?>
		<div class="main_container">
			<div class="alert alert-info alert-center">
				<?php echo sprintf($this->lang->line("choose_language_info"), $settings["site_name"]); ?>
			</div>
			<?php
			if(sizeof($languages) == 0) {
			?>
			<div class="alert alert-danger alert-center">
				<?php echo $this->lang->line("no_language_options_found"); ?>
			</div>
			<?php
			} else {
			?>
			<ul class="language_option">
				<?php
				foreach($languages as $language):
				?>
				<li><a class="btn btn-primary" href="<?php echo base_url(); ?>user/switch_language/<?php echo $language["id"]; ?>/"><?php echo ucfirst($language["language"]); ?></a></li>
				<?php
				endforeach;
				?>
				<li><a class="btn btn-primary" href="<?php echo base_url(); ?>user/switch_language/0/"><?php echo ucfirst($settings["default_language"]); ?></a></li>
			</ul>
			<?php
			}
			?>
		</div>
	</div>
</div>
<?php
$this->load->view('templates/footers/main_footer');
?>
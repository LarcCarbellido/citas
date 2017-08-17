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
			<h3><?php echo $page->title; ?></h3>
		</div>
    </div>

	<div class="row">
		<div class="main_container">
			<?php
			echo $page->content;	
			?>
		</div>
	</div>
</div>
<?php
$this->load->view('templates/footers/main_footer');
?>
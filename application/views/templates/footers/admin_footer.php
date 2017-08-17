
	
	    <!-- Mainly scripts -->
	    <script src="<?php echo base_url(); ?>js//welcome/jquery.js"></script>
	    <script src="<?php echo base_url(); ?>js/bootstrap.min.js"></script>
		<script src="<?php echo base_url(); ?>js/jquery.nailthumb.min.js"></script>
		<script src="<?php echo base_url(); ?>js/nouislider.min.js"></script>
		<script src="<?php echo base_url(); ?>js/tinycolor.js"></script>
		<script src="<?php echo base_url(); ?>js/bootstrap.colorpickersliders.min.js"></script>

		<script type="text/javascript">
			var base_url = "<?php echo base_url(); ?>";	
		</script>
	    
	    <?php 
		foreach($jscripts as $js):	
		?>
		<script type="text/javascript" src="<?php echo $js; ?>"></script>
		<?php
		endforeach;
		?>
	
	    <!-- Custom and plugin javascript -->
	    <script src="<?php echo base_url(); ?>js/flatui-fileinput.js"></script>
	    <script src="<?php echo base_url(); ?>js/masonry.min.js"></script>
		
		<?php echo $settings["site_analytics"]; ?>
	</body>

</html>
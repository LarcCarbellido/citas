<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Whoops! That's a 404!</title>
	<link href='https://fonts.googleapis.com/css?family=Dancing+Script:400,700' rel='stylesheet' type='text/css'>
	<link href='http://fonts.googleapis.com/css?family=Titillium+Web' rel='stylesheet' type='text/css'>
	<link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300' rel='stylesheet' type='text/css'>
    <link href="/css/site/bootstrap.min.css" rel="stylesheet">
    <link href="/css/font-awesome.min.css" rel="stylesheet">
    <link href="/css/site/animate.css" rel="stylesheet">
    	<link href="/css/owl.carousel.css" rel="stylesheet">
	<link href="/css/owl.theme.css" rel="stylesheet">
	<link href="/css/owl.transitions.css" rel="stylesheet">
    <link href="/css/site/nailthumb.min.css" rel="stylesheet">
    <link href="/css/blueimp-gallery.min.css" rel="stylesheet">
    <link href="/css/vendor/jquery.sidr.dark.css" rel="stylesheet">
    <link href="/css/jquery.nouislider.min.css" rel="stylesheet">
    <link href="/css/site/style.css" rel="stylesheet">
    
    <style>
		.container {
			text-align: center;
			color: #FFF;
			max-width: 750px;
		} 
		
		img {
			margin-top: 20px;
			height: 280px !important;
			display: inline-block !important;
		}
		
		hr {
			background: rgba(255,255,255,0.7);
		    border: medium none;
		    height: 2px;
		}   
	</style>
</head>
<body>
	<div class="container">
		<img src="http://crea.io/images/okdate/404heart.png" class="img-responsive" alt="404" />
		<h1><?php echo $heading; ?></h1>
		<?php echo $message; ?>
		<hr />
		<a href="/" class="btn btn-primary">Back to the Website</a>
	</div>
</body>
</html>
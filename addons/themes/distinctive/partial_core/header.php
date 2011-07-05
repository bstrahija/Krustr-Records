<!doctype html>
<!--[if lt IE 7 ]> <html lang="en" class="no-js ie6"> <![endif]-->
<!--[if IE 7 ]>    <html lang="en" class="no-js ie7"> <![endif]-->
<!--[if IE 8 ]>    <html lang="en" class="no-js ie8"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--> <html lang="en" class="no-js"> <!--<![endif]-->
<head>
	<meta charset="utf-8">
	
	<?php partial('meta_tags'); ?>
	
	<link rel="stylesheet" href="<?php echo theme_url(); ?>/assets/css/init.css?v=1.0">
	<link rel="stylesheet" href="<?php echo theme_url(); ?>/assets/css/style.css?v=1.0">
	<link rel="stylesheet" href="<?php echo theme_url(); ?>/assets/css/post.css?v=1.0">
	<script src="<?php echo theme_url(); ?>/assets/js/libs/modernizr-1.6.min.js"></script>
</head>
<body class="pg-home">
<div id="layout">
	<header id="hd1">
		<nav id="top">
			<a href="<?php echo site_url('login'); ?>">Login</a> &bull;
			<a href="#">Connect with Facebook</a>
		</nav>
		
		<hgroup id="logo">
			<h1><a href="<?php echo site_url(); ?>"><?php echo site_name(); ?></a></h1>
		</hgroup>

		<nav id="nav">
			<ul>
				<li><a href="<?php echo site_url(); ?>">Home</a></li>
				<li><a href="<?php echo site_url('about'); ?>">About</a></li>
				<li><a href="<?php echo site_url('blog'); ?>">Blog</a></li>
				<li><a href="<?php echo site_url('deals'); ?>">Deals</a></li>
				<li><a href="<?php echo site_url('contact'); ?>">Contact</a></li>
			</ul>
		</nav>
	</header>
	<div class="line"></div>
	
	<div id="main">

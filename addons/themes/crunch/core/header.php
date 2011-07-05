<!doctype html>
<!--[if lt IE 7 ]> <html lang="en" class="no-js ie6"> <![endif]-->
<!--[if IE 7 ]>    <html lang="en" class="no-js ie7"> <![endif]-->
<!--[if IE 8 ]>    <html lang="en" class="no-js ie8"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--> <html lang="en" class="no-js"> <!--<![endif]-->
<head>
	<meta charset="utf-8">
	
	{{> meta_tags}}
	
	<link rel="stylesheet" href="{{theme_url}}assets/css/init.css?v=1.0">
	<link rel="stylesheet" href="{{theme_url}}assets/css/style.css?v=1.0">
	<link rel="stylesheet" href="{{theme_url}}assets/css/libs/nivo-slider.css?v=1.0">
	<link rel="stylesheet" href="{{theme_url}}assets/css/libs/ddsmoothmenu.css?v=1.0">
	<link rel="stylesheet" href="{{theme_url}}assets/css/libs/prettyPhoto.css?v=1.0">
	<link rel="stylesheet" href="{{theme_url}}assets/css/post.css?v=1.0">
	
	<link href="http://fonts.googleapis.com/css?family=Lobster" rel="stylesheet" type="text/css">
	<link href="http://fonts.googleapis.com/css?family=Cardo" rel="stylesheet" type="text/css">
	
	<script src="{{theme_url}}assets/js/libs/modernizr-1.6.min.js"></script>
</head>
<body class="pg-home">
<div id="layout">
	<div id="wrap">
		<header id="hd1">
			<hgroup id="logo-txt">
				<h1><a href="<?php echo site_url(); ?>"><?php echo site_name(); ?></a></h1>
				<h3><?php echo site_slogan(); ?></h3>
			</hgroup>
			<!-- /#logo-txt -->
			
			{{> navigation}}
			
			<nav id="top-social">
				<ul>
					<li><a href="http://www.twitter.com/creolab" class="twitter" title="Follow Us on Twitter!">Follow Us on Twitter!</a></li>
					<li><a href="#" class="facebook" title="Join Us on Facebook!">Join Us on Facebook!</a></li>
					<li><a href="#" title="RSS" class="rss">Subcribe to Our RSS Feed</a></li>
				</ul>
			</nav>
			<!-- /#top-social -->
			
			<div id="top-search">
				{{> search_form}}
			</div>
			<!-- /#top-search -->
		</header>
		<!-- /#hd1 -->
		
		<section id="content">

<!doctype html>
<!--[if lt IE 7]> <html class="no-js ie6" lang="hr" xmlns:fb="http://www.facebook.com/2008/fbml"> <![endif]-->
<!--[if IE 7]>    <html class="no-js ie7" lang="hr" xmlns:fb="http://www.facebook.com/2008/fbml"> <![endif]-->
<!--[if IE 8]>    <html class="no-js ie8" lang="hr" xmlns:fb="http://www.facebook.com/2008/fbml"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="hr"> <!--<![endif]-->
<head>
	<meta charset="utf-8">
	<title>Krusts Records</title>

	<?php partial('meta_tags'); ?>
	
	<!-- Grab Google CDN's jQuery. fall back to local if necessary -->
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.js"></script>
	<script>!window.jQuery && document.write(unescape('%3Cscript src="js/libs/jquery-1.4.2.js"%3E%3C/script%3E'))</script>

	<?php partial('assets_css'); ?>
</head>

<!-- paulirish.com/2008/conditional-stylesheets-vs-css-hacks-answer-neither/ -->

<body>
<div id="wrap">
	<header>
		<h1><a href="<?php echo site_url(); ?>">Krusts Records</a></h1>
		<nav>
			<ul>
				<li<?php echo (is_home()) ? 								' class="on"' : ''; 	?>><?php echo anchor('', 			'Home'); 		?></li>
				<li<?php echo (is_entry('about-us')) ? 						' class="on"' : ''; 	?>><?php echo anchor('about-us', 	'About'); 		?></li>
				<li<?php echo (is_entry('blog')) ? 							' class="on"' : ''; 	?>><?php echo anchor('blog', 		'Blog'); 		?></li>
				<li<?php echo (is_entry('artists') 	|| is_entry('artist')) ? ' class="on"' : ''; 	?>><?php echo anchor('artists', 	'Artists'); 	?></li>
				<li<?php echo (is_entry('albums') 	|| is_entry('album')) ? 	' class="on"' : ''; 	?>><?php echo anchor('albums', 		'Releases'); 	?></li>
				<li<?php echo (is_entry('contact')) ? 						' class="on"' : ''; 	?>><?php echo anchor('contact', 	'Contact'); 	?></li>
			</ul>
		</nav>
		<img class="hdimg" src="<?php echo assets_url('img/head.png'); ?>" alt="" width="224" height="220">
	</header>
	
	<hr>
	
	<div id="main">
<?php
	// Get meta tags
	if (isset($entry)) :
		$meta_title 		= @$entry->meta_title;
		$meta_description 	= @$entry->meta_description;
		$meta_keywords 		= @$entry->meta_keywords;
	endif;
	
	// Try to override with defaults
	if ( ! @$meta_title) 		$meta_title 		= 'Krust Records';
	if ( ! @$meta_description) 	$meta_description 	= 'Demo web site for the Krustr CMS.';
	if ( ! @$meta_keywords) 	$meta_keywords 		= 'krustr, cms, creo, strahija, strija';
	
?><meta name="robots" content="all">
	<meta name="copyright" content="Creo">
	
	<meta name="title" content="<?php echo $meta_title; ?>">
	<meta name="keywords" content="<?php echo $meta_keywords; ?>">
	<meta name="description" content="<?php echo $meta_description; ?>">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
	<meta name="MSSmartTagsPreventParsing" content="true">
	
	<!-- <meta name="viewport" content="width=device-width; initial-scale=auto"> -->
	<link rel="icon" href="<?php echo $this->theme->path('favicon.ico'); ?>" type="image/x-icon">
	<link rel="shortcut icon" href="<?php echo $this->theme->path('favicon.ico'); ?>" type="image/x-icon">
	<link rel="apple-touch-icon" href="<?php echo $this->theme->path('apple-touch-icon.png'); ?>">
	<link rel="author" href="http://www.creolab.hr/">
	<meta name="generator" content="krustr - creative content framework">
	
	<?php //echo facebook_opengraph_meta(); ?>

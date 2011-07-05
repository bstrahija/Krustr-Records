<?php
	// Site title
	$site_title = $site_name;
	
	if (isset($content->entry)) {
		$site_title = $content->entry->title.' | '.$site_name;
		if ( ! $meta_title) $meta_title = $content->entry->title.' | '.$site_name;
	}
	elseif (is_channel() and $channel) {
		if (is_array($channel)) $channel = array_to_object($channel);
		$site_title = $channel->title.' | '.$site_name;
	}
	else {
		if ( ! $meta_title) $meta_title = $site_name;
	} // end if
	
	set_var('site_title',       $site_title);
	set_var('meta_title',       $meta_title);
	set_var('meta_keywords',    $meta_keywords);
	set_var('meta_description', $meta_description);
?>

	<title><?php echo $site_title; ?></title>
	<meta name="robots" content="all">
	<meta name="copyright" content="Creo, Boris Strahija">
	
	<meta name="title" content="<?php echo $meta_title; ?>">
	<meta name="keywords" content="<?php echo $meta_keywords; ?>">	
	<meta name="description" content="<?php echo $meta_description; ?>">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
	<meta http-equiv="imagetoolbar" content="no">
	<meta name="MSSmartTagsPreventParsing" content="true">

	<link rel="author" href="http://www.creolab.hr/">
	<meta name="generator" content="krustr - creative content framework">
	
	<link rel="alternate" type="application/rss+xml" title="Creo RSS" href="<?php echo site_url('feed/rss'); ?>">
	

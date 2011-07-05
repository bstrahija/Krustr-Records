<!doctype html>
<html lang="en" class="<?php echo browser_class(); ?>">
<head>
	<meta charset="utf-8">
	<title>Krustr&deg;</title>
	<meta name="description" content="Krustr Content Framework">
	<meta name="author" content="Boris Strahija, Creo">
	<link rel="icon" href="<?php echo admin_url('assets/images/favicon.ico'); ?>" type="image/x-icon"> 
	<link rel="shortcut icon" href="<?php echo admin_url('assets/images/favicon.ico'); ?>" type="image/x-icon"> 
	<link rel="apple-touch-icon" href="<?php echo admin_url('assets/images/apple-touch-icon.png'); ?>"> 
	
	<script>
	var site_url 		= "<?php echo site_url(); ?>";
	var base_url 		= "<?php echo base_url(); ?>";
	var current_url 	= "<?php echo current_url(); ?>";
	var app_url 		= "<?php echo site_url(APPPATH).'/'; ?>";
	var admin_url 		= "<?php echo site_url(BACKEND); ?>/";
	var rich_editor 	= "<?php echo $this->config->item('rich_editor'); ?>";
	var csrf_token_name = "<?php if (isset($this->securoty)) echo $this->security->csrf_token_name; ?>";
	var csrf_hash 		= "<?php if (isset($this->securoty)) echo $this->security->csrf_hash; ?>";
	var multilang 	    = <?php echo (int) $this->config->item('multilang', 'krustr'); ?>;
	
	var nav_mark_1 = "";
	var nav_mark_2 = "";
	var nav_mark_3 = "";
	var nav_mark_4 = "";
	
	<?php if (isset($channel)) : ?>
		var channel = "<?php echo $channel; ?>";
	<?php endif; ?>
	</script>
	
	<?php $this->load->view('inc/default/assets'); ?>
</head>
<body id="krustr">
<div id="page-loader"><p>Loading...</p></div>

<?php $this->load->view('inc/default/notifications'); ?>

<header id="hd1" class="wsub"><div class="w clearfix">
	<hgroup id="logo">
		<h1><a href="<?php echo site_url(); ?>" title="Live site"><em class="mnml">|</em>Demo</a></h1>
	</hgroup>
	
	<hgroup id="user-info">
		<div class="avatar"><a href="<?php echo admin_url('users/me'); ?>" title="Profile"><img src="<?php echo gravatar(user_email(), 'X', 32, site_url('system/assets/images/avatar_dummy.png')); ?>" width="32" height="32" alt="Boris Strahija"></a></div>
		<div class="greeting">
			<em class="picto user"></em> Hello, <strong><a href="<?php echo admin_url('users/me'); ?>" class="account" title="Profile"><?php echo user_var('display_name'); ?></a></strong><br>
			<a href="<?php echo admin_url('authentication/logout'); ?>" class="logout" title="Logout">Logout <em class="picto locked"></em></a>
		</div>
	</hgroup>
	
	<?php //$this->load->view('inc/default/lang'); ?>
	
	<?php $this->load->view('inc/default/nav'); ?>
</div></header>

<div id="layout">
	<div id="scene">
	
	<?php $this->load->view('inc/default/subnav'); ?>
	
	




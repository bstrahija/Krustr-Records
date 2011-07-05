<!DOCTYPE html>
<html>
<head>
	<title>Krustr&deg;</title>
	<meta charset="uft-8">
	
	<script>
	var site_url 		= "<?php echo site_url(); ?>";
	var base_url 		= "<?php echo base_url(); ?>";
	var current_url 	= "<?php echo current_url(); ?>";
	var app_url 		= "<?php echo site_url(APPPATH).'/'; ?>";
	var admin_url 		= "<?php echo site_url(BACKEND); ?>/";
	var rich_editor 	= "<?php echo $this->config->item('rich_editor', 'krustr'); ?>";
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
	
	<?php $this->load->view('inc/default/assets') ?>
</head>
<body id="login">
<div class="wrap">
	<h1>
		<em><b>&gt;</b> Krustr <b>&lt;</b></em>
		<span>Creative Administration</span>
	</h1>
	
	
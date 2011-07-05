<!--[if lt IE 9]>
<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->

<?php //clear_cache(); ?>

<?php display_css(array(
	'init.less',
	'def.less',
	//'libs/iconic.css',
	'libs/mnml.css',
	'libs/pictos.css',
	'libs/visualize.css',
	'libs/ui.themes/ui-darkness/jquery-ui-1.8.13.custom.css',
	'libs/uniform.default.css',
	'libs/uniform.agent.css',
	'libs/tiptip.css',
	'libs/jquery.fancybox-1.3.4.css',
	'libs/jquery-ui-timepicker.css',
	'libs/jquery.jcrop.css',
	'libs/apprise.min.css',
	'header_footer.less',
	'nav.less',
	'dashboard.less',
	'grid.less',
	'aside.less',
	'style.less',
	'forms.less',
	'fields.less',
	'tabs.less',
	'notices.less',
	'login.less',
	'debug.less',
	'post.less',
)); ?>

<?php display_js(array(
	'libs/jquery-1.6.1.js',
	'libs/jquery.jdpicker.js',
	'libs/jquery.visualize.js',
	'libs/jquery.visualize.tooltip.js',
	'libs/jquery.ui/jquery-ui-1.8.13.custom.min.js',
	'libs/jquery.tiptip.min.js',
	'libs/jquery.form.js',
	'libs/jquery.fancybox-1.3.4.js',
	'libs/jquery.uniform.js',
	'libs/jquery.ui.timepicker.js',
	'libs/swfobject.js',
	'libs/jcrop.js',
	'libs/apprise-1.5.min.js',
	'plugins.js',
	'script.js',
	'grid.js',
)); ?>

<?php $this->load->view('inc/default/assets_rich_editor') ?>


<script src="<?php echo site_url('system/assets/js/libs/file-upload/fileuploader.js'); ?>"></script>
<link rel="stylesheet" href="<?php echo site_url('system/assets/js/libs/file-upload/fileuploader.css'); ?>">





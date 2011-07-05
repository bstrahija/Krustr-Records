<?php if ($this->config->item('rich_editor') == 'jwysiwyg') : ?>
	<script src="<?php echo site_url('system/assets/js/libs/jwysiwyg/jquery.wysiwyg.js'); ?>"></script>
	<script src="<?php echo site_url('system/assets/js/libs/jwysiwyg/plugins/wysiwyg.fullscreen.js'); ?>"></script>
	<script src="<?php echo site_url('system/assets/js/libs/jwysiwyg/plugins/wysiwyg.fileManager.js'); ?>"></script>
	<script src="<?php echo site_url('system/assets/js/libs/jwysiwyg/plugins/wysiwyg.rmFormat.js'); ?>"></script>
	<script src="<?php echo site_url('system/assets/js/libs/jwysiwyg/controls/wysiwyg.link.js'); ?>"></script>
	<script src="<?php echo site_url('system/assets/js/libs/jwysiwyg/controls/wysiwyg.colorpicker.js'); ?>"></script>
	<script src="<?php echo site_url('system/assets/js/libs/jwysiwyg/controls/wysiwyg.table.js'); ?>"></script>
	<script src="<?php echo site_url('system/assets/js/libs/jwysiwyg/controls/wysiwyg.image.js'); ?>"></script>
	<link rel="stylesheet" href="<?php echo site_url('system/assets/js/libs/jwysiwyg/jquery.wysiwyg.css'); ?>">
	<link rel="stylesheet" href="<?php echo site_url('system/assets/js/libs/jwysiwyg/jquery.wysiwyg.modal.css'); ?>">
	<link rel="stylesheet" href="<?php echo site_url('system/assets/js/libs/jwysiwyg/jquery.wysiwyg.override.css'); ?>">

<?php elseif ($this->config->item('rich_editor') == 'ckeditor') : ?>
	<script src="<?php echo site_url('system/assets/js/libs/ckeditor/ckeditor.js'); ?>"></script>
	<script src="<?php echo site_url('system/assets/js/libs/ckeditor/adapters/jquery.js'); ?>"></script>

<?php elseif ($this->config->item('rich_editor') == 'markdown') : ?>
	<link rel="stylesheet" href="<?php echo site_url('system/assets/js/libs/markitup/skins/simple/style.css'); ?>">
	<link rel="stylesheet" href="<?php echo site_url('system/assets/js/libs/markitup/sets/markdown/style.css'); ?>">
	<script src="<?php echo site_url('system/assets/js/libs/markitup/jquery.markitup.js'); ?>"></script>
	<script src="<?php echo site_url('system/assets/js/libs/markitup/sets/markdown/set.js'); ?>"></script>
	
<?php endif; ?>
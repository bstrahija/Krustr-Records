<div class="image-uploader" id="image-uploader-<?php echo $field_id; ?>">
	<div class="clearfix">
		<div class="preview">
			<?php if (@$content) : ?>
				<?php $this->load->view('content/widgets/uploader_image_preview', array('content'=>$content)) ?>
			<?php else : ?>
				<div class="img none"><em class="picto photo"></em></div>
				<div class="desc"><p>No image uploaded.</p></div>
			<?php endif; ?>
		</div>
		
		<?php if (@$content and is_superadmin()) : // !TODO (have to find a way to store title, description etc.) ?>
		<div class="image-details inner-form">
			<h4>Image Details</h4>
			<label for="image-<?php echo $field_id ?>-title">Title</label>
			<input type="text" id="image-<?php echo $field_id ?>-title" name="image-<?php echo $field_id ?>-title"><br><br>
			<label for="image-<?php echo $field_id ?>-description">Description</label>
			<textarea id="image-<?php echo $field_id ?>-description" name="image-<?php echo $field_id ?>-description"></textarea>
		</div>
		<!-- /.gallery-details -->
		<?php endif; ?>
	</div>

	<div class="image-uploader-box clearfix">
		<h4>Upload Image</h4>
		<input class="image-uploader-input" id="file-upload-<?php echo $field_id; ?>" name="file_upload" type="file" />
	</div>
	<!-- /.uploader -->
	<br>
	
	<div class="upload-btn-c upload-btn-c-<?php echo $field_id; ?> clearfix">
		<a class="upload-btn upload-btn-<?php echo $field_id; ?>" href="javascript:$('#file-upload-<?php echo $field_id; ?>').uploadifyUpload();"><em class="picto upload"></em> Upload Files</a> 
	</div>
</div>
<!-- /.image-uploader -->

<script>
$(function() {
	$('#file-upload-<?php echo $field_id; ?>').uploadify({
		uploader      : app_url+'assets/flash/uploadify.swf',
		script        : site_url+'system/third_party/uploadify/uploadify.php',
		checkScript   : site_url+'system/third_party/uploadify/check.php',
		cancelImg     : app_url+'assets/images/cancel.png',
		folder        : '<?php echo reduce_double_slashes($_SERVER['DOCUMENT_ROOT'].'/uploads/tmp'); ?>',
		auto          : false,
		multi         : false,
		fileExt       : '*.jpg;*.gif;*.png;*.jpeg',
		fileDesc      : 'Image Files',
		buttonText    : 'Browse',
		buttonImg     : app_url+'assets/images/btn_browse.png',
		width         : 152,
		height        : 36,
		sizeLimit     : 10*1024*1024, // 10 MB
		simUploadLimit: 1,
		onComplete    : function(a, b, c, file_path, e, f) {
			$(".upload-btn-c-<?php echo $field_id; ?>").fadeOut();
			log(file_path);
			log(c.name);
			process_uploaded_image(file_path, c.name, <?php echo $entry_id; ?>, <?php echo $field_id; ?>);
		},
		onSelectOnce : function() {
			$(".upload-btn-c-<?php echo $field_id; ?>").fadeIn();
		}
	});
});
</script>
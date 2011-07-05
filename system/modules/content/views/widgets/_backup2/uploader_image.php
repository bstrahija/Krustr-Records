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
		<br><br><br><br><br><br><br><br>
	</div>
	<!-- /.uploader -->
	<br>
	
	<div class="upload-btn-c upload-btn-c-<?php echo $field_id; ?> clearfix">
		<a class="upload-btn upload-btn-<?php echo $field_id; ?>" href="javascript:$('#file-upload-<?php echo $field_id; ?>').uploadifyUpload();"><em class="picto upload"></em> Upload Files</a> 
	</div>
</div>
<!-- /.image-uploader -->


<script>
$(function () {
	var $upload_container = $('<div id="ext-file-upload-<?php echo $field_id; ?>" style="border: 1px solid #000; margin: 20px; padding: 10px; background: #eee;">123</div>');
	$("body").prepend($upload_container);
	$.get('<?php echo admin_url('content/uploader_image_tpl/'.$entry_id.'/'.$field_id); ?>', function(data) {
		$upload_container.html(data);
		$('#fileupload-<?php echo $field_id; ?>').fileupload();

	});
});
</script> 
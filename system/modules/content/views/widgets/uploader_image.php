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
			<input type="text" value="<?php echo @$field_content->title; ?>" id="image-<?php echo $field_id ?>-title" name="image-<?php echo $field_id ?>-title"><br><br>
			<label for="image-<?php echo $field_id ?>-description">Description</label>
			<textarea id="image-<?php echo $field_id ?>-description" name="image-<?php echo $field_id ?>-description"><?php echo @$field_content->description; ?></textarea>
		</div>
		<!-- /.gallery-details -->
		<?php endif; ?>
	</div>

	<div class="image-uploader-box clearfix">
		<h4>Upload Image</h4>
		<div id="file-uploader-<?php echo $field_id; ?>">       
		    <noscript>          
		        <p>Please enable JavaScript to use file uploader.</p>
		    </noscript>         
		</div>
	</div>
	<!-- /.uploader -->
	<br>
</div>
<!-- /.image-uploader -->

<script>
$(function() {
	var uploader_<?php echo $field_id; ?> = new qq.FileUploader({
		element: document.getElementById('file-uploader-<?php echo $field_id; ?>'),
		action:  '<?php echo admin_url('upload/image/'.$entry_id.'/'.$field_id); ?>',
		params: {
			entry_id: '<?php echo $entry_id; ?>',
			field_id: '<?php echo $field_id; ?>'
		},
		allowedExtensions: ['jpg', 'jpeg', 'png', 'gif'],
		debug: true,
		onSubmit:   function(id, fileName) {},
		onProgress: function(id, fileName, loaded, total) { log("Uploaded "+loaded+" of "+total) },
		onComplete: function(id, fileName, responseJSON) {
			$("#image-uploader-<?php echo $field_id; ?> .preview").load('<?php echo admin_url('upload/preview_image/'.$entry_id.'/'.$field_id) ?>');
		},
		onCancel:   function(id, fileName) {}
	}); 
});
</script>
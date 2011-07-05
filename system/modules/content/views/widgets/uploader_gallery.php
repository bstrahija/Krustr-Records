<div class="gallery-uploader" id="gallery-uploader-<?php echo $field_id; ?>">
	<div class="preview">
		<?php if (@$content) : ?>
			<?php $this->load->view('content/widgets/uploader_gallery_preview', array('content'=>$content)) ?>
		<?php else : ?>
			<div class="no-img">
				<div class="img"><em class="picto photo"></em></div>
				<div class="desc"><p>No images uploaded.</p></div>
			</div>
		<?php endif; ?>
	</div>
	
	<?php if (is_superadmin()) : // !TODO (have to find a way to store title, description etc.) ?>
	<div class="gallery-details inner-form">
		<h4>Gallery Details</h4>
		<label for="gallery-<?php echo $field_id ?>-title">Title</label>
		<input type="text" id="gallery-<?php echo $field_id ?>-title" name="gallery-<?php echo $field_id ?>-title" value="<?php echo @$gallery->title; ?>"><br><br>
		<label for="gallery-<?php echo $field_id ?>-description">Description</label>
		<textarea id="gallery-<?php echo $field_id ?>-description" name="gallery-<?php echo $field_id ?>-description"><?php echo @$gallery->body; ?></textarea>
	</div>
	<!-- /.gallery-details -->
	<?php endif; ?>
	
	<div class="gallery-uploader-box clearfix">
		<h4>Upload Images</h4>
		<div id="file-uploader-<?php echo $field_id; ?>">       
		    <noscript>          
		        <p>Please enable JavaScript to use file uploader.</p>
		    </noscript>         
		</div>
	</div>
	<!-- /.uploader -->
	<br>
</div>
<!-- /.gallery-uploader -->



<script>
$(function() {
	var uploader_<?php echo $field_id; ?> = new qq.FileUploader({
		element: document.getElementById('file-uploader-<?php echo $field_id; ?>'),
		action:  '<?php echo admin_url('upload/gallery/'.$entry_id.'/'.$field_id); ?>',
		params: {
			entry_id: '<?php echo $entry_id; ?>',
			field_id: '<?php echo $field_id; ?>'
		},
		allowedExtensions: ['jpg', 'jpeg', 'png', 'gif'],
		debug: true,
		onSubmit:   function(id, fileName) {},
		onProgress: function(id, fileName, loaded, total) { log("Uploaded "+loaded+" of "+total) },
		onComplete: function(id, fileName, responseJSON) {
			log("Complete: "+id);
			$("#gallery-uploader-<?php echo $field_id; ?> .preview").load('<?php echo admin_url('upload/preview_gallery/'.$entry_id.'/'.$field_id) ?>');
		},
		onCancel:   function(id, fileName) {}
	}); 
});
</script>
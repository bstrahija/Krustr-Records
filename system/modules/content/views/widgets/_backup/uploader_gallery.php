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
		<input class="gallery-uploader-input" id="file-upload-<?php echo $field_id; ?>" name="file_upload" type="file" />
	</div>
	<!-- /.uploader -->
	<br>
	
	<div class="upload-btn-c upload-btn-c-<?php echo $field_id; ?> clearfix">
		<a class="upload-btn upload-btn-<?php echo $field_id; ?>" href="javascript:$('#file-upload-<?php echo $field_id; ?>').uploadifyUpload();"><em class="picto upload"></em> Upload Files</a> 
	</div>
</div>
<!-- /.gallery-uploader -->



<script>
var gallery_items_<?php echo $field_id; ?> = [];

$(function() {
	$('#file-upload-<?php echo $field_id; ?>').uploadify({
		uploader      : app_url+'assets/flash/uploadify.swf',
		script        : site_url+'system/third_party/uploadify/uploadify.php',
		checkScript   : site_url+'system/third_party/uploadify/check.php',
		cancelImg     : app_url+'assets/images/cancel.png',
		folder        : '/uploads/tmp',
		auto          : false,
		multi         : true,
		fileExt       : '*.jpg;*.gif;*.png;*.jpeg',
		fileDesc      : 'Image Files',
		buttonText    : 'Browse',
		buttonImg     : app_url+'assets/images/btn_browse.png',
		width         : 152,
		height        : 36,
		sizeLimit     : 10*1024*1024, // 10 MB
		simUploadLimit: 1,
		onComplete    : function(a, b, c, file_path, e, f) {
			var tmp_obj = {
				'file_path': file_path,
				'file_name': c.name,
				'entry_id': <?php echo $entry_id; ?>,
				'field_id': <?php echo $field_id; ?>
			}
			gallery_items_<?php echo $field_id; ?>.push(tmp_obj);
			$(".upload-btn-c-<?php echo $field_id; ?>").fadeOut();
		},
		onAllComplete : function(a, b, c, d, e, f, g) {
			process_uploaded_images(gallery_items_<?php echo $field_id; ?>, <?php echo $entry_id; ?>, <?php echo $field_id; ?>);
		},
		onError: function (a, b, c, d) {
         if (d.status == 404)
            alert('Could not find upload script. Use a path relative to: '+'<?= getcwd() ?>');
         else if (d.type === "HTTP")
            alert('error '+d.type+": "+d.status);
         else if (d.type ==="File Size")
            alert(c.name+' '+d.type+' Limit: '+Math.round(d.sizeLimit/1024)+'KB');
         else
            alert('error '+d.type+": "+d.text);
            
            log(d);
		},
		onSelectOnce : function() {
			$(".upload-btn-c-<?php echo $field_id; ?>").fadeIn();
		}
	});
});
</script>
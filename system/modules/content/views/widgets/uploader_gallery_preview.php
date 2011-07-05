<div class="imgs"><ul>
	<h4>Images</h4>
	<?php foreach ($gallery_images as $image) : ?>
		<li>
			<a class="img lightbox" rel="field-group" href="<?php echo site_url($image->file_path); ?>"><img src="<?php echo image_thumb($image->file_path, 64, 64); ?>" alt="" width="64" height="64" title="<?php echo htmlspecialchars($image->title); ?>" alt="<?php echo htmlspecialchars($image->title); ?>"></a>
			<a class="act remove" href="<?php echo admin_url('upload/remove_gallery_image/'.$image->id); ?>" data-remote="upload/remove_gallery_image/<?php echo $image->id; ?>" data-refresh="#gallery-uploader-<?php echo $field_id; ?> .preview" data-confirm="Are you sure you want to delete this image?"><em class="picto remove"></em> Remove</a>
			<a class="act edit lightbox-ajax" href="<?php echo admin_url('upload/edit_gallery_image/'.$image->id); ?>"><em class="picto pencil"></em> Edit</a>
		</li>
	<?php endforeach; ?>
</ul></div>


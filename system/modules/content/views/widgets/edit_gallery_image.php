<div class="popup popup-edit-image">
	<?php echo form_open(current_url(), null, array('image_id'=>$image->id)); ?>
		<div class="img"><img src="<?php echo image_thumb($image->file_path, 64, 64); ?>" alt="" width="64" height="64"></div>
		<div class="frm">
			<p class="r">
				<label>Title</label>
				<input type="text" name="title" value="<?php echo @$image->title; ?>">
			</p>
			<p class="btns"><input type="submit" value="save" class="btn"></p>
		</div>
	<?php echo form_close(); ?>
</div>

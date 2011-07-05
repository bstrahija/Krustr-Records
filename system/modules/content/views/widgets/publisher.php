<div class="widget publisher">
	<h2>Publisher</h2>
	
	<dl class="data">
		<?php if (isset($entry)) : ?>
			<dt>Status:</dt>
				<dd class="publish-status">
					<em class="val"><?php echo ucfirst($entry->status); ?></em>
					<a href="#" class="act toggle"><em class="picto pencil"></em></a>
					<div class="slide">
						<select name="entry-status-pick" class="entry-status-pick">
							<option value="published">Published</option>
							<option value="draft">Draft</option>
						</select>
					</div>
					<!-- /.slide -->
				</dd>
			<dt>Created:</dt>
				<dd><em class="val"><?php echo date('Y/m/d H:i', $entry->created_at); ?></em></dd>
			<dt>Updated:</dt>
				<dd><em class="val"><?php echo date('Y/m/d H:i', $entry->updated_at); ?></em></dd>
			
			<?php if ($entry->status == 'published') : ?>
				<dt>Published:</dt>
					<dd class="published"><em class="val"><?php echo date('Y/m/d H:i', $entry->published_at); ?></em>
						<a href="#" class="act toggle"><em class="picto pencil"></em></a>
						<div class="slide">
							<input type="text" name="published_at_date" value="<?php echo date('Y/m/d', $entry->published_at); ?>" class="datepicker pick-publish-date">
							<input type="text" name="published_at_time" value="<?php echo date('H:i', $entry->published_at); ?>" class="timepicker pick-publish-time">
						</div>
						<!-- /.slide -->
					</dd>

			<?php endif; ?>
		
		<?php else : ?>
			<dt>Status:</dt>
				<dd><em>Draft</em></dd>
			<dt>Publish:</dt>
				<dd class="published">
					<em class="val">Immediately</em>
					<a href="#" class="act toggle"><em class="picto pencil"></em></a>
					<div class="slide">
						<input type="text" name="published_at_date" value="<?php echo date('Y/m/d'); ?>" class="datepicker pick-publish-date">
						<input type="text" name="published_at_time" value="<?php echo date('H:i'); ?>" class="timepicker pick-publish-time">
					</div>
					<!-- /.slide -->
				</dd>
			
		<?php endif; ?>
	</dl>
	
	<div class="btns btns-default">
		<a href="#" class="btn btn-save"><em class="mnml">S</em> Save</a>
		
		<?php if (isset($entry)) : ?>
			<a href="<?php echo site_url('preview/show/'.$entry->id); ?>" class="btn btn-preview preview"><em class="mnml">r</em> Preview</a><hr>
		<?php endif; ?>
		
		<a href="<?php echo admin_url('content/all/'.$channel->slug); ?>" class="btn btn-cancel cancel"><em class="picto">D</em> Cancel</a>
	</div>
	<!-- /.btns -->
	
	<?php if (isset($entry) and $entry->status != 'published' or ( ! isset($entry))) : ?>
	<div class="btns btns-publish">
		<hr>
		<a href="#" class="btn btn-publish"><em class="picto publish"></em> Publish</a>
	</div>
	<!-- /.btns -->
	<?php endif; ?>
		
</div>
<!-- /.publisher -->


<script>
$(function() {
	// Toggle the date edit
	$(".publisher .published .toggle").click(function(e) {
		$(this).parent().find(".slide").toggle();
		e.preventDefault();
	});
	
	// Change the publish date
	$(".pick-publish-date, .pick-publish-time").change(function() {
		update_publish_date();
		return false;
	});
	
	
	// ----------------
	
	// Toggle the status edit
	$(".publisher .publish-status .toggle").click(function(e) {
		$(this).parent().find(".slide").toggle();
		e.preventDefault();
	});
	
	// Change the publish status
	$(".entry-status-pick").change(function() {
		$(".publish-status em.val").text( $(".entry-status-pick option:selected").text() );
		$(".content-form input[name=status]").val( $(".entry-status-pick").val() );
		return false;
	});
	
	// ----------------
	
	// Save action
	$(".publisher .btn-save").click(function(e) {
		$(".content-form input[type=submit]").trigger("click");
		e.preventDefault();
	});
	
	// ----------------
	
	// Publish action
	$(".publisher .btn-publish").click(function(e) {
		$(".content-form input[name=status]").val("published");
		$(".content-form input[type=submit]").trigger("click");
		e.preventDefault();
	});
	
});



function update_publish_date() {
	var this_date = $(".pick-publish-date").val();
	var this_time = $(".pick-publish-time").val();
	console.log(this_date);
	console.log(this_time);
	$(".content-form input[name=published_at]").val(this_date + " " + this_time);
	$(".publisher .published .val").text(this_date + " " + this_time);
}
</script>